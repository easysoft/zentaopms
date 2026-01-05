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
        if(empty($data->allowCreate) || $data->allowCreate['option'] == 'hasPriv') $data->allowCreate['value']          = array();
        if(empty($data->allowDelete) || $data->allowDelete['option'] == 'hasPriv') $data->allowDelete['value']          = array();
        if(empty($data->allowUpdate) || $data->allowUpdate['option'] == 'hasPriv') $data->allowUpdate['value']          = array();
        if(empty($data->allowForcePush) || $data->allowForcePush['option'] == 'hasPriv') $data->allowForcePush['value'] = array();
        if(empty($data->allowMergeFrom) || $data->allowMergeFrom['option'] == 'all') $data->allowMergeFrom['value']     = array();
        if(empty($data->allowMergeTo) || $data->allowMergeTo['option'] == 'all') $data->allowMergeTo['value']           = array();

        $rule                = new stdClass();
        $rule->repo          = $repoID;
        $rule->branchType    = $typeID;
        $rule->branchName    = empty($typeID) ? $branchName : '';
        $rule->createUser    = !empty($typeID) ? implode(',', $data->allowCreate['value']) : '';
        $rule->deleteUser    = implode(',', $data->allowDelete['value']);
        $rule->updateUser    = implode(',', $data->allowUpdate['value']);
        $rule->forcePushUser = implode(',', $data->allowForcePush['value']);
        $rule->sourceBranch  = implode(',', $data->allowMergeFrom['value']);
        $rule->targetBranch  = implode(',', $data->allowMergeTo['value']);

        return $rule;
    }
}