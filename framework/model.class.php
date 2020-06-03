<?php
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
include dirname(__FILE__) . '/base/model.class.php';
class model extends baseModel
{
    /**
     * 企业版部分功能是从然之合并过来的。然之代码中调用loadModel方法时传递了一个非空的appName，在禅道中会导致错误。
     * 调用父类的loadModel方法来避免这个错误。
     * Some codes merged from ranzhi called the function loadModel with a non-empty appName which causes an error in zentao.
     * Call the parent function with empty appName to avoid this error.
     *
     * @param   string  $moduleName
     * @access  public
     * @return  object|bool  the model object or false if model file not exists.
     */
    public function loadModel($moduleName, $appName = '')
    {
        return parent::loadModel($moduleName);
    }

    /**
     * 删除记录
     * Delete one record.
     *
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return void
     */
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = preg_replace('/^' . preg_quote($this->config->db->prefix) . '/', '', trim($table, '`'));
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
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
     * Check flow rule.
     * 
     * @param  object $field 
     * @param  string $value 
     * @access public
     * @return bool|string
     */
    public function checkFlowRule($field, $value)
    {
        if(!isset($this->config->bizVersion)) return false;

        return $this->loadModel('flow')->checkRule($field, $value);
    }

    /**
     * Execute Hooks 
     * 
     * @param  int    $objectID 
     * @access public
     * @return void
     */
    public function executeHooks($objectID)
    {
        if(!isset($this->config->bizVersion)) return false;

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName);
        if(empty($action) or $action->extensionType == 'none') return false;

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if($flow && $action) $this->loadModel('workflowhook')->execute($flow, $action, $objectID);
    }
}
