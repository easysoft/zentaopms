<?php
/**
 * The branchrules entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhiYuan Ma <mazhiyuan@zentao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class branchRulesEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? $header['Authorization'] : '';
        $entry  = $this->loadModel('entry')->getByCode('gitfox');

        if(empty($token) || empty($entry->key) || $token != $entry->key)
        {
            return $this->sendError(401, 'Unauthorized');
        }

        $branchName = $this->param('branchName');
        $repoID     = $this->param('id');

        $this->loadModel('repobranchrule');
        $this->loadModel('repobranchtype');
        $rule = $this->repobranchrule->getBranchRule(0, $repoID, $branchName);
        if(!$rule)
        {
            $branchTypes   = $this->repobranchtype->getBranchTypeList($repoID);
            $branchTypeID  = 0;
            if(!empty($branchTypes))
            {
                foreach($branchTypes as $branchType)
                {
                    if(empty($branchType->prefixes)) continue;

                    foreach($branchType->prefixes as $prefix)
                    {
                        if(strpos($branchName, $prefix) === 0)
                        {
                            $branchTypeID = $branchType->id;
                            break 2;
                        }
                    }
                }
            }
            $rule = $this->repobranchrule->getBranchRule($branchTypeID, $repoID, '');
        }

        if(!$rule)
        {
            $defaultRule = new stdClass();
            $defaultRule->createUser    = array();
            $defaultRule->deleteUser    = array();
            $defaultRule->updateUser    = array();
            $defaultRule->forcePushUser = array();
            $defaultRule->sourceBranch  = array();
            $defaultRule->targetBranch  = array();
            if(strpos($branchName, 'archived/') === 0)
            {
                $repo = $this->loadModel('repo')->fetchByID($repoID);
                if($repo->branchArchivable)
                {
                    $defaultRule->updateUser = array('archiveManager');
                    $defaultRule->deleteUser = array('archiveManager');
                }
            }
            return $this->send(200, $defaultRule);
        }

        /* 将用户字段 'admin,testuser' 解析为 array('admin', 'testuser')。 */
        $rule->createUser    = !empty($rule->createUser) ? explode(',', $rule->createUser) : array();
        $rule->deleteUser    = !empty($rule->deleteUser) ? explode(',', $rule->deleteUser) : array();
        $rule->updateUser    = !empty($rule->updateUser) ? explode(',', $rule->updateUser) : array();
        $rule->forcePushUser = !empty($rule->forcePushUser) ? explode(',', $rule->forcePushUser) : array();

        /* 将 sourceBranch 和 targetBranch 字段的 '1,2' 解析为 ID 数组，再获取分支类型的 prefix 合并。 */
        $sourceBranchIDs = !empty($rule->sourceBranch) ? explode(',', $rule->sourceBranch) : array();
        $targetBranchIDs = !empty($rule->targetBranch) ? explode(',', $rule->targetBranch) : array();

        $rule->sourceBranch = array();
        $rule->targetBranch = array();

        if(!empty($sourceBranchIDs))
        {
            $sourceBranchTypes = $this->repobranchtype->getBranchTypeByIDs($sourceBranchIDs);
            foreach($sourceBranchTypes as $branchType)
            {
                if(!empty($branchType->prefixes)) $rule->sourceBranch = array_merge($rule->sourceBranch, $branchType->prefixes);
            }
        }

        if(!empty($targetBranchIDs))
        {
            $targetBranchTypes = $this->repobranchtype->getBranchTypeByIDs($targetBranchIDs);
            foreach($targetBranchTypes as $branchType)
            {
                if(!empty($branchType->prefixes)) $rule->targetBranch = array_merge($rule->targetBranch, $branchType->prefixes);
            }
        }

        return $this->send(200, $rule);
    }
}
