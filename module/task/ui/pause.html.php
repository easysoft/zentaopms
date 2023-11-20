<?php
declare(strict_types=1);
/**
 * The pause view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu<liumengyi@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

/* ====== Define the page structure with zin widgets ====== */

modalHeader();
formPanel
(
    setID('taskPauseForm'),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('5')
        ),
        input(
            setClass('hidden'),
            set::name('status'),
            set::value('pause')
        )
    )
);
hr();
history();

render();
