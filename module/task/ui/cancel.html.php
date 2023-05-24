<?php
declare(strict_types=1);
/**
 * The cancel view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        http://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title(''),
    entityLabel
    (
        set::entityID($task->id),
        set::level(1),
        set::text($task->name),
    ),
    formGroup
    (
        set::name("comment"),
        set::label($lang->story->comment),
        set::control("editor")
    )
);

render();
