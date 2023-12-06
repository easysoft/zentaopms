<?php
declare(strict_types=1);
/**
 * The browse view file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gogs
 * @link        https://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('gogs', 'create');
$createLink = $this->createLink('gogs', 'create');
$createItem = array('text' => $lang->gogs->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($gogsList, $config->gogs->dtable->fieldList, $this->gogs);

toolbar
(
    $canCreate ? item(set($createItem)) : null
);

jsVar('confirmDelete',    $lang->gogs->confirmDelete);
jsVar('canBrowseProject', common::hasPriv('gogs', 'browseProject'));

dtable
(
    set::cols(array_values($config->gogs->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('gogs', 'browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
