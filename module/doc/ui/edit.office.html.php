<?php
declare(strict_types=1);
/**
 * The edit view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
formPanel
(
    modalHeader(),
    on::change('[name=product],[name=project],[name=execution],[name=space]', "loadObjectModules"),
    on::change('[name=lib]', "loadLibModules"),
    on::change('[name=lib]',    'checkLibPriv'),
    on::change('[name^=users]', 'checkLibPriv'),
    (strpos('product|project|execution', $type) !== false) ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->{$type}),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => $type, 'items' => $objects, 'required' => true, 'value' => $objectID))
    ) : null,
    ($type == 'mine' || $type == 'custom') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'space', 'items' => $spaces, 'required' => true, 'disabled' => $type == 'mine' ? true : false, 'value' => $type == 'mine' ? 'mine' : $lib->parent))
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'lib', 'items' => $libs, 'required' => true, 'value' => $doc->lib))
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->module),
        set::control(array('control' => 'picker', 'name' => 'module', 'items' => $optionMenu, 'required' => true, 'value' => $doc->module))
    ),
    formGroup
    (
        set::label($lang->doc->title),
        set::name('title'),
        set::value($doc->title),
        set::required(true)
    ),
    formGroup
    (
        strpos($config->doc->officeTypes, $doc->type) === false ? setClass('hidden') : null,
        set::label($lang->doc->keywords),
        set::control('input'),
        set::name('keywords'),
        set::value($doc->keywords)
    ),
    formGroup
    (
        set::label($lang->doc->files),
        fileSelector()
    ),
    formGroup
    (
        set::label($lang->doc->mailto),
        mailto(set::items($users), set::value($doc->mailto))
    ),
    formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value($doc->acl),
            on::change('toggleWhiteList')
        )
    ),
    $lib->type != 'mine' ? formGroup
    (
        $doc->acl == 'open' ? setClass('hidden') : null,
        set::label($lang->doc->whiteList),
        set::id('whiteListBox'),
        div
        (
            setClass('w-full check-list'),
            inputGroup
            (
                setClass('w-full'),
                $lang->doc->groups,
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
                userPicker(set::label($lang->doc->users), set::items($users), set::value($doc->users))
            )
        )
    ) : null,
    formHidden('contentType', $doc->contentType),
    formHidden('type', $doc->type),
    formHidden('status', $doc->status),
    formHidden('parent', $doc->parent)
);
