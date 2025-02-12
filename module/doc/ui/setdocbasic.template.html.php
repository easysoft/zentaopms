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
    set::submitBtnText($isDraft ? $lang->doc->saveDraft : (empty($docID) ? $lang->doc->release : $lang->save)),
    on::change('[name=lib]')->call('loadLibModules', jsRaw("event, 'docTemplate'")),
    set::ajax(array('beforeSubmit' => jsRaw('window.beforeSetDocBasicInfo'))),
    formGroup
    (
        set::label($lang->docTemplate->scope),
        set::required(true),
        picker(set::name('lib'), set::items($lang->docTemplate->scopes), set::value(isset($libs[$libID]) ? $libID : ''), set::required(true))
    ),
    formGroup
    (
        set::label($lang->docTemplate->module),
        set::required(true),
        picker(set::name('module'), set::items($modules), set::value($moduleID), set::required(true))
    ),
    formGroup
    (
        set::label($lang->docTemplate->desc),
        textarea(set::name('desc'), set::value($docID ? $doc->templateDesc : ''), set::rows(3))
    )
);
