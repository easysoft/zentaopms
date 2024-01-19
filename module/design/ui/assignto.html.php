<?php
declare(strict_types=1);
/**
 * The assignTo view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the form in main content */
modalHeader(set::title($lang->design->assignAction));
formPanel
(
    set::submitBtnText($lang->design->assignAction),
    formGroup
    (
        set::width("1/2"),
        set::name("assignedTo"),
        set::label($lang->design->assignedTo),
        set::value($design->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('5')
        )
    )
);
hr();
history();

/* ====== Render page ====== */
render();
