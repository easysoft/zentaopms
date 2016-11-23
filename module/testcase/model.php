<?php
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: model.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class testcaseModel extends model
{
    /**
     * Set menu.
     * 
     * @param  array $products 
     * @param  int   $productID 
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $selectHtml = $this->product->select($products, $productID, 'testcase', 'browse', '', $branch);
        foreach($this->lang->testcase->menu as $key => $menu)
        {
            $replace = ($key == 'product') ? $selectHtml : $productID;
            common::setMenuVars($this->lang->testcase->menu, $key, $replace);
        }
    }

    /**
     * Create a case.
     * 
     * @param int $bugID
     * @access public
     * @return void
     */
    function create($bugID)
    {
        $now  = helper::now();
        $case = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('status', 'normal')
            ->add('version', 1)
            ->add('fromBug', $bugID)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion((int)$this->post->story))
            ->remove('steps,expects,files,labels')
            ->setDefault('story', 0)
            ->join('stage', ',')
            ->get();

        $result = $this->loadModel('common')->removeDuplicate('case', $case, "product={$case->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        /* value of story may be showmore. */
        $case->story = (int)$case->story;
        $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->batchCheck($this->config->testcase->create->requiredFields, 'notempty')->exec();
        if(!$this->dao->isError())
        {
            $caseID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('testcase', $caseID);
            foreach($this->post->steps as $stepID => $stepDesc)
            {
                if(empty($stepDesc)) continue;
                $step          = new stdClass();
                $step->case    = $caseID;
                $step->version = 1;
                $step->desc    = htmlspecialchars($stepDesc);
                $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
            return array('status' => 'created', 'id' => $caseID);
        }
    }
    
    /**
     * Batch create cases.
     * 
     * @param  int    $productID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    function batchCreate($productID, $branch, $storyID)
    {
        $branch      = (int)$branch;
        $now         = helper::now();
        $cases       = fixer::input('post')->get();
        $batchNum    = count(reset($cases));

        $result = $this->loadModel('common')->removeDuplicate('case', $cases, "product={$productID}");
        $cases  = $result['data'];

        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($cases->title[$i]) and empty($cases->type[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->testcase->type)));
        }

        $module = 0;
        $story  = 0;
        $type   = '';
        $pri    = 3;
        for($i = 0; $i < $batchNum; $i++)
        {
            $module = $cases->module[$i] == 'ditto' ? $module : $cases->module[$i];
            $story  = $cases->story[$i] == 'ditto'  ? $story  : $cases->story[$i];
            $type   = $cases->type[$i] == 'ditto'   ? $type   : $cases->type[$i];
            $pri    = $cases->pri[$i] == 'ditto'    ?  $pri   : $cases->pri[$i];
            $cases->module[$i] = (int)$module;
            $cases->story[$i]  = (int)$story;        
            $cases->type[$i]   = $type;
            $cases->pri[$i]    = $pri;
        }

        $this->loadModel('story');
        $storyVersions = array();
        for($i = 0; $i < $batchNum; $i++)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i] = new stdclass();
                $data[$i]->product      = $productID;
                $data[$i]->branch       = $cases->branch[$i];
                $data[$i]->module       = $cases->module[$i];
                $data[$i]->type         = $cases->type[$i];
                $data[$i]->pri          = $cases->pri[$i];
                $data[$i]->stage        = empty($cases->stage[$i]) ? '' : implode(',', $cases->stage[$i]);
                $data[$i]->story        = $storyID ? $storyID : $cases->story[$i];
                $data[$i]->color        = $cases->color[$i];
                $data[$i]->title        = $cases->title[$i];
                $data[$i]->precondition = $cases->precondition[$i];
                $data[$i]->keywords     = $cases->keywords[$i];
                $data[$i]->openedBy     = $this->app->user->account;
                $data[$i]->openedDate   = $now;
                $data[$i]->status       = 'normal';
                $data[$i]->version      = 1;

                $caseStory = $data[$i]->story;
                $data[$i]->storyVersion = isset($storyVersions[$caseStory]) ? $storyVersions[$caseStory] : 0;
                if($caseStory and !isset($storyVersions[$caseStory]))
                {
                    $data[$i]->storyVersion = $this->story->getVersion($caseStory);
                    $storyVersions[$caseStory] = $data[$i]->storyVersion;
                }

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
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Opened');
            }
            else
            {
                unset($cases->module[$i]);
                unset($cases->type[$i]);
                unset($cases->pri[$i]);
                unset($cases->story[$i]);
                unset($cases->title[$i]);
                unset($cases->stage[$i]);
                unset($cases->precondition[$i]);
                unset($cases->keywords[$i]);
            }
        }
    }

    /**
     * Get cases of a module.
     * 
     * @param  int    $productID 
     * @param  int    $moduleIdList
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getModuleCases($productID, $branch = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get case info by ID.
     * 
     * @param  int    $caseID 
     * @param  int    $version 
     * @access public
     * @return object|bool
     */
    public function getById($caseID, $version = 0)
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        if(!$case) return false;
        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $case->$key = '';
        if($case->story)
        {
            $story = $this->dao->findById($case->story)->from(TABLE_STORY)->fields('title, status, version')->fetch();
            $case->storyTitle         = $story->title;
            $case->storyStatus        = $story->status;
            $case->latestStoryVersion = $story->version;
        }
        if($case->fromBug) $case->fromBugTitle = $this->dao->findById($case->fromBug)->from(TABLE_BUG)->fields('title')->fetch('title'); 

        $case->toBugs = array();
        $toBugs       = $this->dao->select('id, title')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        foreach($toBugs as $toBug) $case->toBugs[$toBug->id] = $toBug->title;

        if($case->linkCase) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->fetchPairs();
        if($version == 0) $version = $case->version;
        $case->steps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->orderBy('id')->fetchAll();
        $case->files = $this->loadModel('file')->getByObject('testcase', $caseID);
        $case->currentVersion = $version ? $version : $case->version;
        return $case;
    }

    /**
     * Get case list.
     *
     * @param  int|array|string $caseIDList
     * @access public
     * @return array
     */
    public function getByList($caseIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->beginIF($caseIDList)->andWhere('id')->in($caseIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get test cases.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager)
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->caseBrowseType and $this->session->caseBrowseType != 'bysearch') ? $this->session->caseBrowseType : $browseType;

        /* By module or all cases. */
        $cases = array();
        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $cases = $this->getModuleCases($productID, $branch, $modules, $sort, $pager);
        }
        /* Cases need confirmed. */
        elseif($browseType == 'needconfirm')
        {
            $cases = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->andWhere('t1.product')->eq($productID)
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll();
        }
        /* By search. */
        elseif($browseType == 'bysearch')
        {
            $cases = $this->getBySearch($productID, $queryID, $sort, $pager);
        }

        return $cases;
    }

    /**
     * Get cases by search.
     *
     * @param  int    $productID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($productID, $queryID, $orderBy, $pager = null)
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('testcaseQuery', $query->sql);
                $this->session->set('testcaseForm', $query->form);
            }
            else
            {
                $this->session->set('testcaseQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        }

        $queryProductID = $productID;
        $allProduct     = "`product` = 'all'";
        $caseQuery      = '(' . $this->session->testcaseQuery;
        if(strpos($this->session->testcaseQuery, $allProduct) !== false)
        {
            $products  = array_keys($this->loadModel('product')->getPrivProducts());
            $caseQuery = str_replace($allProduct, '1', $caseQuery);
            $caseQuery = $caseQuery . ' AND `product` ' . helper::dbIN($products);
            $queryProductID = 'all';
        }
        $caseQuery .= ')';

        $cases = $this->dao->select('*')->from(TABLE_CASE)->where($caseQuery)
            ->beginIF($queryProductID != 'all')->andWhere('product')->eq($productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();

        return $cases;
    }

    /**
     * Get cases by assignedTo.
     * 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssignedTo($account, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->Where('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->ne('done')
            ->andWhere('t3.status')->ne('done')
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get cases by openedBy
     * 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByOpenedBy($account, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->findByOpenedBy($account)->from(TABLE_CASE)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get cases of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCases($storyID)
    {
        return $this->dao->select('id, title, pri, type, status, lastRunner, lastRunDate, lastRunResult')
            ->from(TABLE_CASE)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get counts of some stories' cases.
     *
     * @param  array  $stories
     * @access public
     * @return int
     */
    public function getStoryCaseCounts($stories)
    {
        $caseCounts = $this->dao->select('story, COUNT(*) AS cases')
            ->from(TABLE_CASE)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($caseCounts[$storyID])) $caseCounts[$storyID] = 0;
        return $caseCounts;
    }

    /**
     * Update a case.
     * 
     * @param  int    $caseID 
     * @access public
     * @return void
     */
    public function update($caseID)
    {
        $oldCase     = $this->getById($caseID);
        if(isset($_POST['lastEditedDate']) and $oldCase->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now         = helper::now();
        $stepChanged = false;
        $steps       = array();

        //---------------- Judge steps changed or not.-------------------- */
        
        /* Remove the empty setps in post. */
        foreach($this->post->steps as $key => $desc)
        {
            $desc = trim($desc);
            if(!empty($desc)) $steps[] = array('desc' => $desc, 'expect' => trim($this->post->expects[$key]));
        }

        /* If step count changed, case changed. */
        if(count($oldCase->steps) != count($steps))
        {
            $stepChanged = true;
        }
        else
        {
            /* Compare every step. */
            foreach($oldCase->steps as $key => $oldStep)
            {
                if(trim($oldStep->desc) != trim($steps[$key]['desc']) or trim($oldStep->expect) != $steps[$key]['expect']) 
                {
                    $stepChanged = true;
                    break;
                }
            }
        }
        $version = $stepChanged ? $oldCase->version + 1 : $oldCase->version;

        $case = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('version', $version)
            ->setIF($this->post->story != false and $this->post->story != $oldCase->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setDefault('story', 0)
            ->join('stage', ',')
            ->remove('comment,steps,expects,files,labels')
            ->get();
        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->batchCheck($this->config->testcase->edit->requiredFields, 'notempty')->where('id')->eq((int)$caseID)->exec();
        if(!$this->dao->isError())
        {
            if($stepChanged)
            {
                foreach($this->post->steps as $stepID => $stepDesc)
                {
                    if(empty($stepDesc)) continue;
                    $step = new stdclass();
                    $step->case    = $caseID;
                    $step->version = $version;
                    $step->desc    = htmlspecialchars($stepDesc);
                    $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                    $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
                }
            }

            /* Join the steps to diff. */
            if($stepChanged)
            {
                $oldCase->steps = $this->joinStep($oldCase->steps);
                $case->steps    = $this->joinStep($this->getById($caseID, $version)->steps);
            }
            else
            {
                unset($oldCase->steps);
            }
            return common::createChanges($oldCase, $case);
        }
    }

    /**
     * Link related cases.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function linkCases($caseID)
    {
        if($this->post->cases == false) return;

        $case       = $this->getById($caseID);
        $cases2Link = $this->post->cases;

        $cases = implode(',', $cases2Link) . ',' . trim($case->linkCase, ',');
        $this->dao->update(TABLE_CASE)->set('linkCase')->eq(trim($cases, ','))->where('id')->eq($caseID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $this->loadModel('action')->create('case', $caseID, 'linkRelatedCase', '', implode(',', $cases2Link));
    }

    /**
     * Get cases to link.
     *
     * @param  int    $caseID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getCases2Link($caseID, $browseType = 'bySearch', $queryID)
    {
        if($browseType == 'bySearch')
        {
            $case       = $this->getById($caseID);
            $cases2Link = $this->getBySearch($case->product, $queryID, 'id', null);
            foreach($cases2Link as $key => $case2Link)
            {
                if($case2Link->id == $caseID) unset($cases2Link[$key]);
                if(in_array($case2Link->id, explode(',', $case->linkCase))) unset($cases2Link[$key]);
            }
            return $cases2Link;
        }
        else
        {
            return array();
        }
    }

    /**
     * Unlink related case.
     *
     * @param  int    $caseID
     * @param  int    $case2Unlink
     * @access public
     * @return void
     */
    public function unlinkCase($caseID, $case2Unlink = 0)
    {
        $case = $this->getById($caseID);

        $cases = explode(',', trim($case->linkCase, ','));
        foreach($cases as $key => $caseId)
        {
            if($caseId == $case2Unlink) unset($cases[$key]);
        }
        $cases = implode(',', $cases);

        $this->dao->update(TABLE_CASE)->set('linkCase')->eq($cases)->where('id')->eq($caseID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $this->loadModel('action')->create('case', $caseID, 'unlinkRelatedCase', '', $case2Unlink);
    }

    /**
     * Get linkCases.
     *
     * @param  int    $caseID
     * @access public
     * @return array
     */
    public function getLinkCases($caseID)
    {
        $case = $this->getById($caseID);
        return $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->fetchPairs();
    }

    /**
     * Batch update testcases.
     * 
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $cases      = array();
        $allChanges = array();
        $now        = helper::now();
        $data       = fixer::input('post')->get();
        $caseIDList = $this->post->caseIDList;

        /* Adjust whether the post data is complete, if not, remove the last element of $caseIDList. */
        if($this->session->showSuhosinInfo) array_pop($caseIDList);

        /* Process data if the value is 'ditto'. */
        foreach($caseIDList as $caseID)
        {
            if($data->pris[$caseID]    == 'ditto') $data->pris[$caseID]    = isset($prev['pri'])    ? $prev['pri']    : 3;
            if($data->modules[$caseID] == 'ditto') $data->modules[$caseID] = isset($prev['module']) ? $prev['module'] : 0;
            if($data->types[$caseID]   == 'ditto') $data->types[$caseID]   = isset($prev['type'])   ? $prev['type']   : '';

            $prev['pri']    = $data->pris[$caseID];
            $prev['module'] = $data->modules[$caseID];
            $prev['type']   = $data->types[$caseID];
        }

        /* Initialize cases from the post data.*/
        foreach($caseIDList as $caseID)
        {
            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->pri            = $data->pris[$caseID];
            $case->status         = $data->statuses[$caseID];
            $case->module         = $data->modules[$caseID];
            $case->color          = $data->colors[$caseID];
            $case->title          = $data->titles[$caseID];
            $case->precondition   = $data->precondition[$caseID];
            $case->keywords       = $data->keywords[$caseID];
            $case->type           = $data->types[$caseID];
            $case->stage          = empty($data->stages[$caseID]) ? '' : implode(',', $data->stages[$caseID]);

            $cases[$caseID] = $case;
            unset($case);
        }

        /* Update cases. */
        foreach($cases as $caseID => $case)
        {
            $oldCase = $this->getByID($caseID);
            $this->dao->update(TABLE_CASE)->data($case)
                ->autoCheck()
                ->batchCheck($this->config->testcase->edit->requiredFields, 'notempty')
                ->where('id')->eq($caseID)
                ->exec();

            if(!dao::isError())
            {
                unset($oldCase->steps);
                $allChanges[$caseID] = common::createChanges($oldCase, $case);
            }
            else
            {
                die(js::error('case#' . $caseID . dao::getError(true)));
            }
        }

        return $allChanges;
    }

    /**
     * Batch change the module of case.
     *
     * @param  array  $caseIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($caseIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldCases   = $this->getByList($caseIDList);
        foreach($caseIDList as $caseID)
        {
            $oldCase = $oldCases[$caseID];
            if($moduleID == $oldCase->module) continue;

            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->module         = $moduleID;

            $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->where('id')->eq((int)$caseID)->exec();
            if(!dao::isError()) $allChanges[$caseID] = common::createChanges($oldCase, $case);
        }
        return $allChanges;
    }

    /**
     * Join steps to a string, thus can diff them.
     * 
     * @param  array   $steps 
     * @access public
     * @return string
     */
    public function joinStep($steps)
    {
        $return = '';
        if(empty($steps)) return $return;
        foreach($steps as $step) $return .= $step->desc . ' EXPECT:' . $step->expect . "\n";
        return $return;
    }

    /**
     * Create case steps from a bug's step.
     * 
     * @param  string    $steps 
     * @access public
     * @return array
     */
    function createStepsFromBug($steps)
    {
        $steps        = strip_tags($steps);
        $caseSteps    = array((object)array('desc' => $steps, 'expect' => ''));   // the default steps before parse.
        $lblStep      = strip_tags($this->lang->bug->tplStep);
        $lblResult    = strip_tags($this->lang->bug->tplResult);
        $lblExpect    = strip_tags($this->lang->bug->tplExpect);
        $lblStepPos   = strpos($steps, $lblStep);
        $lblResultPos = strpos($steps, $lblResult);
        $lblExpectPos = strpos($steps, $lblExpect);

        if($lblStepPos === false or $lblResultPos === false or $lblExpectPos === false) return $caseSteps;

        $caseSteps  = substr($steps, $lblStepPos + strlen($lblStep), $lblResultPos - strlen($lblStep));
        $caseExpect = substr($steps, $lblExpectPos + strlen($lblExpect)); 
        $caseSteps  = trim($caseSteps);
        $caseExpect = trim($caseExpect);

        $caseSteps = explode("\n", trim($caseSteps));
        $stepCount = count($caseSteps);
        foreach($caseSteps as $key => $caseStep)
        {
            $expect = $key + 1 == $stepCount ? $caseExpect : '';
            $caseSteps[$key] = (object)array('desc' => trim($caseStep), 'expect' => $expect);
        }
        return $caseSteps;
    }

    /**
     * Adjust the action is clickable.
     * 
     * @param  object $case 
     * @param  string $action 
     * @access public
     * @return void
     */
    public static function isClickable($case, $action)
    {
        $action = strtolower($action);

        if($action == 'createbug') return $case->lastRunResult == 'fail';

        return true;
    }

    /**
     * Create from import 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function createFromImport($productID, $branch = 0)
    {
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('file');
        $now    = helper::now();
        $branch = (int)$branch;
        $data   = fixer::input('post')->get();

        if(!empty($_POST['id']))
        {
            $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.id')->in(($_POST['id']))
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.version=t2.version')
                ->orderBy('t2.id')
                ->fetchGroup('case');
            $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
        }

        $cases = array();
        foreach($data->product as $key => $product)
        {
            $caseData = new stdclass();

            $caseData->product      = $product;
            $caseData->module       = $data->module[$key];
            $caseData->story        = (int)$data->story[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->status       = $data->status[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
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

            $cases[$key] =$caseData;
        }

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
                    foreach($this->post->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc))continue;
                        $step = new stdclass();
                        $step->desc   = $desc;
                        $step->expect = trim($this->post->expect[$key][$id]);

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
                    $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->exec();
                    if($stepChanged)
                    {
                        foreach($steps as $id => $step)
                        {
                            $step = (array)$step;
                            if(empty($step['desc'])) continue;
                            $stepData = new stdclass();
                            $stepData->case    = $caseID;
                            $stepData->version = $version;
                            $stepData->desc    = htmlspecialchars($step['desc']);
                            $stepData->expect  = htmlspecialchars($step['expect']);
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
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
                $caseData->branch     = $branch;
                $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->exec();

                if(!dao::isError())
                {
                    $caseID = $this->dao->lastInsertID();
                    foreach($this->post->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $stepData = new stdclass();
                        $stepData->case    = $caseID;
                        $stepData->version = 1;
                        $stepData->desc    = htmlspecialchars($desc);
                        $stepData->expect  = htmlspecialchars($this->post->expect[$key][$id]);
                        $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                    }
                    $this->action->create('case', $caseID, 'Opened');
                }
            }
        }

        unlink($this->session->importFile);
        unset($_SESSION['importFile']);
    }

    /**
     * Get fields for import.
     * 
     * @access public
     * @return array
     */
    public function getImportFields()
    {
        $caseLang   = $this->lang->testcase;
        $caseConfig = $this->config->testcase;
        $fields     = explode(',', $caseConfig->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($caseLang->$fieldName) ? $caseLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($productID, $products, $queryID, $actionURL)
    {
        $this->config->testcase->search['params']['product']['values'] = array($productID => $products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->config->testcase->search['fields']['branch'] = $this->lang->product->branch;
            $this->config->testcase->search['params']['branch']['values'] = array('' => '') + $this->loadModel('branch')->getPairs($productID, 'noempty');
        }
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * Print cell data
     * 
     * @param  object $col 
     * @param  object $case 
     * @param  array  $users 
     * @param  array  $branches 
     * @access public
     * @return void
     */
    public function printCell($col, $case, $users, $branches, $modulePairs = array())
    {
        $caseLink = helper::createLink('testcase', 'view', "caseID=$case->id");
        $account  = $this->app->user->account;
        $id = $col->id;
        if($col->show)
        {
            $class = '';
            if($id == 'status') $class .= $case->status;
            if($id == 'title') $class .= ' text-left';
            if($id == 'lastRunResult') $class .= $case->lastRunResult;

            echo "<td class='" . $class . "'" . ($id=='title' ? " title='{$case->title}'":'') . ">";
            switch ($id)
            {
            case 'id':
                echo html::a($caseLink, sprintf('%03d', $case->id));
                break;
            case 'pri':
                echo "<span class='pri" . zget($this->lang->testcase->priList, $case->pri, $case->pri) . "'>";
                echo zget($this->lang->testcase->priList, $case->pri, $case->pri);
                echo "</span>";
                break;
            case 'title':
                if($case->branch) echo "<span class='label label-info label-badge'>{$branches[$case->branch]}</span> ";
                if($modulePairs and $case->module) echo "<span class='label label-info label-badge'>{$modulePairs[$case->module]}</span> ";
                echo html::a($caseLink, $case->title, null, "style='color: $case->color'");
                break;
            case 'branch':
                echo $branches[$case->branch];
                break;
            case 'type':
                echo $this->lang->testcase->typeList[$case->type];
                break;
            case 'stage':
                foreach(explode(',', trim($case->stage, ',')) as $stage) echo $this->lang->testcase->stageList[$stage] . '<br />';
                break;
            case 'status':
                echo $this->lang->testcase->statusList[$case->status];
                break;
            case 'story':
                static $stories = array();
                if(empty($stories)) $stories = $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq('0')->andWhere('product')->eq($case->product)->fetchPairs('id', 'title');
                if($case->story and isset($stories[$case->story])) echo html::a(helper::createLink('story', 'view', "storyID=$case->story"), $stories[$case->story]);
                break;
            case 'openedBy':
                echo zget($users, $case->openedBy, $case->openedBy);
                break;
            case 'openedDate':
                echo substr($case->openedDate, 5, 11);
                break;
            case 'lastRunner':
                echo zget($users, $case->lastRunner, $case->lastRunner);
                break;
            case 'lastRunDate':
                if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));
                break;
            case 'lastRunResult':
                if($case->lastRunResult) echo $this->lang->testcase->resultList[$case->lastRunResult];
                break;
            case 'actions':
                common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', 'play', '', 'runCase iframe');
                common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', '', '', 'results iframe');
                common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list');
                common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');

                if(common::hasPriv('testcase', 'delete'))
                {
                    $deleteURL = helper::createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"batchForm\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$this->lang->testcase->delete}' class='btn-icon'");
                }

                common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'bug', '', 'iframe');
                break;
            }
            echo '</td>';
        }
    }
}
