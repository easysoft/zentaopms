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
     * 分页查询起始偏移量。
     * offset
     *
     * @var float
     * @access public
     */
    public $offset = 0;

    /**
     * 设置分页查询起始偏移量。
     * Set offset of paging query.
     *
     * @param  int    $offset
     * @access public
     * @return void
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

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
     * 创建limit语句。
     * Create the limit string.
     *
     * @access public
     * @return string
     */
    public function limit()
    {
        $limit = '';
        if($this->pageTotal > 1) $limit = ' lImiT ' . ($this->offset + ($this->pageID - 1) * $this->recPerPage) . ", $this->recPerPage";
        return $limit;
    }

    /**
     * Show pager.
     *
     * @param  string $align
     * @param  string $type
     * @param  int    $maxRecPerPage
     * @access public
     * @return void
     */
    public function show($align = 'right', $type = 'full', $maxRecPerPage = 0)
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
                $pageSizeOptions = '';
                if($maxRecPerPage)
                {
                    /* Set record per page. */
                    for($i = 5; $i <= 50; $i += 5) $options[] = $i;
                    $options = array_merge($options, array(100, 200, 500, 1000, 2000));

                    $pageSizeOptions = 'data-page-size-options="';
                    foreach($options as $option)
                    {
                        $pageSizeOptions .= "$option,";
                        if($option >= $maxRecPerPage)
                        {
                            $pageSizeOptions = trim($pageSizeOptions, ',') . '"';
                            break;
                        }
                    }
                }

                global $app, $lang;
                $appendApp  = '';
                $moduleName = $this->moduleName;
                if(isset($lang->navGroup->{$moduleName}) and $lang->navGroup->{$moduleName} != $app->tab) $appendApp = "#app={$app->tab}";
                echo "<ul class='pager' $pageSizeOptions data-page-cookie='{$this->pageCookie}' data-ride='pager' data-rec-total='{$this->recTotal}' data-rec-per-page='{$this->recPerPage}' data-page='{$this->pageID}' data-link-creator='" . helper::createLink($this->moduleName, $this->methodName, $params) . $appendApp . "'></ul>";
            }
        }
        else
        {
            parent::show($align, $type);
        }
    }
}
