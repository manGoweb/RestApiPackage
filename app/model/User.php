<?php

namespace Clevis\RestApi;

use Clevis\Users;
use DateTime;


/**
 * Uživatel
 *
 * Rozšiřuje uživatele z balíčku Users o vlastnoti pro API (apiKey a expiraci)
 *
 * @property string $apiKey
 * @property DateTime $apiKeyExpirationDate
 */
class User extends Users\User
{

}
