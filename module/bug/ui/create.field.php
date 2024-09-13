<?php
namespace zin;

global $lang, $app;

$isShadowProduct   = data('product.shadow');
$noMultipleProject = data('project.multiple') === '0';
$isOriginalProduct = (int)data('bug.productID') === (int)data('productID');
$resultFiles       = data('resultFiles');
$resultFiles       = array_values($resultFiles);

$fields = defineFieldList('bug.create', 'bug');

$fields->field('product')->hidden($isShadowProduct && $isOriginalProduct);

$fields->field('project')
    ->foldable(!$isShadowProduct)
    ->wrapAfter($isShadowProduct && $isOriginalProduct  && $noMultipleProject)
    ->className($isShadowProduct && !$isOriginalProduct && !$noMultipleProject ? 'w-1/4' : 'w-1/2')
    ->className(($isShadowProduct && $isOriginalProduct) || $noMultipleProject ? 'full:w-1/2' : 'full:w-1/4');

$fields->field('execution')
    ->label(data('project.model') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
    ->hidden($noMultipleProject)
    ->className($isShadowProduct && !$isOriginalProduct ? 'w-1/4' : 'w-1/2')
    ->className($isShadowProduct && $isOriginalProduct ? 'full:w-1/2' : 'full:w-1/4')
    ->foldable(!$isShadowProduct);

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

$fields->field('steps')
    ->width('full')
    ->control(array('control' => 'editor', 'templateType' => 'bug'));

$fields->field('files')
    ->width('full')
    ->control('fileSelector', array('defaultFiles' => $resultFiles));

$fields->field('story')->foldable();

$fields->field('task')->foldable();

$fields->field('feedbackBy')->foldable();

$fields->field('notifyEmail')->foldable();

$fields->field('browser')->foldable();

$fields->field('os')->foldable();

$fields->field('mailto')->foldable();

$fields->field('keywords')->foldable();

$fields->field('module')->className($isShadowProduct ? 'w-1/2' : 'w-1/4')->className('full:w-1/2');

$fields->field('openedBuild')->className($isShadowProduct ? 'w-1/2' : 'w-1/4')->className('full:w-1/2');

$fields->field('resultFiles')->control('hidden')->value(data('resultFiles'));

if($isShadowProduct && !$isOriginalProduct) $fields->moveAfter('module', 'product');
