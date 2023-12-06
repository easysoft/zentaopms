<?php
declare(strict_types=1);
/**
 * The browse view file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     jenkins
 * @link        https://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('jenkins', 'create');
$createLink = $this->createLink('jenkins', 'create');
$createItem = array('text' => $lang->jenkins->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($jenkinsList, $config->jenkins->dtable->fieldList, $this->jenkins);

toolbar
(
    $canCreate ? item(set($createItem)) : null
);

jsVar('confirmDelete',    $lang->jenkins->confirmDelete);
jsVar('canBrowseProject', common::hasPriv('jenkins', 'browseProject'));

dtable
(
    set::cols(array_values($config->jenkins->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('jenkins', 'browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
