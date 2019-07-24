<?php
/**
 * ZenTaoPHP的分页类。
 * The pager class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/pager/pager.class.php');
/**
 * pager类.
 * Pager class.
 * 
 * @package framework
 */
class pager extends basePager
{
    /**
     * 设置模块名。
     * Set the $moduleName property.
     * 
     * @access public
     * @return void
     */
    public function setModuleName()
    {
        if(isset($this->app->workflowModule))
        {
            $this->moduleName = $this->app->workflowModule;
        }
        else
        {
            $this->moduleName = $this->app->getModuleName();
        }
    }

    /**
     * 设置方法名。
     * Set the $methodName property.
     * 
     * @access public
     * @return void
     */
    public function setMethodName()
    {
        if(isset($this->app->workflowMethod))
        {
            $this->methodName = $this->app->workflowMethod;
        }
        else
        {
            $this->methodName = $this->app->getMethodName();
        }
    }

    /**
     * 从请求网址中获取记录总数、每页记录数、页码。
     * Get recTotal, recPerpage, pageID from the request params, and add them to params.
     * 
     * @access public
     * @return void
     */
    public function setParams()
    {
        parent::setParams();
        if(isset($this->app->workflowModule) && isset($this->app->workflowMethod))
        {
            unset($this->params['module']);
        }
    }

    /**
     * Show pager.
     * 
     * @param  string $align 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function show($align = 'right', $type = 'full')
    {
        if($type == 'pagerjs')
        {
            $this->setParams();
            $params = $this->params;
            foreach($params as $key => $value)
            {
                if(strtolower($key) == 'recperpage') $params[$key] = '{recPerPage}';
                if(strtolower($key) == 'pageid')     $params[$key] = '{page}';
            }
            if($this->recTotal == 0)
            {
                echo "<div class='pull-right'>" . $this->lang->pager->noRecord . '</div>';
            }
            else
            {
                echo "<ul class='pager' data-page-cookie='{$this->pageCookie}' data-ride='pager' data-rec-total='{$this->recTotal}' data-rec-per-page='{$this->recPerPage}' data-page='{$this->pageID}' data-link-creator='" . helper::createLink($this->moduleName, $this->methodName, $params) . "'></ul>";
            }
        }
        else
        {
            parent::show($align, $type);
        }
    }
}
