<?php
global $app;
$app->loadConfig('story');
$config->requirement = clone $config->story;
