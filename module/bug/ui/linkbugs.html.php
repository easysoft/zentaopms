<?php
declare(strict_types=1);
/**
 * The linkbugs view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($bugs2Link as $bug)
{
    $bug->productName = zget($products, $bug->product);
    $bug->openedBy    = zget($users, $bug->openedBy);
    $bug->assignedTo  = zget($users, $bug->assignedTo);
}

$cols = array_values($config->bug->linkBugs->dtable->fieldList);
$data = array_values($bugs2Link);

div
(
    set::id('searchFormPanel'),
);

form
(
    $bugs2Link ? dtable
    (
        set::cols($cols),
        set::data($data),
        set::footPager(usePager()),
        set::footer(jsRaw('window.footerGenerator'))
    ) : null,
    set::checkable(true),
    set::actions(),
);

h::js('window.toggleSearchForm()');

render();
