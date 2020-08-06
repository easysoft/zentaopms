<?php
/**
 * The control file of custom of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class custom extends control
{
    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function index()
    {
        die(js::locate(inlink('set')));
    }

    /**
     * Custom
     *
     * @param  string $module
     * @param  string $field
     * @param  string $lang
     * @access public
     * @return void
     */
    public function set($module = 'story', $field = 'priList', $lang = '')
    {
        if(empty($lang)) $lang = $this->app->getClientLang();
        if($module == 'user' and $field == 'priList') $field = 'roleList';
        if($module == 'block' and $field == 'priList')$field = 'closed';
        $currentLang = $this->app->getClientLang();

        $this->app->loadLang($module);
        $fieldList = zget($this->lang->$module, $field, '');

        if(($module == 'story' or $module == 'testcase') and $field == 'review')
        {
            $this->app->loadConfig($module);
            $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
            $this->view->needReview      = zget($this->config->$module, 'needReview', 1);
            $this->view->forceReview     = zget($this->config->$module, 'forceReview', '');
            $this->view->forceNotReview  = zget($this->config->$module, 'forceNotReview', '');
        }
        if($module == 'task' and $field == 'hours')
        {
            $this->app->loadConfig('project');
            $this->view->weekend   = $this->config->project->weekend;
            $this->view->workhours = $this->config->project->defaultWorkhours;
        }
        if($module == 'bug' and $field == 'longlife')
        {
            $this->app->loadConfig('bug');
            $this->view->longlife  = $this->config->bug->longlife;
        }
        if($module == 'block' and $field == 'closed')
        {
            $this->loadModel('block');
            $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';

            $this->view->blockPairs  = $this->block->getClosedBlockPairs($closedBlock);
            $this->view->closedBlock = $closedBlock;
        }
        if($module == 'user' and $field == 'deleted')
        {
            $this->app->loadConfig('user');
            $this->view->showDeleted = isset($this->config->user->showDeleted) ? $this->config->user->showDeleted : '0';
        }

        if(strtolower($this->server->request_method) == "post")
        {
            if($module == 'story' and $field == 'review')
            {
                $data = fixer::input('post')->join('forceReview', ',')->get();
                $this->loadModel('setting')->setItems("system.$module", $data);
            }
            elseif($module == 'testcase' and $field == 'review')
            {
                $review = fixer::input('post')->get();
                if($review->needReview) $data = fixer::input('post')->join('forceNotReview', ',')->remove('forceReview')->get();
                if(!$review->needReview) $data = fixer::input('post')->join('forceReview', ',')->remove('forceNotReview')->get();
                $this->loadModel('setting')->setItems("system.$module", $data);
            }
            elseif($module == 'task' and $field == 'hours')
            {
                $this->loadModel('setting')->setItems('system.project', fixer::input('post')->get());
            }
            elseif($module == 'bug' and $field == 'longlife')
            {
                $this->loadModel('setting')->setItems('system.bug', fixer::input('post')->get());
            }
            elseif($module == 'block' and $field == 'closed')
            {
                $data = fixer::input('post')->join('closed', ',')->get();
                $this->loadModel('setting')->setItem('system.block.closed', zget($data, 'closed', ''));
            }
            elseif($module == 'user' and $field == 'contactField')
            {
                $data = fixer::input('post')->join('contactField', ',')->get();
                if(!isset($data->contactField)) $data->contactField = '';
                $this->loadModel('setting')->setItem('system.user.contactField', $data->contactField);
            }
            elseif($module == 'user' and $field == 'deleted')
            {
                $data = fixer::input('post')->get();
                $this->loadModel('setting')->setItem('system.user.showDeleted', $data->showDeleted);
            }
            else
            {
                $lang = $_POST['lang'];
                $oldCustoms = $this->custom->getItems("lang=$lang&module=$module&section=$field");
                foreach($_POST['keys'] as $index => $key)
                {
                    if(!empty($key)) $key = trim($key);
                    /* Invalid key. It should be numbers. (It includes severityList in bug module and priList in stroy, task, bug, testcasea, testtask and todo module.) */
                    if($field == 'priList' or $field == 'severityList')
                    {
                        if(!is_numeric($key) or $key > 255) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidNumberKey));
                    }
                    if(!empty($key) and !isset($oldCustoms[$key]) and $key != 'n/a' and !validater::checkREG($key, '/^[a-z_0-9]+$/')) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidStringKey));

                    /* The length of roleList in user module and typeList in todo module is less than 10. check it when saved. */
                    if($field == 'roleList' or $module == 'todo' and $field == 'typeList')
                    {
                        if(strlen($key) > 10) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidStrlen['ten']));
                    }
                    
                    /* The length of sourceList in story module and typeList in task module is less than 20, check it when saved. */
                    if($field == 'sourceList' or $module == 'task' and $field == 'typeList')
                    {
                        if(strlen($key) > 20) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidStrlen['twenty']));
                    }
                    
                    /* The length of stageList in testcase module is less than 255, check it when saved. */
                    if($module == 'testcase' and $field == 'stageList' and strlen($key) > 255) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidStrlen['twoHundred']));
                    
                    /* The length of field that in bug and testcase module and reasonList in story and task module is less than 30, check it when saved. */
                    if($module == 'bug' or $field == 'reasonList' or $module == 'testcase')
                    {
                        if(strlen($key) > 30) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->invalidStrlen['thirty']));
                    }
                }

                $this->custom->deleteItems("lang=$lang&module=$module&section=$field");
                $data = fixer::input('post')->get();
                foreach($data->keys as $index => $key)
                {
                    //if(!$system and (!$value or !$key)) continue; //Fix bug #951.
                    
                    $value  = $data->values[$index];
                    $system = $data->systems[$index];
                    $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}.{$system}", $value);
                }
            }
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('custom', 'set', "module=$module&field=$field&lang=" . str_replace('-', '_', $lang))));
        }

        /* Check whether the current language has been customized. */
        $lang = str_replace('_', '-', $lang);
        $dbFields = $this->custom->getItems("lang=$lang&module=$module&section=$field");
        if(empty($dbFields)) $dbFields = $this->custom->getItems("lang=" . ($lang == $currentLang ? 'all' : $currentLang) . "&module=$module&section=$field");
        if($dbFields)
        {
            $dbField = reset($dbFields);
            if($lang != $dbField->lang)
            {
                $lang = str_replace('-', "_", $dbField->lang);
                foreach($fieldList as $key => $value)
                {
                    if(isset($dbFields[$key]) and $value != $dbFields[$key]->value) $fieldList[$key] = $dbFields[$key]->value;
                }
            }
        }

        $this->view->title       = $this->lang->custom->common . $this->lang->colon . $this->lang->$module->common;
        $this->view->position[]  = $this->lang->custom->common;
        $this->view->position[]  = $this->lang->$module->common;
        $this->view->fieldList   = $fieldList;
        $this->view->dbFields    = $dbFields;
        $this->view->field       = $field;
        $this->view->lang2Set     = str_replace('_', '-', $lang);
        $this->view->module      = $module;
        $this->view->currentLang = $currentLang;
        $this->view->canAdd      = strpos($this->config->custom->canAdd[$module], $field) !== false;

        $this->display();
    }

    /**
     * Restore the default lang. Delete the related items.
     * 
     * @param  string $module 
     * @param  string $field 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function restore($module, $field, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->custom->confirmRestore, inlink('restore', "module=$module&field=$field&confirm=yes")));

        if($module == 'user' and $field == 'contactField')
        {
            $this->loadModel('setting')->deleteItems("module=$module&key=$field");
        }
        else
        {
            $this->custom->deleteItems("module=$module&section=$field");
        }
        die(js::reload('parent'));
    }

    /**
     * Flow zentao. 
     * 
     * @access public
     * @return void
     */
    public function flow()
    {
        if($_POST)
        {
            $this->custom->setFlow();
            $this->custom->setStoryRequirement();

            $this->loadModel('setting')->setItem('system.custom.hourPoint', $this->post->hourPoint);
            $this->loadModel('setting')->setItem('system.common.conceptSetted', 1);

            $this->app->loadLang('common');
            $locate = inlink('flow');
            if(!isset($this->config->conceptSetted)) $this->lang->custom->notice->conceptResult .= $this->lang->custom->notice->conceptPath;
            if(!isset($this->config->conceptSetted)) $locate = helper::createLink('my', 'index');
            $message = sprintf($this->lang->custom->notice->conceptResult, $this->lang->productCommon, $this->lang->projectCommon, $this->lang->storyCommon, $this->lang->hourCommon);
            $this->send(array('result' => 'success', 'notice' => $message, 'locate' => $locate));
        }

        $this->view->title      = $this->lang->custom->flow;
        $this->view->position[] = $this->lang->custom->flow;
        $this->display();
    }

    /**
     * Set working mode function.
     * 
     * @access public
     * @return void
     */
    public function working()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.global.flow', $this->post->flow);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title      = $this->lang->custom->working;
        $this->view->position[] = $this->lang->custom->working;
        $this->display();
    }

    /**
     * Set Required.
     * 
     * @param  string $moduleName 
     * @access public
     * @return void
     */
    public function required($moduleName = '')
    {
        if(empty($moduleName)) $moduleName = current($this->config->custom->requiredModules);

        if($this->server->request_method == 'POST')
        {
            $this->custom->saveRequiredFields($moduleName);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        foreach($this->config->custom->requiredModules as $requiredModule) $this->app->loadLang($requiredModule);

        /* Get this module requiredFields. */
        $this->loadModel($moduleName);
        if($moduleName == 'user') $this->app->loadModuleConfig($moduleName);
        $requiredFields = $this->custom->getRequiredFields($this->config->$moduleName);

        if($moduleName == 'doc')
        {
            unset($requiredFields['createLib']);
            unset($requiredFields['editLib']);
        }

        $this->view->title      = $this->lang->custom->required;
        $this->view->position[] = $this->lang->custom->required;

        $this->view->requiredFields = $requiredFields;
        $this->view->moduleName     = $moduleName;
        $this->display();
    }

    /**
     * Set score display switch
     *
     * @access public
     * @return void
     */
    public function score()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.global.scoreStatus', $this->post->score);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title      = $this->lang->custom->score;
        $this->view->position[] = $this->lang->custom->common;
        $this->view->position[] = $this->view->title;
        $this->display();
    }

    /**
     * Timezone.
     * 
     * @access public
     * @return void
     */
    public function timezone()
    {
        if(strtolower($_SERVER['REQUEST_METHOD']) == "post")
        {
            $this->loadModel('setting')->setItems('system.common', fixer::input('post')->get());
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        unset($this->lang->admin->menu->custom['subModule']);
        $this->lang->admin->menu->system['subModule'] = 'custom';
        $this->lang->custom->menu = $this->lang->admin->menu;

        $this->view->title = $this->lang->custom->timezone;
        $this->view->position[] = $this->lang->custom->timezone;
        $this->display();
    }

    /**
     * Ajax save custom fields.
     * 
     * @param  string $module 
     * @param  string $section 
     * @param  string $key 
     * @access public
     * @return void
     */
    public function ajaxSaveCustomFields($module, $section, $key)
    {
        $account = $this->app->user->account;
        if($this->server->request_method == 'POST')
        {
            $fields  = $this->post->fields;
            if(is_array($fields)) $fields = join(',', $fields);
            $this->loadModel('setting')->setItem("$account.$module.$section.$key", $fields);
        }
        else
        {
            $this->loadModel('setting')->deleteItems("owner=$account&module=$module&section=$section&key=$key");
        }
        die(js::reload('parent'));
    }

    /**
     * Custom menu view
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxMenu($module = 'main', $method = '')
    {
        $this->view->module = $module;
        $this->view->method = $method;
        $this->display();
    }

    /**
     * Ajax set menu
     * 
     * @param  string $module
     * @param  string $method
     * @param  string $menus
     * @access public
     * @return void
     */
    public function ajaxSetMenu($module = 'main', $method = '', $menus = '')
    {
        if($_POST)
        {
            if(!empty($_POST['menus']))  $menus  = $_POST['menus'];
            if(!empty($_POST['module'])) $module = $_POST['module'];
            if(!empty($_POST['method'])) $method = $_POST['method'];
        }
        elseif(!empty($menus))
        {
            $menus = header::safe64Decode($menus);
        }

        if(empty($menus)) $this->send(array('result' => 'fail', 'message' => $this->lang->custom->saveFail));

        if(is_array($menus))
        {
            foreach($menus as $menu)
            {
                $menu = json_decode($menu);
                $this->custom->saveCustomMenu($menu->value, $menu->module, isset($menu->method) ? $menu->method : '');
            }
        }
        else
        {
            $this->custom->saveCustomMenu($menus, $module, $method);
        }

        $this->send(array('result' => 'success'));
    }

    /**
     * Ajax get menu
     *
     * @param  string $module
     * @param  string $method
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetMenu($module = 'main', $method = '', $type = '')
    {
        if($this->config->global->flow == 'full')     $this->loadModel('project')->setMenu(array(), 0);
        if($this->config->global->flow == 'onlyTest') $this->loadModel('testcase')->setMenu(array(), 0);
        if($this->config->global->flow == 'onlyTest') $this->loadModel('bug')->setMenu(array(), 0);
        if($this->config->global->flow == 'onlyTask') $this->loadModel('project')->setMenu(array(), 0);
        if($type === 'all')
        {
            $menu = array();
            $menu['main'] = customModel::getModuleMenu('main', true);
            if($method)
            {
                $this->app->loadLang($module);
                customModel::mergeFeatureBar($module, $method);
                /* Mark search query item. */
                if(isset($this->lang->$module->featureBar[$method]))
                {
                    foreach($this->lang->$module->featureBar[$method] as $barKey => $barValue)
                    {
                        if(strpos($barKey, 'QUERY') === 0)$this->lang->$module->featureBar[$method][$barKey] = "<i class='icon icon-search'></i> " . $barValue;
                    }
                }
            }
            if($module !== 'main')
            {
                $menu['module']  = array();
                $menu['feature'] = array();
                if(!isset($this->config->custom->noModuleMenu[$module]))
                {
                    $menu['module']  = customModel::getModuleMenu($module, true);
                    $menu['feature'] = customModel::getFeatureMenu($module, $method);
                }
                $menu['moduleName'] = $module;
                $menu['methodName'] = $method;
            }
        }
        else
        {
            $menu = !empty($method) ? customModel::getFeatureMenu($module, $method) : customModel::getModuleMenu($module, true);
        }
        die(str_replace("'", '\u0027', json_encode(array('result' => $menu ? 'success' : 'fail', 'menu' => $menu))));
    }

    /**
     * Ajax restore menu.
     * 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function ajaxRestoreMenu($setPublic = 0, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->custom->confirmRestore, inlink('ajaxRestoreMenu', "setPublic=$setPublic&confirm=yes")));

        $account = $this->app->user->account;
        $this->loadModel('setting')->deleteItems("owner={$account}&module=common&section=customMenu");
        if($setPublic) $this->setting->deleteItems("owner=system&module=common&section=customMenu");
        die(js::reload('parent.parent'));
    }

    /**
     * Ajax set doc setting.
     * 
     * @access public
     * @return void
     */
    public function ajaxSetDoc()
    {
        if($this->server->request_method == 'POST')
        {
            $data = fixer::input('post')->join('showLibs', ',')->get();
            if(isset($data->showLibs)) $data = $data->showLibs;
            $this->loadModel('setting')->setItem("{$this->app->user->account}.doc.custom.showLibs", $data);
            die(js::reload('parent.parent'));
        }
    }

    /**
     * Reset required.
     * 
     * @param  srting $module 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function resetRequired($module, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->custom->confirmRestore, inlink('resetRequired', "module=$module&confirm=yes")));

        $this->loadModel('setting')->deleteItems("owner=system&module={$module}&key=requiredFields");
        die(js::reload('parent.parent'));
    }
}
