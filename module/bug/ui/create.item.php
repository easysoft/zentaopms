<?php
declare(strict_types=1);
namespace zin;

/* Define form group items. */
$items = array();

$items['product'] = array();
$items['product']['hidden']  = $product->shadow;
$items['product']['control'] = 'inputGroup';
$items['product']['items']   = array();
$items['product']['items'][] = array('type' => 'picker', 'name' => 'product', 'items' => $products, 'value' => $bug->productID);
$items['product']['items'][] = $product->type != 'normal' && isset($products[$bug->productID]) ? array( 'type' => 'picker', 'boxClass' => 'flex-none', 'width' => '100px', 'name' => 'branch', 'items' => $branches, 'value' => $bug->branch) : null;

$items['module'] = array();
$items['module']['control'] = array('type' => 'modulePicker', 'items' => $moduleOptionMenu, 'value' => $bug->moduleID, 'manageLink' => createLink('tree', 'browse', "rootID={$bug->productID}&view=bug&currentModuleID=0&branch={$bug->branch}"));

$items['openedBuild'] = array();
$items['openedBuild']['control'] = 'inputGroup';
$items['openedBuild']['items']   = array();
$items['openedBuild']['items'][] = array('type' => 'picker', 'name' => 'openedBuild[]', 'items' => $builds, 'multiple' => true);
$items['openedBuild']['items'][] = array('type' => 'addon', 'id' => 'buildBoxActions', 'className' => 'btn-group hidden');
$items['openedBuild']['items'][] = array('type' => 'btn', 'icon' => 'refresh text-primary', 'hint' =>  $lang->bug->loadAll, 'id' => 'allBuilds');

$items['assignedTo'] = array();
$items['assignedTo']['control'] = 'inputGroup';
$items['assignedTo']['items']   = array();
$items['assignedTo']['items'][] = array('type' => 'picker', 'name' => 'assignedTo', 'items' => $productMembers, 'value' => $bug->assignedTo);
$items['assignedTo']['items'][] = array('type' => 'btn', 'icon' => 'refresh text-primary', 'hint' =>  $lang->bug->loadAll, 'id' => 'allUsers');

$items['deadline'] = array();
$items['deadline']['control'] = 'datePicker';

$items['title'] = array();
$items['title']['width']   = 'full';
$items['title']['control'] = array('type' => 'colorInput', 'colorValue' => $bug->color);

$items['type'] = array();
$items['type']['width']   = '1/6';
$items['type']['items']   = $lang->bug->typeList;

$items['severity'] = array();
$items['severity']['width']   = '1/6';
$items['severity']['control'] = 'severityPicker';
$items['severity']['items']   = $lang->bug->severityList;

$items['pri'] = array();
$items['pri']['width']   = '1/6';
$items['pri']['control'] = 'priPicker';
$items['pri']['items']   = $lang->bug->priList;

$items['steps'] = array();
$items['steps']['width']    = 'full';
$items['steps']['control']  = 'editor';

$items['files'] = array();
$items['files']['width']    = 'full';
$items['files']['control']  = 'files';

$items['project'] = array();
$items['project']['foldable'] = true;
$items['project']['items']    = $projects;
$items['project']['value']    = $projectID;

$items['execution'] = array();
$items['execution']['id']       = 'executionBox';
$items['execution']['foldable'] = true;
$items['execution']['label']    = $bug->projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution;
$items['execution']['items']    = $executions;
$items['execution']['value']    = $executionID;

$items['story'] = array();
$items['story']['foldable'] = true;
$items['story']['items'] = empty($bug->stories) ? array() : $bug->stories;
$items['story']['value'] = $bug->storyID;

$items['task'] = array();
$items['task']['foldable'] = true;
$items['task']['items']    = array();
$items['task']['value']    = $bug->taskID;

if(!empty($executionType) && $executionType == 'kanban')
{
    $items['region'] = array();
    $items['region']['label'] = $lang->kanbancard->region;
    $items['region']['items'] = $regionPairs;
    $items['region']['value'] = $regionID;

    $items['lane'] = array();
    $items['lane']['label'] = $lang->kanbancard->lane;
    $items['lane']['items'] = $lanePairs;
    $items['lane']['value'] = $laneID;
}

$items['feedbackBy'] = array();
$items['feedbackBy']['foldable'] = true;

$items['notifyEmail'] = array();
$items['notifyEmail']['foldable'] = true;

$items['os'] = array();
$items['os']['foldable'] = true;
$items['os']['control']  = array('type' => 'picker', 'items' => $lang->bug->osList, 'multiple' => true);

$items['browser'] = array();
$items['browser']['foldable'] = true;
$items['browser']['control']  = array('type' => 'picker', 'items' => $lang->bug->browserList, 'multiple' => true);

$items['mailto'] = array();
$items['mailto']['foldable'] = true;
$items['mailto']['control']  = array('type' => 'mailto', 'value' => $bug->mailto);

$items['keywords'] = array();

$items['case']        = array('control' => 'hidden', 'value' => $bug->caseID);
$items['caseVersion'] = array('control' => 'hidden', 'value' => $bug->version);
$items['result']      = array('control' => 'hidden', 'value' => $bug->runID);
$items['testtask']    = array('control' => 'hidden', 'value' => $bug->testtask);
