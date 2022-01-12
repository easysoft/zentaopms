<?php
$config->sonarqube = new stdclass();

$config->sonarqube->create = new stdclass();
$config->sonarqube->create->requiredFields = 'name,url,account,password';
