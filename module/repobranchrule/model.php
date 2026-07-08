<?php
declare(strict_types=1);
/**
 * The model file of repobranchrule module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhiYuan Ma <mazhiyuan@chandao.com>
 * @package     repobranchrule
 * @link        https://www.zentao.net
 */
class repobranchruleModel extends model
{
    /**
     * 检查分支创建的权限。
     * Check priv about branch create.
     *
     * @param  int    $repoID
     * @param  string $branchName
     * @param  string $operator
     * @access public
     * @return bool
     */
    public function checkPrivToCreateBranch(int $repoID, string $branchName, string $operator): bool
    {
        $branchTypes = $this->loadModel('repobranchtype')->getBranchTypeList($repoID);
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

        $rule = $this->getBranchRule($branchTypeID, $repoID, '');
        if($rule && !empty($rule->createUser))
        {
            return strpos(',' . $rule->createUser . ',', ',' . $operator . ',') !== false;
        }

        return true;
    }

    /**
     * 检查分支删除的权限。
     * Check priv about branch delete.
     *
     * @param  int    $repoID
     * @param  string $branchName
     * @param  string $operator
     * @access public
     * @return bool
     */
    public function checkPrivToDeleteBranch(int $repoID, string $branchName, string $operator): bool
    {
        // 根据仓库 ID 和分支名称查询分支级别的规则进行权限校验
        $rule = $this->getBranchRule(0, $repoID, $branchName);
        if($rule && !empty($rule->deleteUser))
        {
            return strpos(',' . $rule->deleteUser . ',', ',' . $operator . ',') !== false;
        }

        // 分支类型级别的规则进行权限校验
        $branchTypes = $this->loadModel('repobranchtype')->getBranchTypeList($repoID);
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

        $rule = $this->getBranchRule($branchTypeID, $repoID, '');
        if($rule && !empty($rule->deleteUser))
        {
            return strpos(',' . $rule->deleteUser . ',', ',' . $operator . ',') !== false;
        }

        return true;
    }

    /**
     * 查询仓库下指定规则类型的键值对。
     * Get pairs about branch rule.
     *
     * @param  int    $repoID
     * @param  string $key
     * @param  string $operate
     * @access public
     * @return array
     */
    public function getBranchRulePairs(int $repoID, string $key, string $operate): array
    {
        return $this->dao->select("$key,$operate")->from(TABLE_BRANCHRULESET)
            ->where('repo')->eq($repoID)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs($key, $operate);
    }

    /**
     * 获取指定的分支规则。
     * Get branch rule.
     *
     * @param  int    $typeID
     * @param  int    $repoID
     * @param  string $branchName
     * @access public
     * @return object|false
     */
    public function getBranchRule(int $typeID = 0, int $repoID = 0, string $branchName = ''): object|false
    {
        return $this->dao->select('*')->from(TABLE_BRANCHRULESET)
            ->where('repo')->eq($repoID)
            ->andWhere('branchType')->eq($typeID)
            ->andWhere('branchName')->eq($branchName)
            ->fetch();
    }

    /**
     * 创建分支规则。
     * Create branch rule.
     *
     * @param  int    $rule
     * @access public
     * @return bool
     */
    public function createBranchRule(object $rule): bool
    {
        $this->dao->insert(TABLE_BRANCHRULESET)->data($rule)->autoCheck()->exec();
        return !dao::isError();
    }

    /**
     * 更新分支规则。
     * Update branch rule.
     *
     * @param  int    $id
     * @param  object $rule
     * @access public
     * @return bool
     */
    public function updateBranchRule(int $id, object $rule): bool
    {
        $this->dao->update(TABLE_BRANCHRULESET)->data($rule)->where('id')->eq($id)->autoCheck()->exec();
        return !dao::isError();
    }

    /**
     * 删除分支规则。
     * Delete branch rule.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function deleteBranchRule(int $id): bool
    {
        $this->dao->delete()->from(TABLE_BRANCHRULESET)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * 获取指定仓库的所有分支规则。
     * Get all branch rules.
     *
     * @param  int $repoID
     * @param  array $branchTypes
     * @param  array $branchNames
     * @access public
     * @return array
     */
    public function getList(int $repoID, array $branchTypes = [], array $branchNames = []): array
    {
        return $this->dao->select('*')->from(TABLE_BRANCHRULESET)
            ->where('repo')->eq($repoID)
            ->beginIF(!empty($branchTypes))->andWhere('branchType')->in($branchTypes)->fi()
            ->beginIF(!empty($branchNames))->andWhere('branchName')->in($branchNames)->fi()
            ->fetchAll('id');
    }

    /**
     * 根据分支名称获取分支规则。
     * Get branch rule by branch name.
     *
     * @param  int    $repoID
     * @param  string $branchName
     * @access public
     * @return object|array
     */
    public function getRuleByBranchName(int $repoID, string $branchName): object|array
    {
        $branchRule = $this->getBranchRule(0, $repoID, $branchName);
        if(!empty($branchRule)) return $branchRule;

        $branchTypes = $this->loadModel('repobranchtype')->getByBranches($repoID, array($branchName));
        if(empty($branchTypes)) return array();

        $branchTypeID = isset($branchTypes[$branchName]) ? $branchTypes[$branchName]->id : 0;
        $branchRule   = $this->getBranchRule($branchTypeID, $repoID);
        if(empty($branchRule)) return array();

        return $branchRule;
    }
}
