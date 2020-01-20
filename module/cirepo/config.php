<?php
$config->repo->create->requiredFields = 'SCM,name,path,encoding,client,credentials';
$config->repo->edit->requiredFields = 'SCM,name,path,encoding,client,credentials';
$config->repo->cacheTime = 10;
$config->repo->syncTime  = 10;
$config->repo->batchNum  = 100;
$config->repo->images    = '|png|gif|jpg|ico|jpeg|bmp|';
$config->repo->binary    = '|pdf|';