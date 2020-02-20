<?php
/**
 * The model file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class jenkinsModel extends model
{
    /**
     * Get a jenkins by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $jenkins = $this->dao->select('*')->from(TABLE_JENKINS)->where('id')->eq($id)->fetch();
        $jenkins->password = base64_decode($jenkins->password);

        return $jenkins;
    }

    /**
     * Get jenkins list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function listAll($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $jenkinsList = $this->dao->select('*')->from(TABLE_JENKINS)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $jenkinsList;
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $jenkins = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->skipSpecial('serviceUrl,token,account,password')
            ->get();

        $jenkins->password = base64_encode($jenkins->password);

        $this->dao->insert(TABLE_JENKINS)->data($jenkins)
            ->batchCheck($this->config->jenkins->create->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $jenkins = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->skipSpecial('serviceUrl,token,account,password')
            ->get();

        $jenkins->password = base64_encode($jenkins->password);

        $this->dao->update(TABLE_JENKINS)->data($jenkins)
            ->batchCheck($this->config->jenkins->edit->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * list jenkins for ci task edit
     *
     * @return array
     */
    public function getPairs()
    {
        $repos = $this->dao->select('id,name')->from(TABLE_JENKINS)->where('deleted')->eq('0')->orderBy('id')->fetchPairs('id', 'name');
        $repos = array('' => '') + $repos;
        return $repos;
    }
}
