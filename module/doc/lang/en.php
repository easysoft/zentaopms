<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->doclib = new stdclass();
$lang->doclib->name         = 'Library Name';
$lang->doclib->control      = 'Access Control';
$lang->doclib->group        = 'Group';
$lang->doclib->user         = 'User';
$lang->doclib->files        = 'Attachments';
$lang->doclib->all          = 'All Doc Libraries';
$lang->doclib->select       = 'Select Library';
$lang->doclib->execution    = $lang->executionCommon . ' Library';
$lang->doclib->product      = $lang->productCommon . ' Library';
$lang->doclib->apiLibName   = 'Library Name';
$lang->doclib->defaultSpace = 'Default Space';
$lang->doclib->defaultMyLib = 'My Library';
$lang->doclib->spaceName    = 'Space Name';
$lang->doclib->createSpace  = 'Add New';
$lang->doclib->editSpace    = 'Edit Space';
$lang->doclib->privateACL   = "Private (Accessible for creator and while list user with %s permission.)";
$lang->doclib->defaultOrder = 'Default Docs Order';
$lang->doclib->migratedWiki = 'Migrated Wiki';

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "If Execution is empty, the created library will be a {$lang->projectCommon} library.";

$lang->doclib->type['wiki'] = 'Doc Lib';
$lang->doclib->type['api']  = 'API Lib';

$lang->doclib->aclListA = array();
$lang->doclib->aclListA['default'] = 'Default';
$lang->doclib->aclListA['custom']  = 'Custom';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Custom';
$lang->doclib->aclListB['private'] = 'Private';

$lang->doclib->mySpaceAclList['private'] = "Private (Accessible for creator only.)";

$lang->doclib->aclList = array();
$lang->doclib->aclList['open']    = "Public (Accessible for all users with the document module view permission.)";
$lang->doclib->aclList['default'] = "Default (Accessible for users with %s permission.)";
$lang->doclib->aclList['private'] = "Private (Accessible for creator and while list users only.)";

$lang->doclib->idOrder = array();
$lang->doclib->idOrder['id_asc']  = 'ID Ascending';
$lang->doclib->idOrder['id_desc'] = 'ID Descending' ;

$lang->doclib->create['product']   = 'Create ' . $lang->productCommon . ' Library';
$lang->doclib->create['execution'] = 'Create ' . $lang->executionCommon . ' Library';
$lang->doclib->create['custom']    = 'Create Custom Library';

$lang->doclib->main['product']   = $lang->productCommon . ' Primary Library';
$lang->doclib->main['project']   = "{$lang->projectCommon} Primary Library";
$lang->doclib->main['execution'] = $lang->executionCommon . ' Primary Library';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Custom';

$lang->doclib->nameList['custom'] = 'Custom Lib Name';

$lang->doclib->apiNameUnique = array();
$lang->doclib->apiNameUnique['product'] = 'In API library under the same ' . $lang->productCommon . '.';
$lang->doclib->apiNameUnique['project'] = 'In API library under the same ' . $lang->projectCommon . '.';
$lang->doclib->apiNameUnique['nolink']  = 'In independent API library';

$lang->docTemplate = new stdclass();
$lang->docTemplate->id                           = 'ID';
$lang->docTemplate->title                        = 'Template Title';
$lang->docTemplate->frequency                    = 'Frequency';
$lang->docTemplate->type                         = 'Type';
$lang->docTemplate->addedBy                      = 'Creator';
$lang->docTemplate->addedDate                    = 'Created on';
$lang->docTemplate->editedBy                     = 'Edited by';
$lang->docTemplate->editedDate                   = 'Edited on';
$lang->docTemplate->views                        = 'Views';
$lang->docTemplate->confirmDelete                = 'Are you sure you want to delete this document template?';
$lang->docTemplate->scope                        = 'Scope';
$lang->docTemplate->lib                          = $lang->docTemplate->scope;
$lang->docTemplate->module                       = 'Template Category';
$lang->docTemplate->desc                         = 'Description';
$lang->docTemplate->deliverable                  = 'Is Deliverable';
$lang->docTemplate->parentModule                 = 'Parent Category';
$lang->docTemplate->typeName                     = 'Category Name';
$lang->docTemplate->parent                       = 'Hierarchy';
$lang->docTemplate->addTemplateType              = 'Add template category';
$lang->docTemplate->editTemplateType             = 'Edit template category';
$lang->docTemplate->docTitlePlaceholder          = 'Please enter a document template title.';
$lang->docTemplate->docTitleRequired             = 'Document template title cannot be empty.';
$lang->docTemplate->errorDeleteType              = 'Cannot delete: This category contains document templates.';
$lang->docTemplate->convertToNewDocConfirm       = 'The new document format uses a modern block-based editor for an enhanced experience. Are you sure you want to convert this template to the new format? Once saved as a draft or published, you cannot revert to the legacy editor.';
$lang->docTemplate->oldDocEditingTip             = 'This template was created with the legacy editor and is now being edited with the new editor. Saving will convert it to the new format.';
$lang->docTemplate->leaveEditingConfirm          = 'You are currently editing a document template. Are you sure you want to leave?';
$lang->docTemplate->searchScopePlaceholder       = 'Search Scope';
$lang->docTemplate->searchTypePlaceholder        = 'Search Categories';
$lang->docTemplate->moveDocTemplate              = 'Move Document Template';
$lang->docTemplate->moveSubTemplate              = 'Move Sub Template';
$lang->docTemplate->createTypeFirst              = 'Please create a document template category first.';
$lang->docTemplate->editedList                   = 'Template editor';
$lang->docTemplate->content                      = 'Template Content';
$lang->docTemplate->templateDesc                 = 'Template Description';
$lang->docTemplate->status                       = 'Template Status';
$lang->docTemplate->emptyTip                     = 'No system data matches the current parameters and filters.';
$lang->docTemplate->emptyDataTip                 = 'No system data matches the current filter criteria.';
$lang->docTemplate->previewTip                   = 'Once parameters are configured, this block will display data based on the filter settings.';
$lang->docTemplate->confirmDeleteChapterWithSub  = "Are you sure you want to delete this chapter? All content nested under it will be hidden.";
$lang->docTemplate->confirmDeleteTemplateWithSub = "Are you sure you want to delete this document template? All content nested under it will be hidden.";
$lang->docTemplate->scopeHasTemplateTips         = 'This scope contains document templates. Please remove them before deleting the scope.';
$lang->docTemplate->scopeHasModuleTips           = 'This scope contains template categories. Please remove them before deleting the scope.';
$lang->docTemplate->needEditable                 = 'You do not have permission to edit this document template.';

