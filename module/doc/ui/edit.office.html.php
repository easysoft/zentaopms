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
    on::change('#product',   "loadObjectModules"),
    on::change('#project',   "loadObjectModules"),
    on::change('#execution', "loadObjectModules"),
    (strpos('product|project|execution', $type) !== false) ? formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::label($lang->doc->{$type}),
        picker
        (
            set::name($type),
            set::id($type),
            set::items($objects),
            set::value($objectID),
            set::required(true)
        )
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::label($lang->doc->libAndModule),
        picker
        (
            set::name('module'),
            set::items($moduleOptionMenu),
            set::value($doc->lib . '_' . $doc->module),
            set::required(true)
        )
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
    formGroup
    (
        $doc->acl == 'open' ? setClass('hidden') : null,
        set::label($lang->doc->whiteList),
        set::id('whitelistBox'),
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
    ),
    formHidden('contentType', $doc->contentType),
    formHidden('type', $doc->type),
    formHidden('status', $doc->status),
    formHidden('parent', $doc->parent)
);
