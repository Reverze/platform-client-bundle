<?php

namespace PlatformClientBundle\Service\Exception;

class PlatformApiServiceException extends \Exception
{
    public static function routeNotFound(string $routeName) : PlatformApiServiceException
    {
        return (new PlatformApiServiceException(sprintf("Route's not found: '%s'", $routeName)));
    }
    
    public static function routePathNotDefined(string $routePath) : PlatformApiServiceException
    {
        return (new PlatformApiServiceException(sprintf("Route's path is not defined for route '%s'", $routePath)));
    }
}


?>
