<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->doc->common         = 'Document';
$lang->doc->id             = 'ID';
$lang->doc->product        = $lang->productCommon;
$lang->doc->project        = $lang->projectCommon;
$lang->doc->lib            = 'Library';
$lang->doc->module         = 'Category';
$lang->doc->title          = 'Name';
$lang->doc->digest         = 'Summary';
$lang->doc->comment        = 'Comment';
$lang->doc->type           = 'Type';
$lang->doc->content        = 'Text';
$lang->doc->keywords       = 'Tags';
$lang->doc->url            = 'URL';
$lang->doc->files          = 'Files';
$lang->doc->addedBy        = 'Author';
$lang->doc->addedDate      = 'Added';
$lang->doc->editedBy       = 'UpdatedBy';
$lang->doc->editedDate     = 'UpdatedDate';
$lang->doc->version        = 'Version';
$lang->doc->basicInfo      = 'Basic Info';
$lang->doc->deleted        = 'Deleted';
$lang->doc->fileObject     = 'Dependent Item';
$lang->doc->whiteList      = 'White List';
$lang->doc->contentType    = 'Format';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = 'File Name';
$lang->doc->filePath       = 'File Path';
$lang->doc->extension      = 'Extension';
$lang->doc->size           = 'Size';
$lang->doc->download       = 'Download';
$lang->doc->acl            = 'Right';
$lang->doc->fileName       = 'Files';
$lang->doc->groups         = 'Groups';
$lang->doc->users          = 'Users';
$lang->doc->item           = ' Items';
$lang->doc->num            = 'Documents';
$lang->doc->searchResult   = 'Search Result';

$lang->doc->moduleDoc      = 'By Module';
$lang->doc->searchDoc      = 'Search';
$lang->doc->fast           = 'Quick Entry';
$lang->doc->allDoc         = 'All Documents';
$lang->doc->openedByMe     = 'My';
$lang->doc->orderByOpen    = 'Recent Added';
$lang->doc->orderByEdit    = 'Recent Updated';
$lang->doc->orderByVisit   = 'Last Visited';
$lang->doc->todayEdited    = 'Updated Today';
$lang->doc->pastEdited     = 'Total Updated';
$lang->doc->myDoc          = 'My Documents';
$lang->doc->myCollection   = 'My Favorites';

/* Methods list */
$lang->doc->index            = 'Document Home';
$lang->doc->create           = 'Create Document';
$lang->doc->edit             = 'Edit Document';
$lang->doc->delete           = 'Delete Document';
$lang->doc->browse           = 'Document List';
$lang->doc->view             = 'Document Detail';
$lang->doc->diff             = 'Diff';
$lang->doc->diffAction       = 'Diff Document';
$lang->doc->sort             = 'Rank Document';
$lang->doc->manageType       = 'Manage Category';
$lang->doc->editType         = 'Edit';
$lang->doc->deleteType       = 'Delete';
$lang->doc->addType          = 'Add';
$lang->doc->childType        = 'Categories';
$lang->doc->collect          = 'Add Favorite';
$lang->doc->cancelCollection = 'Remove Favorite';
$lang->doc->deleteFile       = 'Delete File';

$lang->doc->libName        = 'Document Library';
$lang->doc->libType        = 'Category';
$lang->doc->custom         = 'Custom Document Library';
$lang->doc->customAB       = 'Custom Doc Lib';
$lang->doc->createLib      = 'Create Document Library';
$lang->doc->allLibs        = 'Library List';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->projectCommon} Libraries";
$lang->doc->showFiles      = 'Attachments';
$lang->doc->editLib        = 'Edit Document Library';
$lang->doc->deleteLib      = 'Delete Document Library';
$lang->doc->fixedMenu      = 'Fix to Menu';
$lang->doc->removeMenu     = 'Remove from Menu';
$lang->doc->search         = 'Search';

/* Query condition list. */
$lang->doc->allProduct     = 'All' . $lang->productCommon . 's';
$lang->doc->allProject     = 'All' . $lang->projectCommon . 's';

$lang->doc->libTypeList['product'] = $lang->productCommon . ' Library';
$lang->doc->libTypeList['project'] = $lang->projectCommon . ' Library';
$lang->doc->libTypeList['custom']  = 'Custom Library';

$lang->doc->libIconList['product'] = 'icon-cube';
$lang->doc->libIconList['project'] = 'icon-stack';
$lang->doc->libIconList['custom']  = 'icon-folder-o';

$lang->doc->systemLibs['product'] = $lang->productCommon;
$lang->doc->systemLibs['project'] = $lang->projectCommon;

global $config;
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->systemLibs['project']);
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->libTypeList['project']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->systemLibs['product']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->libTypeList['product']);

$lang->doc->aclList['open']    = 'Public';
$lang->doc->aclList['custom']  = 'Custom';
$lang->doc->aclList['private'] = 'Private';

