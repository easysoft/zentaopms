<?php
$config->pipeline->create = new stdclass();
$config->pipeline->edit   = new stdclass();
$config->pipeline->create->requiredFields = 'name,url,type';
$config->pipeline->edit->requiredFields   = 'name,url,type';

$config->pipeline->formatTypeService = array('gitlab');
$config->pipeline->checkRepoServers  = 'gitlab,gitea,gogs';
