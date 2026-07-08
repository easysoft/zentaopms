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
        $rule = $this->repobranchrule->getRuleByBranchName($repoID, $branchName);
        if(empty($rule))
        {
            $defaultRule = new stdClass();
            $defaultRule->createUser    = array();
            $defaultRule->deleteUser    = array();
            $defaultRule->updateUser    = array();
            $defaultRule->forcePushUser = array();
            $defaultRule->sourceBranch  = array();
            $defaultRule->targetBranch  = array();
            $defaultRule->ppmCreateUser = array();
            $defaultRule->ppmHandleUser = array();
            $defaultRule->commitLine    = 0;
            $defaultRule->pushLine      = 0;
            $defaultRule->forceReview   = 0;
            $defaultRule->reviewFlowID  = 0;
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
        $rule->ppmCreateUser = !empty($rule->ppmCreateUser) ? explode(',', $rule->ppmCreateUser) : array();
        $rule->ppmHandleUser = !empty($rule->ppmHandleUser) ? explode(',', $rule->ppmHandleUser) : array();
        $rule->commitLine    = (int)$rule->commitLine;
        $rule->pushLine      = (int)$rule->pushLine;
        $rule->forceReview   = (int)$rule->forceReview;
        $rule->reviewFlowID  = (int)$rule->reviewFlowID;

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
