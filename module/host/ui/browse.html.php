<?php
declare(strict_types=1);
/**
 * The browse view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('hostLang', $lang->host);

$config->host->featureBar['all']['active'] = true;
$config->host->featureBar['all']['badge']  = $pager->recTotal != '' ? array('text' => $pager->recTotal, 'class' => 'size-sm rounded-full white') : null;
featureBar
(
    set::items($config->host->featureBar),
    li(searchToggle())
);

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('host', 'create');
$createLink = $this->createLink('host', 'create');
$createItem = array('text' => $lang->host->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$config->host->dtable->fieldList['group']['map']      = $optionMenu;
$config->host->dtable->fieldList['admin']['map']      = $accounts;
$config->host->dtable->fieldList['serverRoom']['map'] = $rooms;
$tableData = initTableData($hostList, $config->host->dtable->fieldList, $this->host);
foreach($tableData as $row)
{
    if(!$row->admin) $row->admin = '';
}

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

/* zin: Define the sidebar in main content. */
sidebar
(
    moduleMenu(set(array(
        'modules'     => $moduleTree,
        'activeKey'   => $param,
        'allText'     => $lang->host->group,
        'settingText' => $lang->host->groupMaintenance,
        'showDisplay' => false,
        'settingLink' => $this->createLink('tree', 'browse', "productID=0&view=host"),
        'closeLink'   => $this->createLink('host', 'browse')
    )))
);

dtable
(
    set::userMap($accounts),
    set::cols(array_values($config->host->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('host', 'browse', "browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
