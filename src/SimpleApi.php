<?php

namespace Incapption\SimpleApi;

use RuntimeException;
use Incapption\SimpleApi\Helper\ApiRequest;
use Incapption\SimpleApi\Models\StringResult;
use Incapption\SimpleApi\Enums\HttpStatusCode;
use Incapption\SimpleApi\Helper\RouteParameters;
use Incapption\SimpleApi\Interfaces\iMethodResult;

abstract class SimpleApi
{
	/**
	 * @var string
	 */
	private $requestUri;

	/**
	 * @var string
	 */
	private $requestMethod;

	public function __construct(string $requestUri, string $requestMethod)
	{
		$this->requestUri = $requestUri;
		$this->requestMethod = $requestMethod;
	}

	/**
	 * Abstract function for registering the API routes.
	 *
	 * @return void
	 */
	protected abstract function registerRoutes();

	/**
	 * Check whether the server REQUEST_URI includes the defined API endpoint, for example "/api/v1"
	 *
	 * @param string $endpoint
	 *
	 * @return bool
	 */
	public function isApiEndpoint(string $endpoint) : bool
	{
		return !empty($this->requestUri) && substr($this->requestUri, 0, strlen($endpoint)) === $endpoint;
	}

	/**
	 * Iterates the registered routes for the requested endpoint, calls the method and returns the result.
	 */
	public function getResult() : iMethodResult
	{
		foreach (SimpleApiRoute::getRegisteredRoutes() as $item)
        {
	        // parse route parameters and match them with variables from requestUri
	        ApiRequest::parseRouteParameters($item->getRoute(), $this->requestUri);

        	if ($item->compareRouteAndRequestUri($item->getRoute(), $this->requestUri) === false || strtoupper($this->requestMethod) !==
		        strtoupper($item->getHttpMethod()->getValue()))
	        {
	        	continue;
	        }

        	if ($middleware = $item->getMiddleware())
	        {
	            $middleware->authorize();
	        }

        	if (method_exists($item->getController(), $item->getMethod()) === false)
	        {
	        	throw new RuntimeException($item->getController().'->'.$item->getMethod().'() does not exist');
	        }

        	// create instance of the controller
	        $controller = $item->getController();
	        $controller = new $controller();

	        // call method
            $result = call_user_func_array(array($controller, $item->getMethod()), []);

        	if ($result instanceof iMethodResult)
	        {
				return $result;
	        }

        	throw new RuntimeException($item->getController().'->'.$item->getMethod().'() has to return iMethodResult');
        }

		return new StringResult(HttpStatusCode::NOT_FOUND(), 'Not Found: invalid api endpoint or method');
	}

	/**
	 * Echoes JSON result, sets the status code and exits the application.
	 *
	 * @param iMethodResult $result
	 */
	public function echoResultExit(iMethodResult $result)
	{
		header('Content-Type: application/json; charset=utf-8');
		http_response_code($result->getStatusCode()->getValue());
		echo $result->getJson();
		exit;
	}
}