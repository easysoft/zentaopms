<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$buildModule  = $app->tab == 'project' ? 'projectbuild' : 'build';
$canBeChanged = common::canBeChanged($buildModule, $build);
$menus        = $this->build->buildOperateMenu($build);
$decodeParam  = helper::safe64Decode($param);

$buildItems = array();
foreach($buildPairs as $id => $name)
{
    $buildItem['text']   = $name;
    $buildItem['url']    = helper::createLink($buildModule, 'view', "buildID=$id");
    $buildItem['active'] = $id == $build->id;

    $buildItems[] = $buildItem;
}

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('GLOBAL'),
            $lang->goback
        ),
        dropdown
        (
            btn
            (
                setClass('ghost text-primary bg-light bg-opacity-50'),
                entityLabel
                (
                    set::entityID($build->id),
                    set::level(2),
                    set::text($build->name),
                    set::textClass('text-primary'),
                    set::idClass('id-label'),
                ),
            ),
            set::items($buildItems),
        )
    ),
    !empty($menus) ? to::suffix(btnGroup(set::items($menus))) : null
);

jsVar('initLink',       $link);
jsVar('type',           $type);
jsVar('orderBy',        $orderBy);
jsVar('buildID',        $build->id);
jsVar('sortLink',       helper::createLink($buildModule, 'view', "buildID={$build->id}&type={type}&link={$link}&param={$param}&orderBy={orderBy}"));
jsVar('confirmDelete',  $lang->build->confirmDelete);
jsVar('currentAccount', $app->user->account);
jsVar('buildProduct',   $build->product);

/* Story's batch btn. */
$canBatchUnlinkStory = $canBeChanged && common::hasPriv($buildModule, 'batchUnlinkStory');
$canBatchCloseStory  = $canBeChanged && common::hasPriv('story', 'batchClose');

