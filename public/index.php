<?php

require '__DIR__v' . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;


$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('Welcome to Slim!');
});
$app->get('/users', function ($request, $response, $args) {
  $users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];
  $term = $request->getQueryParam('term');
  if ($term) {
    $filtredUsers = array_filter($users, function($value) use ($term) {
      return (stripos($value, $term) !== false);
    });
  } else {
    $filtredUsers = $users;
  };
  $params = ['users' => $filtredUsers];
  return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});
$app->post('/users', function ($request, $response) {
    return $response->withStatus(302);
});
$app->get('/courses/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});
$app->get('/users/{id}', function ($request, $response, $args) {
    // Указанный путь считается относительно базовой директории для шаблонов, заданной на этапе конфигурации
    // $this доступен внутри анонимной функции благодаря http://php.net/manual/ru/closure.bindto.php
    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});
$app->run();
?>
