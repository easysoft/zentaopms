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
     * @return object|bool
     */
    protected function fetchBaseInfo(int $actionID): object|bool
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

    /**
     * 获取已经删除了的阶段列表。
     * Get deleted staged list.
     *
     * @param  array $stagePathList
     * @access protected
     * @return void
     */
    protected function getDeletedStagedList(array $stagePathList)
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stagePathList)->andWhere('deleted')->eq(1)->andWhere('type')->eq('stage')->orderBy('id_asc')->fetchAll('id');
    }

    /**
     * 根据执行id获取属性。
     * Get attribute by execution id.
     *
     * @param  int $id
     * @access protected
     * @return object
     */
    protected function getAttributeByID($id): object
    {
        return $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($id)->fetch('attribute');
    }
}
