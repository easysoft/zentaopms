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
jsVar('repoID', $repoID);

$fields = defineFieldList('ppm');
if($notInDevOps) $fields->field('repoID')->label($lang->ppm->repo)->required(true)->control('picker')->items($repoPairs)->value($repoID)->width('1/2')->wrapAfter(true);
$fields->field('sourceBranch')->required(true)->control('picker')->items($branches)->value($activeBranch)->width('1/2');
$fields->field('targetBranch')->required(true)->control('picker')->items($branches)->value($defaultBranch)->width('1/2');
$fields->field('title')->required(true)->value($commitMessage)->width('1/2');
$fields->field('reviewer')->control(array('control' => 'picker', 'multiple' => true))->items($users)->required(true)->value($reviewers)->width('1/2');
$fields->field('desc')->label($lang->ppm->description)->control(array('control' => 'editor', 'upload-url' => 'disabled', 'placeholder' => $lang->ppm->description))->width('full');
$fields->field('message')->label('')->data(array('canMerge' => $canMerge, 'conflictFiles' => $conflictFiles, 'mergeMessage' => $mergeMessage))->hidden($canMerge)->control(array('control' => 'formTips', 'text' => $mergeMessage))->width('full');

if($notInDevOps) $fields->autoLoad('repoID', 'sourceBranch,targetBranch,title,reviewer,message');
$fields->autoLoad('sourceBranch', 'reviewer,message,title');
$fields->autoLoad('targetBranch', 'reviewer,message,title');

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
    set::fields($fields),
    set::loadUrl(createLink('ppm', 'create', "repoID={repoID}&objectID={$objectID}&sourceBranch={sourceBranch|base64}&targetBranch={targetBranch|base64}")),
    on::formloaded()->call('loadReviewers', '>>> formload', jsRaw('event'), jsRaw('args'))
);

div(setID('createCheckList'), setClass('panel-form size-lg hidden'));
