<?php
//include('vendor/autoload.php');
//$preziFinder = new \MidoriKocak\PreziFinder(new \MidoriKocak\URLQueryParser(), new \MidoriKocak\PDOPreziList());
//echo $preziFinder->request($_SERVER['REQUEST_URI']);
?>

<?php

require 'vendor/autoload.php';

$settings = [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
    ],
];

$corsOptions = array(
    "origin" => "*",
    "exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
    "allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);

$app = new Slim\App($settings);
$app->add($cors);

$container = $app->getContainer();

$container['preziList'] = function ($c) {
    $preziList = new \MidoriKocak\PDOPreziList();
    return $preziList;
};

$app->get('/', function ($request, $response, $args) {
    $response->write("Welcome to PreziFinder API");
    return $response;
});

$app->get('/prezi/[{id}]', function ($request, $response, $args) {
    $response->withJson($this->preziList->getPreziById($args['id']));
    return $response;
});

$app->get('/prezis', function ($request, $response, $args) {
    if (empty($request->getQueryParams())) {
        $response->withJson($this->preziList->getAllPrezis());
    } else {
        $params = $request->getQueryParams();
        $type = key($params);
        if ($type == "sort") {
            $response->withJson($this->preziList->sort($params["sort"], $params["order"]));
        } elseif ($type == "fields") {
            $response->withJson($this->preziList->fields(explode(",", $params["fields"])));
        }
    }
    return $response;
});

$app->get('/prezis/search', function ($request, $response, $args) {
    $response->withJson($this->preziList->search($request->getQueryParams()));
    return $response;
});

$app->get('/prezis/sort', function ($request, $response, $args) {
    $response->withJson($this->preziList->sort($request->getQueryParams()));
    return $response;
});

$app->get('/prezis/filter', function ($request, $response, $args) {
    $response->withJson($this->preziList->filter($request->getQueryParams()));
    return $response;
});

$app->run();