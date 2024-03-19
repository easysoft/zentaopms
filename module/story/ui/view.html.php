<?php
declare(strict_types=1);
/**
 * The view view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

use function zin\utils\flat;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

$isInModal     = isInModal();
$isRequirement = $story->type == 'requirement';
$isStoryType   = $story->type == 'story';
$actions       = $isInModal ? null : $this->story->buildOperateMenu($story, 'view', $project ? $project : null);

/* 版本列表。Version list. */
$versions = array();
for($i = $story->version; $i >= 1; $i--)
{
    $versionItem = setting()
        ->text("#{$i}")
        ->url(inlink('view', "storyID={$story->id}&version=$i&param=0&storyType={$story->type}"));

    if($isInModal)
    {
        $versionItem->set(array('data-load' => 'modal', 'data-target' => '.modal-content'));
    }

    $versionItem->selected($version == $i);
    $versions[] = $versionItem;
}

/* 根据需求类型，设置要激活的导航项。Active navbar item by story type. */
setPageData('activeMenuID', $story->type);

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && hasPriv('story', 'create'))
{
    $otherParam = 'storyID=&projectID=';
    if($app->rawModule == 'projectstory' || $app->tab == 'project') $otherParam = "storyID=&projectID={$this->session->project}";
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->story->create,
        'url'  => createLink('story', 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}&$otherParam&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type")
    );
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->story->legendSpec)
    ->control('html')
    ->content(empty($story->spec) ? $lang->noDesc : $story->spec);
$sections[] = setting()
    ->title($lang->story->legendVerify)
    ->control('html')
    ->content(empty($story->verify) ? $lang->noDesc : $story->verify);
if($story->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $story->files,
        'showDelete' => false,
        'object'     => $story
    );
}

/* 子需求列表。 Children list. */
if($story->children)
{
    $cols['id']         = $config->story->dtable->fieldList['id'];
    $cols['title']      = $config->story->dtable->fieldList['title'];
    $cols['pri']        = $config->story->dtable->fieldList['pri'];
    $cols['assignedTo'] = $config->story->dtable->fieldList['assignedTo'];
    $cols['estimate']   = $config->story->dtable->fieldList['estimate'];
    $cols['status']     = $config->story->dtable->fieldList['status'];
    $cols['actions']    = $config->story->dtable->fieldList['actions'];
    $cols['id']['checkbox']        = false;
    $cols['title']['nestedToggle'] = false;
    $cols['actions']['minWidth']   = 190;
    if($isInModal)
    {
        $cols['title']['data-toggle'] = 'modal';
        $cols['title']['data-size']   = 'lg';
    }

    foreach(array_keys($cols) as $fieldName) $cols[$fieldName]['sortType'] = false;

    $options = array('users' => $users);
    foreach($story->children as $child) $child = $this->story->formatStoryForList($child, $options);

    $sections[] = array
    (
        'title'          => $isRequirement ? $lang->story->story : $lang->story->children,
        'control'        => 'dtable',
        'id'             => 'table-story-children',
        'cols'           => $cols,
        'userMap'        => $users,
        'data'           => array_values($story->children),
        'fixedLeftWidth' => '0.4'
    );
}

/* 初始化侧边栏标签页。Init tabs in sidebar. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->story->legendBasicInfo)
    ->control('storyBasicInfo')
    ->statusText($this->processStatus('story', $story));

/* 需求一生。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->story->legendLifeTime)
    ->control('storyLifeInfo');

if($twins)
{
    $tabs[] = setting()
        ->group('relatives')
        ->title($lang->story->twins)
        ->control('twinsStoryList')
        ->branches($branches)
        ->items($twins);
}

if($this->config->URAndSR && !$hiddenURS && $config->vision != 'or')
{
    $tabs[] = setting()
        ->group('relatives')
        ->title($isStoryType ? $lang->story->requirement : $lang->story->story)
        ->control('linkedStoryList')
        ->items($relations)
        ->story($story);
}
if($isStoryType && hasPriv('story', 'tasks'))
{
    $tabs[] = setting()
        ->group('relatives')
        ->title($lang->story->legendProjectAndTask)
        ->control('executionTaskList')
        ->tasks(flat($story->tasks))
        ->executions($story->executions);
}

/* 相关信息。 Related info. */
$tabs[] = setting()
        ->group('relatives')
        ->title($lang->story->legendRelated)
        ->control('storyRelatedList');

$parentTitle = $story->parent > 0 ? set::parentTitle($story->parentName) : null;
$parentUrl   = $story->parent > 0 ? set::parentUrl(createLink('story', 'view', "storyID={$story->parent}&version=0&param=0&storyType=$story->type")) : null;

$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link font-normal text-base'), "#{$version}"),
    set::items($versions)
)) : null;

$deletedLabel = $story->deleted ? to::title(label($lang->story->deleted, setClass('danger'))) : null;

detail
(
    set::objectType('story'),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions($actions),
    $parentTitle,
    $parentUrl,
    $versionBtn,
    $deletedLabel
);

/**
 * Notice: 旗舰版和 IPD 版在项目的需求详情页面有导入需求库按钮，需要这个对话框。
 * 应该将此对话框放置在对应的版本中比较合适。
 */
if(isset($libs))
{
    modal
    (
        setID('importToLib'),
        set::title($lang->story->importToLib),
        form
        (
            set::action($this->createLink('story', 'importToLib', "storyID=$story->id")),
            formGroup
            (
                set::label($lang->story->lib),
                picker
                (
                    set::name('lib'),
                    set::items($libs),
                    set::required(true)
                )
            ),
            (!hasPriv('assetlib', 'approveStory') && !hasPriv('assetlib', 'batchApproveStory')) ? formGroup
            (
                set::label($lang->story->approver),
                picker
                (
                    set::name('assignedTo'),
                    set::items($approvers)
                )
            ) : null,
            set::submitBtnText($lang->import),
            set::actions(array('submit'))
        )
    );
}
