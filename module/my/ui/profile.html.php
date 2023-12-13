<?php
declare(strict_types=1);
/**
 * The profile view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('avatar', $this->app->user->avatar);
jsVar('userID', $this->app->user->id);

$groupName = '';
foreach($groups as $group) $groupName .= $group->name . ' ';

$deptName = '/';
if($deptPath)
{
    $deptName = array();
    foreach($deptPath as $key => $dept) $deptName[] = $dept->name;
    $deptName = implode($lang->arrow, $deptName);
};

$getItems = function($datas)
{
    $cells = array();
    foreach($datas as $label => $value)
    {
        $cells[] = cell
        (
            set::width('50%'),
            set::className('flex'),
            cell
            (
                set::width('70px'),
                set::className('text-right'),
                span(set::className('text-gray'), $label)
            ),
            cell
            (
                set::flex('1'),
                set::className('ml-2'),
                $value
            )
        );
    }

    return div
    (
        set::className('flex py-2'),
        $cells
    );
};


div
(
    div
    (
        set::className('center'),
        div
        (
            userAvatar(set::user($user), set::size('lg')),
            formbase(set::id('avatarForm'), set::className('hidden'), set::url(createLink('my', 'uploadAvatar')), upload())
        ),
        div
        (
            span(setClass('text-md text-black'), $user->realname),
            zget($lang->user->roleList, $user->role, '') == '' ? null : span
            (
                setClass('text-base text-gray ml-1'),
                '(' . zget($lang->user->roleList, $user->role) . ')'
            )
        )
    ),
    formRowGroup(set::title($lang->my->form->lblBasic)),
    div
    (
        set::className('py-2'),
        $getItems(array($lang->user->realname => $user->realname,         $lang->user->gender => zget($lang->user->genderList, $user->gender))),
        $getItems(array($lang->user->account  => $user->account,          $lang->user->email  => $user->email ? a(set::href("mailto:{$user->email}"), $user->email) : '')),
        $getItems(array($lang->user->dept     => html($deptName),         $lang->user->role   => zget($lang->user->roleList, $user->role, ''))),
        $getItems(array($lang->user->abbr->join   => formatTime($user->join), $lang->user->priv   => trim($groupName)))
    ),
    formRowGroup(set::title($lang->my->form->lblContact)),
    div
    (
        set::className('py-2'),
        $getItems(array($lang->user->mobile  => $user->mobile,  $lang->user->weixin    => $user->weixin)),
        $getItems(array($lang->user->phone   => $user->phone,   $lang->user->qq        => $user->qq)),
        $getItems(array($lang->user->zipcode => $user->zipcode, $lang->user->abbr->address => $user->address))
    ),
    formRowGroup(set::title($lang->my->form->lblAccount)),
    div
    (
        set::className('py-2'),
        $getItems(array($lang->user->commiter => $user->commiter, $lang->user->skype    => $user->skype ? a(set::href("callto://{$user->skype}"), $user->skype) : '')),
        $getItems(array($lang->user->visits   => $user->visits,   $lang->user->whatsapp => $user->whatsapp)),
        $getItems(array($lang->user->last     => $user->last,     $lang->user->whatsapp => $user->whatsapp)),
        $getItems(array($lang->user->ip       => $user->ip,       $lang->user->dingding => $user->dingding))
    ),
    center
    (
        floatToolbar
        (
            set::object($user),
            set::main(array(
                common::hasPriv('my', 'changepassword') ? array('text' => $lang->changePassword,    'data-toggle' => 'modal', 'url' => createLink('my', 'changepassword')) : null,
                common::hasPriv('my', 'editprofile')    ? array('text' => $lang->user->editProfile, 'data-toggle' => 'modal', 'url' => createLink('my', 'editprofile')) : null,
                common::hasPriv('my', 'uploadAvatar')   ? array('text' => $lang->my->uploadAvatar,  'data-on' => 'click', 'data-call' => 'uploadAvatar') : null
            ))
        )
    )
);

render();
