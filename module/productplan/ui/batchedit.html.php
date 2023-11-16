<?php
declare(strict_types=1);
/**
 * The batchedit view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array_merge(array('idIndex' => array('name' => 'idIndex', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px')), $config->productplan->form->batchEdit);
$items['future'] = array('name' => 'future', 'label' => $lang->productplan->future, 'control' => 'checkList', 'width' => '80px', 'items' => array(1 => $lang->productplan->future));

$items['branch']['multiple'] = true;
$items['branch']['items']    = $branchTagOption;
if($product->type == 'normal') unset($items['branch']);

$parentBranches = array();
foreach($plans as $plan)
{
    if($plan->begin == $config->productplan->future) $plan->begin = '';
    if($plan->end == $config->productplan->future)   $plan->end   = '';
    if($plan->parent > 0 and isset($parentList[$plan->parent]))
    {
        $parentPlan = $parentList[$plan->parent];
        foreach($branchTagOption as $branchID => $branchName)
        {
            if(str_contains(",{$parentPlan->branch},", ",$branchID,")) $parentBranches[$plan->parent][] = array('text' => $branchName, 'value' => $branchID);
        }
    }
}

$branchPickerItems = array();
foreach($branchTagOption as $branchID => $branchName) $branchPickerItems[] = array('text' => $branchName, 'value' => $branchID);

$statusPickerItems = array();
$noWaitPickerItems = array();
foreach($lang->productplan->statusList as $statusKey => $statusName)
{
    $statusPickerItems[] = array('text' => $statusName, 'value' => $statusKey);
    if($statusKey != 'wait') $noWaitPickerItems[] = array('text' => $statusName, 'value' => $statusKey);
}

jsVar('oldBranch', $oldBranch);
jsVar('futureConfig', $config->productplan->future);
jsVar('branchPickerItems', $branchPickerItems);
jsVar('parentBranches', $parentBranches);
jsVar('statusPickerItems', $statusPickerItems);
jsVar('noWaitPickerItems', $noWaitPickerItems);

formBatchPanel
(
    setID('bachEditForm'),
    set::title($lang->product->batchEdit),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($plans)),
    set::onRenderRow(jsRaw('renderRowData'))
);

render();
