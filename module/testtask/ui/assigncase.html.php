<?php
declare(strict_types=1);
/**
 * The assignTo ui file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->testtask->assignCase),
    set::entityID($run->case->id),
    set::entityText($run->case->title)
);

/* zin: Define the form in main content. */
formPanel
(
    set::submitBtnText($lang->testtask->assignedTo),
    formGroup
    (
        set::width('1/3'),
        set::name('assignedTo'),
        set::label($lang->testtask->assignedTo),
        set::value($run->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
hr();
history(set::objectID($run->case->id), set::objectType('case'));

render();
