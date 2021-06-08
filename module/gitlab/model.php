<?php
/**
 * The model file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class gitlabModel extends model
{
    /**
     * Get a gitlab by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $gitlab = $this->dao->select('*')->from(TABLE_PIPLINE)->where('id')->eq($id)->fetch();
        $gitlab->password = base64_decode($gitlab->password);

        return $gitlab;
    }

    /**
     * Get gitlab list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_PIPLINE)
            ->where('deleted')->eq('0')
            ->andwhere('type')->eq('1')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get gitlab pairs
     *
     * @return array
     */
    public function getPairs()
    {
        $gitlab = $this->dao->select('id,name')->from(TABLE_PIPLINE)
            ->where('deleted')->eq('0')
            ->orderBy('id')->fetchPairs('id', 'name');
        $gitlab = array('' => '') + $gitlab;
        return $gitlab;
    }

    /**
     * Create a gitlab.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $gitlab = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('type', 1)
            ->skipSpecial('url,token')
            ->get();


        $this->dao->insert(TABLE_PIPLINE)->data($gitlab)
            ->batchCheck($this->config->gitlab->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;
        return $this->dao->lastInsertId();
    }

    /**
     * Update a gitlab.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $gitlab = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->skipSpecial('url,token')
            ->get();

        $this->dao->update(TABLE_PIPLINE)->data($gitlab)
            ->batchCheck($this->config->gitlab->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Get gitlab token permissions.
     *
     * @param  string   $host
     * @param  string   $token
     * @access public
     * @return array
     */
    public function getPermissionsByToken($host, $token)
    {
        $host  = rtrim($host, '/');
        $host .= '/api/v4/user/activities';

        $results = json_decode(file_get_contents($host . "?private_token=$token"));

        return $results;
    }

}
