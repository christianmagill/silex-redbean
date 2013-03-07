<?php

$admin = $app['controllers_factory'];

$app->mount('/admn', $admin);