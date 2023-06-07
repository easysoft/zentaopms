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

formPanel
(
    set::title($lang->story->submitReview),
    set::actions(array('submit')),
    set::headingClass('status-heading'),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::entityID($story->id),
            set::text($story->title),
            set::level(1),
            set::reverse(true),
        )
    ),
    formGroup
    (
        set::label($lang->story->reviewedBy),
        set::width('full'),
        set::strong(false),
        inputGroup
        (
            select
            (
                set::name('reviewer'),
                set::value($story->reviewer),
                set::items($reviewers),
            ),
            $needNotReviewBox,
        )
    ),
);

h::hr(set::class('mt-6'));
history();

render('modalDialog');
