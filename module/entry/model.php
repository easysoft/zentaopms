<?php
/**
 * The model file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class entryModel extends model
{
    /**
     * Get an entry by id. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return object 
     */
    public function getById($entryID)
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('id')->eq($entryID)->fetch();
    }

    /**
     * Get an entry by code. 
     * 
     * @param  string $code 
     * @access public
     * @return object 
     */
    public function getByCode($code)
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('deleted')->eq('0')->andWhere('code')->eq($code)->fetch();
    }

    /**
     * Get entry list. 
     * 
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_ENTRY)->where('deleted')->eq('0')->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get log list of an entry . 
     * 
     * @param  int    $id
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array 
     */
    public function getLogList($id, $orderBy = 'date_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_LOG)
            ->where('objectType')->eq('entry')
            ->andWhere('objectID')->eq($id)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Create an entry. 
     * 
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $entry = fixer::input('post')
            ->setDefault('ip', '*')
            ->setIF($this->post->allIP, 'ip', '*')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('allIP')
            ->get();

        if($this->post->freePasswd == 1) $this->config->entry->create->requiredFields = 'name, code, key';

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
     * Update an entry. 
     * 
     * @param  int    $entryID 
     * @access public
     * @return bool | array
     */
    public function update($entryID)
    {
        $oldEntry = $this->getById($entryID);

        $entry = fixer::input('post')
            ->setDefault('ip', '*')
            ->setIF($this->post->allIP, 'ip', '*')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('allIP')
            ->get();

        if($this->post->freePasswd == 1) $this->config->entry->edit->requiredFields = 'name, code, key';

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
     * Update called time.
     * 
     * @param  string $code 
     * @param  int    $time 
     * @access public
     * @return bool
     */
    public function updateTime($code, $time)
    {
        $this->dao->update(TABLE_ENTRY)->set('calledTime')->eq($time)->where('code')->eq($code)->exec();
        return !dao::isError();
    }

    /**
     * Save log of an entry.
     *
     * @params int    $entryID
     * @params string $url
     *
     * @access public
     * @return void
     */
    public function saveLog($entryID, $url)
    {
        $log = new stdclass();
        $log->objectType = 'entry';
        $log->objectID   = $entryID;
        $log->url        = $url;
        $log->date       = helper::now();

        $this->dao->insert(TABLE_LOG)->data($log)->exec();
        return !dao::isError();
    }
}
