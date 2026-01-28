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
        $this->loadModel($currentModule);

        if($moduleName == 'execution' && $methodName == 'task' && $this->config->vision != 'lite') $this->view->execution = $this->execution->getByID($this->session->execution);
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

            $module = $this->post->currentModule;
            $method = $this->post->currentMethod;

            $this->app->checkModuleName($module);
            $this->app->checkMethodName($method);

            $this->loadModel('setting')->setItem($account . '.' . $module . '.' . $method . '.showModule', $this->post->value);
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
            if($rawModule == 'story' && $extra && $rawModule != $extra) $rawModule = $extra;

            $cols       = $this->datatable->getSetting($module, $method, true, $extra);
            $postFields = json_decode($this->post->fields);

            /* 生成配置信息。 */
            $fields = array();
            $attrs  = array('order', 'width', 'show', 'extraWidth', 'border'); // 需要保存的配置属性。
            foreach($cols as $id => $col)
            {
                $fields[$id] = array();
                foreach($attrs as $attr)
                {
                    if($attr == 'show' && isset($col['required']) && $col['required']) $col['show'] = true;
                    if(!isset($col[$attr])) continue;
                    $fields[$id][$attr] = $col[$attr];
                }
            }
            foreach($postFields as $field)
            {
                $id = $field->id;
                if($module == 'testcase' && $id == 'caseID') $id = 'id';
                if(!isset($fields[$id])) continue;

                foreach($attrs as $attr)
                {
                    if(!isset($field->$attr)) continue;
                    if($attr == 'show') $field->$attr = $field->$attr ? true : false;
                    $fields[$id][$attr] = $field->$attr;
                }
            }

            $name  = 'datatable.' . $module . ucfirst($method) . '.cols';
            $value = json_encode($fields);

            /* Split story and requirement custom fields. */
            if(("$module-$method" == 'product-browse') && in_array($extra, array('story', 'requirement', 'epic'))) $name = 'datatable.' . $module . ucfirst($method) . ucfirst($extra) . '.cols';

            /* 保存个人配置信息。 */
            $this->loadModel('setting')->setItem($account . '.' . $name, $value);

            /* 保存全局配置信息。 */
            if($this->post->global) $this->setting->setItem('system.' . $name, $value);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => 'dao error.'));

            $load = "$module-$method" == 'my-effort' ? $this->createLink('my', 'effort') : true;
            return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => $load));
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

        if($module == 'testcase')
        {
            unset($cols['assignedTo']);
            unset($cols['product']);
            unset($cols['module']);
        }
        if($module == 'bug')
        {
            unset($cols['product']);
            unset($cols['module']);
        }
        if(zget($this->config->datatable->moduleAlias, "$module-$method", $module) == 'story')
        {
            unset($cols['product'], $cols['module']);
            if($extra != 'story')
            {
                foreach(array('taskCount', 'bugCount', 'caseCount') as $field) unset($cols[$field]);
                $cols['title']['title'] = $cols['title']['title'] = $this->lang->story->name;
            }

            if($this->app->tab != 'product') $cols['title']['title'] = $this->lang->story->name;
        }

        if(($module == 'productplan' && $method == 'browse') || ($module == 'project' && $method == 'bug'))
        {
            $branchField = $module == 'productplan' ? 'branchName' : 'branch';
            if($this->session->currentProductType == 'normal')
            {
                unset($cols[$branchField]);
            }
            else
            {
                $this->app->loadLang('product');
                $cols[$branchField]['title'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$this->session->currentProductType]);
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
            unset($cols['execution']);
            unset($cols['branch']);
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

        if($module == 'project' && $method == 'execution')
        {
            $project = $this->datatable->fetchByID($this->session->project, 'project');
            if(!empty($project->isTpl)) unset($cols['deliverable']);
        }

        if($module == 'deliverable')
        {
            $hasProcess = $this->loadModel('workflowgroup')->hasFeature((int)$extra, 'process');
            if(!$hasProcess) unset($cols['activity'], $cols['trimmable'], $cols['trimRule']);
        }

        if($extra == 'unsetStory' && isset($cols['story'])) unset($cols['story']);

        if($this->config->edition == 'ipd' && $module == 'product' && $method == 'browse' && $extra == 'story') unset($cols['roadmap']);
        if($this->app->tab == 'project' && !$this->session->multiple && $module == 'meeting' && $method == 'browse') unset($cols['execution']);

        $this->view->module = $module;
        $this->view->method = $method;
        $this->view->cols   = $cols;
        $this->view->extra  = $extra;
        $this->display();
    }

    /**
     * Save config
     *
     * @access public
     * @return void
     */
    public function ajaxOldSave()
    {
        if(!empty($_POST))
        {
            $account = $this->app->user->account;
            if($account == 'guest') return $this->send(array('result' => 'fail', 'target' => $target, 'message' => 'guest.'));

            $name = 'datatable.' . $this->post->target . '.' . $this->post->name;
            $this->loadModel('setting')->setItem($account . '.' . $name, $this->post->value);
            if($this->post->allModule !== false) $this->setting->setItem("$account.execution.task.allModule", $this->post->allModule);
            if($this->post->showBranch !== false) $this->setting->setItem($account . '.' . $this->post->currentModule . '.' . $this->post->currentMethod . '.showBranch', $this->post->showBranch);
            if($this->post->global) $this->setting->setItem('system.' . $name, $this->post->value);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => 'dao error.'));
            return $this->send(array('result' => 'success'));
        }
    }

    /**
     * custom fields.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxOldCustom(string $module, string $method, string $extra = '')
    {
        $moduleName = $module;
        $target     = $module . ucfirst($method);
        $mode       = isset($this->config->datatable->$target->mode) ? $this->config->datatable->$target->mode : 'table';
        $key        = $mode == 'datatable' ? 'cols' : 'tablecols';

        if($module == 'testtask')
        {
            $this->loadModel('testcase');
            $this->app->loadConfig('testtask');
            $this->config->testcase->datatable->defaultField = $this->config->testtask->datatable->defaultField;
            $this->config->testcase->datatable->fieldList['actions']['width'] = '100';
            $this->config->testcase->datatable->fieldList['status']['width']  = '90';
        }
        if($module == 'testcase')
        {
            $this->loadModel('testcase');
            unset($this->config->testcase->datatable->fieldList['assignedTo']);
        }

        $this->view->module = $module;
        $this->view->method = $method;
        $this->view->mode   = $mode;

        $module  = zget($this->config->datatable->moduleAlias, "$module-$method", $module);
        $setting = '';
        if(isset($this->config->datatable->$target->$key)) $setting = $this->config->datatable->$target->$key;
        if(empty($setting))
        {
            $this->loadModel($module);
            $setting = json_encode($this->config->$module->datatable->defaultField);
        }

        $cols = $this->datatable->getOldFieldList($module);

        if($module == 'story' && $extra != 'requirement') unset($cols['SRS']);

        if($extra == 'requirement')
        {
            unset($cols['plan']);
            unset($cols['stage']);
            unset($cols['taskCount']);
            unset($cols['bugCount']);
            unset($cols['caseCount']);
            unset($cols['URS']);

            $cols['title']['title'] = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
        }

        if($moduleName == 'project' and $method == 'bug')
        {
            $project = $this->loadModel('project')->getByID($this->session->project);

            if(!$project->multiple) unset($cols['execution']);
            if(!$project->hasProduct and $project->model != 'scrum') unset($cols['plan']);
            if(!$project->hasProduct) unset($cols['branch']);
        }

        if($moduleName == 'execution' and $method == 'bug')
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            $project   = $this->loadModel('project')->getByID($execution->project);
            if(!$project->hasProduct and $project->model != 'scrum') unset($cols['plan']);
            if(!$project->hasProduct) unset($cols['branch']);
        }

        if($moduleName == 'execution' and $method == 'story')
        {
            $execution = $this->loadModel('execution')->getByID($this->session->execution);
            if(!$execution->hasProduct and !$execution->multiple) unset($cols['plan']);
            if(!$execution->hasProduct) unset($cols['branch']);
        }
        if($moduleName == 'auditplan' && $this->app->tab == 'project' && !$this->session->multiple) unset($cols['execution']);
        if($extra == 'unsetStory' and isset($cols['story'])) unset($cols['story']);

        $this->view->cols    = $cols;
        $this->view->setting = $setting;
        $this->display();
    }

    /**
     * 恢复自定义项为默认配置。
     * Ajax reset cols.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $system
     * @param  string $confirm
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxReset(string $module, string $method, int $system = 0, string $confirm = 'no', string $extra = '')
    {
        if($confirm != 'yes')
        {
            $confirmURL = $this->createLink('datatable', 'ajaxreset', "module={$module}&method={$method}&system={$system}&confirm=yes&extra={$extra}");
            $tip        = (int)$system ? $this->lang->datatable->confirmGlobalReset : $this->lang->datatable->confirmReset;
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$tip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }

        $account = !$system ? $this->app->user->account : "system,{$this->app->user->account}";
        $target  = $module . ucfirst($method);

        $this->loadModel('setting')->deleteItems("owner={$account}&module=datatable&section={$target}&key=cols");

        /* Delete story and requirement custom fields. */
        if(strpos(',product-browse,execution-story,', ",$module-$method,") !== false)
        {
            $extra   = in_array($extra, array('story', 'requirement', 'epic')) ? ucfirst($extra) : 'Story';
            $section = $module . ucfirst($method) . $extra;
            $this->loadModel('setting')->deleteItems("owner={$account}&module=datatable&section={$section}&key=cols");
        }
        return $this->send(array('result' => 'success', 'load' => true, 'callback' => "$('#table-$module-$method,[zui-create-dtable]').first().closest('[z-use-dtable]').attr('zui-create-dtable', '')"));
    }

    /**
     * Ajax reset old cols.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $system
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxOldReset(string $module, string $method, int $system = 0, string $confirm = 'no')
    {
        if($confirm == 'no') return print(js::confirm($this->lang->datatable->confirmReset, inlink('ajaxOldReset', "module=$module&method=$method&system=$system&confirm=yes")));

        $account = $this->app->user->account;
        $target  = $module . ucfirst($method);
        $mode    = isset($this->config->datatable->$target->mode) ? $this->config->datatable->$target->mode : 'table';
        $key     = $mode == 'datatable' ? 'cols' : 'tablecols';

        $this->loadModel('setting')->deleteItems("owner=$account&module=datatable&section=$target&key=$key");
        if($system) $this->setting->deleteItems("owner=system&module=datatable&section=$target&key=$key");
        return print(js::reload('parent'));
    }

    /**
     * 应用自定义列配置到全局。
     * Ajax save setting to global.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxSaveGlobal(string $module, string $method, string $extra = '', string $confirm = 'no')
    {
        if($confirm != 'yes')
        {
            $confirmURL = $this->createLink('datatable', 'ajaxsaveglobal', "module={$module}&method={$method}&extra={$extra}&confirm=yes");
            $tip        = $this->lang->datatable->confirmSetGlobal;
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$tip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }

        $target = $module . ucfirst($method);
        if(strpos(',product-browse,execution-story,', ",$module-$method,") !== false && strpos(',story,requirement,epic,', ",$extra,") !== false) $target .= ucfirst($extra);

        $settings = isset($this->config->datatable->$target->cols) ? $this->config->datatable->$target->cols : '';
        if(!empty($settings)) $this->loadModel('setting')->setItem("system.datatable.{$target}.cols", $settings);

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }
}
