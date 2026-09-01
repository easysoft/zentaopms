<?php
namespace zin;

global $lang, $app, $config;

$isShadowProduct   = data('product.shadow');
$noMultipleProject = (string)data('project.multiple') === '0';
$isOriginalProduct = (int)data('bug.productID') === (int)data('productID');
$resultFiles       = data('resultFiles');
$copyFiles         = data('bug.files');
$files             = $copyFiles ? array_values($copyFiles) : array_values($resultFiles);

$productID   = (int)data('bug.productID');
$branchID    = data('bug.branch');
$branchID    = ($branchID === '' || $branchID === null || $branchID === 'all') ? 0 : $branchID;
$moduleID    = (int)data('bug.moduleID');
$storyID     = (int)data('bug.storyID');
$caseID      = (int)data('bug.caseID');
$executionID = (int)data('executionID');
$projectID   = (int)data('projectID');
$objectID    = $executionID ? $executionID : $projectID;
$storyItems  = createLink('story', 'ajaxGetProductStories', "productID={$productID}&branch={$branchID}&moduleID={$moduleID}&storyID={$storyID}&onlyOption=false&status=active&limit=0&type=full&hasParent=0&objectID={$objectID}");
$caseItems   = createLink('bug', 'ajaxGetProductCases', "productID={$productID}&branchID={$branchID}&search={search}&limit=0&caseID={$caseID}");

if($files)
{
    foreach($files as $key => $fileInfo)
    {
        if($fileInfo->objectType == 'stepResult')
        {
            $fileInfo->extra = '';
            continue;
        }
        if($fileInfo->extra != '') unset($files[$key]);
    }
}

$fields = defineFieldList('bug.create', 'bug');

$fields->field('product')->hidden($isShadowProduct && $isOriginalProduct);

$fields->field('deadline')->className($isShadowProduct && $isOriginalProduct ? 'w-1/2' : 'w-1/4')->className('full:w-1/2');

$fields->field('project')
     ->className($noMultipleProject ? 'w-1/2' : 'w-1/4')
     ->className($isShadowProduct  ? 'full:w-1/2' : 'full:w-1/4');

 $fields->field('execution')
     ->label(data('project.model') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
     ->hidden($noMultipleProject)
     ->className('w-1/4')
     ->className($isShadowProduct  ? 'full:w-1/2' : 'full:w-1/4');

$fields->field('plan')
    ->label($lang->bug->plan)
    ->className('w-1/2 full:w-1/2')
    ->hidden($noMultipleProject)
    ->items(data('plans'))
    ->foldable();

if(common::hasPriv('build', 'create'))
{
    $fields->field('openedBuild')
        ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal', 'id' => 'createBuild', 'data-size' => 'lg'))
        ->text($lang->build->create)->hint($lang->build->create)
        ->url(createLink('build', 'create', 'executionID=' . data('executionID') . '&productID=' . data('bug.productID') . '&projectID=' . data('projectID')))
        ->className(count(data('builds')) > 1 || !data('executionID') ? 'hidden' : '')
        ->itemEnd();
}

if(common::hasPriv('release', 'create') && !common::isTutorialMode())
{
    $fields->field('openedBuild')
        ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal', 'data-size' => 'lg','id' => 'createRelease'))
        ->text($lang->release->create)->hint($lang->release->create)
        ->url(createLink('release', 'create', 'productID=' . data('bug.productID') . '&branch=' . data('bug.branch') . '&status=normal'))
        ->className(count(data('builds')) > 1 || data('executionID') ? 'hidden' : '')
        ->itemEnd();
}

$fields->field('title')->className($isShadowProduct ? 'full:w-1/2' : 'full:w-full');

$fields->field('steps')
    ->width('full')
    ->control(array('control' => 'editor', 'templateType' => 'bug'));

$fields->field('files')
    ->width('full')
    ->control(array('control' => 'fileSelector', 'defaultFiles' => array_values($files)));

$fields->field('case')
    ->control(array('control' => 'picker', 'items' => $caseItems))
    ->items(false)
    ->value($caseID)
    ->foldable();

$fields->field('story')
    ->control(array('control' => 'picker', 'items' => $storyItems))
    ->items(false)
    ->value($storyID)
    ->foldable();

$fields->field('task')->foldable();

$fields->field('feedbackBy')->foldable();

$fields->field('notifyEmail')->foldable();

$fields->field('browser')->foldable();

$fields->field('os')->foldable();

$fields->field('mailto')->foldable();

$fields->field('keywords')->foldable();

$fields->field('module')->className($isShadowProduct && $isOriginalProduct ? 'w-1/2' : 'w-1/4')->className('full:w-1/2');

$fields->field('openedBuild')->className('w-1/4')->className('full:w-1/2');

$fields->field('assignedTo')->className('w-1/4')->className('full:w-1/2');

$fields->field('fileList')->control('hidden')->value($copyFiles ? $copyFiles : $resultFiles);

if($isShadowProduct && !$isOriginalProduct) $fields->moveAfter('module', 'product');
