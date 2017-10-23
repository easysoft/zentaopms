<?php
/**
 * The control file of score module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id: control.php $
 * @link        http://www.zentao.net
 */
class score extends control
{
    /**
     * score list
     *
     * @access public
     * @return mixed
     */
    public function browse()
    {
        $this->view->title = $this->lang->score->common;
        $this->display();
    }
}