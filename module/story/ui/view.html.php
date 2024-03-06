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

/* 模块列表。Module list. */
$moduleItems = array();
if(empty($modulePath))
{
    $moduleItems[] = '/';
}
else
{
    if($storyModule->branch and isset($branches[$storyModule->branch]))
    {
        $moduleItems[] = $branches[$storyModule->branch];
    }

    foreach($modulePath as $key => $module)
    {
        $moduleItems[] = $product->shadow ? $module->name : array('text' => $module->name, 'url' => createLink('product', 'browse', "productID=$story->product&branch=$story->branch&browseType=byModule&param=$module->id"));

        if(isset($modulePath[$key + 1])) $moduleItems[] = '/';
    }
}

/* Get min stage. */
$minStage    = $story->stage;
$stageList   = implode(',', array_keys($this->lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if($story->stages and $branches)
{
    foreach($story->stages as $branch => $stage)
    {
        if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = strpos($stageList, $stage);
        }
    }
}

/* Join mailto. */
$mailtoList = array();
if(!empty($story->mailto))
{
    foreach(explode(',', $story->mailto) as $account)
    {
        if(empty($account)) continue;
        $mailtoList[] = zget($users, trim($account));
    }
}
$mailtoList = implode($lang->comma, $mailtoList);

/* 根据需求类型，设置要激活的导航项。Active navbar item by story type. */
setPageData('activeMenuID', $story->type);

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && hasPriv('story', 'create'))
{
    $toolbar[] = array('icon' => 'plus', 'type' => 'primary', 'text' => $lang->story->create);
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
        'title'      => $lang->files,
        'control'    => 'fileList',
        'files'      => $story->files,
        'showDelete' => false,
        'object'     => $story
    );
}

/* 子需求列表。 */
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
        'cols'           => $cols,
        'userMap'        => $users,
        'data'           => array_values($story->children),
        'fixedLeftWidth' => '0.4'
    );
}

/* 基本信息。Legend basic items. */
$legendBasicItems = array();
if(!$product->shadow)
{
    $legendBasicItems[$lang->story->product] = common::hasPriv('product', 'view') ? array('control' => 'link', 'url' => createLink('product', 'view', "productID=$story->product"), 'text' => $product->name) : $product->name;
}
if($product->type !== 'normal')
{
    $legendBasicItems[$lang->story->branch] = common::hasPriv('product', 'browse') ? array('control' => 'link', 'url' => createLink('product', 'browse', "productID=$story->product&branch=$story->branch"), 'text' => $branches[$story->branch]) : $branches[$story->branch];
}
$legendBasicItems[$lang->story->module] = array
(
    'control' => 'breadcrumb',
    'items'   => $moduleItems
);
if($story->type != 'requirement' and $story->parent != -1 and !$hiddenPlan)
{
    $planTitleItems = array();
    if(isset($story->planTitle) && $story->planTitle)
    {
        foreach($story->planTitle as $planID => $planTitle)
        {
            $planTitleItems[] = hasPriv('productplan', 'view') ? $planTitle : array
            (
                'url'     => createLink('plan', 'view', "planID=$planID"),
                'text'    => $planTitle
            );
        }
    }
    $legendBasicItems[$lang->story->plan] = array
    (
        'control' => 'list',
        'items'   => $planTitleItems
    );
}
$legendBasicItems[$lang->story->source] = zget($lang->story->sourceList, $story->source, '');
$legendBasicItems[$lang->story->sourceNote] = array
(
    'control' => 'text',
    'content' => $story->sourceNote,
    'id'      => 'sourceNoteBox'
);
$legendBasicItems[$lang->story->status] = array
(
    'control' => 'status',
    'class'   => 'status-story',
    'status'  => $story->URChanged ? 'changed' : $story->status,
    'text'    => $this->processStatus('story', $story)
);
if(!$isRequirement)
{
    $legendBasicItems[$lang->story->stage] = array
    (
        'class' => 'stage-line',
        'text'  => zget($lang->story->stageList, $minStage, '')
    );
}
$legendBasicItems[$lang->story->category] = zget($lang->story->categoryList, $story->category);
$legendBasicItems[$lang->story->pri] = array
(
    'control' => 'pri',
    'pri'     => $story->pri,
    'text'    => $lang->story->priList
);
$legendBasicItems[$lang->story->estimate] = $story->estimate . $config->hourUnit;
if(in_array($story->source, $config->story->feedbackSource))
{
    $legendBasicItems[$lang->story->feedbackBy]  = $story->feedbackBy;
    $legendBasicItems[$lang->story->notifyEmail] = $story->notifyEmail;
}
$legendBasicItems[$lang->story->keywords]      = $story->keywords;
$legendBasicItems[$lang->story->legendMailto]  = $mailtoList;

/* 需求一生。Legend life items. */
$legendLifeItems = array();
$legendLifeItems[$lang->story->openedBy] = zget($users, $story->openedBy) . $lang->at . $story->openedDate;
$legendLifeItems[$lang->story->assignedTo] = $story->assignedTo ? zget($users, $story->assignedTo) . $lang->at . $story->assignedDate : null;
$legendLifeItems[$lang->story->reviewers] = array
(
    'children' => wg(div
    (
        setClass('row gap-2 flex-wrap'),
        array_values(array_map(function($reviewer, $result) use($users)
        {
            global $lang;
            return !empty($result) ? span(setClass('mr-2'), set::title($lang->story->reviewed), set::style(array('color' => '#cbd0db')), zget($users, $reviewer)) : span(setClass('mr-2'), set::title($lang->story->toBeReviewed), zget($users, $reviewer));
        }, array_keys($reviewers), array_values($reviewers)))
    ))
);
$legendLifeItems[$lang->story->reviewedDate] = $story->reviewedDate;
$legendLifeItems[$lang->story->closedBy] = $story->closedBy ? zget($users, $story->closedBy) . $lang->at . $story->closedDate : null;
$legendLifeItems[$lang->story->closedReason] = array
(
    'class'    => 'resolution',
    'children' => wg
    (
        $story->closedReason ? zget($lang->story->reasonList, $story->closedReason) : null,
        isset($story->extraStories[$story->duplicateStory]) ? a(set::href(inlink('view', "storyID=$story->duplicateStory")), set::title($story->extraStories[$story->duplicateStory]), "#{$story->duplicateStory} {$story->extraStories[$story->duplicateStory]}") : null
    )
);
$legendLifeItems[$lang->story->lastEditedBy] = zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate;

/* 初始化侧边栏标签页。Init tabs in sidebar. */
$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->story->legendBasicInfo)
    ->control('datalist')
    ->items($legendBasicItems);
$tabs[] = setting()
    ->group('basic')
    ->title($lang->story->legendLifeTime)
    ->control('datalist')
    ->items($legendLifeItems);

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
if($isStoryType && common::hasPriv('story', 'tasks'))
{
    $tabs[] = setting()
        ->group('relatives')
        ->title($lang->story->legendProjectAndTask)
        ->control('executionTaskList')
        ->tasks(flat($story->tasks))
        ->executions($story->executions);
}

/* 相关信息。 */
$tabs[] = setting()
        ->group('relatives')
        ->title($lang->story->legendRelated)
        ->control('storyRelatedList');

$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link text-base'), "#{$version}"),
    set::items($versions)
)) : null;

detail
(
    set::objectType('story'),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions($actions),
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
            (!common::hasPriv('assetlib', 'approveStory') && !common::hasPriv('assetlib', 'batchApproveStory')) ? formGroup
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
