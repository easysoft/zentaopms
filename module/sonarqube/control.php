<?php
/**
 * The control file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: ${FILE_NAME} 5144 2022/1/11 8:55 上午 caoyanyi@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class sonarqube extends control
{
    /**
     * Browse sonarqube.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $sonarqubeList = $this->sonarqube->getList($orderBy, $pager);

        $this->view->title         = $this->lang->sonarqube->common . $this->lang->colon . $this->lang->sonarqube->browse;
        $this->view->sonarqubeList = $sonarqubeList;
        $this->view->orderBy       = $orderBy;
        $this->view->pager         = $pager;

        $this->display();
    }

    /**
     * Ajax get project list.
     *
     * @param  int    $engine
     * @access public
     * @return void
     */
    public function ajaxGetProjectList($sonarqubeID)
    {
        $projectList  = $this->loadModel('sonarqube')->getList($sonarqubeID);
        $projectPairs = array(0 => '');
        foreach($projectList as $project)
        {
            $projectPairs[$project->id] = $project->name;
        }
        echo html::select('projectKey', $projectPairs, '', "class='form-control chosen' required");
    }
}
