<?php
declare(strict_types=1);
/**
 * The binduser view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gitlab
 * @link        http://www.zentao.net
 */

namespace zin;

$userItems = array();
foreach($userPairs as $enName => $name)
{
    $userItems[] = array('text' => $name, 'value' => $enName);
}
jsVar('userItems', $userItems);
jsVar('zentaoUsers', $zentaoUsers);
jsVar('gitlabUsers', $gitlabUsers);
jsVar('accountDesc',  $lang->gitlab->accountDesc);

/* zin: Define the set::module('compile') feature bar on main menu. */
featureBar
(
    to::leading(array(backBtn(set::icon('back'), set::class('secondary'), $lang->goback))),
    set::current($type),
    set::link($this->createLink('gitlab', 'binduser', "gitlabID=$gitlabID&type={key}")),
);

/* zin: Define the toolbar on main menu. */
toolbar();


$tbody = array();
foreach($gitlabUsers as $index => $user)
{
    $tbody[] = h::tr
    (
        h::td
        (
            avatar($user->avatar, set::size(20), setClass('mr-2')),
            $user->realname,
            input(set::value($user->realname), set::name("gitlabUserNames[$user->id]"), set::type('hidden'))
        ),
        h::td
        (
            $user->email
        ),
        h::td
        (
            setID('zentaoEmail-' . $user->id),
            $user->zentaoEmail
        ),
        h::td
        (
            picker
            (
                setID('users-' . $user->id),
                set::required(false),
                set::name("zentaoUsers[$user->id]"),
                set::value($user->zentaoAccount),
                set::items($userPairs)
            ),
        ),
        h::td
        (
            span
            (
                setClass($user->status),
                $lang->gitlab->{$user->status}
            )
        ),
    );
}

form
(
    setClass('mb-4'),
    h::table
    (
        setClass('table table-fixed canvas'),
        h::tr
        (
            h::th
            (
                $lang->gitlab->gitlabAccount
            ),
            h::th
            (
                $lang->gitlab->gitlabEmail
            ),
            h::th
            (
                $lang->gitlab->zentaoEmail
            ),
            h::th
            (
                set::width('300px'),
                $lang->gitlab->zentaoAccount,
                span(setClass('gitlab-account-desc'), $lang->gitlab->accountDesc)
            ),
            h::th
            (
                set::width('100px'),
                $lang->gitlab->bindingStatus
            ),
        ),
        $tbody
    )
);
