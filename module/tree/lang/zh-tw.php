<?php
/**
 * The tree module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: zh-tw.php 4836 2013-06-19 05:39:40Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->tree                     = new stdclass();
$lang->tree->common             = '模組維護';
$lang->tree->edit               = '編輯模組';
$lang->tree->delete             = '刪除模組';
$lang->tree->browse             = '通用模組維護';
$lang->tree->browseTask         = '任務模組維護';
$lang->tree->manage             = '維護模組';
$lang->tree->fix                = '修正數據';
$lang->tree->manageProduct      = "維護{$lang->productCommon}視圖模組";
$lang->tree->manageExecution    = "維護{$lang->executionCommon}視圖模組";
$lang->tree->manageLine         = "維護{$lang->productCommon}綫";
$lang->tree->manageBug          = '維護測試視圖模組';
$lang->tree->manageCase         = '維護用例視圖模組';
$lang->tree->manageCaselib      = '維護用例庫模組';
$lang->tree->manageCustomDoc    = '維護文檔庫分類';
$lang->tree->manageApiChild     = '維護介面庫目錄';
$lang->tree->updateOrder        = '更新排序';
$lang->tree->manageChild        = '維護子模組';
$lang->tree->manageStoryChild   = '維護子模組';
$lang->tree->manageLineChild    = "維護{$lang->productCommon}綫";
$lang->tree->manageBugChild     = '維護Bug子模組';
$lang->tree->manageCaseChild    = '維護用例子模組';
$lang->tree->manageCaselibChild = '維護用例庫子模組';
$lang->tree->manageTaskChild    = "維護{$lang->executionCommon}子模組";
$lang->tree->syncFromProduct    = '複製模組';
$lang->tree->dragAndSort        = "拖放排序";
$lang->tree->sort               = "排序";
$lang->tree->addChild           = "增加子模組";
$lang->tree->confirmDelete      = '該模組及其子模組都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDeleteMenu  = '該目錄及其子目錄都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDelCategory = '該分類及其子分類都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDeleteLine  = "您確定刪除該{$lang->productCommon}綫嗎？";
$lang->tree->confirmRoot        = "模組的所屬{$lang->productCommon}修改，會關聯修改該模組下的{$lang->SRCommon}、Bug、用例的所屬{$lang->productCommon}，以及{$lang->executionCommon}和{$lang->productCommon}的關聯關係。該操作比較危險，請謹慎操作。是否確認修改？";
$lang->tree->confirmRoot4Doc    = "修改所屬文檔庫，會同時修改該分類下文檔的關聯關係。該操作比較危險，請謹慎操作。是否確認修改？";
$lang->tree->successSave        = '成功保存';
$lang->tree->successFixed       = '成功修正數據！';
$lang->tree->repeatName         = '模組名“%s”已經存在！';
$lang->tree->shouldNotBlank     = '模組名不能為空格！';
$lang->tree->host               = '主机';
$lang->tree->editHost           = '编辑主机分组';
$lang->tree->deleteHost         = '删除主机分组';
$lang->tree->manageHostChild    = '维护主机子分组';
$lang->tree->groupMaintenance   = '维护主机分组';
$lang->tree->groupName          = '分组名称';
$lang->tree->parentGroup        = '上级分组';
$lang->tree->childGroup         = '子分组';
$lang->tree->confirmDeleteHost  = '该分组及子分组都会被删除，您确定删除吗？';

$lang->tree->module       = '模組';
$lang->tree->name         = '模組名稱';
$lang->tree->line         = "{$lang->productCommon}綫名稱";
$lang->tree->cate         = '分類名稱';
$lang->tree->dir          = '目錄名稱';
$lang->tree->root         = '所屬根';
$lang->tree->branch       = '平台/分支';
$lang->tree->path         = '路徑';
$lang->tree->type         = '類型';
$lang->tree->parent       = '上級模組';
$lang->tree->parentCate   = '上級分類';
$lang->tree->child        = '子模組';
$lang->tree->parentGroup  = '上级分组';
$lang->tree->childGroup   = '子分组';
$lang->tree->subCategory  = '子分類';
$lang->tree->editCategory = '編輯分類';
$lang->tree->delCategory  = '刪除分類';
$lang->tree->lineChild    = "子{$lang->productCommon}綫";
$lang->tree->owner        = '負責人';
$lang->tree->order        = '排序';
$lang->tree->short        = '簡稱';
$lang->tree->all          = '所有模組';
$lang->tree->executionDoc = "{$lang->executionCommon}文檔";
$lang->tree->product      = "所屬{$lang->productCommon}";
