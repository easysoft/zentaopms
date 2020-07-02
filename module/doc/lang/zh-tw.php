<?php
/**
 * The doc module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-tw.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->doc->common         = '文檔';
$lang->doc->id             = '文檔編號';
$lang->doc->product        = '所屬' . $lang->productCommon;
$lang->doc->project        = '所屬' . $lang->projectCommon;
$lang->doc->lib            = '所屬文檔庫';
$lang->doc->module         = '所屬分類';
$lang->doc->title          = '文檔標題';
$lang->doc->digest         = '文檔摘要';
$lang->doc->comment        = '文檔備註';
$lang->doc->type           = '文檔類型';
$lang->doc->content        = '文檔正文';
$lang->doc->keywords       = '關鍵字';
$lang->doc->url            = '文檔URL';
$lang->doc->files          = '附件';
$lang->doc->addedBy        = '由誰添加';
$lang->doc->addedDate      = '添加時間';
$lang->doc->editedBy       = '由誰更新';
$lang->doc->editedDate     = '更新時間';
$lang->doc->version        = '版本號';
$lang->doc->basicInfo      = '基本信息';
$lang->doc->deleted        = '已刪除';
$lang->doc->fileObject     = '所屬對象';
$lang->doc->whiteList      = '白名單';
$lang->doc->contentType    = '文檔格式';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = '附件名';
$lang->doc->filePath       = '地址';
$lang->doc->extension      = '類型';
$lang->doc->size           = '大小';
$lang->doc->download       = '下載';
$lang->doc->acl            = '權限';
$lang->doc->fileName       = '附件';
$lang->doc->groups         = '分組';
$lang->doc->users          = '用戶';
$lang->doc->item           = '項';
$lang->doc->num            = '文檔數量';
$lang->doc->searchResult   = '搜索結果';

$lang->doc->moduleDoc      = '按模組瀏覽';
$lang->doc->searchDoc      = '搜索';
$lang->doc->fast           = '快速訪問';
$lang->doc->allDoc         = '所有文檔';
$lang->doc->openedByMe     = '由我創建';
$lang->doc->orderByOpen    = '最近添加';
$lang->doc->orderByEdit    = '最近更新';
$lang->doc->orderByVisit   = '最近訪問';
$lang->doc->todayEdited    = '今日更新';
$lang->doc->pastEdited     = '往日更新';
$lang->doc->myDoc          = '我的文檔';
$lang->doc->myCollection   = '我的收藏';

/* 方法列表。*/
$lang->doc->index            = '文檔主頁';
$lang->doc->create           = '創建文檔';
$lang->doc->edit             = '編輯文檔';
$lang->doc->delete           = '刪除文檔';
$lang->doc->browse           = '文檔列表';
$lang->doc->view             = '文檔詳情';
$lang->doc->diff             = '對比';
$lang->doc->diffAction       = '對比文檔';
$lang->doc->sort             = '文檔排序';
$lang->doc->manageType       = '維護分類';
$lang->doc->editType         = '編輯分類';
$lang->doc->deleteType       = '刪除分類';
$lang->doc->addType          = '增加分類';
$lang->doc->childType        = '子分類';
$lang->doc->collect          = '收藏';
$lang->doc->cancelCollection = '取消收藏';
$lang->doc->deleteFile       = '刪除附件';

$lang->doc->libName        = '文檔庫名稱';
$lang->doc->libType        = '文檔庫類型';
$lang->doc->custom         = '自定義文檔庫';
$lang->doc->customAB       = '自定義庫';
$lang->doc->createLib      = '創建文檔庫';
$lang->doc->allLibs        = '文檔庫列表';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->projectCommon}庫列表";
$lang->doc->showFiles      = '附件庫';
$lang->doc->editLib        = '編輯文檔庫';
$lang->doc->deleteLib      = '刪除文檔庫';
$lang->doc->fixedMenu      = '固定到菜單欄';
$lang->doc->removeMenu     = '從菜單欄移除';
$lang->doc->search         = '搜索';

/* 查詢條件列表 */
$lang->doc->allProduct     = '所有' . $lang->productCommon;
$lang->doc->allProject     = '所有' . $lang->projectCommon;

$lang->doc->libTypeList['product'] = $lang->productCommon . '文檔庫';
$lang->doc->libTypeList['project'] = $lang->projectCommon . '文檔庫';
$lang->doc->libTypeList['custom']  = '自定義文檔庫';

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

$lang->doc->aclList['open']    = '公開';
$lang->doc->aclList['custom']  = '自定義';
$lang->doc->aclList['private'] = '私有';

$lang->doc->typeList['html']     = '富文本';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = '連結';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->types['text'] = '文檔';
$lang->doc->types['url']  = '連結';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = '瀏覽方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['grid'] = '目錄';

