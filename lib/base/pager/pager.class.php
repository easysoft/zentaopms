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
/**
 * pager类.
 * Pager class.
 *
 * @package framework
 */
class basePager
{
    /**
     * 每页的默认显示记录数。
     * The default counts of per page.
     *
     * @public int
     */
    const DEFAULT_REC_PER_PAGE = 20;

    /**
     * 总个数。
     * The total counts.
     *
     * @var int
     * @access public
     */
    public $recTotal;

    /**
     * 每页的记录数。
     * Record count per page.
     *
     * @var int
     * @access public
     */
    public $recPerPage;

    /**
     * The cookie name of recPerPage.
     *
     * @var string
     * @access public
     */
    public $pageCookie;

    /**
     * 总页面数。
     * Page count.
     *
     * @var string
     * @access public
     */
    public $pageTotal;

    /**
     * 当前页码。
     * Current page id.
     *
     * @var string
     * @access public
     */
    public $pageID;

    /**
     * 全局变量$app。
     * The global $app.
     *
     * @var object
     * @access public
     */
    public $app;

    /**
     * 全局变量$lang。
     * The global $lang.
     *
     * @var object
     * @access public
     */
    public $lang;

    /**
     * 当前的模块名。
     * Current module name.
     *
     * @var string
     * @access public
     */
    public $moduleName;

    /**
     * 当前的方法名。
     * Current method.
     *
     * @var string
     * @access public
     */
    public $methodName;

    /**
     * 参数信息。
     * The params.
     *
     * @public array
     */
    public $params;

    /**
     * 构造方法。
     * The construct function.
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function __construct($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->setApp();
        $this->setLang();
        $this->setModuleName();
        $this->setMethodName();

        $this->setRecTotal((int)$recTotal);
        $this->setRecPerPage((int)$recPerPage);
        $this->setPageTotal();
        $this->setPageID((int)$pageID);
    }

    /**
     * 构造方法。
     * The factory function.
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return object
     */
    public static function init($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        return new pager($recTotal, $recPerPage, $pageID);
    }

    /**
     * 设置总记录数。
     * Set the recTotal property.
     *
     * @param  int    $recTotal
     * @access public
     * @return void
     */
    public function setRecTotal($recTotal = 0)
    {
        $this->recTotal = (int)$recTotal;
    }

    /**
     * 设置每页记录数。
     * Set the recPerPage property.
     *
     * @param  int    $recPerPage
     * @access public
     * @return void
     */
    public function setRecPerPage($recPerPage)
    {
        /* Set the cookie name. */
        $this->pageCookie = 'pager' . ucfirst($this->app->getModuleName()) . ucfirst($this->app->getMethodName());

        if(isset($_COOKIE[$this->pageCookie])) $recPerPage = $_COOKIE[$this->pageCookie];
        $this->recPerPage = ($recPerPage > 0) ? $recPerPage : PAGER::DEFAULT_REC_PER_PAGE;
    }

    /**
     * 设置总页数。
     * Set the pageTotal property.
     *
     * @access public
     * @return void
     */
    public function setPageTotal()
    {
        $this->pageTotal = ceil($this->recTotal / $this->recPerPage);
    }

    /**
     * 设置页码。
     * Set the page id.
     *
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function setPageID($pageID)
    {
        if($pageID > 0 and ($this->pageTotal == 0 or $pageID <= $this->pageTotal))
        {
            $this->pageID = $pageID;
        }
        else
        {
            $this->pageID = 1;
        }
    }

    /**
     * 设置全局变量$app。
     * Set the $app property;
     *
     * @access public
     * @return void
     */
    public function setApp()
    {
        global $app;
        $this->app = $app;
    }

    /**
     * 设置全局变量$lang。
     * Set the $lang property.
     *
     * @access public
     * @return void
     */
    public function setLang()
    {
        global $lang;
        $this->lang = $lang;
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
        $this->moduleName = $this->app->getModuleName();
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
        $this->methodName = $this->app->getMethodName();
    }

    /**
     * 从请求网址中获取记录总数、每页记录数、页码。
     * Get recTotal, recPerpage, pageID from the request params, and add them to params.
     *
     * @access public
     * @return void
     */
    public function setParams($params = array())
    {
        $this->params = $params ? $params : $this->app->getParams();
        foreach($this->params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $this->params[$key] = $this->recTotal;
            if(strtolower($key) == 'recperpage') $this->params[$key] = $this->recPerPage;
            if(strtolower($key) == 'pageid')     $this->params[$key] = $this->pageID;
        }
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
        if($this->pageTotal > 1) $limit = ' lImiT ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        return $limit;
    }

