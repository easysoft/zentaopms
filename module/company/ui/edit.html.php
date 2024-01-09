<?php
declare(strict_types=1);
/**
 * The edit view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::formClass('border-0'),
    set::shadow(false),
    set::title($company->name),
    set::headingClass('modal-heading'),
    set::actions(array('submit')),
    set::submitBtnText($lang->save),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->company->name),
            set::value($company->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('phone'),
            set::label($lang->company->phone),
            set::value($company->phone)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('fax'),
            set::label($lang->company->fax),
            set::value($company->fax)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('address'),
            set::label($lang->company->address),
            set::value($company->address)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('zipcode'),
            set::label($lang->company->zipcode),
            set::value($company->zipcode)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('website'),
            set::label($lang->company->website),
            set::value($company->website ? $company->website: 'http://')
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('backyard'),
            set::label($lang->company->backyard),
            set::value($company->backyard ? $company->backyard : 'http://')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->company->guest),
            radioList
            (
                set::name('guest'),
                set::items($lang->company->guestOptions),
                set::value($company->guest),
                set::inline(true)
            )
        )
    )
);

render();
