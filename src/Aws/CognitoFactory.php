<?php

namespace Drupal\openid_connect_cognito\Aws;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use GuzzleHttp\Client;

/**
 * Constructs cognito services.
 */
class CognitoFactory {

  /**
   * {@inheritdoc}
   */
  public static function create(CognitoIdentityProviderClient $client, Client $httpClient, array $settings) {
    return new Cognito(
      $client,
      $settings['client_id'],
      $settings['client_secret'],
      $settings['user_pool_id'],
      $httpClient
    );
  }

}
