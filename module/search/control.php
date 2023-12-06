<?php

use function zin\wg;

/**
 * The control file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: control.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
class search extends control
{
    public $search;

    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  string $module
     * @param  string $fields
     * @param  array  $params
     * @param  string $actionURL
     * @param  int    $queryID
     * @param  string $formName
     * @access public
     * @return void
     */
    public function buildForm(string $module = '', string $fields = '', string $params = '', string $actionURL = '', int $queryID = 0, string $formName = '')
    {
        $module       = empty($module) ? $this->session->searchParams['module'] : $module;
        $searchParams = $module . 'searchParams';
        $searchForm   = $module . 'Form';

        $fields = empty($fields) ? json_decode($_SESSION[$searchParams]['searchFields'], true) : $fields;
        $params = empty($params) ? json_decode($_SESSION[$searchParams]['fieldParams'], true)  : $params;

        $_SESSION['searchParams']['module'] = $module;
        if(empty($_SESSION[$searchForm])) $this->search->initSession($module, $fields, $params);

        if(in_array($module, $this->config->search->searchObject) && $this->session->objectName)
        {
            $space = common::checkNotCN() ? ' ' : '';
            $this->lang->search->common = $this->lang->search->common . $space . $this->session->objectName;
        }

        $this->view->module       = $module;
        $this->view->actionURL    = empty($actionURL) ? $_SESSION[$searchParams]['actionURL'] : $actionURL;
        $this->view->fields       = $fields;
        $this->view->fieldParams  = $this->search->setDefaultParams($fields, $params);
        $this->view->queries      = $this->search->getQueryList($module);
        $this->view->queryID      = (empty($module) && empty($queryID)) ? $_SESSION[$searchParams]['queryID'] : $queryID;
        $this->view->style        = !empty($_SESSION[$searchParams]['style']) ? $_SESSION[$searchParams]['style'] : 'full';
        $this->view->onMenuBar    = !empty($_SESSION[$searchParams]['onMenuBar']) ? $_SESSION[$searchParams]['onMenuBar'] : 'no';
        $this->view->formSession  = $_SESSION[$module . 'Form'];
        $this->view->formName     = $formName;

        if($module == 'program') $this->view->options = $this->searchZen->setOptions($fields, $this->view->fieldParams, $this->view->queries);

        $this->app->loadModuleConfig('action');
        $this->display();
    }

    /**
     * 构建搜索查询。
     * Build search query.
     *
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        /* 将查询 sql 和 表单名字设置 session。*/
        /* Set query sql and form name in session. */
        $this->search->buildQuery();

        $actionURL = $this->post->actionURL;
        $parsedURL = parse_url($actionURL);

        /* 查询链接中有 host 直接返回。*/
        /* If action url has host, return. */
        if(isset($parsedURL['host'])) return;

        /* 检查查询链接。*/
        /* Check action url. */
        if($this->config->requestType != 'GET')
        {
            $path = $parsedURL['path'];
            $path = str_replace($this->config->webRoot, '', $path);
            if(strpos($path, '.') !== false) $path = substr($path, 0, strpos($path, '.'));
            if(preg_match("/^\w+{$this->config->requestFix}\w+/", $path) == 0) return;
        }
        else
        {
            $query = $parsedURL['query'];
            if(preg_match("/^{$this->config->moduleVar}=\w+\&{$this->config->methodVar}=\w+/", $query) == 0) return;
        }

        return print(json_encode(array('result' => 'success', 'load' => $actionURL)));
    }

    /**
     * 保存搜索查询。
     * Save search query.
     *
     * @param  string  $module
     * @param  string  $onMenuBar
     * @access public
     * @return void
     */
    public function saveQuery(string $module, string $onMenuBar = 'no')
    {
        if($_POST)
        {
            $queryID = $this->search->saveQuery();
            if(!$queryID) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json')
            {
                echo 'success';
                return;
            }
            return $this->send(array('closeModal' => true, 'callback' => array('name' => 'zui.SearchForm.addQuery', 'params' => array(array('module' => $module, 'id' => $queryID, 'text' => $this->post->title)))));
        }

        $this->view->module    = $module;
        $this->view->onMenuBar = $onMenuBar;
        $this->display();
    }

    /**
     * 删除搜索查询。
     * Delete current search query.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function deleteQuery($queryID)
    {
        $this->search->deleteQuery($queryID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Ajax get search query.
     *
     * @param  string $module
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function ajaxGetQuery($module = '', $queryID = 0)
    {
        $query   = $queryID ? $queryID : '';
        $module  = empty($module) ? $this->session->searchParams['module'] : $module;
        $queries = $this->search->getQueryList($module);
        $html = '';
        foreach($queries as $query)
        {
            if(empty($query->id)) continue;

            $html .= '<li>' . html::a("javascript:executeQuery({$query->id})", $query->title . ((common::hasPriv('search', 'deleteQuery') and $this->app->user->account == $query->account) ? '<i class="icon icon-close"></i>' : ''), '', "class='label user-query' data-query-id='$query->id' title='{$query->title}'") . '</li>';
        }
        echo $html;
    }

    /**
     * Ajax remove from menu.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function ajaxRemoveMenu($queryID)
    {
        $this->dao->update(TABLE_USERQUERY)->set('shortcut')->eq(0)->where('id')->eq($queryID)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 建立索引。
     * Build All index.
     *
     * @param  string  $mode    show|build
     * @param  string  $type
     * @param  int     $lastID
     * @access public
     * @return void
     */
    public function buildIndex(string $mode = 'show', string $type = '', int $lastID = 0)
    {
        if($mode == 'build')
        {
            $result = $this->search->buildAllIndex($type, $lastID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($result['finished'])) return $this->send(array('result' => 'finished', 'message' => $this->lang->search->buildSuccessfully));

            $type = zget($this->lang->search->modules, ($result['type'] == 'testcase' ? 'case' : $result['type']), $result['type']);
            return $this->send(array('result' => 'unfinished', 'message' => sprintf($this->lang->search->buildResult, $type, $type, $result['count']), 'type' => $type, 'count' => $result['count'], 'next' => inlink('buildIndex', "mode=build&type={$result['type']}&lastID={$result['lastID']}")));
        }

        $this->view->title = $this->lang->search->buildIndex;
        $this->display();
    }

    /**
     * 全局搜索结果页面。
     * Global search results home page.
     *
     * @param  int    $recTotal
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function index(int $recTotal = 0, int $pageID = 1)
    {
        $this->lang->admin->menu->search = "{$this->lang->search->common}|search|index";

        /* 获取搜索的关键词。*/
        /* Get the words. */
        if(empty($words)) $words = $this->get->words;
        if(empty($words)) $words = $this->post->words;
        if(empty($words) && ($recTotal != 0 || $pageID != 1)) $words = $this->session->searchIngWord;
        $words = strip_tags(strtolower($words));

        /* 获取搜索类型。*/
        /* Get the type. */
        if(empty($type)) $type = $this->get->type;
        if(empty($type)) $type = $this->post->type;
        if(empty($type) && ($recTotal != 0 || $pageID != 1)) $type = $this->session->searchIngType;
        if(is_array($type)) $type = array_filter(array_unique($type));
        $type = (empty($type) || (is_array($type) && in_array('all', $type))) ? 'all' : $type;

        /* 开始搜索时记录当时的时间。*/
        $begin = time();

        $this->app->loadClass('pager', $static = true);
        $pager   = new pager(0, $this->config->search->recPerPage, $pageID);
        $results = $this->search->getList($words, $type, $pager);

        $uri  = inlink('index', "recTotal=$pager->recTotal&pageID=$pager->pageID");
        $uri .= strpos($uri, '?') === false ? '?' : '&';
        $uri .= 'words=' . $words;
        $this->searchZen->setSessionForIndex($uri, $words, $type);

        $this->view->title      = $this->lang->search->index;
        $this->view->results    = $results;
        $this->view->consumed   = time() - $begin;
        $this->view->type       = $type;
        $this->view->typeList   = $this->searchZen->getTypeList();
        $this->view->pager      = $pager;
        $this->view->words      = $words;
        $this->view->referer    = $this->session->referer;

        $this->display();
    }
}
