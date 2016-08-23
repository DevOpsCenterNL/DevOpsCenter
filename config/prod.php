<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__ . '/../src/Resources/templates');
$app['twig.options'] = array('cache' => __DIR__ . '/../var/cache/twig');
$app['locale'] = 'NL';
$app['slack.team'] = "devopscenter";
$app['slack.token'] = "mwuahahahahaTHISisNOTmyREALtoken!";
$app['slack.cache'] = __DIR__ . '/../var/cache/slack';
$app['env'] = 'prod';
