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
    public function setBranchRule(int $branchTypeID = 0, int $repoID = 0, string $branchRawName = '', string $from = 'settings', bool $isDefault = false)
    {
        // 根据 '分支' 或 '设置' 的操作入口不同，实现对应的菜单高亮定位
        // if($from == 'branch')
        // {
        //     $this->lang->devops->menu->branch['alias']   = 'setbranchrule';
        //     $this->lang->devops->menu->settings['alias'] = str_replace(',setbranchrule', '', $this->lang->devops->menu->settings['alias']);
        //     unset($this->lang->devops->menu->settings['subMenu']->branchType['alias']);
        // }
        // else
        // {
        //     unset($this->lang->devops->menu->branch['alias']);
        //     $this->lang->devops->menu->settings['alias'] .= ',setbranchrule';
        //     $this->lang->devops->menu->settings['subMenu']->branchType['alias'] = 'setbranchrule';
        // }
        // // $this->commonAction($repoID);
        // $this->loadModel('ci')->setMenu($repoID);

        $branchName = empty($branchRawName) ? $branchRawName : helper::safe64Decode($branchRawName);
        $branchType = $this->loadModel('repo')->getBranchTypeByID($branchTypeID);
        if(!$branchType)
        {
            $branchType = new stdClass();
            $branchType->key = '';
        }
        $repo        = $this->loadModel('repo')->getByID($repoID);
        $users       = $repo->acl == 'open' ? $this->loadModel('devopsspace')->getSpaceMembers($repo->space, true) : $this->repo->getRepoUsers($repoID);
        $members     = !empty($users) ? $this->loadModel('user')->getListByAccounts(array_keys($repo->members)) : array();
        $branchTypes = $this->loadModel('repo')->getBranchTypePairs($repoID);
        $originRule  = $this->repobranchrule->getBranchRule($branchTypeID, $repoID, $branchName);
        if(!$originRule)
        {
            $originRule = new stdClass();
            $originRule->id = 0;
        }

        if($_POST)
        {
            $formData = form::data($this->config->repobranchrule->form->setBranchRule)->get();

            $rule = $this->repobranchruleZen->buildBranchRuleData($branchTypeID, $repoID, $branchName, $formData);
            if(dao::isError()) $this->sendError(dao::getError());
            $rule->editedBy   = $this->app->user->account;
            $rule->editedDate = helper::now();

            if($originRule->id == 0)
            {
                $rule->createdBy   = $this->app->user->account;
                $rule->createdDate = helper::now();
                $result = $this->repobranchrule->createBranchRule($rule);
                if(!$result) $this->sendError($this->lang->fail);
            }
            else
            {
                $result = $this->repobranchrule->updateBranchRule($originRule->id, $rule);
                if(!$result) $this->sendError($this->lang->fail);
                $this->loadModel('action')->create('branchRule', $originRule->id, 'edited');
            }

            $link = ($branchTypeID == 0) ? $this->loadModel('repo')->createLink('browseBranch', "repoID=$repoID") : $this->loadModel('repo')->createLink('browsebranchtype', "repoID=$repoID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $this->view->title        = empty($branchTypeID) ? $branchName : $branchType->name;
        $this->view->from         = $from;
        $this->view->repoID       = $repoID;
        $this->view->branchName   = $branchRawName;
        $this->view->isDefault    = $isDefault;
        $this->view->branchTypeID = $branchTypeID;
        $this->view->ruleID       = $originRule->id;
        $this->view->originRule   = $originRule;
        $this->view->users        = !empty($members) ? array_column($members, 'realname', 'account') : array();
        $this->view->branchTypes  = $branchTypes;
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
    public function ajaxDeleteBranchRule(int $branchTypeID, int $repoID, string $branchName, int $ruleID, string $from, bool $isDefault)
    {
        $link = $this->createLink('repobranchrule', 'setBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&from=$from&isDefault=$isDefault");
        if($ruleID == 0)
        {
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
        }

        $result = $this->repobranchrule->deleteBranchRule($ruleID);
        if(!$result) $this->sendError($this->lang->fail);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
    }
}