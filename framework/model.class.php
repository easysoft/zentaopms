<?php declare(strict_types=1);
/**
 * ZenTaoPHP的model类。
 * The model class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * model基类。
 * The base class of model.
 *
 * @package framework
 */
include __DIR__ . '/base/model.class.php';
class model extends baseModel
{
    /**
     * 企业版部分功能是从然之合并过来的。ZDOO代码中调用loadModel方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadModel方法来避免这个错误。
     * Some codes merged from ZDOO called the function loadModel with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool  the model object or false if model file not exists.
     */
    public function loadModel($moduleName, $appName = ''): object|bool
    {
        return parent::loadModel($moduleName);
    }

    /**
     * 企业版部分功能是从然之合并过来的。ZDOO代码中调用loadTao方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadTao方法来避免这个错误。
     * Some codes merged from ZDOO called the function loadTao with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param  string $moduleName 模块名，如果为空，使用当前模块。The module name, if empty, use current module's name.
     * @param  string $appName    应用名，如果为空，使用当前应用。The app name, if empty, use current app's name.
     * @access public
     * @return object|bool  the model object or false if model file not exists.
     */
    public function loadTao($moduleName, $appName = ''): object|bool
    {
        return parent::loadTao($moduleName);
    }

    /**
     * 删除记录
     * Delete one record.
     *
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return bool
     */
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = preg_replace('/^' . preg_quote((string) $this->config->db->prefix) . '/', '', trim($table, '`'));
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);

        return true;
    }

    /**
     * Build menu of a module.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $params
     * @param  object $data
     * @param  string $type
     * @param  string $icon
     * @param  string $target
     * @param  string $class
     * @param  bool   $onlyBody
     * @param  string $misc
     * @param  string $title
     * @param  bool   $returnHtml
     * @access public
     * @return string
     */

    public function buildMenu($moduleName, $methodName, $params, $data, $type = 'view', $icon = '', $target = '', $class = '', $onlyBody = false, $misc = '' , $title = '', $returnHtml = true)
    {
        if(str_contains($moduleName, '.')) [$appName, $moduleName] = explode('.', $moduleName);

        if(str_contains($methodName, '_') && strpos($methodName, '_') > 0) [$module, $method] = explode('_', $methodName);

        if(empty($module)) $module = $moduleName;
        if(empty($method)) $method = $methodName;

        static $actions = array();
        if(isset($this->config->bizVersion))
        {
            if(empty($actions[$moduleName]))
            {
                $actions[$moduleName] = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
                    ->where('module')->eq($moduleName)
                    ->andWhere('buildin')->eq('1')
                    ->andWhere('status')->eq('enable')
                    ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                    ->fetchAll('action');
            }
        }

        $enabled = true;
        if(!empty($actions) and isset($actions[$moduleName][$methodName]))
        {
            $action = $actions[$moduleName][$methodName];

            if($action->extensionType == 'override') return $this->loadModel('flow')->buildActionMenu($moduleName, $action, $data, $type);

            $conditions = json_decode((string) $action->conditions);
            if($conditions and $action->extensionType == 'extend')
            {
                if($icon != 'copy' and $methodName != 'create') $title = $action->name;
                if($conditions) $enabled = $this->loadModel('flow')->checkConditions($conditions, $data);
            }
            else
            {
                if(method_exists($this, 'isClickable')) $enabled = $this->isClickable($data, $method, $module);
            }
        }
        else
        {
            if(method_exists($this, 'isClickable')) $enabled = $this->isClickable($data, $method, $module);
        }

        if(!$returnHtml) return $enabled;

        $html = '';
        $type = $type == 'browse' ? 'list' : 'button';
        $html = common::buildIconButton($module, $method, $params, $data, $type, $icon, $target, $class, $onlyBody, $misc, $title, '', $enabled);
        return $html;
    }

    /**
     * Build menu of actions created by workflow action.
     *
     * @param  string $module
     * @param  int    $data
     * @param  string $type         browse | view
     * @param  string $show         direct | dropdownlist
     * @access public
     * @return string
     */
    public function buildFlowMenu($module, $data, $type = 'browse', $show = '')
    {
        if(!isset($this->config->bizVersion)) return '';

        $moduleName = $module;
        if(str_contains($module, '.')) [$appName, $moduleName] = explode('.', $module);

        static $actions;
        static $relations;
        if(empty($actions))
        {
            $actions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)
                ->where('module')->eq($moduleName)
                ->andWhere('buildin')->eq('0')
                ->andWhere('status')->eq('enable')
                ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                ->orderBy('order_asc')
                ->fetchAll();
        }
        if(empty($relations)) $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($moduleName)->fetchPairs();

        $this->loadModel('flow');

        $approvalProgressMenu = '';
        if($type == 'view' && !empty($this->config->openedApproval) && commonModel::hasPriv('approval', 'progress'))
        {
            $flow = $this->loadModel('workflow', 'flow')->getByModule($moduleName);
            if($flow->approval == 'enabled' && !empty($data->approval))
            {
                $extraClass = str_contains(',testsuite,build,release,productplan,', ",{$moduleName},") ? 'btn-link' : '';
                $approvalProgressMenu .= "<div class='divider'></div>";
                $approvalProgressMenu .= baseHTML::a(helper::createLink('approval', 'progress', "approvalID={$data->approval}", '', true), $this->lang->flow->approvalProgress, "class='btn {$extraClass} iframe'");
            }
        }

        $menu = '';
        if($show)
        {
            foreach($actions as $action)
            {
                if(!str_contains((string) $action->position, $type) || $action->show != $show) continue;

                $menu .= $this->flow->buildActionMenu($moduleName, $action, $data, $type, $relations);
            }

            if($approvalProgressMenu) $menu .= $approvalProgressMenu;
        }
        else
        {
            $dropdownMenu = '';
            foreach($actions as $action)
            {
                if(!str_contains((string) $action->position, $type)) continue;

                if($type == 'view' || $action->show == 'direct')         $menu         .= $this->flow->buildActionMenu($moduleName, $action, $data, $type, $relations);
                if($type == 'browse' && $action->show == 'dropdownlist') $dropdownMenu .= $this->flow->buildActionMenu($moduleName, $action, $data, $type, $relations);
            }

            if($approvalProgressMenu) $menu .= $approvalProgressMenu;

            if($type == 'browse' && $dropdownMenu)
            {
                $menu .= "<div class='dropdown'><a href='javascript:;' data-toggle='dropdown'>{$this->lang->more}<span class='caret'> </span></a>";
                $menu .= "<ul class='dropdown-menu pull-right'>{$dropdownMenu}</ul></div>";
            }
        }

        return $menu;
    }

    /**
     * Process status of an object according to its subStatus.
     *
     * @param  string $module   product | release | story | project | task | bug | testcase | testtask | feedback
     * @param  object $record   a record of above modules.
     * @access public
     * @return string
     */
    public function processStatus($module, $record)
    {
        if(!isset($this->config->bizVersion) or empty($record->subStatus)) return zget($this->lang->$module->statusList, $record->status);

        return $this->loadModel('workflowfield')->processSubStatus($module, $record);
    }

    /**
     * Process workflow export data.
     *
     * @param  object    $data
     * @access public
     * @return object
     */
    public function processExportData($data)
    {
        if(!isset($this->config->bizVersion)) return $data;

        return $this->loadModel('workflowfield')->processExportData($data);
    }

    /**
     * Process workflow export options.
     *
     * @param  object    $data
     * @access public
     * @return object
     */
    public function processExportOptions($data)
    {
        if(!isset($this->config->bizVersion)) return $data;

        return $this->loadModel('workflowfield')->processExportOptions($data);
    }

    /**
     * Process workflow import data.
     *
     * @param  object    $data
     * @access public
     * @return object
     */
    public function processImportData($data)
    {
        if(!isset($this->config->bizVersion)) return $data;

        return $this->loadModel('workflowfield')->processImportData($data);
    }

    /**
     * Get flow extend fields.
     *
     * @access public
     * @return array
     */
    public function getFlowExtendFields()
    {
        if(!isset($this->config->bizVersion)) return array();

        return $this->loadModel('flow')->getExtendFields($this->app->getModuleName(), $this->app->getMethodName());
    }

    /**
     * Set workflow export fields.
     *
     * @access public
     * @return string
     */
    public function getFlowExportFields()
    {
        if(!isset($this->config->bizVersion)) return array();

        return $this->loadModel('workflowfield')->getExportFields($this->app->getModuleName());
    }

    /**
     * Execute Hooks
     *
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function executeHooks(int $objectID)
    {
        if(!isset($this->config->bizVersion)) return false;

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName);
        if(empty($action) or $action->extensionType == 'none') return false;

        $this->loadModel('file');
        if($this->post->uid) $this->file->updateObjectID($this->post->uid, $objectID, $moduleName);
        $fields = $this->workflowaction->getFields($moduleName, $action->action, false);
        foreach($fields as $field)
        {
            if($field->control == 'file' && $field->show && !$field->readonly)
            {
                $this->file->saveUpload($moduleName, $objectID, $field->field, $field->field, $field->field);
            }
        }

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if($flow && $action) return $this->loadModel('workflowhook')->execute($flow, $action, $objectID);
    }

    /**
     * Call the functions declared in the tao files.
     *
     * @param  string $method
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $moduleName = $this->getModuleName();
        $taoClass   = $moduleName . 'Tao';

        if(is_callable(array($this->{$taoClass}, $method))) return call_user_func_array(array($this->{$taoClass}, $method), $arguments);

        $this->app->triggerError("the module {$moduleName} has no {$method} method", __FILE__, __LINE__, $exit = true);
    }

    /**
     * Call the static functions declared in the tao files.
     *
     * @param  string $method
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        global $app;

        $moduleName = strtolower(static::class);

        preg_match_all('/^(ext)?(\w+)model/', $moduleName, $matches);
        if(isset($matches[2][0]))
        {
            $moduleName = $matches[2][0];
        }
        else
        {
            preg_match_all('/^(ext)?(\w+)tao/', $moduleName, $matches);
            if(isset($matches[2][0])) $moduleName = $matches[2][0];
        }

        $modelClass = 'ext' . $moduleName . 'Model';
        if(method_exists($modelClass, $method)) return call_user_func_array("{$modelClass}::{$method}", $arguments);

        $taoClass = 'ext' . $moduleName . 'Tao';
        if(method_exists($taoClass, $method)) return call_user_func_array("{$taoClass}::{$method}", $arguments);

        $taoClass = $moduleName . 'Tao';
        if(method_exists($taoClass, $method)) return call_user_func_array("{$taoClass}::{$method}", $arguments);

        $app->triggerError("the module {$moduleName} has no {$method} method", __FILE__, __LINE__, $exit = true);
    }
}
