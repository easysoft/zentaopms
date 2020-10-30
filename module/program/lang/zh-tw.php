<?php
/* Actions. */
$lang->program->createGuide          = '選擇項目模板';
$lang->program->PRJIndex             = '項目儀表盤';
$lang->program->PRJHome              = '項目主頁';
$lang->program->PRJCreate            = '創建項目';
$lang->program->PRJEdit              = '編輯項目';
$lang->program->PRJBatchEdit         = '批量編輯';
$lang->program->PRJBrowse            = '項目列表';
$lang->program->PRJAll               = '所有項目';
$lang->program->PRJStart             = '啟動項目';
$lang->program->PRJFinish            = '完成項目';
$lang->program->PRJSuspend           = '掛起項目';
$lang->program->PRJDelete            = '刪除項目';
$lang->program->PRJClose             = '關閉項目';
$lang->program->PRJActivate          = '激活項目';
$lang->program->PRJGroup             = '項目權限分組';
$lang->program->PRJCreateGroup       = '項目創建分組';
$lang->program->PRJEditGroup         = '項目編輯分組';
$lang->program->PRJCopyGroup         = '項目複製分組';
$lang->program->PRJManageView        = '項目維護視野';
$lang->program->PRJManagePriv        = '項目維護權限';
$lang->program->PRJManageMembers     = '項目團隊';
$lang->program->export               = '導出';
$lang->program->PRJManageGroupMember = '維護分組用戶';
$lang->program->PRJModuleSetting     = '項目集設置';
$lang->program->PRJModuleOpen        = '顯示項目集名';
$lang->program->PRJUpdateOrder       = '排序';
$lang->program->PRJSort              = '項目排序';
$lang->program->PRJWhitelist         = '項目白名單';
$lang->program->PRJAddWhitelist      = '項目添加白名單';
$lang->program->unbindWhielist       = '項目刪除白名單';
$lang->program->PRJManageProducts    = '項目關聯產品';

/* Fields. */
$lang->program->common             = '項目集';
$lang->program->stage              = '階段';
$lang->program->PRJName            = '項目名稱';
$lang->program->PRJModel           = '管理類型';
$lang->program->PRJCategory        = '項目類型';
$lang->program->PRJDesc            = '項目描述';
$lang->program->PRJCode            = '項目代號';
$lang->program->PRJCopy            = '複製項目';
$lang->program->begin              = '計劃開始日期';
$lang->program->end                = '計劃完成日期';
$lang->program->PRJStatus          = '項目狀態';
$lang->program->PRJPM              = '項目負責人';
$lang->program->PO                 = '產品負責人';
$lang->program->PRJBudget          = '項目預算';
$lang->program->PRJTemplate        = '項目模板';
$lang->program->PRJEstimate        = '預計';
$lang->program->PRJConsume         = '消耗';
$lang->program->PRJSurplus         = '剩餘';
$lang->program->PRJProgress        = '進度';
$lang->program->dateRange          = '起止時間';
$lang->program->to                 = '至';
$lang->program->realEnd            = '實際完成日期';
$lang->program->realBegan          = '實際開始日期';
$lang->program->bygrid             = '看板';
$lang->program->bylist             = '列表';
$lang->program->mine               = '我參與的';
$lang->program->setPlanduration    = '設置工期';
$lang->program->auth               = '權限控制';
$lang->program->durationEstimation = '工作量估算';
$lang->program->teamCount          = '投入人數';
$lang->program->leftStories        = '剩餘需求';
$lang->program->leftTasks          = '剩餘任務';
$lang->program->leftBugs           = '剩餘Bug';
$lang->program->PRJChildren        = '子項目';
$lang->program->PRJParent          = '父項目';
$lang->program->allStories         = '總需求';
$lang->program->doneStories        = '已完成';
$lang->program->allInput           = '項目總投入';
$lang->program->weekly             = '項目周報';
$lang->program->pv                 = 'PV';
$lang->program->ev                 = 'EV';
$lang->program->sv                 = 'SV%';
$lang->program->ac                 = 'AC';
$lang->program->cv                 = 'CV%';
$lang->program->PRJTeamCount       = '項目成員';
$lang->program->PRJLongTime        = '長期項目';

$lang->program->unitList['']       = '';
$lang->program->unitList['yuan']   = '元';
$lang->program->unitList['dollar'] = 'Dollars';

