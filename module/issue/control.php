<?php
/**
 * The control file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class issue extends control
{
    /**
     * Get issue list data.
     *
     * @param  string $browseType
     * @param  string orderBy
     * @param  int recTotal
     * @param  int recPerPage
     * @param  int pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->browse;
        $this->view->position[] = $this->lang->issue->browse;

        $this->display();
    }

    /**
     * Create a issue.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $result = $this->issue->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->create;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->create;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }
   
   
   /**
    * batchCreate issues
    *
    * @access public
    * @return void
    */ 
    public function batchCreate()
    {
	if($_POST)
	{
	    $results = $this->issue->batchCreate();
	    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
	    $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
	}
     
        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->batchCreate;
        $this->view->position[] = $this->lang->issue->common;
	$this->view->position[] = $this->lang->issue->batchCreate;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

	$this->display();
    }

}
