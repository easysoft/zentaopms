<?php
declare(strict_types=1);
/**
 * The app view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$libTypes = array();
if($type === 'project')
{
    $libTypes[] = array('type' => 'project',   'name' => $lang->projectCommon,     'icon' => 'project');
    $libTypes[] = array('type' => 'execution', 'name' => $lang->execution->common, 'icon' => 'run');
}

$privs = array();
$privs['create']       = hasPriv('doc', 'create');
$privs['edit']         = hasPriv('doc', 'edit');
$privs['delete']       = hasPriv('doc', 'delete');
$privs['exportDoc']    = $this->config->edition != 'open' && hasPriv('doc', $type . '2export');
$privs['moveDoc']      = hasPriv('doc', 'moveDoc');
$privs['collect']      = hasPriv('doc', 'collect');
$privs['createLib']    = hasPriv('doc', 'createLib');
$privs['editLib']      = hasPriv('doc', 'editLib');
$privs['moveLib']      = hasPriv('doc', 'moveLib');
$privs['deleteLib']    = hasPriv('doc', 'deleteLib');
$privs['sortDocLib']   = hasPriv('doc', 'sortDocLib');
$privs['exportFiles']  = hasPriv('doc', 'exportFiles');
$privs['createSpace']  = hasPriv('doc', 'createSpace');
$privs['deleteSpace']  = hasPriv('doc', 'deleteSpace');
$privs['editSpace']    = hasPriv('doc', 'editLib');
$privs['addModule']    = hasPriv('doc', 'addCatalog');
$privs['deleteModule'] = hasPriv('doc', 'deleteCatalog');
$privs['editModule']   = hasPriv('doc', 'editCatalog');
$privs['sortModule']   = hasPriv('doc', 'sortCatalog');

$sessionStr = session_name() . '=' . session_id();
$fileUrl    = createLink('file', 'download', 'fileID={id}');
$fileUrl   .= strpos($fileUrl, '?') === false ? '?' : '&';
$fileUrl   .= $sessionStr;

/**
 * Setting language data for frontend. Use getLang('xxx') in js/app.ui.js.
 * 设置前端语言数据。 在 js/app.ui.js 中使用 getLang('xxx') 来访问语言数据。
 */
$langData = new stdclass();
$langData->cancel                = $lang->cancel;
$langData->export                = $lang->export;
$langData->filePreview           = $lang->file->preview;
$langData->fileDownload          = $lang->file->download;
$langData->fileDelete            = $lang->file->delete;
$langData->fileRename            = $lang->file->edit;
$langData->fileConfirmDelete     = $lang->file->confirmDelete;
$langData->createSpace           = $lang->doc->createSpace;
$langData->createLib             = $lang->doc->createLib;
$langData->actions               = $lang->doc->libDropdown;
$langData->moveTo                = $lang->doc->moveTo;
$langData->create                = $lang->doc->createAB;
$langData->createDoc             = $lang->doc->create;
$langData->editDoc               = $lang->doc->edit;
$langData->deleteDoc             = $lang->doc->delete;
$langData->uploadDoc             = $lang->doc->uploadDoc;
$langData->createList            = $lang->doc->createList;
$langData->confirmDelete         = $lang->doc->confirmDelete;
$langData->confirmDeleteLib      = $lang->doc->confirmDeleteLib;
$langData->confirmDeleteSpace    = $lang->doc->confirmDeleteSpace;
$langData->confirmDeleteModule   = $lang->doc->confirmDeleteModule;
$langData->collect               = $lang->doc->collect;
$langData->edit                  = $lang->doc->edit;
$langData->delete                = $lang->doc->delete;
$langData->cancelCollection      = $lang->doc->cancelCollection;
$langData->moveDoc               = $lang->doc->moveDocAction;
$langData->moveTo                = $lang->doc->moveTo;
$langData->moveLib               = $lang->doc->moveLibAction;
$langData->moduleName            = $lang->doc->catalogName;
$langData->saveDraft             = $lang->doc->saveDraft;
$langData->release               = $lang->doc->release;

zui::docApp
(
    set::_class('shadow rounded ring canvas'),
    set::_style(array('height' => 'calc(100vh - 72px)')),
    set::_id('docApp'),
    set::spaceType($type),
    set::spaceID($spaceID),
    set::libID($libID),
    set::libTypes($libTypes),
    set::moduleID($moduleID),
    set::docID($docID),
    set::mode($mode),
    set::fetcher(createLink('doc', 'ajaxGetSpaceData', 'type={spaceType}&spaceID={spaceID}&picks={picks}')),
    set::docFetcher(createLink('doc', 'ajaxGetDoc', 'docID={docID}&version={version}')),
    set::filesFetcher(createLink('doc', 'ajaxGetFiles', 'type={objectType}&objectID={objectID}')),
    set::width('100%'),
    set::height('100%'),
    set::userMap($users),
    set::currentUser($this->app->user->account),
    set::privs($privs),
    set::fileUrl($fileUrl),
    set::lang($langData),
    set::uploadUrl(createLink('file', 'ajaxUpload', 'uid={uid}&objectType={objectType}&objectID={objectID}&extra={extra}&field={field}&api={api}&onlyImage=0')),
    set::downloadUrl(createLink('file', 'ajaxQuery', 'fileID={id}&objectType={objectType}&objectID={objectID}&title={title}&extra={extra}&stream=0')),
    set::sessionStr(session_name() . '=' . session_id()),
    set('$options', jsRaw('window.setDocAppOptions'))
);
