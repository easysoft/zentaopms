<?php
/**
 * The control file of index module of ZenTaoPMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: control.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class index extends control
{
    /**
     * Construct function, load project, product.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The index page of whole zentao system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index($open = '')
    {
        $latestVersionList = array();
        if(isset($this->config->global->latestVersionList)) $latestVersionList = json_decode($this->config->global->latestVersionList);

        $this->view->open  = helper::safe64Decode($open);
        $this->view->title = $this->lang->index->common;

        $this->view->latestVersionList = $latestVersionList;
        $this->display();
    }

    /**
     * Get the log record according to the version.
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function changeLog($version = '')
    {
        $latestVersionList = json_decode($this->config->global->latestVersionList);
        $version           = $latestVersionList->$version;

        $this->view->version = $version;
        $this->display();
    }

    /**
     * Just test the extension engine.
     *
     * @access public
     * @return void
     */
    public function testext()
    {
        echo $this->fetch('misc', 'getsid');
    }

    /**
     * ajaxClearObjectSession
     *
     * @access public
     * @return void
     */
    public function ajaxClearObjectSession()
    {
        $objectType = $this->post->objectType;
        $appGroup   = zget($this->config->index->appGroup, $objectType, '');
        if($objectType == 'testcase')    $objectType = 'case';
        if($objectType == 'testreport')  $objectType = 'report';
        if($objectType == 'productplan') $objectType = 'productPlan';

        $this->session->set($objectType . 'List', '', $appGroup);
    }
}
