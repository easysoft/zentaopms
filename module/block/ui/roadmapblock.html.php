<?php
declare(strict_types=1);
/**
 * The roadmap view file of block module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */
namespace zin;

$productModel = $this->loadModel('product');

$fnPrintSingleRoadmap = function($branchKey = 0) use ($roadmaps, $product, $productModel)
{
    global $lang;

    $data        = $productModel->buildRoadmapForUI($roadmaps, $branchKey);
    $hasRoadmaps = !empty($data) ;
    if(!$hasRoadmaps)
    {
        return div
        (
            setClass('empty-tip text-center'),
            span(setClass('text-gray'), $lang->release->noRelease),
            common::canModify('product', $product) && common::hasPriv('release', 'create') ? btn
            (
                setClass('ml-2'),
                setStyle('background', 'rgb(var(--color-primary-50-rgb))'),
                setStyle('box-shadow', 'none'),
                set::url(createLink('release', 'create', "productID=$product->id&branch=$branchKey")),
                span
                (
                    icon('plus'),
                    setClass('text-primary'),
                    $lang->release->create
                )
            ) : null
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
            $fnPrintSingleRoadmap($branchKey)
        );
    }
    return tabs($tabPaneItems);
};

panel
(
    set('headingClass', 'border-b'),
    set::title($block->title),
    $product->type == 'normal' ? $fnPrintSingleRoadmap() : $fnPrintBranchRoadmap()
);

render();
