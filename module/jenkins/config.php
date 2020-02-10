<?php
$config->jenkins->create = new stdclass();
$config->jenkins->create->requiredFields = 'name,serviceUrl,credentials';
$config->jenkins->edit = new stdclass();
$config->jenkins->edit->requiredFields = 'name,serviceUrl,credentials';
