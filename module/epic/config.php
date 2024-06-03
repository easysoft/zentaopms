<?php
global $app;
$app->loadConfig('story');
$config->epic = clone $config->story;
