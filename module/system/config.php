<?php
$config->system = new stdclass();

$config->system->groupPrivs = array();
$config->system->groupPrivs['deleteBackup']  = 'backup|delete';
$config->system->groupPrivs['restoreBackup'] = 'backup|restore';
