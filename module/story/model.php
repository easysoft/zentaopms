<?php
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class storyModel extends model
{
    /**
     * Get a story by id.
     * 
     * @param  int    $storyID 
     * @param  int    $version 
     * @access public
     * @return object|bool
     */
    public function getById($storyID, $version = 0)
    {
        $story = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
        if(!$story) return false;
        if(substr($story->closedDate, 0, 4) == '0000') $story->closedDate = '';
        if($version == 0) $version = $story->version;
        $spec = $this->dao->select('title,spec,verify')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title  = isset($spec->title)  ? $spec->title  : '';
        $story->spec   = isset($spec->spec)   ? $spec->spec   : '';
        $story->verify = isset($spec->verify) ? $spec->verify : '';
        $story->projects = $this->dao->select('t1.project, t2.name, t2.status')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->orderBy('t1.project DESC')
            ->fetchAll('project');
        $story->tasks     = $this->dao->select('id, name, assignedTo, project, status, consumed, `left`')->from(TABLE_TASK)->where('story')->eq($storyID)->orderBy('id DESC')->fetchGroup('project');
        //$story->bugCount  = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_BUG)->where('story')->eq($storyID)->fetch('count');
        //$story->caseCount = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_CASE)->where('story')->eq($storyID)->fetch('count');
        if($story->toBug) $story->toBugTitle = $this->dao->findById($story->toBug)->from(TABLE_BUG)->fetch('title');
        if($story->plan)  $story->planTitle  = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $extraStories = array();
        if($story->duplicateStory) $extraStories = array($story->duplicateStory);
        if($story->linkStories)    $extraStories = explode(',', $story->linkStories);
        if($story->childStories)   $extraStories = array_merge($extraStories, explode(',', $story->childStories));
        $extraStories = array_unique($extraStories);
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchPairs();
        return $story;
    }

    /**
     * Get affected things. 
     * 
     * @param  object  $story 
     * @access public
     * @return object
     */
    public function getAffectedScope($story)
    {
        /* Remove closed projects. */
        if($story->projects)
        {
            foreach($story->projects as $projectID => $project)
            {
                if($project->status != 'doing') unset($story->projects[$projectID]);
            }
        }

        /* Get team members. */
        if($story->projects)
        {
            $story->teams = $this->dao->select('account, project')
                ->from(TABLE_TEAM)
                ->where('project')->in(array_keys($story->projects))
                ->fetchGroup('project');
        }

        /* Get affected bugs. */
        $story->bugs = $this->dao->findByStory($story->id)->from(TABLE_BUG)
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();

        /* Get affected cases. */
        $story->cases = $this->dao->findByStory($story->id)->from(TABLE_CASE)->andWhere('deleted')->eq(0)->fetchAll();

        return $story;
    }

    /**
     * Create a story.
     * 
     * @access public
     * @return int|bool the id of the created story or false when error.
     */
    public function create()
    {
        $now   = helper::now();
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->cleanFloat('estimate')
            ->stripTags('title')
            ->callFunc('title', 'trim')
            ->setDefault('plan', 0)
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('assignedDate', 0)
            ->add('version', 1)
            ->add('status', 'draft')
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->needNotReview, 'status', 'active')
            ->remove('files,labels,spec,verify,needNotReview')
            ->get();
        $this->dao->insert(TABLE_STORY)->data($story)->autoCheck()->batchCheck($this->config->story->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $storyID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('story', $storyID, $extra = 1);

            $data->story   = $storyID;
            $data->version = 1;
            $data->title   = $story->title;
            $data->spec    = $this->post->spec;
            $data->verify  = $this->post->verify;
            $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();
            return $storyID;
        }
        return false;
    }

    /**
     * Change a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return array  the change of the story.
     */
    public function change($storyID)
    {
        $now         = helper::now();
        $oldStory    = $this->getById($storyID);
        $specChanged = false;
        if($this->post->spec != $oldStory->spec or $this->post->title != $oldStory->title or $this->loadModel('file')->getCount()) $specChanged = true;

        $story = fixer::input('post')
            ->stripTags('title')
            ->callFunc('title', 'trim')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setIF($this->post->assignedTo != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($specChanged, 'version', $oldStory->version + 1)
            ->setIF($specChanged and $oldStory->status == 'active' and $this->post->needNotReview == false, 'status',  'changed')
            ->setIF($specChanged and $oldStory->status == 'draft'  and $this->post->needNotReview, 'status', 'active')
            ->setIF($specChanged, 'reviewedBy',  '')
            ->setIF($specChanged, 'closedBy', '')
            ->setIF($specChanged, 'closedReason', '')
            ->setIF($specChanged and $oldStory->reviewedBy, 'reviewedDate',  '0000-00-00')
            ->setIF($specChanged and $oldStory->closedBy,   'closedDate',   '0000-00-00')
            ->remove('files,labels,spec,verify,comment,needNotReview')
            ->get();
        $this->dao->update(TABLE_STORY)
            ->data($story)
            ->autoCheck()
            ->batchCheck($this->config->story->change->requiredFields, 'notempty')
            ->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError())
        {
            if($specChanged)
            {
                $data->story   = $storyID;
                $data->version = $oldStory->version + 1;
                $data->title   = $story->title;
                $data->spec    = $this->post->spec;
                $data->verify  = $this->post->verify;
                $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();
                $story->spec   = $this->post->spec;
                $story->verify = $this->post->verify;
            }
            else
            {
                unset($oldStory->spec);
            }
            return common::createChanges($oldStory, $story);
        }
    }

    /**
     * Update a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return array the changes of the story.
     */
    public function update($storyID)
    {
        $now      = helper::now();
        $oldStory = $this->getById($storyID);

        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->stripTags('title')
            ->add('assignedDate', $oldStory->assignedDate)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('plan', 0)
            ->setDefault('status', $oldStory->status)
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF($oldStory->status == 'draft', 'stage', '')
            ->remove('files,labels,comment')
            ->get();

        $this->dao->update(TABLE_STORY)
            ->data($story)
            ->autoCheck()
            ->batchCheck($this->config->story->edit->requiredFields, 'notempty')
            ->checkIF($story->closedBy, 'closedReason', 'notempty')
            ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->closedReason == 'subdivided', 'childStories', 'notempty')
            ->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError()) return common::createChanges($oldStory, $story);
    }

    /**
     * Review a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return bool
     */
    public function review($storyID)
    {
        if($this->post->result == false)   die(js::alert($this->lang->story->mustChooseResult));
        if($this->post->result == 'revert' and $this->post->preVersion == false) die(js::alert($this->lang->story->mustChoosePreVersion));

        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $date     = helper::today();
        $story = fixer::input('post')
            ->remove('result,preVersion,comment')
            ->setDefault('reviewedDate', $date)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'draft',   'status', 'active')
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'changed', 'status', 'active')
            ->setIF($this->post->result == 'reject', 'closedBy',   $this->app->user->account)
            ->setIF($this->post->result == 'reject', 'closedDate', $now)
            ->setIF($this->post->result == 'reject', 'assignedTo', 'closed')
            ->setIF($this->post->result == 'reject', 'status', 'closed')
            ->setIF($this->post->result == 'revert', 'version', $this->post->preVersion)
            ->setIF($this->post->result == 'revert', 'status',  'active')
            ->setIF($this->post->closedReason == 'done', 'stage', 'released')
            ->removeIF($this->post->result != 'reject', 'closedReason, duplicateStory, childStories')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'subdivided', 'childStories')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)
            ->autoCheck()
            ->batchCheck($this->config->story->review->requiredFields, 'notempty')
            ->checkIF($this->post->result == 'reject', 'closedReason', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'subdivided', 'childStories',   'notempty')
            ->where('id')->eq($storyID)->exec();
        if($this->post->result == 'revert')
        {
            $preTitle = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($this->post->preVersion)->fetch('title');
            $this->dao->update(TABLE_STORY)->set('title')->eq($preTitle)->where('id')->eq($storyID)->exec();
            $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($oldStory->version)->exec();
            $this->dao->delete()->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->andWhere('extra')->eq($oldStory->version)->exec();
        }
        $this->setStage($storyID);
        return true;
    }

    /**
     * Close a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return bool
     */
    public function close($storyID)
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $story = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('closedDate', $now)
            ->add('closedBy',   $this->app->user->account)
            ->add('assignedTo',   'closed')
            ->add('assignedDate', $now)
            ->add('status', 'closed') 
            ->removeIF($this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->closedReason != 'subdivided', 'childStories')
            ->setIF($this->post->closedReason == 'done', 'stage', 'released')
            ->setIF($this->post->closedReason != 'done', 'plan', 0)
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)
            ->autoCheck()
            ->batchCheck($this->config->story->close->requiredFields, 'notempty')
            ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->closedReason == 'subdivided', 'childStories',   'notempty')
            ->where('id')->eq($storyID)->exec();
        return true;
    }

    /**
     * Activate a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return bool
     */
    public function activate($storyID)
    {
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $story = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('assignedDate', $now)
            ->add('status', 'active') 
            ->add('closedBy', '')
            ->add('closedReason', '')
            ->add('closedDate', '0000-00-00')
            ->add('reviewedBy', '')
            ->add('reviewedDate', '0000-00-00')
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
        return true;
    }

    /**
     * Set stage of a story.
     * 
     * @param  int    $storyID 
     * @param  string $customStage 
     * @access public
     * @return bool
     */
    public function setStage($storyID, $customStage = '')
    {
        /* Custom stage defined, use it. */
        if($customStage)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq($customStage)->where('id')->eq((int)$storyID)->exec();
            return true;
        }

        /* Get projects which status is doing. */
        $projects = $this->dao->select('project')
            ->from(TABLE_PROJECTSTORY)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq((int)$storyID)
            ->andWhere('t2.status')->ne('done')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs();

        /* If no projects, in plan, stage is planned. No plan, wait. */
        if(!$projects)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('planned')->where('id')->eq((int)$storyID)->andWhere('plan')->gt(0)->exec();
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq((int)$storyID)->andWhere('plan')->eq(0)->andWhere('status')->eq('active')->exec();
            return true;
        }

        /* Search related tasks. */
        $tasks = $this->dao->select('type,status')->from(TABLE_TASK)
            ->where('project')->in($projects)
            ->andWhere('story')->eq($storyID)
            ->andWhere('status')->ne('cancel')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('type');

        /* No tasks, then the stage is projected. */
        if(!$tasks)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('projected')->where('id')->eq((int)$storyID)->exec();
            return true;
        }

        /* If have test task, the stage is tested or testing. */
        if(isset($tasks['test']))
        {
            $stage = 'tested';
            foreach($tasks['test'] as $task)
            {
                if($task->status != 'done')
                {
                    $stage = 'testing';
                    break;
                }
            }
        }
        /* No test taske, stage is done or developing. */
        else
        {
            $stage = 'developed';
            foreach($tasks as $type => $typeTasks)
            {
                foreach($typeTasks as $task)
                {
                    if($task->status != 'done')
                    {
                        $stage = 'developing';
                        break;
                    }
                }
            }
        }
        $this->dao->update(TABLE_STORY)->set('stage')->eq($stage)->where('id')->eq((int)$storyID)->exec();
        return;
    }

    /**
     * Get stories list of a product.
     * 
     * @param  int           $productID 
     * @param  array|string  $moduleIds 
     * @param  string        $status 
     * @param  string        $orderBy 
     * @param  object        $pager 
     * @access public
     * @return array
     */
    public function getProductStories($productID = 0, $moduleIds = 0, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $stories = $this->dao->select('t1.*, t2.title as planTitle')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->where('t1.product')->in($productID)
            ->beginIF(!empty($moduleIds))->andWhere('module')->in($moduleIds)->fi() 
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        
        /* Set session for report query. */
        $this->session->set('storyReport', $this->dao->get());
        
        return $stories;
    }

    /**
     * Get stories pairs of a product.
     * 
     * @param  int           $productID 
     * @param  array|string  $moduleIds 
     * @param  string        $status 
     * @param  string        $order 
     * @access public
     * @return array
     */
    public function getProductStoryPairs($productID = 0, $moduleIds = 0, $status = 'all', $order = 'id_desc')
    {
        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productID)->andWhere('t1.product')->in($productID)->fi()
            ->beginIF($moduleIds)->andWhere('t1.module')->in($moduleIds)->fi()
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($order)
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories);
    }

    /**
     * Get stories by a query id.
     * 
     * @param  int    $productID 
     * @param  string $query 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByQuery($productID, $query, $orderBy, $pager = null)
    {
        $tmpStories = $this->dao->select('*')->from(TABLE_STORY)->where($query)
            ->beginIF($productID != 'all')->andWhere('product')->eq((int)$productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchGroup('plan');

        /* Set session for report query. */
        $this->session->set('storyReport', $this->dao->get());

        if(!$tmpStories) return array();
        $plans   = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in(array_keys($tmpStories))->fetchPairs();
        $stories = array();
        foreach($tmpStories as $planID => $planStories)
        {
            foreach($planStories as $story)
            {
                $story->planTitle = isset($plans[$planID]) ? $plans[$planID] : '';
                $stories[] = $story;
            }
        }
        return $stories;
    }

    /**
     * Get stories list of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProjectStories($projectID = 0, $orderBy = 'pri_asc,id_desc')
    {
        return $this->dao->select('t1.*, t2.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * Get stories pairs of a project.
     * 
     * @param  int    $projectID 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getProjectStoryPairs($projectID = 0, $productID = 0)
    {
        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')
            ->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories);
    }

    /**
     * Get stories list of a plan.
     * 
     * @param  int    $planID 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getPlanStories($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_STORY)
            ->where('plan')->eq((int)$planID)
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
    }

    /**
     * Get stories pairs of a plan.
     * 
     * @param  int    $planID 
     * @param  string $status 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getPlanStoryPairs($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_STORY)
            ->where('plan')->eq($planID)
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * Get stories of a user.
     * 
     * @param  string $account 
     * @param  string $type         the query type 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getUserStories($account, $type = 'assignedto', $orderBy = 'id_desc', $pager = null)
    {
        $type = strtolower($type);
        return $this->dao->select('t1.*, t2.title as planTitle, t3.name as productTitle')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t2')->on('t1.plan = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type == 'assignedto')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'openedby')->andWhere('openedby')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'reviewedby')->andWhere('reviewedby')->like('%' . $this->app->user->account . '%')->fi()
            ->beginIF($type == 'closedby')->andWhere('closedby')->eq($this->app->user->account)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get doing projects' members of a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return array
     */
    public function getProjectMembers($storyID)
    {
        $projects = $this->dao->select('project')
            ->from(TABLE_PROJECTSTORY)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq((int)$storyID)
            ->andWhere('t2.status')->eq('doing')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs();
        if($projects) return($this->dao->select('account')->from(TABLE_TEAM)->where('project')->in($projects)->fetchPairs('account'));
    }

    /**
     * Get version of a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return int
     */
    public function getVersion($storyID)
    {
        return $this->dao->select('version')->from(TABLE_STORY)->where('id')->eq((int)$storyID)->fetch('version');
    }

    /**
     * Get versions of some stories.
     * 
     * @param  array|string story id list
     * @access public
     * @return array
     */
    public function getVersions($storyID)
    {
        return $this->dao->select('id, version')->from(TABLE_STORY)->where('id')->in($storyID)->fetchPairs();
    }

    /**
     * Format stories 
     * 
     * @param  array    $stories 
     * @access private
     * @return void
     */
    private function formatStories($stories)
    {
        /* Get module names of stories. */
        /*$modules = array();
        foreach($stories as $story) $modules[] = $story->module;
        $moduleNames = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($modules)->fetchPairs();*/

        /* Format these stories. */
        $storyPairs = array('' => '');
        foreach($stories as $story) $storyPairs[$story->id] = $story->id . ':' . $story->title . "({$this->lang->story->pri}:$story->pri, {$this->lang->story->estimate}: $story->estimate)";
        return $storyPairs;
    }

    /**
     * Extract accounts from some stories.
     * 
     * @param  array  $stories 
     * @access public
     * @return array
     */
    public function extractAccountsFromList($stories)
    {
        $accounts = array();
        foreach($stories as $story)
        {
            if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
            if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
            if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
            if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /**
     * Extract accounts from a story.
     * 
     * @param  object  $story 
     * @access public
     * @return array
     */
    public function extractAccountsFromSingle($story)
    {
        $accounts = array();
        if(!empty($story->openedBy))     $accounts[] = $story->openedBy;
        if(!empty($story->assignedTo))   $accounts[] = $story->assignedTo;
        if(!empty($story->closedBy))     $accounts[] = $story->closedBy;
        if(!empty($story->lastEditedBy)) $accounts[] = $story->lastEditedBy;
        return array_unique($accounts);
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
        $chartOption  = $this->lang->story->report->$chartType;
        $commonOption = $this->lang->story->report->options;

        $chartOption->graph->caption = $this->lang->story->report->charts[$chartType];
        if(!isset($chartOption->swf))    $chartOption->swf    = $commonOption->swf;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
    }

    /**
     * Get report data of storys per product 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerProduct()
    {
        $datas = $this->dao->select('product as name, count(product) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('product')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $products = $this->loadModel('product')->getPairs();
        foreach($datas as $productID => $data) $data->name = isset($products[$productID]) ? $products[$productID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per module 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerModule()
    {
        $datas = $this->dao->select('module as name, count(module) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(array_keys($datas))->fetchPairs();
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        return $datas;
    }

    /**
     * Get report data of storys per plan 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerPlan()
    {
        $datas = $this->dao->select('plan as name, count(plan) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('plan')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $plans = $this->dao->select('id, title')->from(TABLE_PRODUCTPLAN)->where('id')->in(array_keys($datas))->fetchPairs();
        foreach($datas as $planID => $data) $data->name = isset($plans[$planID]) ? $plans[$planID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per status 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerStatus()
    {
        $datas = $this->dao->select('status as name, count(status) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('status')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $status => $data) if(isset($this->lang->story->statusList[$status])) $data->name = $this->lang->story->statusList[$status];
        return $datas;
    }

    /**
     * Get report data of storys per stage 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerStage()
    {
        $datas = $this->dao->select('stage as name, count(stage) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('stage')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $stage => $data) $data->name = $this->lang->story->stageList[$stage] != '' ? $this->lang->story->stageList[$stage] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per pri 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerPri()
    {
        $datas = $this->dao->select('pri as name, count(pri) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('pri')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $pri => $data)  $data->name = $this->lang->story->priList[$pri] != '' ? $this->lang->story->priList[$pri] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per estimate 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerEstimate()
    {
        return $this->dao->select('estimate as name, count(estimate) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('estimate')->orderBy('value')->fetchAll();
    }

    /**
     * Get report data of storys per openedBy 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerOpenedBy()
    {
        $datas = $this->dao->select('openedBy as name, count(openedBy) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('openedBy')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = isset($this->users[$account]) ? $this->users[$account] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per assignedTo 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerAssignedTo()
    {
        $datas = $this->dao->select('assignedTo as name, count(assignedTo) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('assignedTo')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = isset($this->users[$account]) and $this->users[$account] != '' ? $this->users[$account] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per closedReason 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerClosedReason()
    {
        $datas = $this->dao->select('closedReason as name, count(closedReason) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('closedReason')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $reason => $data) $data->name = $this->lang->story->reasonList[$reason] != '' ? $this->lang->story->reasonList[$reason] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of storys per change 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerChange()
    {
        return $this->dao->select('(version-1) as name, count(*) as value')->from(TABLE_STORY)
            ->beginIF($this->session->storyReport !=  false)->where($this->session->storyReport)->fi()
            ->groupBy('version')->orderBy('value')->fetchAll();
    }
}
