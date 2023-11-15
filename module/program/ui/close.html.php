<?php
declare(strict_types=1);
/**
 * The close view file of program module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();
formPanel
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->program->realEnd),
        set::name('realEnd'),
        set::control('date'),
        set::value(!helper::isZeroDate($program->realEnd) ? $program->realEnd : helper::today()),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6')
        )
    )
);
hr();
history();
