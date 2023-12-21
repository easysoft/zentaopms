<?php
declare(strict_types=1);
/**
 * The manage product view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('unmodifiableProducts', $unmodifiableProducts);
jsVar('unmodifiableBranches', $unmodifiableBranches);
jsVar('unmodifiableMainBranches', $unmodifiableMainBranches);
jsVar('allProducts', $allProducts);
jsVar('branchGroups', $branchGroups);
jsVar('BRANCH_MAIN', BRANCH_MAIN);
jsVar('unLinkProductTip', $lang->project->unLinkProductTip);

$noticeSwitch = ($project->stageBy == 'product' and count($linkedProducts) == 1 and empty($executions) and in_array($project->model, array('waterfall', 'waterfallplus')));
jsVar('linkedProducts', array_keys($linkedProducts));
jsVar('noticeSwitch', $noticeSwitch);
jsVar('noticeDivsion', $lang->project->noticeDivsion);
jsVar('stageBySwitchList', $lang->project->stageBySwitchList);

/* Link other product modal. */
if($config->systemMode == 'ALM')
{
    modal
    (
        setID('linkProduct'),
        set::modalProps(array('title' => $lang->project->manageOtherProducts)),
        form
        (
            setClass('text-center', 'py-4'),
            set::actions(array('submit')),
            formGroup
            (
                set::label($lang->project->selectProduct),
                set::required(true),
                picker
                (
                    set::name('otherProducts[]'),
                    set::multiple(true),
                    set::items($otherProducts)
                )
            )
        )

    );
}

$index      = 0;
$linkedList = array();
foreach($allProducts as $productID => $productName)
{
    if(empty($linkedBranches[$productID])) continue;

    $cannotUnlink = in_array($productID, $unmodifiableProducts) && $project->model == 'waterfall';

    foreach($linkedBranches[$productID] as $branchID)
    {
        $linkedList[] = btn
        (
            setClass('product-block modal-content center-row justify-start text-left'),
            checkbox
            (
                set::name("products[{$index}]"),
                set::text($productName),
                set::checked(true),
                set::disabled($cannotUnlink),
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
                set::required(true)
            ) : null,
            $cannotUnlink ? input
            (
                set::type('hidden'),
                set::name("products[{$index}]"),
                set::value($productID)
            ) : null,
            $cannotUnlink || isset($allBranches[$productID][$branchID]) ? input
            (
                set::type('hidden'),
                set::name("branch[{$index}]"),
                set::value($branchID)
            ) : null,
        );

        if(!isset($branchGroups[$productID]))
        {
            if($this->config->systemMode == 'ALM')
            {
                unset($currentProducts[$productID]);
            }
            else
            {
                unset($allProducts[$productID]);
            }
        }

        if(isset($branchGroups[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);

        if(isset($branchGroups[$productID]) and empty($branchGroups[$productID]))
        {
            if($this->config->systemMode == 'ALM')
            {
                unset($currentProducts[$productID]);
            }
            else
            {
                unset($allProducts[$productID]);
            }
        }
        $index ++;
    }
}

$unlinkList       = array();
$unlinkedProducts = $config->systemMode == 'ALM' ? $currentProducts : $allProducts;
foreach($unlinkedProducts as $productID => $productName)
{
    $unlinkList[] = btn
    (
        setClass('product-block modal-content center-row justify-start text-left'),
        checkbox
        (
            set::name("products[{$index}]"),
            set::text($productName),
            set::value($productID)
        ),
        isset($branchGroups[$productID]) ? picker
        (
            set::name("branch[{$index}]"),
            set::items($branchGroups[$productID]),
            set::required(true)
        ) : null
    );

    $index ++;
}

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
                setClass('flex flex-wrap'),
                $linkedList
            )
        ),

        $unlinkList ? h::hr() : null,

        $unlinkList ? section
        (
            set::title($lang->execution->unlinkedProducts),
            $config->systemMode == 'ALM' ? to::actions
            (
                btn
                (
                    setClass('ghost text-primary ml-2'),
                    set::url('#linkProduct'),
                    set('data-toggle', 'modal'),
                    set('data-size', 'sm'),
                    icon('link'),
                    p($lang->project->manageOtherProducts)
                )
            ) : null,
            div
            (
                setClass('flex flex-wrap'),
                $unlinkList
            )
        ) : null,

        h::hr()
    ),

    formHidden('post', 'post'),
    set::actions(array('submit'))
);

render();
