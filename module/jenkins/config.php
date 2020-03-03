<?php
$config->jenkins->create = new stdclass();
$config->jenkins->edit   = new stdclass();
$config->jenkins->create->requiredFields = 'name,url,credentials';
$config->jenkins->edit->requiredFields   = 'name,url,credentials';
