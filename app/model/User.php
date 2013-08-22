<?php

namespace Clevis\RestApi;

use Clevis\Users;
use DateTime;
use Orm;


/**
 * Uživatel
 *
 * Rozšiřuje uživatele z balíčku Users o vlastnoti pro API (apiKey a expiraci)
 *
 * @property string|NULL $apiKey
 * @property DateTime|NULL $apiKeyExpirationDate
 *
 * @property Orm\OneToMany $apiRequests {1:m Clevis\RestApi\ApiRequestsRepository $user}
 */
class User extends Users\User implements IApiUser
{

	public function getApiKey()
	{
		return $this->getValue('apiKey');
	}

}
