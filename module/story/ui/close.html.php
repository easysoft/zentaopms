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

modalHeader();
formPanel
(
    set::submitBtnText($lang->story->closeAction),
    on::change('#closedReasonBox', 'setStory'),
    formGroup
    (
        set::id('closedReasonBox'),
        set::name('closedReason'),
        set::label($lang->story->closedReason),
        set::width('1/2'),
        set::value(''),
        set::items($reasonList),
    ),
    formRow
    (
        set::hidden(true),
        set::id('duplicateStoryBox'),
        formGroup
        (
            set::name('duplicateStory'),
            set::required(true),
            set::label($lang->story->duplicateStory),
            set::width('1/2'),
            set::value(''),
            set::items($productStories),
        ),
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6),
    ),
);

history();

render();
