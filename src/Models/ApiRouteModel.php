<?php

namespace Incapption\SimpleApi\Models;

use Incapption\SimpleApi\Enums\HttpMethod;
use Incapption\SimpleApi\Interfaces\iApiMiddleware;

class ApiRouteModel
{
    /**
     * @var string
     */
    private $route;
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $method;
    /**
     * @var iApiMiddleware[]
     */
    private $middlewares = [];
    /**
     * @var HttpMethod
     */
    private $httpMethod;

    /**
     * @return HttpMethod
     */
    public function getHttpMethod(): HttpMethod
    {
        return $this->httpMethod;
    }

    /**
     * @param HttpMethod $httpMethod
     *
     * @return ApiRouteModel
     */
    public function setHttpMethod(HttpMethod $httpMethod): ApiRouteModel
    {
        $this->httpMethod = $httpMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     *
     * @return ApiRouteModel
     */
    public function setRoute(string $route): ApiRouteModel
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     *
     * @return ApiRouteModel
     */
    public function setController(string $controller): ApiRouteModel
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return ApiRouteModel
     */
    public function setMethod(string $method): ApiRouteModel
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return iApiMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param iApiMiddleware|null $middleware
     *
     * @return ApiRouteModel
     */
    public function addMiddleware(iApiMiddleware $middleware): ApiRouteModel
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param array $middlewares
     *
     * @return ApiRouteModel
     */
    public function addMiddlewares(array $middlewares): ApiRouteModel
    {
        foreach ($middlewares as $middleware)
        {
           $this->addMiddleware($middleware);
        }

        return $this;
    }
}