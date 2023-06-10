<?php
declare(strict_types=1);
/**
 * The browse view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($browseType),
    set::linkParams("product=$productID&branch=$branch&browseType={key}"),
    li(searchToggle())
);

$config->productplan->dtable->fieldList['branch']['map'] = $branches;

$cols = array_values($config->productplan->dtable->fieldList);
$data = array_values($plans);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'btnType' => 'secondary', 'data-url' => createLink('productplan', 'batchEdit', "productID={$product->id}&branch=$branch")),
    array('caret' => 'up', 'text' => $lang->productplan->status, 'btnType' => 'secondary', 'url' => '#navAssignedTo','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
));

dtable
(
    set::userMap($users),
    set::customCols(true),
    set::cols($cols),
    set::data($data),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(helper::createLink('productplan', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}"))
    ),
);

render();
