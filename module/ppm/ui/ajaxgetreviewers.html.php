<?php
declare(strict_types=1);
/**
 * The ajax get reviewer view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;

$reviewItems        = array();
$specifiedReviewers = empty($flow) ? array() : $flow->definition->reviewFlow->approvals->specifiedReviewers;
$minReviewers       = empty($flow) ? 0 : $flow->definition->reviewFlow->approvals->minReviewers;
$reviewerCount      = count($reviewers);

jsVar('reviewResult', $reviewResult);
jsVar('minReviewers', $minReviewers);
jsVar('reviewerCount', $reviewerCount);
jsVar('mrLang', $lang->ppm);

foreach($reviewers as $reviewer)
{
    $approvalClass = 'success';
    if($reviewer->decision == 'rejected') $approvalClass = 'danger';
    if($reviewer->decision == 'pending') $approvalClass = 'secondary';
    $reviewItems[] = div
    (
        setClass('bg-gray-100 my-1 py-2 px-4'),
        div
        (
            setClass('flex items-center'),
            icon(setClass('text-lg'), in_array($reviewer->account, $specifiedReviewers) ? 'customer' : 'contacts'),
            span(setClass('ml-2 text-lg'), zget($users, $reviewer->account)),
            label(setClass($approvalClass . ' ml-4 size-sm'), $lang->ppm->approvalStatusList[$reviewer->decision]),
            $reviewerCount > 1 && !in_array($reviewer->account, $specifiedReviewers) && $app->user->account == $ppm->createdBy ? div
            (
                setClass('flex flex-auto justify-end'),
                btn
                (
                    setClass('ghost size-sm ajax-submit'),
                    icon(setClass('text-primary'), 'trash'),
                    set::url('ppm', 'ajaxDeleteReviewer', "ppmID={$ppmID}&reviewer={$reviewer->account}&type={$type}")
                )
            ) : ''
        ),
        div
        (
            setClass('mt-2 pl-6 h-auto break-all'),
            set::title(empty($reviewer->opinion) ? $lang->noData : strip_tags($reviewer->opinion)),
            span("{$lang->ppm->approvalResult}: ", empty($reviewer->opinion) ? $lang->noData : html($reviewer->opinion))
        )
    );
}
tabs
(
    empty($type) || $type == 'basic' ? on::init()->do('loadCurrentPage("#mr-detail"); loadCurrentPage(".mr-toolbar"); loadCurrentPage("#mr-history");') : null,
    $app->user->account == $ppm->createdBy ? set::headerBtn
    (
        array
        (
            'title' => $lang->ppm->add,
            'icon'  => 'plus',
            'class' => 'ghost text-primary',
            'url'   => createLink('ppm', 'ajaxAddReviewers', 'ppmID=' . $ppmID . '&type=' . $type),
        )
    ) : null,
    setClass('canvas rounded shadow py-2 px-4 mt-2'),
    tabPane
    (
        set::title($lang->ppm->reviewer),
        tableData($reviewItems)
    )
);
