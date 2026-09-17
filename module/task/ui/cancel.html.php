<?php
declare(strict_types=1);
/**
 * The cancel view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$fields = useFields('task.cancel');
modalHeader();
formPanel
(
    set::fields($fields)
);
hr();
history();