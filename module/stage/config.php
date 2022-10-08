<?php 
$config->stage->create = new stdclass();
$config->stage->edit   = new stdclass();
$config->stage->create->requiredFields = 'name,percent,type';
$config->stage->edit->requiredFields   = 'name,percent,type';
