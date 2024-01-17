<?php
namespace zin;

global $lang, $app;

$isShadowProduct = data('product.shadow');
$inQaApp         = $app->tab == 'qa';

$fields = defineFieldList('bug.create', 'bug');

$fields->field('product')->hidden($isShadowProduct && !$inQaApp);

$fields->field('project')->foldable(!$isShadowProduct)->className($isShadowProduct && $inQaApp ? 'w-1/4' : 'w-1/2')->className('full:w-1/4');

$fields->field('execution')
       ->label(data('bug.projectModel') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
       ->hidden(data('execution') && !data('execution.multiple'))
       ->className($isShadowProduct && $inQaApp ? 'w-1/4' : 'w-1/2')
       ->className('full:w-1/4')
       ->foldable(!$isShadowProduct);

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

if($isShadowProduct && $inQaApp) $fields->moveAfter('module', 'product');