$lang->docTemplate->create = 'More';
$lang->docTemplate->edit   = 'Scope';
$lang->docTemplate->delete = 'Delete Document Template';

$lang->docTemplate->more       = 'No description.';
$lang->docTemplate->scopeLabel = 'of';
$lang->docTemplate->noTemplate = 'Expired';
$lang->docTemplate->noDesc     = 'Create Template';
$lang->docTemplate->of         = 'Edit Document Template';
$lang->docTemplate->overdue    = 'Delete Document Template';

$lang->docTemplate->addModule         = 'Add Category';
$lang->docTemplate->addSameModule     = 'Add Sibling category';
$lang->docTemplate->addSubModule      = 'Add Sub-category';
$lang->docTemplate->editModule        = 'Edit Category';
$lang->docTemplate->deleteModule      = 'Delete Category';
$lang->docTemplate->noModules         = 'No document template categories.';
$lang->docTemplate->addSubDocTemplate = 'Add Sub-template';

$lang->docTemplate->filterTypes = array();
$lang->docTemplate->filterTypes[] = array('all', 'All');
$lang->docTemplate->filterTypes[] = array('draft', 'Draft');
$lang->docTemplate->filterTypes[] = array('released', 'Released');
$lang->docTemplate->filterTypes[] = array('createdByMe', 'Creator Me');

$lang->docTemplate->deliverableList['1'] = 'Yes';
$lang->docTemplate->deliverableList['0'] = 'No';

/* Fields. */
$lang->doc->common       = 'Document';
$lang->doc->id           = 'ID';
$lang->doc->product      = $lang->productCommon;
$lang->doc->project      = $lang->projectCommon;
$lang->doc->execution    = $lang->execution->common;
$lang->doc->plan         = $lang->productplan->shortCommon;
$lang->doc->lib          = 'Library';
$lang->doc->module       = 'Parent';
$lang->doc->libAndModule = 'Library&Catalog';
$lang->doc->object       = 'Object';
$lang->doc->title        = 'Title';
$lang->doc->digest       = 'Summary';
$lang->doc->comment      = 'Comment';
$lang->doc->type         = 'Type';
$lang->doc->content      = 'Text';
$lang->doc->keywords     = 'Keywords';
$lang->doc->status       = 'Status';
$lang->doc->url          = 'URL';
$lang->doc->files        = 'File';
$lang->doc->addedBy      = 'Creator';
$lang->doc->addedDate    = 'Created Date';
$lang->doc->editedBy     = 'Edited by';
$lang->doc->editedDate   = 'Last Updated';
$lang->doc->editingDate  = 'Current Editor & Time';
$lang->doc->lastEditedBy = 'Last Editor';
$lang->doc->updateInfo   = 'Update info';
$lang->doc->version      = 'Version Number5';
$lang->doc->basicInfo    = 'Basic Info';
$lang->doc->deleted      = 'Deleted';
$lang->doc->fileObject   = 'Belongs to';
$lang->doc->whiteList    = 'Whitelist';
$lang->doc->readonly     = 'Read Only';
$lang->doc->editable     = 'Editable';
$lang->doc->contentType  = 'Document Format';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = 'File Name';
$lang->doc->filePath     = 'File Path';
$lang->doc->extension    = 'Type';
$lang->doc->size         = 'Files Size';
$lang->doc->source       = 'Source';
$lang->doc->download     = 'Download';
$lang->doc->acl          = 'Permission';
$lang->doc->fileName     = 'File';
$lang->doc->groups       = 'Group';
$lang->doc->users        = 'User';
$lang->doc->item         = ' Item';
$lang->doc->num          = 'Document Count';
$lang->doc->searchResult = 'Search Result';
$lang->doc->mailto       = 'Mail to';
$lang->doc->noModule     = 'No directories or documents found in this library. Please manage directories or create a document.';
$lang->doc->noChapter    = 'No chapters or articles found in this manual. Please manage the manual.';
$lang->doc->views        = 'Views';
$lang->doc->draft        = 'Draft';
$lang->doc->collector    = 'Favorited by';
$lang->doc->main         = 'Main Document Library';
$lang->doc->order        = 'Sort';
$lang->doc->doc          = 'Document';
$lang->doc->updateOrder  = 'Update Order';
$lang->doc->update       = 'Update';
$lang->doc->nextStep     = 'Next';
$lang->doc->closed       = 'Closed';
$lang->doc->saveDraft    = 'Save as Draft';
$lang->doc->template     = 'Template';
$lang->doc->position     = 'Location';
$lang->doc->person       = 'Personal';
$lang->doc->team         = 'Team';
$lang->doc->manage       = 'Document Management';
$lang->doc->release      = 'Release';
$lang->doc->story        = 'Story';
$lang->doc->convertdoc   = 'Convert to Doc';
$lang->doc->needEditable = 'You don\'t have permission to edit this document.';
$lang->doc->needReadable = 'You don\'t have permission to view this document.';
$lang->doc->groupLabel   = 'Group';
$lang->doc->userLabel    = 'User';

