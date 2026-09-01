<?php
global $app, $config, $lang;

$config->user->execution = new stdclass();
$config->user->execution->dtable = new stdclass();
$config->user->execution->dtable->name['link'] = array('module' => 'execution', 'method' => 'view', 'params' => 'executionID={id}');

if(!isset($config->my)) $config->my = new stdclass();
if(!isset($config->my->task->dtable))
{
    $app->loadLang('task');
    if($config->edition != 'open') $app->loadLang('custom');

    $isEn = $app->getClientLang() == 'en';

    include $app->getModulePath('', 'my') . 'config/dtable/task.php';
}
$config->user->task = new stdclass();
$config->user->task->dtable = clone $config->my->task->dtable;
$config->user->task->dtable->defaultField = $config->user->defaultFields['task'];
unset($config->user->task->dtable->fieldList['actions']);
foreach($config->user->task->dtable->fieldList as $field => $fieldConfig)
{
    $config->user->task->dtable->fieldList[$field]['show'] = in_array($field, $config->user->task->dtable->defaultField);
}
$config->user->task->dtable->fieldList['id']['type']          = 'id';
$config->user->task->dtable->fieldList['id']['checkbox']      = false;
$config->user->task->dtable->fieldList['name']['data-toggle'] = 'modal';
$config->user->task->dtable->fieldList['name']['data-size']   = 'lg';
$config->user->task->dtable->fieldList['name']['show']        = true;
