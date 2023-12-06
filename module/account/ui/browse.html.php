<?php
declare(strict_types=1);
/**
 * The browse view file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     account
 * @link        https://www.zentao.net
 */

namespace zin;

$queryMenuLink = createLink('account', 'browse', "browseType=bySearch&param={queryID}");
featureBar
(
    set::queryMenuLinkCallback(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink)),
    li(searchToggle())
);

/* zin: Define the toolbar on main menu. */
$canCreate  = hasPriv('account', 'create');
$createLink = $this->createLink('account', 'create');
$createItem = array('text' => $lang->account->create, 'url' => $createLink, 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal', 'data-size' => 'sm');

$tableData = initTableData($accountList, $config->account->dtable->fieldList, $this->account);

toolbar
(
    $canCreate ? item(set($createItem)) : null,
);

dtable
(
    set::userMap($users),
    set::cols(array_values($config->account->dtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('account', 'browse', "browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();