$lang->doc->moduleDoc     = 'View by Module';
$lang->doc->searchDoc     = 'Search';
$lang->doc->fast          = 'Quick Access';
$lang->doc->allDoc        = 'All Documents';
$lang->doc->allVersion    = 'All Versions';
$lang->doc->openedByMe    = 'Creator Me';
$lang->doc->editedByMe    = 'Edited by Me';
$lang->doc->orderByOpen   = 'Recently Added';
$lang->doc->orderByEdit   = 'Recently Updated';
$lang->doc->orderByVisit  = 'Recently Viewed';
$lang->doc->todayEdited   = 'Updated Today';
$lang->doc->pastEdited    = 'Older Updates';
$lang->doc->myDoc         = 'My Documents';
$lang->doc->myView        = 'Recently Viewed';
$lang->doc->myCollection  = 'Favorited by Me';
$lang->doc->myCreation    = 'Creator Me';
$lang->doc->myEdited      = 'Edited by Me';
$lang->doc->myLib         = 'My Library';
$lang->doc->tableContents = 'Catalog';
$lang->doc->addCatalog    = 'Add Catalog';
$lang->doc->editCatalog   = 'Edit Catalog';
$lang->doc->deleteCatalog = 'Delete Catalog';
$lang->doc->sortCatalog   = 'Sort Catalogs';
$lang->doc->sortDoclib    = 'Sort Libraries';
$lang->doc->sortDoc       = 'Sort Docs';
$lang->doc->docStatistic  = 'Docs Summary';
$lang->doc->docCreated    = 'Created';
$lang->doc->docEdited     = 'Edited';
$lang->doc->docViews      = 'Views';
$lang->doc->docCollects   = 'Favorites';
$lang->doc->todayUpdated  = "Updated Today";
$lang->doc->daysUpdated   = 'Updated %s day(s) ago';
$lang->doc->monthsUpdated = 'Updated %s month(s) ago';
$lang->doc->yearsUpdated  = 'Updated %s year(s) ago';
$lang->doc->viewCount     = '%s View(s)';
$lang->doc->collectCount  = '%s Favorite(s)';

/* Methods list */
$lang->doc->index            = 'Dashboard';
$lang->doc->createAB         = 'Create';
$lang->doc->create           = 'Create Document';
$lang->doc->createOrUpload   = 'Create/Import Document';
$lang->doc->edit             = 'Edit Document';
$lang->doc->effort           = 'Effort';
$lang->doc->delete           = 'Delete Document';
$lang->doc->createBook       = 'Create Manual';
$lang->doc->browse           = 'Document List';
$lang->doc->view             = 'Document Details';
$lang->doc->diff             = 'Diff';
$lang->doc->confirm          = 'Confirm';
$lang->doc->cancelDiff       = 'Cancel diff';
$lang->doc->diffAction       = 'Diff Docs';
$lang->doc->sort             = 'Sort Docs';
$lang->doc->manageType       = 'Manage Directories';
$lang->doc->editType         = 'Edit Directory';
$lang->doc->editChildType    = 'Manage Sub-directories';
$lang->doc->deleteType       = 'Delete Directory';
$lang->doc->addType          = 'Add Directory';
$lang->doc->childType        = 'Sub-directory';
$lang->doc->catalogName      = 'Directory Name';
$lang->doc->collect          = 'Favorite';
$lang->doc->collectSuccess   = 'Added to favorites!';
$lang->doc->cancelCollection = 'Unfavorite';
$lang->doc->deleteFile       = 'Delete File';
$lang->doc->menuTitle        = 'Directory';
$lang->doc->api              = 'API';
$lang->doc->displaySetting   = 'Display Settings';
$lang->doc->collectAction    = 'Favorite Document';

$lang->doc->libName            = 'Library Name';
$lang->doc->libType            = 'Library Type';
$lang->doc->custom             = 'Custom Document Library';
$lang->doc->customAB           = 'Custom Library';
$lang->doc->createLib          = 'Create Library';
$lang->doc->createLibAction    = 'Create Library';
$lang->doc->createSpace        = 'Create Space';
$lang->doc->allLibs            = 'Library List';
$lang->doc->objectLibs         = "Docs Details";
$lang->doc->showFiles          = 'Attachments';
$lang->doc->editLib            = 'Edit Library';
$lang->doc->editSpaceAction    = 'Edit Space';
$lang->doc->editLibAction      = 'Edit Library';
$lang->doc->deleteSpaceAction  = 'Delete Space';
$lang->doc->deleteLibAction    = 'Delete Library';
$lang->doc->moveLibAction      = 'Move Library';
$lang->doc->moveDocAction      = 'Move Document';
$lang->doc->batchMove          = 'Batch Move';
$lang->doc->batchMoveDocAction = 'Batch Move Docs';
$lang->doc->fixedMenu          = 'Pin to Menu';
$lang->doc->removeMenu         = 'Unpin from Menu';
$lang->doc->search             = 'Search';
$lang->doc->allCollections     = 'View All Favorites';
$lang->doc->keywordsTips       = 'Separate multiple keywords with commas.';
$lang->doc->sortLibs           = 'Sort Libs';
$lang->doc->titlePlaceholder   = 'Enter title here.';
$lang->doc->confirm            = 'Confirm';
$lang->doc->docSummary         = '<strong>%s</strong> docs on this page.';
$lang->doc->docCheckedSummary  = '<strong>%s</strong> docs selected.';
$lang->doc->showDoc            = 'Show Documents';
$lang->doc->uploadFile         = 'Upload File';
$lang->doc->uploadDoc          = 'Import';
$lang->doc->uploadFormat       = 'Upload Format';
$lang->doc->editedList         = 'Document Editor';
$lang->doc->moveTo             = 'Move to';
$lang->doc->notSupportExport   = 'This document cannot be exported.';
$lang->doc->downloadTemplate   = 'Download Template';
$lang->doc->addFile            = 'Submit File';
$lang->doc->frozenTips         = 'After the doc are baselined, %s is not allowed.';

$lang->doc->preview         = 'Preview';
$lang->doc->insertTitle     = 'Insert %s list';
$lang->doc->previewTip      = 'Displayed data can be modified via filter settings. Inserted data is a static snapshot.';
$lang->doc->insertTip       = 'Please select at least one item after previewing.';
$lang->doc->insertText      = 'Insert';
$lang->doc->searchCondition = 'Search Filters';
$lang->doc->list            = ' List';
$lang->doc->detail          = 'Details';
$lang->doc->zentaoData      = 'Zentao Data';
$lang->doc->emptyError      = 'Cannot be empty.';
$lang->doc->caselib         = 'Test Case Library';
$lang->doc->customSearch    = 'Custom Search';

$lang->doc->addChapter     = 'Add chapter';
$lang->doc->editChapter    = 'Edit chapter';
$lang->doc->sortChapter    = 'Sort Chapters';
$lang->doc->deleteChapter  = 'Delete chapter';
$lang->doc->addSubChapter  = 'Add Sub-chapter';
$lang->doc->addSameChapter = 'Add Sibling Chapter';
$lang->doc->addSubDoc      = 'Add Child Document';
$lang->doc->chapterName    = 'Chapter Name';

