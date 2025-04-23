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

$optionMenu = $this->tree->getOptionMenu($libID, 'docTemplate', 0, 'all', 'nodeleted', 'all');
$modules = array();
foreach($optionMenu as $menuID => $menu)
{
    if($menuID == 0) continue;
    $modules[] = array('text' => $menu, 'value' => $menuID, 'keys' => $menu);
}

formPanel
(
    setID('setDocBasicForm'),
    setData('officeTypes', $this->config->doc->officeTypes),
    setData('docType', isset($doc) ? $doc->users : 'undefined'),
    set::title($title),
    set::submitBtnText($lang->save),
    on::change('[name=lib]')->call('loadLibModules', jsRaw("event, 'docTemplate'")),
    set::ajax(array('beforeSubmit' => jsRaw('window.beforeSetDocBasicInfo'))),
    formGroup
    (
       set::label($modalType == 'chapter' ? $lang->doc->chapterName : $lang->docTemplate->title),
       set::name('title'),
       set::required(true),
       set::value(isset($doc) ? $doc->title : '')
    ),
    empty($parentID) ? formGroup
    (
        set::label($lang->docTemplate->scope),
        set::required(true),
        picker(set::name('lib'), set::items($lang->docTemplate->scopes), set::value($libID), set::required(true))
    ) : null,
    empty($parentID) ? formGroup
    (
        set::label($lang->docTemplate->module),
        set::required(true),
        picker(set::name('module'), set::items($modules), set::value($moduleID), set::required(true))
    ) : null,
    ($modalType != 'chapter' || !$isCreate) && !empty($parentID) ? formGroup
    (
        set::label($lang->doc->module),
        picker
        (
            set::name('parent'),
            set::items($chapterAndDocs),
            set::value($parentID ? $parentID : "m_$moduleID"),
            set::required(true)
        ),
    ) : null,
    $modalType != 'chapter' ? formGroup
    (
        set::label($lang->docTemplate->desc),
        textarea(set::name('templateDesc'), set::value($docID ? $doc->templateDesc : ''), set::rows(3))
    ) : null,
    empty($parentID) ? formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            setClass($objectType == 'mine' ? 'pointer-events-none' : ''),
            set::name('acl'),
            set::items($lang->doc->aclListA),
            set::value(isset($doc) ? $doc->acl : 'open'),
            on::change('toggleWhiteList')
        )
    ) : null,
    empty($parentID) ? formGroup
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
    ) : null
);
