<?php

namespace Drupal\openid_connect_cognito\Aws;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

/**
 * Factory to construct identity provider clients.
 */
class CognitoIdentityProviderClientFactory {

  /**
   * Creates a new client.
   *
   * @param array
   *   An array of configuration settings.
   *
   * @return \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient
   *   The created client.
   */
  public static function create(array $settings) {
    $settings = [
      'region' => $settings['region'],
      'credentials' => [
        'key' => $settings['aws_key'],
        'secret' => $settings['aws_secret'],
      ],
    ];
    return new CognitoIdentityProviderClient($settings + [
      'debug' => FALSE,
      'version' => '2016-04-18',
    ]);
  }

}