$lang->doc->tips = new stdclass();
$lang->doc->tips->noProduct   = 'No products found. Please create one first.';
$lang->doc->tips->noProject   = 'No projects found. Please create one first.';
$lang->doc->tips->noExecution = 'No executions found. Please create one first.';
$lang->doc->tips->noCaselib   = 'No test case libraries found. Please create one first.';

$lang->doc->zentaoList = array();
$lang->doc->zentaoList['story']          = $lang->SRCommon;
$lang->doc->zentaoList['productStory']   = $lang->productCommon . ' ' . $lang->SRCommon;
$lang->doc->zentaoList['projectStory']   = $lang->projectCommon . ' ' . $lang->SRCommon;
$lang->doc->zentaoList['executionStory'] = $lang->execution->common . ' ' . $lang->SRCommon;
$lang->doc->zentaoList['planStory']      = $lang->productplan->shortCommon . ' ' . $lang->SRCommon;

$lang->doc->zentaoList['case']        = $lang->testcase->common;
$lang->doc->zentaoList['productCase'] = $lang->productCommon . ' ' . $lang->testcase->common;
$lang->doc->zentaoList['projectCase'] = $lang->projectCommon . ' ' . $lang->testcase->common;
$lang->doc->zentaoList['caselib']     = 'Test Case Library';

$lang->doc->zentaoList['task']       = $lang->task->common;
$lang->doc->zentaoList['bug']        = $lang->bug->common;
$lang->doc->zentaoList['projectBug'] = $lang->projectCommon . ' ' . $lang->bug->common;
$lang->doc->zentaoList['productBug'] = 'Product Bug';
$lang->doc->zentaoList['planBug']    = 'Plan Bug';

$lang->doc->zentaoList['more']               = 'More';
$lang->doc->zentaoList['productPlan']        = $lang->productCommon . ' Plan';
$lang->doc->zentaoList['productPlanContent'] = $lang->productCommon . ' Plan Details';
$lang->doc->zentaoList['productRelease']     = $lang->productCommon . ' ' . $lang->release->common;
$lang->doc->zentaoList['projectRelease']     = $lang->projectCommon . ' ' . $lang->release->common;
$lang->doc->zentaoList['ER']                 = $lang->defaultERName;
$lang->doc->zentaoList['UR']                 = $lang->URCommon;
$lang->doc->zentaoList['feedback']           = 'Feedback';
$lang->doc->zentaoList['ticket']             = 'Ticket';
$lang->doc->zentaoList['gantt']              = 'Gantt Chart';

$lang->doc->zentaoList['HLDS'] = 'High-Level Design';
$lang->doc->zentaoList['DDS']  = 'Detailed Design';
$lang->doc->zentaoList['DBDS'] = 'Database Design';
$lang->doc->zentaoList['ADS']  = 'API Design';

$lang->doc->zentaoAction = array();
$lang->doc->zentaoAction['set']       = 'Settings';
$lang->doc->zentaoAction['delete']    = 'Delete';
$lang->doc->zentaoAction['setParams'] = 'Set Parameters';

$lang->doc->uploadFormatList = array();
$lang->doc->uploadFormatList['separateDocs'] = 'Save each file as a separate document.';
$lang->doc->uploadFormatList['combinedDocs'] = 'Save all files as one document.';

$lang->doc->fileType = new stdclass();
$lang->doc->fileType->stepResult = 'Test Result';

global $config;
/* Query condition list. */
$lang->doc->allProduct    = 'All' . $lang->productCommon . 's';
$lang->doc->allExecutions = 'All' . $lang->execution->common . 's';
$lang->doc->allProjects   = 'All' . $lang->projectCommon . 's';

$lang->doc->libTypeList['product']   = $lang->productCommon . ' Docs Library';
$lang->doc->libTypeList['project']   = "{$lang->projectCommon} Docs Library";
$lang->doc->libTypeList['execution'] = $lang->execution->common . ' Docs Library';
$lang->doc->libTypeList['api']       = 'API Library';
$lang->doc->libTypeList['custom']    = 'Custom Library';

$lang->doc->libGlobalList['api'] = 'API Library';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->statusList['']       = "";
$lang->doc->statusList['normal'] = "Published";
$lang->doc->statusList['draft']  = "Draft";

$lang->doc->aclList['open']    = "Public (Visible and editable by all users.)";
$lang->doc->aclList['private'] = "Private (Visible and editable by specific users only.)";

$lang->doc->aclListA['open']    = "Public (Accessible to all users. Users with document template editing permissions can access and edit it.)";
$lang->doc->aclListA['private'] = "Private (Editable and usable by the creator only.)";

$lang->doc->selectSpace = 'Select Space';
$lang->doc->space       = 'Space';
$lang->doc->spaceList['mine']    = 'My Space';
$lang->doc->spaceList['custom']  = 'Team Space';
$lang->doc->spaceList['product'] = $lang->productCommon . ' Space';
$lang->doc->spaceList['project'] = $lang->projectCommon . ' Space';
$lang->doc->spaceList['api']     = 'API Space';

$lang->doc->apiType = 'API Type';
$lang->doc->apiTypeList['product'] = $lang->productCommon . ' API';
$lang->doc->apiTypeList['project'] = $lang->projectCommon . ' API';
$lang->doc->apiTypeList['nolink']  = 'Independent API';

$lang->doc->typeList['html']     = 'Html';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = 'URL';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->createList['template']   = 'Wiki';
$lang->doc->createList['word']       = 'Word';
$lang->doc->createList['ppt']        = 'PPT';
$lang->doc->createList['excel']      = 'Excel';
$lang->doc->createList['attachment'] = $lang->doc->uploadDoc;

$lang->doc->types['doc'] = 'Doc';
$lang->doc->types['api'] = 'API Doc';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'View Mode';
$lang->doc->browseTypeList['list'] = 'List';
$lang->doc->browseTypeList['grid'] = 'Directory';

$lang->doc->fastMenuList['byediteddate']  = 'Recent Updates';
//$lang->doc->fastMenuList['visiteddate']   = 'Recently Visited';
$lang->doc->fastMenuList['openedbyme']    = 'My Documents';
$lang->doc->fastMenuList['collectedbyme'] = 'My Favorites';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = 'Show Attachments';
$lang->doc->customObjectLibs['customFiles'] = 'Show Custom Library';

