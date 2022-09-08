<?php
$config->zahost->create       = new stdclass();
$config->zahost->create->requiredFields = 'name,hostType,publicIP,cpuCores,memory,diskSize,tags,instanceNum';
$config->zahost->create->ipFields       = 'privateIP,publicIP';
