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

$canBeChanged = common::canBeChanged('build', $build);
$menus        = $this->build->buildOperateMenu($build);
detailHeader
(
    to::title(entityLabel(set(array('entityID' => $build->id, 'level' => 1, 'text' => $build->name)))),
    !empty($menus) ? to::suffix(btnGroup(set::items($menus))) : null
);

jsVar('orderBy', $orderBy);
jsVar('buildID', $build->id);
jsVar('confirmDelete', $lang->build->confirmDelete);
jsVar('sortLink', helper::createLink('build', 'view', "buildID={$build->id}&type={type}&link={$link}&param={$param}&orderBy={orderBy}"));
jsVar('confirmUnlinkStory', $lang->build->confirmUnlinkStory);
jsVar('confirmUnlinkBug',   $lang->build->confirmUnlinkBug);
jsVar('unlinkStoryURL',     helper::createLink('build', 'unlinkStory', "buildID={$build->id}&storyID=%s"));
jsVar('unlinkBugURL',       helper::createLink('build', 'unlinkBug', "buildID={$build->id}&bugID=%s"));

/* Story's batch btn. */
$canBatchUnlinkStory = $canBeChanged && common::hasPriv('build', 'batchUnlinkStory');
$canBatchCloseStory  = $canBeChanged && common::hasPriv('story', 'batchClose');

$storyFootToolbar = array();
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->build->batchUnlink, 'btnType' => 'primary', 'data-type' => 'story', 'data-url' => inlink('batchUnlinkStory', "build={$build->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->story->batchClose,    'btnType' => 'primary', 'data-type' => 'story', 'data-url' => createLink('story', 'batchClose', "productID={$build->product}"));

/* Bug's batch btn. */
$canBatchUnlinkBug = $canBeChanged && common::hasPriv('build', 'batchUnlinkBug');
$canBatchCloseBug  = $canBeChanged && common::hasPriv('bug', 'batchClose');

$bugFootToolbar = array();
if($canBatchUnlinkBug) $bugFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->build->batchUnlink, 'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => inlink('batchUnlinkBug', "build={$build->id}"));
if($canBatchCloseBug)  $bugFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->bug->batchClose, 'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => createLink('bug', 'batchClose', "productID={$build->product}"));

/* Integrated builds or single build. */
if($build->execution)
{
    $executionTitle = empty($multipleProject) ? $lang->build->project : ($executionType ? $lang->build->executionAB : $lang->build->execution);
    $executionName  = zget($executions, $build->execution);
}
else
{
    $builds = '';
    foreach(explode(',', $build->builds) as $buildID)
    {
        if($buildID) $builds .= html::a($this->createLink('build', 'view', "buildID=$buildID") . "#app={$app->tab}", zget($buildPairs, $buildID)) . $lang->comma;
    }
}

/* Init table data for dtable. */
$stories = initTableData($stories, $config->build->story->dtable->fieldList, $this->build);
$bugs    = initTableData($bugs, $config->build->bug->dtable->fieldList, $this->build);

if($canBeChanged)
{
    $linkBtnList = array();
    if(common::hasPriv('build', 'linkStory'))
    {
        $linkBtnList[] = array(
            'text'        => $lang->build->linkStory,
            'icon'        => 'link',
            'url'         => inlink('linkStory', "buildID={$build->id}&browseType=story"),
            'class'       => 'btn link-story',
            'type'        => 'primary',
            'data-toggle' => 'modal'
        );
    }

    if(common::hasPriv('build', 'linkBug'))
    {
        $linkBtnList[] = array(
            'text'        => $lang->build->linkBug,
            'icon'        => 'bug',
            'url'         => inlink('linkBug', "buildID={$build->id}&browseType=bug"),
            'class'       => 'btn link-bug',
            'type'        => 'primary',
            'data-toggle' => 'modal'
        );
    }

    btnGroup(
        set::items($linkBtnList),
        setClass('link-btns hidden')
    );
}

detailBody
(
    sectionList(
        btnGroup(
            setClass('right-menu px-6')
        ),
        tabs
        (
            set::class('w-full'),

            /* Linked story table. */
            tabPane
            (
                to::prefix(icon('lightbulb')),
                set::key('story'),
                set::title($lang->build->stories),
                set::active($type == 'story'),
                dtable
                (
                    set::userMap($users),
                    set::cols(array_values($config->build->story->dtable->fieldList)),
                    set::data($stories),
                    set::checkable($canBatchUnlinkStory || $canBatchCloseStory),
                    set::sortLink(jsRaw('window.createSortLink')),
                    set::footToolbar($storyFootToolbar),
                    set::footPager(
                        usePager(null, 'storyPager'),
                        set::recPerPage($storyPager->recPerPage),
                        set::recTotal($storyPager->recTotal),
                        set::linkCreator(helper::createLink('build', 'view', "buildID={$build->id}&type=story&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                    ),
                )
            ),

            /* Resolved bugs table. */
            tabPane
            (
                to::prefix(icon('bug')),
                set::key('bug'),
                set::title($lang->build->bugs),
                set::active($type == 'bug'),
                dtable
                (
                    set::userMap($users),
                    set::cols(array_values($config->build->bug->dtable->fieldList)),
                    set::data($bugs),
                    set::checkable($canBatchUnlinkBug || $canBatchCloseBug),
                    set::sortLink(jsRaw('window.createSortLink')),
                    set::footToolbar($bugFootToolbar),
                    set::footPager(
                        usePager(null, 'bugPager'),
                        set::recPerPage($bugPager->recPerPage),
                        set::recTotal($bugPager->recTotal),
                        set::linkCreator(helper::createLink('build', 'view', "buildID={$build->id}&type=bug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                    ),
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
                    set::footPager(
                        usePager(null, 'generatedBugPager'),
                        set::recPerPage($generatedBugPager->recPerPage),
                        set::recTotal($generatedBugPager->recTotal),
                        set::linkCreator(helper::createLink('build', 'view', "buildID={$build->id}&type=generatedBug&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$generatedBugPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                    ),
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
                            html::a($build->scmPath, $build->scmPath, '_blank')
                        ),
                        item
                        (
                            set::name($lang->build->filePath),
                            html::a($build->filePath, $build->filePath, '_blank')
                        ),
                        item
                        (
                            set::name($lang->build->desc),
                            $build->desc
                        ),
                    ),
                    h::hr(set::class('mt-6')),
                    section
                    (
                        set::title($lang->files),
                        set::content(''),
                        set::useHtml(true)
                    ),
                    h::hr(set::class('mt-6')),
                    history()
                )
            )
        )
    )
);

render();
