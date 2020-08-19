<?php
if(!isset($config->holiday)) $config->holiday = new stdclass();
$config->holiday->require = new stdclass();
$config->holiday->require->create = 'name,begin,end,year';
$config->holiday->require->edit   = 'name,begin,end,year';
