<?php
$config->instance = new stdclass;
$config->instance->keepDomainList = array();
$config->instance->keepDomainList['console'] = 'console';
$config->instance->keepDomainList['demo']    = 'demo';
$config->instance->keepDomainList['s3']      = 's3';
$config->instance->keepDomainList['s3-api']  = 's3-api';

$config->instance->seniorChartList = array();
$config->instance->seniorChartList['zentao']     = ['zentao-biz', 'zentao-max'];
$config->instance->seniorChartList['zentao-biz'] = ['zentao-max'];

$config->instance->adminer = new stdclass();
$config->instance->adminer->dbTypes = array();
$config->instance->adminer->dbTypes['mysql']      = 'mysql';
$config->instance->adminer->dbTypes['postgresql'] = 'pgsql';

$features = explode(',', getenv('QUICKON_FEATURES', ''));
$config->instance->enableAutoRestore = in_array('auto-rollback', $features);

global $lang, $app;
$app->loadLang('space');

$config->instance->actionList['start']['icon']        = 'play';
$config->instance->actionList['start']['className']   = 'ajax-submit';
$config->instance->actionList['start']['hint']        = $lang->instance->start;
$config->instance->actionList['start']['text']        = $lang->instance->start;
$config->instance->actionList['start']['url']         = array('module' => 'instance', 'method' => 'ajaxStart', 'params' => 'id={id}');

$config->instance->actionList['stop']['icon']         = 'off';
$config->instance->actionList['stop']['className']    = 'ajax-submit';
$config->instance->actionList['stop']['hint']         = $lang->instance->stop;
$config->instance->actionList['stop']['text']         = $lang->instance->stop;
$config->instance->actionList['stop']['data-confirm'] = $lang->instance->notices['confirmStop'];
$config->instance->actionList['stop']['url']          = array('module' => 'instance', 'method' => 'ajaxStop', 'params' => 'id={id}');

$config->instance->actionList['uninstall']['icon']         = 'trash';
$config->instance->actionList['uninstall']['hint']         = $lang->instance->uninstall;
$config->instance->actionList['uninstall']['text']         = $lang->instance->uninstall;
$config->instance->actionList['uninstall']['className']    = 'ajax-submit';
$config->instance->actionList['uninstall']['data-confirm'] = $lang->instance->notices['confirmUninstall'];
$config->instance->actionList['uninstall']['url']          = array('module' => 'instance', 'method' => 'ajaxUninstall', 'params' => 'id={id}');

$config->instance->actionList['visit']['icon'] = 'menu-my';
$config->instance->actionList['visit']['hint'] = $lang->instance->visit;
$config->instance->actionList['visit']['text'] = $lang->instance->visit;
$config->instance->actionList['visit']['target'] = '_blank';
$config->instance->actionList['visit']['url']  = array('module' => 'instance', 'method' => 'visit', 'params' => 'id={id}&externalID={externalID}');

$config->instance->actionList['upgrade']['icon']        = 'refresh';
$config->instance->actionList['upgrade']['data-toggle'] = 'modal';
$config->instance->actionList['upgrade']['data-size']   = 'sm';
$config->instance->actionList['upgrade']['text']        = $lang->instance->upgrade;
$config->instance->actionList['upgrade']['hint']        = $lang->instance->upgrade;
$config->instance->actionList['upgrade']['url']         = helper::createLink('instance', 'upgrade', "id={id}");

$config->instance->actions = new stdclass();
$config->instance->actions->view = array();
$config->instance->actions->view['mainActions']   = array('visit', 'start', 'stop', 'upgrade');
$config->instance->actions->view['suffixActions'] = array('uninstall');

$config->instance->devopsApps = array(89 => 'gitea', 58 => 'gitlab', 59 => 'jenkins', 60 => 'sonarqube', 118 => 'nexus');
