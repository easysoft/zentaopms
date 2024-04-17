<?php
declare(strict_types=1);
/**
 * The ticket view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

featureBar
(
    set::current($browseType),
    set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module('workTicket'), set::open($browseType == 'bysearch')))
);

$tickets = initTableData($tickets, $config->my->ticket->dtable->fieldList, $this->ticket);

dtable
(
    set::cols(array_values($config->my->ticket->dtable->fieldList)),
    set::data(array_values($tickets)),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);

render();
