<?php
$config->job = new stdclass();
$config->job->create = new stdclass();
$config->job->create->requiredFields = 'name,repo,jenkins,jenkinsJob,triggerType';
$config->job->edit = new stdclass();
$config->job->edit->requiredFields = 'name,repo,jenkins,jenkinsJob,triggerType';
