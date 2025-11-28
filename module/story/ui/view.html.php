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

jsVar('gradeGroup', $gradeGroup);

$isInModal     = isInModal();
$isRequirement = $story->type == 'requirement';
$isStoryType   = $story->type == 'story';
if(empty($executionID)) $executionID = 0;

$story->estimate = helper::formatHours($story->estimate);

/* 版本列表。Version list. */
$versions = array();
for($i = $story->version; $i >= 1; $i--)
{
    $versionItem = setting()
        ->text("#{$i}")
        ->url(inlink('view', "storyID={$story->id}&version=$i&param=0&storyType={$story->type}"));

    if($isInModal) $versionItem->set(array('data-load' => 'modal', 'data-target' => '.modal.show:not(.modal-hide)'));

    $versionItem->selected($version == $i);
    $versions[] = $versionItem;
}

/* 根据需求类型，设置要激活的导航项。Active navbar item by story type. */
if($app->tab == 'product') setPageData('activeMenuID', $story->type);

$canModify  = true;
if($app->tab == 'project' && isset($project)) $canModify = common::canModify('project', $project);
if($app->tab == 'execution' && isset($execution)) $canModify = common::canModify('execution', $execution);

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(empty($story->frozen) && !$isInModal && hasPriv($story->type, 'create') && $canModify)
{
    $otherParam = 'storyID=&projectID=';
    if($app->rawModule == 'projectstory' || $app->tab == 'project') $otherParam = "storyID=&projectID={$this->session->project}";
    $toolbar[] = array
    (
        'icon'     => 'plus',
        'type'     => 'primary',
        'text'     => $lang->story->create,
        'url'      => createLink($story->type, 'create', "productID={$story->product}&branch={$story->branch}&moduleID={$story->module}&$otherParam&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type"),
        'data-app' => $app->tab
    );
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->story->legendSpec)
    ->control('html')
    ->content(empty($story->spec) ? $lang->noDesc : $story->spec);
if($this->config->vision != 'lite')
{
    $sections[] = setting()
        ->title($lang->story->legendVerify)
        ->control('html')
        ->content(empty($story->verify) ? $lang->noDesc : $story->verify);
}
if($story->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $story->files,
        'showDelete' => false,
        'padding'    => false,
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
    if($this->config->vision != 'lite') $cols['stage'] = $config->story->dtable->fieldList['stage'];
    $cols['actions']    = $config->story->dtable->fieldList['actions'];
    $cols['title']['title']      = $lang->story->name;
    $cols['id']['checkbox']      = false;
    $cols['actions']['minWidth'] = 200;
    if($isInModal)
    {
        $cols['title']['data-toggle'] = 'modal';
        $cols['title']['data-size']   = 'lg';
    }
    if(!hasPriv('story', 'view')) unset($cols['title']['link']);

    if(($story->type == 'story' && $config->vision == 'or') || ($story->vision == 'or' && $config->vision == 'rnd')) unset($cols['actions']);

    foreach(array_keys($cols) as $fieldName) $cols[$fieldName]['sortType'] = false;

    $options = array('users' => $users);
    foreach($story->children as $child) $child = $this->story->formatStoryForList($child, $options, $child->type, $maxGradeGroup);

    $sections[] = array
    (
        'title'        => $lang->story->children,
        'control'      => 'dtable',
        'id'           => 'table-story-children',
        'cols'         => $cols,
        'userMap'      => $users,
        'data'         => array_values($story->children),
        'onRenderCell' => jsRaw('renderChildCell')
    );
}

