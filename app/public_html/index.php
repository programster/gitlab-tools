<?php

require_once(__DIR__ . '/../bootstrap.php');

$slimSettings = array('determineRouteBeforeAppMiddleware' => true);

if (ENVIRONMENT === 'dev')
{
    $slimSettings['displayErrorDetails'] = true;
}

$slimConfig = array('settings' => $slimSettings);
$app = new Slim\App($slimConfig);



// Define trailing slash middleware
$trailingSlashMiddleware = function (Slim\Http\Request $request, Slim\Http\Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();

    if ($path != '/' && substr($path, -1) == '/') 
    {
        // redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));

        if ($request->getMethod() == 'GET') 
        {
            return $response->withRedirect((string)$uri, 307);
        }
        else
        {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
};

// Apply the middleware to every request.
//$app->add($trailingSlashMiddleware);

HomeController::registerRoutes($app);
IssueController::registerRoutes($app);
ProjectController::registerRoutes($app);

$app->run();


