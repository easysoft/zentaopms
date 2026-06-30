<?php
$config->jenkins->create = new stdclass();
$config->jenkins->edit   = new stdclass();
$config->jenkins->create->requiredFields = 'name,url,account';
$config->jenkins->edit->requiredFields   = 'name,url,account';
