<?php
declare(strict_types=1);
/**
 * The browse view file of serverroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     serverroom
 * @link        https://www.zentao.net
 */

namespace zin;

$queryMenuLink = createLink('serverroom', 'browse', "browseType=bySearch&param={queryID}");
featureBar
(
    set::queryMenuLinkCallback(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink)),
    li(searchToggle())
);

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('serverroom', 'create');
$createLink = $this->createLink('serverroom', 'create');
$createItem = array('text' => $lang->serverroom->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus');

$tableData = initTableData($serverRoomList, $config->serverroom->dtable->fieldList, $this->serverroom);

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

dtable
(
    set::userMap($users),
    set::cols(array_values($config->serverroom->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('serverroom', 'browse', "browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();
