<?php
$config->webhook->create = new stdclass();
$config->webhook->create->requiredFields = 'name, url';

$config->webhook->edit = new stdclass();
$config->webhook->edit->requiredFields = 'name, url';

/* Unset entry to hide actions. */
$config->webhook->objectTypes = array();
$config->webhook->objectTypes['product']     = array('opened', 'edited', 'closed', 'undeleted'); 
$config->webhook->objectTypes['story']       = array('opened', 'edited', 'commented', 'frombug', 'changed', 'reviewed', 'closed', 'activated');
$config->webhook->objectTypes['productplan'] = array('opened', 'edited'); 
$config->webhook->objectTypes['project']     = array('opened', 'edited', 'started', 'delayed', 'suspended', 'closed', 'activated', 'undeleted');
$config->webhook->objectTypes['task']        = array('opened', 'edited', 'commented', 'assigned', 'confirmed', 'started', 'finished', 'paused', 'canceled', 'restarted', 'closed', 'activated');
$config->webhook->objectTypes['bug']         = array('opened', 'edited', 'commented', 'assigned', 'confirmed', 'bugconfirmed', 'resolved', 'closed', 'activated');
$config->webhook->objectTypes['case']        = array('opened', 'edited', 'commented', 'reviewed', 'confirmed');
$config->webhook->objectTypes['testtask']    = array('opened', 'edited', 'started', 'blocked', 'closed', 'activated');
$config->webhook->objectTypes['todo']        = array('opened', 'edited');
