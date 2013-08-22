<?php

namespace Clevis\RestApi;

use Nette\Http;
use Nette\Object;
use Nette\Utils\Json;
use Nette\Application;
use Clevis\Skeleton\Orm\RepositoryContainer;


/**
 * Loguje požadavky na API
 */
class ApiLogger extends Object implements IApiLogger
{

	/** @var ApiRequestsRepository */
	private $requests;

	/** @var RepositoryContainer */
	private $orm;

	/** @var string třída entity */
	private $class = 'Clevis\\RestApi\\ApiRequest';


	public function __construct(ApiRequestsRepository $requests, RepositoryContainer $orm)
	{
		$this->requests = $requests;
		$this->orm = $orm;
	}

	public function setEntityClass($class)
	{
		$this->class = $class;
	}

	/**
	 * Zaloguje požadavek uživatele
	 *
	 * @param ApiResponse
	 */
	public function logRequest(
		Http\Request $httpRequest, Http\Response $httpResponse,
		Application\Request $request, ApiResponse $response,
		$requestBody,
		IApiUser $user = NULL
	) {
		$params = $request->getParameters();
		$action = @$params['action'] ? $request->presenterName . ':' . $params['action'] : NULL;

		// zahodit veškeré změny
		$this->orm->clean();
		$this->orm->purge();

		/** @var ApiRequest $log */
		$log = new $this->class;
		$log->setValues(array(
			'method' => $httpRequest->method,
			'url' => (string) $httpRequest->url,
			'headers' => $this->serializeHeaders($httpRequest->headers),
			'body' => $requestBody,
			'apiVersion' => $httpRequest->getHeader('X-Api-Version'),
			'user' => $user,
			'action' => $action,
			'responseCode' => $response->getResponseCode(),
			'responseHeaders' => $this->serializeHeaders($httpResponse->headers),
			'responseBody' => Json::encode($response->payload),
			'remoteAddress' => $httpRequest->remoteAddress,
			'remoteHost' => $httpRequest->remoteHost,
		));
		$this->requests->persistAndFlush($log);
	}

	/**
	 * @param array
	 * @return string
	 */
	private function serializeHeaders(array $headers)
	{
		return implode("\n", array_map(function ($header) use ($headers) {
			return $header . ': ' . $headers[$header];
		}, array_keys($headers)));
	}

}
