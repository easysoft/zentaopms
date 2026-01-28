<?php
declare(strict_types=1);
/**
 * The control file of custom of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class custom extends control
{
    /**
     * Index.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if($this->config->vision == 'lite') return $this->locate(inlink('execution'));

        if(common::hasPriv('custom', 'set'))       return $this->locate(inlink('set', "module=project&field=" . key($this->lang->custom->project->fields)));
        if(common::hasPriv('custom', 'product'))   return $this->locate(inlink('product'));
        if(common::hasPriv('custom', 'execution')) return $this->locate(inlink('execution'));

        foreach($this->lang->custom->system as $sysObject)
        {
            if(common::hasPriv('custom', $sysObject)) return $this->locate(inlink($sysObject));
        }
    }

    /**
     * 设置对象字段的语言项。
     * Set the language items of the object fields.
     *
     * @param  string $module todo|story|task|bug|testcase|testtask|user|project|block
     * @param  string $field  priList|typeList|statusList|sourceList|reasonList|stageList|reviewRules|reviewResultList|review|severityList|osList|browserList|resolutionList|longlife|resultList|roleList|contactField|deleted|unitList|closed
     * @param  string $lang   all|zh-cn|zh-tw|en|de|fr
     * @access public
     * @return void
     */
    public function set(string $module = 'story', string $field = 'priList', string $lang = '')
    {
        if(empty($lang)) $lang = $this->app->getClientLang();
        if($module == 'user' && $field == 'priList')  $field = 'statusList';
        if($module == 'block' && $field == 'priList') $field = 'closed';
        $currentLang = $this->app->getClientLang();

        $this->customZen->assignVarsForSet($module, $field, $lang, $currentLang);

        if(strtolower($this->server->request_method) == "post")
        {
            $this->customZen->setFieldListForSet($module, $field);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($module == 'baseline' && $field == 'objectList') return $this->sendSuccess(array('load' => true));
            if($module == 'reviewcl') return $this->sendSuccess(array('load' => true));
            return $this->sendSuccess(array('load' => $this->createLink('custom', 'set', "module=$module&field=$field&lang=" . ($lang == 'all' ? $lang : ''))));
        }

        $this->view->title       = $this->lang->custom->common . $this->lang->hyphen . $this->lang->$module->common;
        $this->view->field       = $field;
        $this->view->module      = $module;
        $this->view->currentLang = $currentLang;
        $this->view->canAdd      = strpos($this->config->custom->canAdd[$module], $field) !== false;

        $this->display();
    }

    /**
     * 还原默认语言项。删除相关项。
     * Restore the default lang. Delete the related items.
     *
     * @param  string $module
     * @param  string $field
     * @access public
     * @return void
     */
    public function restore(string $module, string $field)
    {
        if($module == 'user' && $field == 'contactField')
        {
            $this->loadModel('setting')->deleteItems("module={$module}&key={$field}");
        }
        else
        {
            $this->custom->deleteItems("module={$module}&section={$field}");
        }

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 设置表单必填字段。
     * Set the required fields.
     *
     * @param  string $moduleName product|story|productplan|release|execution|task|bug|testcase|testsuite|testtask|testreport|caselib|doc|user|project|build
     * @access public
     * @return void
     */
    public function required(string $moduleName = '')
    {
        if(empty($moduleName)) $moduleName = current($this->config->custom->requiredModules);

        if($this->server->request_method == 'POST')
        {
            $this->custom->saveRequiredFields($moduleName, $_POST);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('required', "moduleName=$moduleName")));
        }

        foreach($this->config->custom->requiredModules as $requiredModule) $this->app->loadLang($requiredModule);

        /* Get this module requiredFields. */
        $this->loadModel($moduleName);
        if($moduleName == 'user') $this->app->loadModuleConfig($moduleName);
        $requiredFields = $this->custom->getRequiredFields($this->config->$moduleName);

        if($moduleName == 'doc')
        {
            unset($requiredFields['createlib']);
            unset($requiredFields['editlib']);
        }

        $this->view->title          = $this->lang->custom->required;
        $this->view->requiredFields = $requiredFields;
        $this->view->module         = $moduleName;
        $this->display();
    }

    /**
     * 时区。
     * Timezone.
     *
     * @access public
     * @return void
     */
    public function timezone()
    {
        if(strtolower($_SERVER['REQUEST_METHOD']) == "post")
        {
            $this->loadModel('setting')->setItems('system.common', $_POST);
            return $this->sendSuccess(array('load' => true));
        }

        unset($this->lang->admin->menu->custom['subModule']);
        $this->lang->admin->menu->system['subModule'] = 'custom';

        $this->view->title = $this->lang->custom->timezone;
        $this->display();
    }

    /**
     * 需求概念列表。
     * Browse story concept.
     *
     * @access public
     * @return void
     */
    public function browseStoryConcept()
    {
        $this->view->title    = $this->lang->custom->browseStoryConcept;
        $this->view->URSRList = $this->custom->getURSRList();
        $this->view->module   = 'product';

        $this->display();
    }

    /**
     * 设置需求概念。
     * Set story concept.
     *
     * @access public
     * @return void
     */
    public function setStoryConcept()
    {
        if($_POST)
        {
            $result = $this->custom->setURAndSR($_POST);
            if(!$result) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->URSREmpty));

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        if(!common::hasPriv('custom', 'setDefaultConcept')) unset($this->config->custom->browseStoryConcept->dtable->fieldList['default']);
        $this->view->title = $this->lang->custom->setStoryConcept;

        $this->display();
    }

    /**
     * 编辑需求概念。
     * Edit story concept.
     *
     * @param  int    $key
     * @access public
     * @return void
     */
    public function editStoryConcept(int $key = 0)
    {
        if($_POST)
        {
            $result = $this->custom->updateURAndSR($key, '', $_POST);
            if(!$result) return $this->send(array('result' => 'fail', 'message' => $this->lang->custom->notice->URSREmpty));

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $lang = $this->app->getClientLang();
        $URSR = $this->custom->getURSRConcept($key, $lang);

        $this->view->URSR = json_decode($URSR);

        $this->display();
    }

    /**
     * 设置默认需求概念。
     * Set story concept.
     *
     * @param  int   $key
     * @access public
     * @return void
     */
    public function setDefaultConcept(int $key = 0)
    {
        $this->loadModel('setting')->setItem('system.custom.URSR', $key);
        return $this->sendSuccess(array('load' => inlink('browsestoryconcept')));
    }

    /**
     * Delete story concept.
     *
     * @param  int    $key
     * @access public
     * @return void
     */
    public function deleteStoryConcept($key = 0)
    {
        $lang = $this->app->getClientLang();
        $this->custom->deleteItems("lang=$lang&section=URSRList&key=$key");

        $defaultConcept = $this->loadModel('setting')->getItem('owner=system&module=custom&key=URSR');
        $this->dao->update(TABLE_CONFIG)
                  ->set('`value`')->eq($defaultConcept)
                  ->where('module')->eq('common')
                  ->andWhere('`key`')->eq('URSR')
                  ->andWhere('`value`')->eq($key)
                  ->exec();

        return $this->send(array('result' => 'success', 'load' => inlink('browseStoryConcept')));
    }

    /**
     * 项目关闭设置。
     * Set whether the closed project is read-only.
     *
     * @access public
     * @return void
     */
    public function project()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem("system.common.CRProject@{$this->config->vision}", $this->post->project);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->projectCommon;
        $this->view->module = 'project';
        $this->display();
    }

    /**
     * 执行关闭设置。
     * Set whether the closed execution is read-only.
     *
     * @access public
     * @return void
     */
    public function execution()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem("system.common.CRExecution@{$this->config->vision}", $this->post->execution);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->custom->executionCommon;
        $this->view->module = 'execution';

        $this->display();
    }

    /**
     * 产品关闭设置。
     * Set whether the closed product is read-only.
     *
     * @access public
     * @return void
     */
    public function product()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.CRProduct', $this->post->product);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->custom->productName;
        $this->view->module = 'product';

        $this->display();
    }

    /**
     * 看板关闭设置。
     * Set whether the kanban is read-only.
     *
     * @access public
     * @return void
     */
    public function kanban()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem("system.common.CRKanban", $this->post->kanban);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->custom->kanban;

        $this->display();
    }

    /**
     * 流程设置。
     * Set flow.
     *
     * @access public
     * @return void
     */
    public function flow()
    {
        if($_POST)
        {
            $this->custom->setConcept($_POST['sprintConcept']);
            $this->loadModel('setting')->setItem('system.custom.hourPoint', $this->post->hourPoint);

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->custom->flow;

        $this->display();
    }

    /**
     * 模式管理。
     * Mode management.
     *
     * @access public
     * @return void
     */
    public function mode()
    {
        $mode = zget($this->config->global, 'mode', 'light');
        if($this->post->mode && $this->post->mode != $mode) // If mode value change.
        {
            $mode    = $this->post->mode;
            $program = isset($_POST['program']) ? $_POST['program'] : 0;

            /* Create the program to which the project in light mode belongs. */
            if($mode == 'light' && empty($program)) $program = $this->loadModel('program')->createDefaultProgram();

            $this->loadModel('setting')->setItem('system.common.global.mode', $mode);
            $this->setting->setItem('system.common.global.defaultProgram', $program);

            $this->custom->disableFeaturesByMode($mode);

            if($mode == 'light') $this->custom->processProjectAcl();
            if($mode == 'ALM')   $this->loadModel('setting')->setItem('system.common.closedFeatures', 'otherOA');

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('callback' => '$.apps.updateAppsMenu(true);', 'load' => true, 'closeModel' => true));
        }

        list($disabledFeatures, $enabledProjectFeatures, $disabledProjectFeatures) = $this->custom->computeFeatures();

        $this->view->title                   = $this->lang->custom->mode;
        $this->view->mode                    = $mode;
        $this->view->programs                = $this->loadModel('program')->getTopPairs('noclosed', true);
        $this->view->programID               = isset($this->config->global->defaultProgram) ? $this->config->global->defaultProgram : 0;
        $this->view->disabledFeatures        = $disabledFeatures;
        $this->view->enabledProjectFeatures  = $enabledProjectFeatures;
        $this->view->disabledProjectFeatures = $disabledProjectFeatures;
        $this->view->currentModeTips         = sprintf($this->lang->custom->currentModeTips, $this->lang->custom->modeList[$mode], $this->lang->custom->modeList[$mode == 'light' ? 'ALM' : 'light']);

        $this->display();
    }

    /**
     * 保存自定义字段。
     * Ajax save custom fields.
     *
     * @param  string $module
     * @param  string $section
     * @param  string $key
     * @access public
     * @return void
     */
    public function ajaxSaveCustomFields(string $module, string $section, string $key)
    {
        if(!isset($this->config->custom->customFields[$module][$section])) return;
        if(!in_array($key, $this->config->custom->customFields[$module][$section])) return;

        $this->loadModel('setting');
        $global  = $this->post->global;
        $action  = $this->post->action;
        $account = $this->app->user->account;
        if($global) $account = 'system';
        if($this->server->request_method == 'POST' && $action != 'reset')
        {
            $fields = $this->post->fields;
            if(is_array($fields)) $fields = implode(',', $fields);
            if($module == 'execution' && $section == 'custom' && $key == 'createFields' && strpos(",{$fields},", ',team,') !== false) $fields .= ',teams';

            $this->setting->setItem("{$account}.{$module}.{$section}.{$key}", $fields);

            if(in_array($module, array('story', 'task', 'testcase')) && $section == 'custom' && $key == 'batchCreateFields') return;
            if($module == 'bug' && $section == 'custom' && $key == 'batchCreateFields') return;
        }
        else
        {
            $this->setting->deleteItems("owner={$account}&module={$module}&section={$section}&key={$key}");
        }

        $this->loadModel('common')->loadConfigFromDB();
        $this->app->loadLang($module);
        $this->app->loadConfig($module, '', true);

        if($module == 'programplan' && $section == 'custom') $key = 'createFields';
        $customFields = zget(zget($this->config->$module, 'list', array()), $section . ucfirst($key), '');
        $showFields   = zget(zget($this->config->$module, $section, array()), $key, '');
        if($module == 'marketresearch') return print(js::reload('parent'));
        return $this->send(array('result' => 'success', 'key' => $key, 'callback' => 'loadCurrentPage', 'customFields' => $customFields, 'showFields' => $showFields));
    }

    /**
     * 获取自定义列。
     * Ajax get custom fields.
     *
     * @param  string $module
     * @param  string $section
     * @param  string $key
     * @access public
     * @return void
     */
    public function ajaxGetCustomFields(string $module, string $section, string $key)
    {
        return print($this->loadModel('setting')->getItem("owner={$this->app->user->account}&module={$module}&section={$section}&key={$key}"));
    }

    /**
     * 重置必填字段。
     * Reset required.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function resetRequired(string $module)
    {
        $this->loadModel('setting')->deleteItems("owner=system&module={$module}&key=requiredFields");
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 设置代号。
     * Set code.
     *
     * @access public
     * @return void
     */
    public function code()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.setCode', $this->post->code);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->custom->code;

        $this->display();
    }

    /**
     * 设置是否启用工作量占比。
     * Set whether to enable the workload percent.
     * @access public
     * @return void
     */
    public function percent()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.setPercent', $this->post->percent);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->stage->percent;

        $this->display();
    }

    /**
     * 设置每日可用工时和休息日。
     * Set hours and weekend
     *
     * @param  string $type hours|weekend
     * @access public
     * @return void
     */
    public function hours(string $type = 'hours')
    {
        if($_POST)
        {
            $data = $_POST;
            $type = $_POST['type'];

            unset($data['type']);
            if($data['weekend'] != 1) unset($data['restDay']);

            if($type == 'hours' && ($data['defaultWorkhours'] < 0 || $data['defaultWorkhours'] > 24)) $this->sendError($this->lang->custom->hoursError);

            $this->loadModel('setting')->setItems('system.execution', $data);
            return $this->sendSuccess(array('load' => inLink('hours', "type={$type}")));
        }

        $this->app->loadConfig('execution');

        $this->view->title     = $this->lang->workingHour;
        $this->view->type      = $type;
        $this->view->weekend   = $this->config->execution->weekend;
        $this->view->workhours = $this->config->execution->defaultWorkhours;
        $this->view->restDay   = zget($this->config->execution, 'restDay', 0);
        $this->view->module    = 'setDate';

        $this->display();
    }

    /**
     * 设置是否限制任务开始和结束时间。
     * Set whether the task begin and end date is limited to the execution begin and end date.
     *
     * @access public
     * @return void
     */
    public function limitTaskDate()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.limitTaskDate', $this->post->limitTaskDate);
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->custom->beginAndEndDate;
        $this->view->module = 'task';

        $this->display();
    }

    /**
     * Ajax set menu
     *
     * @access public
     * @return void
     */
    public function ajaxSetMenu()
    {
        if($_POST)
        {
            $menu    = $this->post->menu;  // 导航类型，nav(左侧主导航)|$app(顶部一级导航)|$app-home(项目集、项目的首页导航)|$app-$subMenu（顶部二级导航）|admin-$menuKey(后台导航)
            $items   = $this->post->items; // 导航项
            $account = $this->app->user->account;
            $oldMenu = isset($this->config->customMenu->{$menu}) ? $this->config->customMenu->{$menu} : '';

            /* 之前隐藏的导航若没开启继续保持隐藏。 */
            if($oldMenu)
            {
                $oldMenus = json_decode($oldMenu);
                $menus    = json_decode($items);

                $menuNames = array();
                foreach($menus as $item) $menuNames[] = $item->name;

                foreach($oldMenus as $key => $item)
                {
                    if(!empty($item->hidden) && !in_array($item->name, $menuNames)) $menus[] = $item;
                }

                $items = json_encode($menus);
            }

            if($menu && $items) $this->loadModel('setting')->setItem("$account.common.customMenu.$menu@{$this->config->vision}", $items);
        }

        $this->send(array('result' => 'success'));
    }

    /**
     * Ajax restore menu
     *
     * @access public
     * @return void
     */
    public function ajaxRestoreMenu()
    {
        if($_POST)
        {
            $account = $this->app->user->account;
            $menu    = $this->post->menu;
            $this->loadModel('setting')->deleteItems("owner={$account}&module=common&section=customMenu&key=$menu");
        }

        $this->send(array('result' => 'success'));
    }
}
