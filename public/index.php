<?php
require("internal/router/Router.php");

$router = new Router();

// check login
$router->use(function($request, $response) {
    if (preg_match("#^/(login|logout)(/?|/.+)#", $request->getPath()) !== 1) {
        $response->redirect("/login");
    }
});

$router->get("/?", function($request, $response) {
    $response->redirect("/calendar");
});

$router->get("/login", function($request, $response) {
    $response->send("login");
});

$router->post("/login", function($request, $response) {
    $response->send("login post");
});

$router->get("/logout", function($request, $response) {

});

$router->get("/calendar", function($request, $response) {
    $name = $request->getQueryParameter("name");
    $response->send("Hello $name");
});

$router->get("/calendar/(\d{4}-\d{2}-\d{2})/?", function($request, $response) {

});

$router->run();
?>
