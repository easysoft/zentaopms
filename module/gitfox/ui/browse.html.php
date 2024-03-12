<?php
declare(strict_types=1);
/**
 * The browse view file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li<zhaoke@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('gitfox', 'create');
$createLink = $this->createLink('gitfox', 'create');
$createItem = array('text' => $lang->gitfox->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($gitfoxList, $config->gitfox->dtable->fieldList, $this->gitfox);

toolbar
(
    $canCreate ? item(set($createItem)) : null
);

jsVar('confirmDelete',    $lang->gitfox->confirmDelete);
jsVar('canBrowseProject', common::hasPriv('gitfox', 'browseProject'));

dtable
(
    set::cols(array_values($config->gitfox->dtable->fieldList)),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('gitfox', 'browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
