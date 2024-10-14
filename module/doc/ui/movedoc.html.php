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
        set::control(array('control' => "picker", 'name' => 'module', 'items' => $optionMenu, 'value' => $doc->module, 'required' => true))
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
                set::items($lang->doc->aclList),
                set::value($defaultAcl),
                on::change("toggleDocAcl")
            )
        )
    ),
    formRow
    (
        setID('whiteListBox'),
        setClass(($libID == $doc->lib && $spaceType != 'mine' && $defaultAcl == 'private') ? '' : 'hidden'),
        formGroup
        (
            set::label($lang->doc->whiteList),
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
