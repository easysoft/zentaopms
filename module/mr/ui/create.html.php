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
jsVar('repoID', $repoID);

$fields = defineFieldList('mr');
$fields->field('title')->required(true)->width('1/2');
$fields->control('branchBox')->label($lang->mr->sourceBranch)->required(true)->control('inputGroup')->width('full')->itemBegin('sourceBranch')->control('picker')->items($branches)->value($activeBranch)->itemEnd()->item(array('control' => 'icon', 'name' => 'angle-double-right', 'class' => 'icon-x center mx-1'))->itemBegin('targetBranch')->control('picker')->items($branches)->value($defaultBranch)->itemEnd();
$fields->field('reviewer')->control(array('control' => 'picker', 'multiple' => true))->items($users)->required(true)->value($reviewers)->width('full');
$fields->field('desc')->label($lang->mr->description)->control('editor')->width('full');
$fields->field('message')->label('')->data(array('canMerge' => $canMerge, 'conflictFiles' => $conflictFiles))->hidden($canMerge)->control(array('control' => 'formTips', 'icon' => 'alert', 'text' => $mergeMessage))->width('full');

$fields->autoLoad('sourceBranch', 'reviewer,message');
$fields->autoLoad('targetBranch', 'reviewer,message');

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

formGridPanel
(
    setID('createForm'),
    set::modeSwitcher(false),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    on::change('[name=targetBranch]', 'loadReviewers'),
    on::change('[name=sourceBranch]', 'loadReviewers'),
    set::fields($fields),
    set::loadUrl(createLink('mr', 'create', "repoID={$repoID}&objectID={$objectID}&sourceBranch={sourceBranch}&targetBranch={targetBranch}")),
    on::formloaded()->call('loadReviewers', '>>> formload', jsRaw('event'), jsRaw('args'))
);

div(setID('createCheckList'), setClass('panel-form size-lg hidden'));
