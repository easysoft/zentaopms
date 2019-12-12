<?php
$config->credential->create->requiredFields = 'name,serviceUrl';
$config->credential->edit->requiredFields = 'name,serviceUrl';

$config->jenkins->create->requiredFields = 'name';
$config->jenkins->edit->requiredFields = 'name';

$config->repo->create->requiredFields = 'SCM,name,path,encoding,client,credential';
$config->repo->edit->requiredFields = 'SCM,name,path,encoding,client,credential';
