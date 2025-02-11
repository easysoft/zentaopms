<?php
declare(strict_types=1);
/**
 * The linktask file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));
jsVar('multipleAB', $lang->task->multipleAB);
jsVar('childrenAB', $lang->task->childrenAB);
jsVar('parentAB', $lang->task->parentAB);

detailHeader
(
    to::prefix(''),
    to::title
    (
        $lang->repo->linkTask
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->repo->linkTask, 'className' => 'batch-btn-repo ajax-btn', 'data-url' => helper::createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy=$orderBy"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'tasks'));

searchForm
(
    set::module('task'),
    set::simple(true),
    set::show(true)
);

div
(
    set('class', 'repo-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->repo->unlinkedTasks . "({$pager->recTotal})"
    )
);

$config->repo->taskDtable->fieldList['assignedTo']['currentUser']      = $app->user->account;
$config->repo->taskDtable->fieldList['status']['statusMap']['changed'] = $lang->task->storyChange;

$allTasks = initTableData($allTasks, $config->repo->taskDtable->fieldList);
dtable
(
    set::userMap($users),
    set::data($allTasks),
    set::cols($config->repo->taskDtable->fieldList),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::onRenderCell(jsRaw('window.renderTaskCell')),
    set::footPager(usePager())
);

render();
