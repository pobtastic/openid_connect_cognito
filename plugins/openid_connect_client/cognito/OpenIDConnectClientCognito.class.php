<?php

/**
 * @file
 * OpenID Connect client for AWS Cognito.
 */

/**
 * Implements OpenID Connect Client plugin for AWS Cognito.
 */
class OpenIDConnectClientCognito extends OpenIDConnectClientBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();

    $form['aws_key'] = array(
      '#title' => t('AWS Key'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('aws_key'),
    );
    $form['aws_secret'] = array(
      '#title' => t('AWS Secret'),
      '#type' => 'password',
      '#default_value' => $this->getSetting('aws_secret'),
    );
    $form['region'] = array(
      '#title' => t('AWS Region'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('region', 'eu-central-1'),
    );
    $form['domain'] = array(
      '#title' => t('Domain Prefix'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('domain'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndpoints() {
    $replacements = array(
      '@domain' => $this->getSetting('domain'),
      '@region' => $this->getSetting('region', 'eu-central-1'),
    );
    return array(
      'authorization' => format_string('https://@domain.auth.@region.amazoncognito.com/oauth2/authorize', $replacements),
      'token' => format_string('https://@domain.auth.@region.amazoncognito.com/oauth2/token', $replacements),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function authorize($scope = 'openid email') {
    // AWS Cognito requires 'aws.cognito.signin.user.admin' in order to do anything with User Pools.
    $explode_scope = explode(' ', $scope);
    $explode_scope[] = 'aws.cognito.signin.user.admin';
    return parent::authorize(implode(' ', $explode_scope));
  }

  /**
   * {@inheritdoc}
   */
  public function retrieveUserInfo($access_token) {
    // Cognito doesn't yet have a userinfo endpoint.
    $guzzle = new GuzzleHttp\Client();
    $client = Drupal\openid_connect_cognito\Aws\CognitoIdentityProviderClientFactory::create($this->settings);
    $cognito = Drupal\openid_connect_cognito\Aws\CognitoFactory::create($client, $guzzle, $this->settings);

    $response = $cognito->getUser($access_token);

    if (!$response->hasError()) {
      $result = $response->getResult()->toArray();
      $userinfo = array();
      foreach ($result['UserAttributes'] as $attribute) {
        $userinfo[$attribute['Name']] = $attribute['Value'];
      }

      return $userinfo;
    }

    openid_connect_log_request_error(__FUNCTION__, $this->name, $response->getError());

    return array();
  }

}
