<?php
declare(strict_types=1);
/**
 * The close view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($testtask->name);

form
(
    formGroup
    (
        set::label($lang->testtask->status),
        set::name('status'),
        set::value('done'),
        set::class('hidden'),
    ),
    formGroup
    (
        set::label($lang->testtask->realFinishedDate),
        set::name('realFinishedDate'),
        set::value(helper::isZeroDate($testtask->realFinishedDate) ? helper::now() : $testtask->realFinishedDate),
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows(6)
        )
    ),
    formGroup
    (
        set::label($lang->testtask->mailto),
        inputGroup
        (
            select
            (
                set::name('mailto'),
                set::items($users),
                set::value($testtask->mailto ? tr_replace(' ', '', $testtask->mailto) : ''),
                set::multiple(true),
            ),
            select
            (
                set::name('contact'),
                set::items($contactList),
            )
        )
    ),
    set::actions(array('submit'))
);

history();

render('modalDialog');

