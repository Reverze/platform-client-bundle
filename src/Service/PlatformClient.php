<?php

namespace PlatformClientBundle\Service;

use Circle\RestClientBundle\Services\RestClient;
use Symfony\Component\HttpFoundation\Response;

/**
 * Platform API client
 */
class PlatformClient
{
    /**
     * Platform provider's name
     * @var string
     */
    protected $providerName = null;
    
    /**
     * Provider's api host
     * @var string
     */
    protected $providerApiHost = null;
    
    /**
     * Provider's api routes
     * @var array
     */
    protected $providerApiRoutes = array();
    
    /**
     * RestClient's service
     * @var \Circle\RestClientBundle\Services\RestClient
     */
    protected $restClient = null;
    
    /**
     * Creates a new connector to platform API
     * @param string $providerName Provider's name
     * @param string $providerApiHost Provider's api host
     * @param array $apiRoutes Provider's api routes
     */
    public function __construct(RestClient $restClient, string $providerName, string $providerApiHost, array $apiRoutes = array())
    {
        /**
         * If provider's name is not defined before
         */
        if (empty($this->providerName)){
            $this->providerName = (string) $providerName;
        }
        
        /**
         * If provider's api host is not defined before
         */
        if (empty($this->providerApiHost)){
            $this->providerApiHost = (string) $providerApiHost;
        }
        
        /**
         * If provider's api route are not stored
         */
        if (empty($this->providerApiRoutes)){
            $this->providerApiRoutes = (array) $apiRoutes;
        }
        
        /**
         * If restClient is not initialized
         */
        if (empty($this->restClient)){
            $this->restClient = $restClient;
        }
    }
    
    /**
     * Performs API action
     * @param string $routeName
     * @param array $parameters
     * @return Response
     * @throws \HBMasterBundle\Utils\Api\Exception\PlatformApiServiceException
     */
    public function perform(string $routeName, array $parameters = array()) : Response
    {
        /**
         * Gets route
         */
        $route = $this->getRoute($routeName);
        
        /**
         * If route's path is not specified
         */
        if (!array_key_exists('path', $route)){
            throw Exception\PlatformApiServiceException::routePathNotDefined($routeName);
        }
        
        /**
         * Compiles route's path
         */
        $path = $this->compilePath($route['path'], $parameters);
        
        $method = 'load';
        
        if (array_key_exists('method', $route)){
            $method = strtolower($route['method']);
        }
        
        
        /**
         * 
         */
        if ($method === 'load'){
            return $this->restClient->get($path); 
        }
        if ($method === 'store'){
            return $this->restClient->post($path, json_encode($parameters));
        }
    }
    
    
    /**
     * Gets api route
     * @param string $routeName
     * @return array or empty array if not found
     */
    private function getRoute(string $routeName) : array
    {
        if (isset($this->providerApiRoutes[$routeName])){
            return $this->providerApiRoutes[$routeName];
        }
        
        /**
         * If route was not found throws exception
         */
        throw Exception\PlatformApiServiceException::routeNotFound($routeName);
    }
    
    /**
     * Compiles route's path
     * @param string $path
     * @param array $parameters
     * @return string
     */
    private function compilePath(string $path, array $parameters = array()) : string
    {
        /**
         * Path's parameters
         */
        $pathParameters = array();
        
        /**
         * Parameter regular expression
         */
        $parameterExpression = '/\\{[a-zA-Z0-9\-\_]+\\}/';
        
        /**
         * Matchs defined route's parameters into pathParamters
         */
        preg_match_all($parameterExpression, $path, $pathParameters);
        
        /**
         * Compiled path version
         */
        $compiledPath = $path;
        
        if (isset($pathParameters[0]) && is_array($pathParameters[0])){
            foreach ($pathParameters[0] as $parameter){
                $parameterName = str_replace('{', '', $parameter);
                $parameterName = str_replace('}', '', $parameterName);
                
                if (isset($parameters[$parameterName])){
                    $compiledPath = str_replace('{' . $parameterName . '}', strval($parameters[$parameterName]), $compiledPath);
                }
            }
        }
        
        return $compiledPath;
    }
    
}


?>

