<?php
declare(strict_types=1);
/**
 * The browse view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */

namespace zin;

if($repoID)
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch('name');
    dropmenu(set::objectID($repoID), set::text($repoName), set::tab('repo'));
}

/* zin: Define the set::module('job') feature bar on main menu. */
featureBar
(
    set::current('job'),
    set::link($this->createLink('{key}', 'browse', "repoID=$repoID"))
);

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('job', 'create');
$createLink = $this->createLink('job', 'create');
$createItem = array('text' => $lang->job->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($jobList, $config->job->dtable->fieldList, $this->job);

toolbar
(
    $canCreate ? item(set($createItem)) : null
);

jsVar('confirmDelete',    $lang->job->confirmDelete);
jsVar('canBrowseProject', common::hasPriv('job', 'browseProject'));

dtable
(
    set::cols($config->job->dtable->fieldList),
    set::data($tableData),
    set::sortLink(createLink('job', 'browse', "repoID={$repoID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
