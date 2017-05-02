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
    public function set($module = 'story', $field = 'priList', $lang = 'zh_cn')
    {
        if($module == 'user' and $field == 'priList') $field = 'roleList';
        if($module == 'block' and $field == 'priList')$field = 'closed';
        $currentLang = $this->app->getClientLang();

        $this->app->loadLang($module);
        $fieldList = zget($this->lang->$module, $field, '');

        if($module == 'bug' and $field == 'typeList')
        {
            unset($fieldList['designchange']);
            unset($fieldList['newfeature']);
            unset($fieldList['trackthings']);
        }
        if(($module == 'story' or $module == 'testcase') and $field == 'review')
        {
            $this->app->loadConfig($module);
            $this->view->users = $this->loadModel('user')->getPairs('nodeleted|noclosed');
            $this->view->needReview   = zget($this->config->$module, 'needReview', 1);
            $this->view->forceReview  = zget($this->config->$module, 'forceReview', '');
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

        if(strtolower($_SERVER['REQUEST_METHOD']) == "post")
        {
            if(($module == 'story' or $module == 'testcase') and $field == 'review')
            {
                $data = fixer::input('post')->join('forceReview', ',')->get();
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
            else
            {
                $lang = $_POST['lang'];
                $this->custom->deleteItems("lang=$lang&module=$module&section=$field");
                foreach($_POST['keys'] as $index => $key)
                {
                    $value  = $_POST['values'][$index];
                    $system = $_POST['systems'][$index];

                    //if(!$system and (!$value or !$key)) continue; //Fix bug #951.

                    /* Fix bug #942. */
                    if($field == 'priList' and !is_numeric($key)) die(js::alert($this->lang->custom->notice->priListKey));
                    if($module == 'bug' and $field == 'severityList' and !is_numeric($key)) die(js::alert($this->lang->custom->notice->severityListKey));

                    /* the length of role is 20, check it when save. */
                    if($module == 'user' and $field == 'roleList' and strlen($key) > 20) die(js::alert($this->lang->custom->notice->userRole));

                    $this->custom->setItem("{$lang}.{$module}.{$field}.{$key}.{$system}", $value);
                }
            }
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('custom', 'set', "module=$module&field=$field&lang=" . str_replace('-', '_', $lang)), 'parent'));
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

        $this->custom->deleteItems("module=$module&section=$field");
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
            $this->loadModel('setting')->setItem('system.custom.productProject', $this->post->productProject);
            die(js::reload('parent'));
        }

        $this->view->title      = $this->lang->custom->flow;
        $this->view->position[] = $this->lang->custom->flow;
        $this->display();
    }

    public function working()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.global.flow', $this->post->flow);
            die(js::reload('parent'));
        }

        $this->view->title      = $this->lang->custom->working;
        $this->view->position[] = $this->lang->custom->working;
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
        $fields  = $this->post->fields;
        if(is_array($fields)) $fields = join(',', $fields);
        $this->loadModel('setting')->setItem("$account.$module.$section.$key", $fields);
        die(js::reload('parent'));
    }

    /**
     * Ajax set homepage.
     * 
     * @param  string $module 
     * @param  string $page 
     * @access public
     * @return void
     */
    public function ajaxSetHomepage($module, $page = '')
    {
        if(empty($page))
        {
            $this->view->title  = $this->lang->homepage;
            $this->view->module = $module;
            die($this->display());
        }

        $account = $this->app->user->account;
        $this->loadModel('setting')->setItem("$account.$module.homepage", $page);
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
            foreach ($menus as $menu)
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
                $menu['module']     = customModel::getModuleMenu($module, true);
                $menu['feature']    = customModel::getFeatureMenu($module, $method);
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
    public function ajaxRestoreMenu($confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->custom->confirmRestore, inlink('ajaxRestoreMenu', "confirm=yes")));

        $this->loadModel('setting')->deleteItems("owner={$this->app->user->account}&module=common&section=customMenu");
        die(js::reload('parent.parent'));
    }
}
