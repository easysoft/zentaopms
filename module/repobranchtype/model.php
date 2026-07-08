<?php
declare(strict_types=1);
/**
 * The model file of repobranchtype module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
class repobranchtypeModel extends model
{
    /**
     * 创建分支类型。
     * Create branch type.
     *
     * @param  int    $gitfoxID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function apiCreateBranchType(int $repoID, object $formData): object|false
    {
        /* 确保 prefixes 是数组。 */
        $prefixes = $formData->prefixes;
        if(is_string($prefixes)) $prefixes = explode(',', $prefixes);
        if(!is_array($prefixes)) $prefixes = array();

        /* 过滤空前缀并重新索引数组。 */
        $filteredPrefixes = array_values(array_filter($prefixes, function($v)
        {
            return !empty(trim($v));
        }));

        /* 组装单个分支类型对象。 */
        $branchType = array(
            'name'     => $formData->name,
            'key'      => $formData->key,
            'prefixes' => $filteredPrefixes,
            'desc'     => $formData->desc ?? ''
        );

        /* 包装为数组(后端需要数组格式)。 */
        $requestData = array($branchType);

        /* 调用 GitFox API 创建分支类型。 */
        $result = $this->loadModel('gitfox')->apiCreateBranchType($repoID, $requestData);

        return $result;
    }

    /**
     * 删除分支类型。
     * Delete branch type.
     *
     * @param  object|null $repo
     * @param  int         $typeID
     * @access public
     * @return bool
     */
    public function apiDeleteBranchType(?object $repo, int $typeID): bool
    {
        $repoID = $repo ? (int)$repo->id : 0;
        $result = $this->loadModel('gitfox')->apiDeleteBranchType($repoID, $typeID);
        return (bool)$result;
    }

    /**
     * 更新分支类型。
     * Update branch type.
     *
     * @param  object|null $repo
     * @param  int         $typeID
     * @param  object      $formData
     * @access public
     * @return bool
     */
    public function apiUpdateBranchType(?object $repo, int $typeID, object $formData): bool
    {
        /* 确保 prefixes 是数组。 */
        $prefixes = $formData->prefixes;
        if(is_string($prefixes)) $prefixes = explode(',', $prefixes);
        if(!is_array($prefixes)) $prefixes = array();

        /* 过滤空前缀并重新索引数组。 */
        $filteredPrefixes = array_values(array_filter($prefixes, function($v)
        {
            return !empty(trim($v));
        }));

        /* 组装分支类型对象（更新接口不需要 key 字段）。 */
        $branchType = array(
            'name'     => $formData->name,
            'prefixes' => $filteredPrefixes,
            'desc'     => $formData->desc ?? ''
        );

        /* 如果 repo 为空，从分支类型获取 repoID。 */
        $existingType = $this->getBranchTypeByID($typeID);
        if(empty($existingType)) return false;

        if(empty($repo) && $existingType->repo != 0)
        {
            $repo = $this->loadModel('repo')->getByID($existingType->repo);
            if(empty($repo)) return false;
        }

        /* 调用 GitFox API 更新分支类型。 */
        $repoID = $repo ? (int)$repo->id : 0;
        $result = $this->loadModel('gitfox')->apiUpdateBranchType($repoID, $typeID, $branchType);

        return (bool)$result;
    }

    /**
     * 导入分支类型。
     * Import branch types from global templates (repo=0) to specific repo.
     *
     * @param  object $repo
     * @param  array  $branchTypeIDs
     * @access public
     * @return bool
     */
    public function importBranchTypes(object $repo, array $branchTypeIDs): bool
    {
        if(empty($branchTypeIDs)) return false;

        /* 批量查询分支类型。 */
        $branchTypeList = $this->getBranchTypeByIDs($branchTypeIDs);

        /* 检查查询结果数量是否与请求的 ID 数量一致。 */
        if(count($branchTypeList) != count($branchTypeIDs)) return false;

        /* 组装数据，只导入 repo=0 的全局模板。 */
        $branchTypes = array();
        foreach($branchTypeList as $branchType)
        {
            if($branchType->repo != 0) continue;

            $branchTypes[] = array(
                'name'     => $branchType->name,
                'key'      => $branchType->key,
                'prefixes' => $branchType->prefixes,
                'desc'     => $branchType->desc ?? ''
            );
        }

        if(empty($branchTypes)) return false;

        /* 调用 GitFox API 批量创建分支类型。 */
        $repoID = $repo ? (int)$repo->id : 0;
        if($repoID == 0) return false;

        $result = $this->loadModel('gitfox')->apiCreateBranchType($repoID, $branchTypes);

        return (bool)$result;
    }

    /**
     * 获取指定代码库的分支类型键值对。
     * Get branch type pairs.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getBranchTypePairs(int $repoID = 0): array
    {
        return $this->dao->select('id, name')
            ->from(TABLE_BRANCHTYPE)
            ->where('deleted')->eq(0)
            ->beginIF($repoID)->andWhere('repo')->eq($repoID)->fi()
            ->fetchPairs('id', 'name');
    }

    /**
     * 获取分支类型列表(支持分页和搜索)。
     * Get branch type list with pagination and search.
     *
     * @param  int     $repoID
     * @param  string  $name      按名称搜索
     * @param  string  $key       按键值搜索
     * @param  string  $prefix    按前缀搜索(会搜索JSON数组中是否包含该前缀)
     * @param  string  $orderBy
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getBranchTypeList(int $repoID = 0, string $name = '', string $key = '', string $prefix = '', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $branchTypes = $this->dao->select('*')->from(TABLE_BRANCHTYPE)
            ->where('deleted')->eq(0)
            ->andWhere('repo')->eq($repoID)
            ->beginIF($name)->andWhere('name')->like("%{$name}%")->fi()
            ->beginIF($key)->andWhere('`key`')->like("%{$key}%")->fi()
            ->beginIF($prefix)->andWhere('prefix')->like("%{$prefix}%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        if(empty($branchTypes)) return array();

        /* 解析 prefix 字段(逗号分隔字符串转数组)。 */
        foreach($branchTypes as $branchType)
        {
            $branchType->prefixes = $this->parsePrefixToArray($branchType->prefix);
        }

        return $branchTypes;
    }

    /**
     * 根据ID获取分支类型详情。
     * Get branch type by ID.
     *
     * @param  int $typeID
     * @access public
     * @return object|null
     */
    public function getBranchTypeByID(int $typeID): ?object
    {
        $branchType = $this->dao->select('*')->from(TABLE_BRANCHTYPE)
            ->where('id')->eq($typeID)
            ->fetch();

        if(!$branchType) return null;

        /* 解析 prefix 字段(逗号分隔字符串转数组)。 */
        $branchType->prefixes = $this->parsePrefixToArray($branchType->prefix);

        return $branchType;
    }

    /**
     * 批量获取分支类型。
     * Get branch types by IDs.
     *
     * @param  array $typeIDs
     * @access public
     * @return array
     */
    public function getBranchTypeByIDs(array $typeIDs): array
    {
        if(empty($typeIDs)) return array();

        $branchTypes = $this->dao->select('*')->from(TABLE_BRANCHTYPE)
            ->where('id')->in($typeIDs)
            ->fetchAll('id');

        if(empty($branchTypes)) return array();

        /* 解析每个分支类型的 prefix 字段。 */
        foreach($branchTypes as &$branchType)
        {
            $branchType->prefixes = $this->parsePrefixToArray($branchType->prefix);
        }

        return $branchTypes;
    }

    /**
     * 根据repoID获取分支类型列表。
     * Get branch types by repoID.
     *
     * @param  int $repoID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getBranchTypeByRepoID(int $repoID, string $orderBy = 'id_desc'): array
    {
        $branchTypes = $this->dao->select('*')->from(TABLE_BRANCHTYPE)
            ->where('repo')->eq($repoID)
            ->orderBy($orderBy)
            ->fetchAll('id', false);

        if(!$branchTypes) return array();

        /* 解析 prefix 字段(逗号分隔字符串转数组)。 */
        foreach($branchTypes as $branchType)
        {
            $branchType->prefixes = $this->parsePrefixToArray($branchType->prefix);
        }

        return $branchTypes;
    }

    /**
     * 解析prefix字段为数组。
     * Parse prefix field to array.
     *
     * @param  string $prefix
     * @access protected
     * @return array
     */
    protected function parsePrefixToArray(string $prefix): array
    {
        if(empty($prefix)) return array();

        return array_filter(array_map('trim', explode(',', $prefix)));
    }

    /**
     * 根据分支列表获取对应的分支类型。
     * Get branch type by branch list.
     *
     * @param  int   $repoID
     * @param  array $branchList
     * @access public
     * @return array
     */
    public function getByBranches($repoID, array $branchList): array
    {
        $branchTypes = $this->getBranchTypeByRepoID($repoID);
        if(empty($branchTypes)) return array();

        $branchTypeList = array();
        foreach($branchTypes as $branchType)
        {
            $prefixes = $branchType->prefixes;
            foreach($prefixes as $prefix)
            {
                foreach($branchList as $branch)
                {
                    if(strpos($branch, $prefix) === 0)
                    {
                        $branchTypeList[$branch] = $branchType;
                    }
                }
            }
        }

        return $branchTypeList;
    }
}
