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

jsVar('storyType', $story->type);

modalHeader();
formPanel
(
    set::submitBtnText($lang->story->closeAction),
    formGroup
    (
        setID('closedReason'),
        set::name('closedReason'),
        set::label($lang->story->closedReason),
        set::width('1/3'),
        set::value(''),
        set::items($reasonList),
        on::change('#closedReason', 'setStory')
    ),
    formGroup
    (
        set::hidden(true),
        setID('duplicateStoryBox'),
	    set::required(true),
	    set::label($lang->story->duplicateStory),
	    set::width('1/3'),
	    set::value(''),
	    picker
	    (
	        set::name('duplicateStory'),
	        set::items($productStories)
	    )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('comment')
    )
);
hr();
history();

render();
