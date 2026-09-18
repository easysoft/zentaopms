<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      zhouxin <zhouxin@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('storyType', $story->type);
jsVar('storyID', $story->id);
jsVar('undoneTasks', $undoneTasks);
jsVar('confirmCloseTips', sprintf($lang->story->undoneTasksTips, $undoneTasks));

$fields = useFields('story.close');

modalHeader();
formPanel
(
    set::submitBtnText($lang->story->closeAction),
    set::ajax(array('beforeSubmit' => jsRaw('checkSubmit'))),
    set::fields($fields)
);

hr();
history();
