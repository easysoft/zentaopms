<?php
declare(strict_types=1);
/**
 * The build view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$changeProductBox = array();
if($project->hasProduct)
{
    $changeProductBox[] = div(
        setClass('select-product-box mx-2'),
        picker
        (
            set::name('product'),
            set::value($productID),
            set::items($products),
            on::change('changeProduct')
        )
    );
}
/* zin: Define the set::module('projectBuild') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("projectID={$project->id}&type={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    set::module('project'),
    set::method('build'),
    $changeProductBox,
    li(searchToggle(set::module('projectBuild'), set::open($type == 'bysearch')))
);

/* zin: Define the toolbar on main menu. */
$canCreateBuild = hasPriv('projectbuild', 'create') && common::canModify('project', $project);

if($canCreateBuild) toolbar(item(set(array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->build->create, 'url' => createLink('projectbuild', 'create', "projectID={$project->id}")))));

jsVar('projectID', $project->id);
jsVar('changeProductLink', createLink($app->rawModule, $app->rawMethod, "projectID={$project->id}&type=product&param={productID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));
jsVar('scmPathTip', $lang->build->scmPath);
jsVar('filePathTip', $lang->build->filePath);
jsVar('integratedTip', $lang->build->integrated);
jsVar('deletedTip', $lang->build->deleted);
dtable
(
    set::userMap($users),
    set::cols($config->build->dtable->fieldList),
    set::data($builds),
    set::plugins(array('cellspan')),
    set::orderBy($orderBy),
    set::sortLink(createLink($app->rawModule, $app->rawMethod, "projectID={$project->id}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::footPager(usePager(array('linkCreator' => createLink($app->rawModule, $app->rawMethod, "projectID={$project->id}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}"), 'recTotal' => $pager->recTotal, 'recPerPage' => $pager->recPerPage))),
    set::emptyTip($lang->build->noBuild),
    set::createTip($lang->build->create),
    set::createLink($canCreateBuild ? createLink('projectbuild', 'create', "projectID={$project->id}") : '')
);

/* ====== Render page ====== */
render();
