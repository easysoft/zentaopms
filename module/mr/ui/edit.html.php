<?php
declare(strict_types=1);
/**
 * The create view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = defineFieldList('mr');
$fields->field('sourceBranch')->required(true)->value($MR->sourceBranch)->disabled(true)->width('1/2');
$fields->field('targetBranch')->required(true)->value($MR->targetBranch)->disabled(true)->width('1/2');
$fields->field('title')->required(true)->value($MR->title)->width('1/2');
$fields->field('reviewer')->control(array('control' => 'picker', 'multiple' => true))->items($users)->required(true)->value($reviewers)->disabled(true)->width('1/2');
$fields->field('desc')->label($lang->mr->description)->control('editor')->value($MR->desc)->width('full');

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

formGridPanel
(
    setID('editForm'),
    set::modeSwitcher(false),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    set::fields($fields),
    set::loadUrl(createLink('mr', 'edit', "id={$MR->id}")),
);
