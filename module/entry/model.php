<?php
declare(strict_types=1);
/**
 * The model file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class entryModel extends model
{
    /**
     * 通过ID获取应用。
     * Get an entry by id.
     *
     * @param  int    $entryID
     * @access public
     * @return object|false
     */
    public function getById(int $entryID): object|false
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('id')->eq($entryID)->fetch();
    }

    /**
     * 通过代号获取应用。
     * Get an entry by code.
     *
     * @param  string $code
     * @access public
     * @return object|false
     */
    public function getByCode(string $code): object|false
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('deleted')->eq('0')->andWhere('code')->eq($code)->fetch();
    }

    /**
     * 通过密钥获取应用。
     * Get an entry by key.
     *
     * @param  string $key
     * @access public
     * @return object|false
     */
    public function getByKey(string $key): object|false
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('deleted')->eq('0')->andWhere('`key`')->eq($key)->fetch();
    }

    /**
     * 获取应用列表。
     * Get entry list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', object $pager = null): array
    {
        if(strpos($orderBy, 'desc_') !== false) $orderBy = str_replace('desc_', '`desc`_', $orderBy);
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('deleted')->eq('0')->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * 获取应用的日志列表。
     * Get log list of an entry .
     *
     * @param  int    $id
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLogs(int $id, string $orderBy = 'date_desc', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_LOG)
            ->where('objectType')->eq('entry')
            ->andWhere('objectID')->eq($id)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 创建一个应用。
     * Create an entry.
     *
     * @param  object   $entry
     * @access public
     * @return false|int
     */
    public function create(object $entry): false|int
    {
        if($entry->freePasswd == 1) $this->config->entry->create->requiredFields = 'name, code, key';

        $this->dao->insert(TABLE_ENTRY)->data($entry)
            ->batchCheck($this->config->entry->create->requiredFields, 'notempty')
            ->check('code', 'code')
            ->check('code', 'unique')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * 更新一个应用。
     * Update an entry.
     *
     * @param  int        $entryID
     * @param  object     $entry
     * @access public
     * @return false|array
     */
    public function update(int $entryID, object $entry): false|array
    {
        $oldEntry = $this->getById($entryID);

        if($entry->freePasswd == 1) $this->config->entry->edit->requiredFields = 'name, code, key';

        $this->dao->update(TABLE_ENTRY)->data($entry)
            ->batchCheck($this->config->entry->edit->requiredFields, 'notempty')
            ->check('code', 'code')
            ->check('code', 'unique', "id!=$entryID")
            ->autoCheck()
            ->where('id')->eq($entryID)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldEntry, $entry);
    }

    /**
     * 更新调用时间。
     * Update called time.
     *
     * @param  string $code
     * @param  int    $time
     * @access public
     * @return bool
     */
    public function updateCalledTime(string $code, int $time): bool
    {
        $this->dao->update(TABLE_ENTRY)->set('calledTime')->eq($time)->where('code')->eq($code)->exec();
        return !dao::isError();
    }

    /**
     * 保存日志。
     * Save log of an entry.
     *
     * @params int    $entryID
     * @params string $url
     * @access public
     * @return bool
     */
    public function saveLog(int $entryID, string $url): bool
    {
        $log = new stdclass();
        $log->objectType = 'entry';
        $log->objectID   = $entryID;
        $log->url        = $url;
        $log->date       = helper::now();

        $this->dao->insert(TABLE_LOG)->data($log)->exec();
        return !dao::isError();
    }

    /**
     * 判断操作是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $report
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $entry, string $action): bool
    {
        return true;
    }
}
