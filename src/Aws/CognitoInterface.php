<?php

namespace Drupal\openid_connect_cognito\Aws;

/**
 * An interface to define what a Cognito class should implement.
 */
interface CognitoInterface {

  /**
   * Fetches the user account by token.
   *
   * @param string $token
   *   The access token.
   * 
   * @return \Drupal\cognito\Aws\CognitoResult
   *   The cognito result.
   */
  public function getUser($token);

}
