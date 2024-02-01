<?php
global $app, $lang;

$config->pivot->dtable = new stdclass();

/**
 * Columns configuration of the bug assign table.
 */
$config->pivot->dtable->bugAssign = new stdclass();
$config->pivot->dtable->bugAssign->fieldList['assignedTo']['title'] = $lang->pivot->user;
$config->pivot->dtable->bugAssign->fieldList['assignedTo']['align'] = 'center';

$config->pivot->dtable->bugAssign->fieldList['productName']['title'] = $lang->pivot->product;
$config->pivot->dtable->bugAssign->fieldList['productName']['type']  = 'html';
$config->pivot->dtable->bugAssign->fieldList['productName']['align'] = 'center';

$config->pivot->dtable->bugAssign->fieldList['bugCount']['title'] = $lang->pivot->bug;
$config->pivot->dtable->bugAssign->fieldList['bugCount']['align'] = 'center';

$config->pivot->dtable->bugAssign->fieldList['total']['title'] = $lang->pivot->total;
$config->pivot->dtable->bugAssign->fieldList['total']['align'] = 'center';

/**
 * Columns configuration of the bug create table.
 */
$app->loadLang('bug');

$config->pivot->dtable->bugCreate = new stdclass();
$config->pivot->dtable->bugCreate->fieldList['openedBy']['title'] = $lang->bug->openedBy;
$config->pivot->dtable->bugCreate->fieldList['openedBy']['width'] = 120;
$config->pivot->dtable->bugCreate->fieldList['openedBy']['align'] = 'center';
$config->pivot->dtable->bugCreate->fieldList['openedBy']['fixed'] = 'left';

$config->pivot->dtable->bugCreate->fieldList['unResolved']['title'] = $lang->bug->unResolved;
$config->pivot->dtable->bugCreate->fieldList['unResolved']['align'] = 'center';

foreach($lang->bug->resolutionList as $resolutionType => $resolution)
{
    if(empty($resolutionType)) continue;
    $config->pivot->dtable->bugCreate->fieldList[$resolutionType] = array('title' => $resolution, 'align' => 'center');
}

$config->pivot->dtable->bugCreate->fieldList['validRate']['title'] = $lang->pivot->validRate;
$config->pivot->dtable->bugCreate->fieldList['validRate']['align'] = 'center';
$config->pivot->dtable->bugCreate->fieldList['validRate']['fixed'] = 'right';

$config->pivot->dtable->bugCreate->fieldList['total']['title'] = $lang->pivot->total;
$config->pivot->dtable->bugCreate->fieldList['total']['hint']  = $lang->pivot->validRateTips;
$config->pivot->dtable->bugCreate->fieldList['total']['align'] = 'center';
$config->pivot->dtable->bugCreate->fieldList['total']['fixed'] = 'right';

/**
 * Columns configuration of the product summary table.
 */
$app->loadLang('product');
$app->loadLang('productplan');
$app->loadLang('story');

$config->pivot->dtable->productSummary = new stdclass();
$config->pivot->dtable->productSummary->fieldList['name']['title'] = $lang->product->name;
$config->pivot->dtable->productSummary->fieldList['name']['link']  = common::hasPriv('product', 'view') ? array('module' => 'product', 'method' => 'view', 'params' => 'id={id}') : '';
$config->pivot->dtable->productSummary->fieldList['name']['width'] = 200;
$config->pivot->dtable->productSummary->fieldList['name']['fixed'] = 'left';

$config->pivot->dtable->productSummary->fieldList['PO']['title'] = $lang->pivot->PO;
$config->pivot->dtable->productSummary->fieldList['PO']['width'] = 80;
$config->pivot->dtable->productSummary->fieldList['PO']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['planTitle']['title'] = $lang->productplan->common;
$config->pivot->dtable->productSummary->fieldList['planTitle']['width'] = 120;

$config->pivot->dtable->productSummary->fieldList['planBegin']['title'] = $lang->productplan->begin;
$config->pivot->dtable->productSummary->fieldList['planBegin']['width'] = 90;

$config->pivot->dtable->productSummary->fieldList['planEnd']['title'] = $lang->productplan->end;
$config->pivot->dtable->productSummary->fieldList['planEnd']['width'] = 90;

$config->pivot->dtable->productSummary->fieldList['storyDraft']['title'] = $lang->story->statusList['draft'];
$config->pivot->dtable->productSummary->fieldList['storyDraft']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyDraft']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['storyReviewing']['title'] = $lang->story->statusList['reviewing'];
$config->pivot->dtable->productSummary->fieldList['storyReviewing']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyReviewing']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['storyActive']['title'] = $lang->story->statusList['active'];
$config->pivot->dtable->productSummary->fieldList['storyActive']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyActive']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['storyChanging']['title'] = $lang->story->statusList['changing'];
$config->pivot->dtable->productSummary->fieldList['storyChanging']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyChanging']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['storyClosed']['title'] = $lang->story->statusList['closed'];
$config->pivot->dtable->productSummary->fieldList['storyClosed']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyClosed']['align'] = 'center';

$config->pivot->dtable->productSummary->fieldList['storyTotal']['title'] = $lang->pivot->total;
$config->pivot->dtable->productSummary->fieldList['storyTotal']['width'] = 60;
$config->pivot->dtable->productSummary->fieldList['storyTotal']['align'] = 'center';
$config->pivot->dtable->productSummary->fieldList['storyTotal']['fixed'] = 'right';

