<?php
$config->zahost->create = new stdclass();
$config->zahost->create->requiredFields = 'name,hostType,publicIP,cpuCores,memory,diskSize,virtualSoftware,instanceNum';
$config->zahost->create->ipFields       = 'publicIP';
