<?php
declare(strict_types=1);
/**
 * The browse view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('repoID', $repoID);

if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
}

/* zin: Define the set::module('pipeline') feature bar on main menu. */
featureBar
(
    set::current('pipeline'),
    set::link($this->createLink('{key}', 'browse', "repoID=$repoID")),
    div(searchToggle(set::module('pipeline'), set::open($type == 'bySearch')))
);

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('pipeline', 'create');
$createItem = array('text' => $lang->pipeline->create, 'url' => inLink('create', "spaceID={$spaceID}&repoID={$repoID}"), 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal');

$cols = $this->loadModel('datatable')->getSetting('pipeline');
$tableData = initTableData($pipelineList, $cols, $this->pipeline);

toolbar($canCreate ? item(set($createItem)) : null);

jsVar('confirmDelete', $lang->pipeline->confirmDelete);

dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($tableData),
    set::sortLink(createLink('pipeline', 'browse', "space={$spaceID}&repoID={$repoID}&type={$type}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
