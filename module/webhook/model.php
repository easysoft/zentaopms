<?php
/**
 * The model file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class webhookModel extends model
{
    /**
     * Get an webhook by id. 
     * 
     * @param  int    $webhookID 
     * @access public
     * @return array
     */
    public function getById($webhookID)
    {
        return $this->dao->select('*')->from(TABLE_WEBHOOK)->where('id')->eq($webhookID)->fetch();
    }

    /**
     * Get webhook list. 
     * 
     * @param  string $orderBy
     * @param  obejct $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_WEBHOOK)->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Create an webhook. 
     * 
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $webhook = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_WEBHOOK)->data($webhook)
            ->batchCheck($this->config->webhook->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * Update an webhook. 
     * 
     * @param  int    $webhookID 
     * @access public
     * @return bool | array
     */
    public function update($webhookID)
    {
        $oldEntry = $this->getById($webhookID);

        $webhook = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_WEBHOOK)->data($webhook)
            ->batchCheck($this->config->webhook->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($webhookID)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldEntry, $webhook);
    }

    /**
     * Delete an webhook. 
     * 
     * @param  int    $webhookID 
     * @param  int    $null 
     * @access public
     * @return bool
     */
    public function delete($webhookID, $null = null)
    {
        $this->dao->delete()->from(TABLE_WEBHOOK)->where('id')->eq($webhookID)->exec();
        return !dao::isError();
    }
}
