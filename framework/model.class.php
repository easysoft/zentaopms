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
     * 加载一个模块的model。加载完成后，使用$this->$moduleName来访问这个model对象。
     * 比如：loadModel('user')引入user模块的model实例对象，可以通过$this->user来访问它。
     *
     * Load the model of one module. After loaded, can use $this->$moduleName to visit the model object.
     *
     * Extension: set appName as empty.
     *
     * @param   string  $moduleName
     * @access  public
     * @return  object|bool  the model object or false if model file not exists.
     */
    public function loadModel($moduleName, $appName = '')
    {
        $appName = '';

        if(empty($moduleName)) return false;
        if(empty($appName)) $appName = $this->appName;

        global $loadedModels;
        if(isset($loadedModels[$appName][$moduleName]))
        {
            $this->$moduleName = $loadedModels[$appName][$moduleName];
            return $this->$moduleName;
        }

        $modelFile = $this->app->setModelFile($moduleName, $appName);

        if(!helper::import($modelFile)) return false;
        $modelClass = class_exists('ext' . $appName . $moduleName. 'model') ? 'ext' . $appName . $moduleName . 'model' : $appName . $moduleName . 'model';
        if(!class_exists($modelClass))
        {
            $modelClass = class_exists('ext' . $moduleName. 'model') ? 'ext' . $moduleName . 'model' : $moduleName . 'model';
            if(!class_exists($modelClass)) $this->app->triggerError(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        }

        $loadedModels[$appName][$moduleName] = new $modelClass($appName);
        $this->$moduleName = $loadedModels[$appName][$moduleName];
        return $this->$moduleName;
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
}
