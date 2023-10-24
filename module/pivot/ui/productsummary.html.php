<?php
declare(strict_types = 1);
/**
 * The product summary view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$generateData = function() use ($module, $method, $lang, $users, $products)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->error->noData);

    $canView = hasPriv('product', 'view');

    $cols = array(
        'name'           => array('title' => $lang->product->name,                  'width' => '200', 'link' => $canView ? createLink('product', 'view', "id={id}") : '', 'fixed' => 'left'),
        'PO'             => array('title' => $lang->pivot->PO,                      'width' => '80',  'userMap' => $users),
        'planTitle'      => array('title' => $lang->productplan->common,            'width' => '140'),
        'planBegin'      => array('title' => $lang->productplan->begin,             'width' => '70'),
        'planEnd'        => array('title' => $lang->productplan->end,               'width' => '70'),
        'storyDraft'     => array('title' => $lang->story->statusList['draft'],     'width' => '60', 'align' => 'center'),
        'storyReviewing' => array('title' => $lang->story->statusList['reviewing'], 'width' => '60', 'align' => 'center'),
        'storyActive'    => array('title' => $lang->story->statusList['active'],    'width' => '60', 'align' => 'center'),
        'storyChanging'  => array('title' => $lang->story->statusList['changing'],  'width' => '60', 'align' => 'center'),
        'storyClosed'    => array('title' => $lang->story->statusList['closed'],    'width' => '60', 'align' => 'center'),
        'storyTotal'     => array('title' => $lang->pivot->total,                   'width' => '60', 'align' => 'center')
    );

    foreach($products as $product)
    {
        $product->planTitle      = '';
        $product->planBegin      = '';
        $product->planEnd        = '';
        $product->storyDraft     = 0;
        $product->storyReviewing = 0;
        $product->storyActive    = 0;
        $product->storyChanging  = 0;
        $product->storyClosed    = 0;
        $product->storyTotal     = 0;

        if(!isset($product->plans)) continue;

        foreach($product->plans as $plan)
       {
            $product->planTitle      = $plan->title;
            $product->planBegin      = $plan->begin == '2030-01-01' ? $lang->productplan->future : $plan->begin;
            $product->planEnd        = $plan->end   == '2030-01-01' ? $lang->productplan->future : $plan->end;
            $product->storyDraft     = isset($plan->status['draft'])     ? $plan->status['draft']     : 0;
            $product->storyReviewing = isset($plan->status['reviewing']) ? $plan->status['reviewing'] : 0;
            $product->storyActive    = isset($plan->status['active'])    ? $plan->status['active']    : 0;
            $product->storyChanging  = isset($plan->status['changing'])  ? $plan->status['changing']  : 0;
            $product->storyClosed    = isset($plan->status['closed'])    ? $plan->status['closed']    : 0;
            $product->storyTotal     = $product->storyDraft + $product->storyReviewing + $product->storyActive + $product->storyChanging + $product->storyClosed;
        }
    }

    return div
    (
        setClass('w-full'),
        div
        (
            setID('conditions'),
            setClass('bg-white mb-4 p-2'),
            checkList
            (
                on::change('loadProductSummary'),
                set::inline(true),
                set::items(array(array('text' => $lang->pivot->closedProduct, 'value' => 'closedProduct'), array('text' => $lang->pivot->overduePlan, 'value' => 'overduePlan')))
            )
        ),
        dtable
        (
            set::cols($cols),
            set::data($products),
            set::fixedLeftWidth('0.25'),
            set::emptyTip($lang->error->noData)
        )
    );
};
