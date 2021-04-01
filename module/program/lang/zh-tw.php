<?php
/* Fields. */
$lang->program->name      = '項目集名稱';
$lang->program->template  = '項目集模板';
$lang->program->category  = '項目集類型';
$lang->program->desc      = '項目集描述';
$lang->program->copy      = '複製項目集';
$lang->program->status    = '狀態';
$lang->program->PM        = '負責人';
$lang->program->budget    = '預算';
$lang->program->progress  = '項目進度';
$lang->program->children  = '子項目集';
$lang->program->parent    = '父項目集';
$lang->program->allInput  = '項目集總投入';
$lang->program->teamCount = '總人數';
$lang->program->longTime  = '長期';
$lang->program->view      = '項目集詳情';

/* Actions. */
$lang->program->common                  = '項目集';
$lang->program->index                   = '項目集主頁';
$lang->program->create                  = '添加項目集';
$lang->program->createGuide             = '選擇項目模板';
$lang->program->edit                    = '編輯項目集';
$lang->program->browse                  = '項目集列表';
$lang->program->product                 = '產品列表';
$lang->program->project                 = '項目集項目列表';
$lang->program->all                     = '所有項目集';
$lang->program->start                   = '啟動項目集';
$lang->program->finish                  = '完成項目集';
$lang->program->suspend                 = '掛起項目集';
$lang->program->delete                  = '刪除項目集';
$lang->program->close                   = '關閉項目集';
$lang->program->activate                = '激活項目集';
$lang->program->export                  = '導出';
$lang->program->stakeholder             = '干係人列表';
$lang->program->createStakeholder       = '添加干係人';
$lang->program->unlinkStakeholder       = '移除干係人';
$lang->program->batchUnlinkStakeholders = '批量移除干係人';
$lang->program->unlink                  = '移除';
$lang->program->moreProgram             = '更多項目集';
$lang->program->confirmBatchUnlink      = "您確定要批量移除這些干係人嗎？";
$lang->program->stakeholderType         = '干係人類型';
$lang->program->isStakeholderKey        = '關鍵干係人';
$lang->program->importStakeholder       = '從父項目集導入';
$lang->program->unbindWhitelist         = '移除白名單';
$lang->program->importStakeholder       = '從父項目集導入';
$lang->program->manageMembers           = '項目集團隊';
$lang->program->beyondParentBudget      = '已超出所屬項目集的剩餘預算';
$lang->program->parentBudget            = '所屬項目集剩餘預算：';
$lang->program->beginLetterParent       = "父項目集的開始日期：%s，開始日期不能小於父項目集的開始日期";
$lang->program->endGreaterParent        = "父項目集的完成日期：%s，完成日期不能大於父項目集的完成日期";
$lang->program->beginGreateChild        = "子項目集的最小開始日期：%s，父項目集的開始日期不能大於子項目集的最小開始日期";
$lang->program->endLetterChild          = "子項目的最大完成日期：%s，父項目的完成日期不能小於子項目的最大完成日期";
$lang->program->closeErrorMessage       = '存在子項目集或項目為未關閉狀態';
$lang->program->hasChildren             = '該項目集有子項目集或項目存在，不能刪除。';
$lang->program->confirmDelete           = "您確定要刪除嗎？";
$lang->program->readjustTime            = '重新調整項目集起止時間';

$lang->program->stakeholderTypeList['inside']  = '內部';
$lang->program->stakeholderTypeList['outside'] = '外部';

$lang->program->noProgram  = '暫時沒有項目集';
$lang->program->showClosed = '顯示已關閉';
$lang->program->tips       = '選擇了父項目集，則可關聯該父項目集下的產品。如果項目未選擇任何項目集，則系統會預設創建一個和該項目同名的產品並關聯該項目。';

$lang->program->endList[31]  = '一個月';
$lang->program->endList[93]  = '三個月';
$lang->program->endList[186] = '半年';
$lang->program->endList[365] = '一年';
$lang->program->endList[999] = '長期';

$lang->program->aclList['private'] = "私有（項目集負責人和干係人可訪問，干係人可後續維護）";
$lang->program->aclList['open']    = "公開（有項目集視圖權限，即可訪問）";

$lang->program->subAclList['private'] = "私有（本項目集負責人和干係人可訪問，干係人可後續維護）";
$lang->program->subAclList['open']    = "全部公開（有項目集視圖權限，即可訪問）";
$lang->program->subAclList['program'] = "項目集內公開（所有上級項目集負責人和干係人、本項目集負責人和干係人可訪問）";

$lang->program->subAcls['private'] = '私有';
$lang->program->subAcls['open']    = '全部公開';
$lang->program->subAcls['program'] = '項目集內公開';

$lang->program->authList['extend'] = '繼承 (取項目權限與組織權限的並集)';
$lang->program->authList['reset']  = '重新定義 (只取項目權限)';

$lang->program->statusList['wait']      = '未開始';
$lang->program->statusList['doing']     = '進行中';
$lang->program->statusList['suspended'] = '已掛起';
$lang->program->statusList['closed']    = '已關閉';

$lang->program->featureBar['all'] = '所有';
