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

$needNotReviewBox = '';
if(!$this->story->checkForceReview())
{
    $needNotReviewBox = span
    (
        set('class', 'input-group-addon'),
        checkbox
        (
            set::name('needNotReview'),
            set::text($lang->story->needNotReview),
        ),
    );
}

modalHeader(set::title($lang->story->submitReview));
formPanel
(
    set::submitBtnText($lang->story->submitReview),
    formGroup
    (
        set::label($lang->story->reviewedBy),
        set::width('full'),
        set::strong(false),
        set::required(true),
        inputGroup
        (
            select
            (
                set::name('reviewer[]'),
                set::value($story->reviewer),
                set::multiple(true),
                set::items($reviewers),
            ),
            $needNotReviewBox,
        )
    ),
);

history();

render();
