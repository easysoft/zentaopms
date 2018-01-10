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
$lang->doc->common         = 'Doc';
$lang->doc->id             = 'ID';
$lang->doc->product        = $lang->productCommon;
$lang->doc->project        = $lang->projectCommon;
$lang->doc->lib            = 'Library';
$lang->doc->module         = 'Module';
$lang->doc->title          = 'Title';
$lang->doc->digest         = 'Summary';
$lang->doc->comment        = 'Remark';
$lang->doc->type           = 'Type';
$lang->doc->content        = 'Text';
$lang->doc->keywords       = 'Keywords';
$lang->doc->url            = 'URL';
$lang->doc->files          = 'File';
$lang->doc->addedBy        = 'Creator';
$lang->doc->addedDate      = 'Create Date';
$lang->doc->editedBy       = 'Edit By';
$lang->doc->editedDate     = 'Edit Date';
$lang->doc->version        = 'Version';
$lang->doc->basicInfo      = 'Basic Info';
$lang->doc->deleted        = 'Deleted';
$lang->doc->fileObject     = 'The Object';
$lang->doc->whiteList      = 'White List';
$lang->doc->contentType    = 'Format';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = 'File Title';
$lang->doc->filePath       = 'File Path';
$lang->doc->extension      = 'Extension';
$lang->doc->size           = 'Size';
$lang->doc->download       = 'Download';
$lang->doc->acl            = 'Right';
$lang->doc->groups         = 'Groups';
$lang->doc->users          = 'Users';

$lang->doc->moduleDoc      = 'By Module';
$lang->doc->searchDoc      = 'Search';
$lang->doc->allDoc         = 'All';
$lang->doc->openedByMe     = 'My';
$lang->doc->orderByOpen    = 'Recently Added';
$lang->doc->orderByEdit    = 'Recently Modifie';

/* 方法列表。*/
$lang->doc->index          = 'Home';
$lang->doc->create         = 'Create Doc';
$lang->doc->edit           = 'Edit';
$lang->doc->delete         = 'Delete';
$lang->doc->browse         = 'List';
$lang->doc->view           = 'Details';
$lang->doc->diff           = 'Diff';
$lang->doc->sort           = 'Sort';
$lang->doc->manageType     = 'Manage category';
$lang->doc->editType       = 'Edit';
$lang->doc->deleteType     = 'Delete';
$lang->doc->addType        = 'Add';
$lang->doc->childType      = 'Child';

$lang->doc->libName        = 'Name';
$lang->doc->libType        = 'Category';
$lang->doc->custom         = 'Custom Library';
$lang->doc->customAB       = 'Custom Library';
$lang->doc->createLib      = 'Create Library';
$lang->doc->allLibs        = 'Libraries';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->projectCommon} Library List";
$lang->doc->showFiles      = 'File Library';
$lang->doc->editLib        = 'Edit Library';
$lang->doc->deleteLib      = 'Delete Library';
$lang->doc->fixedMenu      = 'Fixed In Menu';
$lang->doc->removeMenu     = 'Remove From Menu';
$lang->doc->search         = 'Search';

/* 查询条件列表 */
$lang->doc->allProduct     = 'All' . $lang->productCommon;
$lang->doc->allProject     = 'All' . $lang->projectCommon;

$lang->doc->libTypeList['product'] = $lang->productCommon . ' Scope';
$lang->doc->libTypeList['project'] = $lang->projectCommon . ' Scope';
$lang->doc->libTypeList['custom']  = 'Custom Library';

$lang->doc->systemLibs['product'] = $lang->productCommon . 'Doc Lib';
$lang->doc->systemLibs['project'] = $lang->projectCommon . 'Doc Lib';

global $config;
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->systemLibs['project']);
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->libTypeList['project']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->systemLibs['product']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->libTypeList['product']);

$lang->doc->aclList['open']    = 'Public';
$lang->doc->aclList['custom']  = 'Custom';
$lang->doc->aclList['private'] = 'Private';

$lang->doc->types['text'] = 'Text';
$lang->doc->types['url']  = 'URL';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'Categories';
$lang->doc->browseTypeList['list'] = 'List';
$lang->doc->browseTypeList['menu'] = 'Menu';
$lang->doc->browseTypeList['tree'] = 'Tree';
$lang->doc->browseTypeList['card'] = 'Card';

$lang->doc->confirmDelete      = "Do you want to delete this document?";
$lang->doc->confirmDeleteLib   = "Do you want to delete this Doc Lib?";
$lang->doc->errorEditSystemDoc = "System Doc Lib needs no modifications.";
$lang->doc->errorEmptyProduct  = "No {$lang->productCommon}. It cannot be created.";
$lang->doc->errorEmptyProject  = "No {$lang->projectCommon}. It cannot be created.";
$lang->doc->errorMainSysLib    = "This library cannot be deleted.";
$lang->doc->accessDenied       = "Access denied!";
$lang->doc->versionNotFount    = 'It does not exist in this build.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'Url';

$lang->doclib = new stdclass();
$lang->doclib->name    = 'Name';
$lang->doclib->control = 'Access Control';
$lang->doclib->group   = 'Group';
$lang->doclib->user    = 'User';
$lang->doclib->files   = 'Files';
$lang->doclib->all     = 'All Library';
$lang->doclib->select  = 'Select';
$lang->doclib->project = $lang->projectCommon . ' Library';
$lang->doclib->product = $lang->productCommon . ' Library';

$lang->doclib->main['product'] =  'Main Lib';
$lang->doclib->main['project'] =  'Main Lib';
