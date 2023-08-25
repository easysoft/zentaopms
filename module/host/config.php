<?php
$config->host->create       = new stdclass();
$config->host->edit         = new stdclass();
$config->host->changestatus = new stdclass();
$config->host->create->requiredFields = 'name,tags,provider,intranet,extranet';
$config->host->edit->requiredFields   = 'name,tags,provider,intranet,extranet';
$config->host->create->intFields      = 'cpuNumber,cpuCores';
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

$config->host->create = new stdclass();
$config->host->create->requiredFields = 'name,hostType,extranet,cpuCores,memory,diskSize,vsoft';
$config->host->create->ipFields       = 'extranet';

$config->host->defaultPort = '55001';

$config->host->edit = new stdclass();
$config->host->edit->requiredFields = 'name,hostType,cpuCores,memory,diskSize,vsoft';

$config->host->imageListUrl = 'https://pkg.qucheng.com/zenagent/list.json';

$config->host->cpuCoreList = array(1 => 1, 2 => 2, 4 => 4, 6 => 6, 8 => 8, 10 => 10, 12 => 12, 16 => 16, 24 => 24, 32 => 32, 64 => 64);

$config->host->initBash = 'curl -sSL https://pkg.qucheng.com/zenagent/zagent.sh | bash /dev/stdin -k %s -z %s';

$config->host->editor = new stdclass();
$config->host->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->host->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->host->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->host->automation = new stdclass();
$config->host->automation->zenAgentURL   = 'https://github.com/easysoft/zenagent/blob/main/guide/deploy/index.md';
$config->host->automation->ztfURL        = 'https://ztf.im/';
$config->host->automation->kvmURL        = 'https://www.linux-kvm.org/page/Documents';
$config->host->automation->nginxURL      = 'http://nginx.org/en/docs/';
$config->host->automation->novncURL      = 'https://novnc.com/info.html';
$config->host->automation->websockifyURL = 'https://github.com/novnc/websockify';