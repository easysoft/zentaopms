<?php
declare(strict_types=1);
/**
 * The ajax get reviewer view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$reviewItems        = array();
$specifiedReviewers = empty($flow) ? array() : $flow->definition->reviewFlow->approvals->specifiedReviewers;
$minReviewers       = empty($flow) ? 0 : $flow->definition->reviewFlow->approvals->minReviewers;
$reviewerCount      = count($reviewers);

jsVar('reviewResult', $reviewResult);
jsVar('minReviewers', $minReviewers);
jsVar('reviewerCount', $reviewerCount);
jsVar('mrLang', $lang->mr);

foreach($reviewers as $reviewer)
{
    $approvalClass = 'success';
    if($reviewer->decision == 'reject') $approvalClass = 'danger';
    if($reviewer->decision == 'pending') $approvalClass = 'secondary';
    $reviewItems[] = div
    (
        setClass('bg-gray-100 my-1 py-2 px-4'),
        div
        (
            setClass('flex items-center'),
            icon(setClass('text-lg'), in_array($reviewer->account, $specifiedReviewers) ? 'customer' : 'contacts'),
            span(setClass('ml-2 text-lg'), zget($users, $reviewer->account)),
            label(setClass($approvalClass . ' ml-4 size-sm'), $lang->mr->approvalStatusList[$reviewer->decision]),
            $reviewerCount > 1 ? div
            (
                setClass('flex flex-auto justify-end'),
                btn
                (
                    setClass('ghost size-sm ajax-submit'),
                    icon(setClass('text-primary'), 'trash'),
                    set::url('mr', 'ajaxDeleteReviewer', "mrID={$mrID}&reviewer={$reviewer->account}")
                )
            ) : ''
        ),
        div
        (
            setClass('mt-2 pl-6'),
            span("{$lang->mr->approvalResult}: ", empty($reviewer->opinion) ? $lang->noData : $reviewer->opinion)
        )
    );
}
tabs
(
    on::init()->call('loadApprovalsBlock'),
    set::headerBtn
    (
        array
        (
            'title' => $lang->mr->add,
            'icon'  => 'plus',
            'class' => 'ghost text-primary',
            'url'   => createLink('mr', 'ajaxAddReviewers', 'mrID=' . $mrID),
        )
    ),
    setClass('canvas rounded shadow py-2 px-4 mt-2'),
    tabPane
    (
        set::title($lang->mr->reviewer),
        tableData($reviewItems)
    )
);
