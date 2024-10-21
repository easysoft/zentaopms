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

$listID = isset($list->id) ? $list->id : 0;

$myContactList      = array();
$publicContactList  = array();
$isActivePublicList = false;
foreach($lists as $contactList)
{
    $selected = $listID == $contactList->id;
    if($contactList->account == $app->user->account)
    {
        $myContactList[] = array('text' => $contactList->listName, 'url' => createLink('my', 'manageContacts', "listID=$contactList->id"), 'selected' => $selected);
    }
    else if($contactList->public)
    {
        $publicContactList[] = array('text' => $contactList->listName, 'url' => createLink('my', 'manageContacts', "listID=$contactList->id"), 'selected' => $selected);
        if($selected) $isActivePublicList = true;
    }
}

$userList = array();
if(!empty($list->userList))
{
    foreach(explode(',', $list->userList) as $account) $userList[] = zget($users, $account);
}

div
(
    setClass('canvas shadow ring rounded mx-auto no-shadow-in-modal'),
    style::maxWidth(1200),
    div
    (
        setID('manageContacts'),
        setClass('row'),
        cell
        (
            setClass('col flex-none p-4 gap-3 w-48 items-stretch'),
            div(setClass('text-gray'), $lang->my->contactList),
            btn
            (
                set::type('primary-pale'),
                set::icon('plus'),
                set::url('my', 'manageContacts'),
                setData('load', '#contactPanel'),
                setClass('w-full'),
                $lang->my->createContacts
            ),
            tabs
            (
                setID('contactTab'),
                tabPane
                (
                    set::title($lang->my->myContact),
                    set::active(!$isActivePublicList),
                    simpleList(set::items($myContactList)),
                ),
                tabPane
                (
                    set::title($lang->my->publicContact),
                    set::active($isActivePublicList),
                    simpleList(set::items($publicContactList)),
                )
            )
        ),
        divider(),
        cell
        (
            setID('contactPanel'),
            setClass('flex-1 px-8 pb-8'),
            div
            (
                set('class', 'panel-title text-lg flex w-full py-6'),
                $label,
                $mode == 'edit' ? array
                (
                    span
                    (
                        setClass('text-warning'),
                        icon('info')
                    ),
                    span
                    (
                        setClass('text-gray text-base font-normal'),
                        $lang->my->manageSelf
                    )
                ) : null
            ),
            ($mode == 'create' || $mode == 'edit') ? form
            (
                set::actions(array()),
                $listID ? null : on::init()->removeClass('#contactTab .selected', 'selected'),
                formRow
                (
                    formGroup
                    (
                        set::width('1/2'),
                        set::required(true),
                        set::label($lang->user->contacts->listName),
                        input
                        (
                            set::name('listName'),
                            set::value(!empty($list->listName) ? $list->listName : '')
                        )
                    )
                ),
                formRow
                (
                    formGroup
                    (
                        set::label($lang->user->contacts->selectedUsers),
                        set::required(true),
                        picker
                        (
                            set::multiple(true),
                            set::name('userList[]'),
                            set::items($users),
                            set::maxItemsCount($config->maxCount),
                            set::value(!empty($list->userList) ? $list->userList : '')
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
                            set::value(1),
                            set::checked(!empty($list->public)),
                            set::text($lang->my->shareContacts)
                        )
                    )
                ),
                formRow
                (
                    setClass('form-actions'),
                    formGroup
                    (
                        set::label(''),
                        button(setClass('btn primary'), set::type('submit'), $lang->save),
                        ($mode == 'edit' && common::hasPriv('my', 'deleteContacts')) ? button
                        (
                            setClass('btn ajax-submit ml-4'),
                            setData('url', createLink('my', 'deleteContacts', "listID=$listID")),
                            setData('confirm', $lang->user->contacts->confirmDelete),
                            $lang->delete
                        ): null
                    )
                )
            ) : tableData
            (
                item
                (
                    set::name($lang->user->contacts->listName),
                    $list->listName
                ),
                item
                (
                    set::name($lang->user->contacts->userList),
                    implode($lang->comma, array_filter($userList))
                )
            )
        )
    )
);
