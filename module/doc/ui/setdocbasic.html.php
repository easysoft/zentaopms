<?php
declare(strict_types=1);
/**
 * The setDocBasic view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('setDocBasicForm'),
    set::title($title),
    set::submitBtnText($lang->doc->release),
    on::change('[name=space],[name=product],[name=execution]')->call('loadObjectModules', jsRaw('event')),
    on::change('[name=lib]')->call('loadLibModules', jsRaw('event')),
    on::change('[name=project]')->call('loadExecutions', jsRaw('event')),
    on::change('[name=lib],[name^=users]', 'checkLibPriv'),
    set::ajax(array('beforeSubmit' => jsRaw('window.beforeSetDocBasicInfo'))),

    $objectType == 'project' ? formRow
    (
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->project),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => 'project', 'items' => $objects, 'required' => true, 'value' => isset($execution) ? $execution->project : $objectID))
        ),
        ($this->app->tab == 'doc' and $config->vision == 'rnd') ? formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->execution),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $executions, 'value' => isset($execution) ? $objectID : ''))
        ) : null
    ) : null,
    ($objectType == 'execution') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->execution),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $objects, 'required' => true, 'value' => $lib->execution))
    ) : null,
    ($objectType == 'product') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->product),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'product', 'items' => $objects, 'required' => true, 'value' => $objectID))
    ) : null,
    ($objectType == 'custom' || $objectType === 'mine') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'space', 'items' => $spaces, 'required' => true, 'value' => $objectID, 'disabled' => $lib->type == 'mine'))
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->lib),
        set::required(true),
        picker(set::name('lib'), set::items($libs), set::value(isset($libs[$libID]) ? $libID : ''), set::required(true))
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->module),
        picker(set::name('module'), set::items($optionMenu), set::value($moduleID), set::required(true))
    ),
    formGroup
    (
        set::label($lang->doc->mailto),
        mailto(set::items($users))
    ),
    formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value(isset($doc) ? $doc->acl : 'private'),
            on::change('toggleWhiteList')
        )
    ),
    formGroup
    (
        setID('whiteListBox'),
        setClass('hidden'),
        set::label($lang->doc->whiteList),
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
                    set::multiple(true)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker(set::label($lang->doc->users), set::items($users))
            )
        )
    )
);