/**
 * Columns configuration of the project deviation table.
 */
$config->pivot->dtable->projectDeviation = new stdclass();
$config->pivot->dtable->projectDeviation->fieldList['executionID']['title'] = $lang->pivot->id;
$config->pivot->dtable->projectDeviation->fieldList['executionID']['width'] = 80;
$config->pivot->dtable->projectDeviation->fieldList['executionID']['align'] = 'center';
$config->pivot->dtable->projectDeviation->fieldList['executionID']['fixed'] = 'left';

$config->pivot->dtable->projectDeviation->fieldList['projectName']['title'] = $lang->pivot->project;
$config->pivot->dtable->projectDeviation->fieldList['projectName']['link']  = common::hasPriv('project', 'index') ? array('module' => 'project', 'method' => 'index', 'params' => 'id={projectID}') : '';

$config->pivot->dtable->projectDeviation->fieldList['executionName']['title'] = $lang->pivot->execution;
$config->pivot->dtable->projectDeviation->fieldList['executionName']['type']  = 'html';

$config->pivot->dtable->projectDeviation->fieldList['estimate']['title'] = $lang->pivot->estimate;
$config->pivot->dtable->projectDeviation->fieldList['estimate']['width'] = 80;
$config->pivot->dtable->projectDeviation->fieldList['estimate']['align'] = 'center';
$config->pivot->dtable->projectDeviation->fieldList['estimate']['fixed'] = 'right';

$config->pivot->dtable->projectDeviation->fieldList['consumed']['title'] = $lang->pivot->consumed;
$config->pivot->dtable->projectDeviation->fieldList['consumed']['width'] = 80;
$config->pivot->dtable->projectDeviation->fieldList['consumed']['align'] = 'center';
$config->pivot->dtable->projectDeviation->fieldList['consumed']['fixed'] = 'right';

$config->pivot->dtable->projectDeviation->fieldList['deviation']['title'] = $lang->pivot->deviation;
$config->pivot->dtable->projectDeviation->fieldList['deviation']['width'] = 80;
$config->pivot->dtable->projectDeviation->fieldList['deviation']['type']  = 'html';
$config->pivot->dtable->projectDeviation->fieldList['deviation']['align'] = 'center';
$config->pivot->dtable->projectDeviation->fieldList['deviation']['fixed'] = 'right';

$config->pivot->dtable->projectDeviation->fieldList['deviationRate']['title'] = $lang->pivot->deviationRate;
$config->pivot->dtable->projectDeviation->fieldList['deviationRate']['width'] = 80;
$config->pivot->dtable->projectDeviation->fieldList['deviationRate']['type']  = 'html';
$config->pivot->dtable->projectDeviation->fieldList['deviationRate']['align'] = 'center';
$config->pivot->dtable->projectDeviation->fieldList['deviationRate']['fixed'] = 'right';

/**
 * Columns configuration of the workload table.
 */
$config->pivot->dtable->workload = new stdclass();
$config->pivot->dtable->workload->fieldList['user']['title'] = $lang->pivot->user;
$config->pivot->dtable->workload->fieldList['user']['width'] = 100;
$config->pivot->dtable->workload->fieldList['user']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['user']['fixed'] = 'left';

$config->pivot->dtable->workload->fieldList['projectName']['title'] = $lang->pivot->project;
$config->pivot->dtable->workload->fieldList['projectName']['type']  = 'title';
$config->pivot->dtable->workload->fieldList['projectName']['link']  = common::hasPriv('project', 'view') ? array('module' => 'project', 'method' => 'view', 'params' => 'projectID={projectID}') : '';
$config->pivot->dtable->workload->fieldList['projectName']['align'] = 'left';

$config->pivot->dtable->workload->fieldList['executionName']['title'] = $lang->pivot->execution;
$config->pivot->dtable->workload->fieldList['executionName']['type']  = 'html';
$config->pivot->dtable->workload->fieldList['executionName']['align'] = 'left';

$config->pivot->dtable->workload->fieldList['executionTasks']['title'] = $lang->pivot->task;
$config->pivot->dtable->workload->fieldList['executionTasks']['width'] = 80;
$config->pivot->dtable->workload->fieldList['executionTasks']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['executionTasks']['fixed'] = 'right';

$config->pivot->dtable->workload->fieldList['executionHours']['title'] = $lang->pivot->remain;
$config->pivot->dtable->workload->fieldList['executionHours']['width'] = 80;
$config->pivot->dtable->workload->fieldList['executionHours']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['executionHours']['fixed'] = 'right';

$config->pivot->dtable->workload->fieldList['totalTasks']['title'] = $lang->pivot->taskTotal;
$config->pivot->dtable->workload->fieldList['totalTasks']['width'] = 80;
$config->pivot->dtable->workload->fieldList['totalTasks']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['totalTasks']['fixed'] = 'right';

$config->pivot->dtable->workload->fieldList['totalHours']['title'] = $lang->pivot->manhourTotal;
$config->pivot->dtable->workload->fieldList['totalHours']['width'] = 80;
$config->pivot->dtable->workload->fieldList['totalHours']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['totalHours']['fixed'] = 'right';

$config->pivot->dtable->workload->fieldList['workload']['title'] = $lang->pivot->workloadAB;
$config->pivot->dtable->workload->fieldList['workload']['width'] = 80;
$config->pivot->dtable->workload->fieldList['workload']['align'] = 'center';
$config->pivot->dtable->workload->fieldList['workload']['fixed'] = 'right';
