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
        /* 如果设置了请求的原始模块名，则把其赋值给$this->moduleName。*/
        /* If the original module name of the request is set, assign it to $this->moduleName. */
        if(isset($this->app->rawModule))
        {
            $this->moduleName = $this->app->rawModule;
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
        /* 如果设置了请求的原始方法名，则把其赋值给$this->methodName。*/
        /* If the original method name of the request is set, assign it to $this->methodName. */
        if(isset($this->app->rawMethod))
        {
            $this->methodName = $this->app->rawMethod;
        }
        else
        {
            $this->methodName = $this->app->getMethodName();
        }
    }

    /**
     * 如果设置了请求的原始模块名和方法名，则去掉module参数，以便分页功能生成原始请求的URL而不是转换后的工作流URL。
     * If the original module name and method name of the request are set, the module parameter is removed so that
     * the paging function generates the URL of the original request instead of the converted workflow URL. 
     *
     * @access public
     * @return void
     */
    public function setParams($params = array())
    {
        if(isset($this->app->rawParams))
        {
            parent::setParams($this->app->rawParams);
        }
        else
        {
            parent::setParams();
        }

        /* If the original module name and method name of the request are set, the module parameter is removed. */
        if($this->app->isFlow) unset($this->params['module']);
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
