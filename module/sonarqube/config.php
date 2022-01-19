<?php
$config->sonarqube = new stdclass();

$config->sonarqube->create = new stdclass();
$config->sonarqube->create->requiredFields = 'name,url,account,password';

$config->sonarqube->edit = new stdclass();
$config->sonarqube->edit->requiredFields = 'name,url,account,password';
