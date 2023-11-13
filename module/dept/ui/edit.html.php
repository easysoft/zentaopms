<?php
declare(strict_types=1);
/**
 * The edit view file of dept module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     dept
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::text($lang->dept->edit),
    set::entityID('')
);

formPanel
(
    set::formClass('boder-b-0'),
    set::submitBtnText($lang->save),
    formRow
    (
        formGroup
        (
            set::label($lang->dept->parent),
            picker
            (
                set::name('parent'),
                set::items($optionMenu),
                set::value($dept->parent)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->dept->name),
            set::name('name'),
            set::value($dept->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->dept->manager),
            picker
            (
                set::name('manager'),
                set::items($users),
                set::value($dept->manager)
            )
        )
    )
);

render();

