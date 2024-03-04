<?php
$config->pipeline->create = new stdclass();
$config->pipeline->edit   = new stdclass();
$config->pipeline->create->requiredFields = 'name,url,type';
$config->pipeline->edit->requiredFields   = 'name,url,type';
