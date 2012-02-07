<?php
/**
 * The model file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class bugModel extends model
{
    /**
     * Set menu.
     * 
     * @param  array  $products 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function setMenu($products, $productID)
    {
        $this->loadModel('product')->setMenu($products, $productID);
        $selectHtml = $this->product->select($products, $productID, 'bug', 'browse');
        foreach($this->lang->bug->menu as $key => $menu)
        {
            $replace = ($key == 'product') ? $selectHtml . $this->lang->arrow : $productID;
            common::setMenuVars($this->lang->bug->menu, $key, $replace);
        }
    }

    /**
     * Create a bug.
     * 
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $now = helper::now();
        $bug = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->setDefault('project,story,task', 0)
            ->setDefault('openedBuild', '')
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->specialChars('title,keyword')
            ->cleanInt('product, module, severity')
            ->join('openedBuild', ',')
            ->remove('files, labels')
            ->get();
        $this->dao->insert(TABLE_BUG)->data($bug)->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('bug', $bugID);
            return $bugID;
        }
        return false;
    }

    /**
     * Get bugs of a module.
     * 
     * @param  int             $productID 
     * @param  string|array    $moduleIds 
     * @param  string          $orderBy 
     * @param  object          $pager 
     * @access public
     * @return array
     */
    public function getModuleBugs($productID, $moduleIds = 0, $projects, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('product')->eq((int)$productID)
            ->beginIF(!empty($moduleIds))->andWhere('module')->in($moduleIds)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get info of a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return object
     */
    public function getById($bugID)
    {
        $bug = $this->dao->select('t1.*, t2.name AS projectName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
        if(!$bug) return false;
        $bug->steps = $this->loadModel('file')->setImgSize($bug->steps);
        foreach($bug as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $bug->$key = '';
        if($bug->mailto)
        {
            $bug->mailto = ltrim(trim($bug->mailto), ',');  // Remove the first ,
            $bug->mailto = str_replace(' ', '', $bug->mailto);
            $bug->mailto = rtrim($bug->mailto, ',') . ',';
            $bug->mailto = str_replace(',', ', ', $bug->mailto);
        }
        if($bug->duplicateBug) $bug->duplicateBugTitle = $this->dao->findById($bug->duplicateBug)->from(TABLE_BUG)->fields('title')->fetch('title');
        if($bug->case)         $bug->caseTitle         = $this->dao->findById($bug->case)->from(TABLE_CASE)->fields('title')->fetch('title');
        if($bug->linkBug)      $bug->linkBugTitles     = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bug->linkBug)->fetchPairs();
        if($bug->toStory > 0)  $bug->toStoryTitle      = $this->dao->findById($bug->toStory)->from(TABLE_STORY)->fields('title')->fetch('title');
        if($bug->toTask > 0)   $bug->toTaskTitle       = $this->dao->findById($bug->toTask)->from(TABLE_TASK)->fields('name')->fetch('name');
        $bug->files = $this->loadModel('file')->getByObject('bug', $bugID);
        return $bug;
    }

    /**
     * getActiveBugs 
     * 
     * @param  object   $pager 
     * @param  int      $projectID 
     * @access public
     * @return array
     */
    public function getActiveBugs($pager, $projectID, $products)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('status')->eq('active')
            ->andWhere('toTask')->eq(0)
            ->andWhere('tostory')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->beginIF(empty($products))->andWhere('project')->eq($projectID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Update a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function update($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = helper::now();
        $bug = fixer::input('post')
            ->cleanInt('product,module,severity,project,story,task')
            ->specialChars('title,keyword')
            ->remove('comment,files,labels')
            ->setDefault('project,module,project,story,task,duplicateBug', 0)
            ->setDefault('openedBuild', '')
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->join('openedBuild', ',')
            ->setIF($this->post->assignedTo  != $oldBug->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->resolvedBy  != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution  != '' and $this->post->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($this->post->resolution  != '' and $this->post->resolvedBy   == '', 'resolvedBy',   $this->app->user->account)
            ->setIF($this->post->closedBy    != '' and $this->post->closedDate   == '', 'closedDate',   $now)
            ->setIF($this->post->closedDate  != '' and $this->post->closedBy     == '', 'closedBy',     $this->app->user->account)
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'assignedTo',   'closed') 
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'assignedDate', $now) 
            ->setIF($this->post->resolution  != '' or  $this->post->resolvedDate != '', 'status',       'resolved') 
            ->setIF($this->post->closedBy    != '' or  $this->post->closedDate   != '', 'status',       'closed') 
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedTo', $oldBug->openedBy) 
            ->setIF(($this->post->resolution != '' or  $this->post->resolvedDate != '') and $this->post->assignedTo == '', 'assignedDate', $now)
            ->setIF($this->post->resolution  == '' and $this->post->resolvedDate =='', 'status', 'active')
            ->setIF($this->post->resolution  != '', 'confirmed', 1)
            ->setIF($this->post->story != false and $this->post->story != $oldBug->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
            ->checkIF($bug->resolvedBy, 'resolution', 'notempty')
            ->checkIF($bug->closedBy,   'resolution', 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldBug, $bug);
    }

    /**
     * Confirm a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function confirm($bugID)
    {
        $now = helper::now();
        $bug->confirmed = 1;
        $bug->lastEditedBy = $this->app->user->account;
        $bug->lastEditedDate = $now;
        $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
    }

    /**
     * Resolve a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function resolve($bugID)
    {
        $now = helper::now();
        $bug = fixer::input('post')
            ->add('resolvedBy',     $this->app->user->account)
            ->add('resolvedDate',   $now)
            ->add('status',         'resolved')
            ->add('confirmed',      1)
            ->add('assignedDate',   $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('duplicateBug', 0)
            ->setDefault('assignedTo', $oldBug->openedBy)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->batchCheck($this->config->bug->resolve->requiredFields, 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();
    }

    /**
     * Activate a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function activate($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = helper::now();
        $bug = fixer::input('post')
            ->setDefault('assignedTo', $oldBug->resolvedBy)
            ->add('assignedDate', $now)
            ->add('resolution', '')
            ->add('status', 'active')
            ->add('resolvedDate', '0000-00-00')
            ->add('resolvedBy', '')
            ->add('resolvedBuild', '')
            ->add('closedBy', '')
            ->add('closedDate', '0000-00-00')
            ->add('duplicateBug', 0)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->join('openedBuild', ',')
            ->remove('comment,files,labels')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
        $this->dao->update(TABLE_BUG)->set('activatedCount = activatedCount + 1')->where('id')->eq((int)$bugID)->exec();
    }

    /**
     * Close a bug.
     * 
     * @param  int    $bugID 
     * @access public
     * @return void
     */
    public function close($bugID)
    {
        $oldBug = $this->getById($bugID);
        $now = helper::now();
        $bug = fixer::input('post')
            ->add('assignedTo',     'closed')
            ->add('assignedDate',   $now)
            ->add('status',         'closed')
            ->add('closedBy',       $this->app->user->account)
            ->add('closedDate',     $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('confirmed',      1)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
    }

    /**
     * Extract accounts from some bugs.
     * 
     * @param  int    $bugs 
     * @access public
     * @return array
     */
    public function extractAccountsFromList($bugs)
    {
        $accounts = array();
        foreach($bugs as $bug)
        {
            if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
            if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
            if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
            if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
            if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /**
     * Extract accounts from a bug.
     * 
     * @param  object    $bug 
     * @access public
     * @return array
     */
    public function extractAccountsFromSingle($bug)
    {
        $accounts = array();
        if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
        if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
        if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
        if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
        if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        return array_unique($accounts);
    }

    /**
     * Get bug pairs of a user.
     * 
     * @param  int    $account 
     * @param  bool   $appendProduct 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getUserBugPairs($account, $appendProduct = true, $limit = 0)
    {
        $bugs = array();
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('id desc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->query();
        while($bug = $stmt->fetch())
        {
            if($appendProduct) $bug->title = $bug->product . ' / ' . $bug->title;
            $bugs[$bug->id] = $bug->title;
        }
        return $bugs;
    }

    /**
     * Get bugs of a project.
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getProjectBugs($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('project')->eq((int)$projectID)
            ->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bug info from a result.
     * 
     * @param  int    $resultID 
     * @param  int    $caseID
     * @param  int    $version
     * @access public
     * @return array
     */
    public function getBugInfoFromResult($resultID, $caseID = 0, $version = 0)
    {
        $title    = '';
        $bugSteps = '';

        $result = $this->dao->findById($resultID)->from(TABLE_TESTRESULT)->fetch();
        if($caseID > 0)
            $run->case = $this->loadModel('testcase')->getById($caseID, $result->version);
        else
            $run = $this->loadModel('testtask')->getRunById($result->run);
        if($result and $result->caseResult == 'fail')
        {
            $title       = $run->case->title;
            $caseSteps   = $run->case->steps;
            $stepResults = unserialize($result->stepResults);
            if($run->case->precondition != '')
            {
                $bugSteps = "<p>[" . $this->lang->testcase->precondition . "]</p>" . "\n" . $run->case->precondition;
            }
            $bugSteps .= $this->lang->bug->tplStep;
            if(!empty($stepResults))
            {
                foreach($caseSteps as $key => $step)
                {
                    $bugSteps .= ($key + 1) . '. '  .$step->desc . "<br />";
                    if($stepResults[$step->id]['result'] == 'fail')
                    {
                        $bugSteps .= $this->lang->bug->tplResult;
                        $bugSteps .= $stepResults[$step->id]['real'] . "<br />";
                        $bugSteps .= $this->lang->bug->tplExpect;
                        $bugSteps .= $step->expect;
                        break;
                    }
                }
            }
            else
            {
                $bugSteps .= $this->lang->bug->tplResult;
                $bugSteps .= $this->lang->bug->tplExpect;
            }
        }
        return array('title' => $title, 'steps' => $bugSteps, 'storyID' => $run->case->story);
    }

    /**
     * Get report data of bugs per project 
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerProject()
    {
        $datas = $this->dao->select('project as name, count(project) as value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('project')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $projects = $this->loadModel('project')->getPairs();
        foreach($datas as $projectID => $data) $data->name = isset($projects[$projectID]) ? $projects[$projectID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of bugs per module 
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerModule()
    {
        $datas = $this->dao->select('module as name, count(module) as value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(array_keys($datas))->fetchPairs();
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        return $datas;
    }

    /**
     * Get report data of opened bugs per day.
     * 
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerDay()
    {
        return $this->dao->select('DATE_FORMAT(openedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('openedDate')->fetchAll();
    }

    /**
     * Get report data of resolved bugs per day.
     * 
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerDay()
    {
        return $this->dao->select('DATE_FORMAT(resolvedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)
            ->where($this->session->bugReportCondition)->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('resolvedDate')
            ->fetchAll();
    }

    /**
     * Get report data of closed bugs per day.
     * 
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerDay()
    {
        return $this->dao->select('DATE_FORMAT(closedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)
            ->where($this->session->bugReportCondition)->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('closedDate')->fetchAll();
    }

    /**
     * Get report data of openeded bugs per user.
     * 
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerUser()
    {
        $datas = $this->dao->select('openedBy AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of resolved bugs per user.
     * 
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUser()
    {
        $datas = $this->dao->select('resolvedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)->where($this->session->bugReportCondition)
            ->andWhere('resolvedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of closed bugs per user.
     * 
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerUser()
    {
        $datas = $this->dao->select('closedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->session->bugReportCondition)
            ->andWhere('closedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of bugs per severity.
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerSeverity()
    {
        $datas = $this->dao->select('severity AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $severity => $data) if(isset($this->lang->bug->severityList[$severity])) $data->name = $this->lang->bug->severityList[$severity];
        return $datas;
    }

    /**
     * Get report data of bugs per resolution.
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerResolution()
    {
        $datas = $this->dao->select('resolution AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->session->bugReportCondition)
            ->andWhere('resolution')->ne('')
            ->groupBy('name')->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $resolution => $data) if(isset($this->lang->bug->resolutionList[$resolution])) $data->name = $this->lang->bug->resolutionList[$resolution];
        return $datas;
    }

    /**
     * Get report data of bugs per status.
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerStatus()
    {
        $datas = $this->dao->select('status AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) if(isset($this->lang->bug->statusList[$status])) $data->name = $this->lang->bug->statusList[$status];
        return $datas;
    }

    /**
     * Get report data of bugs per status.
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerActivatedCount()
    {
        $datas = $this->dao->select('activatedCount AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $data) $data->name = $this->lang->bug->report->bugsPerActivatedCount->graph->xAxisName . ':' . $data->name; 
        return $datas;
    }

    /**
     * Get report data of bugs per type.
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerType()
    {
        $datas = $this->dao->select('type AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->session->bugReportCondition)->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $type => $data) if(isset($this->lang->bug->typeList[$type])) $data->name = $this->lang->bug->typeList[$type];
        return $datas;
    }
  
    /**
     * getDataOfBugsPerAssignedTo 
     * 
     * @access public
     * @return void
     */
    public function getDataOfBugsPerAssignedTo()
    {
        $datas = $this->dao->select('assignedTo AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)->where($this->session->bugReportCondition)
            ->groupBy('name')
            ->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }
    
    /**
     * Merge the default chart settings and the settings of current chart.
     * 
     * @param  string    $chartType 
     * @access public
     * @return void
     */
    public function mergeChartOption($chartType)
    {
        $chartOption  = $this->lang->bug->report->$chartType;
        $commonOption = $this->lang->bug->report->options;

        $chartOption->graph->caption = $this->lang->bug->report->charts[$chartType];
        if(!isset($chartOption->swf))    $chartOption->swf    = $commonOption->swf;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* 合并配置。*/
        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
    }

    /**
     * Get bug templates of a user.
     * 
     * @param  string    $account 
     * @access public
     * @return array
     */
    public function getUserBugTemplates($account)
    {
        $templates = $this->dao->select('id, title, content')
            ->from(TABLE_USERTPL)
            ->where('account')->eq($account)
            ->orderBy('id')
            ->fetchAll();
        return $templates;
    }

    /**
     * Save user template.
     * 
     * @access public
     * @return void
     */
    public function saveUserBugTemplate()
    {
        $template = fixer::input('post')
            ->specialChars('title')
            ->add('account', $this->app->user->account)
            ->add('type', 'bug')
            ->get();
        $this->dao->insert(TABLE_USERTPL)->data($template)->autoCheck('title, content', 'notempty')->check('title', 'unique')->exec();
    }

    /**
     * Return the file => label pairs of some fields.
     * 
     * @param  string    $fields 
     * @access public
     * @return array
     */
    public function getFieldPairs($fields)
    {
        $fields = explode(',', $fields);
        foreach($fields as $key => $field)
        {
            $field = trim($field);
            $fields[$field] = $this->lang->bug->$field;
            unset($fields[$key]);
        }
        return $fields;
    }

    /**
     * Get all bugs.
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getAllBugs($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)->where('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of assign to me. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssigntome($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of opened by me. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByOpenedbyme($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of resolved by me. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByResolvedbyme($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of nobody to do. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssigntonull($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByAssignedTo('')->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs the status is active or unclosed. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  string $status 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByStatus($productID, $projects, $status, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('project')->in(array_keys($projects))
            ->andWhere('product')->eq($productID)
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($status == 'unresolved')->andWhere('status')->eq('active')->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get unresolve bugs for long time. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByLonglifebugs($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByLastEditedDate("<", date(DT_DATE1, strtotime('-7 days')))->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('openedDate')->lt(date(DT_DATE1,strtotime('-7 days')))
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get postponed bugs. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByPostponedbugs($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->findByResolution('postponed')->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs need confirm. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByNeedconfirm($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where("t2.status = 'active'")
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.version > t1.storyVersion')
            ->andWhere('t1.project')->in(array_keys($projects))
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * Get bugs by search. 
     * 
     * @param  int    $productID 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getBySearch($productID, $projects, $queryID, $orderBy, $pager)
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('bugQuery', $query->sql);
                $this->session->set('bugForm', $query->form);
            }
            else
            {
                $this->session->set('bugQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->bugQuery == false) $this->session->set('bugQuery', ' 1 = 1');
        }

        /* check the purview of projects.*/
        if(strpos($this->session->bugQuery, '`project`') === false) 
        {
            $var = $this->session->bugQuery . 'AND `project`' . helper::dbIN(array_keys($projects));
            $this->session->set('bugQuery', "$var");
        }

        $bugQuery = str_replace("`product` = 'all'", '1', $this->session->bugQuery); // Search all product.
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where($bugQuery)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        return $bugs;
    }

    /**
     * Form customed bugs. 
     * 
     * @param  array    $bugs 
     * @access public
     * @return array
     */
    public function formCustomedBugs($bugs)
    {
        /* Get related objects id lists. */
        $relatedModuleIdList   = array();
        $relatedStoryIdList    = array();
        $relatedTaskIdList     = array();
        $relatedCaseIdList     = array();
        $relatedProjectIdList  = array();

        foreach($bugs as $bug)
        {
            $relatedModuleIdList[$bug->module]   = $bug->module;
            $relatedStoryIdList[$bug->story]     = $bug->story;
            $relatedTaskIdList[$bug->task]       = $bug->task;
            $relatedCaseIdList[$bug->case]       = $bug->case;
            $relatedProjectIdList[$bug->project] = $bug->project;

            /* Get related objects title or names. */
            $relatedModules   = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($relatedModuleIdList)->fetchPairs();
            $relatedStories   = $this->dao->select('id, title')->from(TABLE_STORY) ->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedTasks     = $this->dao->select('id, name')->from(TABLE_TASK)->where('id')->in($relatedTaskIdList)->fetchPairs();
            $relatedCases     = $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
            $relatedProjects  = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->in($relatedProjectIdList)->fetchPairs();

            /* fill some field with useful value. */
            if(isset($relatedModules[$bug->module]))    $bug->module       = $relatedModules[$bug->module];
            if(isset($relatedStories[$bug->story]))     $bug->story        = $relatedStories[$bug->story];
            if(isset($relatedTasks[$bug->task]))        $bug->task         = $relatedTasks[$bug->task];
            if(isset($relatedCases[$bug->case]))        $bug->case         = $relatedCases[$bug->case];
            if(isset($relatedProjects[$bug->project]))  $bug->project      = $relatedProjects[$bug->project];
        }
        return $bugs;
    }
}
