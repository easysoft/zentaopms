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
        $gitlab = $this->dao->select('*')->from(TABLE_GITLAB)->where('id')->eq($id)->fetch();
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
        return $this->dao->select('*')->from(TABLE_GITLAB)
            ->where('deleted')->eq('0')
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
        $gitlab = $this->dao->select('id,name')->from(TABLE_GITLAB)
            ->where('deleted')->eq('0')
            ->orderBy('id')->fetchPairs('id', 'name');
        $gitlab = array('' => '') + $gitlab;
        return $gitlab;
    }

    /**
     * Get gitlab tasks.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getTasks($id)
    {
        $gitlab = $this->getById($id);

        $gitlabServer   = $gitlab->url;
        $gitlabUser     = $gitlab->account;
        $gitlabPassword = $gitlab->token ? $gitlab->token : $gitlab->password;

        $userPWD  = "$gitlabUser:$gitlabPassword";
        $response = common::http($gitlabServer . '/api/json/items/list', '', array(CURLOPT_USERPWD => $userPWD));
        $response = json_decode($response);

        $tasks = array();
        if(isset($response->jobs))
        {
            foreach($response->jobs as $job) $tasks[basename($job->url)] = $job->name;
        }
        return $tasks;

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
            ->skipSpecial('url,token,account,password')
            ->get();

        $gitlab->password = base64_encode($gitlab->password);

        $this->dao->insert(TABLE_GITLAB)->data($gitlab)
            ->batchCheck($this->config->gitlab->create->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
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
            ->skipSpecial('url,token,account,password')
            ->get();

        $gitlab->password = base64_encode($gitlab->password);

        $this->dao->update(TABLE_GITLAB)->data($gitlab)
            ->batchCheck($this->config->gitlab->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }
}
