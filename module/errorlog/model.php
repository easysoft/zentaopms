<?php
/**
 * The model file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 */
class errorlogModel extends model
{
    /**
     * 获取错误日志列表。
     * Get error log list.
     *
     * @param  string $query
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $query = '', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_ERRORLOG)
            ->where('1=1')
            ->beginIF(!empty($query))->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('', false);
    }

    /**
     * 根据ID获取错误日志。
     * Get an error log by id.
     *
     * @param  int $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        return $this->dao->select('*')->from(TABLE_ERRORLOG)->where('id')->eq($id)->fetch();
    }

    /**
     * 根据请求ID获取错误日志。
     * Get an error log by request id.
     *
     * @param  string $requestID
     * @access public
     * @return object|false
     */
    public function getByRequestID(string $requestID): object|false
    {
        return $this->dao->select('*')->from(TABLE_ERRORLOG)->where('requestID')->eq($requestID)->orderBy('id_desc')->limit(1)->fetch();
    }

    /**
     * 根据错误级别获取对应的展示类型。
     * Get display type by error level.
     *
     * @param  int $level
     * @access public
     * @return string
     */
    public function getLevelType(int $level): string
    {
        $fatalLevels   = E_ERROR | E_USER_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE | E_RECOVERABLE_ERROR;
        $warningLevels = E_WARNING | E_USER_WARNING | E_CORE_WARNING | E_COMPILE_WARNING;

        if($level & $fatalLevels)   return 'danger';
        if($level & $warningLevels) return 'warning';
        return 'primary';
    }

    /**
     * 根据ID删除错误日志。
     * Delete an error log by id.
     *
     * @param  int $id
     * @access public
     * @return bool
     */
    public function deleteByID(int $id): bool
    {
        $this->dao->delete()->from(TABLE_ERRORLOG)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * 根据ID批量删除错误日志。
     * Delete error logs by ids.
     *
     * @param  array $ids
     * @access public
     * @return bool
     */
    public function deleteByIDs(array $ids): bool
    {
        $ids = array_filter($ids, 'is_numeric');
        if(empty($ids)) return true;

        $this->dao->delete()->from(TABLE_ERRORLOG)->where('id')->in($ids)->exec();
        return !dao::isError();
    }

    /**
     * 获取有错误日志的模块列表。
     * Get modules which have error logs.
     *
     * @access public
     * @return array
     */
    public function getModulePairs(): array
    {
        return $this->dao->select('DISTINCT `module`')->from(TABLE_ERRORLOG)->where('module')->ne('')->orderBy('module')->fetchPairs('module', 'module');
    }

    /**
     * 删除指定日期之前的错误日志。
     * Delete error logs before the date.
     *
     * @param  string $date
     * @access public
     * @return bool
     */
    public function deleteByDate(string $date): bool
    {
        $this->dao->delete()->from(TABLE_ERRORLOG)->where('createdDate')->lt($date)->exec();
        return !dao::isError();
    }
}
