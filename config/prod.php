<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../src/Resources/templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
$app['locale'] = 'NL';
$app['slack.team'] = "devopscenter";
$app['slack.token'] = "xoxp-58747788693-58747841255-71415768020-63f6e59160";
