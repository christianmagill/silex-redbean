<?php

$general = $app['controllers_factory'];
$general->get('/','App\Controller\General::home')->bind('
home');
$general->get('/{name}','App\Controller\General::name')->bind('
name');
$general->get('/foo','App\Controller\General::foo')->bind('
foo');
$app->mount('/', $general);