    /**
     * 向页面显示分页信息。
     * Print the pager's html.
     *
     * @param  string $align
     * @param  string $type
     * @access public
     * @return void
     */
    public function show($align = 'right', $type = 'full')
    {
        if($align === 'justify')
        {
            echo $this->getJustify($type);
        }
        else
        {
            echo $this->get($align, $type);
        }
    }

    /**
     * 获取优化后的分页。
     * Get the justify pager html string
     *
     * @access public
     * @return [type] [description]
     */
    public function getJustify()
    {
        if($this->recTotal <= 0) return '';

        $this->setParams();
        $pager = '';

        $pager .= "<li class='previous" . ($this->pageID == 1 ? ' disabled' : '') . "'>";
        $this->params['pageID'] = 1;
        $pager .= $this->createLink('« ' . $this->lang->pager->previousPage) . '</li>';

        $pager .= "<li class='caption'>";
        $firstId = $this->recPerPage * ($this->pageID - 1) + 1;
        $pager .= sprintf($this->lang->pager->summery, $firstId, max(min($this->recPerPage * $this->pageID, $this->recTotal), $firstId), $this->recTotal);
        $pager .= '</li>';

        $pager .= "<li class='next" . (($this->pageID == $this->pageTotal || $this->pageTotal <= 1) ? ' disabled' : '') . "'>";
        $this->params['pageID'] = min($this->pageTotal, $this->pageID + 1);
        $pager .= $this->createLink($this->lang->pager->nextPage . ' »') . '</li>';

        return "<ul class='pager pager-justify'>{$pager}</ul>";
    }

    /**
     * 设置分页信息的样式。
     * Get the pager html string.
     *
     * @param  string $align
     * @param  string $type     the pager type, full|short|shortest
     * @access public
     * @return string
     */
    public function get($align = 'right', $type = 'full')
    {
        /* 如果记录个数为0，返回没有记录。 */
        /* If the RecTotal is zero, return with no record. */
        if($this->recTotal == 0) return $type == 'mobile' ? '' : "<div style='float:$align; clear:none;' class='page'>{$this->lang->pager->noRecord}</div>";

        /* Set the params. */
        $this->setParams();

        /* 创建前一页和后一页链接。 */
        /* Create the prePage and nextpage, all types have them. */
        $pager  = $this->createPrePage($type);
        $pager .= $this->createNextPage($type);

        /* 简单和完全模式。  The short and full type. */
        if($type !== 'shortest' and $type !== 'mobile')
        {
            $pager  = $this->createFirstPage() . $pager;
            $pager .= $this->createLastPage();
        }

        if($type == 'mobile')
        {
            $position = $this->pageTotal == 1 ? '' : $this->pageID . '/' . $this->pageTotal;
            $pager    = $pager . ' ' . $position;
        }
        else if($type != 'full')
        {
            $pager = $this->pageID . '/' . $this->pageTotal . ' ' . $pager;
        }

        /* 只是完全模式。   Only the full type . */
        if($type == 'full')
        {
            $pager  = $this->createDigest() . $pager;
            $pager .= $this->createGoTo();
            $pager .= $this->createRecPerPageJS();
        }

        return "<div style='float:$align; clear:none;' class='pager form-inline'>$pager</div>";
    }

    /**
     * 生成分页摘要信息。
     * Create the digest code.
     *
     * @access public
     * @return string
     */
    public function createDigest()
    {
        return sprintf($this->lang->pager->digest, $this->recTotal, $this->createRecPerPageList(), $this->pageID, $this->pageTotal);
    }

    /**
     * 创建首页链接。
     * Create the first page.
     *
     * @access public
     * @return string
     */
    public function createFirstPage()
    {
        if($this->pageID == 1) return $this->lang->pager->first . ' ';
        $this->params['pageID'] = 1;
        return $this->createLink($this->lang->pager->first);
    }

