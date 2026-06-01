[1mdiff --git a/module/execution/control.php b/module/execution/control.php[m
[1mindex 0c33f7e877e..5993c4abc3e 100644[m
[1m--- a/module/execution/control.php[m
[1m+++ b/module/execution/control.php[m
[36m@@ -121,6 +121,8 @@[m [mclass execution extends control[m
      */[m
     public function task(int $executionID = 0, string $status = 'unclosed', int $param = 0, string $orderBy = '', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1, string $from = 'execution', int $blockID = 0)[m
     {[m
[32m+[m[32m        $this->loadModel('upgrade')->updateReviewedByField();[m
[32m+[m
         $this->app->loadLang('doc');[m
         if(($from == 'doc' || $from == 'ai') && empty($this->executions)) return $this->send(array('result' => 'fail', 'message' => $this->lang->doc->tips->noExecution));[m
 [m
[1mdiff --git a/module/upgrade/config/upgradeflow.php b/module/upgrade/config/upgradeflow.php[m
[1mindex 24cde7332b8..d867908e5cd 100644[m
[1m--- a/module/upgrade/config/upgradeflow.php[m
[1m+++ b/module/upgrade/config/upgradeflow.php[m
[36m@@ -150,6 +150,7 @@[m [mif($config->edition != 'open')[m
     $config->upgrade->execFlow['18_4_beta1']['functions'] = 'processDeployStepAction,updateBISQL,updatePivotStage';[m
     $config->upgrade->execFlow['20_4']['functions']      .= ',updateTaskRelationPriv';[m
     $config->upgrade->execFlow['21_7_9']['functions']    .= ',modifyProjectWorkflowGroup,upgradeAuditcl,upgradeProcessAndActivity,addWorkflowGroupOtherActivity,addDefaultDeliverableModule,upgradeDesignToDeliverable,buildinTestcaseStageDeliverable,upgradeDeliverable,buildinBaselineReview,upgradeReviewclCategory,upgradeBaselineObjects,upgradeReviewToDeliverable,upgradeBaseline,addDeliverablePrivs,upgradeStageAndPoint,upgradeObjectOfDecision,upgradeClosedFeature,parseDocFetcherURL,updateWorkflowGroupPriv,upgradeReportTemplateObjects';[m
[32m+[m[32m    $config->upgrade->execFlow['22_2']                   .= array('functions' => 'updateReviewedByField'); // 暂定22.2，发布之前要改版本号[m
 }[m
 [m
 if(in_array($this->config->edition, array('max', 'ipd'))) $config->upgrade->execFlow['18_7']['functions'] = 'processOldMetrics,processHistoryDataForMetric,metric-updateMetricDate';[m
[1mdiff --git a/module/upgrade/model.php b/module/upgrade/model.php[m
[1mindex 875f2eaadf2..27171012bb5 100644[m
[1m--- a/module/upgrade/model.php[m
[1m+++ b/module/upgrade/model.php[m
[36m@@ -13303,4 +13303,50 @@[m [mclass upgradeModel extends model[m
         }[m
         return !dao::isError();[m
     }[m
[31m-}[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * 为已开启审批流的工作流真实表补充 reviewedBy 字段，并从审批节点回填已审批人。[m
[32m+[m[32m     * Add reviewedBy field for workflow tables with approval enabled and backfill from approval nodes.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @access public[m
[32m+[m[32m     * @return bool[m
[32m+[m[32m     */[m
[32m+[m[32m    public function updateReviewedByField(): bool[m
[32m+[m[32m    {[m
[32m+[m[32m        if($this->config->edition == 'open') return true;[m
[32m+[m
[32m+[m[32m        $tables = $this->dao->select('`table`')->from(TABLE_WORKFLOW)[m
[32m+[m[32m            ->where('approval')->eq('enabled')[m
[32m+[m[32m            ->fetchPairs();[m
[32m+[m[32m        if(empty($tables)) return true;[m
[32m+[m
[32m+[m[32m        $nodes = $this->dao->select('approval,account')->from(TABLE_APPROVALNODE)[m
[32m+[m[32m            ->where('status')->eq('done')[m
[32m+[m[32m            ->andWhere('type')->eq('review')[m
[32m+[m[32m            ->orderBy('id')[m
[32m+[m[32m            ->fetchAll();[m
[32m+[m
[32m+[m[32m        $reviewedByMap = array();[m
[32m+[m[32m        foreach($nodes as $node)[m
[32m+[m[32m        {[m
[32m+[m[32m            if(!isset($reviewedByMap[$node->approval])) $reviewedByMap[$node->approval] = array();[m
[32m+[m[32m            $reviewedByMap[$node->approval][$node->account] = $node->account;[m
[32m+[m[32m        }[m
[32m+[m[32m        foreach($reviewedByMap as $approvalID => $accounts) $reviewedByMap[$approvalID] = implode(',', $accounts);[m
[32m+[m
[32m+[m[32m        foreach($tables as $table)[m
[32m+[m[32m        {[m
[32m+[m[32m            if(!$this->checkFieldsExists($table, 'reviewedBy')) $this->dbh->exec("ALTER TABLE `{$table}` ADD `reviewedBy` text NULL");[m
[32m+[m[32m            if(!$this->checkFieldsExists($table, 'approval')) continue;[m
[32m+[m
[32m+[m[32m            $approvalIDs = $this->dao->select('approval')->from($table)->where('approval')->gt(0)->fetchPairs();[m
[32m+[m[32m            foreach($approvalIDs as $approvalID)[m
[32m+[m[32m            {[m
[32m+[m[32m                if(!isset($reviewedByMap[$approvalID])) continue;[m
[32m+[m[32m                $this->dao->update($table)->set('reviewedBy')->eq($reviewedByMap[$approvalID])->where('approval')->eq($approvalID)->exec();[m
[32m+[m[32m            }[m
[32m+[m[32m        }[m
[32m+[m
[32m+[m[32m        return !dao::isError();[m
[32m+[m[32m    }[m
[32m+[m[32m}[m
\ No newline at end of file[m
