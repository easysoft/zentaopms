<?php
declare(strict_types=1);
/**
 * The batchChangeStatus view file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items['planIdList']   = array('name' => 'planIdList',   'label' => $lang->idAB,                      'control' => 'hidden', 'hidden' => true);
$items['id']           = array('name' => 'id',           'label' => $lang->idAB,                      'control' => 'index',  'width' => '60px');
$items['title']        = array('name' => 'title',        'label' => $lang->productplan->title,        'control' => 'text',   'width' => '300px', 'disabled' => true);
$items['status']       = array('name' => 'status',       'label' => $lang->productplan->status,       'control' => 'text',   'width' => '80px',  'disabled' => true);
$items['closedReason'] = array('name' => 'closedReason', 'label' => $lang->productplan->closedReason, 'control' => 'picker', 'width' => '200px', 'required' => true, 'items' => $lang->productplan->closedReasonList);
$items['comment']      = array('name' => 'comment',      'label' => $lang->comment,                   'control' => 'text',   'filter' => 'trim');

foreach($plans as $plan)
{
    $plan->planIdList = $plan->id;
    $plan->status     = zget($lang->productplan->statusList, $plan->status);
}

$closedReasonItems = array();
foreach($lang->productplan->closedReasonList as $reasonKey => $reasonName) $closedReasonItems[] = array('text' => $reasonName, 'value' => $reasonKey);
jsVar('closedReasonItems', $closedReasonItems);

formBatchPanel
(
    set::title($lang->productplan->batchClose),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($plans)),
);

render();