$lang->doc->orderLib                       = 'Sort Libraries';
$lang->doc->customShowLibs                 = 'Display Settings';
$lang->doc->customShowLibsList['zero']     = 'Show Empty Libraries';
$lang->doc->customShowLibsList['children'] = 'Show Subcategory Documents';
$lang->doc->customShowLibsList['unclosed'] = "Show Open Executions Only ";

$lang->doc->mail = new stdclass();
$lang->doc->mail->releasedDoc = new stdclass();
$lang->doc->mail->edit        = new stdclass();
$lang->doc->mail->releasedDoc->title = "%s published document #%s: %s.";
$lang->doc->mail->edit->title        = "%s edited document #%6s: %s.";

$lang->doc->confirmDelete               = "Are you sure you want to delete this document?";
$lang->doc->confirmDeleteWithSub        = "Deleting this document will also remove all its associated content. Are you sure you want to proceed?";
$lang->doc->confirmDeleteLib            = "Are you sure you want to delete this document library?";
$lang->doc->confirmDeleteSpace          = "Deleting this space will remove all associated libraries, directories, and documents. Are you sure you want to proceed?";
$lang->doc->confirmDeleteBook           = "Are you sure you want to delete this manual?";
$lang->doc->confirmDeleteChapter        = "Are you sure you want to delete this chapter?";
$lang->doc->confirmDeleteChapterWithSub = "Deleting this chapter will also remove all its sub-chapters and documents. Are you sure you want to proceed?";
$lang->doc->confirmDeleteModule         = "Are you sure you want to delete this directory?";
$lang->doc->confirmDeleteModuleWithSub  = "Deleting this directory will also remove all its sub-directories, chapters, and documents. Are you sure you want to proceed?";
$lang->doc->confirmOtherEditing         = "This document is currently being edited. Continuing to edit will overwrite changes made by others. Do you want to continue?";
$lang->doc->errorEditSystemDoc          = "System document libraries cannot be modified. ";
$lang->doc->errorEmptyProduct           = "No {$lang->productCommon} found. The document cannot be created.";
$lang->doc->errorEmptyProject           = "No {$lang->executionCommon} found. The document cannot be created.";
$lang->doc->errorEmptySpaceLib          = "No document library exists in this space. The document cannot be created. Please create a document library first.";
$lang->doc->errorMainSysLib             = "System document libraries cannot be deleted.";
$lang->doc->accessDenied                = "You do not have permission to access this.";
$lang->doc->cannotView                  = "You do not have permission to view this document. Please contact the creator %s.";
$lang->doc->versionNotFount             = 'This document version does not exist.';
$lang->doc->noDoc                       = 'No documents yet.';
$lang->doc->noArticle                   = 'No articles yet.';
$lang->doc->noLib                       = 'No libraries yet.';
$lang->doc->noBook                      = 'No manuals have been created in the Wiki library. Please create one.';
$lang->doc->cannotCreateOffice          = '<p>Sorry, only ZenTao Biz and ZenTao Max versions supports creating %s documents.</p><p>To try these advanced versions, please contact us at support@zentao.pm.</p>';
$lang->doc->notSetOffice                = "Configuring <ahref='%s'>Collabora Online</a> is required to create %s documents.";
$lang->doc->requestTypeError            = "Collabora Online editing is unavailable because the current ZenTao requestType is not set to PATH_INFO. Please contact your administrator to modify the requestType configuration.";
$lang->doc->notSetCollabora             = "Collabora Online is not configured. %s documents cannot be created. Please configure <ahref='%s'>Collabora Online</a>.";
$lang->doc->noSearchedDoc               = 'No documents found.';
$lang->doc->noEditedDoc                 = 'You have not edited any documents yet.';
$lang->doc->noOpenedDoc                 = 'You have not created any documents yet.';
$lang->doc->noCollectedDoc              = 'You have not added any documents to your favorites.';
$lang->doc->errorEmptyLib               = 'No data available in the document library.';
$lang->doc->confirmUpdateContent        = 'Unsaved content detected. Do you want to continue editing?';
$lang->doc->selectLibType               = 'Please select a document library type.';
$lang->doc->selectDoc                   = 'Please select a document.';
$lang->doc->noLibreOffice               = 'You do not have permission to access the Office conversion settings.';
$lang->doc->errorParentChapter          = 'The parent chapter cannot be the chapter itself or one of its sub-chapters.';
$lang->doc->errorOthersCreated          = 'Moving documents Creator others in this library is currently not supported. Do you want to proceed?';
$lang->doc->confirmLeaveOnEdit          = 'Unsaved content detected. Do you want to navigate away?';
$lang->doc->errorOccurred               = 'Operation failed. Please try again later.';
$lang->doc->selectLibFirst              = 'Please select a document library first.';
$lang->doc->createLibFirst              = 'Please create a document library first.';
$lang->doc->nopriv                      = 'You do not have permission to access %s and cannot view this document. Please contact the administrator to adjust permissions.';
$lang->doc->docConvertComment           = "The document has been converted to the new editor format. Switch to version %s to view the original document.";
$lang->doc->previewNotAvailable         = 'Preview is currently unavailable. Please visit ZenTao to view document %s.';
$lang->doc->hocuspocusConnect           = 'Collaborative editing service connected.';
$lang->doc->hocuspocusDisconnect        = 'Collaborative editing service disconnected. Content will be synced upon reconnection.';
$lang->doc->docTemplateConvertComment   = 'The document template has been converted to the new editor format. Switch to version %s to view the original template.';
$lang->doc->noSupportList               = "This {$lang->projectCommon} does not support %s.";

$lang->doc->noticeAcl['lib']['product']['default']   = "Accessible for users who with permission to  access to the selected {Slang-productCommon}.";
$lang->doc->noticeAcl['lib']['product']['custom']    = "Accessible for users who with permission to  access to the selected {Slang-productCommon} and users on the whitelist.";
$lang->doc->noticeAcl['lib']['project']['default']   = "Accessible for users who with permission to  access to the selected {$lang->projectCommon}.";
$lang->doc->noticeAcl['lib']['project']['open']      = "Accessible for users who with permission to  access to the selected {$lang->projectCommon}.";
$lang->doc->noticeAcl['lib']['project']['private']   = "Accessible for users who with permission to  access to the selected {$lang->projectCommon} and users on the whitelist.";
$lang->doc->noticeAcl['lib']['project']['custom']    = "Accessible for users on the whitelist.";
$lang->doc->noticeAcl['lib']['execution']['default'] = "Accessible for users who with permission to  access to the selected {$lang->execution->common}.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Accessible for users who with permission to  access to the selected {$lang->execution->common} and users on the whitelist..";
$lang->doc->noticeAcl['lib']['api']['open']          = 'Accessible for all users.';
$lang->doc->noticeAcl['lib']['api']['custom']        = 'Accessible for users on the whitelist..';
$lang->doc->noticeAcl['lib']['api']['private']       = 'Accessible only for the creator.';
$lang->doc->noticeAcl['lib']['custom']['open']       = 'Accessible for all users.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Accessible for users on the whitelist..';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Accessible only for the creator.';

