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
    on::change('[name=lib]')->call('loadScopeTypes', jsRaw("event")),
    on::change('[name=isDeliverable]', 'window.changeIsDeliverable'),
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
        picker(set::name('lib'), set::items($scopeItems), set::value($libID), set::required(true))
    ) : null,
    empty($parentID) ? formGroup
    (
        set::label($lang->docTemplate->module),
        set::required(true),
        picker(set::name('module'), set::items($modules), set::value($moduleID), set::required(true))
    ) : null,
    ($modalType != 'chapter' || !$isCreate) && !empty($parentID) ? formGroup
    (
        set::label($lang->docTemplate->parent),
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
    $modalType != 'chapter' && in_array($config->edition, array('max', 'ipd')) ? formGroup
    (
        set::label($lang->docTemplate->deliverable),
        radioList
        (
            set::inline(true),
            set::name('isDeliverable'),
            set::items($lang->docTemplate->deliverableList),
            set::value($docID ? $doc->isDeliverable : '0')
        )
    ) : null,
    empty($parentID) ? formGroup
    (
        set::label($lang->doclib->control),
        radioList
        (
            setClass($objectType == 'mine' ? 'pointer-events-none' : ''),
            set::name('acl'),
            set::disabled($docID && $doc->isDeliverable ? true : false),
            set::items($lang->doc->aclListA),
            set::value(isset($doc) ? $doc->acl : 'open')
        ),
        input(setClass('hidden'), set::name('acl'), set::id('acl'), set::disabled($docID && $doc->isDeliverable ? false : true), set::value('open'))
    ) : null
);
