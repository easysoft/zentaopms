<?php
/**
 * The model file of cijenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class cijenkinsModel extends model
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
            ->get();

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
            ->get();

        $this->dao->update(TABLE_JENKINS)->data($jenkins)
            ->batchCheck($this->config->jenkins->edit->requiredFields, 'notempty')
            ->batchCheck("serviceUrl", 'URL')
            ->batchCheckIF($jenkins->type === 'credential', "credential", 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * list jenkins for ci task edit
     *
     * @return mixed
     */
    public function listForSelection($whr)
    {
        $repos = $this->dao->select('id, name')->from(TABLE_JENKINS)
            ->where('deleted')->eq('0')
            ->beginIF(!empty(whr))->andWhere('(' . $whr . ')')->fi()
            ->orderBy(id)
            ->fetchPairs();
        $repos[''] = '';
        return $repos;
    }
}
