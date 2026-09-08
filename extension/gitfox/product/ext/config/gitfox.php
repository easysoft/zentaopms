<?php
/* GitFox 应用列表为空时不自动跳转到创建应用页。 */
$config->product->skipRedirectMethod .= ',browse,';

$config->product->dtable = new stdclass();
$config->product->dtable->fieldList = array();
$config->product->dtable->fieldList['id']['title'] = $lang->idAB;
$config->product->dtable->fieldList['id']['type']  = 'id';

$config->product->dtable->fieldList['name']['name']     = 'name';
$config->product->dtable->fieldList['name']['title']    = $lang->product->name;
$config->product->dtable->fieldList['name']['type']     = 'title';
$config->product->dtable->fieldList['name']['flex']     = 1;
$config->product->dtable->fieldList['name']['sortType'] = true;
$config->product->dtable->fieldList['name']['align']    = 'left';
$config->product->dtable->fieldList['name']['border']   = 'right';

$config->product->dtable->fieldList['repo']['name']     = 'repo';
$config->product->dtable->fieldList['repo']['title']    = $lang->product->repo;
$config->product->dtable->fieldList['repo']['sortType'] = false;
$config->product->dtable->fieldList['repo']['width']    = 0.3;

$config->product->dtable->fieldList['desc']['name']     = 'desc';
$config->product->dtable->fieldList['desc']['title']    = $lang->product->desc;
$config->product->dtable->fieldList['desc']['type']     = 'desc';
$config->product->dtable->fieldList['desc']['sortType'] = false;

$config->product->dtable->fieldList['actions']['name']     = 'actions';
$config->product->dtable->fieldList['actions']['title']    = $lang->actions;
$config->product->dtable->fieldList['actions']['type']     = 'actions';
$config->product->dtable->fieldList['actions']['minWidth'] = '60';
$config->product->dtable->fieldList['actions']['fixed']    = 'right';
$config->product->dtable->fieldList['actions']['menu']     = array('edit', 'close|activate', 'delete');
$config->product->dtable->fieldList['actions']['list']     = $config->product->actionList;
