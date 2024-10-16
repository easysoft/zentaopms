<?php
declare(strict_types=1);
/**
 * The moveLib view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::titleClass('text-root font-bold'), set::title($lang->doc->moveLibAction));

$defaultAcl = $lib->acl;
if($libType == 'mine') $defaultAcl = 'private';
if($libType == 'custom' && ($lib->type == 'mine' || $lib->parent != $targetSpace)) $defaultAcl = 'open';

jsVar('targetSpace', $targetSpace);
jsVar('libID', $lib->id);
jsVar('errorOthersCreated', $lang->doc->errorOthersCreated);
formPanel
(
    on::change('[name=space]', 'changeSpace'),
    $hasOthersDoc ? set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))) : null,
    formGroup
    (
        set::width('5/6'),
        set::name("space"),
        set::label($lang->doc->space),
        set::value($targetSpace),
        set::control("picker"),
        set::items($spaces),
        set::required(true)
    ),
    formRow
    (
        setID('aclBox'),
        formGroup
        (
            set::label($lang->doclib->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->doclib->aclList),
                set::value($defaultAcl),
                on::change("toggleLibAcl")
            )
        )
    ),
    formRow
    (
        setID('whiteListBox'),
        setClass(($libType != 'mine' && $defaultAcl == 'private') ? '' : 'hidden'),
        formGroup
        (
            set::label($lang->doc->whiteList),
            set::width('5/6'),
            div
            (
                setClass('w-full check-list'),
                div
                (
                    setClass('w-full'),
                    inputGroup
                    (
                        $lang->doclib->group,
                        picker(set::name('groups[]'), set::items($groups), set::multiple(true))
                    )
                ),
                div
                (
                    setClass('w-full'),
                    userPicker(set::label($lang->doclib->user), set::items($users))
                )
            )
        )
    )
);
