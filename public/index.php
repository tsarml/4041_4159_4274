<?php

define('ROOT', __DIR__);
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
require ROOT . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT . '/..');
$dotenv->safeLoad();

session_start();

Flight::register('db', 'PDO', [
    'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . ';dbname=' . ($_ENV['DB_NAME'] ?? '4041_4159_4274') . ';charset=utf8',
    $_ENV['DB_USER'] ?? 'root',
    $_ENV['DB_PASS'] ?? '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
]);

spl_autoload_register(function($class) {
    $file = ROOT . '/../src/Controllers/' . $class . '.php';
    if (file_exists($file)) require $file;
});


Flight::map('render', function($view, $data = []) {
    extract($data);
    ob_start();
    require ROOT . '/../src/Views/' . $view . '.php';
    $content = ob_get_clean();
    require ROOT . '/../src/Views/layout.php';
});

Flight::set('flight.base_url', BASE_URL);

require ROOT . '/../config/routes.php';

Flight::start();