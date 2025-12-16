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

if($objectType == 'template')
{
    include 'setdocbasic.template.html.php';
    return;
}

if($modalType == 'chapter') $lang->doc->aclList['private'] = $lang->doclib->aclList['private'];

formPanel
(
    setID('setDocBasicForm'),
    setData('officeTypes', $this->config->doc->officeTypes),
    setData('docType', isset($doc) ? $doc->type : 'undefined'),
    set::title($title),
    set::submitBtnText($lang->save),
    on::change('[name=space],[name=product],[name=execution]')->call('loadObjectModules', jsRaw('event'), $docID),
    on::change('[name=lib]')->call('loadLibModules', jsRaw('event'), $docID),
    on::change('[name=project]')->call('loadExecutions', jsRaw('event')),
    on::change('[name=lib],[name^=users]', "checkLibPriv('#whiteListBox', 'users')"),
    on::change('[name=lib],[name^=readUsers]', "checkLibPriv('#readListBox', 'readUsers')"),
    set::ajax(array('beforeSubmit' => jsRaw('window.beforeSetDocBasicInfo'))),

    formGroup
    (
       set::width('1/2'),
       set::label($modalType == 'chapter' ? $lang->doc->chapterName : $lang->doc->title),
       set::name('title'),
       set::required(true),
       set::value($docTitle)
    ),
    $this->app->tab == 'doc' && $objectType == 'project' && $modalType != 'chapter' ? formRow
    (
        formGroup
        (
           setClass('w-1/2'),
           set::label($lang->doc->project),
           set::name('project'),
           set::items(createLink('project', 'ajaxGetDropMenu', "objectID=$objectID&module=&method=&extra=selectmode&useLink=0")),
           set::value(isset($execution) ? $execution->project : $objectID),
           set::required(true)
        ),
        ($mode == 'create' && $this->app->tab == 'doc' and $config->vision == 'rnd') ? formGroup
        (
            setClass('w-1/2'),
            set::label($lang->doc->execution),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $executions, 'value' => isset($execution) ? $objectID : ''))
        ) : null
    ) : null,
    $this->app->tab == 'doc' && $objectType == 'execution' && $modalType != 'chapter' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->execution),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $objects, 'required' => true, 'value' => $lib->execution))
    ) : null,
    $this->app->tab == 'doc' && $objectType == 'product' && $modalType != 'chapter' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->product),
        set::name('product'),
        set::items(createLink('product', 'ajaxGetDropMenu', "objectID=$objectID&module=&method=&extra=selectmode&useLink=0")),
        set::value($objectID),
        set::required(true)
    ) : null,
    (($objectType == 'custom' || $objectType === 'mine') && $modalType != 'chapter') ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'space', 'items' => $spaces, 'required' => true, 'value' => "{$objectType}.{$objectID}"))
    ) : null,
    ($modalType != 'chapter' || !$isCreate) ? formGroup
    (
        setData('libType', $objectType),
        set::width('1/2'),
        set::label($lang->doc->lib),
        set::required(true),
        picker(set::name('lib'), set::items($libs), set::value(isset($libs[$libID]) ? $libID : ''), set::required(true))
    ) : null,
    ($modalType != 'chapter' || !$isCreate) ? formGroup
    (
        set::width('1/2'),
        set::label($lang->doc->module),
        picker
        (
            set::name('parent'),
            set::items($modalType != 'chapter' ? array('m_0' => '/') + $chapterAndDocs : $chapterAndDocs),
            set::value($parentID ? $parentID : "m_$moduleID"),
            set::required(true)
        ),
    ) : null,
    $objectType !== 'mine' && $modalType != 'chapter' ? formGroup
    (
        set::label($lang->doc->mailto),
        mailto(set::items($users), set::value(isset($doc) ? $doc->mailto : null))
    ) : null,
    isset($doc) && $doc->contentType != 'doc' ? formGroup
    (
        setStyle('min-height', 'auto'),
        set::label($lang->doc->files),
        fileSelector()
    ) : null,
    formGroup
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
    formGroup
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
                $lang->doc->groupLabel,
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
                    set::label($lang->doc->userLabel),
                    set::items($users),
                    set::value(isset($doc) ? $doc->users : null)
                )
            )
        )
    )
);
