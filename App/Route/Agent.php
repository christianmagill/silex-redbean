<?php

$agent = $app['controllers_factory'];

$app->mount('/agent', $agent);