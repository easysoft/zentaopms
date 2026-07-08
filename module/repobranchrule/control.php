<?php
declare(strict_types=1);
/**
 * The control file of repobranchrule module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhiYuan Ma <mazhiyuan@chandao.com>
 * @package     repobranchrule
 * @link        https://www.zentao.net
 */
class repobranchrule extends control
{
    /**
     * Common actions.
     *
     * @param  int    $repoID
     * @param  int    $objectID     projectID|executionID
     * @param  int    $spaceID
     * @access public
     * @return void
     */
    public function commonAction(int $repoID = 0, int $objectID = 0, int $spaceID = 0)
    {
        $tab   = $this->app->tab;
        $repos = $this->loadModel('repo')->getRepoPairs($tab, $objectID);

        if($tab == 'project')
        {
            $project = $this->loadModel('project')->getByID($objectID);
            if($project && $project->model === 'kanban') return $this->locate($this->createLink('project', 'index', "projectID=$objectID"));

            $this->loadModel('project')->setMenu($objectID);
            $this->view->projectID = $objectID;
        }
        elseif($tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            if($execution && $execution->type === 'kanban') return $this->locate($this->createLink('execution', 'kanban', "executionID=$objectID"));

            if($execution)
            {
                $features = $this->execution->getExecutionFeatures($execution);
                if(!$features['devops']) return print($this->locate($this->createLink('execution', 'task', "executionID=$objectID")));
            }

            $this->loadModel('execution')->setMenu($objectID);
            $this->view->executionID = $objectID;
        }
        elseif($tab != 'admin')
        {
            $this->repo->setMenu($repos, $repoID, $spaceID);
        }

        if(empty($repos) && !in_array(strtolower($this->methodName), array('create', 'edit', 'setrules', 'createrepo', 'import', 'maintain')))
        {
            $method = $this->app->tab == 'devops' ? 'maintain' : 'createRepo';
            return $this->locate(inLink($method, "objectID=$objectID"));
        }
    }

    /**
     * 配置分支规则。
     * Set a branch rule.
     *
     * @param  int       $branchTypeID
     * @param  int       $repoID
     * @param  string    $branchName
     * @param  string    $from
     * @access public
     * @return void
     */
    public function setBranchRule(int $branchTypeID = 0, int $repoID = 0, string $branchRawName = '', string $from = 'settings', int $objectID = 0)
    {
        // 根据 '分支' 或 '设置' 的操作入口不同，实现对应的菜单高亮定位
        if($from == 'branch')
        {
            $this->lang->devops->menu->branch['subModule']   = 'repobranchrule';
            $this->lang->devops->menu->settings['subModule'] = str_replace(',repobranchrule', '', $this->lang->devops->menu->settings['subModule']);
            unset($this->lang->devops->menu->settings['subMenu']->branchType['subModule']);
        }
        else
        {
            unset($this->lang->devops->menu->branch['subModule']);
            $this->lang->devops->menu->settings['subModule'] .= ',repobranchrule';
            $this->lang->devops->menu->settings['subMenu']->branchType['subModule'] = 'repobranchrule';
        }
        $this->commonAction($repoID, $objectID);

        $branchName  = empty($branchRawName) ? $branchRawName : helper::safe64Decode($branchRawName);
        $branchType  = $this->loadModel('repobranchtype')->getBranchTypeByID($branchTypeID);
        $repo        = $this->loadModel('repo')->getByID($repoID);
        $branchTypes = $this->repobranchtype->getBranchTypePairs($repoID);
        $originRule  = $this->repobranchrule->getBranchRule($branchTypeID, $repoID, $branchName);

        if($_POST)
        {
            $formData = form::data($this->config->repobranchrule->form->setBranchRule)->get();
            $link     = $branchTypeID ? $this->createLink('repobranchtype', 'browse', "repoID=$repoID") : $this->loadModel('repo')->createLink('browseBranch', "repoID=$repoID");

            /* 如果 $formData 里的字段全部为默认值，则返回保存成功，不保存数据。 */
            $formData = $this->checkRules($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!$formData)
            {
                if(!$originRule) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));

                $result = $this->repobranchrule->deleteBranchRule($originRule->id);
                if(dao::isError()) return $this->sendError(dao::getError());

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
            }

            $formData->repo       = $repoID;
            $formData->branchType = $branchTypeID;
            $formData->branchName = empty($branchTypeID) ? $branchName : '';

            if($originRule)
            {
                $formData->editedBy   = $this->app->user->account;
                $formData->editedDate = helper::now();
                $result = $this->repobranchrule->updateBranchRule($originRule->id, $formData);
                if(!$result) return $this->sendError($this->lang->fail);
            }
            else
            {
                $formData->createdBy   = $this->app->user->account;
                $formData->createdDate = helper::now();
                $result = $this->repobranchrule->createBranchRule($formData);
                if(!$result) return $this->sendError($this->lang->fail);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $this->view->title        = empty($branchTypeID) ? $branchName : $branchType->name;
        $this->view->title        = $this->lang->repobranchrule->setBranchRule;
        $this->view->from         = $from;
        $this->view->repoID       = $repoID;
        $this->view->branchName   = $branchRawName;
        $this->view->objectID     = $objectID;
        $this->view->branchTypeID = $branchTypeID;
        $this->view->ruleID       = zget($originRule, 'id', 0);
        $this->view->originRule   = !$originRule ? array() : $originRule;
        $this->view->users        = $this->repo->getRepoMembers($repo);
        $this->view->branchTypes  = $branchTypes;
        $this->view->reviewFlows  = $this->loadModel('reporeviewflow')->getPairs($repoID, 'enable');
        $this->display();
    }

    /**
     * 删除分支规则。
     * Ajax delete branch rule.
     *
     * @param  int    $branchTypeID
     * @param  int    $repoID
     * @param  string $branchName
     * @param  int    $ruleID
     * @access public
     * @return void
     */
    public function ajaxDeleteBranchRule(int $branchTypeID, int $repoID, string $branchName, int $ruleID, string $from, int $objectID = 0)
    {
        $link = $this->createLink('repobranchrule', 'setBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&from=$from&objectID=$objectID");
        if($ruleID == 0)
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $result = $this->repobranchrule->deleteBranchRule($ruleID);
        if(!$result) $this->sendError($this->lang->fail);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
    }
}
