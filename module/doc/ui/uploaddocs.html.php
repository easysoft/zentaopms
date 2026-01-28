<?php
declare(strict_types=1);
/**
 * The uploadDocs view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('docID',   $docID);
jsVar('libType', $objectType);
formPanel
(
    set::title(!empty($docID) ? $lang->doc->edit : $lang->doc->uploadFile),
    set::submitBtnText($lang->doc->release),
    $objectType == 'project' ? formRow
    (
        formGroup
        (
            setClass('projectBox'),
            set::label($lang->doc->project),
            set::name('project'),
            set::items($objects),
            set::value($objectID),
            on::change('loadExecutions'),
            set::required(true)
        ),
        $app->tab == 'doc' ? formGroup
        (
            set::width('1/2'),
            setClass('executionBox'),
            set::label($lang->doc->execution),
            set::name('execution'),
            set::items(isset($executions) ? $executions : null),
            set::value(!empty($doc->execution) ? $doc->execution : 0),
            set::placeholder($lang->doc->placeholder->execution),
            on::change('loadObjectModules')
        ) : null
    ) : null,
    $objectType == 'execution' ? formGroup
    (
        set::label($lang->doc->execution),
        set::name('execution'),
        set::items($objects),
        set::value($objectID),
        on::change('loadObjectModules'),
        set::required(true)
    ) : null,
    $objectType == 'product' ? formGroup
    (
        set::label($lang->doc->product),
        set::name('product'),
        set::items($objects),
        set::value($objectID),
        on::change('loadObjectModules'),
        set::required(true)
    ) : null,
    ($objectType == 'custom' || $objectType == 'mine') ? formGroup
    (
        set::label($lang->doc->space),
        set::name('space'),
        set::items($spaces),
        set::value($objectID),
        on::change('loadObjectModules'),
        set::required(true)
    ) : null,
    formGroup
    (
        setData('libType', $objectType),
        set::label($lang->doc->lib),
        set::name('lib'),
        set::items($libs),
        set::value($libID),
        on::change('loadLibModules'),
        set::required(true)
    ),
    formGroup
    (
        setClass('moduleBox'),
        set::label($lang->doc->module),
        set::name('parent'),
        set::items(array('m_0' => '/') + $optionMenu),
        set::value(!empty($doc->parent) ? $doc->parent : ($moduleID ? "m_$moduleID" : 0)),
        set::required(true)
    ),
    formGroup
    (
        setClass('uploadFileBox'),
        set::label($lang->doc->uploadFile),
        fileSelector(set::defaultFiles(!empty($doc->files) ? array_values($doc->files) : null)),
        set::required(true)
    ),
    formGroup
    (
        setClass('titleBox'),
        set::hidden(!empty($doc) ? false : true),
        set::label($lang->doc->title),
        set::name('title'),
        set::value(!empty($doc->title) ? $doc->title : ''),
        set::required(true),
        on::input('titleChanged')
    ),
    formGroup
    (
        setClass('uploadFormatBox'),
        set::hidden(true),
        set::label($lang->doc->uploadFormat),
        radioList
        (
            set::name('uploadFormat'),
            set::items($lang->doc->uploadFormatList),
            set::value('separateDocs'),
            set::inline(true),
            on::change('toggleDocTitle')
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
                set::items($lang->doc->aclList),
                set::value(!empty($doc->acl) ? $doc->acl : ($objectType == 'mine' ? 'private' : 'open')),
                on::change("toggleAcl('doc')")
            )
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
                $lang->doclib->group,
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
                userPicker(set::label($lang->doclib->user), set::items($users))
            )
        )
    ),
    formHidden('status', 'normal'),
    formHidden('type', $docType),
    formHidden('contentType', 'doc')
);
