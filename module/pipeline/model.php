<?php
/**
 * The model file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class pipelineModel extends model
{
    /**
     * Get a pipeline by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $pipeline = $this->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($id)->fetch();
        if($pipeline)
        {
            $pipeline->password = base64_decode($pipeline->password);
        }
        return $pipeline;
    }

    /**
     * Get pipeline list.
     *
     * @param  string $type jenkins|gitlab
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'jenkins', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->AndWhere('type')->eq($type)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get pipeline pairs
     *
     * @return array
     */
    public function getPairs($type)
    {
        $pipeline = $this->dao->select('id,name')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->AndWhere('type')->eq($type)
            ->orderBy('id')->fetchPairs('id', 'name');
        $pipeline = array('' => '') + $pipeline;
        return $pipeline;
    }

    /**
     * Create a pipeline.
     *
     * @access public
     * @return bool
     */
    public function create($type)
    {
        $pipeline = fixer::input('post')
            ->add('type', $type)
            ->add('private',md5(rand(10,113450)))
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->skipSpecial('url,token,account,password')
            ->get();
        if($type == 'gitlab') $pipeline->url = rtrim($pipeline->url, '/');

        if(isset($pipeline->password)) $pipeline->password = base64_encode($pipeline->password);

        $this->dao->insert(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->create->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * Update a pipeline.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $pipeline = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->skipSpecial('url,token,account,password')
            ->get();

        $type = $this->dao->select('type')->from(TABLE_PIPELINE)->where('id')->eq($id)->fetch('type');
        if($type == 'gitlab') $pipeline->url = rtrim($pipeline->url, '/');
        if(isset($pipeline->password)) $pipeline->password = base64_encode($pipeline->password);

        $this->dao->update(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        return !dao::isError();
    }
}
