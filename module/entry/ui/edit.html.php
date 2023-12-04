<?php
declare(strict_types=1);
/**
 * The edit view file of entry module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     entry
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::back('GLOBAL'),
    to::heading
    (
        entityLabel
        (
            to::prefix($lang->entry->edit),
            set::entityID($entry->id),
            set::level(1),
            set::text($entry->name),
            set::reverse(true)
        )
    ),
    to::headingActions
    (
        toolbar
        (
            a
            (
                setClass('text-darken'),
                set::href($lang->entry->helpLink),
                set('target', '_blank'),
                $lang->entry->help
            ),
            item(set::type('divider')),
            a
            (
                setClass('text-darken'),
                set::href($lang->entry->notifyLink),
                set('target', '_blank'),
                $lang->entry->notify
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->name),
            set::name('name'),
            set::title($lang->entry->note->name),
            set::placeholder($lang->entry->note->name),
            set::value($entry->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->code),
            set::name('code'),
            set::title($lang->entry->note->code),
            set::placeholder($lang->entry->note->code),
            set::value($entry->code)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->freePasswd),
            radioList
            (
                on::change('toggleFreePasswd'),
                set::name('freePasswd'),
                set::items($lang->entry->freePasswdList),
                set::value($entry->freePasswd),
                set::inline(true)
            )
        )
    ),
    formRow
    (
        setClass($entry->freePasswd ? 'hidden' : ''),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->account),
            set::placeholder($lang->entry->note->account),
            set::name('account'),
            set::items($users),
            set::value($entry->account)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->key),
            set::name('key'),
            set::value($entry->key),
            set::readonly(true)
        ),
        formGroup
        (
            a
            (
                setClass('btn ml-2 text-darken'),
                on::click('createKey()'),
                $lang->entry->createKey
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->ip),
            set::title($lang->entry->note->ip),
            set::placeholder($lang->entry->note->ip),
            set::name('ip'),
            set::value($entry->ip == '*' ? '' : $entry->ip),
            set::disabled($entry->ip == '*')
        ),
        formGroup
        (
            setClass('items-center ml-2'),
            checkbox
            (
                set::name('allIP'),
                on::change('toggleAllIP'),
                set::checked($entry->ip == '*'),
                $lang->entry->note->allIP
            )
        )
    ),
    formGroup
    (
        set::label($lang->entry->desc),
        editor
        (
            set::name('desc'),
            set::rows('3'),
            html($entry->desc)
        )
    )
);

render();