$lang->doc->noticeAcl['doc']['open']    = 'Anyone with access to the associated document library can access this.';
$lang->doc->noticeAcl['doc']['custom']  = 'Accessible for users on the whitelist..';
$lang->doc->noticeAcl['doc']['private'] = 'Accessible only for the creator.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url       = 'URL';
$lang->doc->placeholder->execution = 'In project library if no execution.';

$lang->doc->summary = "<strong>%s</strong> files on this page with the total size <strong>%s</strong>. <strong>%s</strong>: ";
$lang->doc->ge      = ':';
$lang->doc->point   = '.';

$lang->doc->libDropdown['editLib']       = 'Edit Library';
$lang->doc->libDropdown['deleteLib']     = 'Delete Library';
$lang->doc->libDropdown['editSpace']     = 'Edit Space';
$lang->doc->libDropdown['deleteSpace']   = 'Delete Space';
$lang->doc->libDropdown['addModule']     = 'Add Directory';
$lang->doc->libDropdown['addSameModule'] = 'Add Sibling Directory';
$lang->doc->libDropdown['addSubModule']  = 'Add Sub-directory';
$lang->doc->libDropdown['editModule']    = 'Edit Directory';
$lang->doc->libDropdown['delModule']     = 'Delete Directory';

$lang->doc->featureBar['tableContents']['all']   = 'All';
$lang->doc->featureBar['tableContents']['draft'] = 'Draft';

$lang->doc->featureBar['myspace']['all']   = 'All';
$lang->doc->featureBar['myspace']['draft'] = 'Draft';

$lang->doc->showDocList[1] = 'Yes';
$lang->doc->showDocList[0] = 'No';

$lang->doc->whitelistDeny['product']   = "<i class='icon pr-1 text-important icon-exclamation'></i>User<span class='px-1 text-important'>%s</span> lacks access permissions for the product and cannot view the document. Please configure the product access permissions to resolve this.";
$lang->doc->whitelistDeny['project']   = "<i class='icon pr-1 text-important icon-exclamation'></i>User<span class='px-1 text-important'>%s</span> lacks access permissions for the project and cannot view the document. Please configure the project access permissions to resolve this.";
$lang->doc->whitelistDeny['execution'] = "<i class='icon pr-1 text-important icon-exclamation'></i>User<span class='px-1 text-important'>%s</span> lacks access permissions for the execution and cannot view the document. Please configure the execution access permissions to resolve this.";
$lang->doc->whitelistDeny['doc']       = "<i class='icon pr-1 text-important icon-exclamation'></i>User<span class='px-1 text-important'>%s</span> lacks access permissions for the library and cannot view the document. Please configure the library access permissions to resolve this.";

$lang->doc->filterTypes[] = array('all', 'All');
$lang->doc->filterTypes[] = array('draft', 'Draft');
$lang->doc->filterTypes[] = array('collect', 'My Favorites');
$lang->doc->filterTypes[] = array('createdByMe', 'Created by Me');
$lang->doc->filterTypes[] = array('editedByMe', 'Edited by Me');

$lang->doc->fileFilterTypes[] = array('all', 'All');
$lang->doc->fileFilterTypes[] = array('addedByMe', 'Added by Me');

$lang->doc->productFilterTypes[] = array('all',  'All');
$lang->doc->productFilterTypes[] = array('mine', 'Managed by Me');

$lang->doc->projectFilterTypes[] = array('all', 'All');
$lang->doc->projectFilterTypes[] = array('mine', 'Involved');

$lang->doc->spaceFilterTypes[] = array('all', 'All');

$lang->doc->manageScope        = 'Manage Scope';
$lang->doc->browseTemplate     = 'Template Gallery';
$lang->doc->createTemplate     = 'Create Document Template';
$lang->doc->editTemplate       = 'Edit Document Template';
$lang->doc->moveTemplate       = 'Move Document Template';
$lang->doc->deleteTemplate     = 'Delete Document Template';
$lang->doc->viewTemplate       = 'Document Template Details';
$lang->doc->addTemplateType    = 'Create Template Category';
$lang->doc->editTemplateType   = 'Edit Template Category';
$lang->doc->deleteTemplateType = 'Delete Template Category';
$lang->doc->sortTemplate       = 'Sort';

