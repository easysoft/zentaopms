<?php
$config->integration = new stdclass();
$config->integration->create = new stdclass();
$config->integration->edit   = new stdclass();
$config->integration->create->requiredFields = 'name,repo,jkHost,jkJob,triggerType';
$config->integration->edit->requiredFields   = 'name,repo,jkHost,jkJob,triggerType';
