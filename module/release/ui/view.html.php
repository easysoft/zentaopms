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
jsVar('sortLink', helper::createLink('release', 'view', "releaseID={$release->id}&type={type}&link={$link}&param={$param}&orderBy={orderBy}"));

/* Table data and setting for finished stories tab. */
jsVar('summary', $summary);
jsVar('storyCases', $storyCases);
jsVar('confirmunlinkstory', $lang->release->confirmUnlinkStory);
jsVar('checkedSummary', str_replace('%storyCommon%', $lang->SRCommon, $lang->product->checkedSummary));
jsVar('unlinkstoryurl', helper::createLink('release', 'unlinkStory', "releaseID={$release->id}&storyID=%s"));
$storyTableData = initTableData($stories, $config->release->dtable->story->fieldList, $this->release);

$canBatchUnlinkStory = common::hasPriv('release', 'batchUnlinkStory');
$canBatchCloseStory  = common::hasPriv('story', 'batchClose');

$storyFootToolbar = array();
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'btnType' => 'primary', 'data-type' => 'story', 'data-url' => inlink('batchUnlinkStory', "release={$release->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->story->batchClose,    'btnType' => 'primary', 'data-type' => 'story', 'data-url' => createLink('story', 'batchClose', "productID={$release->product}"));

/* Table data and setting for resolved bugs tab. */
jsVar('confirmunlinkbug', $lang->release->confirmUnlinkBug);
jsVar('unlinkbugurl', helper::createLink('release', 'unlinkBug', "releaseID={$release->id}&bugID=%s"));

$config->release->dtable->bug->fieldList['resolvedBuild']['map'] = $builds;
$bugTableData = initTableData($bugs, $config->release->dtable->bug->fieldList, $this->release);

$canBatchUnlinkBug = common::hasPriv('release', 'batchUnlinkBug');
$canBatchCloseBug  = common::hasPriv('bug', 'batchClose');

$bugFootToolbar = array();
if($canBatchUnlinkBug) $bugFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => inlink('batchUnlinkBug', "release={$release->id}"));
if($canBatchCloseBug)  $bugFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->bug->batchClose,      'btnType' => 'primary', 'data-type' => 'bug', 'data-url' => createLink('bug', 'batchClose', "productID={$release->product}"));

detailBody
(
    tabs
    (
        set::class('w-full'),

        /* Linked story table. */
        tabPane
        (
            set::key('finishedStory'),
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
            set::key('resolvedBug'),
            set::title($lang->release->bugs),
            set::active($type == 'bug'),
            set::icon('icon-bug text-green'),
            dtable
            (
                set::userMap($users),
                set::cols(array_values($config->release->dtable->bug->fieldList)),
                set::data($bugTableData),
                set::checkable(true),
                set::sortLink(jsRaw('createSortLink')),
                set::footToolbar($bugFootToolbar),
                set::footPager(
                    usePager(null, 'bugPager'),
                    set::recPerPage($bugPager->recPerPage),
                    set::recTotal($bugPager->recTotal),
                    set::linkCreator(helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$bugPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                ),
            )
        ),

        /* Left bug table. */
        tabPane
        (
            set::key('leftBug'),
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
