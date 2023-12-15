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

$config->instance->actionList['ajaxStart']['icon']        = 'play';
$config->instance->actionList['ajaxStart']['className']   = 'ajax-submit';
$config->instance->actionList['ajaxStart']['hint']        = $lang->instance->start;
$config->instance->actionList['ajaxStart']['text']        = $lang->instance->start;
$config->instance->actionList['ajaxStart']['url']         = array('module' => 'instance', 'method' => 'ajaxStart', 'params' => 'id={id}');

$config->instance->actionList['ajaxStop']['icon']         = 'off';
$config->instance->actionList['ajaxStop']['className']    = 'ajax-submit';
$config->instance->actionList['ajaxStop']['hint']         = $lang->instance->stop;
$config->instance->actionList['ajaxStop']['text']         = $lang->instance->stop;
$config->instance->actionList['ajaxStop']['data-confirm'] = $lang->instance->notices['confirmStop'];
$config->instance->actionList['ajaxStop']['url']          = array('module' => 'instance', 'method' => 'ajaxStop', 'params' => 'id={id}');

$config->instance->actionList['ajaxUninstall']['icon']         = 'trash';
$config->instance->actionList['ajaxUninstall']['hint']         = $lang->instance->uninstall;
$config->instance->actionList['ajaxUninstall']['text']         = $lang->instance->uninstall;
$config->instance->actionList['ajaxUninstall']['className']    = 'ajax-submit';
$config->instance->actionList['ajaxUninstall']['data-confirm'] = $lang->instance->notices['confirmUninstall'];
$config->instance->actionList['ajaxUninstall']['url']          = array('module' => 'instance', 'method' => 'ajaxUninstall', 'params' => 'id={id}&type={type}');

$config->instance->actionList['visit']['icon']  = 'menu-my';
$config->instance->actionList['visit']['hint']  = $lang->instance->visit;
$config->instance->actionList['visit']['text']  = $lang->instance->visit;
$config->instance->actionList['visit']['class'] = 'ajax-submit ghost btn btn-default';
$config->instance->actionList['visit']['url']   = array('module' => 'instance', 'method' => 'visit', 'params' => 'id={id}&externalID={externalID}');

$config->instance->actionList['upgrade']['icon']        = 'refresh';
$config->instance->actionList['upgrade']['data-toggle'] = 'modal';
$config->instance->actionList['upgrade']['data-size']   = 'sm';
$config->instance->actionList['upgrade']['text']        = $lang->instance->upgrade;
$config->instance->actionList['upgrade']['hint']        = $lang->instance->upgrade;
$config->instance->actionList['upgrade']['url']         = helper::createLink('instance', 'upgrade', "id={id}");

$config->instance->actions = new stdclass();
$config->instance->actions->view = array();
$config->instance->actions->view['mainActions']   = array('visit', 'ajaxStart', 'ajaxStop', 'upgrade');
$config->instance->actions->view['suffixActions'] = array('ajaxUninstall');

$config->instance->devopsApps = array('gitea', 'gitlab', 'jenkins', 'sonarqube', 'nexus3', 'nexus');
