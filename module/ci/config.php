<?php
$config->credential->create = new stdclass();
$config->credential->create->requiredFields = 'name';

$config->credential->edit = new stdclass();
$config->credential->edit->requiredFields = 'name';

$config->jenkins->create = new stdclass();
$config->jenkins->create->requiredFields = 'name';

$config->jenkins->edit = new stdclass();
$config->jenkins->edit->requiredFields = 'name';