$lang->doc->docLang                              = new stdClass();
$lang->doc->docLang->cancel                      = $lang->cancel;
$lang->doc->docLang->export                      = $lang->export;
$lang->doc->docLang->exportWord                  = "Export Word";
$lang->doc->docLang->exportPdf                   = "Export PDF";
$lang->doc->docLang->exportImage                 = "Export Image";
$lang->doc->docLang->exportHtml                  = "Export HTML";
$lang->doc->docLang->exportMarkdown              = "Export Markdown";
$lang->doc->docLang->exportJSON                  = "Export Backup(.json)";
$lang->doc->docLang->importMarkdown              = "Import Markdown";
$lang->doc->docLang->importConfluence            = "Import Confluence Storage";
$lang->doc->docLang->importJSON                  = "Import Backup(.json)";
$lang->doc->docLang->importConfirm               = "Importing will overwrite the existing content. Are you sure you want to proceed?";
$lang->doc->docLang->settings                    = $lang->settings;
$lang->doc->docLang->save                        = $lang->save;
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createLib                   = $lang->doc->createLib;
$lang->doc->docLang->actions                     = $lang->doc->libDropdown;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->create                      = $lang->doc->createAB;
$lang->doc->docLang->createDoc                   = $lang->doc->create;
$lang->doc->docLang->editDoc                     = $lang->doc->edit;
$lang->doc->docLang->effort                      = $lang->doc->effort;
$lang->doc->docLang->deleteDoc                   = $lang->doc->delete;
$lang->doc->docLang->uploadDoc                   = $lang->doc->uploadDoc;
$lang->doc->docLang->createList                  = $lang->doc->createList;
$lang->doc->docLang->confirmDelete               = $lang->doc->confirmDelete;
$lang->doc->docLang->confirmDeleteWithSub        = $lang->doc->confirmDeleteWithSub;
$lang->doc->docLang->confirmDeleteLib            = $lang->doc->confirmDeleteLib;
$lang->doc->docLang->confirmDeleteSpace          = $lang->doc->confirmDeleteSpace;
$lang->doc->docLang->confirmDeleteModule         = $lang->doc->confirmDeleteModule;
$lang->doc->docLang->confirmDeleteModuleWithSub  = $lang->doc->confirmDeleteModuleWithSub;
$lang->doc->docLang->confirmDeleteChapter        = $lang->doc->confirmDeleteChapter;
$lang->doc->docLang->confirmDeleteChapterWithSub = $lang->doc->confirmDeleteChapterWithSub;
$lang->doc->docLang->collect                     = $lang->doc->collect;
$lang->doc->docLang->edit                        = $lang->doc->edit;
$lang->doc->docLang->delete                      = $lang->doc->delete;
$lang->doc->docLang->cancelCollection            = $lang->doc->cancelCollection;
$lang->doc->docLang->moveDoc                     = $lang->doc->moveDocAction;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->moveLib                     = $lang->doc->moveLibAction;
$lang->doc->docLang->moduleName                  = $lang->doc->catalogName;
$lang->doc->docLang->saveDraft                   = $lang->doc->saveDraft;
$lang->doc->docLang->template                    = $lang->doc->template;
$lang->doc->docLang->release                     = $lang->doc->release;
$lang->doc->docLang->batchMove                   = $lang->doc->batchMove;
$lang->doc->docLang->filterTypes                 = $lang->doc->filterTypes;
$lang->doc->docLang->fileFilterTypes             = $lang->doc->fileFilterTypes;
$lang->doc->docLang->productFilterTypes          = $lang->doc->productFilterTypes;
$lang->doc->docLang->projectFilterTypes          = $lang->doc->projectFilterTypes;
$lang->doc->docLang->spaceFilterTypes            = $lang->doc->spaceFilterTypes;
$lang->doc->docLang->sortCatalog                 = $lang->doc->sortCatalog;
$lang->doc->docLang->sortDoclib                  = $lang->doc->sortDoclib;
$lang->doc->docLang->sortDoc                     = $lang->doc->sortDoc;
$lang->doc->docLang->errorOccurred               = $lang->doc->errorOccurred;
$lang->doc->docLang->selectLibFirst              = $lang->doc->selectLibFirst;
$lang->doc->docLang->createLibFirst              = $lang->doc->createLibFirst;
$lang->doc->docLang->space                       = 'Space';
$lang->doc->docLang->spaceTypeNames              = array();
$lang->doc->docLang->spaceTypeNames['mine']      = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['product']   = $lang->productCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['project']   = $lang->projectCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['execution'] = $lang->executionCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['api']       = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['custom']    = $lang->doc->docLang->space;
$lang->doc->docLang->enterSpace                  = 'Enter Space';
$lang->doc->docLang->noDocs                      = 'No documents.';
$lang->doc->docLang->noFiles                     = 'No files.';
$lang->doc->docLang->noLibs                      = 'No document libraries.';
$lang->doc->docLang->noModules                   = 'No directories.';
$lang->doc->docLang->docsTotalInfo               = 'Total: {0} document(s).';
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createModule                = $lang->doc->addCatalog;
$lang->doc->docLang->leaveEditingConfirm         = 'You have unsaved changes. Are you sure you want to leave?';
$lang->doc->docLang->saveDocFailed               = 'Failed to save document. Please try again later.';
$lang->doc->docLang->loadingDocsData             = 'Loading document data...';
$lang->doc->docLang->loadDataFailed              = 'Failed to load data.';
$lang->doc->docLang->noSpaceTip                  = 'It looks empty here. Create a space to get started. ';
$lang->doc->docLang->searchModulePlaceholder     = 'Search Directories';
$lang->doc->docLang->searchDocPlaceholder        = 'Search Documents';
$lang->doc->docLang->searchChapterPlaceholder    = 'Search Chapters';
$lang->doc->docLang->searchSpacePlaceholder      = 'Search Spaces';
$lang->doc->docLang->searchLibPlaceholder        = 'Search Libraries';
$lang->doc->docLang->searchPlaceholder           = 'Search';
$lang->doc->docLang->newDocLabel                 = 'New';
$lang->doc->docLang->editingDocLabel             = 'Editing';
$lang->doc->docLang->filesLib                    = $lang->doclib->files;
$lang->doc->docLang->currentDocVersionHint       = 'Current version (Click to switch)';
$lang->doc->docLang->viewsCount                  = $lang->doc->views;
$lang->doc->docLang->keywords                    = $lang->doc->keywords;
$lang->doc->docLang->keywordsPlaceholder         = $lang->doc->keywordsTips;
$lang->doc->docLang->loadingDocTip               = 'Loading document...';
$lang->doc->docLang->loadingEditorTip            = 'Loading editor...';
$lang->doc->docLang->pasteImageTip               = $lang->noticePasteImg;
$lang->doc->docLang->downloadFile                = 'Download';
$lang->doc->docLang->loadingFilesTip             = 'Loading file...';
$lang->doc->docLang->recTotalFormat              = $lang->pager->totalCountAB;
$lang->doc->docLang->recPerPageFormat            = $lang->pager->pageSizeAB;
$lang->doc->docLang->firstPage                   = $lang->pager->firstPage;
$lang->doc->docLang->prevPage                    = $lang->pager->previousPage;
$lang->doc->docLang->nextPage                    = $lang->pager->nextPage;
$lang->doc->docLang->lastPage                    = $lang->pager->lastPage;
$lang->doc->docLang->docOutline                  = 'Outline';
$lang->doc->docLang->noOutline                   = 'No outline.';
$lang->doc->docLang->loading                     = $lang->loading;
$lang->doc->docLang->libNamePrefix               = 'Library';
$lang->doc->docLang->colon                       = $lang->colon;
$lang->doc->docLang->createdByUserAt             = 'Creator {name} at {time}';
$lang->doc->docLang->editedByUserAt              = 'Edited by {name} at {time}';
$lang->doc->docLang->docInfo                     = 'Document Info';
$lang->doc->docLang->docStatus                   = $lang->doc->status;
$lang->doc->docLang->creator                     = $lang->doc->addedBy;
$lang->doc->docLang->createDate                  = $lang->doc->addedDate;
$lang->doc->docLang->modifier                    = $lang->doc->editedBy;
$lang->doc->docLang->editDate                    = $lang->doc->editedDate;
$lang->doc->docLang->collectCount                = $lang->doc->docCollects;
$lang->doc->docLang->collected                   = 'Added to Favorited';
$lang->doc->docLang->history                     = $lang->history;
$lang->doc->docLang->updateHistory               = $lang->doc->updateInfo;
$lang->doc->docLang->updateInfoFormat            = 'Updated by {name} at {time}';
$lang->doc->docLang->noUpdateInfo                = 'No update history.';
$lang->doc->docLang->enterFullscreen             = 'Full Screen';
$lang->doc->docLang->exitFullscreen              = 'Exit Full Screen';
$lang->doc->docLang->collapse                    = 'Collapse';
$lang->doc->docLang->draft                       = $lang->doc->statusList['draft'];
$lang->doc->docLang->released                    = $lang->doc->statusList['normal'];
$lang->doc->docLang->attachment                  = $lang->doc->files;
$lang->doc->docLang->docTitleRequired            = 'Document title is required. ';
$lang->doc->docLang->docTitlePlaceholder         = 'Enter document title.';
$lang->doc->docLang->noDataYet                   = 'No data.';
$lang->doc->docLang->position                    = $lang->doc->position;
$lang->doc->docLang->relateObject                = 'Related Items';
$lang->doc->docLang->showHasDocsOnlyProduct      = 'Show only products with docs';
$lang->doc->docLang->showHasDocsOnlyProject      = 'Show only projects with docs';
$lang->doc->docLang->showClosedProduct           = 'Show closed products';
$lang->doc->docLang->showClosedProject           = 'Show closed projects';
$lang->doc->docLang->noProducts                  = 'No products.';
$lang->doc->docLang->noProjects                  = 'No projects.';
$lang->doc->docLang->productMine                 = 'Managed by Me';
$lang->doc->docLang->projectMine                 = 'My Involvement';
$lang->doc->docLang->productOther                = 'Others';
$lang->doc->docLang->projectOther                = 'Others';
$lang->doc->docLang->accessDenied                = $lang->doc->accessDenied;
$lang->doc->docLang->convertToNewDoc             = 'Convert document';
$lang->doc->docLang->convertToNewDocConfirm      = 'The new document format uses a modern block-based editor for an enhanced experience. Are you sure you want to convert this template to the new format? Once saved as a draft or published, you cannot revert to the legacy editor.';
$lang->doc->docLang->created                     = 'created';
$lang->doc->docLang->edited                      = 'edited';
$lang->doc->docLang->notSaved                    = 'Unsaved';
$lang->doc->docLang->oldDocEditingTip            = 'This document was created with the legacy editor and is now being edited with the new editor. Saving will convert it to the new format.';
$lang->doc->docLang->switchToOldEditor           = 'Switch to legacy editor';
$lang->doc->docLang->zentaoList                  = $lang->doc->zentaoList;
$lang->doc->docLang->list                        = $lang->doc->list;
$lang->doc->docLang->loadingFile                 = 'Downloading images...';
$lang->doc->docLang->needEditable                = $lang->doc->needEditable;
$lang->doc->docLang->addChapter                  = $lang->doc->addChapter;
$lang->doc->docLang->editChapter                 = $lang->doc->editChapter;
$lang->doc->docLang->sortChapter                 = $lang->doc->sortChapter;
$lang->doc->docLang->deleteChapter               = $lang->doc->deleteChapter;
$lang->doc->docLang->addSubChapter               = $lang->doc->addSubChapter;
$lang->doc->docLang->addSameChapter              = $lang->doc->addSameChapter;
$lang->doc->docLang->addSubDoc                   = $lang->doc->addSubDoc;
$lang->doc->docLang->chapterName                 = $lang->doc->chapterName;
$lang->doc->docLang->autoSaveHint                = 'Auto-saved.';
$lang->doc->docLang->editing                     = 'Editing';
$lang->doc->docLang->restoreVersionHint          = 'Restore to version';
$lang->doc->docLang->restoreVersion              = 'Restore';
$lang->doc->docLang->restoreVersionConfirm       = 'This will create a new version using the content from version {version}. Do you want to continue?';
$lang->doc->docLang->frozenTips                  = $lang->doc->frozenTips;
$lang->doc->docLang->aiTask                      = 'AI Teammate Task';

