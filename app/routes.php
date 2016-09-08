<?php namespace MailTwelve;

/** @var \Herbert\Framework\Router $router */

$router->get([
    'as'   => 'info',
    'uri'  => '/mail/test',
    'uses' => __NAMESPACE__ . '\Controllers\GeneralController@info'
]);