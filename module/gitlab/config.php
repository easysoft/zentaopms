<?php
$config->gitlab->create = new stdclass();
$config->gitlab->edit   = new stdclass();

$config->gitlab->create->requiredFields = 'name,url,token';
$config->gitlab->edit->requiredFields   = 'name,url,token';

$config->gitlab->taskLabel = new stdclass();
$config->gitlab->taskLabel->name        = "zentao task";
$config->gitlab->taskLabel->description = "task label from zentao";
$config->gitlab->taskLabel->color       = "#0033CC";
$config->gitlab->taskLabel->priority    = "0";

$config->gitlab->bugLabel = new stdclass();
$config->gitlab->bugLabel->name         = "zentao bug";
$config->gitlab->bugLabel->description  = "bug label from zentao";
$config->gitlab->bugLabel->color        = "#D10069";
$config->gitlab->bugLabel->priority     = "0";

$config->gitlab->zentaoApiWebhookUrl    = "%s/api.php?m=gitlab&f=webhook&product=%s&gitlab=%s";
$config->gitlab->zentaoApiWebhookToken  = "<access token>";


