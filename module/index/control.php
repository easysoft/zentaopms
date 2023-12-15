<?php
declare(strict_types=1);
/**
 * The control file of index module of ZenTaoPMS.
 *
 * When requests the root of a website, this index module will be called.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: control.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        https://www.zentao.net
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
     * @param  string $open   base64 encode string.
     * @access public
     * @return void
     */
    public function index(string $open = '')
    {
        if($this->app->getViewType() == 'mhtml') $this->locate($this->createLink('my', 'index'));
        if($this->get->open) $open = $this->get->open;

        $latestVersionList = array();
        if(isset($this->config->global->latestVersionList)) $latestVersionList = json_decode($this->config->global->latestVersionList, true);

        $this->view->title             = $this->lang->index->common;
        $this->view->open              = helper::safe64Decode($open);
        $this->view->showFeatures      = $this->indexZen->checkShowFeatures();
        $this->view->latestVersionList = $latestVersionList;
        $this->view->appsItems         = commonModel::getMainNavList($this->app->rawModule);
        $this->view->browserMessage    = $this->loadModel('message')->getBrowserMessageConfig();

        $this->display();
    }

    /**
     * 在框架中打开具体页面。
     * Open url in index frame.
     *
     * @param  string $open     base64 encode string.
     * @access public
     * @return void
     */
    public function app(string $open = '')
    {
        $this->view->defaultUrl = helper::safe64Decode($open);
        $this->display();
    }

    /**
     * Get the log record according to the version.
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function changeLog(string $version = '')
    {
        $latestVersionList = json_decode($this->config->global->latestVersionList);
        $version           = $latestVersionList->$version;

        $this->view->version = $version;
        $this->display();
    }

    /**
     * Ajax get view method for asset lib by object type.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function ajaxGetViewMethod(int $objectID, string $objectType)
    {
        $method = $this->indexZen->getViewMethodForAssetLib($objectID, $objectType);
        return print($method);
    }
}
