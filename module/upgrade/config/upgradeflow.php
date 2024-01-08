<?php
global $app;
$appRoot = str_replace(DS, '/', $app->getAppRoot());

$config->upgrade->execFlow = array();
$config->upgrade->execFlow['1_0beta']     = array('functions' => 'updateCompany');
$config->upgrade->execFlow['1_2']         = array('functions' => 'updateUBB,updateNL1_2');
$config->upgrade->execFlow['1_3']         = array('functions' => 'updateNL1_3,updateTasks');
$config->upgrade->execFlow['2_2']         = array('functions' => 'updateCases,updateActivatedCountOfBug');
$config->upgrade->execFlow['3_0_beta1']   = array('functions' => 'updateAction,setOrderData');
$config->upgrade->execFlow['3_3']         = array('functions' => 'updateTaskAssignedTo');
$config->upgrade->execFlow['4_0_beta2']   = array('functions' => 'updateProjectType,updateEstimatePriv');
$config->upgrade->execFlow['4_0_1']       = array('functions' => 'addPriv4_0_1');
$config->upgrade->execFlow['4_1']         = array('functions' => 'addPriv4_1,processTaskFinish,deleteCompany');
$config->upgrade->execFlow['5_2_1']       = array('functions' => 'mergeProjectGoalAndDesc');
$config->upgrade->execFlow['6_0_beta1']   = array('functions' => 'toLowerTable,fixBugOSInfo,fixTaskFinishedBy');
$config->upgrade->execFlow['6_0']         = array('functions' => 'fixDataIndex');
$config->upgrade->execFlow['7_1']         = array('functions' => 'initOrder');
$config->upgrade->execFlow['7_3']         = array('functions' => 'adjustPriv7_4_beta');
$config->upgrade->execFlow['8_0_1']       = array('functions' => 'addPriv8_1');
$config->upgrade->execFlow['8_1_3']       = array('functions' => 'addPriv8_2_beta,adjustConfigSectionAndKey');
$config->upgrade->execFlow['8_2_6']       = array('functions' => 'adjustDocModule,moveDocContent,adjustPriv8_3');
$config->upgrade->execFlow['8_3_1']       = array('functions' => 'renameMainLib,adjustPriv8_4');
$config->upgrade->execFlow['9_0_beta']    = array('functions' => 'adjustPriv9_0');
$config->upgrade->execFlow['9_0']         = array('functions' => 'fixProjectProductData');
$config->upgrade->execFlow['9_0_1']       = array('functions' => 'addBugDeadlineToCustomFields,adjustPriv9_0_1');
$config->upgrade->execFlow['9_1_2']       = array('functions' => 'processCustomMenus,adjustPriv9_2');
$config->upgrade->execFlow['9_4']         = array('functions' => 'adjustPriv9_4');
$config->upgrade->execFlow['9_5_1']       = array('functions' => 'initProjectStoryOrder');
$config->upgrade->execFlow['9_6']         = array('functions' => 'fixDatatableColsConfig');
$config->upgrade->execFlow['9_6_1']       = array('functions' => 'addLimitedGroup');
$config->upgrade->execFlow['9_6_3']       = array('functions' => 'changeLimitedName,adjustPriv9_7,changeStoryWidth');
$config->upgrade->execFlow['9_7']         = array('functions' => 'changeTeamFields,moveData2Notify');
$config->upgrade->execFlow['9_8']         = array('functions' => 'fixTaskFinishedInfo');
$config->upgrade->execFlow['9_8_1']       = array('functions' => 'fixTaskAssignedTo,fixProjectClosedInfo,resetProductLine');
$config->upgrade->execFlow['9_8_2']       = array('functions' => 'addUniqueKeyToTeam');
$config->upgrade->execFlow['9_8_3']       = array('functions' => 'adjustPriv10_0_alpha');
$config->upgrade->execFlow['10_0_alpha']  = array('functions' => 'fixProjectStatisticBlock');
$config->upgrade->execFlow['10_0']        = array('functions' => 'fixStorySpecTitle,removeUnlinkPriv');
$config->upgrade->execFlow['10_1']        = array('xxsqls' => "$appRoot/db/xuanxuan.sql");
$config->upgrade->execFlow['10_3_1']      = array('functions' => 'removeCustomMenu');
$config->upgrade->execFlow['10_4']        = array('functions' => 'changeTaskParentValue');
$config->upgrade->execFlow['10_6']        = array('functions' => 'initXuanxuan', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.1.0.sql,$appRoot/db/upgradexuanxuan2.2.0.sql");
$config->upgrade->execFlow['11_1']        = array('functions' => 'syncXXHttpsConfig', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.3.0.sql");
$config->upgrade->execFlow['11_2']        = array('functions' => 'processDocLibAcl');
$config->upgrade->execFlow['11_3']        = array('functions' => 'addPriv11_4');
$config->upgrade->execFlow['11_4_1']      = array('functions' => 'addPriv11_5,updateXX_11_5', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.4.0.sql,$appRoot/db/upgradexuanxuan2.5.0.sql");
$config->upgrade->execFlow['11_6_1']      = array('functions' => 'adjustWebhookType,adjustPriv11_6_2');
$config->upgrade->execFlow['11_6_3']      = array('functions' => 'adjustPriv11_6_4');
$config->upgrade->execFlow['11_6_5']      = array('functions' => 'fixGroupAcl,fixBugTypeList,adjustPriv11_7,rmEditorAndTranslateDir,setConceptSetted', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.5.7.sql,$appRoot/db/upgradexuanxuan3.0.0-beta.1.sql,$appRoot/db/upgradexuanxuan3.0-beta3.sql");
$config->upgrade->execFlow['11_7']        = array('functions' => 'adjustPriv12_0');
$config->upgrade->execFlow['12_0_1']      = array('functions' => 'importRepoFromConfig');
$config->upgrade->execFlow['12_1']        = array('xxsqls' => "$appRoot/db/upgradexuanxuan3.1.1.sql");
$config->upgrade->execFlow['12_3_3']      = array('functions' => 'addPriv12_3_3,processImport2TaskBugs');
$config->upgrade->execFlow['12_4_2']      = array('functions' => 'fixFromCaseVersion,initStoryOfPlan');
$config->upgrade->execFlow['12_4_4']      = array('functions' => 'adjustPriv12_5');
$config->upgrade->execFlow['12_5_3']      = array('functions' => 'adjustWhitelistOfProject,adjustWhitelistOfProduct,adjustPriv15_0');
$config->upgrade->execFlow['15_0_rc1']    = array('functions' => 'adjustUserView');
$config->upgrade->execFlow['15_0_rc3']    = array('functions' => 'updateLibType,updateRunCaseStatus,fix4TaskLinkProject,fixExecutionTeam', 'xxsqls' => "$appRoot/db/upgradexuanxuan3.3.sql,$appRoot/db/upgradexuanxuan4.0.sql,$appRoot/db/upgradexuanxuan4.0.beta2.sql,$appRoot/db/upgradexuanxuan4.0.beta3.sql");
$config->upgrade->execFlow['15_0']        = array('functions' => 'adjustBugOfProject,processBuildTable,updateProductVersion');
$config->upgrade->execFlow['15_0_2']      = array('functions' => 'uniqueProjectAdmin');
$config->upgrade->execFlow['15_2']        = array('functions' => 'processGitlabRepo,processStoryFileType,processProductDoc,adjustPriv15_3');
$config->upgrade->execFlow['15_3']        = array('functions' => 'adjustBugRequired,processTesttaskDate,processDocTempContent');
$config->upgrade->execFlow['15_4']        = array('xxsqls' => "$appRoot/db/upgradexuanxuan4.2.sql,$appRoot/db/upgradexuanxuan4.4.sql");
$config->upgrade->execFlow['15_5']        = array('functions' => 'addDefaultKanbanPri');
$config->upgrade->execFlow['15_7_1']      = array('functions' => 'updateObjectBranch,updateProjectStories,updateProjectLinkedBranch');
$config->upgrade->execFlow['16_0_beta1']  = array('functions' => 'createDemoAPI');
$config->upgrade->execFlow['16_1']        = array('functions' => 'moveKanbanData');
$config->upgrade->execFlow['16_2']        = array('functions' => 'updateSpaceTeam,updateDocField');
$config->upgrade->execFlow['16_4']        = array('functions' => 'updateActivatedDate,completionAllSQL,updateGroup4Lite', 'xxsqls' => "$appRoot/db/upgradexuanxuan4.6.sql,$appRoot/db/upgradexuanxuan5.1.sql");
$config->upgrade->execFlow['16_5']        = array('functions' => 'updateProjectStatus,updateStoryReviewer');
$config->upgrade->execFlow['17_0_beta1']  = array('xxsqls' => "$appRoot/db/upgradexuanxuan5.5.sql");
$config->upgrade->execFlow['17_0_beta2']  = array('functions' => 'changeStoryNeedReview');
$config->upgrade->execFlow['17_0']        = array('functions' => 'replaceSetLanePriv,updateProjectData');
$config->upgrade->execFlow['17_1']        = array('functions' => 'moveProjectAdmins,addStoryViewPriv', 'xxsqls' => "$appRoot/db/upgradexuanxuan5.6.sql", 'xxfunctions' => 'xuanAddMessageIndexColumns,xuanReindexMessages,xuanUpdateLastReadMessageIndex,xuanFixChatsWithoutLastRead');
$config->upgrade->execFlow['17_3']        = array('functions' => 'processBugLinkBug');
$config->upgrade->execFlow['17_4']        = array('functions' => 'rebuildFULLTEXT,updateSearchIndex', 'xxfunctions' => 'addAdminInviteField');
$config->upgrade->execFlow['17_5']        = array('functions' => 'updateOSAndBrowserOfBug,addURPriv,updateStoryStatus,syncCase2Project');
$config->upgrade->execFlow['17_6']        = array('functions' => 'updateStoryFile,convertTaskteam,convertEstToEffort,fixWeeklyReport,xuanSetOwnedByForGroups,xuanRecoverCreatedDates,xuanSetPartitionedMessageIndex');
$config->upgrade->execFlow['17_6_1']      = array('functions' => 'updateProductView');
$config->upgrade->execFlow['17_6_2']      = array('xxsqls' => "$appRoot/db/upgradexuanxuan6.4.sql");
$config->upgrade->execFlow['17_8']        = array('functions' => 'xuanSetMuteForHiddenGroups,xuanNotifyGroupHiddenUsers,initShadowBuilds', 'xxsqls' => "$appRoot/db/upgradexuanxuan6.5.sql");
$config->upgrade->execFlow['18_0_beta1']  = array('xxsqls' => "$appRoot/db/upgradexuanxuan6.6.sql");
$config->upgrade->execFlow['18_0_beta3']  = array('functions' => 'updateMyBlocks');
$config->upgrade->execFlow['18_1']        = array('functions' => 'insertMixStage');
$config->upgrade->execFlow['18_2']        = array('functions' => 'setting-setSN');
$config->upgrade->execFlow['18_3']        = array('functions' => 'changeBookToCustomLib,createDefaultDimension,convertDocCollect,addBIUpdateMark');
$config->upgrade->execFlow['18_4_alpha1'] = array('functions' => 'setURSwitchStatus');
$config->upgrade->execFlow['18_4']        = array('functions' => 'fixMissedFlowField', 'xxsqls' => "$appRoot/db/upgradexuanxuan7.1.sql,$appRoot/db/upgradexuanxuan7.2.beta.sql");
$config->upgrade->execFlow['18_5']        = array('functions' => 'installIPD,updatePivotFieldsType,addCreateAction4Story');
$config->upgrade->execFlow['18_6']        = array('functions' => 'removeProductLineRequired');
$config->upgrade->execFlow['18_7']        = array('functions' => 'processHistoryDataForMetric,metric-updateMetricDate');
$config->upgrade->execFlow['18_8']        = array('functions' => 'upgradeTesttaskMembers,deleteGeneralReportBlock,stopOldCron');
$config->upgrade->execFlow['18_9']        = array('functions' => 'addDefaultTraincoursePriv,renameBIModule,migrateXuanClientSettings');
$config->upgrade->execFlow['18_10']       = array('functions' => 'revertStoryCustomFields');

if(!empty($config->isINT))
{
    unset($config->upgrade->execFlow['11_1']);
    unset($config->upgrade->execFlow['12_1']);
    unset($config->upgrade->execFlow['15_4']);
    unset($config->upgrade->execFlow['11_4_1']['xxsqls']);
    unset($config->upgrade->execFlow['11_6_5']['xxsqls']);
    unset($config->upgrade->execFlow['15_0_rc3']['xxsqls']);

    $config->upgrade->execFlow['11_4_1']['functions'] = 'addPriv11_5';
    $config->upgrade->execFlow['16_4']['xxsqls']      = "$appRoot/db/xuanxuan.sql";
}

if($config->edition == 'max')
{
    $config->upgrade->execFlow['16_5']['functions']       .= ',moveResult2Node';
    $config->upgrade->execFlow['17_2']['functions']        = 'addReviewIssusApprovalData';
    $config->upgrade->execFlow['18_0_beta1']['functions']  = 'initReviewEfforts';
}

if($config->edition != 'open')
{
    $config->upgrade->execFlow['17_0_beta1']['functions']  = 'processViewFields';
    $config->upgrade->execFlow['17_0']['functions']       .= ',processFlowPosition';
    $config->upgrade->execFlow['17_4']['functions']       .= ',processCreatedInfo,processCreatedBy,updateApproval,addDefaultRuleToWorkflow,processReviewLinkages,addFlowActions,addFlowFields';
    $config->upgrade->execFlow['17_4']['params']['addFlowActions'] = array('biz7.4');
    $config->upgrade->execFlow['17_4']['params']['addFlowFields']  = array('biz7.4');
    $config->upgrade->execFlow['17_6_2']['functions']     = 'processFeedbackModule';
    $config->upgrade->execFlow['18_3']['functions']      .= ',processDataset,processChart,processReport,processDashboard';
    $config->upgrade->execFlow['18_4_beta1']['functions'] = 'processDeployStepAction,updateBISQL,updatePivotStage';
}

if(in_array($this->config->edition, array('max', 'ipd'))) $config->upgrade->execFlow['18_7']['functions'] = 'processOldMetrics,processHistoryDataForMetric,metric-updateMetricDate';

$config->upgrade->execFlow['pro3_2_1']    = array('functions' => 'recordFinished');
$config->upgrade->execFlow['pro3_3']      = array('functions' => 'toLowerTable', 'params' => array('toLowerTable' => array('pro')));
$config->upgrade->execFlow['pro4_0']      = array('functions' => 'fixRepo');
$config->upgrade->execFlow['pro7_0_beta'] = array('functions' => 'fixReport');
$config->upgrade->execFlow['pro8_8']      = array('functions' => 'checkURAndSR');
$config->upgrade->execFlow['pro10_0_2']   = array('functions' => 'fixReportLang');
$config->upgrade->execFlow['pro10_2']     = array('functions' => 'addDefaultKanbanPri');

$config->upgrade->execFlow['biz2_3_1']     = array('functions' => 'adjustFeedbackViewData');
$config->upgrade->execFlow['biz3_4']       = array('functions' => 'importBuildinModules');
$config->upgrade->execFlow['biz3_5_alpha'] = array('functions' => 'addSubStatus');
$config->upgrade->execFlow['biz3_5_beta']  = array('functions' => 'processSubTables');
$config->upgrade->execFlow['biz3_6']       = array('functions' => 'addDefaultActions,importCaseLibModule,deleteBuildinFields');
$config->upgrade->execFlow['biz3_6_1']     = array('functions' => 'addWorkflowActions,processWorkflowLayout,processWorkflowLabel,processWorkflowCondition');
$config->upgrade->execFlow['biz3_7']       = array('functions' => 'processWorkflowFields');
$config->upgrade->execFlow['biz3_7_2']     = array('functions' => 'processFlowStatus');
$config->upgrade->execFlow['biz4_0_1']     = array('functions' => 'addMailtoFields');
$config->upgrade->execFlow['biz4_0_3']     = array('functions' => 'updateAttendStatus,initView4WorkflowDatasource');
$config->upgrade->execFlow['biz5_0']       = array('functions' => 'adjustPrivBiz5_0_1');
$config->upgrade->execFlow['biz5_0_1']     = array('functions' => 'updateWorkflow4Execution');
$config->upgrade->execFlow['biz5_2']       = array('functions' => 'addDefaultKanbanPri');
$config->upgrade->execFlow['biz5_3_1']     = array('functions' => 'processFeedbackField,addFileFields,addReportActions');
$config->upgrade->execFlow['biz6_4']       = array('functions' => 'importLiteModules');

if(!empty($config->isINT))
{
    $config->upgrade->execFlow['biz3_0'] = array('xxfunctions' => 'syncXXHttpsConfig', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.3.0.sql");
    $config->upgrade->execFlow['biz3_2_1'] = array('functions' => 'updateXX_11_5', 'xxsqls' => "$appRoot/db/upgradexuanxuan2.4.0.sql,$appRoot/db/upgradexuanxuan2.5.0.sql");
    $config->upgrade->execFlow['biz3_6_1']['xxsqls'] = "$appRoot/db/upgradexuanxuan3.1.1.sql";
}

$config->upgrade->execFlow['max2_2'] = array('functions' => 'addDefaultKanbanPri');
