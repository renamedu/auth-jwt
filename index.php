<?php

include_once "authorize.php";
include_once "feed.php";
require_once "register.php";

// Маршруты
// [маршрут => функция которая будет вызвана]
$routes = [
    '/register' => 'register',
    '/feed' => 'feed',
    '/authorize' => 'authorize',
];

// возвращает путь запроса
// вырезает auth-jwt из пути
function getRequestPath() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return '/' . ltrim(str_replace('auth-jwt', '', $path), '/');
}

// наш роутер, в который передаются маршруты и запрашиваемый путь
// возвращает функцию если маршрут совпал с путем
// иначе возвращает функцию notFound
function getMethod(array $routes, $path) {
    // перебор всех маршрутов
    foreach ($routes as $route => $method) {
        // если маршрут сопадает с путем, возвращаем функцию
        if ($path === $route) {
            return $method;
        }
    }
    return 'notFound';
}

// метод, который отдает заголовок и содержание для маршрутов,
// которые не существуют
function notFound() {
    header("HTTP/1.0 404 Not Found");

    return '404 Not Found';
}

// Роутер
// получаем путь запроса
$path = getRequestPath();
// получаем функцию обработчик
$method = getMethod($routes, $path);
// отдаем данные клиенту
$method = $method();
if ($method !== null) {
    echo json_encode($method);
}