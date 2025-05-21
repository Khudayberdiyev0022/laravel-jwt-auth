<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class WebhookSignatureFactory extends Factory
{
  protected string $signingKey;

  public function __construct()
  {
    $this->signingKey = config('app.webhook_signing_key');
  }

  public function generate(string $url, array $params)
  {
    ksort($params);

    $data = $url.json_encode($params);

    return base64_encode(hash_hmac('sha256', $data, $this->signingKey, true));
  }

  public function definition(): array
  {
    return [
      //
    ];
  }
}
