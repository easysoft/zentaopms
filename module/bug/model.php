<?php
/**
 * The model file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
    public function setMenu($products, $productID)
    {
        $this->loadModel('product')->setMenu($products, $productID);
        $selectHtml = $this->product->select($products, $productID, 'bug', 'browse');
        foreach($this->lang->bug->menu as $key => $menu)
        {
            $replace = ($key == 'product') ? $selectHtml : $productID;
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
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product, module, severity')
            ->join('openedBuild', ',')
            ->join('mailto', ',')
            ->remove('files, labels')
            ->get();

        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $this->dao->insert(TABLE_BUG)->data($bug)->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('bug', $bugID);
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
    public function batchCreate($productID)
    {
        $this->loadModel('action');
        $now        = helper::now();
        $actions    = array();
        $data       = fixer::input('post')->get();
        $batchNum   = count(reset($data));

        $result = $this->loadModel('common')->removeDuplicate('bug', $data, "product=$productID");
        $data   = $result['data'];

        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($data->title[$i]) and empty($data->openedBuilds[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->bug->openedBuild)));
        }

        /* Get pairs(moduleID => moduleOwner) for bug. */
        $stmt         = $this->dbh->query($this->loadModel('tree')->buildMenuQuery($productID, 'bug', $startModuleID = 0));
        $moduleOwners = array();
        while($module = $stmt->fetch()) $moduleOwners[$module->id] = $module->owner;

        $module  = 0;
        $project = 0;
        $type    = '';
        $os      = '';
        $browser = '';
        for($i = 0; $i < $batchNum; $i++)
        {
            if($data->modules[$i]  != 'ditto') $module  = (int)$data->modules[$i];
            if($data->projects[$i] != 'ditto') $project = (int)$data->projects[$i];
            if($data->types[$i]    != 'ditto') $type    = $data->types[$i];
            if($data->oses[$i]     != 'ditto') $os      = $data->oses[$i];
            if($data->browsers[$i] != 'ditto') $browser = $data->browsers[$i];

            $data->modules[$i]  = (int)$module;
            $data->projects[$i] = (int)$project;
            $data->types[$i]    = $type;
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
            $bug->module      = $data->modules[$i];
            $bug->project     = $data->projects[$i];
            $bug->openedBuild = implode(',', $data->openedBuilds[$i]);
            $bug->title       = $data->title[$i];
            $bug->steps       = nl2br($data->stepses[$i]);
            $bug->type        = $data->types[$i];
            $bug->severity    = $data->severities[$i];
            $bug->os          = $data->oses[$i];
            $bug->browser     = $data->browsers[$i];

            if(!empty($data->uploadImage[$i]))
            {
                $fileName  = htmlspecialchars_decode($data->uploadImage[$i]);
                $realPath  = $this->session->bugImagesFile . $fileName;

                $file = array();
                $file['extension'] = $this->file->getExtension($fileName);
                $file['pathname']  = $this->file->setPathName($i, $file['extension']);
                $file['title']     = str_replace(".{$file['extension']}", '', $fileName);
                $file['size']      = filesize($realPath);
                if(rename($realPath, $this->file->savePath . $file['pathname']))
                {
                    if(in_array($file['extension'], $this->config->file->imageExtensions))
                    {
                        $file['addedBy']    = $this->app->user->account;
                        $file['addedDate']  = $now;
                        $this->dao->insert(TABLE_FILE)->data($file)->exec();

                        $url = $this->file->webPath . $file['pathname'];
                        $bug->steps .= '<img src="' . $url . '" alt="" />';
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
            if(is_dir($this->session->bugImagesFile)) $classFile->removeDir($this->session->bugImagesFile);
            unset($_SESSION['bugImagesFile']);
        }

        return $actions;
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

        if($setImgSize) $bug->steps = $this->loadModel('file')->setImgSize($bug->steps);
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
    public function getActiveBugs($products, $projectID, $pager = null)
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
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where("((status = 'resolved' AND resolution = 'postponed') OR (status = 'active'))")
            ->andWhere('toTask')->eq(0)
            ->andWhere('toStory')->eq(0)
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
            ->stripTags($this->config->bug->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('project,module,project,story,task,duplicateBug', 0)
            ->setDefault('openedBuild', '')
            ->setDefault('plan', 0)
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
            ->remove('comment,files,labels')
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

        /* Adjust whether the post data is complete, if not, remove the last element of $bugIDList. */
        if($this->session->showSuhosinInfo) array_pop($bugIDList);

        if(!empty($bugIDList))
        {
            /* Initialize bugs from the post data.*/
            foreach($bugIDList as $bugID)
            {
                $oldBug = $this->getByID($bugID);

                $bug = new stdclass();
                $bug->lastEditedBy   = $this->app->user->account;
                $bug->lastEditedDate = $now;
                $bug->type           = $data->types[$bugID];
                $bug->severity       = $data->severities[$bugID];
                $bug->pri            = $data->pris[$bugID];
                $bug->status         = $data->statuses[$bugID];
                $bug->title          = $data->titles[$bugID];
                $bug->assignedTo     = $data->assignedTos[$bugID];
                $bug->resolvedBy     = $data->resolvedBys[$bugID];
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
                $oldBug = $this->getByID($bugID);

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
        $now = helper::now();

        $bug = fixer::input('post')
            ->setDefault('confirmed', 1)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->join('mailto', ',')
            ->get();

        $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
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
            ->add('resolvedDate',   $now)
            ->add('status',         'resolved')
            ->add('confirmed',      1)
            ->add('assignedDate',   $now)
            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('duplicateBug', 0)
            ->setDefault('assignedTo', $oldBug->openedBy)
            ->remove('comment,files,labels')
            ->get();

        if($bug->resolvedBuild != 'trunk')
        {
            $bug->testtask = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($bug->resolvedBuild)->orderBy('id_desc')->limit(1)->fetch('id');
        }

        $this->dao->update(TABLE_BUG)->data($bug)
            ->autoCheck()
            ->batchCheck($this->config->bug->resolve->requiredFields, 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->where('id')->eq((int)$bugID)
            ->exec();
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
        foreach($bugIDList as $bugID)
        {
            $oldBug = $bugs[$bugID];
            if($oldBug->status != 'active') continue;
            $bug = new stdClass();
            $bug->resolution     = $resolution;
            $bug->resolvedBuild  = $resolution == 'fixed' ? $resolvedBuild : '';
            $bug->resolvedBy     = $this->app->user->account;
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedTo     = $oldBug->openedBy;
            $bug->assignedDate   = $now;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;

            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
        }
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
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF($type != 'all')->andWhere("$type")->eq($account)->fi()
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
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getProjectBugs($projectID, $orderBy = 'id_desc', $pager = null, $build = 0)
    {
        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('project')->eq((int)$projectID)
            ->beginIF($build != 0)->andWhere('openedBuild')->eq($build)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

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
    public function getReleaseBugs($buildID, $productID)
    {
        $project = $this->dao->select('t1.begin')
            ->from(TABLE_PROJECT)->alias('t1') 
            ->leftJoin(TABLE_BUILD)->alias('t2')
            ->on('t1.id = t2.project')
            ->where('t2.id')->eq($buildID)
            ->fetch();
        $bugs = $this->dao->select('*')->from(TABLE_BUG) 
            ->where('resolvedDate')->ge($project->begin)
            ->andWhere('resolution')->ne('postponed')
            ->andWhere('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('openedDate ASC')
            ->fetchAll();
        return $bugs;
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
                $i = 0;
                foreach($caseSteps as $key => $step)
                {
                    if(!in_array($step->id, $steps)) continue;
                    $i++;
                    $bugSteps .= $i . '. '  . $step->desc . "<br />";
                }

                $bugSteps .= $this->lang->bug->tplResult;
                $i = 0;
                foreach($caseSteps as $key => $step)
                {
                    if(!in_array($step->id, $steps)) continue;
                    $i++;
                    if(empty($stepResults[$step->id]['real'])) continue;
                    $bugSteps .= $i . '. ' . $stepResults[$step->id]['real'] . "<br />";
                }

                $bugSteps .= $this->lang->bug->tplExpect;
                $i = 0;
                foreach($caseSteps as $key => $step)
                {
                    if(!in_array($step->id, $steps)) continue;
                    $i++;
                    if(!$step->expect) continue;
                    $bugSteps .= $i . '. ' . $step->expect . "<br />";
                }

            }
            else
            {
                $bugSteps .= $this->lang->bug->tplResult;
                $bugSteps .= $this->lang->bug->tplExpect;
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
        $builds = $this->loadModel('build')->getProductBuildPairs($this->session->product);

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
                        $datas[$buildID]->value++;
                    }
                    else
                    {
                        $datas[$buildID]->name  = $buildID;
                        $datas[$buildID]->value = 1;
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
            ->add('account', $this->app->user->account)
            ->add('type', 'bug')
            ->stripTags('content', $this->config->allowedTags)
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
    public function getAllBugs($productID, $projects, $orderBy, $pager = null)
    {
        $bugs = $this->dao->select('t1.*, t2.title as planTitle')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.project')->in(array_keys($projects))
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll(); 

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
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
     * Get unconfirmed bugs.
     * 
     * @param  int    $productID 
     * @param  int    $projects 
     * @param  int    $orderBy 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getUnconfirmed($productID, $projects, $orderBy, $pager)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('confirmed')->eq(0)
            ->andWhere('product')->eq($productID)
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
            ->andWhere('deleted')->eq(0)
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
            ->andWhere('t1.product')->eq($productID)
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
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getBySearch($productID, $projects, $queryID, $orderBy, $pager = null)
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
            $var = $this->session->bugQuery . ' AND `project`' . helper::dbIN(array_keys($projects));
            $this->session->set('bugQuery', "$var");
        }
        if(strpos($this->session->bugQuery, '`product`') === false) 
        {
            $var = $this->session->bugQuery . ' AND `product` = ' . $productID;
            $this->session->set('bugQuery', "$var");
        }

        $allProduct = "`product` = 'all'";
        $bugQuery   = $this->session->bugQuery;
        if(strpos($this->session->bugQuery, $allProduct) !== false)
        {
            $products = array_keys($this->loadModel('product')->getPrivProducts());
            $bugQuery = str_replace($allProduct, '1', $this->session->bugQuery);
            $bugQuery = $bugQuery . ' AND `product`' . helper::dbIN($products);
        }
        $bugQuery = $this->loadModel('search')->replaceDynamic($bugQuery);
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
        if(!$this->session->bugOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', $this->session->bugQueryCondition) . ')';
        return $this->session->bugQueryCondition;
    }
}
