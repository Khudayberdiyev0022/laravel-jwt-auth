<?php

use Database\Factories\WebhookSignatureFactory;

it('will return 401 without a signature', function () {
  $response = $this->post(route('webhooks.test'), ['data' => ['testing' => true]]);

  expect($response)
    ->getStatusCode()->toBe(401);
});

it('will return 401 with an invalid signature', function () {
  $response = $this->withHeader('X-Signature', 'invalid-signature')
    ->post(route('webhooks.test'), ['data' => ['testing' => true]]);

  expect($response)
    ->getStatusCode()->toBe(401);
});

it('will return 401 with a manipulated payload', function () {
  $route = route('webhooks.test');
  $payload = ['data' => ['testing' => true]];

  $factory = new WebhookSignatureFactory();

  $response = $this->withHeader('X-Signature', $factory->generate($route, $payload))
    ->post(route('webhooks.test'), [...$payload, 'manipulated' => true]);

  expect($response)
    ->getStatusCode()->toBe(401);
});

it('will return 200 with a valid signature and payload', function () {
  $route = route('webhooks.test');
  $payload = ['data' => ['testing' => true]];

  $factory = new WebhookSignatureFactory();

  $response = $this->withHeader('X-Signature', $factory->generate($route, $payload))
    ->post(route('webhooks.test'), $payload);

  expect($response)
    ->getStatusCode()->toBe(200);
});


