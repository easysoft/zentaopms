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
$acl = 'default';
if($type == 'mine')   $acl = 'private';
if($type == 'custom') $acl = 'open';

formPanel
(
    set::title($lang->doc->createLib),
    on::change('[name=product]',   'checkObjectPriv'),
    on::change('[name=project]',   'checkObjectPriv'),
    on::change('[name=execution]', 'checkObjectPriv'),
    on::change('[name^=users]',    'checkObjectPriv'),
    in_array($type, array('product', 'project')) ? formGroup
    (
        set::label($lang->doc->libType),
        setClass($config->vision == 'lite' ? 'hidden' : ''),
        set::width('5/6'),
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
            set::width('5/6'),
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
            set::width('5/6'),
            set::name('execution'),
            set::items($executionPairs),
            set::placeholder($lang->doclib->tip->selectExecution),
            set::disabled(empty($project->multiple))
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
    $type == 'custom' || $type == 'mine' ? formRow
    (
        formGroup
        (
            set::label($lang->doc->space),
            set::required(true),
            set::width('5/6'),
            inputGroup
            (
                set::seg(true),
                picker
                (
                    $spaces ? null : setClass('hidden'),
                    set::name('parent'),
                    set::items($spaces),
                    set::value($spaceID),
                    set::required(true)
                ),
                input
                (
                    $spaces ? setClass('hidden') : null,
                    set::name('spaceName'),
                ),
                div
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        on::change('toggleNewSpace'),
                        set::name('newSpace'),
                        set::checked(empty($spaces)),
                        set::text($lang->doclib->createSpace)
                    )
                )
            )
        )
    ) : null,
    formRow
    (
        setClass('normalLib'),
        formGroup
        (
            set::label($lang->doclib->name),
            set::width('5/6'),
            set::name('name')
        )
    ),
    formRow
    (
        setClass('apilib hidden'),
        formGroup
        (
            set::label($lang->api->baseUrl),
            set::width('5/6'),
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
            set::width('5/6'),
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
                    setClass('w-full'),
                    userPicker(set::label($lang->doclib->user), set::items($users))
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
