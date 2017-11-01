<?php
/**
 * The control file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: control.php $
 * @link        http://www.zentao.net
 */
class score extends control
{
    /**
     * Ajax action score
     * javascript use : $.get(createLink('score', 'ajax', "method=method"));
     *
     * @param string $method
     *
     * @access public
     * @return void
     */
    public function ajax($method = '')
    {
        $this->loadModel('score')->create('ajax', $method);
    }

    /**
     * initialization score
     *
     * @param int $lastID
     *
     * @access public
     * @return void
     */
    public function init($lastID = 0)
    {
        if(helper::isAjaxRequest())
        {
            $result = $this->score->init($lastID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($result['status'] == 'finish')
            {
                $this->send(array('result' => 'finished', 'message' => $this->lang->score->initFinish));
            }
            else
            {
                $this->send(array('result' => 'unfinished', 'message' => $this->lang->score->processed, 'lastID' => $result['lastID']));
            }
        }
        $this->display();
    }
}