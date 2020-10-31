<?php
require("internal/router/Router.php");

$router = new Router();

$router->use(function($request, $response) {
    $response->setHeader("X-Foo-Time", time());
});

$router->get("/hello", function($request, $response) {
    $name = $request->getQueryParameter("name");
    $response->send("Hello $name");
});

$router->get("/test/(\w+)", function($request, $response) {
    ob_start();
    var_dump($request);
    $content = ob_get_clean();
    $response->send($content);
});

$router->run();
?>
