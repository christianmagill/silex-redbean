<?php

$member = $app['controllers_factory'];

$app->mount('/member', $member);