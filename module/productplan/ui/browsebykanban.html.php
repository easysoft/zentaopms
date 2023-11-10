<?php
declare(strict_types=1);
/**
 * The browsebykanban view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;
$privs = array
(
    'canViewPlan' => common::hasPriv($app->rawModule, 'view'),
);

foreach($kanbanList as $current => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $group['draggable']  = true;
        $group['colWidth']   = 'auto';
        $group['getCol']     = jsRaw('window.getCol');
        $group['getItem']    = jsRaw('window.getItem');
        $group['canDrop']    = jsRaw('window.canDrop');
        $group['onDrop']     = jsRaw('window.onDrop');
        $kanbanList[$current]['items'][$index] = $group;
    }
}

$orderItems = array();
foreach($lang->productplan->orderList as $order => $label)
{
    $orderItems[] = array('text' => $label, 'active' => $orderBy == $order, 'url' => $this->createLink($app->rawModule, 'browse', "productID=$productID&branch=$branchID&browseType=$browseType&queryID=$queryID&orderBy=$order"));
}

jsVar('privs',           $privs);
jsVar('expired',         $lang->productplan->expired);
jsVar('confirmStart',    $lang->productplan->confirmStart);
jsVar('confirmFinish',   $lang->productplan->confirmFinish);
jsVar('confirmActivate', $lang->productplan->confirmActivate);
toolbar
(
    set::className('w-full justify-end'),
    dropdown
    (
        btn
        (
            setClass('ghost'),
            $lang->productplan->orderList[$orderBy]
        ),
        set::items($orderItems)
    ),
    div
    (
        btn(setClass($viewType == 'list'   ? 'text-primary' : 'text-darker'), set::icon('format-list-bulleted'), setData('type', 'list'), setClass('switchButton')),
        btn(setClass($viewType == 'kanban' ? 'text-primary' : 'text-darker'), set::icon('kanban'), setData('type', 'kanban'), setClass('switchButton')),
    ),
    common::hasPriv('productplan', 'create') ? item
    (
        set
        (
            array
            (
                'text'  => $lang->productplan->create,
                'url'   => $this->createLink($app->rawModule, 'create', "productID=$product->id&branch=$branch"),
                'icon'  => 'plus',
                'class' => 'btn primary'
            )
        )
    ) : null
);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 120px)')
);

render();