$lang->doc->typeList['html']     = 'Html';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = 'URL';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->types['text'] = 'Text';
$lang->doc->types['url']  = 'URL';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'Category';
$lang->doc->browseTypeList['list'] = 'List';
$lang->doc->browseTypeList['grid'] = 'Card';

$lang->doc->fastMenuList['byediteddate']  = 'Recent Edited';
//$lang->doc->fastMenuList['visiteddate']   = 'Recently Visited';
$lang->doc->fastMenuList['openedbyme']    = 'My Documents';
$lang->doc->fastMenuList['collectedbyme'] = 'My Favorites';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = 'Show Attachment Library';
$lang->doc->customObjectLibs['customFiles'] = 'Show Custom Library';

$lang->doc->orderLib = 'Rank Settings';
$lang->doc->customShowLibs = 'Display Settings';
$lang->doc->customShowLibsList['zero']     = 'Display Empty Library';
$lang->doc->customShowLibsList['children'] = 'Display Child-category Documents';
$lang->doc->customShowLibsList['unclosed'] = "Display Active {$lang->projectCommon}s Only";

$lang->doc->confirmDelete      = "Do you want to delete this document?";
$lang->doc->confirmDeleteLib   = "Do you want to delete this document library?";
$lang->doc->errorEditSystemDoc = "You don't have to change system document library.";
$lang->doc->errorEmptyProduct  = "No {$lang->productCommon}. It cannot be created.";
$lang->doc->errorEmptyProject  = "No {$lang->projectCommon}. It cannot be created.";
$lang->doc->errorMainSysLib    = "This library cannot be deleted.";
$lang->doc->accessDenied       = "Access is denied!";
$lang->doc->versionNotFount    = 'It does not exist in this build.';
$lang->doc->noDoc              = 'No documents. ';
$lang->doc->cannotCreateOffice = 'Sorry, %s can only be created in ZenTao Enterprise. Contact us at renee@easysoft.ltd to try ZenTao Enterprise.';
$lang->doc->notSetOffice       = "<p>To create a %s document, you need to configure <a href='%s' target='_parent'>office convert</a>.<p>";
$lang->doc->noSearchedDoc      = 'No documents found.';
$lang->doc->noEditedDoc        = 'You have not edited any documents.';
$lang->doc->noOpenedDoc        = 'You have not created any documents.';
$lang->doc->noCollectedDoc     = 'You have not favorited any documents.';

$lang->doc->noticeAcl['lib']['product']['default'] = 'Users who can access the selected product can access it.';
$lang->doc->noticeAcl['lib']['product']['custom']  = 'Users who can access the selected product or users in the whiltelist can access it.';
$lang->doc->noticeAcl['lib']['project']['default'] = 'Users who can access the selected project can access it.';
$lang->doc->noticeAcl['lib']['project']['custom']  = 'Users who can access the selected project or users in the whiltelist can access it.';
$lang->doc->noticeAcl['lib']['custom']['open']     = 'All users can access it.';
$lang->doc->noticeAcl['lib']['custom']['custom']   = 'Users in the whitelist can access it.';
$lang->doc->noticeAcl['lib']['custom']['private']  = 'Only the one who created it can access it.';

$lang->doc->noticeAcl['doc']['open']    = 'Users who can access the document library which the document belongs can access it.';
$lang->doc->noticeAcl['doc']['custom']  = 'Users in the whiltelist can access it.';
$lang->doc->noticeAcl['doc']['private'] = 'Only the one who created it can access it.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'URL';

$lang->doclib = new stdclass();
$lang->doclib->name    = 'Name';
$lang->doclib->control = 'Access Control';
$lang->doclib->group   = 'Group';
$lang->doclib->user    = 'User';
$lang->doclib->files   = 'Attachments';
$lang->doclib->all     = 'All Libraries';
$lang->doclib->select  = 'Select';
$lang->doclib->project = $lang->projectCommon . ' Library';
$lang->doclib->product = $lang->productCommon . ' Library';

$lang->doclib->aclListA['default'] = 'Default';
$lang->doclib->aclListA['custom']  = 'Custom';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Custom';
$lang->doclib->aclListB['private'] = 'Private';

$lang->doclib->create['product'] = 'Create ' . $lang->productCommon . ' Library';
$lang->doclib->create['project'] = 'Create ' . $lang->projectCommon . ' Library';
$lang->doclib->create['custom']  = 'Create Custom Library';

$lang->doclib->main['product'] =  'Primary Library';
$lang->doclib->main['project'] =  'Primary Library';

$lang->doclib->tabList['product'] = $lang->productCommon;
$lang->doclib->tabList['project'] = $lang->projectCommon;
$lang->doclib->tabList['custom']  = 'Custom';

$lang->doclib->nameList['custom'] = 'Custom Name';
