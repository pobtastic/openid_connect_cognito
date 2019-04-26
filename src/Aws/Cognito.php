<?php

namespace Drupal\openid_connect_cognito\Aws;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use GuzzleHttp\Client;

/**
 * A helper service for integration with AWS Cognito.
 */
class Cognito extends CognitoBase {

  /**
   * The Cognito aws-sdk client.
   *
   * @var \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient
   */
  protected $client;

  /**
   * The unique Id for this client.
   *
   * @var string
   */
  protected $clientId;

  /**
   * The secret for this client.
   *
   * @var string
   */
  protected $clientSecret;

  /**
   * The unique user pool Id.
   *
   * @var string
   */
  protected $userPoolId;

  /**
   * The http client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Cognito constructor.
   *
   * @param \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $client
   *   The congnito aws client.
   * @param string $clientId
   *   The client Id.
   * @param string $clientSecret
   *   The client secret.
   * @param string $userPoolId
   *   The user pool Id.
   * @param \GuzzleHttp\Client $httpClient
   *   The http client.
   */
  public function __construct(CognitoIdentityProviderClient $client, $clientId, $clientSecret, $userPoolId, Client $httpClient) {
    $this->client = $client;
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->userPoolId = $userPoolId;
    $this->httpClient = $httpClient;
  }

  /**
   * {@inheritdoc}
   */
  public function getUser($token) {
    return $this->wrap(function () use ($token) {
      return $this->client->getUser([
        'AccessToken' => $token,
      ]);
    });
  }

}