/* 初始化侧边栏标签页。Init tabs in sidebar. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->story->legendBasicInfo)
    ->control(array('control' => 'storyBasicInfo', 'hiddenPlan' => $config->vision == 'or' && $story->type != 'story' ? true : false))
    ->statusText($story->URChanged ? $lang->story->URChanged : $this->processStatus('story', $story));

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

if(!in_array($config->vision, array('lite', 'or')) && $canModify)
{
    $tabs['linkStories'] = setting()
        ->group('relatives')
        ->title($lang->story->linkStories)
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
if($config->vision != 'lite')
{
    $tabs['storyRelatedList'] = setting()
        ->group('relatives')
        ->title($lang->story->legendRelated)
        ->control('storyRelatedList');
}

$parentTitle = $story->parent > 0 ? set::parentTitle($story->parentName) : null;
$parentUrl   = $story->parent > 0 ? set::parentUrl(createLink($story->parentType, 'view', "storyID={$story->parent}&version=0&param=0&storyType=$story->type")) : null;

$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link font-normal text-base'), "#{$version}"),
    set::items($versions)
)) : null;

if($isInModal) $config->{$story->type}->actionList['recall']['url']['params'] = str_replace('&from=view&', '&from=modal&', $config->{$story->type}->actionList['recall']['url']['params']);
if($story->status == 'changing') $config->{$story->type}->actionList['recall']['text'] = $lang->story->recallChange;

$this->loadModel('repo');
$hasRepo    = $this->repo->getListByProduct($story->product, implode(',', $config->repo->gitServiceTypeList), 1);
$actions    = $story->deleted || !$canModify ? array() : $this->loadModel('common')->buildOperateMenu($story, $story->type);
$hasDivider = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);

foreach($actions as $key => $action)
{
    if(!empty($story->frozen) && isset($action['icon']) && in_array($action['icon'], array('edit', 'change', 'trash', 'split', 'copy')))
    {
        unset($actions[$key]);
        continue;
    }

    if(!$hasDivider && isset($action['type']) && $action['type'] == 'divider')
    {
        unset($actions[$key]);
        continue;
    }

    if(isset($action['key']) && $action['key'] == 'createTask' && ($story->type != 'story' || in_array($story->status, array('reviewing', 'closed')) || $story->isParent == '1' || $app->tab == 'product' || $isInModal || ($app->tab == 'project' && !empty($project) && $project->multiple)))
    {
        unset($actions[$key]);
        continue;
    }

    if(isset($action['key']) && $action['key'] == 'testcase' && ($story->type != 'story' || $story->isParent == '1'))
    {
        unset($actions[$key]);
        continue;
    }

    if(!$hasRepo && isset($action['icon']) && $action['icon'] == 'treemap')
    {
        unset($actions[$key]);
        continue;
    }

    if(isset($action['icon']) && $action['icon'] == 'split')
    {
        $objectID = $app->tab == 'project' ? ($project->multiple ? $projectID : $executionID) : $executionID;
        if($story->grade < $maxGradeGroup[$story->type] && empty($story->hasOtherTypeChild) && common::hasPriv($story->type, 'batchCreate'))
        {
            $actions[$key]['url'] = createLink($story->type, 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id&executionID=$objectID");
        }
        elseif($story->type == 'epic' && common::hasPriv('requirement', 'batchCreate') && empty($story->hasSameTypeChild) && !($this->config->epic->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['epic']))
        {
            $actions[$key]['url'] = createLink('requirement', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id&executionID=$objectID");
        }
        elseif($story->type == 'requirement' && common::hasPriv('story', 'batchCreate') && empty($story->hasSameTypeChild) && !($this->config->requirement->gradeRule == 'stepwise' && $story->grade < $maxGradeGroup['requirement']))
        {
            $actions[$key]['url'] = createLink('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id&executionID=$objectID");
        }
        else
        {
            unset($actions[$key]);
            continue;
        }
    }

    if($isInModal && isset($actions[$key]) && !isset($actions[$key]['data-toggle']) && !isset($actions[$key]['data-load']))
    {
        $actions[$key]['data-load'] = 'modal';
        $actions[$key]['data-size'] = 'lg';
    }
}

if($config->edition == 'ipd' && $config->vision == 'rnd' && $story->type == 'story') $story = $this->story->getAffectObject(array(), $story->type, $story);
if($config->edition == 'ipd' && $story->type == 'story' && !empty($story->confirmeActionType))
{
    $actions   = array();
    $method    = $story->confirmeActionType == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
    $url       = helper::createLink('story', $method, "objectID=$story->id&object=story&extra={$story->confirmeObjectID}");
    $actions[] = array('name' => $method, 'text' => $lang->story->$method, 'icon' => 'search', 'hint' => $lang->story->$method, 'url' => $url, 'data-toggle' => 'modal');
}

$hiddenActions = $config->vision == 'or' && $story->type == 'story';

detail
(
    set::urlFormatter(array('{id}' => $story->id, '{type}' => $story->type, '{product}' => $story->product, '{branch}' => $story->branch, '{module}' => $story->module, '{execution}' => isset($executionID) ? $executionID : (isset($projectID) ? $projectID : 0))),
    set::objectType('story'),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::backBtn(array('url' => createLink('product', 'browse', "productID=$story->product&$story->branch=&browseType=unclosed&param=0&storyType=$story->type"))),
    $hiddenActions ? null : set::actions(array_values($actions)),
    $parentTitle,
    $parentUrl,
    $versionBtn
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
