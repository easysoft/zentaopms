<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

$actions = $this->release->buildOperateViewMenu($release);
detailHeader
(
    to::title(entityLabel(set(array('entityID' => $release->id, 'level' => 1, 'text' => $release->name)))),
    !empty($actions) ? to::suffix(btnGroup(set::items($actions))) : null
);

jsVar('type', $type);
jsVar('orderBy', $orderBy);
jsVar('sortLink', helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={orderBy}"));
jsVar('storyCases', $storyCases);
jsVar('summary', $summary);
jsVar('checkedSummary', str_replace('%storyCommon%', $lang->SRCommon, $lang->product->checkedSummary));

/* Table data and setting for finished story tab. */
jsVar('confirmUnlinkStory', $lang->release->confirmUnlinkStory);
jsVar('unlinkStoryUrl', helper::createLink('release', 'unlinkStory', "releaseID={$release->id}&story=%s"));
$storyTableData = initTableData($stories, $config->release->dtable->story->fieldList, $this->release);

$canBatchUnlinkStory = common::hasPriv('release', 'batchUnlinkStory');
$canBatchCloseStory  = common::hasPriv('story', 'batchClose');

$storyFootToolbar = array();
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'btnType' => 'primary', 'data-url' => inlink('batchUnlinkStory', "release={$release->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->story->batchClose,    'btnType' => 'primary', 'data-url' => createLink('story', 'batchClose', "productID={$release->product}"));

detailBody
(
    tabs
    (
        set::class('w-full'),

        /* Linked story table. */
        tabPane
        (
            set::key('finished-story'),
            set::title($lang->release->stories),
            set::active($type == 'story'),
            set::icon('icon-lightbulb text-green'),
            dtable
            (
                set::userMap($users),
                set::cols(array_values($config->release->dtable->story->fieldList)),
                set::data($storyTableData),
                set::checkable(true),
                set::sortLink(jsRaw('createSortLink')),
                set::footToolbar($storyFootToolbar),
                set::footPager(
                    usePager(null, 'storyPager'),
                    set::recPerPage($storyPager->recPerPage),
                    set::recTotal($storyPager->recTotal),
                    set::linkCreator(helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                ),
                set::checkInfo(jsRaw('function(checkedIDList){return window.setStoryStatistics(this, checkedIDList);}'))
            )
        ),

        /* Resolved bug table. */
        tabPane
        (
            set::key('resolved-bug'),
            set::title($lang->release->bugs),
            set::active($type == 'bug'),
            set::icon('icon-bug text-green'),
            dtable
            (
                set::userMap($users),
                set::cols(array_values($config->release->dtable->story->fieldList)),
                set::data($storyTableData),
                set::checkable(true),
                set::sortLink(jsRaw('createSortLink')),
                set::footToolbar($storyFootToolbar),
                set::footPager(
                    usePager(null, 'storyPager'),
                    set::recPerPage($storyPager->recPerPage),
                    set::recTotal($storyPager->recTotal),
                    set::linkCreator(helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                ),
                set::checkInfo(jsRaw('function(checkedIDList){return window.setStoryStatistics(this, checkedIDList);}'))
            )
        ),

        /* Left bug table. */
        tabPane
        (
            set::key('left-bug'),
            set::title($lang->release->generatedBugs),
            set::active($type == 'leftBug'),
            set::icon('icon-bug text-red'),
            dtable
            (
                set::userMap($users),
                set::cols(array_values($config->release->dtable->story->fieldList)),
                set::data($storyTableData),
                set::checkable(true),
                set::sortLink(jsRaw('createSortLink')),
                set::footToolbar($storyFootToolbar),
                set::footPager(
                    usePager(null, 'storyPager'),
                    set::recPerPage($storyPager->recPerPage),
                    set::recTotal($storyPager->recTotal),
                    set::linkCreator(helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                ),
                set::checkInfo(jsRaw('function(checkedIDList){return window.setStoryStatistics(this, checkedIDList);}'))
            )
        ),
    )
);

render();
