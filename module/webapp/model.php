<?php
/**
 * The model file of webapp module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <Yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class webappModel extends model
{

    /**
     * The api agent(use snoopy).
     * 
     * @var object   
     * @access public
     */
    public $agent;

    /**
     * The api root.
     * 
     * @var string
     * @access public
     */
    public $apiRoot;

    /**
     * The construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAgent();
        $this->setApiRoot();
        $this->loadModel('tree');
    }

    /**
     * Set menu.
     *
     * @param  array  $projects
     * @param  int    $projectID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($module = null)
    {
        $modules = $this->getModules();

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();
        foreach($modules as $moduleID => $module)
        {
            $module = trim($module, '/');
            $this->lang->webapp->menu->$moduleID = array('link' => "$module|webapp|index|module=$moduleID");
        }
        $this->lang->webapp->menu->manageTree = array('link' => "{$this->lang->webapp->manageTree}|tree|browse|rootID=0&view=webapp", 'float' => 'right');
        $this->lang->webapp->menu->create     = array('link' => "{$this->lang->webapp->create}|webapp|create", 'float' => 'right');
    }


    /**
     * Set the api agent.
     * 
     * @access public
     * @return void
     */
    public function setAgent()
    {
        $this->agent = $this->app->loadClass('snoopy');
    }

    /**
     * Set the apiRoot.
     * 
     * @access public
     * @return void
     */
    public function setApiRoot()
    {
        $this->apiRoot = $this->config->webapp->apiRoot;
    }

    /**
     * Fetch data from an api.
     * 
     * @param  string    $url 
     * @access public
     * @return mixed
     */
    public function fetchAPI($url)
    {
        $this->agent->fetch($url);
        $result = json_decode($this->agent->results);

        if(!isset($result->status)) return false;
        if($result->status != 'success') return false;
        if(isset($result->data) and md5($result->data) != $result->md5) return false;
        if(isset($result->data)) return json_decode($result->data);
    }

    /**
     * Get webapp modules from the api.
     * 
     * @access public
     * @return string|bool
     */
    public function getModulesByAPI()
    {
        $requestType = $this->config->requestType;
        $webRoot     = helper::safe64Encode($this->config->webRoot);
        $apiURL      = $this->apiRoot . 'apiGetmodules-' . $requestType . '-' . $webRoot . '.json';
        $data = $this->fetchAPI($apiURL);
        if(isset($data->modules)) return $data->modules;
        return false;
    }

    /**
     * Get webapps by some condition.
     * 
     * @param  string    $type 
     * @param  mixed     $param 
     * @access public
     * @return array|bool
     */
    public function getAppsByAPI($type, $param, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $apiURL = $this->apiRoot . "apiGetApps-$type-$param-$recTotal-$recPerPage-$pageID.json";
        $data   = $this->fetchAPI($apiURL);
        return $data;
    }

    /**
     * Get a app info by API 
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function getAppInfoByAPI($webappID)
    {
        $apiURL = $this->apiRoot . "apiGetAppInfo-$webappID.json";
        $data   = $this->fetchAPI($apiURL);
        return $data;
    }

    /**
     * Get webapps by status.
     * 
     * @param  string    $status 
     * @access public
     * @return array
     */
    public function getLocalApps($key = 'id', $module = 0)
    {
        $webapps = $this->dao->select('*')->from(TABLE_WEBAPP)
            ->beginIF($module != 0)->where('module')->eq($module)->fi()
            ->fetchAll($key, false);
        $localIcons = array();
        foreach($webapps as $webapp)
        {
            if($webapp->addType == 'custom') $localIcons[$webapp->id] = $webapp->icon;
        }

        if($localIcons)
        {
            $files       = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($localIcons)->fetchAll('id');
            $fileWebPath = $this->loadModel('file')->webPath;
            foreach($localIcons as $webappID => $icon)
            {
                if(isset($files[$icon])) $webapps[$webappID]->icon = $fileWebPath . $files[$icon]->pathname;
            }
        }

        return $webapps;
    }

    /**
     * Get webapp info from database.
     * 
     * @param  string    $webapp 
     * @access public
     * @return object
     */
    public function getLocalAppByID($webappID)
    {
        return $this->dao->select('*')->from(TABLE_WEBAPP)->where('id')->eq($webappID)->fetch('', false);
    }

    /**
     * Install web app. 
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function install($webappID)
    {
        $data = $this->getAppInfoByAPI($webappID);
        $webapp = $data->webapp;
        $webapp->icon      = $this->config->webapp->url . $webapp->icon->webPath;
        $webapp->addedDate = helper::now();
        $webapp->addedBy   = $this->app->user->account;
        $webapp->module    = $this->post->module;
        $webapp->appid     = $webapp->id;
        unset($webapp->account);
        unset($webapp->status);
        unset($webapp->url);
        unset($webapp->id);
        unset($webapp->grade);
        unset($webapp->updatedDate);
        unset($webapp->downloads);
        $this->dao->insert(TABLE_WEBAPP)->data($webapp, false)->autocheck()->exec(false);
        return $this->dao->lastInsertID(); 
    }

    /**
     * Update app. 
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function update($webappID)
    {
        $data = fixer::input('post')->get();
        $this->dao->update(TABLE_WEBAPP)->data($data, false)->where('id')->eq($webappID)->exec(false);
    }

    /**
     * Create a web app. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $data = fixer::input('post')
            ->add('addedBy', $this->app->user->account)
            ->add('addType', 'custom')
            ->add('addedDate', helper::now())
            ->add('author', $this->app->user->account)
            ->remove('files')->get();
        $this->dao->insert(TABLE_WEBAPP)->data($data, false)
            ->autocheck()
            ->batchCheck($this->config->webapp->create->requiredFields, 'notempty')
            ->exec(false);
        if(!dao::isError())
        {
            $webappID = $this->dao->lastInsertID();
            if($_FILES)
            {
                $fileTitle = $this->loadModel('file')->saveUpload('webapp', $webappID);
                $this->dao->update(TABLE_WEBAPP)->set('icon')->eq(key($fileTitle))->where('id')->eq($webappID)->exec(false);
            }
            return $webappID;
        }
    }

    /**
     * Get app modules.
     * 
     * @access public
     * @return void
     */
    public function getModules()
    {
        $modules = $this->tree->getOptionMenu(0, 'webapp');
        $modules[0] = (count($modules) == 1) ? $this->lang->webapp->noModule : $this->lang->webapp->allModule;

        return $modules;
    }
}
