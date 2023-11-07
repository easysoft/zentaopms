<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      zhouxin <zhouxin@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('lastReviewer', $reviewers);

$needNotReviewBox = null;
if(!$this->story->checkForceReview())
{
    $needNotReviewBox = span
    (
        set('class', 'input-group-addon'),
        checkbox
        (
            set::id('needNotReview'),
            set::name('needNotReview'),
            set::text($lang->story->needNotReview),
            set::value(1)
        ),
    );
}

modalHeader(set::title($lang->story->submitReview));
formPanel
(
    set::submitBtnText($lang->story->submitReview),
    on::change('#needNotReview', 'toggleReviewer(e.target)'),
    formGroup
    (
        setID('reviewerBox'),
        set::label($lang->story->reviewedBy),
        set::width('full'),
        set::required(true),
        inputGroup
        (
            picker
            (
                setID('reviewer'),
                set::name('reviewer[]'),
                set::value($story->reviewer),
                set::multiple(true),
                set::items($reviewers),
            ),
            $needNotReviewBox,
        )
    ),
);
hr();
history();

render();
