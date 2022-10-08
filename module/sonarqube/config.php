<?php
$config->sonarqube = new stdclass();

$config->sonarqube->create = new stdclass();
$config->sonarqube->create->requiredFields = 'name,url,account,password';

$config->sonarqube->edit = new stdclass();
$config->sonarqube->edit->requiredFields = 'name,url,account,password';

$config->sonarqube->projectStatusClass = array();
$config->sonarqube->projectStatusClass['OK']    = 'success';
$config->sonarqube->projectStatusClass['WARN']  = 'warning';
$config->sonarqube->projectStatusClass['ERROR'] = 'danger';

$config->sonarqube->createproject = new stdclass();
$config->sonarqube->createproject->requiredFields = 'projectName,projectKey';

$config->sonarqube->cacheTime = 10;
