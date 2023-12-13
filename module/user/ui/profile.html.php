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
if(!isonlybody()) include 'featurebar.html.php';

$groupName = '';
foreach($groups as $group) $groupName .= $group->name . ' ';

$deptName = '/';
if($deptPath)
{
    $deptName = array();
    foreach($deptPath as $key => $dept) $deptName[] = $dept->name;
    $deptName = implode($lang->arrow, $deptName);
};

div
(
    set::style(array('width' => '60%')),
    setClass('bg-white m-auto p-5 mb-4'),
    div
    (
        setClass('flex items-center pb-5'),
        cell
        (
            set::width('50%'),
            setClass('text-right pr-3'),
            userAvatar(set::user($user))
        ),
        cell
        (
            set::width('50%'),
            div(setClass('user-name text-md font-bold'), $user->realname),
            zget($lang->user->roleList, $user->role, '') == '' ? null : div
            (
                setClass('user-role text-gray'),
                zget($lang->user->roleList, $user->role)
            )
        )
    ),
    h::table
    (
        h::tr
        (
            h::th($lang->user->realname),
            h::td($user->realname),
            h::th($lang->user->gender),
            h::td(zget($lang->user->genderList, $user->gender))
        ),
        h::tr
        (
            h::th($lang->user->account),
            h::td($user->account),
            h::th($lang->user->email),
            h::td(set::title($user->email), $user->email ? a(set::href("mailto:{$user->email}"), $user->email) : null)
        ),
        h::tr
        (
            h::th($lang->user->dept),
            h::td(html($deptName)),
            h::th($lang->user->role),
            h::td(zget($lang->user->roleList, $user->role, ''))
        ),
        h::tr
        (
            h::th($lang->user->abbr->join),
            h::td(formatTime($user->join)),
            h::th($lang->user->priv),
            h::td(trim($groupName))
        )
    ),
    h::hr(),
    h::table
    (
        h::tr
        (
            h::th($lang->user->mobile),
            h::td($user->mobile),
            h::th($lang->user->weixin),
            h::td($user->weixin)
        ),
        h::tr
        (
            h::th($lang->user->phone),
            h::td($user->phone),
            h::th($lang->user->qq),
            h::td($user->qq)
        ),
        h::tr
        (
            h::th($lang->user->zipcode),
            h::td($user->zipcode),
            h::th($lang->user->abbr->address),
            h::td(set::title($user->address), $user->address)
        )
    ),
    isInModal() ? null : h::hr(),
    isInModal() ? null : h::table
    (
        h::tr
        (
            h::th($lang->user->commiter),
            h::td($user->commiter),
            h::th($lang->user->skype),
            h::td($user->skype ? a(set::href("callto://{$user->skype}"), $user->skype) : null)
        ),
        h::tr
        (
            h::th($lang->user->visits),
            h::td($user->visits),
            h::th($lang->user->whatsapp),
            h::td($user->whatsapp)
        ),
        h::tr
        (
            h::th($lang->user->last),
            h::td($user->last),
            h::th($lang->user->slack),
            h::td($user->slack)
        ),
        h::tr
        (
            h::th($lang->user->ip),
            h::td($user->ip),
            h::th($lang->user->dingding),
            h::td($user->dingding)
        )
    )
);

render();
