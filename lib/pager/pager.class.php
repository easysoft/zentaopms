<?php
/**
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
 * Pager class.
 * 
 * @package framework
 */
class pager
{
    /**
     * The default counts of per page.
     *
     * @public int
     */
    const DEFAULT_REC_PRE_PAGE = 20;

    /**
     * The total counts.
     * 
     * @var int
     * @access public
     */
    public $recTotal;

    /**
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
     * Page count.
     * 
     * @var string
     * @access public
     */
    public $pageTotal;

    /**
     * Current page id.
     * 
     * @var string
     * @access public
     */
    public $pageID;

    /**
     * The global $app.
     * 
     * @var object
     * @access private
     */
    private $app;

    /**
     * The global $lang.
     * 
     * @var object
     * @access private
     */
    private $lang;

    /**
     * Current module name.
     * 
     * @var string
     * @access private
     */
    private $moduleName;

    /**
     * Current method.
     * 
     * @var string
     * @access private
     */
    private $methodName;

    /**
     * The params.
     *
     * @private array
     */
    private $params;

    /**
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

        $this->setRecTotal($recTotal);
        $this->setRecPerPage($recPerPage);
        $this->setPageTotal();
        $this->setPageID($pageID);
    }

    /**
     * The factory function.
     * 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return object
     */
    public function init($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        return new pager($recTotal, $recPerPage, $pageID);
    }

    /**
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
     * Set the recTotal property.
     * 
     * @param  int    $recPerPage 
     * @access public
     * @return void
     */
    public function setRecPerPage($recPerPage)
    {
        /* Set the cookie name. */
        $this->pageCookie = $this->app->getModuleName() . $this->app->getMethodName();

        if(isset($_COOKIE[$this->pageCookie])) $recPerPage = $_COOKIE[$this->pageCookie];
        $this->recPerPage = ($recPerPage > 0) ? $recPerPage : PAGER::DEFAULT_REC_PRE_PAGE;
    }

    /**
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
     * Set the page id.
     * 
     * @param  int $pageID 
     * @access public
     * @return void
     */
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

    /**
     * Set the $app property;
     * 
     * @access private
     * @return void
     */
    private function setApp()
    {
        global $app;
        $this->app = $app;
    }

    /**
     * Set the $lang property.
     * 
     * @access private
     * @return void
     */
    private function setLang()
    {
        global $lang;
        $this->lang = $lang;
    }

    /**
     * Set the $moduleName property.
     * 
     * @access private
     * @return void
     */
    private function setModuleName()
    {
        $this->moduleName = $this->app->getModuleName();
    }

    /**
     * Set the $methodName property.
     * 
     * @access private
     * @return void
     */
    private function setMethodName()
    {
        $this->methodName = $this->app->getMethodName();
    }

    /**
     * Get recTotal, recPerpage, pageID from the request params, and add them to params.
     * 
     * @access private
     * @return void
     */
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

    /**
     * Create the limit string.
     * 
     * @access public
     * @return string
     */
    public function limit()
    {
        $limit = '';
        if($this->pageTotal > 1) $limit = ' limit ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        return $limit;
    }
   
    /**
     * Print the pager's html.
     * 
     * @param  string $align 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function show($align = 'right', $type = 'full')
    {
        echo $this->get($align, $type);
    }

    /**
     * Get the pager html string.
     * 
     * @param  string $align 
     * @param  string $type     the pager type, full|short|shortest
     * @access public
     * @return string
     */
    public function get($align = 'right', $type = 'full')
    {
        /* If the RecTotal is zero, return with no record. */
        if($this->recTotal == 0) { return "<div style='float:$align; clear:none;' class='pager'>{$this->lang->pager->noRecord}</div>"; }

        /* Set the params. */
        $this->setParams();
        
        /* Create the prePage and nextpage, all types have them. */
        $pager  = $this->createPrePage();
        $pager .= $this->createNextPage();

        /* The short and full type. */
        if($type !== 'shortest')
        {
            $pager  = $this->createFirstPage() . $pager;
            $pager .= $this->createLastPage();
        }

        /* Only the full type . */
        if($type == 'full')
        {
            $pager  = $this->createDigest() . $pager;
            $pager .= $this->createGoTo();
            $pager .= $this->createRecPerPageJS();
        }

        return "<div style='float:$align; clear:none;' class='pager'>$pager</div>";
    }

    /**
     * Create the digest code.
     * 
     * @access private
     * @return string
     */
    private function createDigest()
    {
        return sprintf($this->lang->pager->digest, $this->recTotal, $this->createRecPerPageList(), $this->pageID, $this->pageTotal);
    }

    /**
     * Create the first page.
     * 
     * @access private
     * @return string
     */
    private function createFirstPage()
    {
        if($this->pageID == 1) return $this->lang->pager->first . ' ';
        $this->params['pageID'] = 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->first);
    }

    /**
     * Create the pre page html.
     * 
     * @access private
     * @return string
     */
    private function createPrePage()
    {
        if($this->pageID == 1) return $this->lang->pager->pre . ' ';
        $this->params['pageID'] = $this->pageID - 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->pre);
    }    

    /**
     * Create the next page html.
     * 
     * @access private
     * @return string
     */
    private function createNextPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->next . ' ';
        $this->params['pageID'] = $this->pageID + 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->next);
    }

    /**
     * Create the last page 
     * 
     * @access private
     * @return string
     */
    private function createLastPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->last . ' ';
        $this->params['pageID'] = $this->pageTotal;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->last);
    }    

    /**
     * Create the select object of record perpage.
     * 
     * @access private
     * @return string
     */
    private function createRecPerPageJS()
    {
        /* Replace the recTotal, recPerPage, pageID to special string, and then replace them with values by JS. */
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
        function submitPage(mode)
        {
            pageTotal  = parseInt(document.getElementById('_pageTotal').value);
            pageID     = document.getElementById('_pageID').value;
            recPerPage = document.getElementById('_recPerPage').value;
            recTotal   = document.getElementById('_recTotal').value;
            $.cookie(pageCookie, recPerPage, {expires:config.cookieLife, path:config.webRoot});
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

    /**
    /* Create the select list of RecPerPage. 
     * 
     * @access private
     * @return string
     */
    private function createRecPerPageList()
    {
        for($i = 5; $i <= 50; $i += 5) $range[$i] = $i;
        $range[100]  = 100;
        $range[200]  = 200;
        $range[500]  = 500;
        $range[1000] = 1000;
        return html::select('_recPerPage', $range, $this->recPerPage, "onchange='submitPage(\"changeRecPerPage\");'");
    }

    /**
     * Create the goto part html.
     * 
     * @access private
     * @return string
     */
    private function createGoTo()
    {
        $goToHtml  = "<input type='hidden' id='_recTotal'  value='$this->recTotal' />\n";
        $goToHtml .= "<input type='hidden' id='_pageTotal' value='$this->pageTotal' />\n";
        $goToHtml .= "<input type='text'   id='_pageID'    value='$this->pageID' style='text-align:center;width:30px;' /> \n";
        $goToHtml .= "<input type='button' id='goto'       value='{$this->lang->pager->locate}' onclick='submitPage(\"changePageID\");' />";
        return $goToHtml;
    }    
}
