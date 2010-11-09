<?php
/**
 * The model class file of ZenTaoPHP.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: model.class.php 135 2010-09-14 03:23:35Z yuren_@126.com $
 * @link        http://www.zentao.net
 */
/**
 * 模型基类。
 * 
 * @package ZenTaoPHP
 */
class model
{
    /**
     * 全局的$app对象。
     * 
     * @var object
     * @access protected
     */
    protected $app;

    /**
     * 全局的$config对象。 
     * 
     * @var object
     * @access protected
     */
    protected $config;

    /**
     * 全局的$lang对象。
     * 
     * @var object
     * @access protected
     */
    protected $lang;

    /**
     * 全局的$dbh（数据库访问句柄）对象。
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * dao对象。
     * 
     * @var object
     * @access protected
     */
    public $dao;

    /**
     * POST对象。
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * get对象。
     * 
     * @var ojbect
     * @access public
     */
    public $get;

    /**
     * session对象。
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * server对象。
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * cookie对象。
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;

    /**
     * global对象。
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * 构造函数：
     *
     * 1. 引用全局变量，使之可以通过成员属性访问。
     * 2. 设置当前模块的路径、配置、语言等信息，并加载相应的文件。
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config, $lang, $dbh;
        $this->app    = $app;
        $this->config = $config;
        $this->lang   = $lang;
        $this->dbh    = $dbh;

        $moduleName = $this->getModuleName();
        $this->app->loadLang($moduleName,   $exit = false);
        $this->app->loadConfig($moduleName, $exit = false);
     
        if(isset($config->db->dao)   and $config->db->dao)   $this->loadDAO();
        if(isset($config->super2OBJ) and $config->super2OBJ) $this->setSuperVars();
    }

    /**
     * 设置模块名：将类名中的model替换掉即为模块名。
     * 没有使用$app->getModule()方法，因为它返回的是当前调用的模块。
     * 而在一次请求中，当前模块的control文件很有可能会调用其他模块的model。
     * 
     * @access protected
     * @return void
     */
    protected function getModuleName()
    {
        $parentClass = get_parent_class($this);
        $selfClass   = get_class($this);
        $className   = $parentClass == 'model' ? $selfClass : $parentClass;
        return strtolower(str_ireplace(array('ext', 'Model'), '', $className));
    }

    /**
     * 设置超全局变量。
     * 
     * @access protected
     * @return void
     */
    protected function setSuperVars()
    {
        $this->post    = $this->app->post;
        $this->get     = $this->app->get;
        $this->server  = $this->app->server;
        $this->cookie  = $this->app->cookie;
        $this->session = $this->app->session;
        $this->global  = $this->app->global;
    }

    /**
     * 加载某一个模块的model文件。
     * 
     * @param   string  $moduleName     模块名字，如果为空，则取当前的模块名作为model名。
     * @access  public
     * @return  void
     */
    public function loadModel($moduleName)
    {
        if(empty($moduleName)) return false;
        $modelFile = helper::setModelFile($moduleName);
        if(!file_exists($modelFile)) return false;

        helper::import($modelFile);
        $modelClass = class_exists('ext' . $moduleName. 'model') ? 'ext' . $moduleName . 'model' : $moduleName . 'model';
        if(!class_exists($modelClass)) $this->app->error(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);

        $this->$moduleName = new $modelClass();
        return $this->$moduleName;
    }

    //-------------------- 数据库操作相应的方法。--------------------//

    /**
     * 加载DAO类，并返回对象。
     * 
     * @access private
     * @return void
     */
    private function loadDAO()
    {
        $this->dao = $this->app->loadClass('dao');
    }

    /* 将一条记录标记为已删除。*/
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = str_replace($this->config->db->prefix, '', $table);
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
    }

    /* 还原已经标记为删除的记录。*/
    public function undelete($actionID)
    {
        $action = $this->loadModel('action')->getById($actionID);
        if($action->action != 'deleted') return;
        $table = $this->config->action->objectTables[$action->objectType];
        $this->dao->update($table)->set('deleted')->eq(0)->where('id')->eq($action->objectID)->exec();
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->action->create($action->objectType, $action->objectID, 'undeleted');
    }
}    
