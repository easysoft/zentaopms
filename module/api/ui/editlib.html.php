<?php
declare(strict_types=1);
/**
 * The editLib view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('doclibID', $lib->id);
jsVar('libType', $lib->type);
modalHeader
(
    set::title($lang->api->editLib),
    set::entityID($lib->id),
    set::entityText($lib->name)
);

formPanel
(
    in_array($type, array('product', 'project')) ? formGroup
    (
        set::label($lang->api->{$type}),
        set::control('static'),
        set::value($object->name)
    ) : null,
    formGroup
    (
        set::label($lang->api->name),
        set::name('name'),
        set::value($lib->name)
    ),
    formGroup
    (
        set::label($lang->api->baseUrl),
        set::name('baseUrl'),
        set::value($lib->baseUrl)
    ),
    formRow
    (
        setID('aclBox'),
        formGroup
        (
            set::label($lang->api->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->api->aclList),
                set::value($lib->acl),
                on::change("toggleAcl('lib')")
            )
        )
    ),
    formRow
    (
        setID('whiteListBox'),
        setClass($lib->acl == 'private' ? '' : 'hidden'),
        formGroup
        (
            set::label($lang->api->whiteList),
            div
            (
                setClass('w-full check-list'),
                div
                (
                    inputGroup
                    (
                        $lang->doclib->group,
                        picker
                        (
                            set::name('groups[]'),
                            set::items($groups),
                            set::value($lib->groups),
                            set::multiple(true)
                        )
                    )
                ),
                div
                (
                    inputGroup
                    (
                        $lang->doclib->user,
                        mailto(set::items($users), set::value($lib->users))
                    )
                )
            )
        )
    ),
    formHidden('type', $type)
);

/* ====== Render page ====== */
render();
