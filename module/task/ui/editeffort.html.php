<?php
declare(strict_types=1);
/**
 * The edit effort view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$fields = useFields('task.editEffort');

jsVar('isReadonly', !$effort->isLast || (!empty($task->team) && $effort->left == 0));
jsVar('finishTaskTip', $lang->task->confirmRecord);

modalHeader(set::title($lang->task->editEffort));
formPanel
(
    setID('editEffortForm'),
    set::shadow(!isAjaxRequest('modal')),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::fields($fields)
);
render();