    /**
     * 创建前一页链接。
     * Create the pre page html.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function createPrePage($type = 'full')
    {
        if($type == 'mobile')
        {
            if($this->pageID == 1) return '';
            $this->params['pageID'] = $this->pageID - 1;
            return $this->createLink($this->lang->pager->pre);
        }
        else
        {
            if($this->pageID == 1) return $this->lang->pager->pre . ' ';
            $this->params['pageID'] = $this->pageID - 1;
            return $this->createLink($this->lang->pager->pre);
        }
    }

    /**
     * 创建下一页链接。
     * Create the next page html.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function createNextPage($type = 'full')
    {
        if($type == 'mobile')
        {
            if($this->pageID == $this->pageTotal) return '';
            $this->params['pageID'] = $this->pageID + 1;
            return $this->createLink($this->lang->pager->next);
        }
        else
        {
            if($this->pageID == $this->pageTotal) return $this->lang->pager->next . ' ';
            $this->params['pageID'] = $this->pageID + 1;
            return $this->createLink($this->lang->pager->next);
        }
    }

    /**
     * 创建最后一页链接。
     * Create the last page
     *
     * @access public
     * @return string
     */
    public function createLastPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->last . ' ';
        $this->params['pageID'] = $this->pageTotal;
        return $this->createLink($this->lang->pager->last);
    }

    /**
     * 创建每页显示记录数的select标签。
     * Create the select object of record perpage.
     *
     * @access public
     * @return string
     */
    public function createRecPerPageJS()
    {
        /*
         * 替换recTotal, recPerPage, pageID为特殊的字符串，然后用js代码替换掉。
         * Replace the recTotal, recPerPage, pageID to special string, and then replace them with values by JS.
         **/
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
        pageCookie = '$this->pageCookie';
        function submitPage(mode, perPage)
        {
            pageTotal  = parseInt(document.getElementById('_pageTotal').value);
            pageID     = document.getElementById('_pageID').value;
            recPerPage = document.getElementById('_recPerPage').getAttribute('data-value');
            recTotal   = document.getElementById('_recTotal').value;
            if(mode == 'changePageID')
            {
                if(pageID > pageTotal) pageID = pageTotal;
                if(pageID < 1) pageID = 1;
            }
            else if(mode == 'changeRecPerPage')
            {
                recPerPage = perPage;
                pageID = 1;
            }
            $.cookie(pageCookie, recPerPage, {expires:config.cookieLife, path:config.webRoot});

            vars = vars.replace('_recTotal_', recTotal)
            vars = vars.replace('_recPerPage_', recPerPage)
            vars = vars.replace('_pageID_', pageID);
            location.href=createLink('$this->moduleName', '$this->methodName', vars);
        }
        </script>
EOT;
        return $js;
    }

    /**
     * 生成每页显示记录数的select列表。
     * Create the select list of RecPerPage.
     *
     * @access public
     * @return string
     */
    public function createRecPerPageList()
    {
        for($i = 5; $i <= 50; $i += 5) $range[$i] = $i;
        $range[100]  = 100;
        $range[200]  = 200;
        $range[500]  = 500;
        $range[1000] = 1000;
        $range[2000] = 2000;
        $html = "<div class='dropdown dropup'><a href='javascript:;' data-toggle='dropdown' id='_recPerPage' data-value='{$this->recPerPage}'>" . (sprintf($this->lang->pager->recPerPage, $this->recPerPage)) . "<span class='caret'></span></a><ul class='dropdown-menu'>";
        foreach ($range as $key => $value)
        {
            $html .= '<li' . ($this->recPerPage == $value ? " class='active'" : '') .'>' . "<a href='javascript:submitPage(\"changeRecPerPage\", $value)'>{$value}</a>" . '</li>';
        }
        $html .= '</ul></div>';
        return $html;
    }

    /**
     * 生成跳转到指定页码的部分。
     * Create the goto part html.
     *
     * @access public
     * @return string
     */
    public function createGoTo()
    {
        $goToHtml  = "<input type='hidden' id='_recTotal'  value='$this->recTotal' />\n";
        $goToHtml .= "<input type='hidden' id='_pageTotal' value='$this->pageTotal' />\n";
        $goToHtml .= "<input type='text'   id='_pageID'    value='$this->pageID' style='text-align:center;width:30px;' class='form-control' /> \n";
        $goToHtml .= "<input type='button' id='goto'       value='{$this->lang->pager->locate}' onclick='submitPage(\"changePageID\");' class='btn'/>";
        return $goToHtml;
    }

    /**
     * 创建链接。
     * Create link.
     *
     * @param  string    $title
     * @access public
     * @return string
     */
    public function createLink($title)
    {
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $title);
    }
}
