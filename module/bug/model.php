<?php
/**
 * The model file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
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
    public function setMenu($products, $productID, $branch = 0, $moduleID = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch, $moduleID, 'bug');
        $selectHtml = $this->product->select($products, $productID, 'bug', 'browse', '', $branch, $moduleID, 'bug');

        foreach($this->lang->bug->menu as $key => $menu)
        {
            if($this->config->global->flow != 'onlyTest')
            {
                $replace = ($key == 'product') ? $selectHtml : $productID;
            }
            else
            {
                if($key == 'product')
                {
                    $replace = $selectHtml;
                }
                else
                {
                    $replace = array();
                    $replace['productID'] = $productID;
                    $replace['branch']    = $branch;
                    $replace['param']     = $moduleID;
                }
            }
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
            ->setDefault('deadline', '0000-00-00')
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product, module, severity')
            ->join('openedBuild', ',')
            ->join('mailto', ',')
            ->remove('files, labels,uid')
            ->get();

        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_BUG)->data($bug)->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $bugID, 'bug');
            $this->file->saveUpload('bug', $bugID);
            return array('status' => 'created', 'id' => $bugID);
        }
        return false;
    }

    /**
     * Batch create 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function batchCreate($productID, $branch = 0)
    {
        $this->loadModel('action');
        $branch     = (int)$branch;
        $now        = helper::now();
        $actions    = array();
        $data       = fixer::input('post')->get();
        $batchNum   = count(reset($data));

        $result = $this->loadModel('common')->removeDuplicate('bug', $data, "product={$productID}");
        $data   = $result['data'];

        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($data->title[$i]) and empty($data->openedBuilds[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->bug->openedBuild)));
        }

        /* Get pairs(moduleID => moduleOwner) for bug. */
        $stmt         = $this->dbh->query($this->loadModel('tree')->buildMenuQuery($productID, 'bug', $startModuleID = 0, $branch));
        $moduleOwners = array();
        while($module = $stmt->fetch()) $moduleOwners[$module->id] = $module->owner;

        $module  = 0;
        $project = 0;
        $type    = '';
        $pri     = 0;
        $os      = '';
        $browser = '';
        for($i = 0; $i < $batchNum; $i++)
        {
            if($data->modules[$i]  != 'ditto') $module  = (int)$data->modules[$i];
            if($data->projects[$i] != 'ditto') $project = (int)$data->projects[$i];
            if($data->types[$i]    != 'ditto') $type    = $data->types[$i];
            if($data->pris[$i]     != 'ditto') $pri     = $data->pris[$i];
            if($data->oses[$i]     != 'ditto') $os      = $data->oses[$i];
            if($data->browsers[$i] != 'ditto') $browser = $data->browsers[$i];

            $data->modules[$i]  = (int)$module;
            $data->projects[$i] = (int)$project;
            $data->types[$i]    = $type;
            $data->pris[$i]     = $pri;
            $data->oses[$i]     = $os;
            $data->browsers[$i] = $browser;
        }

        if(isset($data->uploadImage)) $this->loadModel('file');
        for($i = 0; $i < $batchNum; $i++)
        {
            if(empty($data->title[$i])) continue;

            $bug = new stdClass();
            $bug->openedBy    = $this->app->user->account;
            $bug->openedDate  = $now;
            $bug->product     = $productID;
            $bug->branch      = $data->branches[$i];
            $bug->module      = $data->modules[$i];
            $bug->project     = $data->projects[$i];
            $bug->openedBuild = implode(',', $data->openedBuilds[$i]);
            $bug->color       = $data->color[$i];
            $bug->title       = $data->title[$i];
            $bug->steps       = nl2br($data->stepses[$i]);
            $bug->type        = $data->types[$i];
            $bug->pri         = $data->pris[$i];
            $bug->severity    = $data->severities[$i];
            $bug->os          = $data->oses[$i];
            $bug->browser     = $data->browsers[$i];
            $bug->keywords    = $data->keywords[$i];

            if(!empty($data->uploadImage[$i]))
            {
                $fileName = $data->uploadImage[$i];
                $file     = $this->session->bugImagesFile[$fileName];

                $realPath = $file['realpath'];
                unset($file['realpath']);
                if(rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
                {
                    if(in_array($file['extension'], $this->config->file->imageExtensions))
                    {
                        $file['addedBy']    = $this->app->user->account;
                        $file['addedDate']  = $now;
                        $this->dao->insert(TABLE_FILE)->data($file)->exec();

                        $fileID = $this->dao->lastInsertID;
                        $bug->steps .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
                        unset($file);
                    }
                }
                else
                {
                    unset($file);
                }
            }

            if(!empty($moduleOwners[$bug->module]))
            {
                $bug->assignedTo   = $moduleOwners[$bug->module];
                $bug->assignedDate = $now;
            }

            $this->dao->insert(TABLE_BUG)->data($bug)->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
            $bugID = $this->dao->lastInsertID();

            if(!empty($data->uploadImage[$i]) and !empty($file))
            {
                $file['objectType'] = 'bug';
                $file['objectID']   = $bugID;
                $file['addedBy']    = $this->app->user->account;    
                $file['addedDate']  = $now;     
                $this->dao->insert(TABLE_FILE)->data($file)->exec();
                unset($file);
            }

            if(dao::isError()) die(js::error('bug#' . ($i+1) . dao::getError(true)));
            $actions[$bugID] = $this->action->create('bug', $bugID, 'Opened');
        }

        /* Remove upload image file and session. */
        if(!empty($data->uploadImage) and $this->session->bugImagesFile)
        {
            $classFile = $this->app->loadClass('zfile'); 
            $file = current($_SESSION['bugImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $classFile->removeDir($realPath);
            unset($_SESSION['bugImagesFile']);
        }

        return $actions;
    }

    /**
     * Get bugs.
     *
     * @param  int    $productID
     * @param  array  $projects
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  int    $queryID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugs($productID, $projects, $branch, $browseType, $moduleID, $queryID, $sort, $pager)
    {
        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $browseType = ($browseType == 'bymodule' and $this->session->bugBrowseType and $this->session->bugBrowseType != 'bysearch') ? $this->session->bugBrowseType : $browseType;

        /* Get bugs by browse type. */
        $bugs = array();
        if($browseType == 'all')               $bugs = $this->getAllBugs($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'bymodule')      $bugs = $this->getModuleBugs($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'assigntome')    $bugs = $this->getByAssigntome($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'openedbyme')    $bugs = $this->getByOpenedbyme($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'resolvedbyme')  $bugs = $this->getByResolvedbyme($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'assigntonull')  $bugs = $this->getByAssigntonull($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'unconfirmed')   $bugs = $this->getUnconfirmed($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'unresolved')    $bugs = $this->getByStatus($productID, $branch, $modules, $projects, 'unresolved', $sort, $pager);
        elseif($browseType == 'unclosed')      $bugs = $this->getByStatus($productID, $branch, $modules, $projects, 'unclosed', $sort, $pager);
        elseif($browseType == 'toclosed')      $bugs = $this->getByStatus($productID, $branch, $modules, $projects, 'toclosed', $sort, $pager);
        elseif($browseType == 'longlifebugs')  $bugs = $this->getByLonglifebugs($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'postponedbugs') $bugs = $this->getByPostponedbugs($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'needconfirm')   $bugs = $this->getByNeedconfirm($productID, $branch, $modules, $projects, $sort, $pager);
        elseif($browseType == 'bysearch')      $bugs = $this->getBySearch($productID, $queryID, $sort, $pager, $branch);
        elseif($browseType == 'overduebugs')   $bugs = $this->getOverdueBugs($productID, $branch, $modules, $projects, $sort, $pager);

        return $this->checkDelayBugs($bugs);
    }

    /**
     * Check delay bug.
     * 
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function checkDelayBugs($bugs)
    {
        foreach ($bugs as $bug)
        {
            // Delayed or not?.
            if($bug->deadline != '0000-00-00')
            {
                if(substr($bug->resolvedDate, 0, 10) != '0000-00-00')
                {
                    $delay = helper::diffDate(substr($bug->resolvedDate, 0, 10), $bug->deadline);
                }
                elseif($bug->status == 'active')
                {
                    $delay = helper::diffDate(helper::today(), $bug->deadline);
                }

                if(isset($delay) and $delay > 0) $bug->delay = $delay;
            }
        }

        return $bugs;
    }

    /**
     * Get bugs of a module.
     * 
     * @param  int             $productID 
     * @param  string|array    $moduleIdList
     * @param  string          $orderBy 
     * @param  object          $pager 
     * @access public
     * @return array
     */
    public function getModuleBugs($productID, $branch = 0, $moduleIdList = 0, $projects, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('product')->eq((int)$productID)
            ->beginIF(!empty($branch))->andWhere('branch')->eq($branch)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bug list of a plan.
     * 
     * @param  int    $planID 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return void
     */
    public function getPlanBugs($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('plan')->eq((int)$planID)
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
        
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');
        
        return $bugs;
    }

    /**
     * Get info of a bug.
     * 
     * @param  int    $bugID 
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getById($bugID, $setImgSize = false)
    {
        $bug = $this->dao->select('t1.*, t2.name AS projectName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName, t5.title AS planName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t5')->on('t1.plan = t5.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
        if(!$bug) return false;

        $bug = $this->loadModel('file')->replaceImgURL($bug, 'steps');
        if($setImgSize) $bug->steps = $this->file->setImgSize($bug->steps);
        foreach($bug as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $bug->$key = '';

        if($bug->duplicateBug) $bug->duplicateBugTitle = $this->dao->findById($bug->duplicateBug)->from(TABLE_BUG)->fields('title')->fetch('title');
        if($bug->case)         $bug->caseTitle         = $this->dao->findById($bug->case)->from(TABLE_CASE)->fields('title')->fetch('title');
        if($bug->linkBug)      $bug->linkBugTitles     = $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bug->linkBug)->fetchPairs();
        if($bug->toStory > 0)  $bug->toStoryTitle      = $this->dao->findById($bug->toStory)->from(TABLE_STORY)->fields('title')->fetch('title');
        if($bug->toTask > 0)   $bug->toTaskTitle       = $this->dao->findById($bug->toTask)->from(TABLE_TASK)->fields('name')->fetch('name');

        $bug->toCases = array();
        $toCases      = $this->dao->select('id, title')->from(TABLE_CASE)->where('`fromBug`')->eq($bugID)->fetchAll();
        foreach($toCases as $toCase) $bug->toCases[$toCase->id] = $toCase->title;

        $bug->files = $this->loadModel('file')->getByObject('bug', $bugID);

        return $bug;
    }

    /**
     * Get bug list.
     * 
     * @param  int|array|string    $bugIDList 
     * @access public
     * @return array
     */
    public function getByList($bugIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF($bugIDList)->andWhere('id')->in($bugIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * getActiveBugs 
     * 
     * @param  array    $products
     * @param  int      $projectID 
     * @param  object   $pager 
     * @access public
     * @return array
     */
    public function getActiveBugs($products, $branch, $projectID, $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('status')->eq('active')
            ->andWhere('toTask')->eq(0)
            ->andWhere('tostory')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->beginIF(empty($products))->andWhere('project')->eq($projectID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * get Active And Postponed Bugs 
     * 
     * @param  int    $products 
     * @param  int    $projectID 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getActiveAndPostponedBugs($products, $projectID, $pager = null)
    {
        return $this->dao->select('t1.*')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.product = t2.product')
            ->where("((t1.status = 'resolved' AND t1.resolution = 'postponed') OR (t1.status = 'active'))")
            ->andWhere('t1.toTask')->eq(0)
            ->andWhere('t1.toStory')->eq(0)
            ->beginIF(!empty($products))->andWhere('t1.product')->in($products)->fi()
            ->beginIF(empty($products))->andWhere('t1.project')->eq($projectID)->fi()
            ->andWhere('t2.project')->eq($projectID)
            ->andWhere("(t2.branch = '0' OR t1.branch = '0' OR t2.branch = t1.branch)")
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get module owner.
     * 
     * @param  int    $moduleID 
     * @param  int    $productID 
     * @access public
     * @return string
     */
    public function getModuleOwner($moduleID, $productID)
    {
        $users = $this->loadModel('user')->getPairs('nodeleted');

        if($moduleID)
        {
            $module = $this->dao->findByID($moduleID)->from(TABLE_MODULE)->fetch();
            if($module->owner and isset($users[$module->owner])) return $module->owner;

            $moduleIDList = explode(',', trim(str_replace(",$module->id,", ',', $module->path), ','));
            krsort($moduleIDList);
            if($moduleIDList)
            {
                $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($moduleIDList)->andWhere('deleted')->eq(0)->fetchAll('id');
                foreach($moduleIDList as $moduleID)
                {
                    if(isset($modules[$moduleID]))
                    {
                        $module = $modules[$moduleID];
                        if($module->owner and isset($users[$module->owner])) return $module->owner;
                    }
                }
            }
        }

        $owner = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('QD');
        return isset($users[$owner]) ? $owner : '';
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
        $oldBug = $this->dao->select('*')->from(TABLE_BUG)->where('id')->eq((int)$bugID)->fetch();
        if(!empty($_POST['lastEditedDate']) and $oldBug->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now = helper::now();
        $bug = fixer::input('post')
            ->cleanInt('product,module,severity,project,story,task')
            ->stripTags($this->config->bug->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('project,module,project,story,task,duplicateBug,branch', 0)
            ->setDefault('openedBuild', '')
            ->setDefault('plan', 0)
            ->setDefault('deadline', '0000-00-00')
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->join('openedBuild', ',')
            ->join('mailto', ',')
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
            ->remove('comment,files,labels,uid')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
            ->checkIF($bug->resolvedBy, 'resolution', 'notempty')
            ->checkIF($bug->closedBy,   'resolution', 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $bugID, 'bug');
            return common::createChanges($oldBug, $bug);
        }
    }

    /**
     * Batch update bugs.
     * 
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $bugs       = array();
        $allChanges = array();
        $now        = helper::now();
        $data       = fixer::input('post')->get();
        $bugIDList  = $this->post->bugIDList ? $this->post->bugIDList : array();

        if(!empty($bugIDList))
        {
            /* Process the data if the value is 'ditto'. */
            foreach($bugIDList as $bugID)
            {
                if($data->types[$bugID]       == 'ditto') $data->types[$bugID]       = isset($prev['type'])       ? $prev['type']       : ''; 
                if($data->severities[$bugID]  == 'ditto') $data->severities[$bugID]  = isset($prev['severity'])   ? $prev['severity']   : 3; 
                if($data->pris[$bugID]        == 'ditto') $data->pris[$bugID]        = isset($prev['pri'])        ? $prev['pri']        : 0; 
                if($data->branches[$bugID]    == 'ditto') $data->branches[$bugID]    = isset($prev['branch'])     ? $prev['branch'] : 0; 
                if($data->plans[$bugID]       == 'ditto') $data->plans[$bugID]       = isset($prev['plan'])       ? $prev['plan'] : ''; 
                if($data->assignedTos[$bugID] == 'ditto') $data->assignedTos[$bugID] = isset($prev['assignedTo']) ? $prev['assignedTo'] : ''; 
                if($data->resolvedBys[$bugID] == 'ditto') $data->resolvedBys[$bugID] = isset($prev['resolvedBy']) ? $prev['resolvedBy'] : ''; 
                if($data->resolutions[$bugID] == 'ditto') $data->resolutions[$bugID] = isset($prev['resolution']) ? $prev['resolution'] : ''; 
                if($data->os[$bugID]          == 'ditto') $data->os[$bugID]          = isset($prev['os'])         ? $prev['os'] : ''; 
                if($data->browsers[$bugID]    == 'ditto') $data->browsers[$bugID]    = isset($prev['browser'])    ? $prev['browser'] : ''; 

                $prev['type']       = $data->types[$bugID];
                $prev['severity']   = $data->severities[$bugID];
                $prev['pri']        = $data->pris[$bugID];
                $prev['branch']     = $data->branches[$bugID];
                $prev['plan']       = $data->plans[$bugID];
                $prev['assignedTo'] = $data->assignedTos[$bugID];
                $prev['resolvedBy'] = $data->resolvedBys[$bugID];
                $prev['resolution'] = $data->resolutions[$bugID];
                $prev['os']         = $data->os[$bugID];
                $prev['browser']    = $data->browsers[$bugID];
            }

            /* Initialize bugs from the post data.*/
            $oldBugs = $bugIDList ? $this->getByList($bugIDList) : array();
            foreach($bugIDList as $bugID)
            {
                $oldBug = $oldBugs[$bugID];

                $bug = new stdclass();
                $bug->lastEditedBy   = $this->app->user->account;
                $bug->lastEditedDate = $now;
                $bug->type           = $data->types[$bugID];
                $bug->severity       = $data->severities[$bugID];
                $bug->pri            = $data->pris[$bugID];
                $bug->status         = $data->statuses[$bugID];
                $bug->color          = $data->colors[$bugID];
                $bug->title          = $data->titles[$bugID];
                $bug->plan           = empty($data->plans[$bugID]) ? 0 : $data->plans[$bugID];
                $bug->branch         = empty($data->branches[$bugID]) ? 0 : $data->branches[$bugID];
                $bug->assignedTo     = $data->assignedTos[$bugID];
                $bug->deadline       = $data->deadlines[$bugID];
                $bug->resolvedBy     = $data->resolvedBys[$bugID];
                $bug->keywords       = $data->keywords[$bugID];
                $bug->os             = $data->os[$bugID];
                $bug->browser        = $data->browsers[$bugID];
                $bug->resolution     = $data->resolutions[$bugID];
                $bug->duplicateBug   = $data->duplicateBugs[$bugID] ? $data->duplicateBugs[$bugID] : $oldBug->duplicateBug;

                if($bug->assignedTo  != $oldBug->assignedTo)           $bug->assignedDate = $now;
                if(($bug->resolvedBy != '' or $bug->resolution != '') and $oldBug->status != 'resolved') $bug->resolvedDate = $now;
                if($bug->resolution  != '' and $bug->resolvedBy == '') $bug->resolvedBy   = $this->app->user->account;
                if($bug->resolution  != '' and $bug->status != 'closed')
                { 
                    $bug->status    = 'resolved';
                    $bug->confirmed = 1; 
                }
                if($bug->resolution  != '' and $bug->assignedTo == '') 
                {
                    $bug->assignedTo   = $oldBug->openedBy;
                    $bug->assignedDate = $now;
                }

                $bugs[$bugID] = $bug;
                unset($bug);
            }

            /* Update bugs. */
            foreach($bugs as $bugID => $bug)
            {
                $oldBug = $oldBugs[$bugID];

                $this->dao->update(TABLE_BUG)->data($bug)
                    ->autoCheck()
                    ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
                    ->checkIF($bug->resolvedBy, 'resolution', 'notempty')
                    ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
                    ->where('id')->eq((int)$bugID)
                    ->exec();

                if(!dao::isError())
                {
                    $allChanges[$bugID] = common::createChanges($oldBug, $bug);
                }
                else
                {
                    die(js::error('bug#' . $bugID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Assign a bug to a user again.
     * 
     * @param  int    $bugID 
     * @access public
     * @return string
     */
    public function assign($bugID)
    {
        $now = helper::now();
        $oldBug = $this->getById($bugID);
        $bug = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->remove('comment')
            ->join('mailto', ',')
            ->get();

        $this->dao->update(TABLE_BUG)
            ->data($bug)
            ->autoCheck()
            ->where('id')->eq($bugID)->exec(); 

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
        $now    = helper::now();
        $oldBug = $this->getById($bugID);

        $bug = fixer::input('post')
            ->setDefault('confirmed', 1)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->remove('comment')
            ->join('mailto', ',')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();

        if(!dao::isError()) return common::createChanges($oldBug, $bug);
    }

    /**
     * Batch confirm bugs.
     * 
     * @param  array $bugIDList 
     * @access public
     * @return void
     */
    public function batchConfirm($bugIDList)
    {
        $now  = helper::now();
        $bugs = $this->getByList($bugIDList);
        foreach($bugIDList as $bugID)
        {
            if($bugs[$bugID]->confirmed) continue;

            $bug = new stdclass();
            $bug->assignedTo     = $this->app->user->account;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->confirmed      = 1;

            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
        }
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
        $now    = helper::now();
        $oldBug = $this->getById($bugID);
        $bug    = fixer::input('post')
            ->add('resolvedBy',     $this->app->user->account)
            ->add('status',         'resolved')
            ->add('confirmed',      1)
            ->add('assignedDate',   $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('resolvedDate', $now)
            ->setDefault('duplicateBug', 0)
            ->setDefault('assignedTo', $oldBug->openedBy)
            ->remove('comment,files,labels')
            ->get();

        /* Can create build when resolve bug. */
        if(isset($bug->createBuild))
        {
            if(empty($bug->buildName)) dao::$errors['buildName'][] = sprintf($this->lang->error->notempty, $this->lang->bug->placeholder->newBuildName);
            if(empty($bug->buildProject)) dao::$errors['buildProject'][] = sprintf($this->lang->error->notempty, $this->lang->bug->project);
            if(dao::isError()) return false;

            $buildData = new stdclass();
            $buildData->product = $oldBug->product;
            $buildData->branch  = $oldBug->branch;
            $buildData->project = $bug->buildProject;
            $buildData->name    = $bug->buildName;
            $buildData->date    = date('Y-m-d');
            $buildData->builder = $this->app->user->account;
            $this->dao->insert(TABLE_BUILD)->data($buildData)->autoCheck()
                ->check('name', 'unique', "product = {$buildData->product} AND branch = {$buildData->branch} AND deleted = '0'")
                ->exec();
            if(dao::isError()) return false;
            $buildID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('build', $buildID, 'opened');
            $bug->resolvedBuild = $buildID;
        }
        unset($bug->buildName);
        unset($bug->createBuild);
        unset($bug->buildProject);

        if($bug->resolvedBuild != 'trunk') $bug->testtask = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($bug->resolvedBuild)->orderBy('id_desc')->limit(1)->fetch('id');

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->batchCheck($this->config->bug->resolve->requiredFields, 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();

        /* Link bug to build and release. */
        $this->linkBugToBuild($bugID, $bug->resolvedBuild);
    }

    /**
     * Batch change the module of bug.
     *
     * @param  array  $bugIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($bugIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldBugs    = $this->getByList($bugIDList);
        foreach($bugIDList as $bugID)
        {
            $oldBug = $oldBugs[$bugID];
            if($moduleID == $oldBug->module) continue;

            $bug = new stdclass();
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->module         = $moduleID;

            $this->dao->update(TABLE_BUG)->data($bug)->autoCheck()->where('id')->eq((int)$bugID)->exec();
            if(!dao::isError()) $allChanges[$bugID] = common::createChanges($oldBug, $bug);
        }
        return $allChanges;
    }

    /**
     * Batch resolve bugs.
     * 
     * @param  array    $bugIDList 
     * @param  string   $resolution 
     * @param  string   $resolvedBuild
     * @access public
     * @return void
     */
    public function batchResolve($bugIDList, $resolution, $resolvedBuild)
    {
        $now  = helper::now();
        $bugs = $this->getByList($bugIDList);

        $bug       = reset($bugs);
        $productID = $bug->product;
        $users     = $this->loadModel('user')->getPairs('nodeleted');
        $product   = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        $stmt      = $this->dao->query($this->loadModel('tree')->buildMenuQuery($productID, 'bug'));
        $modules   = array();
        while($module = $stmt->fetch()) $modules[$module->id] = $module;

        foreach($bugIDList as $i => $bugID)
        {
            $oldBug = $bugs[$bugID];
            if($oldBug->resolution == 'fixed')
            {
                unset($bugIDList[$i]); 
                continue;
            }
            if($oldBug->status != 'active') continue;

            $assignedTo = $oldBug->openedBy;
            if(!isset($users[$assignedTo]))
            {
                $assignedTo = '';
                $module     = isset($modules[$oldBug->module]) ? $modules[$oldBug->module] : '';
                while($module)
                {
                    if($module->owner and isset($users[$module->owner]))
                    {
                        $assignedTo = $module->owner;
                        break;
                    }
                    $module = isset($modules[$module->parent]) ? $modules[$module->parent] : '';
                }
                if(empty($assignedTo)) $assignedTo = $product->QD;
            }

            $bug = new stdClass();
            $bug->resolution     = $resolution;
            $bug->resolvedBuild  = $resolution == 'fixed' ? $resolvedBuild : '';
            $bug->resolvedBy     = $this->app->user->account;
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedTo     = $assignedTo;
            $bug->assignedDate   = $now;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;

            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
        }

        /* Link bug to build and release. */
        $this->linkBugToBuild($bugIDList, $resolvedBuild);

        return $bugIDList;
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
     * Link related bugs.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function linkBugs($bugID)
    {
        if($this->post->bugs == false) return;

        $bug       = $this->getById($bugID);
        $bugs2Link = $this->post->bugs;

        $bugs = implode(',', $bugs2Link) . ',' . trim($bug->linkBug, ',');
        $this->dao->update(TABLE_BUG)->set('linkBug')->eq(trim($bugs, ','))->where('id')->eq($bugID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $this->loadModel('action')->create('bug', $bugID, 'linkRelatedBug', '', implode(',', $bugs2Link));
    }

    /**
     * Get bugs to link.
     *
     * @param  int    $bugID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getBugs2Link($bugID, $browseType = 'bySearch', $queryID)
    {
        if($browseType == 'bySearch')
        {
            $bug       = $this->getById($bugID);
            $bugs2Link = $this->getBySearch($bug->product, $queryID, 'id', null, $bug->branch);
            foreach($bugs2Link as $key => $bug2Link)
            {
                if($bug2Link->id == $bugID) unset($bugs2Link[$key]);
                if(in_array($bug2Link->id, explode(',', $bug->linkBug))) unset($bugs2Link[$key]);
            }
            return $bugs2Link;
        }
        else
        {
            return array();
        }
    }

    /**
     * Unlink related bug.
     *
     * @param  int    $bugID
     * @param  int    $bug2Unlink
     * @access public
     * @return void
     */
    public function unlinkBug($bugID, $bug2Unlink = 0)
    {
        $bug = $this->getById($bugID);

        $bugs = explode(',', trim($bug->linkBug, ','));
        foreach($bugs as $key => $bugId)
        {
            if($bugId == $bug2Unlink) unset($bugs[$key]);
        }
        $bugs = implode(',', $bugs);

        $this->dao->update(TABLE_BUG)->set('linkBug')->eq($bugs)->where('id')->eq($bugID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $this->loadModel('action')->create('bug', $bugID, 'unlinkRelatedBug', '', $bug2Unlink);
    }

    /**
     * Get linkBugs.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    public function getLinkBugs($bugID)
    {
        $bug = $this->getById($bugID);
        return $this->dao->select('id, title')->from(TABLE_BUG)->where('id')->in($bug->linkBug)->fetchPairs();
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
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['product']['values']       = array($productID => $products[$productID], 'all' => $this->lang->bug->allProduct);
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($productID);
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairs($productID);
        $this->config->bug->search['params']['severity']['values']      = array(0 => '') + $this->lang->bug->severityList; //Fix bug #939.
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($productID, 0, $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;
            $this->config->bug->search['params']['branch']['values']  = array('' => '') + $this->loadModel('branch')->getPairs($productID, 'noempty') + array('all' => $this->lang->branch->all);
        }

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * Process the openedBuild and resolvedBuild fields for bugs.
     *
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function processBuildForBugs($bugs)
    {
        $productIdList = array();
        foreach($bugs as $bug) $productIdList[$bug->id] = $bug->product;
        $builds = $this->loadModel('build')->getProductBuildPairs(array_unique($productIdList), 0, $params = '');

        /* Process the openedBuild and resolvedBuild fields. */
        foreach($bugs as $key => $bug)
        {
            $openBuildIdList = explode(',', $bug->openedBuild);
            $openedBuild = '';
            foreach($openBuildIdList as $buildID)
            {
                $openedBuild .= isset($builds[$buildID]) ? $builds[$buildID] : $buildID;
                $openedBuild .= ',';
            }
            $bug->openedBuild   = rtrim($openedBuild, ',');
            $bug->resolvedBuild = isset($builds[$bug->resolvedBuild]) ? $builds[$bug->resolvedBuild] : $bug->resolvedBuild;
        }

        return $bugs;
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
     * Get user bugs. 
     * 
     * @param  string $account 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $limit 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getUserBugs($account, $type = 'assignedTo', $orderBy = 'id_desc', $limit = 0, $pager = null)
    {
        if(!$this->loadModel('common')->checkField(TABLE_BUG, $type)) return array();
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF($type != 'all')->andWhere("`$type`")->eq($account)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll();
        return $bugs ? $bugs : array();
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
     * @param  int    $build
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBugs($projectID, $build = 0, $type = '', $param = 0, $orderBy = 'id_desc', $pager = null)
    {
        if($type == 'bySearch')
        {
            $queryID  = (int)$param;
            $products = $this->loadModel('project')->getProducts($projectID);

            if($this->session->projectBugQuery == false) $this->session->set('projectBugQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('projectBugQuery', $query->sql);
                    $this->session->set('projectBugForm', $query->form);
                }
            }

            $allProduct = "`product` = 'all'";
            $bugQuery   = $this->session->projectBugQuery;
            if(strpos($this->session->projectBugQuery, $allProduct) !== false)
            {
                $bugQuery = str_replace($allProduct, '1', $this->session->projectBugQuery);
            }

            $bugs = $this->dao->select('*')->from(TABLE_BUG)
                ->where($bugQuery)
                ->andWhere('project')->eq((int)$projectID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $bugs = $this->dao->select('*')->from(TABLE_BUG)
                ->where('deleted')->eq(0)
                ->beginIF(empty($build))->andWhere('project')->eq($projectID)->fi()
                ->beginIF($build)->andWhere("CONCAT(',', openedBuild, ',') like '%,$build,%'")->fi()
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * Get product left bugs. 
     * 
     * @param  int    $build 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getProductLeftBugs($build, $productID, $branch = 0)
    {
        $build = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq($build)->fetch();
        if(empty($build->project)) return array();

        $project      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($build->project)->fetch();
        $beforeBuilds = $this->dao->select('t1.id')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.status')->ne('done')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.date')->lt($project->begin)
            ->fetchPairs('id', 'id');

        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->andWhere('toStory')->eq(0)
            ->andWhere('openedDate')->ge($project->begin)
            ->andWhere('openedDate')->le($project->end)
            ->andWhere("(status = 'active' OR resolvedDate > '{$project->end}')")
            ->andWhere('openedBuild')->notin($beforeBuilds)
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->fetchAll();

        return $bugs;
    }

    /**
     * get Product Bug Pairs 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function getProductBugPairs($productID)
    {
        $bugs = array('' => '');
        $data = $this->dao->select('id, title')->from(TABLE_BUG)
            ->where('product')->eq((int)$productID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->fetchAll();
        foreach($data as $bug)
        {
            $bugs[$bug->id] = $bug->id . ':' . $bug->title;
        }
        return $bugs;
    }

    /**
     * Get bugs according to buildID and productID.
     * 
     * @param  int    $buildID 
     * @param  int    $productID 
     * @access public
     * @return object
     */
    public function getReleaseBugs($buildID, $productID, $branch = 0)
    {
        $project = $this->dao->select('t1.id,t1.begin')->from(TABLE_PROJECT)->alias('t1') 
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.id = t2.project')
            ->where('t2.id')->eq($buildID)
            ->fetch();
        $bugs = $this->dao->select('*')->from(TABLE_BUG) 
            ->where('resolvedDate')->ge($project->begin)
            ->andWhere('resolution')->ne('postponed')
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->andWhere("(project != '$project->id' OR (project = '$project->id' and openedDate < '$project->begin'))")
            ->andWhere('deleted')->eq(0)
            ->orderBy('openedDate ASC')
            ->fetchAll();
        return $bugs;
    }

    /**
     * Get bugs of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryBugs($storyID)
    {
        return $this->dao->select('id, title, pri, type, status, assignedTo, resolvedBy, resolution')
            ->from(TABLE_BUG)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get case bugs.
     * 
     * @param  int    $runID 
     * @param  int    $caseID 
     * @param  int    $version 
     * @access public
     * @return void
     */
    public function getCaseBugs($runID, $caseID = 0, $version = 0)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('1=1')
            ->beginIF($runID)->andWhere('`result`')->eq($runID)->fi()
            ->beginIF($runID == 0 and $caseID)->andWhere('`case`')->eq($caseID)->fi()
            ->beginIF($version)->andWhere('`caseVersion`')->eq($version)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get counts of some stories' bugs.
     *
     * @param  array  $stories
     * @param  int    $projectID 
     * @access public
     * @return int
     */
    public function getStoryBugCounts($stories, $projectID = 0)
    {
        $bugCounts = $this->dao->select('story, COUNT(*) AS bugs')
            ->from(TABLE_BUG)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($bugCounts[$storyID])) $bugCounts[$storyID] = 0;
        return $bugCounts;
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
        $steps    = zget($_POST, 'stepIDList', array());

        $result = $this->dao->findById($resultID)->from(TABLE_TESTRESULT)->fetch();
        if($caseID > 0)
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $result->version);
        }
        else
        {
            $run = $this->loadModel('testtask')->getRunById($result->run);
        }

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
            $i = 1;
            foreach($steps as $stepId)
            {
                if(!isset($caseSteps[$stepId])) continue;

                $step = $caseSteps[$stepId];
                $bugSteps .= $i . '. '  . $step->desc . "<br />";
                $i++;
            }

            $bugSteps .= $this->lang->bug->tplResult;
            $i = 1;
            foreach($steps as $stepId)
            {
                if(!isset($stepResults[$stepId]) or empty($stepResults[$stepId]['real'])) continue;
                $bugSteps .= $i . '. ' . $stepResults[$stepId]['real'] . "<br />";
                $i++;
            }

            $bugSteps .= $this->lang->bug->tplExpect;
            $i = 1;
            foreach($steps as $stepId)
            {
                if(!isset($caseSteps[$stepId])) continue;

                $step = $caseSteps[$stepId];
                if($step->expect) $bugSteps .= $i . '. ' . $step->expect . "<br />";
                $i++;
            }
        }
            
        return array('title' => $title, 'steps' => $bugSteps, 'storyID' => $run->case->story, 'moduleID' => $run->case->module, 'version' => $run->case->version);
    }

    /**
     * Get report data of bugs per project 
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerProject()
    {
        $datas = $this->dao->select('project as name, count(project) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('project')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $projects = $this->loadModel('project')->getPairs();
        foreach($datas as $projectID => $data) $data->name = isset($projects[$projectID]) ? $projects[$projectID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of bugs per build.
     * 
     * @access public
     * @return void
     */
    public function getDataOfBugsPerBuild()
    {
        $datas = $this->dao->select('openedBuild as name, count(openedBuild) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('openedBuild')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $builds = $this->loadModel('build')->getProductBuildPairs($this->session->product, $branch = 0, $params = '');

        /* Deal with the situation that a bug maybe associate more than one openedBuild. */
        foreach($datas as $buildIDList => $data) 
        {
            $openBuildIDList = explode(',', $buildIDList);
            if(count($openBuildIDList) > 1)
            {
                foreach($openBuildIDList as $buildID)
                {
                    if(isset($datas[$buildID])) 
                    {
                        $datas[$buildID]->value += $data->value;
                    }
                    else
                    {
                        if(!isset($datas[$buildID])) $datas[$buildID] = new stdclass();
                        $datas[$buildID]->name  = $buildID;
                        $datas[$buildID]->value = $data->value;
                    }
                }
                unset($datas[$buildIDList]);
            }
        }

        foreach($datas as $buildID => $data)
        {
            $data->name = isset($builds[$buildID]) ? $builds[$buildID] : $this->lang->report->undefined;
        }
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
        $datas = $this->dao->select('module as name, count(module) as value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));
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
        return $this->dao->select('DATE_FORMAT(openedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('openedDate')->fetchAll();
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
            ->where($this->reportCondition())->groupBy('name')
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
            ->where($this->reportCondition())->groupBy('name')
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
        $datas = $this->dao->select('openedBy AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
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
            ->from(TABLE_BUG)->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
        $datas = $this->dao->select('severity AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
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
            ->where($this->reportCondition())
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
        $datas = $this->dao->select('status AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) if(isset($this->lang->bug->statusList[$status])) $data->name = $this->lang->bug->statusList[$status];
        return $datas;
    }

    /**
     * Get report data of bugs per pri
     * 
     * @access public
     * @return array
     */
    public function getDataOfBugsPerPri()
    {
        $datas = $this->dao->select('pri AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) $data->name = $this->lang->bug->report->bugsPerPri->graph->xAxisName . ':' . $data->name;
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
        $datas = $this->dao->select('activatedCount AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
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
        $datas = $this->dao->select('type AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
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
            ->from(TABLE_BUG)->where($this->reportCondition())
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
        if(!isset($chartOption->type))    $chartOption->type    = $commonOption->type;
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
        $templates = $this->dao->select('id,account,title,content,public')
            ->from(TABLE_USERTPL)
            ->where('type')->eq('bug')
            ->andwhere('account', true)->eq($account)
            ->orWhere('public')->eq('1')
            ->markRight(1)
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
            ->add('account', $this->app->user->account)
            ->add('type', 'bug')
            ->stripTags('content', $this->config->allowedTags)
            ->get();

        $condition = "`type`='bug' and account='{$this->app->user->account}'";
        $this->dao->insert(TABLE_USERTPL)->data($template)->batchCheck('title, content', 'notempty')->check('title', 'unique', $condition)->exec();
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
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getAllBugs($productID, $branch, $modules, $projects, $orderBy, $pager = null)
    {
        $bugs = $this->dao->select('t1.*, t2.title as planTitle')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.project')->in(array_keys($projects))
            ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll(); 

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * Get bugs of assign to me. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssigntome($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of opened by me. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByOpenedbyme($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of resolved by me. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByResolvedbyme($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs of nobody to do. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssigntonull($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        
        return $this->dao->findByAssignedTo('')->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get unconfirmed bugs.
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  int    $projects 
     * @param  int    $orderBy 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getUnconfirmed($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('confirmed')->eq(0)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs the overdueBugs is active or unclosed. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getOverdueBugs($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('project')->in(array_keys($projects))
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('status')->eq('active')
            ->andWhere('deleted')->eq(0)
            ->andWhere('deadline')->ne('0000-00-00')
            ->andWhere('deadline')->lt(helper::today())
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs the status is active or unclosed. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByStatus($productID, $branch, $modules, $projects, $status, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('project')->in(array_keys($projects))
            ->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($status == 'unresolved')->andWhere('status')->eq('active')->fi()
            ->beginIF($status == 'toclosed')->andWhere('status')->eq('resolved')->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get unclosed bugs for long time. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByLonglifebugs($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        $lastEditedDate = date(DT_DATE1, time() - $this->config->bug->longlife * 24 * 3600);
        return $this->dao->findByLastEditedDate("<", $lastEditedDate)->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->andWhere('project')->in(array_keys($projects))
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('openedDate')->lt($lastEditedDate)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get postponed bugs. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByPostponedbugs($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->findByResolution('postponed')->from(TABLE_BUG)->andWhere('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get bugs need confirm. 
     * 
     * @param  int    $productID 
     * @param  int    $branch
     * @param  array  $modules
     * @param  array  $projects 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByNeedconfirm($productID, $branch, $modules, $projects, $orderBy, $pager)
    {
        return $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where("t2.status = 'active'")
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($branch)->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->andWhere('t2.version > t1.storyVersion')
            ->andWhere('t1.project')->in(array_keys($projects))
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get bugs by search. 
     * 
     * @param  int    $productID 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getBySearch($productID, $queryID, $orderBy, $pager = null, $branch = 0)
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

        $allProduct = "`product` = 'all'";
        $bugQuery   = $this->session->bugQuery;
        if(strpos($bugQuery, '`product` =') === false) $bugQuery .= ' AND `product` = ' . $productID;
        if(strpos($bugQuery, $allProduct) !== false)
        {
            $products = array_keys($this->loadModel('product')->getPrivProducts());
            $bugQuery = str_replace($allProduct, '1', $bugQuery);
            $bugQuery = $bugQuery . ' AND `product` ' . helper::dbIN($products);
        }
        $allBranch = "`branch` = 'all'";
        if($branch and strpos($bugQuery, '`branch` =') === false) $bugQuery .= " AND `branch` in('0','$branch')";
        if(strpos($bugQuery, $allBranch) !== false) $bugQuery = str_replace($allBranch, '1', $bugQuery);
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

    /**
     * Adjust the action is clickable.
     * 
     * @param  string $bug 
     * @param  string $action 
     * @access public
     * @return void
     */
    public static function isClickable($object, $action)
    {
        $action = strtolower($action);

        if($action == 'confirmbug') return $object->status == 'active' and $object->confirmed == 0;
        if($action == 'resolve')    return $object->status == 'active';
        if($action == 'close')      return $object->status == 'resolved';
        if($action == 'activate')   return $object->status != 'active';
        if($action == 'tostory')    return $object->status == 'active';

        return true;
    }

    /**
     * Get report condition from session.
     * 
     * @access public
     * @return void
     */
    public function reportCondition()
    {
        if(isset($_SESSION['bugQueryCondition']))
        {
            if(!$this->session->bugOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', $this->session->bugQueryCondition) . ')';
            return $this->session->bugQueryCondition;
        }
        return true;
    }

    /**
     * Link bug to build and release
     * 
     * @param  string|array    $bugs 
     * @param  int    $resolvedBuild 
     * @access public
     * @return bool
     */
    public function linkBugToBuild($bugs, $resolvedBuild)
    {
        if(empty($resolvedBuild) or $resolvedBuild == 'trunk') return true;
        if(is_array($bugs)) $bugs = join(',', $bugs);

        $buildBugs  = $this->dao->select('bugs')->from(TABLE_BUILD)->where('id')->eq($resolvedBuild)->fetch('bugs');
        $buildBugs .= ',' . $bugs;
        $buildBugs  = explode(',', trim($buildBugs, ','));
        $buildBugs  = array_unique($buildBugs);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq(join(',', $buildBugs))->where('id')->eq($resolvedBuild)->exec();

        $release = $this->dao->select('id,bugs')->from(TABLE_RELEASE)->where('build')->eq($resolvedBuild)->andWhere('deleted')->eq('0')->fetch();
        if($release)
        {
            $releaseBugs = $release->bugs .',' . $bugs;
            $releaseBugs = explode(',', trim($releaseBugs, ','));
            $releaseBugs = array_unique($releaseBugs);
            $this->dao->update(TABLE_RELEASE)->set('bugs')->eq(join(',', $releaseBugs))->where('id')->eq($release->id)->exec();
        }

        return true;
    }

    /**
     * Print cell data.
     * 
     * @param  object $col 
     * @param  object $bug 
     * @param  array  $users 
     * @param  array  $builds 
     * @param  array  $branches 
     * @access public
     * @return void
     */
    public function printCell($col, $bug, $users, $builds, $branches, $modulePairs)
    {
        $bugLink = inlink('view', "bugID=$bug->id");
        $account = $this->app->user->account;
        $id = $col->id;
        if($col->show)
        {
            $class = '';
            if($id == 'status') $class .= ' bug-' . $bug->status;
            if($id == 'title') $class .= ' text-left';
            if($id == 'assignedTo' && $bug->assignedTo == $account) $class .= ' red';
            if($id == 'deadline' && isset($bug->delay)) $class .= ' delayed';

            echo "<td class='" . $class . "'" . ($id=='title' ? " title='{$bug->title}'" : '') . ">";
            switch ($id)
            {
            case 'id':
                echo html::a($bugLink, sprintf('%03d', $bug->id));
                break;
            case 'severity':
                echo "<span class='severity" . zget($this->lang->bug->severityList, $bug->severity, $bug->severity) . "'>";
                echo zget($this->lang->bug->severityList, $bug->severity, $bug->severity);
                echo "</span>";
                break;
            case 'pri':
                echo "<span class='pri" . zget($this->lang->bug->priList, $bug->pri, $bug->pri) . "'>";
                echo zget($this->lang->bug->priList, $bug->pri, $bug->pri);
                echo "</span>";
                break;
            case 'title':
                $class = 'confirm' . $bug->confirmed;
                echo "<span class='$class'>[{$this->lang->bug->confirmedList[$bug->confirmed]}]</span> ";
                if($bug->branch)echo "<span class='label label-info label-badge'>{$branches[$bug->branch]}</span> ";
                if($modulePairs and $bug->module)echo "<span class='label label-info label-badge'>{$modulePairs[$bug->module]}</span> ";
                echo html::a($bugLink, $bug->title, null, "style='color: $bug->color'");
                break;
            case 'branch':
                echo $branches[$bug->branch];
                break;
            case 'type':
                echo zget($this->lang->bug->typeList, $bug->type);
                break;
            case 'status':
                echo zget($this->lang->bug->statusList, $bug->status);
                break;
            case 'activatedCount':
                echo $bug->activatedCount;
                break;
            case 'openedBy':
                echo zget($users, $bug->openedBy);
                break;
            case 'openedDate':
                echo substr($bug->openedDate, 5, 11);
                break;
            case 'openedBuild':
                foreach(explode(',', $bug->openedBuild) as $build) echo zget($builds, $build) . '<br />';
                break;
            case 'assignedTo':
                echo zget($users, $bug->assignedTo, $bug->assignedTo);
                break;
            case 'assignedDate':
                echo substr($bug->assignedDate, 5, 11);
                break;
            case 'deadline':
                echo $bug->deadline;
                break;
            case 'resolvedBy':
                echo zget($users, $bug->resolvedBy, $bug->resolvedBy);
                break;
            case 'resolution':
                echo zget($this->lang->bug->resolutionList, $bug->resolution);
                break;
            case 'resolvedDate':
                echo substr($bug->resolvedDate, 5, 11);
                break;
            case 'resolvedBuild':
                echo $bug->resolvedBuild;
                break;
            case 'closedBy':
                echo zget($users, $bug->closedBy);
                break;
            case 'lastEditedDate':
                echo substr($bug->lastEditedDate, 5, 11);
                break;
            case 'closedDate':
                echo substr($bug->closedDate, 5, 11);
                break;
            case 'actions':
                $params = "bugID=$bug->id";
                common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
                common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
                common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
                common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
                common::printIcon('bug', 'edit',       $params, $bug, 'list');
                common::printIcon('bug', 'create',     "product=$bug->product&branch=$bug->branch&extra=bugID=$bug->id", $bug, 'list', 'copy');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Send mail 
     * 
     * @param  int    $bugID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($bugID, $actionID)
    {
        $this->loadModel('mail');
        $bug         = $this->getByID($bugID);
        $productName = $this->loadModel('product')->getById($bug->product)->name;
        $users       = $this->loadModel('user')->getPairs('noletter');

        /* Get action info. */
        $action             = $this->loadModel('action')->getById($actionID);
        $history            = $this->action->getHistory($actionID);
        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':')!== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($id)
            {
                $name  = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($action->objectType, 'view', "id=$id"), "#$id " . $name);
            }
        }

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'bug');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        /* Set toList and ccList. */
        $toList = $bug->assignedTo;
        $ccList = trim($bug->mailto, ',');
        if(empty($toList))
        {
            if(empty($ccList)) return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $bug->resolvedBy;
        }

        /* Send it. */
        $this->mail->send($toList, 'BUG #'. $bug->id . ' ' . $bug->title . ' - ' . $productName, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
}
