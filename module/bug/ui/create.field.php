<?php
namespace zin;

global $lang, $app;

$isShadowProduct   = data('product.shadow');
$noMultipleProject = data('project.multiple') === '0';
$inQA              = $app->tab == 'qa';

$fields = defineFieldList('bug.create', 'bug');

$fields->field('product')->hidden($isShadowProduct && !$inQA);

$fields->field('project')->foldable(!$isShadowProduct)->className($isShadowProduct && $inQA && !$noMultipleProject ? 'w-1/4' : 'w-1/2')->className('full:w-1/4');

$fields->field('execution')
       ->label(data('project.modal') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
       ->hidden($noMultipleProject)
       ->className($isShadowProduct && $inQA ? 'w-1/4' : 'w-1/2')
       ->className('full:w-1/4')
       ->foldable(!$isShadowProduct);

$fields->field('openedBuild')
    ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal'))
    ->icon('plus')->text($lang->build->create)->hint($lang->build->create)
    ->url(createLink('build', 'create', 'executionID=' . data('executionID') . '&productID=' . data('bug.productID') . '&projectID=' . data('projectID')))
    ->className(count(data('builds')) > 1 || !data('executionID') ? 'hidden' : '')
    ->itemEnd()
    ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal'))
    ->icon('plus')->text($lang->release->create)->hint($lang->release->create)
    ->url(createLink('release', 'create', 'productID=' . data('bug.productID') . '&branch=' . data('bug.branch')))
    ->className(count(data('builds')) > 1 || data('executionID') ? 'hidden' : '')
    ->itemEnd();

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

if($isShadowProduct && $inQA) $fields->moveAfter('module', 'product');
