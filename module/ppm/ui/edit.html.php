<?php
declare(strict_types=1);
/**
 * The create view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;
$notInDevOps = in_array($this->app->tab, array('execution', 'project'));

$fields = defineFieldList('ppm');
if($notInDevOps) $fields->field('repo')->label($lang->ppm->repo)->value(zget($repo, 'name', ''))->disabled(true)->width('1/2')->wrapAfter(true);
$fields->field('sourceBranch')->required(true)->value($ppm->sourceBranch)->disabled(true)->width('1/2');
$fields->field('targetBranch')->required(true)->value($ppm->targetBranch)->disabled(true)->width('1/2');
$fields->field('title')->required(true)->value($ppm->title)->width('1/2');
$fields->field('reviewer')->control(array('control' => 'picker', 'multiple' => true))->items($users)->required(true)->value($reviewers)->disabled(true)->width('1/2');
$fields->field('desc')->label($lang->ppm->description)->control(array('control' => 'editor', 'upload-url' => 'disabled', 'placeholder' => $lang->ppm->description))->value($ppm->desc)->width('full');

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
    set::loadUrl(createLink('ppm', 'edit', "id={$ppm->id}")),
);
