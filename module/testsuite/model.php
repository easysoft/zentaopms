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

    /**
     * Build select string.
     * 
     * @param  array  $products 
     * @param  int    $productID 
     * @param  string $currentModule 
     * @param  string $currentMethod 
     * @param  string $extra 
     * @access public
     * @return string
     */
    public function select($products, $productID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$productID)
        {
            unset($this->lang->product->menu->branch);
            return;
        }

        setCookie("lastProduct", $productID, $this->config->cookieLife, $this->config->webRoot);
        $currentProduct = $this->product->getById($productID);
        $output = "<a id='currentItem' href=\"javascript:showSearchMenu('product', '$productID', '$currentModule', '$currentMethod', '$extra')\">{$currentProduct->name} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i></div>";
        if($currentProduct->type != 'normal')
        {
            $this->app->loadLang('branch');
            $branchName = $this->lang->branch->all . $this->lang->product->branchName[$currentProduct->type];
            $output .= '</li><li>';
            $output .= "<a id='currentBranch'>{$branchName} <i class='icon icon-caret-right'></i></a> ";
        }
        return $output;
    }

    /**
     * Set library menu.
     * 
     * @param  array  $libraries 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function setLibMenu($libraries, $libID, $moduleID = 0)
    {
        $currentLibName = zget($libraries, $libID, '');
        $selectHtml = empty($libraries) ? '' : "<a id='currentItem' href=\"javascript:showSearchMenu('testsuite', '$libID', 'testsuite', 'library', '')\">{$currentLibName} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i></div>";

        if($this->config->global->flow == 'onlyTest')
        {
            $isMobile   = $this->app->viewType == 'mhtml';
            $modules    = $this->loadModel('tree')->getModulePairs($libID, 'caselib');
            $moduleName = ($moduleID && isset($modules[$moduleID])) ? $modules[$moduleID] : $this->lang->tree->all;

            if(!$isMobile)
            {
                $selectHtml .= '</li><li>';
                $selectHtml .= "<a id='currentModule' href=\"javascript:showSearchMenu('tree', '$libID', 'testsuite', 'library', '')\">{$moduleName} <span class='icon-caret-down'></span></a><div id='dropMenu'><i class='icon icon-spin icon-spinner'></i></div>";
            }
            else
            {
                $selectHtml .= "<a id='currentModule' href=\"javascript:showSearchMenu('tree', '$libID', 'testsuite', 'library', '')\">{$moduleName} <span class='icon-caret-down'></span></a><div id='currentBranchDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            }
        }

        setCookie("lastCaseLib", $libID, $this->config->cookieLife, $this->config->webRoot);
        foreach($this->lang->caselib->menu as $key => $value)
        {
            $replace = ($key == 'lib') ? $selectHtml : '';
            if($this->config->global->flow == 'onlyTest') $replace = ($key == 'lib') ? $selectHtml : $libID;
            common::setMenuVars($this->lang->caselib->menu, $key, $replace);
        }
        $this->lang->testsuite->menu = $this->lang->caselib->menu;
    }

    public function saveLibState($libID = 0, $libraries = array())
    {
        if($libID > 0) $this->session->set('caseLib', (int)$libID);
        if($libID == 0 and $this->cookie->lastCaseLib) $this->session->set('caseLib', $this->cookie->lastCaseLib);
        if($libID == 0 and $this->session->caseLib == '') $this->session->set('caseLib', key($libraries));
        if(!isset($libraries[$this->session->caseLib]))
        {
            $this->session->set('caseLib', key($libraries));
            $libID = $this->session->caseLib;
        }
        return $this->session->caseLib;
    }

    /**
     * Create a test suite.
     * 
     * @param  int   $productID 
     * @access public
     * @return bool|int
     */
    public function create($productID)
    {
        $suite = fixer::input('post')
            ->stripTags($this->config->testsuite->editor->create['id'], $this->config->allowedTags)
            ->add('product', (int)$productID)
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', helper::now())
            ->remove('uid')
            ->get();
        $suite = $this->loadModel('file')->processImgURL($suite, $this->config->testsuite->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTSUITE)->data($suite)
            ->batchcheck($this->config->testsuite->create->requiredFields, 'notempty')
            ->exec();
        if(!dao::isError())
        {
            $suiteID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $suiteID, 'testsuite');
            return $suiteID;
        }
        return false;
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
     * @return object
     */
    public function getById($suiteID, $setImgSize = false)
    {
        $suite = $this->dao->select("*")->from(TABLE_TESTSUITE)->where('id')->eq((int)$suiteID)->fetch();
        $suite = $this->loadModel('file')->replaceImgURL($suite, 'desc');
        if($setImgSize) $suite->desc = $this->file->setImgSize($suite->desc);
        return $suite;
    }

    /**
     * Update a test suite.
     * 
     * @param  int   $suiteID 
     * @access public
     * @return bool|array
     */
    public function update($suiteID)
    {
        $oldSuite = $this->dao->select("*")->from(TABLE_TESTSUITE)->where('id')->eq((int)$suiteID)->fetch();
        $suite    = fixer::input('post')
            ->stripTags($this->config->testsuite->editor->edit['id'], $this->config->allowedTags)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->remove('uid')
            ->get();
        $suite = $this->loadModel('file')->processImgURL($suite, $this->config->testsuite->editor->edit['id'], $this->post->uid);
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
        return false;
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

    /**
     * Get linked cases for suite.
     * 
     * @param  int    $suiteID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @param  bool   $append 
     * @access public
     * @return array
     */
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

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);
        return $this->loadModel('testcase')->appendData($cases);
    }

    /**
     * Get unlinked cases for suite.
     * 
     * @param  object $suite 
     * @param  int    $param 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getUnlinkedCases($suite, $param = 0, $pager = null)
    {
        if($this->session->testsuiteQuery == false) $this->session->set('testsuiteQuery', ' 1 = 1');
        $queryID = (int)$param;
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testsuiteQuery', $query->sql);
                $this->session->set('testsuiteForm', $query->form);
            }
        }

        $query = $this->session->testsuiteQuery;
        $allProduct = "`product` = 'all'";
        if(strpos($query, '`product` =') === false) $query .= " AND `product` = {$suite->product}";
        if(strpos($query, $allProduct) !== false) $query = str_replace($allProduct, '1', $query);

        $linkedCases = $this->getLinkedCases($suite->id, 'id_desc', null, $append = false);
        $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
            ->andWhere('id')->notIN(array_keys($linkedCases))
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
        return $cases;
    }

    /**
     * Delete suite and library. 
     * 
     * @param  int    $suiteID 
     * @param  string $table 
     * @access public
     * @return bool
     */
    public function delete($suiteID, $table = '')
    {
        $suite = $this->getById($suiteID);
        parent::delete(TABLE_TESTSUITE, $suiteID);
        if($suite->type == 'library')
        {
            $this->dao->update(TABLE_ACTION)->set('objectType')->eq('caselib')->where('objectID')->eq($suiteID)->andWhere('objectType')->eq('testsuite')->exec();
        }
        else
        {
            $this->dao->delete()->from(TABLE_SUITECASE)->where('suite')->eq($suiteID)->exec();
        }
        return !dao::isError();
    }

    /**
     * Get libraries.
     * 
     * @access public
     * @return array
     */
    public function getLibraries()
    {
        return $this->dao->select("id,name")->from(TABLE_TESTSUITE)
            ->where('product')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq('library')
            ->orderBy('id_desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Create lib.
     * 
     * @access public
     * @return int
     */
    public function createLib()
    {
        $lib = fixer::input('post')
            ->stripTags($this->config->testsuite->editor->create['id'], $this->config->allowedTags)
            ->setForce('type', 'library')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', helper::now())
            ->remove('uid')
            ->get();
        $lib = $this->loadModel('file')->processImgURL($lib, $this->config->testsuite->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TESTSUITE)->data($lib)
            ->batchcheck($this->config->testsuite->createlib->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted = '0'")
            ->exec();
        if(!dao::isError())
        {
            $libID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $libID, 'caselib');
            return $libID;
        }
        return false;
    }

    /**
     * Get lib cases.
     * 
     * @param  int    $libID 
     * @param  string $browseType 
     * @param  int    $queryID 
     * @param  int    $moduleID 
     * @param  string $sort 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getLibCases($libID, $browseType, $queryID = 0, $moduleID = 0, $sort = 'id_desc', $pager = null)
    {
        $moduleIdList = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType   = ($browseType == 'bymodule' and $this->session->libBrowseType and $this->session->libBrowseType != 'bysearch') ? $this->session->libBrowseType : $browseType;

        $cases = array();
        if($browseType == 'bymodule' or $browseType == 'all' or $browseType == 'wait')
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)
                ->where('lib')->eq((int)$libID)
                ->andWhere('product')->eq(0)
                ->beginIF($moduleIdList)->andWhere('module')->in($moduleIdList)->fi()
                ->beginIF($browseType == 'wait')->andWhere('status')->eq($browseType)->fi()
                ->andWhere('deleted')->eq('0')
                ->orderBy($sort)->page($pager)->fetchAll('id');
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                $this->session->set('caselibQuery', ' 1 = 1');
                if($query)
                {
                    $this->session->set('caselibQuery', $query->sql);
                    $this->session->set('caselibForm', $query->form);
                }
            }
            else
            {
                if($this->session->caselibQuery == false) $this->session->set('caselibQuery', ' 1 = 1');
            }

            $queryLibID = $libID;
            $allLib     = "`lib` = 'all'";
            $caseQuery  = '(' . $this->session->caselibQuery;
            if(strpos($this->session->caselibQuery, $allLib) !== false)
            {
                $caseQuery = str_replace($allLib, '1', $caseQuery);
                $queryLibID = 'all';
            }
            $caseQuery .= ')';

            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($caseQuery)
                ->beginIF($queryLibID != 'all')->andWhere('lib')->eq((int)$libID)->fi()
                ->andWhere('product')->eq(0)
                ->andWhere('deleted')->eq(0)
                ->orderBy($sort)->page($pager)->fetchAll();

        }
        return $cases;
    }

    /**
     * Get not imported cases.
     * 
     * @param  int    $productID 
     * @param  int    $libID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getNotImportedCases($productID, $libID, $orderBy = 'id_desc', $pager = null)
    {
        $importedCases = $this->dao->select('fromCaseID')->from(TABLE_CASE)
            ->where('product')->eq($productID)
            ->andWhere('lib')->eq($libID)
            ->andWhere('fromCaseID')->ne('')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('fromCaseID', 'fromCaseID');
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('lib')->eq($libID)
            ->andWhere('product')->eq(0)
            ->andWhere('id')->notIN($importedCases)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Build search form.
     * 
     * @param  int    $libID 
     * @param  array  $libraries 
     * @param  int    $queryID 
     * @param  string $actionURL 
     * @access public
     * @return void
     */
    public function buildSearchForm($libID, $libraries, $queryID, $actionURL)
    {
        $this->config->testcase->search['fields']['lib']              = $this->lang->testcase->lib;
        $this->config->testcase->search['params']['lib']['values']    = array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->caselib->all);
        $this->config->testcase->search['params']['lib']['operator']  = '=';
        $this->config->testcase->search['params']['lib']['control']   = 'select';
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib');
        if(!$this->config->testcase->needReview) unset($this->config->testcase->search['params']['status']['values']['wait']);
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        unset($this->config->testcase->search['fields']['lastRunner']);
        unset($this->config->testcase->search['params']['lastRunner']);
        unset($this->config->testcase->search['fields']['lastRunResult']);
        unset($this->config->testcase->search['params']['lastRunResult']);
        unset($this->config->testcase->search['fields']['lastRunDate']);
        unset($this->config->testcase->search['params']['lastRunDate']);

        $this->config->testcase->search['module']    = 'caselib';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Get lib link.
     * 
     * @param  string $module 
     * @param  string $method 
     * @param  string $extra 
     * @access public
     * @return string
     */
    public function getLibLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'testsuite')
        {
            if($module == 'testsuite' && ($method == 'createlib'))
            {
                $link = helper::createLink($module, 'library', "libID=%s");
            }
            else
            {
                $link = helper::createLink($module, $method, "libID=%s");
            }
        }
        else if($module == 'tree')
        {
            $link = helper::createLink($module, $method, "libID=%s&type=caselib&currentModuleID=0");
        }
        else
        {
            $link = helper::createLink('testsuite', 'library', "libID=%s");
        }
        return $link;
    }

    /**
     * Create from import.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function createFromImport($libID)
    {
        $this->loadModel('action');
        $this->loadModel('testcase');
        $this->loadModel('file');
        $now    = helper::now();
        $data   = fixer::input('post')->get();

        if(!empty($_POST['id']))
        {
            $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.id')->in(($_POST['id']))
                ->andWhere('t1.version=t2.version')
                ->orderBy('t2.id')
                ->fetchGroup('case');
            $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
        }

        $cases = array();
        foreach($data->lib as $key => $lib)
        {
            $caseData = new stdclass();

            $caseData->lib          = $lib;
            $caseData->module       = $data->module[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->status       = $data->status[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->keywords     = $data->keywords[$key];
            $caseData->frequency    = 1;
            $caseData->precondition = $data->precondition[$key];

            if(isset($this->config->testcase->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(empty($caseData->$requiredField)) die(js::alert(sprintf($this->lang->testcase->noRequire, $key, $this->lang->testcase->$requiredField)));
                }
            }

            $cases[$key] = $caseData;
        }

        $forceReview = $this->testcase->forceReview();
        foreach($cases as $key => $caseData)
        {
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $caseID      = $data->id[$key];
                $stepChanged = false;
                $steps       = array();
                $oldStep     = isset($oldSteps[$caseID]) ? $oldSteps[$caseID] : array();
                $oldCase     = $oldCases[$caseID];

                /* Remove the empty setps in post. */
                $steps = array();
                if(isset($_POST['desc'][$key]))
                {
                    foreach($data->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc))continue;
                        $step = new stdclass();
                        $step->type   = $data->stepType[$key][$id];
                        $step->desc   = $desc;
                        $step->expect = trim($data->expect[$key][$id]);

                        $steps[] = $step;
                    }
                }

                /* If step count changed, case changed. */
                if((!$oldStep != !$steps) or (count($oldStep) != count($steps)))
                {
                    $stepChanged = true;
                }
                else
                {
                    /* Compare every step. */
                    foreach($oldStep as $id => $oldStep)
                    {
                        if(trim($oldStep->desc) != trim($steps[$id]->desc) or trim($oldStep->expect) != $steps[$id]->expect)
                        {
                            $stepChanged = true;
                            break;
                        }
                    }
                }

                $version           = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
                $caseData->version = $version;
                $changes           = common::createChanges($oldCase, $caseData);
                if(!$changes and !$stepChanged) continue;

                if($changes or $stepChanged)
                {
                    $caseData->lastEditedBy   = $this->app->user->account;
                    $caseData->lastEditedDate = $now;
                    if($stepChanged and $forceReview) $caseData->status = 'wait';
                    $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->exec();
                    if($stepChanged)
                    {
                        $parentStepID = 0;
                        foreach($steps as $id => $step)
                        {
                            $step = (array)$step;
                            if(empty($step['desc'])) continue;
                            $stepData = new stdclass();
                            $stepData->type    = ($step['type'] == 'item' and $parentStepID == 0) ? 'step' : $step['type'];
                            $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                            $stepData->case    = $caseID;
                            $stepData->version = $version;
                            $stepData->desc    = htmlspecialchars($step['desc']);
                            $stepData->expect  = htmlspecialchars($step['expect']);
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                            if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                            if($stepData->type == 'step')  $parentStepID = 0;
                        }
                    }
                    $oldCase->steps  = $this->joinStep($oldStep);
                    $caseData->steps = $this->joinStep($steps);
                    $changes = common::createChanges($oldCase, $caseData);
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                $caseData->version    = 1;
                $caseData->openedBy   = $this->app->user->account;
                $caseData->openedDate = $now;
                $caseData->status     = $forceReview ? 'wait' : 'normal';
                $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->exec();

                if(!dao::isError())
                {
                    $caseID       = $this->dao->lastInsertID();
                    $parentStepID = 0;
                    foreach($data->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $stepData = new stdclass();
                        $stepData->type    = ($data->stepType[$key][$id] == 'item' and $parentStepID == 0) ? 'step' : $data->stepType[$key][$id];
                        $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                        $stepData->case    = $caseID;
                        $stepData->version = 1;
                        $stepData->desc    = htmlspecialchars($desc);
                        $stepData->expect  = htmlspecialchars($data->expect[$key][$id]);
                        $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                        if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                        if($stepData->type == 'step')  $parentStepID = 0;
                    }
                    $this->action->create('case', $caseID, 'Opened');
                }
            }
        }

        unlink($this->session->importFile);
        unset($_SESSION['importFile']);
    }

    /**
     * Batch create case for lib.
     * 
     * @param  int    $libID 
     * @access public
     * @return void
     */
    public function batchCreateCase($libID)
    {
        $this->loadModel('testcase');
        $this->loadModel('action');

        $now      = helper::now();
        $cases    = fixer::input('post')->get();
        $batchNum = count(reset($cases));

        $result = $this->loadModel('common')->removeDuplicate('case', $cases, "lib={$libID}");
        $cases  = $result['data'];

        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($cases->title[$i]) and empty($cases->type[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->testcase->type)));
        }

        $module = 0;
        $type   = '';
        $pri    = 3;
        for($i = 0; $i < $batchNum; $i++)
        {
            $module = $cases->module[$i] == 'ditto' ? $module : $cases->module[$i];
            $type   = $cases->type[$i] == 'ditto'   ? $type   : $cases->type[$i];
            $pri    = $cases->pri[$i] == 'ditto'    ?  $pri   : $cases->pri[$i];
            $cases->module[$i] = (int)$module;
            $cases->type[$i]   = $type;
            $cases->pri[$i]    = $pri;
        }

        $forceReview = $this->testcase->forceReview();
        for($i = 0; $i < $batchNum; $i++)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i] = new stdclass();
                $data[$i]->lib          = $libID;
                $data[$i]->module       = $cases->module[$i];
                $data[$i]->type         = $cases->type[$i];
                $data[$i]->pri          = $cases->pri[$i];
                $data[$i]->stage        = empty($cases->stage[$i]) ? '' : implode(',', $cases->stage[$i]);
                $data[$i]->color        = $cases->color[$i];
                $data[$i]->title        = $cases->title[$i];
                $data[$i]->precondition = $cases->precondition[$i];
                $data[$i]->keywords     = $cases->keywords[$i];
                $data[$i]->openedBy     = $this->app->user->account;
                $data[$i]->openedDate   = $now;
                $data[$i]->status       = $forceReview ? 'wait' : 'normal';
                $data[$i]->version      = 1;

                $this->dao->insert(TABLE_CASE)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                    ->exec();

                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                $caseID   = $this->dao->lastInsertID();
                $actionID = $this->action->create('case', $caseID, 'Opened');
            }
        }
    }
}
