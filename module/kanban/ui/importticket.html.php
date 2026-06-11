<?php
/**
 * The importticket view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@zentao.net>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('kanbanID', $kanbanID);
jsVar('regionID', $regionID);
jsVar('groupID',  $groupID);
jsVar('columnID', $columnID);
jsVar('methodName', $this->app->rawMethod);

$app->loadLang('ticket');

$ticketCols = array();
$ticketCols['id']         = array('name' => 'id', 'title' => $lang->idAB, 'type' => 'checkID', 'sortType' => false);
$ticketCols['title']      = array('name' => 'title', 'title' => zget($lang->ticket, 'title'), 'type' => 'title', 'flex' => 1, 'sortType' => false);
$ticketCols['product']    = array('name' => 'product', 'title' => zget($lang->ticket, 'product'), 'type' => 'category', 'sortType' => false, 'map' => $products);
$ticketCols['pri']        = array('name' => 'pri', 'title' => zget($lang->ticket, 'priAB'), 'type' => 'pri', 'sortType' => false, 'priList' => zget($lang->ticket, 'priList', array()));
$ticketCols['status']     = array('name' => 'status', 'title' => zget($lang->ticket, 'status'), 'type' => 'status', 'sortType' => false, 'statusMap' => zget($lang->ticket, 'statusList', array()));
$ticketCols['type']       = array('name' => 'type', 'title' => zget($lang->ticket, 'type'), 'type' => 'category', 'sortType' => false, 'map' => zget($lang->ticket, 'typeList', array()));
$ticketCols['openedDate'] = array('name' => 'openedDate', 'title' => zget($lang->ticket, 'createdDate'), 'type' => 'date', 'sortType' => false);
$ticketCols['assignedTo'] = array('name' => 'assignedTo', 'title' => zget($lang->ticket, 'assignedTo'), 'type' => 'user', 'sortType' => false);

if(common::hasPriv('ticket', 'view'))
{
    $ticketCols['title']['link'] = array('module' => 'ticket', 'method' => 'view', 'params' => 'ticketID={id}');
}

featureBar
(
    inputGroup
    (
        span(set::className('input-group-addon'), $lang->kanban->selectedProduct),
        picker(set::name('product'), set::items($products), set::value($selectedProductID), set::style(array('width' => '200px')), set('data-on', 'change'), set('data-call', 'changeProduct'), set::required(true)),
        span(set::className('input-group-addon'), $lang->kanban->selectedLane),
        picker(set::name('lane'), set::items($lanePairs), set::value(key($lanePairs)), set::style(array('width' => '200px')), set::required(true))
    )
);

formBase
(
    set::id('linkForm'),
    set::actions(''),
    set::className('mt-2'),
    dtable
    (
        set::id('linkTicket'),
        set::fixedLeftWidth('0.33'),
        set::checkable(true),
        set::userMap($users),
        set::cols(array_values($ticketCols)),
        set::data(array_values($tickets2Imported)),
        set::footToolbar(array('items' => array(array('text' => $lang->kanban->importAB, 'btnType' => 'primary', 'className' => 'size-sm batch-btn', 'data-url' => inlink('importTicket', "kanbanID=$kanbanID&regionID=$regionID&groupID=$groupID&columnID=$columnID"))))),
        set::footPager(usePager())
    )
);