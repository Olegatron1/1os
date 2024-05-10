<?php

/**
 * Оптимизировать запросы в базу данных. Работу функций оптимизировать не нужно, только оптимизация работы с базой данных.
 * Акцент стоит ставить на скорость работы и количество запросов. В идеале вся работа должна быть выполнена за 2 sql запроса.
 */

/**
 * Some fancy function that multyplies number to a random float number and returns a rounded value;
 */
function fancyFunction1($value)
{
    return round($value * (mt_rand() / mt_getrandmax()));
}

/**
 * Some fancy function that gets a random part of a string and returns it;
 */
function fancyFunction2($value)
{
    return substr($value, rand(0, strlen($value) - 1), rand(1, strlen($value)));
}

/**
 * Config array to establish a connection with a database.
 */
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'dbname' => 'exam'
];

/**
 * Establishing a database connection.
 */
$connection = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);

/**
 * List of users to update
 */
$users = $connection->query("SELECT `id`, `param1`, `param2` FROM `users`");

$updateParams = [];

foreach ($users as $user) {
    $newParam1 = fancyFunction1($user['param1']);
    $newParam2 = fancyFunction2($user['param2']);

    $updateParams[] = "WHEN `id` = {$user['id']} THEN '$newParam1', '$newParam2'";
}

$updateQuery = "UPDATE `users` SET ";

$updateQuery .= " `param1` = CASE " . implode(" ", $updateParams) . " END, ";
$updateQuery .= " `param2` = CASE " . implode(" ", $updateParams) . " END ";

$connection->query($updateQuery);

$users ->free();

exit(0);