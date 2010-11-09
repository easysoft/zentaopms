<?php
/**
 * The pager class file of ZenTaoPHP.
 *
 * ZenTaoPHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoPHP.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: pager.class.php 134 2010-09-11 07:24:27Z wwccss $
 * @link        http://www.zentao.net
 */
/**
 * pager类，提供对数据库分页的操作。
 * 
 * @package ZenTaoPHP
 */
class pager
{
    /**
     * 默认每页显示记录数。
     *
     * @public int
     */
    const DEFAULT_REC_PRE_PAGE = 20;

    /**
     * 记录总数。
     *
     * @public int
     */
    public $recTotal;

    /**
     * 每页显示记录数。
     *
     * @public int
     */
    public $recPerPage;

    /**
     * 总共的页数。
     *
     * @public int
     */
    public $pageTotal;

    /**
     * 当前页数范围。
     *
     * @public int
     */
    public $pageID;

    /**
     * 全局的app对象。
     *
     * @private object
     */
    private $app;

    /**
     * 全局的lang对象。
     *
     * @private object
     */
    private $lang;

    /**
     * 当前请求的moduleName。
     *
     * @private string
     */
    private $moduleName;

    /**
     * 当前请求的methodName。
     *
     * @private string
     */
    private $methodName;

    /**
     * 当前请求的参数。
     *
     * @private array
     */
    private $params;

    /**
     * 构造函数。
     *
     * @param  int      $recTotal       记录总数
     * @param  int      $recPerPage     每页记录数。
     * @param  int      $pageID         当前分页ID。
     */
    public function __construct($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->setRecTotal($recTotal);
        $this->setRecPerPage($recPerPage);
        $this->setPageTotal();
        $this->setPageID($pageID);
        $this->setApp();
        $this->setLang();
        $this->setModuleName();
        $this->setMethodName();
    }

    /* factory. */
    public function init($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        return new pager($recTotal, $recPerPage, $pageID);
    }

    /* 设置记录总数。*/
    public function setRecTotal($recTotal = 0)
    {
        $this->recTotal = (int)$recTotal;
    }

    /* 设置每页显示的记录数。*/
    public function setRecPerPage($recPerPage)
    {
        $this->recPerPage = ($recPerPage > 0) ? $recPerPage : PAGER::DEFAULT_REC_PRE_PAGE;
    }

    /* 设置总共的页数。*/
    public function setPageTotal()
    {
        $this->pageTotal = ceil($this->recTotal / $this->recPerPage);
    }

    /* 设置当前的分页ID。*/
    public function setPageID($pageID)
    {
        if($pageID > 0 and $pageID <= $this->pageTotal)
        {
            $this->pageID = $pageID;
        }
        else
        {
            $this->pageID = 1;
        }
    }

    /* 设置app对象。*/
    private function setApp()
    {
        global $app;
        $this->app = $app;
    }

    /* 设置lang对象。*/
    private function setLang()
    {
        global $lang;
        $this->lang = $lang;
    }

    /* 设置moduleName。*/
    private function setModuleName()
    {
        $this->moduleName = $this->app->getModuleName();
    }

    /* 设置methodName。*/
    private function setMethodName()
    {
        $this->methodName = $this->app->getMethodName();
    }

    /* 设置params。备注：该方法应当在生成html代码之前被调用。*/
    private function setParams()
    {
        $this->params = $this->app->getParams();
        foreach($this->params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $this->params[$key] = $this->recTotal;
            if(strtolower($key) == 'recperpage') $this->params[$key] = $this->recPerPage;
            if(strtolower($key) == 'pageID')     $this->params[$key] = $this->pageID;
        }
    }

    /* 生成limit语句。*/
    public function limit()
    {
        $limit = '';
        if($this->pageTotal > 1) $limit = ' limit ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        return $limit;
    }
   
    /* 直接显示分页代码。*/
    public function show($align = 'right', $type = 'full')
    {
        echo $this->get($align, $type);
    }

