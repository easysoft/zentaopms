<?php
namespace zin;

global $lang, $app;

$app->loadLang('build');
$app->loadLang('release');

$isShadowProduct   = data('product.shadow');
$noMultipleProject = (string)data('project.multiple') === '0';
$isOriginalProduct = (int)data('bug.productID') === (int)data('productID');
$resultFiles       = data('resultFiles') ?: array();
$copyFiles         = data('bug.files') ?: array();
$builds            = data('builds') ?: array();
$files             = $copyFiles ? array_values($copyFiles) : array_values($resultFiles);

if($files)
{
    foreach($files as $key => $fileInfo)
    {
        if($fileInfo->extra != '') unset($files[$key]);
    }
}

$fields = defineFieldList('bug.create', 'bug');

$fields->field('product')->hidden($isShadowProduct && $isOriginalProduct);

$fields->field('deadline')->width($isShadowProduct && $isOriginalProduct ? '1/2' : '1/4')->className('full:w-1/2');

$fields->field('project')
     ->width($noMultipleProject ? '1/2' : '1/4')
     ->className($isShadowProduct  ? 'full:w-1/2' : 'full:w-1/4');

 $fields->field('execution')
     ->label(data('project.model') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
     ->hidden($noMultipleProject)
     ->width('1/4')
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
        ->className(count($builds) > 1 || !data('executionID') ? 'hidden' : '')
        ->itemEnd();
}

if(common::hasPriv('release', 'create') && !common::isTutorialMode())
{
    $fields->field('openedBuild')
        ->required(true)
        ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal', 'data-size' => 'lg','id' => 'createRelease'))
        ->text($lang->release->create)->hint($lang->release->create)
        ->url(createLink('release', 'create', 'productID=' . data('bug.productID') . '&branch=' . data('bug.branch') . '&status=normal'))
        ->className(count($builds) > 1 || data('executionID') ? 'hidden' : '')
        ->itemEnd();
}

$fields->field('title')->required(true)->width($isShadowProduct ? '1/2' : 'full');

$fields->field('steps')
    ->width('full')
    ->control(array('control' => 'editor', 'templateType' => 'bug'));

$fields->field('files')
    ->width('full')
    ->control(array('control' => 'fileSelector', 'defaultFiles' => array_values($files)));

$fields->field('case')->foldable();

$fields->field('story')->foldable();

$fields->field('task')->foldable();

$fields->field('feedbackBy')->foldable();

$fields->field('notifyEmail')->foldable();

$fields->field('browser')->foldable();

$fields->field('os')->foldable();

$fields->field('mailto')->foldable();

$fields->field('keywords')->foldable();

$fields->field('module')->width($isShadowProduct && $isOriginalProduct ? '1/2' : '1/4')->className('full:w-1/2');

$fields->field('openedBuild')->width('1/4')->className('full:w-1/2');

$fields->field('assignedTo')->width('1/4')->className('full:w-1/2');

$fields->field('fileList')->label($lang->bug->copyFiles)->control('hidden')->value($copyFiles ? $copyFiles : $resultFiles);

if($isShadowProduct && !$isOriginalProduct) $fields->moveAfter('module', 'product');
