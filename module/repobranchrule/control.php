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
        $this->loadModel('ci')->setMenu($repoID);

        $branchName = empty($branchRawName) ? $branchRawName : helper::safe64Decode($branchRawName);
        $branchType = $this->loadModel('repobranchtype')->getBranchTypeByID($branchTypeID);
        if(!$branchType)
        {
            $branchType = new stdClass();
            $branchType->key = '';
        }
        $repo        = $this->loadModel('repo')->getByID($repoID);
        $branchTypes = $this->loadModel('repobranchtype')->getBranchTypePairs($repoID);
        $originRule  = $this->repobranchrule->getBranchRule($branchTypeID, $repoID, $branchName);
        if(!$originRule)
        {
            $originRule = new stdClass();
            $originRule->id = 0;
        }

        if($_POST)
        {
            $formData = form::data($this->config->repobranchrule->form->setBranchRule)->get();
            $link = $branchTypeID ? $this->createLink('repobranchtype', 'browse', "repoID=$repoID") : $this->loadModel('repo')->createLink('browseBranch', "repoID=$repoID");

            /* 如果 $formData 里的字段全部为默认值，则返回保存成功，不保存数据。 */
            $allDefault = true;
            foreach($formData as $ruleDetail)
            {
                if(empty($ruleDetail)) continue;
                $option = $ruleDetail['option'];
                $value  = $ruleDetail['value'];

                /* 当选择 specify 时，校验 value 不能为空。 */
                if($option == 'specify')
                {
                    $value = array_filter($value);
                    if(empty($value)) return $this->sendError($this->lang->repobranchrule->specifyValueEmptyError);

                    $allDefault = false;
                }
            }
            if($allDefault)
            {
                if($originRule->id == 0) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
                $result = $this->repobranchrule->deleteBranchRule($originRule->id);
                if(!$result) $this->sendError($this->lang->repobranchrule->defaultValueRestoreError);
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $link));
            }

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
            }

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
        $this->view->users        = $this->repo->getRepoMembers($repo);
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
