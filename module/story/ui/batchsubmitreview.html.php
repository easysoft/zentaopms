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
$message = '';
if(!empty($invalidTypes))
{
    $message .= sprintf($lang->story->batchSubmitReviewPrivTips, $invalidTypes);
}
if(!empty($invalidStoryIdList))
{
    $message .= sprintf($lang->story->batchSubmitReviewStatusTips, $invalidStoryIdList);
}

if(!empty($message))
{
    $jsMsg = json_encode($message);
    h::js("zui.Modal.alert({$jsMsg});");
}

$needNotReviewBox = span
(
    setClass('input-group-addon'),
    checkbox
    (
        setID('needNotReview'),
        set::name('needNotReview'),
        set::text($lang->story->needNotReview),
        set::value(1),
        set::checked($needReview)
    )
);

modalHeader(set::title($lang->story->batchSubmitReview));

formPanel
(
    set::submitBtnText($lang->story->submitReview),
    on::change('#needNotReview', 'toggleReviewer(e.target)'),
    formGroup
    (
        setID('reviewerBox'),
        set::label($lang->story->reviewers),
        set::width('full'),
        set::required(true),
        inputGroup
        (
            picker
            (
                setID('reviewer'),
                set::name('reviewer[]'),
                set::multiple(true),
                set::items($reviewers),
                set::disabled($needReview)
            ),
            $needNotReviewBox
        )
    )
);
render();