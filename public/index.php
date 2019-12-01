<?php

require '__DIR__v' . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Repository;

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
$app->get('/courses/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});
$app->get('/user/{id}', function ($request, $response, $args) {
    // Указанный путь считается относительно базовой директории для шаблонов, заданной на этапе конфигурации
    // $this доступен внутри анонимной функции благодаря http://php.net/manual/ru/closure.bindto.php
    $params = [];
    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});
$app->get('/users/new', function ($request, $response) {
  $params = [];
  return $this->get('renderer')->render($response, 'users/new.phtml', $params);
});
  $repo = new App\Repository('files/users');
$app->post('/users', function ($request, $response) use ($repo) {
  $user = $request->getParsedBodyParam('user');
  $validator = new App\Validator($user, array_keys($user));
  if (empty($alidator->validate)) {
    $repo->save(json_encode($user) . PHP_EOL);
    return $response->withHeader('Location', '/')
  ->withStatus(302);
}
});
$app->run();
?>
