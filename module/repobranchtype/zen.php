<?php
declare(strict_types=1);
/**
 * The zen file of repobranchtype module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
class repobranchtypeZen extends repobranchtype
{
    /**
     * 验证分支类型数据。
     * Validate branch type data.
     *
     * @param  object $branchType
     * @param  int    $typeID  编辑时传入当前ID用于排除自身
     * @access protected
     * @return bool
     */
    protected function validateBranchType(object $branchType, int $typeID = 0): bool
    {
        $keyPattern = '/^[a-zA-Z][a-zA-Z0-9\/\-_.]*$/';
        if(!preg_match($keyPattern, $branchType->key))
        {
            dao::$errors['key'] = $this->lang->repobranchtype->error->keyFormat;
            return false;
        }

        $filteredPrefixes = array_filter($branchType->prefixes, function($v)
        {
            return !empty(trim($v));
        });

        /* 验证每个 prefix 的格式：只能包含字母、数字和 /-_. 且斜杠最多只能有一个。 */
        $prefixPattern  = '/^[a-zA-Z0-9\-_.]*\/?[a-zA-Z0-9\-_.]*$/';
        $uniquePrefixes = array();
        foreach($filteredPrefixes as $prefix)
        {
            if(!preg_match($prefixPattern, $prefix))
            {
                dao::$errors['prefixes'] = $this->lang->repobranchtype->error->prefixFormat;
                return false;
            }

            /* 检查斜杠数量（最多1个）。 */
            if(substr_count($prefix, '/') > 1)
            {
                dao::$errors['prefixes'] = $this->lang->repobranchtype->error->prefixSlash;
                return false;
            }

            /* 检查前缀是否重复（不区分大小写）。 */
            $lowerPrefix = strtolower($prefix);
            if(in_array($lowerPrefix, $uniquePrefixes))
            {
                dao::$errors['prefixes'] = $this->lang->repobranchtype->error->prefixDuplicate;
                return false;
            }
            $uniquePrefixes[] = $lowerPrefix;
        }

        return !dao::isError();
    }

    /**
     * 构建前缀显示HTML。
     * Build prefixes display HTML.
     *
     * @param  array $branchTypeList
     * @access protected
     * @return array
     */
    protected function buildPrefixesDisplay(array $branchTypeList): array
    {
        foreach($branchTypeList as &$branchType)
        {
            $branchType->prefixesDisplay = '';
        }
        return $branchTypeList;
    }
}
