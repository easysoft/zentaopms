<?php
declare(strict_types=1);
/**
 * The createLib view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('libType', $type);
formPanel
(
    set::title($lang->doc->createLib),
    in_array($type, array('product', 'project')) ? formGroup
    (
        set::label($lang->doc->libType),
        setClass($config->vision == 'lite' ? 'hidden' : ''),
        set::width('3/4'),
        radioList
        (
            set::name('libType'),
            set::items($lang->doclib->type),
            set::value('wiki'),
            set::inline(true),
            on::change('changeDoclibAcl')
        )
    ) : null,
    in_array($type, array('product', 'project', 'execution')) ? formRow
    (
        setClass('objectBox'),
        formGroup
        (
            set::width('3/4'),
            set::label($lang->doc->{$type}),
            set::name($type),
            set::items($objects),
            set::value($objectID),
            set::required(true),
            $type == 'project' ? on::change('loadExecution') : ''
        )
    ) : null,
    in_array($type, array('product', 'project', 'execution')) && $app->tab == 'doc' && $type == 'project' ? formRow
    (
        setClass('executionBox'),
        formGroup
        (
            set::label($lang->doc->execution),
            set::width('3/4'),
            set::name('execution'),
            set::items($executionPairs),
            set::placeholder($lang->doclib->tip->selectExecution),
            set::disabled(!$project->multiple)
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->doclib->tip->selectExecution),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ) : null,
    formRow
    (
        setClass('normalLib'),
        formGroup
        (
            set::label($lang->doclib->name),
            set::width('3/4'),
            set::name('name')
        )
    ),
    formRow
    (
        setClass('apilib hidden'),
        formGroup
        (
            set::label($lang->api->baseUrl),
            set::width('3/4'),
            set::name('baseUrl'),
            set::placeholder($lang->api->baseUrlDesc)
        )
    ),
    formRow
    (
        setID('aclBox'),
        formGroup
        (
            set::label($lang->doclib->control),
            set::width('3/4'),
            radioList
            (
                set::name('acl'),
                set::items($lang->doclib->aclList),
                set::value($acl),
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
            set::width('3/4'),
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
                            set::multiple(true)
                        )
                    )
                ),
                div
                (
                    users(set::label($lang->doclib->user), set::items($users))
                )
            )
        )
    ),
    formRow
    (
        setID('aclAPIBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doclib->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->api->aclList)
            )
        )
    ),
    formRow
    (
        setID('aclOtherBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doclib->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->doclib->aclList)
            )
        ),
        formHidden('type', $type)
    )
);

/* ====== Render page ====== */
render();
