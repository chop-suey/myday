<?php
require("internal/router/Router.php");

$router = new Router();

// check login
$router->use(function($request, $response) {
    if (preg_match("#^/(login|logout)(/?|/.+)#", $request->getPath()) !== 1) {
        $response->redirect("/login");
    }
});

$router->get("/hello", function($request, $response) {
    $name = $request->getQueryParameter("name");
    $response->send("Hello $name");
});

$router->get("/login", function($request, $response) {
    $response->send("login");
});

$router->get("/test/(\w+)", function($request, $response) {
    ob_start();
    var_dump($request);
    $content = ob_get_clean();
    $response->send($content);
});

$router->run();
?>
