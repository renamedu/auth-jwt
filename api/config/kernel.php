<?php

// Показ сообщений об ошибках
error_reporting(E_ALL);

// Установим часовой пояс по умолчанию
date_default_timezone_set("Europe/Moscow");

// Переменные, используемые для JWT
$key = "individual_key";
$iss = "http://site.org";
$aud = "http://localhost.com";
$iat = 1356999524;
$nbf = 1357000000;
