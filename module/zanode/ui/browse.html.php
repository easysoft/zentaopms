<?php
declare(strict_types=1);
/**
 * The browse view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

$createItem = array('text' => $lang->zanode->create, 'url' => createLink('zanode', 'create'), 'icon' => 'plus', 'class' => 'btn primary');

foreach($nodeList as $node)
{
    $node->memory   .= $lang->zahost->unitList['GB'];
    $node->diskSize .= $lang->zahost->unitList['GB'];
}

$nodeList = initTableData($nodeList, $config->zanode->dtable->fieldList, $this->zanode);

\zin\featureBar
(
    li(searchToggle(set::open($browseType == 'bySearch'))),
    a
    (
        setClass('btn btn-link'),
        icon('help'),
        $lang->help,
        set('data-app', 'qa'),
        set('target', '_blank'),
        set::href('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao')
    )
);

toolBar
(
    hasPriv('zanode', 'create') ? item(set($createItem)) : null,
);

dtable
(
    set::cols($config->zanode->dtable->fieldList),
    set::data($nodeList),
    set::onRenderCell(jsRaw('window.renderList')),
    set::afterRender(jsRaw('window.afterRender')),
    set::sortLink(createLink('zanode', 'browse', "browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager()),
    set::orderBy(str_replace('t1.', '', $orderBy))
);

render();

