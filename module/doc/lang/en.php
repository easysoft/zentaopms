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
$lang->doc->digest         = 'Digest';
$lang->doc->comment        = 'Comment';
$lang->doc->type           = 'Type';
$lang->doc->content        = 'Content';
$lang->doc->keywords       = 'Keywords';
$lang->doc->url            = 'URL';
$lang->doc->files          = 'File';
$lang->doc->addedBy        = 'Added by';
$lang->doc->addedDate      = 'Added date';
$lang->doc->editedBy       = 'Edited by';
$lang->doc->editedDate     = 'Edited date';
$lang->doc->version        = 'Version';
$lang->doc->basicInfo      = 'Basic Info';
$lang->doc->deleted        = 'Deleted';
$lang->doc->fileObject     = 'File object';
$lang->doc->whiteList      = 'White list';
$lang->doc->contentType    = 'Doc type';

$lang->doc->moduleDoc      = 'By module';
$lang->doc->searchDoc      = 'By search';
$lang->doc->allDoc         = 'All doc';
$lang->doc->openedByMe     = 'Added by me';

/* Actions. */
$lang->doc->index          = 'Index';
$lang->doc->create         = 'Create doc';
$lang->doc->edit           = 'Edit doc';
$lang->doc->delete         = 'Delete doc';
$lang->doc->browse         = 'Browse doc';
$lang->doc->view           = 'View doc';
$lang->doc->diff           = 'Diff';
$lang->doc->manageType     = 'Manage type';
$lang->doc->addType        = 'Add type';

$lang->doc->libName        = 'Library name';
$lang->doc->libType        = 'Library type';
$lang->doc->custom         = 'Custom library';
$lang->doc->createLib      = 'Create library';
$lang->doc->allLibs        = 'Browse library';
$lang->doc->showFiles      = 'File library';
$lang->doc->editLib        = 'Edit library';
$lang->doc->deleteLib      = 'Delete library';
$lang->doc->fixedMenu      = 'Fixed in Menu';
$lang->doc->removeMenu     = 'Move from menu';

/* Browse tabs. */
$lang->doc->allProduct     = "All {$lang->productCommon}s";
$lang->doc->allProject     = "All {$lang->projectCommon}s";

$lang->doc->libTypeList['product'] = "{$lang->productCommon} doc";
$lang->doc->libTypeList['project'] = "{$lang->projectCommon} doc";
$lang->doc->libTypeList['custom']  = 'Custom library';

$lang->doc->systemLibs['product'] = "{$lang->productCommon} doc";
$lang->doc->systemLibs['project'] = "{$lang->projectCommon} doc";

$lang->doc->aclList['open']    = 'Public';
$lang->doc->aclList['custom']  = 'Custom';
$lang->doc->aclList['private'] = 'Private';

$lang->doc->types['text'] = 'Text';
$lang->doc->types['url']  = 'Url';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'Browse type';
$lang->doc->browseTypeList['list'] = 'List';
$lang->doc->browseTypeList['menu'] = 'Menu';
$lang->doc->browseTypeList['tree'] = 'Tree';

$lang->doc->confirmDelete      = "Are you sure to delete this doc?";
$lang->doc->confirmDeleteLib   = " Are you sure to delete this doc library?";
$lang->doc->errorEditSystemDoc = "System doc library needn't edit";
$lang->doc->errorEmptyProduct  = "{$lang->productCommon} is empty, can not create doc.";
$lang->doc->errorEmptyProject  = "{$lang->projectCommon} is empty, can not create doc.";
$lang->doc->errorMainSysLib    = "Do not delete this library in system library";
$lang->doc->accessDenied       = "Access to this library denied.";
$lang->doc->noMatched          = "No matched library including '%s'";
$lang->doc->versionNotFount    = 'Do not found doc in this version';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'url';

$lang->doclib = new stdclass();
$lang->doclib->name    = 'Library Name';
$lang->doclib->control = 'Control';
$lang->doclib->acl     = 'Power';
$lang->doclib->group   = 'Group';
$lang->doclib->user    = 'User';
$lang->doclib->files   = 'Files';
$lang->doclib->all     = 'All library';
$lang->doclib->select  = 'Select library';