$lang->doc->fastMenuList['byediteddate']  = '最近更新';
//$lang->doc->fastMenuList['visiteddate']   = '最近訪問';
$lang->doc->fastMenuList['openedbyme']    = '我的文檔';
$lang->doc->fastMenuList['collectedbyme'] = '我的收藏';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = '顯示附件庫';
$lang->doc->customObjectLibs['customFiles'] = '顯示自定義文檔庫';

$lang->doc->orderLib                       = '文檔庫排序';
$lang->doc->customShowLibs                 = '顯示設置';
$lang->doc->customShowLibsList['zero']     = '顯示空文檔的庫';
$lang->doc->customShowLibsList['children'] = '顯示子分類的文檔';
$lang->doc->customShowLibsList['unclosed'] = '只顯示未關閉的項目';

$lang->doc->confirmDelete      = "您確定刪除該文檔嗎？";
$lang->doc->confirmDeleteLib   = "您確定刪除該文檔庫嗎？";
$lang->doc->errorEditSystemDoc = "系統文檔庫無需修改。";
$lang->doc->errorEmptyProduct  = "沒有{$lang->productCommon}，無法創建文檔";
$lang->doc->errorEmptyProject  = "沒有{$lang->projectCommon}，無法創建文檔";
$lang->doc->errorMainSysLib    = "該系統文檔庫不能刪除！";
$lang->doc->accessDenied       = "您沒有權限訪問！";
$lang->doc->versionNotFount    = '該版本文檔不存在';
$lang->doc->noDoc              = '暫時沒有文檔。';
$lang->doc->cannotCreateOffice = '<p>對不起，企業版才能創建%s文檔。<p><p>試用企業版，請聯繫我們：4006-8899-23 &nbsp; 0532-86893032。</p>';
$lang->doc->notSetOffice       = "<p>創建%s文檔，需要配置<a href='%s' target='_parent'>Office轉換設置</a>。<p>";
$lang->doc->noSearchedDoc      = '沒有搜索到任何文檔。';
$lang->doc->noEditedDoc        = '您還沒有編輯任何文檔。';
$lang->doc->noOpenedDoc        = '您還沒有創建任何文檔。';
$lang->doc->noCollectedDoc     = '您還沒有收藏任何文檔。';

$lang->doc->noticeAcl['lib']['product']['default'] = '有所選產品訪問權限的用戶可以訪問。';
$lang->doc->noticeAcl['lib']['product']['custom']  = '有所選產品訪問權限或白名單裡的用戶可以訪問。';
$lang->doc->noticeAcl['lib']['project']['default'] = "有所選{$lang->projectCommon}訪問權限的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['project']['custom']  = "有所選{$lang->projectCommon}訪問權限或白名單裡的用戶可以訪問。";
$lang->doc->noticeAcl['lib']['custom']['open']     = '所有人都可以訪問。';
$lang->doc->noticeAcl['lib']['custom']['custom']   = '白名單的用戶可以訪問。';
$lang->doc->noticeAcl['lib']['custom']['private']  = '只有創建者自己可以訪問。';

$lang->doc->noticeAcl['doc']['open']    = '有文檔所屬文檔庫訪問權限的，都可以訪問。';
$lang->doc->noticeAcl['doc']['custom']  = '白名單的用戶可以訪問。';
$lang->doc->noticeAcl['doc']['private'] = '只有創建者自己可以訪問。';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = '相應的連結地址';

$lang->doclib = new stdclass();
$lang->doclib->name    = '文檔庫名稱';
$lang->doclib->control = '訪問控制';
$lang->doclib->group   = '分組';
$lang->doclib->user    = '用戶';
$lang->doclib->files   = '附件庫';
$lang->doclib->all     = '所有文檔庫';
$lang->doclib->select  = '選擇文檔庫';
$lang->doclib->project = $lang->projectCommon . '庫';
$lang->doclib->product = $lang->productCommon . '庫';

$lang->doclib->aclListA['default'] = '預設';
$lang->doclib->aclListA['custom']  = '自定義';

$lang->doclib->aclListB['open']    = '公開';
$lang->doclib->aclListB['custom']  = '自定義';
$lang->doclib->aclListB['private'] = '私有';

$lang->doclib->create['product'] = '創建' . $lang->productCommon . '文檔庫';
$lang->doclib->create['project'] = '創建' . $lang->projectCommon . '文檔庫';
$lang->doclib->create['custom']  = '創建自定義文檔庫';

$lang->doclib->main['product'] = $lang->productCommon . '主庫';
$lang->doclib->main['project'] = $lang->projectCommon . '主庫';

$lang->doclib->tabList['product'] = $lang->productCommon;
$lang->doclib->tabList['project'] = $lang->projectCommon;
$lang->doclib->tabList['custom']  = '自定義';

$lang->doclib->nameList['custom'] = '自定義文檔庫名稱';
