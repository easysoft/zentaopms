<?php
declare(strict_types=1);
class actionTao extends actionModel
{
    /**
     * 获取一个action的基础数据。
     * Fetch base info of a action.
     *
     * @param  int $actionID
     * @access protected
     * @return object|null
     */
    protected function fetchBaseInfo(int $actionID): object|null
    {
        return $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
    }

    /**
     * 获取一个基础对象的信息。
     * Get object base info.
     *
     * @param  string $table
     * @param  array  $queryParam
     * @param  string $field
     * @param  string $orderby
     * @access protected
     * @return object
     */
    protected function getObjectBaseInfo(string $table, array $queryParam, string $field = '*', string $orderby = ''): object
    {
        $querys = array_map(function($key, $query){return "`{$key}` = '{$query}'";}, array_keys($queryParam), $queryParam);
        return $this->dao->select($field)->from($table)->where(implode(' and ', $querys))->orderby($orderby)->fetch();
    }
}
