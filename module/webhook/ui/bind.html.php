<?php
declare(strict_types=1);
/**
 * The bind view file of webhook module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     webhook
 * @link        https://www.zentao.net
 */
namespace zin;

$useridLang = $lang->webhook->dingUserid;
if($webhook->type == 'wechatuser') $useridLang = $lang->webhook->wechatUserid;
if($webhook->type == 'feishuuser') $useridLang = $lang->webhook->feishuUserid;

$statusLang = $lang->webhook->dingBindStatus;
if($webhook->type == 'wechatuser') $statusLang = $lang->webhook->wechatBindStatus;
if($webhook->type == 'feishuuser') $statusLang = $lang->webhook->feishuBindStatus;

$fnBuildTbody = function() use($users, $bindedUsers, $oauthUsers, $useridPairs, $lang)
{
    $trItems = array();

    foreach($users as $user)
    {
        $userid     = '';
        $bindStatus = 0;
        if(isset($bindedUsers[$user->account]))
        {
            $userid     = $bindedUsers[$user->account];
            $bindStatus = 1;
        }
        elseif(isset($oauthUsers[$user->realname]))
        {
            $userid = $oauthUsers[$user->realname];
        }

        $trItems[] = h::tr
        (
            h::td(set::colspan(2), $user->account, span(setClass('label secondary-outline circle ml-2'), $user->realname)),
            h::td
            (
                set::colspan(2),
                div
                (
                    setClass("username"),
                    $userid ? span(setClass('label primary-outline circle'), zget($useridPairs, $userid)) : null
                ),
                formHidden("userid[{$user->account}]", $userid)
            ),
            h::td(set::style(array('text-align' => 'center')), btn(setClass('bind btn-link'), set('data-value', "userid[{$user->account}]"), set('data-toggle', "modal"), set('data-target', '#userList'), set('data-size', 'sm'), icon('edit'))),
            h::td(set::style(array('text-align' => 'center')), \zget($lang->webhook->dingBindStatusList, $bindStatus, ''))
        );
    }

    return h::tbody($trItems);
};

$fnBuildFooter = function() use($users, $lang, $selectedDepts, $webhook)
{
    if(empty($users)) return null;

    return row
    (
        setClass('table-footer justify-between items-center'),
        cell
        (
            btn(set::btnType('submit'), setClass('primary mr-2'), set::size('sm'), $lang->save),
            btn(set::url(createLink('webhook', 'browse')), setClass('mr-2'), set::size('sm'), $lang->goback),
            $selectedDepts ? btn(set::url(createLink('webhook', 'chooseDept', "id={$webhook->id}")), set::size('sm'), $lang->webhook->chooseDeptAgain) : null
        ),
        cell(pager())
    );
};

panel
(
    setClass('m-auto'),
    set::style(array('width' => '900px')),
    set::title($lang->webhook->bind),
    form
    (
        set::actions(false),
        set::method('post'),
        h::table
        (
            setClass('bindUsersList'),
            on::click('.bind', 'showBindModal'),
            setClass('table bordered condensed'),
            h::thead
            (
                h::tr
                (
                    h::th(set::colspan(2), $lang->webhook->zentaoUser),
                    h::th(set::colspan(2), $useridLang),
                    h::th(set::width('80'), set::style(array('text-align' => 'center')), $lang->actions),
                    h::th(set::width('150'), set::style(array('text-align' => 'center')), $statusLang)
                )
            ),
            $fnBuildTbody()
        ),
        $fnBuildFooter()
    )
);

modal
(
    setID('userList'),
    set::title($lang->webhook->bind),
    set::closeBtn(true),
    div
    (
        setClass('mt-3'),
        picker(setID('userSelect'), set::name('userid'), set::items($useridPairs)),
        div(setClass('text-center'), btn(setClass('primary mt-3'), set('onclick', 'confirmChanges()'), $lang->save))
    ),
    div(setID('saveInput'))
);

render();