$lang->program->modelList['scrum']     = "Scrum";
$lang->program->modelList['waterfall'] = "瀑布";

$lang->program->PRJCategoryList['single']   = "單產品項目";
$lang->program->PRJCategoryList['multiple'] = "多產品項目";

$lang->program->PRJLifeTimeList['short'] = "短期";
$lang->program->PRJLifeTimeList['long']  = "長期";
$lang->program->PRJLifeTimeList['ops']   = "運維";

$lang->program->featureBar['all']       = '所有';
$lang->program->featureBar['doing']     = '進行中';
$lang->program->featureBar['wait']      = '未開始';
$lang->program->featureBar['suspended'] = '已掛起';
$lang->program->featureBar['closed']    = '已關閉';

$lang->program->PRJAclList['open']    = "公開(有項目視圖權限，即可訪問)";
$lang->program->PRJAclList['private'] = "私有(項目團隊成員和干係人可訪問)";

$lang->program->PGMPRJAclList['open']    = "全部公開（有項目視圖權限，即可訪問）";
$lang->program->PGMPRJAclList['program'] = "項目集內公開（所有上級項目集負責人和干係人，本項目團隊成員和干係人可訪問）";
$lang->program->PGMPRJAclList['private'] = "私有(項目團隊成員和干係人可訪問)";

$lang->program->PRJAuthList['extend'] = '繼承(取項目權限與組織權限的並集)';
$lang->program->PRJAuthList['reset']  = '重新定義(只取項目權限)';

$lang->program->statusList['wait']      = '未開始';
$lang->program->statusList['doing']     = '進行中';
$lang->program->statusList['suspended'] = '已掛起';
$lang->program->statusList['closed']    = '已關閉';

$lang->program->noPRJ             = '暫時沒有項目';
$lang->program->accessDenied      = '您無權訪問該項目！';
$lang->program->chooseProgramType = '選擇項目管理方式';
$lang->program->nextStep          = '下一步';
$lang->program->hoursUnit         = '%s工時';
$lang->program->membersUnit       = '%s人';
$lang->program->lastIteration     = '近期迭代';
$lang->program->ongoingStage      = '進行中的階段';
$lang->program->scrum             = 'Scrum';
$lang->program->waterfall         = '瀑布';
$lang->program->waterfallTitle    = '瀑布式項目管理';
$lang->program->cannotCreateChild = '該項目已經有實際的內容，無法直接添加子項目。您可以為當前項目創建一個父項目，然後在新的父項目下面添加子項目。';
$lang->program->hasChildren       = '該項目有子項目存在，不能刪除。';
$lang->program->confirmDelete     = "您確定要刪除嗎？";
$lang->program->emptyPM           = '暫無';
$lang->program->cannotChangeToCat = "該項目已經有實際的內容，無法修改為父項目";
$lang->program->cannotCancelCat   = "該項目下已經有子項目，無法取消父項目標記";
$lang->program->parentBeginEnd    = "父項目起止時間：%s ~ %s";
$lang->program->parentBudget      = "父項目預算：%s";
$lang->program->beginLetterParent = "父項目的開始日期：%s，開始日期不能小於父項目的開始日期";
$lang->program->endGreaterParent  = "父項目的完成日期：%s，完成日期不能大於父項目的完成日期";
$lang->program->beginGreateChild  = "子項目的最小開始日期：%s，父項目的開始日期不能大於子項目的最小開始日期";
$lang->program->endLetterChild    = "子項目的最大完成日期：%s，父項目的完成日期不能小於子項目的最大完成日期";
$lang->program->childLongTime     = "子項目中有長期項目，父項目也應該是長期項目";
$lang->program->readjustTime      = '重新調整項目起止時間';

$lang->program->PRJProgramTitle['0']    = '不顯示';
$lang->program->PRJProgramTitle['base'] = '只顯示一級項目集';
$lang->program->PRJProgramTitle['end']  = '只顯示最後一級項目集';

