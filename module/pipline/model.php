<?php
/**
 * The model file of pipline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

class piplineModel extends model
{
    /**
     * Get a pipline by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $pipline = $this->dao->select('*')->from(TABLE_PIPLINE)->where('id')->eq($id)->fetch();
        $pipline->password = base64_decode($pipline->password);
        return $pipline;
    }

    /**
     * Get pipline list.
     *
     * @param  string $type jenkins|gitlab
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'jenkins', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_PIPLINE)
            ->where('deleted')->eq('0')
            ->AndWhere('type')->eq($type)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get pipline pairs
     *
     * @return array
     */
    public function getPairs($type)
    {
        $pipline = $this->dao->select('id,name')->from(TABLE_PIPLINE)
            ->where('deleted')->eq('0')
            ->AndWhere('type')->eq($type)
            ->orderBy('id')->fetchPairs('id', 'name');
        $pipline = array('' => '') + $pipline;
        return $pipline;
    }

    /**
     * Create a pipline.
     *
     * @access public
     * @return bool
     */
    public function create($type)
    {
        $pipline = fixer::input('post')
            ->add('type', $type)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->skipSpecial('url,token,account,password')
            ->get();

        $pipline->password = base64_encode($pipline->password);

        $this->dao->insert(TABLE_PIPLINE)->data($pipline)
            ->batchCheck($this->config->pipline->create->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;
        return $this->dao->lastInsertId();
    }

    /**
     * Update a pipline.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $pipline = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->skipSpecial('url,token,account,password')
            ->get();

        $pipline->password = base64_encode($pipline->password);

        $this->dao->update(TABLE_PIPLINE)->data($pipline)
            ->batchCheck($this->config->pipline->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }
}