    /**
     * 返回pager的html代码。
     *
     * @param  string $align  Alignment, left|center|right, the default is right.
     * @param  string $type   type, full|short|shortest.
     * @return string         The html code of the pager.
     */
    function get($align = 'right', $type = 'full')
    {
        /* If the RecTotal is zero, return with no record. */
        if($this->recTotal == 0) { return "<div style='float:$align; clear:none'>{$this->lang->pager->noRecord}</div>"; }

        /* 设置当前请求传递的参数。*/
        $this->setParams();
        
        /* 所有模式下都有的内容。*/
        $pager  = $this->createPrePage();
        $pager .= $this->createNextPage();

        /* short和full模式都有的内容。*/
        if($type !== 'shortest')
        {
            $pager  = $this->createFirstPage() . $pager;
            $pager .= $this->createLastPage();
        }

        /* Full模式有的内容。*/
        if($type == 'full')
        {
            $pager  = $this->createDigest() . $pager;
            $pager .= $this->createGoTo();
            $pager .= $this->createRecPerPageJS();
        }

        return "<div style='float:$align; clear:none'>$pager</div>";
    }

    /* 生成摘要代码。*/
    function createDigest()
    {
        return sprintf($this->lang->pager->digest, $this->recTotal, $this->createRecPerPageList(), $this->pageID, $this->pageTotal);
    }

    /* 生成首页链接。*/
    function createFirstPage()
    {
        if($this->pageID == 1) return $this->lang->pager->first . ' ';
        $this->params['pageID'] = 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->first);
    }

    /* 生成前页链接。*/
    function createPrePage()
    {
        if($this->pageID == 1) return $this->lang->pager->pre . ' ';
        $this->params['pageID'] = $this->pageID - 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->pre);
    }    

    /* 生成下页链接。*/
    function createNextPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->next . ' ';
        $this->params['pageID'] = $this->pageID + 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->next);
    }

    /* 生成末页链接。*/
    function createLastPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->last . ' ';
        $this->params['pageID'] = $this->pageTotal;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->last);
    }    

    /* 生成JS代码。*/
    function createRecPerPageJS()
    {
        /* 重新修正params，将其中关于分页的变量对应的值设置为特殊的标记，然后使用js将其替换为对应的值。*/
        $params = $this->params;
        foreach($params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $params[$key] = '_recTotal_';
            if(strtolower($key) == 'recperpage') $params[$key] = '_recPerPage_';
            if(strtolower($key) == 'pageid')     $params[$key] = '_pageID_';
        }
        $vars = '';
        foreach($params as $key => $value) $vars .= "$key=$value&";
        $vars = rtrim($vars, '&');

        $js  = <<<EOT
        <script language='Javascript'>
        vars = '$vars';
        function submitPage(mode)
        {
            pageTotal  = parseInt(document.getElementById('_pageTotal').value);
            pageID     = document.getElementById('_pageID').value;
            recPerPage = document.getElementById('_recPerPage').value;
            recTotal   = document.getElementById('_recTotal').value;
            if(mode == 'changePageID')
            {
                if(pageID > pageTotal) pageID = pageTotal;
                if(pageID < 1) pageID = 1;
            }
            else if(mode == 'changeRecPerPage')
            {
                pageID = 1;
            }

            vars = vars.replace('_recTotal_', recTotal)
            vars = vars.replace('_recPerPage_', recPerPage)
            vars = vars.replace('_pageID_', pageID);
            location.href=createLink('$this->moduleName', '$this->methodName', vars);
        }
        </script>
EOT;
        return $js;
    }

    /* Create the select list of RecPerPage. */
    function createRecPerPageList()
    {
        for($i = 5; $i <= 50; $i += 5) $range[$i] = $i;
        $range[100] = 100;
        $range[200] = 200;
        $range[500] = 500;
        return html::select('_recPerPage', $range, $this->recPerPage, "onchange='submitPage(\"changeRecPerPage\");'");
    }

    /* Create the link html code of goto box. */ 
    function createGoTo()
    {
        $goToHtml  = "<input type='hidden' id='_recTotal'  value='$this->recTotal' />\n";
        $goToHtml .= "<input type='hidden' id='_pageTotal' value='$this->pageTotal' />\n";
        $goToHtml .= "<input type='text'   id='_pageID'    value='$this->pageID' style='text-align:center;width:30px;' /> \n";
        $goToHtml .= "<input type='button' id='goto'       value='{$this->lang->pager->locate}' onclick='submitPage(\"changePageID\");' />";
        return $goToHtml;
    }    
}
