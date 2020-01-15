<?php
$config->credential->create->requiredFields = 'name,serviceUrl';
$config->credential->edit->requiredFields = 'name,serviceUrl';

$config->jenkins->create->requiredFields = 'name';
$config->jenkins->edit->requiredFields = 'name';

$config->repo->create->requiredFields = 'SCM,name,path,encoding,client,credential';
$config->repo->edit->requiredFields = 'SCM,name,path,encoding,client,credential';
$config->repo->cacheTime = 10;
$config->repo->syncTime  = 10;
$config->repo->batchNum  = 100;
$config->repo->images    = '|png|gif|jpg|ico|jpeg|bmp|';
$config->repo->binary    = '|pdf|';

$config->repo->requiredFields = 'name,repo,buildType,jenkins,jenkinsTask,triggerType';

$config->repo->commitCommands = array(entity=>'/(.*)\s(?:(?:requirement)|(?:task)|(?:Bug))\s?#(.*)/Ui');