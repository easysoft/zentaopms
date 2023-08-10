<?php
/**
 * The model file of account module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     account
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class accountModel extends model
{
    /**
     * Get account by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->eq($id)->fetch();
    }

    /**
     * Get accounts by id list.
     *
     * @param  int    $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        return $this->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get account list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 't1.id_desc', $pager = null)
    {
        $query   = '';
        if($browseType == 'bysearch')
        {
            /* Concatenate the conditions for the query. */
            if($param)
            {
                $query = $this->loadModel('search')->getZinQuery($param);
                if($query)
                {
                    $this->session->set('accountQuery', $query->sql);
                    $this->session->set('accountForm', $query->form);
                }
            }
            else
            {
                if(!$this->session->accountQuery) $this->session->set('accountQuery', ' 1 = 1');
            }
            $query = $this->session->accountQuery;
        }

        $accounts = $this->dao->select('*')->from(TABLE_ACCOUNT)
            ->where('deleted')->eq('0')
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');
        foreach($accounts as $account) $account->createdBy = zget($users, $account->createdBy, '');

        return $accounts;
    }

    /**
     * Get account pairs
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_ACCOUNT)->where('deleted')->eq('0')->fetchPairs();
    }

    public function create()
    {
        $account = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_ACCOUNT)
            ->data($account)
            ->batchCheck($this->config->account->create->requiredFields, 'notempty')
            ->checkIf($account->email, 'email', 'email')
            ->checkIf($account->mobile, 'mobile', 'mobile')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * Update one account.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function update($id)
    {
        $now        = helper::now();
        $oldAccount = $this->getByID($id);
        $newAccount = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->get();

        $this->dao->update(TABLE_ACCOUNT)->data($newAccount)->autoCheck()
            ->batchCheck($this->config->account->edit->requiredFields, 'notempty')
            ->checkIf($newAccount->email, 'email', 'email')
            ->checkIf($newAccount->mobile, 'mobile', 'mobile')
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldAccount, $newAccount);
        return false;
    }
}
