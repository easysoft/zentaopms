<?php
declare(strict_types=1);
/**
 * The browse view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     sonarqube
 * @link        http://www.zentao.net
 */

namespace zin;

featureBar();

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('sonarqube', 'create');
$createLink = $this->createLink('sonarqube', 'create');
$createItem = array('text' => $lang->sonarqube->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($sonarqubeList, $config->sonarqube->dtable->fieldList, $this->sonarqube);

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

jsVar('confirmDelete',    $lang->sonarqube->confirmDelete);
jsVar('orderBy',          $orderBy);
jsVar('canBrowseProject', common::hasPriv('sonarqube', 'browseProject'));
jsVar('sortLink',         helper::createLink('sonarqube', 'browse', "orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

dtable
(
    set::cols(array_values($config->sonarqube->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(jsRaw('createSortLink')),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
);

render();
