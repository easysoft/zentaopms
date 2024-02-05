<?php
$config->metric = new stdclass();
$config->metric->scopeList     = array('program', 'project', 'product', 'execution', 'dept', 'user', 'system');
$config->metric->objectList    = array('program', 'line', 'product', 'project', 'productplan', 'execution', 'release', 'story', 'requirement', 'task', 'bug', 'case', 'user', 'effort', 'doc', 'feedback', 'risk', 'issue', 'review');
$config->metric->purposeList   = array('scale', 'qc', 'hour', 'cost', 'rate', 'time');
$config->metric->dateList      = array('year', 'month', 'week', 'day');
$config->metric->excludeGlobal = array('program', 'project', 'product', 'execution', 'user', 'dept');

$config->metric->maxSelectNum = 10;

global $lang;
$config->metric->actionList = array();
$config->metric->actionList['edit']['icon'] = 'edit';
$config->metric->actionList['edit']['text'] = $lang->edit;
$config->metric->actionList['edit']['hint'] = $lang->edit;
$config->metric->actionList['edit']['url']  = 'javascript:confirmEdit("{id}", "{isOldMetric}")';

$config->metric->actionList['implement']['icon']        = 'code';
$config->metric->actionList['implement']['text']        = $lang->metric->implement->common;
$config->metric->actionList['implement']['hint']        = $lang->metric->implement->common;
$config->metric->actionList['implement']['data-toggle'] = 'modal';
$config->metric->actionList['implement']['url']         = helper::createLink('metric', 'implement', 'metricID={id}');

$config->metric->actionList['delist']['icon'] = 'ban-circle';
$config->metric->actionList['delist']['text'] = $lang->metric->delist;
$config->metric->actionList['delist']['hint'] = $lang->metric->delist;
$config->metric->actionList['delist']['url']  = 'javascript:confirmDelist("{id}", "{name}")';

$config->metric->actionList['delete']['icon']         = 'trash';
$config->metric->actionList['delete']['hint']         = $lang->delete;
$config->metric->actionList['delete']['url']          = helper::createLink('metric', 'delete', 'metricID={id}');
$config->metric->actionList['delete']['class']        = 'ajax-submit';
$config->metric->actionList['delete']['data-confirm'] = $lang->metric->confirmDelete;

$config->metric->necessaryMethodList = array('getStatement', 'calculate', 'getResult');

$config->metric->oldScopeMap = array();
$config->metric->oldScopeMap['project'] = 'project';
$config->metric->oldScopeMap['product'] = 'product';
$config->metric->oldScopeMap['sprint']  = 'execution';

$config->metric->oldPurposeMap = array();
$config->metric->oldPurposeMap['scale']    = 'scale';
$config->metric->oldPurposeMap['duration'] = 'time';
$config->metric->oldPurposeMap['workload'] = 'hour';
$config->metric->oldPurposeMap['cost']     = 'cost';
$config->metric->oldPurposeMap['quality']  = 'qc';

$config->metric->oldObjectMap = array();
$config->metric->oldObjectMap['staff']       = 'user';
$config->metric->oldObjectMap['finance']     = 'task';
$config->metric->oldObjectMap['case']        = 'case';
$config->metric->oldObjectMap['bug']         = 'bug';
$config->metric->oldObjectMap['review']      = 'review';
$config->metric->oldObjectMap['stage']       = 'execution';
$config->metric->oldObjectMap['program']     = 'project';
$config->metric->oldObjectMap['softRequest'] = 'story';
$config->metric->oldObjectMap['userRequest'] = 'requirement';

$config->metric->chartConfig = new stdclass();
$config->metric->chartConfig->dataZoom = array();
$config->metric->chartConfig->dataZoom['type']            = 'slider';
$config->metric->chartConfig->dataZoom['backgroundColor'] = '#fff';
$config->metric->chartConfig->dataZoom['borderColor']     = '#0000004c';
$config->metric->chartConfig->dataZoom['fillerColor']     = '#0000004c';
$config->metric->chartConfig->dataZoom['bottom']          = '0';
$config->metric->chartConfig->dataZoom['brushSelect']     = false;
$config->metric->chartConfig->dataZoom['showDetail']      = false;
$config->metric->chartConfig->dataZoom['showDataShadow']  = false;
$config->metric->chartConfig->dataZoom['height']          = 10;
$config->metric->chartConfig->dataZoom['zoomLock']        = true;
$config->metric->chartConfig->dataZoom['handleSize']      = 0;
$config->metric->chartConfig->dataZoom['realtime']        = true;

$config->metric->chartConfig->grid = array();
$config->metric->chartConfig->grid['left']         = '10%';
$config->metric->chartConfig->grid['right']        = '10%';
$config->metric->chartConfig->grid['bottom']       = '5%';
$config->metric->chartConfig->grid['containLabel'] = true;

$config->metric->chartConfig->tooltip = array();
$config->metric->chartConfig->tooltip['trigger']      = 'axis';
$config->metric->chartConfig->tooltip['confine']      = true;
$config->metric->chartConfig->tooltip['extraCssText'] = 'max-height: 60%;overflow-y:scroll';
$config->metric->chartConfig->tooltip['enterable']    = true;
$config->metric->chartConfig->tooltip['axisPointer']  = array('type' => 'cross', 'label' => array('backgroundColor' => '#6a7985'));
