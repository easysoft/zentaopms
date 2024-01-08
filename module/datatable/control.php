<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Hao sun <sunhao@cnezsoft.com>
 * @package     datatable
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class datatable extends control
{
    /**
     * 设置列表页是否显示模块名。
     * Set display.
     *
     * @param  string $datatableID
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $currentModule
     * @param  string $currentMethod
     * @access public
     * @return void
     */
    public function ajaxDisplay(string $datatableID, string $moduleName, string $methodName, string $currentModule, string $currentMethod)
    {
        $this->app->loadLang($currentModule);
        $this->app->loadConfig($currentModule);

        if($currentModule == 'product'      && $currentMethod == 'browse') $this->view->showBranch = $this->loadModel('branch')->showBranch($this->session->product);
        if($currentModule == 'projectstory' && $currentMethod == 'story')  $this->view->showBranch = $this->loadModel('branch')->showBranch(0, 0, $this->session->project);

        $this->view->datatableID   = $datatableID;
        $this->view->moduleName    = $moduleName;
        $this->view->methodName    = $methodName;
        $this->view->currentModule = $currentModule;
        $this->view->currentMethod = $currentMethod;
        $this->render();
    }

    /**
     * 保存列表页是否显示模块名的配置项。
     * Save config
     *
     * @access public
     * @return void
     */
    public function ajaxSave()
    {
        if(!empty($_POST))
        {
            $account = $this->app->user->account;
            if($account == 'guest') return $this->send(array('result' => 'fail', 'message' => 'guest.'));

            $this->loadModel('setting')->setItem($account . '.' . $this->post->currentModule . '.' . $this->post->currentMethod . '.showModule', $this->post->value);
            if($this->post->allModule !== false) $this->setting->setItem("$account.execution.task.allModule", $this->post->allModule);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => true));
        }
    }

    /**
     * 自定义列页面保存配置。
     * Ajax save fields.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxSaveFields(string $module, string $method, string $extra = '')
    {
        if(!empty($_POST))
        {
            $account = $this->app->user->account;
            if($account == 'guest') return $this->send(array('result' => 'fail', 'message' => 'guest.'));

            $rawModule  = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
            $fieldList  = $this->datatable->getFieldList($rawModule, $method);
            $postFields = json_decode($this->post->fields);

            /* 生成配置信息。 */
            $fields = array();
            foreach($postFields as $field)
            {
                $id = $field->id;
                if($module == 'testcase' && $id == 'caseID') $id = 'id';
                if(!isset($fieldList[$id])) continue;

                $fields[$id]['order'] = $field->order;
                $fields[$id]['width'] = $field->width;
                $fields[$id]['show']  = $field->show ? true : false;
            }

            $name  = 'datatable.' . $module . ucfirst($method) . '.cols';
            $value = json_encode($fields);

            /* Split story and requirement custom fields. */
            if($module == 'product' && $method == 'browse' && strpos(',story,requirement,', ",$extra,") !== false) $name = 'datatable.' . $module . ucfirst($method) . "-{$extra}" . '.cols';

            /* 保存个人配置信息。 */
            $this->loadModel('setting')->setItem($account . '.' . $name, $value);

            /* 保存全局配置信息。 */
            if($this->post->global) $this->setting->setItem('system.' . $name, $value);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => 'dao error.'));
            return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => true));
        }
    }

    /**
     * 自定义列配置页面。
     * custom fields.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxCustom(string $module, string $method, string $extra = '')
    {
        $cols = $this->datatable->getSetting($module, $method, true, $extra);
        if(!$method) $method = $this->app->getMethodName();

        if($module == 'testcase') unset($cols['assignedTo']);
        if(zget($this->config->datatable->moduleAlias, "$module-$method", $module) == 'story')
        {
            unset($cols['product'], $cols['module']);
            if($extra != 'requirement') unset($cols['SRS']);
            if($extra == 'requirement')
            {
                foreach(array('plan', 'stage', 'taskCount', 'bugCount', 'caseCount', 'URS') as $field) unset($cols[$field]);
                $cols['title']['title'] = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            }
        }

        if($module == 'productplan' && $method == 'browse')
        {
            if($this->session->currentProductType == 'normal')
            {
                unset($cols['branch']);
            }
            else
            {
                $this->app->loadLang('product');
                $cols['branch']['title'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$this->session->currentProductType]);
            }
        }

        if($module == 'project' && $method == 'bug')
        {
            $project = $this->loadModel('project')->getByID($this->session->project);

            if(!$project->multiple) unset($cols['execution']);
            if(!$project->hasProduct && ($project->model != 'scrum' || !$project->multiple)) unset($cols['plan']);
            if(!$project->hasProduct) unset($cols['branch']);
        }

        if($module == 'execution' && $method == 'bug')
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            $project   = $this->loadModel('project')->getByID($execution->project);
            if(!$project->hasProduct && $project->model != 'scrum') unset($cols['plan']);
            if(!$project->hasProduct) unset($cols['branch']);
        }

        if($module == 'execution' && $method == 'story')
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            if(!$execution->hasProduct && !$execution->multiple) unset($cols['plan']);
            if(!$execution->hasProduct) unset($cols['branch']);
        }

        if($extra == 'unsetStory' && isset($cols['story'])) unset($cols['story']);

        $this->view->module = $module;
        $this->view->method = $method;
        $this->view->cols   = $cols;
        $this->view->extra  = $extra;
        $this->display();
    }

    /**
     * 恢复自定义项为默认配置。
     * Ajax reset cols.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $system
     * @access public
     * @return void
     */
    public function ajaxReset(string $module, string $method, int $system = 0)
    {
        $account = !$system ? $this->app->user->account : 'system';
        $target  = $module . ucfirst($method);

        $this->loadModel('setting')->deleteItems("owner={$account}&module=datatable&section={$target}&key=cols");

        /* Delete story and requirement custom fields. */
        if($module == 'product' && $method == 'browse')
        {
            $storyCustom       = $module . ucfirst($method) . '-story';
            $requirementCustom = $module . ucfirst($method) . '-requirement';
            $this->loadModel('setting')->deleteItems("owner={$account}&module=datatable&section={$storyCustom}&key=cols");
            $this->loadModel('setting')->deleteItems("owner={$account}&module=datatable&section={$requirementCustom}&key=cols");
        }
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 应用自定义列配置到全局。
     * Ajax save setting to global.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxSaveGlobal(string $module, string $method, string $extra = '')
    {
        $target = $module . ucfirst($method);
        if($module == 'product' && $method == 'browse' && strpos(',story,requirement', ",$extra,") !== false) $target .= "-{$extra}";

        $settings = isset($this->config->datatable->$target->cols) ? $this->config->datatable->$target->cols : '';
        if(!empty($settings)) $this->loadModel('setting')->setItem("system.datatable.{$target}.cols", $settings);

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }
}
