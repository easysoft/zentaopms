<?php
/**
 * The model file of test suite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: model.php 5114 2013-07-12 06:02:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testsuiteModel extends model
{
    /**
     * Set the menu. 
     * 
     * @param  array $products 
     * @param  int   $productID 
     * @access public
     * @return void
     */
    public function setMenu($products, $productID)
    {
        $this->loadModel('product')->setMenu($products, $productID);
        $selectHtml = $this->select($products, $productID, 'testsuite', 'browse');
        foreach($this->lang->testsuite->menu as $key => $value)
        {
            $replace = ($key == 'product') ? $selectHtml : $productID;
            common::setMenuVars($this->lang->testsuite->menu, $key, $replace);
        }
    }

    public function select($products, $productID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$productID)
        {
            unset($this->lang->product->menu->branch);
            return;
        }

        setCookie("lastProduct", $productID, $this->config->cookieLife, $this->config->webRoot);
        $currentProduct = $this->product->getById($productID);
        $output = "<a id='currentItem' href=\"javascript:showDropMenu('product', '$productID', '$currentModule', '$currentMethod', '$extra')\">{$currentProduct->name} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i></div>";
        return $output;
    }

    /**
     * Create a test suite.
     * 
     * @param  int   $productID 
     * @access public
     * @return void
     */
    function create($productID)
    {
        $suite = fixer::input('post')
            ->stripTags($this->config->testsuite->editor->create['id'], $this->config->allowedTags)
            ->add('product', (int)$productID)
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', helper::now())
            ->remove('uid')
            ->get();
        $suite = $this->loadModel('file')->processEditor($suite, $this->config->testsuite->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTSUITE)->data($suite)
            ->batchcheck($this->config->testsuite->create->requiredFields, 'notempty')
            ->exec();
        if(!dao::isError())
        {
            $suiteID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $suiteID, 'testsuite');
            return $suiteID;
        }
    }

    /**
     * Get test suites of a product.
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getSuites($productID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select("*")->from(TABLE_TESTSUITE)
            ->where('product')->eq((int)$productID)
            ->andWhere('deleted')->eq(0)
            ->andWhere("(`type` = 'public' OR (`type` = 'private' and addedBy = '{$this->app->user->account}'))")
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get test suite info by id.
     * 
     * @param  int   $suiteID 
     * @param  bool  $setImgSize
     * @access public
     * @return void
     */
    public function getById($suiteID, $setImgSize = false)
    {
        $suite = $this->dao->select("*")->from(TABLE_TESTSUITE)->where('id')->eq((int)$suiteID)->fetch();
        if($setImgSize) $suite->desc = $this->loadModel('file')->setImgSize($suite->desc);
        return $suite;
    }

    /**
     * Update a test suite.
     * 
     * @param  int   $suiteID 
     * @access public
     * @return void
     */
    public function update($suiteID)
    {
        $oldSuite = $this->getById($suiteID);
        $suite    = fixer::input('post')
            ->stripTags($this->config->testsuite->editor->edit['id'], $this->config->allowedTags)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->remove('uid')
            ->get();
        $suite = $this->loadModel('file')->processEditor($suite, $this->config->testsuite->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TESTSUITE)->data($suite)
            ->autoCheck()
            ->batchcheck($this->config->testsuite->edit->requiredFields, 'notempty')
            ->where('id')->eq($suiteID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $suiteID, 'testsuite');
            return common::createChanges($oldSuite, $suite);
        }
    }

    /**
     * Link cases.
     * 
     * @param  int   $suiteID 
     * @access public
     * @return void
     */
    public function linkCase($suiteID)
    {
        if($this->post->cases == false) return;
        $postData = fixer::input('post')->get();
        foreach($postData->cases as $caseID)
        {
            $row = new stdclass();
            $row->suite      = $suiteID;
            $row->case       = $caseID;
            $row->version    = $postData->versions[$caseID];
            $this->dao->replace(TABLE_SUITECASE)->data($row)->exec();
        }
    }

    public function getLinkedCases($suiteID, $orderBy = 'id_desc', $pager = null, $append = true)
    {
        $suite = $this->getById($suiteID);
        $cases = $this->dao->select('t1.*,t2.version as caseVersion')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_SUITECASE)->alias('t2')->on('t1.id=t2.case')
            ->where('t2.suite')->eq($suiteID)
            ->andWhere('t1.product')->eq($suite->product)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        if(!$append) return $cases;
        return $this->loadModel('testcase')->appendBugAndResults($cases);
    }

    public function getUnlinkedCases($suite, $param = 0, $pager = null)
    {
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $queryID = (int)$param;
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
        }

        $query = $this->session->testcaseQuery;
        $allProduct = "`product` = 'all'";
        if(strpos($query, '`product` =') === false) $query .= " AND `product` = {$suite->product}";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1', $query);

        $linkedCases = $this->getLinkedCases($suite->id, 'id_desc', null, $append = false);
        $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
            ->andWhere('id')->notIN($linkedCases)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
        return $cases;
    }

    public function delete($suiteID, $table = '')
    {
        parent::delete(TABLE_TESTSUITE, $suiteID);
        $this->dao->delete()->from(TABLE_SUITECASE)->where('suite')->eq($suiteID)->exec();
        return true;
    }
}
