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
     * 查询列表所需的联表字段。
     * Fields for the joined list query.
     *
     * @access protected
     * @return string
     */
    protected function getSelectFields(): string
    {
        return 't1.id, t1.requestID, t1.module, t1.method, t1.account, t1.url, t1.createdDate, t2.md5, t2.level, t2.message, t2.file, t2.line, t2.trace';
    }

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
        return $this->dao->select($this->getSelectFields())->from(TABLE_ERRORLOGREQ)->alias('t1')
            ->leftJoin(TABLE_ERRORLOG)->alias('t2')->on('t1.md5 = t2.md5')
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
        return $this->dao->select($this->getSelectFields())->from(TABLE_ERRORLOGREQ)->alias('t1')
            ->leftJoin(TABLE_ERRORLOG)->alias('t2')->on('t1.md5 = t2.md5')
            ->where('t1.id')->eq($id)
            ->fetch();
    }

    /**
     * 根据请求ID获取错误日志。
     * Get all error logs of one request.
     *
     * @param  string $requestID
     * @access public
     * @return array
     */
    public function getByRequestID(string $requestID): array
    {
        return $this->dao->select($this->getSelectFields())->from(TABLE_ERRORLOGREQ)->alias('t1')
            ->leftJoin(TABLE_ERRORLOG)->alias('t2')->on('t1.md5 = t2.md5')
            ->where('t1.requestID')->eq($requestID)
            ->orderBy('t1.id_asc')
            ->fetchAll('', false);
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
        $log = $this->dao->select('md5')->from(TABLE_ERRORLOGREQ)->where('id')->eq($id)->fetch();
        if($log)
        {
            $this->dao->delete()->from(TABLE_ERRORLOGREQ)->where('id')->eq($id)->exec();
            $this->deleteOrphanErrorLogs(array($log->md5));
        }
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

        $md5s = $this->dao->select('md5')->from(TABLE_ERRORLOGREQ)->where('id')->in($ids)->fetchPairs('md5', 'md5');
        $this->dao->delete()->from(TABLE_ERRORLOGREQ)->where('id')->in($ids)->exec();
        $this->deleteOrphanErrorLogs(array_keys($md5s));
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
        return $this->dao->select('DISTINCT `module`')->from(TABLE_ERRORLOGREQ)->where('module')->ne('')->orderBy('module')->fetchPairs('module', 'module');
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
        $md5s = $this->dao->select('md5')->from(TABLE_ERRORLOGREQ)->where('createdDate')->lt($date)->fetchPairs('md5', 'md5');
        $this->dao->delete()->from(TABLE_ERRORLOGREQ)->where('createdDate')->lt($date)->exec();
        $this->deleteOrphanErrorLogs(array_keys($md5s));
        return !dao::isError();
    }

    /**
     * 清理不再被任何请求引用的错误本体。
     * Delete orphan error bodies which are not referenced by any request record.
     *
     * @param  array $md5s
     * @access protected
     * @return void
     */
    protected function deleteOrphanErrorLogs(array $md5s): void
    {
        $md5s = array_values(array_unique(array_filter($md5s)));
        if(empty($md5s)) return;

        $referencedSQL = $this->dao->select('md5')->from(TABLE_ERRORLOGREQ)->where('md5')->in($md5s)->get();
        $this->dao->delete()->from(TABLE_ERRORLOG)->where('md5')->in($md5s)->andWhere('md5')->subNotIn($referencedSQL)->exec();
    }
}
