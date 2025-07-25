<?php
declare(strict_types=1);
/**
 * The browse template list file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Dai Tingting<daitingting@xirangit.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$spaceID = 1;
$data    = array('spaceID' => $spaceID, 'docs' => array_values($templateList));
$data['spaces'][] = array('name' => $lang->doc->template, 'id' => $spaceID);
foreach($scopes as $scope) $data['libs'][] = array('id' => $scope->id, 'name' => $scope->name, 'space' => $spaceID);
$data['modules'] = $this->doc->getTemplateModules();

$privs = array();
$privs['create']       = hasPriv('doc', 'createTemplate');
$privs['edit']         = hasPriv('doc', 'createTemplate');
$privs['delete']       = hasPriv('doc', 'deleteTemplate');
$privs['view']         = hasPriv('doc', 'viewTemplate');
$privs['addModule']    = hasPriv('doc', 'addTemplateType');
$privs['editModule']   = hasPriv('doc', 'editTemplateType');
$privs['deleteModule'] = hasPriv('doc', 'deleteTemplateType');
$privs['moveDoc']      = hasPriv('doc', 'moveTemplate');
$privs['sortDoc']      = hasPriv('doc', 'sortTemplate');
$privs['collect']      = 'no';
$privs['restoreDoc']   = false;

$filterTypes = $lang->docTemplate->filterTypes;
if(!hasPriv('doc', 'editTemplate')) $filterTypes = array_values(array_filter($filterTypes, function($item){ return $item[0] != 'draft'; }));

$tableCols = array();
$tableCols['id']         = $lang->docTemplate->id;
$tableCols['title']      = $lang->docTemplate->title;
$tableCols['frequency']  = $lang->docTemplate->frequency;
$tableCols['type']       = $lang->docTemplate->type;
$tableCols['addedBy']    = $lang->docTemplate->addedBy;
$tableCols['addedDate']  = $lang->docTemplate->addedDate;
$tableCols['editedBy']   = $lang->docTemplate->editedBy;
$tableCols['editedDate'] = $lang->docTemplate->editedDate;
$tableCols['views']      = $lang->docTemplate->views;
$tableCols['desc']       = $lang->docTemplate->desc;
$tableCols['actions']    = $lang->actions;

$langData = array();
$langData['filterTypes']                 = $filterTypes;
$langData['tableCols']                   = $tableCols;
$langData['createDoc']                   = $lang->docTemplate->create;
$langData['edit']                        = $lang->docTemplate->edit;
$langData['editDoc']                     = $lang->docTemplate->edit;
$langData['delete']                      = $lang->docTemplate->delete;
$langData['deleteDoc']                   = $lang->docTemplate->delete;
$langData['createTemplate']              = $lang->docTemplate->create;
$langData['editTemplate']                = $lang->docTemplate->edit;
$langData['deleteTemplate']              = $lang->docTemplate->delete;
$langData['confirmDelete']               = $lang->docTemplate->confirmDelete;
$langData['addModule']                   = $lang->docTemplate->addModule;
$langData['addSubModule']                = $lang->docTemplate->addSubModule;
$langData['editModule']                  = $lang->docTemplate->editModule;
$langData['deleteModule']                = $lang->docTemplate->deleteModule;
$langData['noModules']                   = $lang->docTemplate->noModules;
$langData['docTitlePlaceholder']         = $lang->docTemplate->docTitlePlaceholder;
$langData['docTitleRequired']            = $lang->docTemplate->docTitleRequired;
$langData['noDocs']                      = $lang->docTemplate->noTemplate;
$langData['convertToNewDocConfirm']      = $lang->docTemplate->convertToNewDocConfirm;
$langData['oldDocEditingTip']            = $lang->docTemplate->oldDocEditingTip;
$langData['leaveEditingConfirm']         = $lang->docTemplate->leaveEditingConfirm;
$langData['searchLibPlaceholder']        = $lang->docTemplate->searchScopePlaceholder;
$langData['searchModulePlaceholder']     = $lang->docTemplate->searchTypePlaceholder;
$langData['moveDoc']                     = $lang->docTemplate->moveDocTemplate;
$langData['createTypeFirst']             = $lang->docTemplate->createTypeFirst;
$langData['addSubDocTemplate']           = $lang->docTemplate->addSubDocTemplate;
$langData['confirmDeleteChapterWithSub'] = $lang->docTemplate->confirmDeleteChapterWithSub;
$langData['confirmDeleteWithSub']        = $lang->docTemplate->confirmDeleteTemplateWithSub;
$langData['needEditable']                = $lang->docTemplate->needEditable;

$langData['actions']['addModule']     = $lang->docTemplate->addTemplateType;
$langData['actions']['addSameModule'] = $lang->docTemplate->addSameModule;
$langData['actions']['addSubModule']  = $lang->docTemplate->addSubModule;
$langData['actions']['editModule']    = $lang->docTemplate->editModule;
$langData['actions']['deleteModule']  = $lang->docTemplate->deleteModule;

$viewModeUrl = createLink('doc', 'browsetemplate', 'libID={libID}&type={filterType}&docID={docID}&orderBy={orderBy}&recPerPage={recPerPage}&pageID={page}&mode={mode}');
$homeUrl     = createLink('doc', 'browsetemplate');

$config->doc->zentaoList = $config->docTemplate->zentaoList;
$lang->doc->zentaoData   = $lang->docTemplate->zentaoData;

docApp
(
    set::data($data),
    set::spaceID($spaceID),
    set::libID($libID),
    set::docID((int)$docID),
    set::docIcon('file-archive'),
    set::noSpace('hidden'),
    set::noModule(),
    set::moduleIcon('fields'),
    set::homeName(array('hint' => $lang->doc->template, 'url' => $homeUrl, 'command' => '')),
    set::mode($mode),
    set::fetcher(createLink('doc', 'ajaxGetSpaceData', 'type=template&spaceID=1&picks={picks}')),
    set::historyFetcher(createLink('action', 'ajaxGetList', 'objectType=docTemplate&objectID={objectID}')),
    set::historyPanel(array('commentUrl' => createLink('action', 'comment', "objectType=doctemplate&objectID={$docID}"), 'objectType' => 'doctemplate')),
    set::privs($privs),
    set::userMap($users),
    set::spaceIcon(false),
    set::langData($langData),
    set::viewModeUrl($viewModeUrl),
    set::pager(array('recTotal' => count($templateList), 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set('$options', jsRaw('window.setDocAppOptions')),
    set::showDocOutline(false),
    set::hasModules($hasModules)
);

render();
