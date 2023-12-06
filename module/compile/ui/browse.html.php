<?php
declare(strict_types=1);
/**
 * The browse view file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     compile
 * @link        https://www.zentao.net
 */

namespace zin;

if($repoID)
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch('name');
    dropmenu(set::objectID($repoID), set::text($repoName), set::tab('repo'));
}

/* zin: Define the set::module('compile') feature bar on main menu. */
featureBar
(
    set::current('compile'),
    set::link($this->createLink('{key}', 'browse', "repoID=$repoID"))
);

/* zin: Define the toolbar on main menu. */
toolbar();

$tableData = initTableData($buildList, $config->compile->dtable->fieldList, $this->compile);

foreach($tableData as $row) if(!$row->testtask) unset($row->actions[1]);

dtable
(
    set::cols($config->compile->dtable->fieldList),
    set::data($tableData),
    set::sortLink(createLink('compile', 'browse', "repoID=$repoID&jobID={$jobID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
