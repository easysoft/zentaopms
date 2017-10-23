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
    public function browse($recTotal = 0, $recPerPage = 100)
    {
        $this->app->loadClass('pager', $static = true);
        $pager  = new pager($recTotal, $recPerPage);
        $scores = $this->score->getScores($pager);

        $this->view->title  = $this->lang->score->common;
        $this->view->pager  = $pager;
        $this->view->scores = $scores;
        $this->display();
    }
}