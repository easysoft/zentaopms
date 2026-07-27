<?php
declare(strict_types=1);
/**
 * The zen file of ppm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
class ppmZen extends ppm
{
    /**
     * 获取合并请求的代码库项目信息。
     * Get the code base project information of the merge request.
     *
     * @param  object    $repo
     * @access protected
     * @return array
     */
    protected function getAllProjects(object $repo): array
    {
        return $this->ppm->getGitFoxProjects((int)$repo->serviceHost, array($repo->serviceProject => $repo->serviceProject));
    }

    /**
     * 构造关联需求的搜索表单。
     * Build the search form of the associated story.
     *
     * @param  int       $ppmID
     * @param  int       $repoID
     * @param  string    $orderBy
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkStorySearchForm(int $ppmID, int $repoID, string $orderBy, int $queryID = 0)
    {
        if(empty($this->product))     $this->loadModel('product');
        if(empty($this->lang->story)) $this->app->loadLang('story');

        $storyStatusList = $this->lang->story->statusList;
        unset($storyStatusList['closed']);

        $this->config->product->search['actionURL']        = $this->createLink($this->app->rawModule, 'linkStory', "id={$ppmID}&repoID={$repoID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->product->search['queryID']          = $queryID;
        $this->config->product->search['style']            = 'simple';
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        unset($this->config->product->search['fields']['plan']);
        unset($this->config->product->search['params']['plan']);
        unset($this->config->product->search['fields']['module']);
        unset($this->config->product->search['params']['module']);
        unset($this->config->product->search['fields']['product']);
        unset($this->config->product->search['params']['product']);
        unset($this->config->product->search['fields']['branch']);
        unset($this->config->product->search['params']['branch']);
        unset($this->config->product->search['fields']['grade']);
        unset($this->config->product->search['params']['grade']);
        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构造关联bug的搜索表单。
     * Build the search form of the associated bug.
     *
     * @param  int       $ppmID
     * @param  int       $repoID
     * @param  string    $orderBy
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkBugSearchForm(int $ppmID, int $repoID, string $orderBy, int $queryID = 0)
    {
        if(empty($this->product)) $this->loadModel('product');

        $this->config->bug->search['actionURL'] = $this->createLink($this->app->rawModule, 'linkBug', "id={$ppmID}&repoID={$repoID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';

        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['params']['product']);
        unset($this->config->bug->search['fields']['plan']);
        unset($this->config->bug->search['params']['plan']);
        unset($this->config->bug->search['fields']['module']);
        unset($this->config->bug->search['params']['module']);
        unset($this->config->bug->search['fields']['execution']);
        unset($this->config->bug->search['params']['execution']);
        unset($this->config->bug->search['fields']['openedBuild']);
        unset($this->config->bug->search['params']['openedBuild']);
        unset($this->config->bug->search['fields']['resolvedBuild']);
        unset($this->config->bug->search['params']['resolvedBuild']);
        unset($this->config->bug->search['fields']['branch']);
        unset($this->config->bug->search['params']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 构造关联任务的搜索表单。
     * Build the search form of the associated task.
     *
     * @param  int       $ppmID
     * @param  int       $repoID
     * @param  string    $orderBy
     * @param  int       $queryID
     * @param  array     $productExecutions
     * @access protected
     * @return void
     */
    protected function buildLinkTaskSearchForm(int $ppmID, int $repoID, string $orderBy, int $queryID, array $productExecutions)
    {
        $this->config->execution->search['module']                        = 'ppmTask';
        $this->config->execution->search['actionURL']                     = $this->createLink($this->app->rawModule, 'linkTask', "id={$ppmID}&repoID={$repoID}&browseType=bySearch&param=myQueryID&orderBy={$orderBy}");
        $this->config->execution->search['queryID']                       = $queryID;
        $this->config->execution->search['params']['execution']['values'] = array_filter($productExecutions);

        unset($this->config->execution->search['fields']['module']);
        unset($this->config->execution->search['params']['module']);
        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
     * 处理关联任务页面分页数据。
     * Process the pagination data of the associated task page.
     *
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @param  array     $allTasks
     * @access protected
     * @return void
     */
    protected function processLinkTaskPager(int $recTotal, int $recPerPage, int $pageID, array $allTasks)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $pager->setRecTotal(count($allTasks));
        $pager->setPageTotal();
        if($pager->pageID > $pager->pageTotal) $pager->setPageID($pager->pageTotal);
        $count    = 1;
        $limitMin = ($pager->pageID - 1) * $pager->recPerPage;
        $limitMax = $pager->pageID * $pager->recPerPage;
        foreach($allTasks as $key => $task)
        {
            if($count <= $limitMin || $count > $limitMax) unset($allTasks[$key]);

            $count ++;
        }

        $this->view->allTasks = $allTasks;
        $this->view->pager    = $pager;
    }

    /**
     * 解析创建合并检查结果。
     * Parse the create merge check result.
     *
     * @param  object $mergeCheckMessage
     * @param  array $mergeRuleResult
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @access public
     * @return string
     */
    public function parseCreateCheckMsg(object|bool $mergeCheckMessage, array $mergeRuleResult, string $sourceBranch, string $targetBranch): string
    {
        if(empty($mergeCheckMessage) || empty($mergeRuleResult[$sourceBranch]) || empty($mergeRuleResult[$targetBranch])) return '';
        $checkSourceBranch = $mergeRuleResult[$sourceBranch]['result'];
        $checkTargetBranch = $mergeRuleResult[$targetBranch]['result'];

        $sourceBranchType = zget($mergeRuleResult[$sourceBranch], 'branchType', array());
        $targetBranchType = zget($mergeRuleResult[$targetBranch], 'branchType', array());
        $conflictFiles    = zget($mergeCheckMessage, 'conflictFiles', array());

        $message = '';
        if($mergeCheckMessage)
        {
            $message = zget($mergeCheckMessage, 'message', '');
            if($message) $message = sprintf($this->config->ppm->messageTips, $message);

            if(!empty($conflictFiles))
            {
                if($message) $message .= '</br>';
                $message .= sprintf($this->config->ppm->messageTips, $this->lang->ppm->checkConflicts);
            }
        }
        if(!$checkSourceBranch && !empty($sourceBranchType))
        {
            if($message) $message .= '</br>';
            $message .= sprintf($this->config->ppm->messageTips, sprintf($this->lang->ppm->checkSourceBranch, implode(',', $sourceBranchType)));
        }
        if(!$checkTargetBranch && !empty($targetBranchType))
        {
            if($message) $message .= '</br>';
            $message .= sprintf($this->config->ppm->messageTips, sprintf($this->lang->ppm->checkTargetBranch, implode(',', $targetBranchType)));
        }

        return $message;
    }

    /**
     * 获取合并检查结果。
     * Get merge check result.
     *
     * @param  object $ppm
     * @param  string $reviewResult
     * @param  array $issues
     * @access public
     * @return object
     */
    public function getCheckResult(object $ppm, string $reviewResult, array $issues = array(), string $mergeType = 'rebase'): object
    {
        $result = new stdClass();
        $mergeCheck = $this->ppm->merge($ppm->id, $mergeType, true);
        $result->mergeable     = zget($mergeCheck, 'mergeable', true);
        $result->conflictFiles = empty($mergeCheck) ? array() : zget($mergeCheck, 'conflictFiles', array());
        $result->message       = empty($result->message) ? '' : $result->message;

        $rule          = $this->loadModel('repobranchrule')->getRuleByBranchName($ppm->targetRepoID, $ppm->targetBranch);
        $ppmHandLeUser = empty($rule) ? array() : explode(',', zget($rule, 'ppmHandleUser', ''));
        $ppmHandLeUser = array_filter($ppmHandLeUser);
        $userCanMerge  = empty($ppmHandLeUser) || in_array($this->app->user->account, $ppmHandLeUser);

        if(!$userCanMerge)
        {
            $users = $this->loadModel('user')->getPairs('noletter');

            $canMergeUsers = array();
            foreach($ppmHandLeUser as $user)
            {
                $canMergeUsers[] = zget($users, $user);
            }
            $canMergeUsers   = sprintf($this->lang->ppm->notice->userNotAllowMerge, implode(',', $canMergeUsers));
            $result->message = $result->message ? $result->message . '; ' . $canMergeUsers : $canMergeUsers;
        }

        $reviewFlow = $this->loadModel('reporeviewflow')->getById(empty($rule) ? 0 : $rule->reviewFlowID);
        if(!empty($reviewFlow) && !empty($reviewFlow->definition->reviewFlow->issues))
        {
            $activeIssues = array();
            foreach($issues as $issue) if($issue->status == 'active') $activeIssues[] = $issue;

            $handleIssueFlow = $reviewFlow->definition->reviewFlow->issues;
            if(!empty($handleIssueFlow) && !empty($activeIssues))
            {
                if($handleIssueFlow->addressOption == 'allMustBeSolved')
                {
                    $result->message = $result->message ? $result->message . '; ' . $this->lang->ppm->notice->hasUnresolvedIssues : $this->lang->ppm->notice->hasUnresolvedIssues;
                }
                elseIf($handleIssueFlow->addressOption == 'specificMustBeSolved')
                {
                    $this->app->loadLang('bug');
                    foreach($activeIssues as $activeIssue)
                    {
                        if(in_array($activeIssue->type, $handleIssueFlow->mandatoryType))
                        {
                            $mandatoryType = array();
                            foreach($handleIssueFlow->mandatoryType as $type)
                            {
                                if(empty($this->lang->bug->typeList[$type])) continue;
                                $mandatoryType[] = zget($this->lang->bug->typeList, $type);
                            }
                            $hasUnresolvedSpecifiedIssues = sprintf($this->lang->ppm->notice->hasUnresolvedSpecifiedIssues, implode(',', $mandatoryType));
                            $result->message = $result->message ? $result->message . '; ' . $hasUnresolvedSpecifiedIssues : $hasUnresolvedSpecifiedIssues;
                            break;
                        }
                    }
                }
            }
        }

        $result->canMerge = empty($result->conflictFiles) && ($reviewResult == 'approved' || $ppm->reviewStatus == 'approved') && !$result->message && $result->mergeable;
        return $result;
    }
}
