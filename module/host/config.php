<?php
$config->host->create       = new stdclass();
$config->host->edit         = new stdclass();
$config->host->changestatus = new stdclass();
$config->host->create->requiredFields = 'name,zap,tags,provider,intranet,extranet';
$config->host->edit->requiredFields   = 'name,zap,tags,provider,intranet,extranet';
$config->host->create->intFields      = 'cpuNumber,cpuCores,zap';
$config->host->create->ipFields       = 'intranet,extranet';

$config->host->editor = new stdclass();
$config->host->editor->changestatus = array('id' => 'reason', 'tools' => 'simple');

global $lang;
$config->host->featureBar = array(
    array(
        'text'   => $lang->host->featureBar['browse']['all'],
        'active' => false,
        'url'    => helper::createLink('host', 'browse'),
    ),
    array(
        'text'   => $lang->host->featureBar['browse']['serverroom'],
        'active' => false,
        'url'    => helper::createLink('host', 'treemap', 'type=serverroom'),
    ),
    array(
        'text'   => $lang->host->featureBar['browse']['group'],
        'active' => false,
        'url'    => helper::createLink('host', 'treemap', 'type=group'),
    ),
);

$config->host->actions = new stdclass();
$config->host->actions->view = array();
$config->host->actions->view['suffixActions'] = array('edit', 'delete');