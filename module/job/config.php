<?php
$config->job = new stdclass();
$config->job->create = new stdclass();
$config->job->edit   = new stdclass();
$config->job->create->requiredFields = 'name,repo,engine,server,pipeline,triggerType';
$config->job->edit->requiredFields   = 'name,repo,server,pipeline,triggerType';
