<?php
declare(strict_types=1);
/**
 * The zen file of repobranchrule module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhiYuan Ma <mazhiyuan@chandao.com>
 * @package     repobranchrule
 * @link        https://www.zentao.net
 */
class repobranchruleZen extends repobranchrule
{
    /**
     * 构建分支规则数据。
     * Build branch rule data.
     *
     * @param  int    $typeID
     * @param  int    $repoID
     * @param  string $branchName
     * @param  object $data
     * @access public
     * @return object|bool
     */
    public function buildBranchRuleData(int $typeID, int $repoID, string $branchName, object $data): object|bool
    {
        if($data->radioForAllowCreate == 'hasPriv') $data->userAllowCreateGroup         = array();
        if($data->radioForAllowDelete == 'hasPriv') $data->userAllowDeleteGroup         = array();
        if($data->radioForAllowUpdate == 'hasPriv') $data->userAllowUpdateGroup         = array();
        if($data->radioForAllowForcePush == 'hasPriv') $data->userAllowForcePushGroup   = array();
        if($data->radioForAllowMergeFrom == 'all') $data->branchTypeAllowMergeFromGroup = array();
        if($data->radioForAllowMergeTo == 'all') $data->branchTypeAllowMergeToGroup     = array();

        $rule                = new stdClass();
        $rule->repo          = $repoID;
        $rule->branchType    = $typeID;
        $rule->branchName    = empty($typeID) ? $branchName : '';
        $rule->createUser    = !empty($typeID) ? implode(',', $data->userAllowCreateGroup) : '';
        $rule->deleteUser    = implode(',', $data->userAllowDeleteGroup);
        $rule->updateUser    = implode(',', $data->userAllowUpdateGroup);
        $rule->forcePushUser = implode(',', $data->userAllowForcePushGroup);
        $rule->sourceBranch  = implode(',', $data->branchTypeAllowMergeFromGroup);
        $rule->targetBranch  = implode(',', $data->branchTypeAllowMergeToGroup);

        return $rule;
    }
}