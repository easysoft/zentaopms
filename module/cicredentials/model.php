<?php
/**
 * The model file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class cicredentialsModel extends model
{
    /**
     * Get a credentials by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $credential = $this->dao->select('*')->from(TABLE_CREDENTIALS)->where('id')->eq($id)->fetch();
        return $credential;
    }

    /**
     * Get credentials list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listAll($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $credentials = $this->dao->select('*')->from(TABLE_CREDENTIALS)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $credentials;
    }

    /**
     * Create a credentials.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $credential = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
//            ->remove('')
            ->get();

        $this->dao->insert(TABLE_CREDENTIALS)->data($credential)
            ->batchCheck($this->config->credential->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a credentials.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $credential = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_CREDENTIALS)->data($credential)
            ->batchCheck($this->config->credential->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * list credentials for repo and jenkins edit page
     *
     * @param $whr
     * @return mixed
     */
    public function listForSelection($whr)
    {
        $credentials = $this->dao->select('id, name')->from(TABLE_CREDENTIALS)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere('(' . $whr . ')')->fi()
            ->orderBy(id)
            ->fetchPairs();
        $credentials[''] = '';
        return $credentials;
    }
}
