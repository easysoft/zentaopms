<?php
declare(strict_types=1);
/**
 * The browse view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('+type', strtolower($type));
jsVar('projectID', $projectID);
jsVar('productID', $productID);

/* zin: Define the set::module('design') feature bar on main menu. */
featureBar
(
    li(searchToggle(set::module('design')))
);

/* zin: Define the toolbar on main menu. */
$canCreate       = hasPriv('design', 'create');
$canBatchCreate  = hasPriv('design', 'batchCreate');
$createItem      = array('text' => $lang->design->create,      'url' => helper::createLink('design', 'create', "projectID={$projectID}&productID={$productID}&type={$type}"));
$batchCreateItem = array('text' => $lang->design->batchCreate, 'url' => helper::createLink('design', 'batchCreate', "projectID={$projectID}&productID={$productID}&type={$type}"));
toolbar
(
    $canCreate && $canBatchCreate ? btnGroup
    (
        btn(setClass('btn primary'), set::icon('plus'), set::url($createItem['url']), $lang->design->create),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array_filter(array($createItem, $batchCreateItem))),
            set::placement('bottom-end')
        )
    ) : null,
    $canCreate && !$canBatchCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null,
    $canBatchCreate && !$canCreate ? item(set($batchCreateItem  + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

jsVar('confirmDelete', $lang->design->confirmDelete);

$tableData = initTableData($designs, $config->design->dtable->fieldList, $this->design);
dtable
(
    set::userMap($users),
    set::cols($config->design->dtable->fieldList),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('design', 'browse', "projectID={$projectID}&productID={$productID}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(
        usePager()
    )
);

/* ====== Render page ====== */
render();