$lang->program->PRJAccessDenied      = '您無權訪問該項目！';
$lang->program->PRJChooseProgramType = '選擇項目管理方式';
$lang->program->scrumTitle           = '敏捷開發全流程項目管理';
$lang->program->PRJCannotCreateChild = '該項目已經有實際的內容，無法直接添加子項目。您可以為當前項目創建一個父項目，然後在新的父項目下面添加子項目。';
$lang->program->PRJHasChildren       = '該項目有子項目存在，不能刪除。';
$lang->program->PRJConfirmDelete     = "您確定刪除項目[%s]嗎？";
$lang->program->PRJCannotChangeToCat = "該項目已經有實際的內容，無法修改為父項目";
$lang->program->PRJCannotCancelCat   = "該項目下已經有子項目，無法取消父項目標記";
$lang->program->PRJParentBeginEnd    = "父項目起止時間：%s ~ %s";
$lang->program->PRJParentBudget      = "父項目預算：%s";
$lang->program->PRJBeginLetterParent = "父項目的開始日期：%s，開始日期不能小於父項目的開始日期";
$lang->program->PRJEndGreaterParent  = "父項目的完成日期：%s，完成日期不能大於父項目的完成日期";
$lang->program->PRJBeginGreateChild  = "項目的最小開始日期：%s，項目集的開始日期不能大於項目的最小開始日期";
$lang->program->PRJEndLetterChild    = "項目的最大完成日期：%s，項目集的完成日期不能小於項目的最大完成日期";
$lang->program->PRJChildLongTime     = "子項目中有長期項目，父項目也應該是長期項目";

/* Actions. */
$lang->program->PGMCommon            = '項目集';
$lang->program->PGMIndex             = '項目集主頁';
$lang->program->PGMCreate            = '添加項目集';
$lang->program->PGMCreateGuide       = '選擇項目模板';
$lang->program->PGMEdit              = '編輯項目集';
$lang->program->PGMBrowse            = '項目集列表';
$lang->program->PGMProduct           = '產品列表';
$lang->program->PGMProject           = '項目集項目列表';
$lang->program->PGMAll               = '所有項目集';
$lang->program->PGMStart             = '啟動項目集';
$lang->program->PGMFinish            = '完成項目集';
$lang->program->PGMSuspend           = '掛起項目集';
$lang->program->PGMDelete            = '刪除項目集';
$lang->program->PGMClose             = '關閉項目集';
$lang->program->PGMView              = '項目集概況';
$lang->program->PGMActivate          = '激活項目集';
$lang->program->PGMExport            = '導出';
$lang->program->PGMStakeholder       = '干係人列表';
$lang->program->createStakeholder    = '添加干係人';
$lang->program->unlinkStakeholder    = '刪除干係人';
$lang->program->importStakeholder    = '從父項目集導入';
$lang->program->PGMManageMembers     = '項目集團隊';

/* Fields. */
$lang->program->PGMName      = '項目集名稱';
$lang->program->PGMTemplate  = '項目集模板';
$lang->program->PGMCategory  = '項目集類型';
$lang->program->PGMDesc      = '項目集描述';
$lang->program->PGMCode      = '項目集代號';
$lang->program->PGMCopy      = '複製項目集';
$lang->program->PGMStatus    = '項目集狀態';
$lang->program->PGMPM        = '項目集負責人';
$lang->program->PGMBudget    = '項目集預算';
$lang->program->PGMProgress  = '項目進度';
$lang->program->PGMChildren  = '子項目集';
$lang->program->PGMParent    = '父項目集';
$lang->program->PGMAllInput  = '項目集總投入';
$lang->program->PGMTeamCount = '項目整合員';
$lang->program->PGMLongTime  = '長期項目';

$lang->program->noPGM         = '暫時沒有項目集';
$lang->program->PGMShowClosed = '顯示已關閉';

$lang->program->PGMAclList['open']    = "公開（有項目集視圖權限，即可訪問）";
$lang->program->PGMAclList['private'] = "私有（項目集負責人和干係人可訪問）";

$lang->program->subPGMAclList['open']    = "全部公開（有項目集視圖權限，即可訪問）";
$lang->program->subPGMAclList['program'] = "項目集內公開 (所有上級項目集負責人和干係人、本項目集負責人和干係人可訪問）";
$lang->program->subPGMAclList['private'] = "私有（本項目集負責人和干係人可訪問）";

$lang->program->PGMAuthList['extend'] = '繼承(取項目權限與組織權限的並集)';
$lang->program->PGMAuthList['reset']  = '重新定義(只取項目權限)';

$lang->program->PGMFeatureBar['all'] = '所有';