$lang->docTemplate->types = array();
$lang->docTemplate->types['plan']   = 'Plan';
$lang->docTemplate->types['story']  = 'Story';
$lang->docTemplate->types['design'] = 'Design';
$lang->docTemplate->types['dev']    = 'Development';
$lang->docTemplate->types['test']   = 'Test';
$lang->docTemplate->types['desc']   = 'Description';
$lang->docTemplate->types['other']  = 'Others';

$lang->docTemplate->builtInScopes = array();
$lang->docTemplate->builtInScopes['rnd']  = array();
$lang->docTemplate->builtInScopes['or']   = array();
$lang->docTemplate->builtInScopes['lite'] = array();
$lang->docTemplate->builtInScopes['rnd']['product']   = 'Product';
$lang->docTemplate->builtInScopes['rnd']['project']   = 'Project';
$lang->docTemplate->builtInScopes['rnd']['execution'] = 'Execution';
$lang->docTemplate->builtInScopes['rnd']['personal']  = 'Personal';
$lang->docTemplate->builtInScopes['or']['market']     = 'Marketing';
$lang->docTemplate->builtInScopes['or']['product']    = 'Product';
$lang->docTemplate->builtInScopes['or']['personal']   = 'Personal';
$lang->docTemplate->builtInScopes['lite']['project']  = 'Project';
$lang->docTemplate->builtInScopes['lite']['personal'] = 'Personal';
