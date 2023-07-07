<?php
declare(strict_types=1);
/**
 * The managecontacts view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$myContactList = array();
foreach($lists as $id => $listName)
{
    $myContactList[] = li
    (
        set::class('editContact ellipsis pl-2 ' . ($id == $listID ? 'active' : '')),
        set('data-id', $id),
        $listName
    );
}

formPanel
(
    set::actions(array()),
    on::click('#createContact', 'getCreateForm'),
    on::click('.editContact',   'getEditForm'),
    div
    (
        set::id('manageContacts'),
        set::class('w-full flex'),
        cell
        (
            set::width('180px'),
            set::class('border-r overflow-hidden'),
            div
            (
                set::class('border-b py-4 pr-4'),
                div(span(set::class('text-gray'), $lang->my->contactList)),
                div
                (
                    set::class('pt-2'),
                    a
                    (
                        set::id('createContact'),
                        set::class('btn secondary-pale w-full'),
                        set::href('javascript:;'),
                        icon('plus'),
                        $lang->user->contacts->createList
                    )
                )
            ),
            div
            (
                tabs
                (
                    set::class('pr-4'),
                    tabPane
                    (
                        set::key('my'),
                        set::title($lang->my->myContact),
                        set::active(true),
                        ul($myContactList)
                    ),
                    tabPane
                    (
                        set::key('public'),
                        set::title($lang->my->publicContact),
                        ul($publicContactList)
                    )
                )
            )
        ),
        cell
        (
            set::id('dataForm'),
            set::class('flex-1 px-8'),
            div
            (
                set('class', 'panel-title text-lg flex w-full py-6'),
                '创建联系人'
            ),
            div
            (
                input(set::class('hidden'), set::name('mode'), set::value($mode)),
                formRow
                (
                    set::class('py-2'),
                    formGroup
                    (
                        set::width('1/2'),
                        set::label('名称'),
                        input
                        (
                            set::name('newList'),
                            set::value()
                        )
                    )
                ),
                formRow
                (
                    set::class('py-2'),
                    formGroup
                    (
                        set::label('选择用户'),
                        select
                        (
                            set::multiple(true),
                            set::name('users[]'),
                            set::items($users),
                            set::value()
                        )
                    )
                ),
                formRow
                (
                    formGroup
                    (
                        set::label(''),
                        set::width('1/1'),
                        checkbox
                        (
                            set::name('public'),
                            set::text('公开联系人'),
                        )
                    )
                ),
                formRow
                (
                    formGroup
                    (
                        set::label(''),
                        set::class('form-actions justify-start'),
                        button(set::class('btn primary'), set::type('submit'), $lang->save)
                    )
                )
            )
        )
    )
);

render();
