<?php
declare(strict_types=1);
/**
 * The editLib view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('doclibID', $lib->id);
jsVar('libType', $lib->type);
modalHeader
(
    set::title($lang->doc->editLib),
    set::entityID($lib->id),
    set::entityText($lib->name),
);

formPanel
(
    in_array($lib->type, array('product', 'project', 'execution')) ? formGroup
    (
        set::label($lang->doc->{$lib->type}),
        set::control('static'),
        set::value($object->name)
    ) : null,
    formGroup
    (
        set::label($lang->doc->libName),
        set::name('name'),
        set::value($lib->name),
        radioList
        (
            setClass('hidden'),
            set::name('acl'),
            set::items($lang->doc->libTypeList),
            set::value($lib->type)
        )
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
                set::items($lib->type == 'api' ? $lang->api->aclList : $lang->doclib->aclList),
                set::value(empty($lib->main) ?  $lib->acl : 'default'),
                on::change("toggleAcl('lib')")
            )
        )
    ),
    formRow
    (
        setID('whiteListBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doc->whiteList),
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
                        users(set::items($users), set::value($lib->users))
                    )
                )
            )
        )
    )
);

/* ====== Render page ====== */
render();
