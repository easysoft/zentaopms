<?php
$config->system = new stdclass();

$config->system->groupPrivs = array();
$config->system->groupPrivs['dashboard']     = 'backup|index';
$config->system->groupPrivs['deletebackup']  = 'backup|delete';
$config->system->groupPrivs['restorebackup'] = 'backup|restore';
