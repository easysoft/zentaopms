<?php
declare(strict_types=1);
/**
 * The manage product view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('unmodifiableProducts',$unmodifiableProducts);
jsVar('unmodifiableBranches', $unmodifiableBranches);
jsVar('linkedStoryIDList', $linkedStoryIDList);
jsVar('allProducts', $allProducts);
jsVar('branchGroups', $branchGroups);
jsVar('unLinkProductTip', $lang->project->unLinkProductTip);

$index      = 0;
$linkedList = array();
foreach($allProducts as $productID => $productName)
{
    if(empty($linkedProducts[$productID])) continue;

    foreach($linkedBranches[$productID] as $branchID)
    {
        $linkedList[] = btn
        (
            setClass('product-block modal-content center-row justify-start items-center text-left'),
            checkbox
            (
                set::name("products[{$index}]"),
                set::text($productName),
                set::checked(true),
                set::value($productID),
                on::click('checkUnlink'),
                setClass('linked-product')
            ),
            isset($allBranches[$productID][$branchID]) ? picker
            (
                set::name("branch[{$index}]"),
                set::value($branchID),
                set::items($allBranches[$productID]),
                set::disabled(true),
                set::required(true),
            ) : formHidden("branch[{$index}]", $branchID),
        );

        if(!isset($branchGroups[$productID])) unset($allProducts[$productID]);
        if(isset($branchGroups[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);
        if(isset($branchGroups[$productID]) and empty($branchGroups[$productID])) unset($allProducts[$productID]);

        $index ++;
    }
}

$unlinkList = array();
if($execution->grade == 1 || $execution->grade == 2)
{
    foreach($allProducts as $productID => $productName)
    {
        if($execution->grade == 2 && (!isset($linkedProducts[$productID]) || $linkedProducts[$productID]->type == 'normal')) continue;

        $unlinkList[] = btn
            (
                setClass('product-block modal-content center-row justify-start items-center text-left'),
                checkbox
                (
                    set::name("products[{$index}]"),
                    set::text($productName),
                    set::value($productID),
                ),
                isset($branchGroups[$productID]) ? picker
                (
                    set::name("branch[{$index}]"),
                    set::value(key($branchGroups[$productID])),
                    set::items($branchGroups[$productID]),
                    set::required(true),
                ) : formHidden("branch[{$index}]", 0),
            );

        $index ++;
    }
}

$canModify = common::canModify('execution', $execution);
form
(
    setID('manageProducts'),
    setClass('canvas pb-6'),
    sectionList
    (
        section
        (
            set::title($lang->execution->linkedProducts),
            div
            (
                setClass('flex items-center flex-wrap'),
                $linkedList
            )
        ),

        $unlinkList ? h::hr() : null,

        $unlinkList ? section
        (
            set::title($lang->execution->unlinkedProducts),
            div
            (
                setClass('flex items-center flex-wrap'),
                $unlinkList
            )
        ) : null,

        $canModify ? h::hr() : null,
    ),
    $canModify ? set::actions(array('submit')) : null,
);

render();
