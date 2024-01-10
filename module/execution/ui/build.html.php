<?php
declare(strict_types=1);
/**
 * The build view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('executionBuild') feature bar on main menu. */
featureBar
(
    set::current($type),
    set::linkParams("executionID={$execution->id}&type={key}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    set::module('execution'),
    set::method('build'),
    div
    (
        set::className('w-44 mr-4'),
        picker
        (
            set::name('product'),
            set::value($product),
            set::items($products),
            set::placeholder($lang->product->common),
            on::change('changeProduct')
        )
    ),
    li(searchToggle(set::module('executionBuild'), set::open($type == 'bysearch')))
);

/* zin: Define the toolbar on main menu. */
$canCreateBuild = hasPriv('build', 'create') && common::canModify('execution', $execution);
if($canCreateBuild) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->build->create, 'url' => $this->createLink('build', 'create', "executionID={$execution->id}"));
toolbar
(
    !empty($createItem) ? item(set($createItem)) : null,
);

jsVar('executionID', $execution->id);
jsVar('changeProductLink', helper::createLink('execution', 'build', "executionID={$execution->id}&type=product&param={productID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));
jsVar('scmPathTip', $lang->build->scmPath);
jsVar('filePathTip', $lang->build->filePath);
jsVar('confirmDelete', $lang->build->confirmDelete);

$fieldList = $config->build->dtable->fieldList;
if($execution->type == 'kanban') unset($fieldList['actions']['actionsMap']['createTest']['data-app']);

dtable
(
    set::userMap($users),
    set::cols($fieldList),
    set::data($builds),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('execution', 'build', "executionID={$execution->id}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::footPager(
        usePager
        (
            array('linkCreator' => helper::createLink('execution', 'build', "executionID={$execution->id}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={page}"), 'recTotal' => $pager->recTotal, 'recPerPage' => $pager->recPerPage)
        )
    ),
    set::emptyTip($lang->build->noBuild),
    set::createTip($lang->build->create),
    set::createLink($canCreateBuild ? createLink('build', 'create', "executionID={$execution->id}") : '')
);

/* ====== Render page ====== */
render();
