<?php
$config->gitea->create = new stdclass;
$config->gitea->create->requiredFields = 'name,url,token';

$config->gitea->edit = new stdclass;
$config->gitea->edit->requiredFields = 'name,url,token';
