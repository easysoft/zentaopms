<?php
declare(strict_types=1);
/**
 * The linkbug file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('mr', 'linkBug', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

detailHeader
(
    to::prefix(''),
    to::title
    (
        $lang->productplan->linkBug,
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->productplan->linkBug, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('mr', 'linkBug', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy=$orderBy"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'bugs'));

div(setID('searchFormPanel'), set('data-module', 'bug'), searchToggle(set::open(true), set::module('bug')));

div
(
    set('class', 'mr-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->productplan->unlinkedBugs . "({$pager->recTotal})"
    )
);
$allBugs = initTableData($allBugs, $config->repo->bugDtable->fieldList);
$data = array_values($allBugs);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($config->repo->bugDtable->fieldList),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager()),
);

render();
