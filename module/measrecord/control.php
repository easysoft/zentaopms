<?php
/**
 * The control file of measrecord of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     measrecord
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class measrecord extends control
{
    public function browse($program = 0, $measID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadLang('measurement');
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->lang->navGroup->measrecord = 'project';

        $this->loadModel('project')->setMenu($program);

        $this->view->records  = $this->measrecord->getListByProgram($program, $measID, $orderBy, $pager);
        $this->view->measList = $this->measrecord->getSavedMeas($program);
        $this->view->title    = $this->lang->measrecord->browse;
        $this->view->program  = $program;
        $this->view->programs = $this->loadModel('program')->getPairs();
        $this->view->products = $this->loadModel('product')->getPairs();
        $this->view->projects = $this->loadModel('project')->getPairsByProgram();

        if($measID)
        {
            $measurement = $this->loadModel('measurement')->getByID($measID);
            $measurement->params     = json_decode($measurement->params);
            $this->view->measurement = $measurement;
        }
        $this->display();
    }
}
