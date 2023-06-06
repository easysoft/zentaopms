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

/* Table data and setting for finished story tab. */
$storyFootToolbar = array();
$storyTableData   = initTableData($stories, $config->release->dtable->story->fieldList, $this->release);

$canBatchUnlinkStory = common::hasPriv('release', 'batchUnlinkStory');
$canBatchCloseStory  = common::hasPriv('story', 'batchClose');
if($canBatchUnlinkStory) $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->release->batchUnlink, 'btnType' => 'primary', 'data-url' => inlink('batchUnlinkStory', "release={$release->id}"));
if($canBatchCloseStory)  $storyFootToolbar['items'][] = array('class' => 'btn primary size-sm batch-btn', 'text' => $lang->story->batchClose,    'btnType' => 'primary', 'data-url' => createLink('story', 'batchClose', "productID={$release->product}"));

detailBody
(
    tabs
    (
        set::class('w-full'),
        tabPane
        (
            set::key('finished-story'),
            set::title($lang->release->stories),
            set::active(true),
            dtable
            (
                set::userMap($users),
                set::cols(array_values($config->release->dtable->story->fieldList)),
                set::data($storyTableData),
                set::checkable(true),
                //set::sortLink(jsRaw('createSortLink')),
                set::footToolbar($storyFootToolbar),
                set::footPager(
                    usePager(null, 'storyPager'),
                    set::recPerPage($storyPager->recPerPage),
                    set::recTotal($storyPager->recTotal),
                    set::linkCreator(helper::createLink('release', 'view', "releaseID={$release->id}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$storyPager->recTotal}&recPerPage={recPerPage}&page={page}"))
                ),
                //set::checkInfo(jsRaw('function(checkedIDList){return window.setStatistics(this, checkedIDList);}'))
            )
        ),
    )
);

render();
