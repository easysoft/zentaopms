<?php
$config->gitlab->create = new stdclass();
$config->gitlab->create->requiredFields = 'name,url,token';

$config->gitlab->edit = new stdclass();
$config->gitlab->edit->requiredFields = 'name,url,token';

$config->gitlab->zentaoLabel = 'Zentao';

$config->gitlab->taskLabel = new stdclass();
$config->gitlab->taskLabel->name        = "zentao task";
$config->gitlab->taskLabel->description = "task label from zentao, do NOT remove this";
$config->gitlab->taskLabel->color       = "#0033CC";
$config->gitlab->taskLabel->priority    = "0";

$config->gitlab->bugLabel = new stdclass();
$config->gitlab->bugLabel->name         = "zentao bug";
$config->gitlab->bugLabel->description  = "bug label from zentao, do NOT remove this";
$config->gitlab->bugLabel->color        = "#D10069";
$config->gitlab->bugLabel->priority     = "0";

$config->gitlab->storyLabel = new stdclass();
$config->gitlab->storyLabel->name         = "zentao story";
$config->gitlab->storyLabel->description  = "story label from zentao, do NOT remove this";
$config->gitlab->storyLabel->color        = "##69D100";
$config->gitlab->storyLabel->priority     = "0";

config->gitlab->zentaoApiWebhookUrl    = "%s/api.php?m=gitlab&f=webhook&product=%s&gitlab=%s";
$config->gitlab->zentaoApiWebhookToken  = "<access token>";
