<?php

namespace Clevis\RestApi;

use Clevis;
use Clevis\Skeleton\Entity;
use DateTime;


/**
 * Log jednoho požadavku na API
 *
 * @property string $method
 * @property string $url
 * @property string $headers
 * @property string|NULL $body
 * @property string $apiVersion
 * @property string $action
 * @property int    $responseCode
 * @property string $responseHeaders
 * @property string $responseBody
 * @property string $remoteAddress
 * @property string $remoteHost
 * @property DateTime $createdAt {default now}
 *
 * @property Clevis\RestApi\User|NULL $user {m:1 Clevis\RestApi\UsersRepository $apiRequests}
 */
class ApiRequest extends Entity
{

}
