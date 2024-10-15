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

/* Define js vars. */
jsVar('type',        $type);
jsVar('spaceID',     $spaceID);
jsVar('libID',       $libID);
jsVar('moduleID',    $moduleID);
jsVar('currentUser', $this->app->user->account);
jsVar('officeTypes', $this->config->doc->officeTypes);

/*
 * 定义库类型名称和图标。
 * Define the lib types and icons.
 */
$libTypes = array();
if($type === 'project')
{
    $libTypes[] = array('type' => 'project',   'name' => $lang->projectCommon,     'icon' => 'project');
    $libTypes[] = array('type' => 'execution', 'name' => $lang->execution->common, 'icon' => 'run');
}

/**
 * 定义文档界面上的权限。
 * Define the privs of doc app.
 */
d($type);
$hasCustomSpace = $type == 'mine' || $type == 'custom';
$privs = array();
$privs['create']       = hasPriv('doc', 'create');
$privs['edit']         = hasPriv('doc', 'edit');
$privs['delete']       = hasPriv('doc', 'delete');
$privs['effort']       = hasPriv('effort', 'createForObject');
$privs['exportDoc']    = $this->config->edition != 'open' && hasPriv('doc', $type . '2export');
$privs['moveDoc']      = hasPriv('doc', 'moveDoc');
$privs['collect']      = hasPriv('doc', 'collect');
$privs['createLib']    = hasPriv('doc', 'createLib');
$privs['editLib']      = hasPriv('doc', 'editLib');
$privs['moveLib']      = hasPriv('doc', 'moveLib');
$privs['deleteLib']    = hasPriv('doc', 'deleteLib');
$privs['sortDocLib']   = hasPriv('doc', 'sortDocLib');
$privs['exportFiles']  = hasPriv('doc', 'exportFiles');
$privs['createSpace']  = $hasCustomSpace && hasPriv('doc', 'createLib');
$privs['deleteSpace']  = $hasCustomSpace && hasPriv('doc', 'deleteLib');
$privs['editSpace']    = $hasCustomSpace && hasPriv('doc', 'editLib');
$privs['addModule']    = hasPriv('doc', 'addCatalog');
$privs['deleteModule'] = hasPriv('doc', 'deleteCatalog');
$privs['editModule']   = hasPriv('doc', 'editCatalog');
$privs['sortModule']   = hasPriv('doc', 'sortCatalog');
$privs['sortDoclib']   = hasPriv('doc', 'sortDoclib');
$privs['sortDoc']      = hasPriv('doc', 'sortDoc');

/**
 * 定义文档界面上的文件下载链接。
 * Define the file download link for doc app.
 */
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
$langData->settings              = $lang->settings;
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
$langData->effort                = $lang->doc->effort;
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
$langData->batchMove             = $lang->doc->batchMove;
$langData->filterTypes           = $lang->doc->filterTypes;
$langData->fileFilterTypes       = $lang->doc->fileFilterTypes;
$langData->sortCatalog           = $lang->doc->sortCatalog;
$langData->sortDoclib            = $lang->doc->sortDoclib;
$langData->sortDoc               = $lang->doc->sortDoc;

/**
 * 通过语言项定义文档表格列显示名称。
 * Define the table columns for doc app.
 */
$langData->tableCols = array();
$langData->tableCols['id']         = $lang->doc->id;
$langData->tableCols['title']      = $lang->doc->title;
$langData->tableCols['collects']   = $lang->doc->docCollects;
$langData->tableCols['views']      = $lang->doc->views;
$langData->tableCols['addedBy']    = $lang->doc->addedBy;
$langData->tableCols['addedDate']  = $lang->doc->addedDate;
$langData->tableCols['editedBy']   = $lang->doc->editedBy;
$langData->tableCols['editedDate'] = $lang->doc->editedDate;
$langData->tableCols['actions']    = $lang->actions;

/**
 * 定义文档应用接口链接。
 * Define the fetcher links for doc app.
 */
$fetcher             = createLink('doc', 'ajaxGetSpaceData', 'type={spaceType}&spaceID={spaceID}&picks={picks}');
$docFetcher          = createLink('doc', 'ajaxGetDoc', 'docID={docID}&version={version}');
$filesFetcher        = createLink('doc', 'ajaxGetFiles', 'type={objectType}&objectID={objectID}');
$libSummariesFetcher = createLink('doc', 'ajaxGetLibSummaries', 'spaceType={spaceType}&spaceList={spaceList}');
$uploadUrl           = createLink('file', 'ajaxUpload', 'uid={uid}&objectType={objectType}&objectID={objectID}&extra={extra}&field={field}&api={api}&onlyImage=0');
$downloadUrl         = createLink('file', 'ajaxQuery', 'fileID={id}&objectType={objectType}&objectID={objectID}&title={title}&extra={extra}&stream=0');

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
    set::filterType($filterType),
    set::search($search),
    set::orderBy($orderBy),
    set::params($params),
    set::homeName($lang->doc->spaceList[$type]),
    set::pager(array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'pageID' => $pageID)),
    set::fetcher($fetcher),
    set::docFetcher($docFetcher),
    set::filesFetcher($filesFetcher),
    set::libSummariesFetcher($libSummariesFetcher),
    set::width('100%'),
    set::height('100%'),
    set::userMap($users),
    set::currentUser($this->app->user->account),
    set::privs($privs),
    set::fileUrl($fileUrl),
    set::langData($langData),
    set::uploadUrl($uploadUrl),
    set::downloadUrl($downloadUrl),
    set::sessionStr(session_name() . '=' . session_id()),
    set('$options', jsRaw('window.setDocAppOptions'))
);
