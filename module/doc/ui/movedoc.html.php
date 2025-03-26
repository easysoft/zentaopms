<?php
declare(strict_types=1);
/**
 * The moveDoc view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->doc->moveDocAction));

$defaultAcl = $doc->acl;
if($spaceType == 'mine')
{
    $defaultAcl = 'private';
    $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
}

jsVar('spaceType', $spaceType);
jsVar('space', $space);
jsVar('libID', $libID);
jsVar('docID', $docID);
formPanel
(
    on::change('[name=space]', 'changeSpace'),
    on::change('[name=lib]', 'changeLib'),
    on::change('[name=lib],[name^=users]', "checkLibPriv('#whiteListBox', 'users')"),
    on::change('[name=lib],[name^=readUsers]', "checkLibPriv('#readListBox', 'readUsers')"),
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => "picker", 'name' => 'space', 'items' => $spaces, 'value' => "{$spaceType}.{$space}"))
    ),
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => "picker", 'name' => 'lib', 'items' => $libPairs, 'value' => $libID))
    ),
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->module),
        set::control(array('control' => "picker", 'name' => 'parent', 'items' => array('m_0' => '/') + $optionMenu, 'value' => empty($doc->parent) ? "m_{$doc->module}" : $doc->parent, 'required' => true))
    ),
    formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value($defaultAcl),
            on::change('toggleWhiteList')
        )
    ),
    formGroup
    (
        setID('whiteListBox'),
        setClass(($spaceType != 'mine' && $defaultAcl == 'private') ? '' : 'hidden'),
        set::label($lang->doc->whiteList),
        div
        (
            setClass('w-full check-list'),
            inputGroup
            (
                setClass('w-full'),
                $lang->doc->groupLabel,
                picker
                (
                    set::name('groups[]'),
                    set::items($groups),
                    set::multiple(true),
                    set::value($doc->groups)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker
                (
                    set::label($lang->doc->userLabel),
                    set::items($users),
                    set::value($doc->users)
                )
            )
        )
    )
);
