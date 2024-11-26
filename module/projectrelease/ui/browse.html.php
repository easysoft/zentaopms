<?php
declare(strict_types=1);
/**
 * The browse view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($type),
    set::linkParams("projectID={$projectID}&executionID={$executionID}&type={key}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")
);

$canManageSystem = hasPriv('system', 'browse') && common::canModify('project', $project);
toolbar
(
    !$project->hasProduct && $canManageSystem ? item(set
    (
        array
        (
            'class' => 'ghost',
            'text' => $lang->release->manageSystem,
            'url' => $this->createLink('system', 'browse', "productID=0&projectID={$projectID}"),
            'data-app' => 'project'
        )
    )) : null,

    common::canModify('project', $project) && hasPriv('projectrelease', 'create') ? item(set
    ([
        'text'  => $lang->release->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => $this->createLink('projectrelease', 'create', "projectID={$projectID}")
    ])) : ''

);

jsVar('markerTitle', $lang->release->marker);
jsVar('canViewProjectbuild', hasPriv('projectbuild', 'view'));

$cols = $this->loadModel('datatable')->getSetting('projectrelease');
if(!$showBranch) unset($cols['branch']);
if(isset($cols['branch']))  $cols['branch']['name'] = 'branchName';
if(isset($cols['product'])) $cols['product']['map'] = $products;
if(empty($project->hasProduct)) unset($cols['product']);
$cols['system']['map'] = $appList;

foreach($releases as $releaseID => $release)
{
    if(empty($release->releases)) continue;

    $release->isParent = true;
    foreach(explode(',', $release->releases) as $childID)
    {
        if(isset($childReleases[$childID]))
        {
            $child = clone $childReleases[$childID];
            $child->id     = "{$release->id}-{$child->id}";
            $child->parent = $release->id;
            $releases[$child->id] = $child;
        }
    }
}

$tableData = initTableData($releases, $cols);
dtable
(
    set::cols(array_values($cols)),
    set::data($tableData),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::sortLink(createLink('projectrelease', 'browse', "projectID={$project->id}&executionID={$executionID}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footer([jsRaw("function(){return {html: '{$pageSummary}'};}"), 'flex', 'pager']),
    set::footPager(usePager()),
    set::emptyTip($lang->release->noRelease),
    set::createTip($lang->release->create),
    set::createLink(hasPriv('projectrelease', 'create') ? createLink('projectrelease', 'create', "projectID={$projectID}") : '')
);

/* ====== Render page ====== */
render();
