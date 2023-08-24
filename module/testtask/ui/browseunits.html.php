<?php
declare(strict_types=1);
/**
 * The browse units view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

include '../../testcase/ui/header.html.php';

$cols  = $this->config->testtask->browseUnits->dtable->fieldList;
$tasks = initTableData($tasks, $cols, $this->testtask);

$summary = sprintf($lang->testtask->unitSummary, $pager->recTotal);

dtable
(
    set::cols($cols),
    set::data(array_values($tasks)),
    set::emptyTip($lang->testtask->emptyUnitTip),
    set::userMap($users),
    set::footer(array(array('html' => $summary), 'flex', 'pager')),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('testtask', 'browseunits', "productID={$productID}&browseType={$browseType}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
