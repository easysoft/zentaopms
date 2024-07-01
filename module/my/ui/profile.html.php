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
    foreach($datas as $field => $data)
    {
        $cells[] = cell
        (
            set::width('50%'),
            set::className('flex py-2 ' . $field),
            cell
            (
                set::width('70px'),
                set::className('text-right'),
                span(set::className('text-gray'), $data['label'])
            ),
            cell
            (
                set::flex('1'),
                set::className('ml-2'),
                $data['value']
            )
        );
    }

    return $cells;
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
        set::className('py-2 flex flex-wrap basic-info'),
        $getItems(array('realname' => array('label' => $lang->user->realname,   'value' => $user->realname))),
        $getItems(array('gender'   => array('label' => $lang->user->gender,     'value' => zget($lang->user->genderList, $user->gender)))),
        $getItems(array('account'  => array('label' => $lang->user->account,    'value' => $user->account))),
        $getItems(array('email'    => array('label' => $lang->user->email,      'value' => $user->email ? a(set::href("mailto:{$user->email}"), $user->email, set::target('_self')) : ''))),
        $getItems(array('dept'     => array('label' => $lang->user->dept,       'value' => html($deptName)))),
        $getItems(array('role'     => array('label' => $lang->user->role,       'value' => zget($lang->user->roleList, $user->role, '')))),
        $getItems(array('join'     => array('label' => $lang->user->abbr->join, 'value' => formatTime($user->join)))),
        $getItems(array('priv'     => array('label' => $lang->user->priv,       'value' => trim($groupName))))
    ),
    formRowGroup(set::title($lang->my->form->lblContact)),
    div
    (
        set::className('py-2 flex flex-wrap contact-info'),
        $getItems(array('mobile'  => array('label' => $lang->user->mobile,        'value' => $user->mobile))),
        $getItems(array('weixin'  => array('label' => $lang->user->weixin,        'value' => $user->weixin))),
        $getItems(array('phone'   => array('label' => $lang->user->phone,         'value' => $user->phone))),
        $getItems(array('qq'      => array('label' => $lang->user->qq,            'value' => $user->qq))),
        $getItems(array('zipcode' => array('label' => $lang->user->zipcode,       'value' => $user->zipcode))),
        $getItems(array('address' => array('label' => $lang->user->abbr->address, 'value' => $user->address)))
    ),
    formRowGroup(set::title($lang->my->form->lblAccount)),
    div
    (
        set::className('py-2 flex flex-wrap account-info'),
        $getItems(array('commiter' => array('label' => $lang->user->commiter, 'value' => $user->commiter))),
        $getItems(array('skype'    => array('label' => $lang->user->skype,    'value' => $user->skype ? a(set::href("callto://{$user->skype}"), $user->skype) : ''))),
        $getItems(array('visits'   => array('label' => $lang->user->visits,   'value' => $user->visits))),
        $getItems(array('whatsapp' => array('label' => $lang->user->whatsapp, 'value' => $user->whatsapp))),
        $getItems(array('last'     => array('label' => $lang->user->last,     'value' => $user->last))),
        $getItems(array('slack'    => array('label' => $lang->user->slack,    'value' => $user->slack))),
        $getItems(array('ip'       => array('label' => $lang->user->ip,       'value' => $user->ip))),
        $getItems(array('dingding' => array('label' => $lang->user->dingding, 'value' => $user->dingding)))
    ),
    center
    (
        setClass('w-full actions-menu my-profile'),
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
