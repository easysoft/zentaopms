<?php
declare(strict_types=1);
/**
 * The model file of account module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
    public function getByID(int $id): object|false
    {
        return $this->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->eq($id)->fetch();
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
    public function getList(string $browseType = 'all', string $param = '', string $orderBy = 'id_desc', object|null $pager = null)
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            /* Concatenate the conditions for the query. */
            if(!$this->session->accountQuery) $this->session->set('accountQuery', ' 1 = 1');
            if($param)
            {
                $query = $this->loadModel('search')->getQuery((int)$param);
                if($query)
                {
                    $this->session->set('accountQuery', $query->sql);
                    $this->session->set('accountForm', $query->form);
                }
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
    public function getPairs(): array
    {
        return $this->dao->select('id,name')->from(TABLE_ACCOUNT)->where('deleted')->eq('0')->fetchPairs();
    }

    /**
     * Create account
     *
     * @param  object $account
     * @access public
     * @return int|false
     */
    public function create(object $account): int|false
    {
        $this->dao->insert(TABLE_ACCOUNT)->data($account)
            ->batchCheck($this->config->account->create->requiredFields, 'notempty')
            ->checkIf($account->email, 'email', 'email')
            ->checkIf($account->mobile, 'mobile', 'mobile')
            ->exec();
        if(dao::isError()) return false;

        $accountID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('account', $accountID, 'created');
        return $accountID;
    }

    /**
     * Update one account.
     *
     * @param  int    $id
     * @param  object $account
     * @access public
     * @return bool
     */
    public function update(int $id, object $account): bool
    {
        $oldAccount = $this->getByID($id);
        $this->dao->update(TABLE_ACCOUNT)->data($account)->autoCheck()
            ->batchCheck($this->config->account->edit->requiredFields, 'notempty')
            ->checkIf($account->email, 'email', 'email')
            ->checkIf($account->mobile, 'mobile', 'mobile')
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError())
        {
            $changes = common::createChanges($oldAccount, $account);
            if(empty($changes)) return true;

            $actionID = $this->loadModel('action')->create('account', $id, 'Edited');
            $this->action->logHistory($actionID, $changes);
            return true;
        }
        return false;
    }
}
