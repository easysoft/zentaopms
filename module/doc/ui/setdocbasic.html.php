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
    setData('officeTypes', $this->config->doc->officeTypes),
    setData('docType', isset($doc) ? $doc->users : 'undefined'),
    set::title($title),
    set::submitBtnText($isDraft ? $lang->doc->saveDraft : (empty($docID) ? $lang->doc->release : $lang->save)),
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
        ($mode == 'create' && $this->app->tab == 'doc' and $config->vision == 'rnd') ? formGroup
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
        set::control(array('control' => 'picker', 'name' => 'space', 'items' => $spaces, 'required' => true, 'value' => "{$objectType}.{$objectID}"))
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
    $isDraft ? null : formGroup
    (
        set::label($lang->doc->mailto),
        mailto(
            set::items($users),
            set::value(isset($doc) ? $doc->mailto : null)
        )
    ),
    $isDraft ? null : formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            setClass($objectType == 'mine' ? 'pointer-events-none' : ''),
            set::name('acl'),
            set::items($lang->doc->aclList),
            set::value(isset($doc) ? $doc->acl : ($objectType == 'mine' ? 'private' : 'open')),
            $objectType != 'mine' ? on::change('toggleWhiteList') : null
        )
    ),
    $isDraft ? null : formGroup
    (
        setID('whiteListBox'),
        setClass((isset($doc) && $libID == $doc->lib && $objectType != 'mine' && $doc->acl == 'private') ? '' : 'hidden'),
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
                    set::value(isset($doc) ? $doc->groups : null),
                    set::multiple(true)
                )
            ),
            div
            (
                setClass('w-full'),
                userPicker
                (
                    set::label($lang->doc->users),
                    set::items($users),
                    set::value(isset($doc) ? $doc->users : null)
                )
            )
        )
    )
);
