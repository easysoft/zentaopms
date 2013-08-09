<?php
$config->sso                         = new stdclass();
$config->sso->create                 = new stdclass();
$config->sso->create->requiredFields = 'title,code,key,ip';
$config->sso->edit                   = new stdclass();
$config->sso->edit->requiredFields   = 'title,key,ip';
