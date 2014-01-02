<?php

namespace Clevis\RestApi;

use DateTime;
use Clevis\Users\Authenticator;
use Nette\Security\AuthenticationException;
use Nette\Object;
use Nette\Utils\Strings;


/**
 * Zajišťuje přihlášení uživatele klíčem API
 *
 * - pokud je nastaven Authenticator, pokusí se uživatele přihlásit jménem a heslem a vytvoří mu API klíč
 * - pokud je $lifeTime (resp. User::$apiKeyExpirationDate) NULL, má klíč platnost navždy
 */
class ApiAuthenticator extends Object implements IApiAuthenticator
{

	/** @var string */
	private $lifeTime = '+30 days';

	/** @var UsersRepository */
	protected $users;

	/** @var Authenticator */
	private $authenticator;


	public function __construct(UsersRepository $users, Authenticator $authenticator = NULL)
	{
		$this->users = $users;
		$this->authenticator = $authenticator;
	}

	/**
	 * Nastavuje dobu platnosti nového kliče API. např. "+30 days"
	 *
	 * @param string
	 */
	public function setApiKeyLifetime($lifeTime)
	{
		$this->lifeTime = $lifeTime;
	}

	/**
	 * @param string|int
	 * @param array|NULL
	 * @return IApiUser|NULL
	 */
	public function authenticate($apiKey, $requestData = NULL)
	{
		/** @var User $user */
		if ($apiKey)
		{
			$user = $this->users->getByApiKey($apiKey);
			if ($user && (!$user->apiKeyExpirationDate || $user->apiKeyExpirationDate > new DateTime))
			{
				return $user;
			}
		}

		if (!$this->authenticator || !$requestData || !isset($requestData->username) || !isset($requestData->password))
		{
			return NULL;
		}

		try
		{
			// zkusí uživatele přihlásit
			$identity = $this->authenticator->authenticate(array($requestData->username, $requestData->password));
			$user = $this->users->getById($identity->getId());
			$this->postAuthenticate($user);
			return $user;
		}
		catch (AuthenticationException $e)
		{
			return NULL;
		}
	}

	/**
	 * Creates API key and sets expiration time.
	 *
	 * @param IApiUser $user
	 * @return IApiUser
	 */
	protected function postAuthenticate($user)
	{
		$user->apiKey = $this->createApiKey($user);
		if ($this->lifeTime)
		{
			$user->apiKeyExpirationDate = new DateTime($this->lifeTime);
		}

		$this->users->persistAndFlush($user);

		return $user;
	}

	/**
	 * Vytváří API klíč uživatele
	 *
	 * @param User
	 * @return string
	 */
	private function createApiKey(User $user)
	{
		return Strings::random(40);
	}

}
