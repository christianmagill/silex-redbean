<?php

$builder = $app['controllers_factory'];

$app->mount('/builder', $builder);