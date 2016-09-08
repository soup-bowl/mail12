<?php namespace MailTwelve;

/** @var \Herbert\Framework\Panel $panel */

$panel->add([
    'type'   => 'panel',
    'as'     => 'mainPanel',
    'title'  => Helper::get('pluginName'),
    'slug'   => 'mailtwelve-home',
    'icon'   => 'dashicons-email-alt',
    'uses'   => __NAMESPACE__ . '\Controllers\MailController@getHome'
]);

$panel->add([
    'type'   => 'wp-sub-panel',
    'parent' => 'options-general.php',
    'as'     => 'dashboardSubpanel',
    'title'  => Helper::get('pluginName'),
    'slug'   => 'mailtwelve-settings',
    'uses'   => __NAMESPACE__ . '\Controllers\SettingsController@getSettingsPanel'
]);

$panel->add([
    'type'   => 'wp-sub-panel',
    'parent' => 'users.php',
    'as'     => 'dashboardSubpanel',
    'title'  => Helper::get('pluginName') . ' Settings',
    'slug'   => 'mailtwelve-user-settings',
    'uses'   => __NAMESPACE__ . '\Controllers\SettingsController@getUserSettingsPanel'
]);