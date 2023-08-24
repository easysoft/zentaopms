<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wang Yidong <yidong@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

$productModel = $this->product;

$fnPrintSingleRoadmap = function($branchKey = 0) use ($roadmaps, $product, $productModel)
{
    $data = $productModel->buildRoadmapForUI($roadmaps, $branchKey);
    $hasRoadmaps = !empty($data) ;

    if(!$hasRoadmaps)
    {
        return div
        (
            setClass('table-empty-tip'),
            span(setClass('text-gray'), $lang->release->noRelease),
            common::canModify('product', $product) && common::hasPriv('release', 'create') ? btn(setClass('secondary'), set::url(createLink('release', 'create', "productID=$product->id&branch=$branchKey")), icon(setClass('pr-1'), 'plus'), $lang->release->create) : null,
        );
    }
    else
    {
        return roadMap(set::releases($data));
    }
};

$fnPrintBranchRoadmap = function() use ($branches, $roadmaps, $product, $productModel, $fnPrintSingleRoadmap)
{
    $tabPaneItems = array();
    foreach($branches as $branchKey => $branchName)
    {
        $tabPaneItems[] = tabPane
        (
            set::key("roadmap_{$branchKey}"),
            set::title($branchName),
            set::active($branchKey == 0),
            $fnPrintSingleRoadmap($branchKey),
        );
    }
    return tabs($tabPaneItems);
};

panel
(
    set::title($lang->product->iteration),
    set::titleClass("text-lg"),
    set::headingClass('justify-start'),
    to::heading
    (
        span(setClass('label rounded-full'), sprintf($lang->product->iterationInfo, $roadmaps['total'])),
    ),
    $product->type == 'normal' ? $fnPrintSingleRoadmap() : $fnPrintBranchRoadmap(),
);