$storyFootToolbar = array();
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('className' => 'btn secondary size-sm batch-btn ajax-btn', 'text' => $lang->build->batchUnlink, 'btnType' => 'primary', 'data-type' => 'story', 'data-url' => inlink('batchUnlinkStory', "build={$build->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('className' => 'btn secondary size-sm batch-btn',          'text' => $lang->story->batchClose,  'btnType' => 'primary', 'data-type' => 'story', 'data-url' => createLink('story', 'batchClose', "productID={$build->product}"));

/* Bug's batch btn. */
$canBatchUnlinkBug = $canBeChanged && common::hasPriv($buildModule, 'batchUnlinkBug');
$canBatchCloseBug  = $canBeChanged && common::hasPriv('bug', 'batchClose');

$bugFootToolbar = array();
if($canBatchUnlinkBug) $bugFootToolbar['items'][] = array('className' => 'btn secondary size-sm batch-btn', 'text' => $lang->build->batchUnlink, 'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => inlink('batchUnlinkBug', "build={$build->id}"));
if($canBatchCloseBug)  $bugFootToolbar['items'][] = array('className' => 'btn secondary size-sm batch-btn', 'text' => $lang->bug->batchClose, 'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => createLink('bug', 'batchClose', "productID={$build->product}"));

/* Integrated builds or single build. */
if($build->execution)
{
    $executionTitle = empty($multipleProject) ? $lang->build->project : ($execution->type ? $lang->build->executionAB : $lang->build->execution);
    $executionName  = zget($executions, $build->execution, '');
}
else
{
    $builds = '';
    foreach(explode(',', $build->builds) as $buildID)
    {
        if($buildID) $builds .= html::a($this->createLink($buildModule, 'view', "buildID=$buildID") . "#app={$app->tab}", zget($buildPairs, $buildID)) . $lang->comma;
    }
}

/* Init table data for dtable. */
$config->build->bug->dtable->fieldList['actions']['list']['unlinkBug']['url']     = helper::createLink($buildModule, 'unlinkBug', "buildID={$build->id}&bugID={id}");
$config->build->story->dtable->fieldList['actions']['list']['unlinkStory']['url'] = helper::createLink($buildModule, 'unlinkStory', "buildID={$build->id}&storyID={id}");

$stories = initTableData($stories, $config->build->story->dtable->fieldList, $this->build);
$bugs    = initTableData($bugs, $config->build->bug->dtable->fieldList, $this->build);

detailBody
(
    sectionList(
        tabs
        (
            set::className('w-full'),

            /* Linked story table. */
            tabPane
            (
                to::prefix(icon('lightbulb')),
                set::key('story'),
                set::title($lang->build->stories),
                set::active($type == 'story'),
                div
                (
                    setClass('tabnActions'),
                    !common::hasPriv($buildModule, 'linkStory') ? null : btn(set::text($lang->build->linkStory), setClass('primary link'), set::icon('link'), set::onclick('showLink(this)'), set('data-type', 'story'), set('data-linkurl', inlink('linkStory', "buildID={$build->id}" . (($link == 'true' && $type == 'story') ? $decodeParam : "&browseType=&param=")))),
                ),
                dtable
                (
                    set::id('storyDTable'),
                    set::userMap($users),
                    set::cols(array_values($config->build->story->dtable->fieldList)),
                    set::data($stories),
                    set::checkable($canBatchUnlinkStory || $canBatchCloseStory),
                    set::sortLink(jsRaw('window.createSortLink')),
                    set::footToolbar($storyFootToolbar),
                    set::footPager(usePager('storyPager', '', array(
                        'recPerPage'  => $storyPager->recPerPage,
                        'recTotal'    => $storyPager->recTotal,
                        'linkCreator' => helper::createLink($buildModule, 'view', "buildID={$build->id}&type=story&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}")
                    ))),
                )
            ),

            /* Resolved bugs table. */
            tabPane
            (
                to::prefix(icon('bug')),
                set::key('bug'),
                set::title($lang->build->bugs),
                set::active($type == 'bug'),
                div
                (
                    setClass('tabnActions'),
                    !common::hasPriv($buildModule, 'linkBug') ? null : btn(set::text($lang->build->linkBug), setClass('primary link'), set::icon('link'), set::onclick('showLink(this)'), set('data-type', 'bug'), set('data-linkurl', inlink('linkBug', "buildID={$build->id}" . (($link == 'true' && $type == 'bug') ? $decodeParam : "&browseType=&param=")))),
                ),
                dtable
                (
                    set::id('bugDTable'),
                    set::userMap($users),
                    set::cols(array_values($config->build->bug->dtable->fieldList)),
                    set::data($bugs),
                    set::checkable($canBatchUnlinkBug || $canBatchCloseBug),
                    set::sortLink(jsRaw('window.createSortLink')),
                    set::footToolbar($bugFootToolbar),
                    set::footPager(usePager('bugPager', '', array(
                        'recPerPage'  => $bugPager->recPerPage,
                        'recTotal'    => $bugPager->recTotal,
                        'linkCreator' => helper::createLink($buildModule, 'view', "buildID={$build->id}&type=bug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}")
                    ))),
                )
            ),

            /* Generated bugs table. */
            tabPane
            (
                to::prefix(icon('bug')),
                set::key('generatedBug'),
                set::title($lang->build->generatedBugs),
                set::active($type == 'generatedBug'),
                dtable
                (
                    set::userMap($users),
                    set::cols(array_values($config->build->generatedBug->dtable->fieldList)),
                    set::data(array_values($generatedBugs)),
                    set::sortLink(jsRaw('window.createSortLink')),
                    set::footPager(usePager('generatedBugPager', '', array(
                        'recPerPage'  => $generatedBugPager->recPerPage,
                        'recTotal'    => $generatedBugPager->recTotal,
                        'linkCreator' => helper::createLink($buildModule, 'view', "buildID={$build->id}&type=generatedBug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$generatedBugPager->recTotal}&recPerPage={recPerPage}&page={page}")
                    ))),
                )
            ),

            /* Basic info block. */
            tabPane
            (
                to::prefix(icon('flag')),
                set::key('buildInfo'),
                set::title($lang->build->basicInfo),
                div(
                    tableData
                    (
                        item
                        (
                            set::name($lang->build->product),
                            $build->productName
                        ),
                        $build->productType != 'normal' ? item
                        (
                            set::name($lang->build->branch),
                            $branchName
                        ) : null,
                        item
                        (
                            set::name($lang->build->name),
                            $build->name
                        ),
                        $build->execution ? item
                        (
                            set::name($executionTitle),
                            ltrim($executionName, '/')
                        ) : item
                        (
                            set::name($builds),
                            rtrim($builds, $lang->comma)
                        ),
                        item
                        (
                            set::name($lang->build->builder),
                            zget($users, $build->builder)
                        ),
                        item
                        (
                            set::name($lang->build->date),
                            $build->date
                        ),
                        item
                        (
                            set::name($lang->build->scmPath),
                            h::a
                            (
                                $build->scmPath,
                                set('href', $build->scmPath),
                                set('target', '_blank'),
                                set('rel', 'nooperner noreferrer'),
                            )
                        ),
                        item
                        (
                            set::name($lang->build->filePath),
                            h::a
                            (
                                $build->filePath,
                                set('href', $build->filePath),
                                set('target', '_blank'),
                                set('rel', 'nooperner noreferrer'),
                            )
                        ),
                        item
                        (
                            set::name($lang->build->desc),
                            html($build->desc)
                        ),
                    ),
                    $build->files ? h::hr(set::className('mt-6')) : null,
                    section
                    (
                        $build->files ? fileList
                        (
                            set::files($build->files),
                        ) : null,
                    ),
                    h::hr(set::className('mt-6')),
                    history()
                )
            )
        )
    )
);

render();
