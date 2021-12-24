<?php
$lang->branch->common = '分支';
$lang->branch->manage = '分支管理';
$lang->branch->sort   = '分支排序';
$lang->branch->delete = '分支刪除';
$lang->branch->add    = '添加';

$lang->branch->manageTitle = '%s管理';
$lang->branch->all         = '所有';
$lang->branch->main        = '主幹';

$lang->branch->edit              = '編輯%s';
$lang->branch->editAction        = '編輯分支';
$lang->branch->activate          = '激活';
$lang->branch->activateAction    = '激活分支';
$lang->branch->close             = '關閉';
$lang->branch->closeAction       = '關閉分支';
$lang->branch->create            = '新建%s';
$lang->branch->createAction      = '新建分支';
$lang->branch->merge             = '合併';
$lang->branch->batchEdit         = '批量編輯';
$lang->branch->defaultBranch     = '預設分支';
$lang->branch->setDefault        = '設為預設分支';
$lang->branch->setDefaultAction  = '設置預設分支';
$lang->branch->mergeTo           = '合併@branch@到';
$lang->branch->mergeBranch       = '合併@branch@';
$lang->branch->mergeBranchAction = '合併分支';

$lang->branch->id          = 'ID';
$lang->branch->product     = '所屬產品';
$lang->branch->name        = '%s名稱';
$lang->branch->status      = '狀態';
$lang->branch->createdDate = '創建時間';
$lang->branch->closedDate  = '關閉時間';
$lang->branch->desc        = '%s描述';
$lang->branch->order       = '排序';
$lang->branch->deleted     = '已刪除';
$lang->branch->closed      = '已關閉';
$lang->branch->default     = '預設';

$lang->branch->confirmDelete     = '是否刪除該@branch@？';
$lang->branch->confirmSetDefault = '請確認是否需要將該@branch@設置為預設@branch@，設置成功後計劃和發佈列表將預設選中預設@branch@。';
$lang->branch->canNotDelete      = '該@branch@下已經有數據，不能刪除！';
$lang->branch->nameNotEmpty      = '名稱不能為空！';
$lang->branch->confirmClose      = '是否關閉該@branch@？';
$lang->branch->confirmActivate   = '是否激活該@branch@？';
$lang->branch->existName         = '@branch@名稱已存在';
$lang->branch->mergedMain        = '主幹不支持被合併。';
$lang->branch->mergeTips         = '合併@branch@後，會將@branch@下面對應的發佈、計劃、模組、需求、Bug、用例都合併到新的@branch@下。';
$lang->branch->targetBranchTips  = '您可以將其合併到已有的一個@branch@，也可以合併到主幹，也可以新創建一個@branch@。';
$lang->branch->confirmMerge      = '"mergedBranch"的數據將被合併到"targetBranch",請確認是否要執行分支合併操作，合併後數據將不可再恢復！';

$lang->branch->noData     = '暫時沒有分支。';
$lang->branch->mainBranch = '產品預設主幹%s。';

$lang->branch->statusList = array();
$lang->branch->statusList['active'] = '激活';
$lang->branch->statusList['closed'] = '已關閉';

$lang->branch->featureBar['manage']['all']    = '所有';
$lang->branch->featureBar['manage']['active'] = '激活';
$lang->branch->featureBar['manage']['closed'] = '已關閉';
