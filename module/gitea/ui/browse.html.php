<?php
declare(strict_types=1);
/**
 * The browse view file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gitea
 * @link        https://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('gitea', 'create');
$createLink = $this->createLink('gitea', 'create');
$createItem = array('text' => $lang->gitea->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($giteaList, $config->gitea->dtable->fieldList, $this->gitea);

toolbar
(
    $canCreate ? item(set($createItem)) : null
);

jsVar('confirmDelete',    $lang->gitea->confirmDelete);
jsVar('canBrowseProject', common::hasPriv('gitea', 'browseProject'));

dtable
(
    set::cols(array_values($config->gitea->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('gitea', 'browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
