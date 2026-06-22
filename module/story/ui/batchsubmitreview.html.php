<?php
declare(strict_types=1);
/**
 * The batch submit review view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     story
 * @link        https://www.zentao.net
 */

namespace zin;

/* Build warning messages. */
if(!empty($message))
{
    $jsMsg = json_encode($message);
    pageJS("zui.Modal.alert({$jsMsg});");
}

/* Build form field value for batch submit review. */
$data = array();
foreach($stories as $story)
{
    $reviewerList   = $this->story->getReviewerPairs($story->id, $story->version);
    $storyReviewers = array_keys($reviewerList);
    $rowForceReview = $this->story->checkForceReview($story->type);
    $needNotReview  = false;
    if(!$rowForceReview)
    {
        if($product)
        {
            $needNotReview = ($app->user->account == $product->PO or $config->{$story->type}->needReview == 0) and empty($storyReviewers);
        }
        else
        {
            $needNotReview = $config->{$story->type}->needReview == 0 and empty($storyReviewers);
        }
    }

    $row = new stdClass();
    $row->id            = $story->id;
    $row->title         = $story->title;
    $row->color         = $story->color;
    $row->reviewer      = $storyReviewers;
    $row->forceReview   = $rowForceReview ? 1 : 0;
    $row->needNotReview = $needNotReview ? 1 : 0;
    $data[] = $row;
}

$hasForceReview = !empty(array_filter($data, function($row) {return $row->forceReview;}));

formBatchPanel
(
    set::title($lang->story->batchSubmitReview),
    set::mode('edit'),
    set::data($data),
    set::submitBtnText($lang->story->submitReview),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('input[data-name="needNotReview"]', 'toggleBatchReviewer'),
    formBatchItem
    (
        set::name('id'),
        set::control('hidden'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('forceReview'),
        set::control('hidden'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('60px')
    ),
    formBatchItem
    (
        set::name('title'),
        set::label($lang->story->title),
        set::control('input'),
        set::disabled(true),
        set::width('240px')
    ),
    formBatchItem
    (
        set::name('reviewer'),
        set::label($lang->story->reviewers),
        set::control('picker'),
        set::required($hasForceReview),
        set::multiple(true),
        set::items($reviewers),
        set::width('300px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('needNotReview'),
        set::label(''),
        set::control('checkbox'),
        set::items(array(1 => $lang->story->needNotReview)),
        set::width('120px')
    )
);

render();
