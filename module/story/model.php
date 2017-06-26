 <?php
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
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
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getById($storyID, $version = 0, $setImgSize = false)
    {
        $story = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
        if(!$story) return false;
        if(substr($story->closedDate, 0, 4) == '0000') $story->closedDate = '';
        if($version == 0) $version = $story->version;
        $spec = $this->dao->select('title,spec,verify')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title  = isset($spec->title)  ? $spec->title  : '';
        $story->spec   = isset($spec->spec)   ? $spec->spec   : '';
        $story->verify = isset($spec->verify) ? $spec->verify : '';

        $story = $this->loadModel('file')->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $story->projects = $this->dao->select('t1.project, t2.name, t2.status')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->orderBy('t1.project DESC')
            ->fetchAll('project');
        $story->tasks  = $this->dao->select('id, name, assignedTo, project, status, consumed, `left`')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->orderBy('id DESC')->fetchGroup('project');
        $story->stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchPairs('branch', 'stage');
        //$story->bugCount  = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_BUG)->where('story')->eq($storyID)->fetch('count');
        //$story->caseCount = $this->dao->select('COUNT(*)')->alias('count')->from(TABLE_CASE)->where('story')->eq($storyID)->fetch('count');
        if($story->toBug) $story->toBugTitle = $this->dao->findById($story->toBug)->from(TABLE_BUG)->fetch('title');
        if($story->plan)
        {
            $plans  = $this->dao->select('id,title,branch')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchAll('id');
            foreach($plans as $planID => $plan)
            {
                $story->planTitle[$planID] = $plan->title;
                if($plan->branch and !isset($story->stages[$plan->branch])) $story->stages[$plan->branch] = 'planned';
            }
        }
        $extraStories = array();
        if($story->duplicateStory) $extraStories = array($story->duplicateStory);
        if($story->linkStories)    $extraStories = explode(',', $story->linkStories);
        if($story->childStories)   $extraStories = array_merge($extraStories, explode(',', $story->childStories));
        $extraStories = array_unique($extraStories);
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchPairs();
        return $story;
    }

    /**
     * Get stories by idList.
     * 
     * @param  int|array|string    $storyIDList 
     * @access public
     * @return array
     */
    public function getByList($storyIDList = 0)
    {
        return $this->dao->select('t1.*, t2.spec, t2.verify')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.version=t2.version')
            ->beginIF($storyIDList)->andWhere('t1.id')->in($storyIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get story specs.
     * 
     * @param  array  $storyIdList 
     * @access public
     * @return array
     */
    public function getStorySpecs($storyIdList)
    {
        return $this->dao->select('story,spec,verify')->from(TABLE_STORYSPEC)
            ->where('story')->in($storyIdList)
            ->orderBy('version')
            ->fetchAll('story');
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
            foreach($story->projects as $projectID => $project) if($project->status == 'done') unset($story->projects[$projectID]);
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
    public function create($projectID = 0, $bugID = 0)
    {
        $now   = helper::now();
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->cleanFloat('estimate')
            ->callFunc('title', 'trim')
            ->setDefault('plan,verify', '')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('assignedDate', 0)
            ->add('version', 1)
            ->add('status', 'draft')
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->needNotReview or $projectID > 0, 'status', 'active')
            ->setIF($this->post->plan > 0, 'stage', 'planned')
            ->setIF($projectID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->join('mailto', ',')
            ->stripTags($this->config->story->editor->create['id'], $this->config->allowedTags)
            ->remove('files,labels,needNotReview,newStory,uid')
            ->get();

        /* Check repeat story. */
        $result = $this->loadModel('common')->removeDuplicate('story', $story, "product={$story->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        if($this->checkForceReview()) $story->status = 'draft';
        if($story->status == 'draft') $story->stage   = $this->post->plan > 0 ? 'planned' : 'wait';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify')->autoCheck()->batchCheck($this->config->story->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $storyID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $storyID, 'story');
            $this->file->saveUpload('story', $storyID, $extra = 1);

            $data          = new stdclass();
            $data->story   = $storyID;
            $data->version = 1;
            $data->title   = $story->title;
            $data->spec    = $story->spec;
            $data->verify  = $story->verify;
            $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

            if($projectID != 0 and $story->status != 'draft') 
            {
                $this->dao->insert(TABLE_PROJECTSTORY)
                    ->set('project')->eq($projectID)
                    ->set('product')->eq($this->post->product)
                    ->set('story')->eq($storyID)
                    ->set('version')->eq(1)
                    ->exec();
            }
            
            if($bugID > 0)
            {
                $bug = new stdclass();
                $bug->toStory      = $storyID;
                $bug->status       = 'closed';
                $bug->resolution   = 'tostory';
                $bug->resolvedBy   = $this->app->user->account;
                $bug->resolvedDate = $now;
                $bug->closedBy     = $this->app->user->account;
                $bug->closedDate   = $now;
                $bug->assignedTo   = 'closed';
                $bug->assignedDate = $now;
                $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bugID)->exec();
    
                $this->loadModel('action')->create('bug', $bugID, 'ToStory', '', $storyID);
                $this->action->create('bug', $bugID, 'Closed');

                /* add files to story from bug. */
                $files = $this->dao->select('*')->from(TABLE_FILE)
                    ->where('objectType')->eq('bug')
                    ->andWhere('objectID')->eq($bugID)
                    ->fetchAll();
                if(!empty($files)) 
                { 
                    foreach($files as $file)
                    {
                        $file->objectType = 'story';
                        $file->objectID = $storyID; 
                        unset($file->id); 
                        $this->dao->insert(TABLE_FILE)->data($file)->exec();
                    }
                }
            }
            $this->setStage($storyID);
            return array('status' => 'created', 'id' => $storyID);
        }
        return false;
    }

    /**
     * Create a batch stories.
     * 
     * @access public
     * @return int|bool the id of the created story or false when error.
     */
    public function batchCreate($productID = 0, $branch = 0)
    {
        $this->loadModel('action');
        $branch   = (int)$branch;
        $now      = helper::now();
        $mails    = array();
        $stories  = fixer::input('post')->get();
        $batchNum = count(reset($stories));

        $result  = $this->loadModel('common')->removeDuplicate('story', $stories, "product={$productID}");
        $stories = $result['data'];

        $module = 0;
        $plan   = 0;
        $pri    = 0;
        $source = '';
        for($i = 0; $i < $batchNum; $i++)
        {
            $module = $stories->module[$i] == 'ditto' ? $module : $stories->module[$i];
            $plan   = $stories->plan[$i]   == 'ditto' ? $plan   : $stories->plan[$i];
            $pri    = $stories->pri[$i]    == 'ditto' ? $pri    : $stories->pri[$i];
            $source = $stories->source[$i] == 'ditto' ? $source : $stories->source[$i];
            $stories->module[$i] = (int)$module;
            $stories->plan[$i]   = $plan;
            $stories->pri[$i]    = (int)$pri;
            $stories->source[$i] = $source;
        }

        if(isset($stories->uploadImage)) $this->loadModel('file');

        $forceReview = $this->checkForceReview();
        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($stories->title[$i]))
            {
                $data = new stdclass();
                $data->branch     = $stories->branch[$i];
                $data->module     = $stories->module[$i];
                $data->plan       = $stories->plan[$i];
                $data->color      = $stories->color[$i];
                $data->title      = $stories->title[$i];
                $data->source     = $stories->source[$i];
                $data->pri        = $stories->pri[$i];
                $data->estimate   = $stories->estimate[$i];
                $data->status     = ($stories->needReview[$i] == 0 and !$forceReview) ? 'active' : 'draft';
                $data->keywords   = $stories->keywords[$i];
                $data->product    = $productID;
                $data->openedBy   = $this->app->user->account;
                $data->openedDate = $now;
                $data->version    = 1;

                $this->dao->insert(TABLE_STORY)->data($data)->autoCheck()
                    ->batchCheck($this->config->story->create->requiredFields, 'notempty')
                    ->exec();
                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                $storyID = $this->dao->lastInsertID();
                $this->setStage($storyID);

                $specData = new stdclass();
                $specData->story   = $storyID;
                $specData->version = 1;
                $specData->title   = $stories->title[$i];
                $specData->spec    = '';
                $specData->verify  = '';
                if(!empty($stories->spec[$i]))  $specData->spec   = nl2br($stories->spec[$i]);
                if(!empty($stories->verify[$i]))$specData->verify = nl2br($stories->verify[$i]);

                if(!empty($stories->uploadImage[$i]))
                {
                    $fileName = $stories->uploadImage[$i];
                    $file     = $this->session->storyImagesFile[$fileName];

                    $realPath = $file['realpath'];
                    unset($file['realpath']);

                    if(rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
                    {
                        $file['addedBy']    = $this->app->user->account;    
                        $file['addedDate']  = $now;     
                        if(in_array($file['extension'], $this->config->file->imageExtensions))
                        {
                            $this->dao->insert(TABLE_FILE)->data($file)->exec();

                            $fileID = $this->dao->lastInsertID();
                            $specData->spec .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
                        }
                        else
                        {
                            $file['objectType'] = 'story';
                            $file['objectID']   = $storyID;
                            $this->dao->insert(TABLE_FILE)->data($file)->exec();
                        }
                    }
                }

                $this->dao->insert(TABLE_STORYSPEC)->data($specData)->exec();

                $actionID = $this->action->create('story', $storyID, 'Opened', '');
                $mails[$i] = new stdclass();
                $mails[$i]->storyID  = $storyID;
                $mails[$i]->actionID = $actionID;
            }
        }

        /* Remove upload image file and session. */
        if(!empty($stories->uploadImage) and $this->session->storyImagesFile)
        {
            $classFile = $this->app->loadClass('zfile'); 
            $file = current($_SESSION['storyImagesFile']);
            $realPath = dirname($file['realpath']);
            if(is_dir($realPath)) $classFile->removeDir($realPath);
            unset($_SESSION['storyImagesFile']);
        }

        return $mails;
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
        $specChanged = false;
        $oldStory    = $this->dao->findById((int)$storyID)->from(TABLE_STORY)->fetch();
        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $story = fixer::input('post')->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)->get();
        if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or $this->loadModel('file')->getCount()) $specChanged = true;

        $now   = helper::now();
        $story = fixer::input('post')
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
            ->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)
            ->remove('files,labels,comment,needNotReview,uid')
            ->get();
        if($this->checkForceReview()) $story->status = 'changed';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->change['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story, 'spec,verify')
            ->autoCheck()
            ->batchCheck($this->config->story->change->requiredFields, 'notempty')
            ->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError())
        {
            if($specChanged)
            {
                $data          = new stdclass();
                $data->story   = $storyID;
                $data->version = $oldStory->version + 1;
                $data->title   = $story->title;
                $data->spec    = $story->spec;
                $data->verify  = $story->verify;
                $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();
            }
            else
            {
                unset($story->spec);
                unset($oldStory->spec);
            }
            $this->file->updateObjectID($this->post->uid, $storyID, 'story');
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
        $oldStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $story = fixer::input('post')
            ->cleanInt('product,module,pri')
            ->add('assignedDate', $oldStory->assignedDate)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('status', $oldStory->status)
            ->setDefault('product', $oldStory->product)
            ->setDefault('plan', 0)
            ->setDefault('branch', 0)
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->join('reviewedBy', ',')
            ->join('mailto', ',')
            ->remove('linkStories,childStories,files,labels,comment')
            ->get();
        if(is_array($story->plan)) $story->plan = trim(join(',', $story->plan), ',');
        if(empty($_POST['product'])) $story->branch = $oldStory->branch;

        $this->dao->update(TABLE_STORY)
            ->data($story)
            ->autoCheck()
            ->checkIF(isset($story->closedBy), 'closedReason', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->where('id')->eq((int)$storyID)->exec();

        if(!dao::isError())
        {
            if($story->product != $oldStory->product)
            {
                $this->dao->update(TABLE_PROJECTSTORY)->set('product')->eq($story->product)->where('story')->eq($storyID)->exec();
                $storyProjects  = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->orderBy('project')->fetchPairs('project', 'project');
                $linkedProjects = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('project')->in($storyProjects)->andWhere('product')->eq($story->product)->orderBy('project')->fetchPairs('project','project');
                $unlinkedProjects = array_diff($storyProjects, $linkedProjects);
                foreach($unlinkedProjects as $projectID)
                {
                    $data = new stdclass();
                    $data->project = $projectID;
                    $data->product = $story->product;
                    $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
                }
            }
            return common::createChanges($oldStory, $story);
        }
    }

    /**
     * Batch update stories.
     * 
     * @access public
     * @return array.
     */
    public function batchUpdate()
    {
        /* Init vars. */
        $stories     = array();
        $allChanges  = array();
        $now         = helper::now();
        $data        = fixer::input('post')->get();
        $storyIDList = $this->post->storyIDList ? $this->post->storyIDList : array();

        /* Init $stories. */
        if(!empty($storyIDList))
        {
            $oldStories = $this->getByList($storyIDList);

            /* Process the data if the value is 'ditto'. */
            foreach($storyIDList as $storyID)
            {
                if($data->pris[$storyID]     == 'ditto') $data->pris[$storyID]     = isset($prev['pri'])    ? $prev['pri']    : 0;
                if($data->branches[$storyID] == 'ditto') $data->branches[$storyID] = isset($prev['branch']) ? $prev['branch'] : 0;
                if($data->modules[$storyID]  == 'ditto') $data->modules[$storyID]  = isset($prev['module']) ? $prev['module'] : 0;
                if($data->plans[$storyID]    == 'ditto') $data->plans[$storyID]    = isset($prev['plan'])   ? $prev['plan']   : 0;
                if($data->sources[$storyID]  == 'ditto') $data->sources[$storyID]  = isset($prev['source']) ? $prev['source'] : '';
                if(isset($data->stages[$storyID])        and ($data->stages[$storyID]        == 'ditto')) $data->stages[$storyID]        = isset($prev['stage'])        ? $prev['stage']        : ''; 
                if(isset($data->closedBys[$storyID])     and ($data->closedBys[$storyID]     == 'ditto')) $data->closedBys[$storyID]     = isset($prev['closedBy'])     ? $prev['closedBy']     : ''; 
                if(isset($data->closedReasons[$storyID]) and ($data->closedReasons[$storyID] == 'ditto')) $data->closedReasons[$storyID] = isset($prev['closedReason']) ? $prev['closedReason'] : ''; 

                $prev['pri']    = $data->pris[$storyID];
                $prev['branch'] = isset($data->branches[$storyID]) ? $data->branches[$storyID] : 0;
                $prev['module'] = $data->modules[$storyID];
                $prev['plan']   = $data->plans[$storyID];
                $prev['source'] = $data->sources[$storyID];
                if(isset($data->stages[$storyID]))        $prev['stage']        = $data->stages[$storyID];
                if(isset($data->closedBys[$storyID]))     $prev['closedBy']     = $data->closedBys[$storyID];
                if(isset($data->closedReasons[$storyID])) $prev['closedReason'] = $data->closedReasons[$storyID];
            }

            foreach($storyIDList as $storyID)
            {
                $oldStory = $oldStories[$storyID];

                $story                 = new stdclass();
                $story->lastEditedBy   = $this->app->user->account;
                $story->lastEditedDate = $now;
                $story->status         = $oldStory->status;
                $story->color          = $data->colors[$storyID];
                $story->title          = $data->titles[$storyID];
                $story->estimate       = $data->estimates[$storyID];
                $story->pri            = $data->pris[$storyID];
                $story->assignedTo     = $data->assignedTo[$storyID];
                $story->assignedDate   = $oldStory == $data->assignedTo[$storyID] ? $oldStory->assignedDate : $now;
                $story->branch         = isset($data->branches[$storyID]) ? $data->branches[$storyID] : 0;
                $story->module         = $data->modules[$storyID];
                $story->plan           = $data->plans[$storyID];
                $story->source         = $data->sources[$storyID];
                $story->keywords       = $data->keywords[$storyID];
                $story->stage          = isset($data->stages[$storyID])             ? $data->stages[$storyID]             : $oldStory->stage;
                $story->closedBy       = isset($data->closedBys[$storyID])          ? $data->closedBys[$storyID]          : $oldStory->closedBy;
                $story->closedReason   = isset($data->closedReasons[$storyID])      ? $data->closedReasons[$storyID]      : $oldStory->closedReason;
                $story->duplicateStory = isset($data->duplicateStories[$storyID])   ? $data->duplicateStories[$storyID]   : $oldStory->duplicateStory;
                $story->childStories   = isset($data->childStoriesIDList[$storyID]) ? $data->childStoriesIDList[$storyID] : $oldStory->childStories;
                $story->version        = $story->title == $oldStory->title ? $oldStory->version : $oldStory->version + 1;

                if($story->title        != $oldStory->title)                         $story->status     = 'changed';
                if($story->plan         !== false and $story->plan == '')            $story->plan       = 0;
                if($story->closedBy     != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedReason != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedBy     != false  or  $story->closedReason != false) $story->status     = 'closed';
                if($story->closedReason != false  and $story->closedBy     == false) $story->closedBy   = $this->app->user->account;

                $stories[$storyID] = $story;
            }

            foreach($stories as $storyID => $story)
            {
                $oldStory = $oldStories[$storyID];

                $this->dao->update(TABLE_STORY)->data($story)
                    ->autoCheck()
                    ->checkIF($story->closedBy, 'closedReason', 'notempty')
                    ->checkIF($story->closedReason == 'done', 'stage', 'notempty')
                    ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                    ->where('id')->eq((int)$storyID)
                    ->exec();
                if($story->title != $oldStory->title)
                {
                    $data          = new stdclass();
                    $data->story   = $storyID;
                    $data->version = $story->version;
                    $data->title   = $story->title;
                    $data->spec    = $oldStory->spec;
                    $data->verify  = $oldStory->verify;
                    $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();
                }

                if(!dao::isError()) 
                {
                    $allChanges[$storyID] = common::createChanges($oldStory, $story);
                }
                else
                {
                    die(js::error('story#' . $storyID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
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
            ->join('reviewedBy', ',')
            ->get();

        /* fix bug #671. */
        $this->lang->story->closedReason = $this->lang->story->rejectedReason;

        $this->dao->update(TABLE_STORY)->data($story)
            ->autoCheck()
            ->batchCheck($this->config->story->review->requiredFields, 'notempty')
            ->checkIF($this->post->result == 'reject', 'closedReason', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'duplicate',  'duplicateStory', 'notempty')
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
     * Batch review stories.
     * 
     * @param  array   $storyIDList 
     * @access public
     * @return array
     */
    function batchReview($storyIDList, $result, $reason)
    {
        $now     = helper::now();
        $date    = helper::today();
        $actions = array();
        $this->loadModel('action');

        $oldStories = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status != 'draft' and $oldStory->status != 'changed') continue;

            $story = new stdClass();
            $story->reviewedDate   = $date;
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            if($result == 'pass') $story->status = 'active';
            if($reason == 'done') $story->stage = 'released';
            if($result == 'reject')
            {
                $story->status     = 'closed';
                $story->closedBy   = $this->app->user->account;
                $story->closedDate = $now;
                $story->assignedTo = closed;
                $this->action->create('story', $storyID, 'Closed', '', ucfirst($reason));
            }

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
            $this->setStage($storyID);

            if($reason and strpos('done,postponed', $reason) !== false) $result = 'pass';
            $actions[$storyID] = $this->action->create('story', $storyID, 'Reviewed', '', ucfirst($result));
        }

        return $actions;
    }

    /**
     * Subdivide story 
     * 
     * @param  int    $storyID 
     * @param  array  $stories 
     * @access public
     * @return int
     */
    public function subdivide($storyID, $stories)
    {
        $now      = helper::now();
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();

        /* Set childStories. */
        $childStories = '';
        foreach($stories as $story) $childStories .= $story->storyID . ',';
        $childStories = trim($childStories, ',');

        $newStory = new stdClass();
        $newStory->plan           = 0;
        $newStory->lastEditedBy   = $this->app->user->account;
        $newStory->lastEditedDate = $now;
        $newStory->closedDate     = $now;
        $newStory->closedBy       = $this->app->user->account;
        $newStory->assignedTo     = 'closed';
        $newStory->assignedDate   = $now;
        $newStory->status         = 'closed';
        $newStory->closedReason   = 'subdivided';
        $newStory->childStories   = $childStories;

        /* Subdivide story and close it. */
        $this->dao->update(TABLE_STORY)->data($newStory)
            ->autoCheck()
            ->batchCheck($this->config->story->close->requiredFields, 'notempty')
            ->where('id')->eq($storyID)->exec();
        $changes  = common::createChanges($oldStory, $newStory);
        $actionID = $this->action->create('story', $storyID, 'Closed', '', 'Subdivided');
        $this->action->logHistory($actionID, $changes);

        return $actionID;
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
            ->where('id')->eq($storyID)->exec();
        return common::createChanges($oldStory, $story);
    }

    /**
     * Batch close story.
     * 
     * @access public
     * @return void
     */
    public function batchClose()
    {
        /* Init vars. */
        $stories     = array();
        $allChanges  = array();
        $now         = helper::now();
        $data        = fixer::input('post')->get();
        $storyIDList = $data->storyIDList ? $data->storyIDList : array();

        $oldStories = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'closed') continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->closedBy       = $this->app->user->account;
            $story->closedDate     = $now;
            $story->assignedTo     = 'closed';
            $story->assignedDate   = $now;
            $story->status         = 'closed';

            $story->closedReason   = $data->closedReasons[$storyID];
            $story->duplicateStory = $data->duplicateStoryIDList[$storyID] ? $data->duplicateStoryIDList[$storyID] : $oldStory->duplicateStory;
            $story->childStories   = $data->childStoriesIDList[$storyID] ? $data->childStoriesIDList[$storyID] : $oldStory->childStories;

            if($story->closedReason == 'done') $story->stage = 'released';
            if($story->closedReason != 'done') $story->plan  = 0;

            $stories[$storyID] = $story;
            unset($story);
        }

        foreach($stories as $storyID => $story)
        {
            if(!$story->closedReason) continue;

            $oldStory = $oldStories[$storyID];

            $this->dao->update(TABLE_STORY)->data($story)
                ->autoCheck()
                ->checkIF($story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
                ->where('id')->eq($storyID)->exec();

            if(!dao::isError()) 
            {
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
            }
            else
            {
                die(js::error('story#' . $storyID . dao::getError(true)));
            }
        }

        return $allChanges;
    }

    /**
     * Batch change the module of story.
     *
     * @param  array  $storyIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($storyIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($moduleID == $oldStory->module) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->module         = $moduleID;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
    }

    /**
     * Batch change the plan of story.
     * 
     * @param  array  $storyIDList 
     * @param  int    $planID 
     * @access public
     * @return array 
     */
    public function batchChangePlan($storyIDList, $planID, $oldPlanID = 0)
    {
        $now         = helper::now();
        $allChanges  = array();
        $oldStories  = $this->getByList($storyIDList);
        $plan        = $this->loadModel('productplan')->getById($planID);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            if(strpos(",{$oldStory->plan},", ",$planID,") !== false) continue;
            if($this->session->currentProductType == 'normal' or empty($oldPlanID) or $oldStory->branch)
            {
                $story->plan = $planID;
            }
            elseif($oldPlanID)
            {
                $story->plan = trim(str_replace(",$oldPlanID,", ',', ",$oldStory->plan,"), ',');
                if(empty($story->branch)) $story->plan .= ",$planID";
            }
            if($planID) $story->stage = 'planned';

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!$planID) $this->setStage($storyID);
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
    }

    /**
     * Batch change branch.
     * 
     * @param  array  $storyIDList 
     * @param  int    $branchID 
     * @access public
     * @return void
     */
    public function batchChangeBranch($storyIDList, $branchID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->branch         = $branchID;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
    }

    /**
     * Batch change the stage of story.
     * 
     * @param  string    $stage 
     * @access public
     * @return array
     */
    public function batchChangeStage($storyIDList, $stage)
    {
        $now         = helper::now();
        $allChanges  = array();
        $oldStories  = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'draft') continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->stage          = $stage;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
    }

    /**
     * Batch assign to.
     * 
     * @access public
     * @return array
     */
    public function batchAssignTo()
    {
        $now         = helper::now();
        $allChanges  = array();
        $storyIDList = $this->post->storyIDList;
        $assignedTo  = $this->post->assignedTo;
        $oldStories  = $this->getByList($storyIDList);
        foreach($storyIDList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($assignedTo == $oldStory->assignedTo) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->assignedTo     = $assignedTo;
            $story->assignedDate   = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        return $allChanges;
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
            ->add('closedBy', '')
            ->add('closedReason', '')
            ->add('closedDate', '0000-00-00')
            ->add('reviewedBy', '')
            ->add('reviewedDate', '0000-00-00')
            ->add('duplicateStory', 0)
            ->add('childStories', '')
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
        return true;
    }

    /**
     * Set stage of a story.
     * 
     * @param  int    $storyID 
     * @access public
     * @return bool
     */
    public function setStage($storyID)
    {
        $storyID = (int)$storyID;

        /* Get projects which status is doing. */
        $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();
        $story    = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $product  = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fetch();
        $projects = $this->dao->select('t1.project,t3.branch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project')
            ->where('t1.story')->eq($storyID)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs('project', 'branch');

        $hasBranch = ($product->type != 'normal' and empty($story->branch));
        $stages    = array();
        if($hasBranch and $story->plan)
        {
            $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchPairs('branch', 'branch');
            foreach($plans as $branch) $stages[$branch] = 'planned';
        }

        /* If no projects, in plan, stage is planned. No plan, wait. */
        if(!$projects)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq($storyID)->andWhere('plan')->eq('')->exec();

            foreach($stages as $branch => $stage) $this->dao->insert(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq($stage)->exec();
            $this->dao->update(TABLE_STORY)->set('stage')->eq('planned')->where('id')->eq($storyID)->andWhere("(plan != '' AND plan != '0')")->exec();
        }

        if($hasBranch)
        {
            foreach($projects as $projectID => $branch) $stages[$branch] = 'projected';
        }

        /* Search related tasks. */
        $tasks = $this->dao->select('type,project,status')->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('story')->eq($storyID)
            ->andWhere('type')->in('devel,test')
            ->andWhere('status')->ne('cancel')
            ->andWhere('closedReason')->ne('cancel')
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('type');

        /* No tasks, then the stage is projected. */
        if(!$tasks and $projects)
        {
            foreach($stages as $branch => $stage) $this->dao->insert(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('projected')->exec();
            $this->dao->update(TABLE_STORY)->set('stage')->eq('projected')->where('id')->eq($storyID)->exec();
        }

        /* Get current stage and set as default value. */
        $currentStage = $story->stage;
        $stage = $currentStage;

        /* Cycle all tasks, get counts of every type and every status. */
        $branchStatusList = array();
        $branchDevelTasks = array();
        $branchTestTasks  = array();
        $statusList['devel'] = array('wait' => 0, 'doing' => 0, 'done'=> 0);
        $statusList['test']  = array('wait' => 0, 'doing' => 0, 'done'=> 0);
        foreach($tasks as $type => $typeTasks)
        {
            foreach($typeTasks as $task)
            {
                $status = $task->status ? $task->status : 'wait';
                $status = $status == 'closed' ? 'done' : $status;

                $branch = $projects[$task->project];
                if(!isset($branchStatusList[$branch])) $branchStatusList[$branch] = $statusList;
                $branchStatusList[$branch][$task->type][$status] ++;
                if($type == 'devel')
                {
                    if(!isset($branchDevelTasks[$branch])) $branchDevelTasks[$branch] = 0;
                    $branchDevelTasks[$branch] ++;
                }
                elseif($type == 'test')
                {
                    if(!isset($branchTestTasks[$branch])) $branchTestTasks[$branch] = 0;
                    $branchTestTasks[$branch] ++;
                }
            }
        }

        /**
         * Judge stage according to the devel and test tasks' status. 
         * 
         * 1. one doing devel task, all test tasks waiting, set stage as developing.
         * 2. all devel tasks done, all test tasks waiting, set stage as developed.
         * 3. one test task doing, set stage as testing. 
         * 4. all test tasks done, still some devel tasks not done(wait, doing), set stage as testing.
         * 5. all test tasks done, all devel tasks done, set stage as tested.
         */
        foreach($branchStatusList as $branch => $statusList)
        {
            $testTasks  = isset($branchTestTasks[$branch]) ? $branchTestTasks[$branch] : 0;
            $develTasks = isset($branchDevelTasks[$branch]) ? $branchDevelTasks[$branch] : 0;
            if($statusList['devel']['doing'] > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developing'; 
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developed';
            if($statusList['test']['doing'] > 0) $stage = 'testing';
            if(($statusList['devel']['wait'] > 0 or $statusList['devel']['doing'] > 0) and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'testing';
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'tested';

            $stages[$branch] = $stage;
        }

        $releases = $this->dao->select('*')->from(TABLE_RELEASE)->where("CONCAT(',', stories, ',')")->like("%,$storyID,%")->andWhere('deleted')->eq(0)->fetchPairs('branch', 'branch');
        foreach($releases as $branch) $stages[$branch] = 'released';

        if(empty($stages)) return;
        if($hasBranch)
        {
            $stageList   = join(',', array_keys($this->lang->story->stageList));
            $minStagePos = strlen($stageList);
            $minStage    = '';
            foreach($stages as $branch => $stage)
            {
                $this->dao->insert(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq($stage)->exec();
                if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) < $minStagePos)
                {
                    $minStage    = $stage;
                    $minStagePos = strpos($stageList, $stage);
                }
            }
            $this->dao->update(TABLE_STORY)->set('stage')->eq($minStage)->where('id')->eq($storyID)->exec();
        }
        else
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq(current($stages))->where('id')->eq($storyID)->exec();
        }

        return;
    }

    /**
     * Link stories.
     *
     * @param  int    $storyID
     * @param  string $type
     * @access public
     * @return void
     */
    public function linkStories($storyID, $type = 'linkStories')
    {
        if($this->post->stories == false) return;

        $story        = $this->getById($storyID);
        $stories2Link = $this->post->stories;

        $stories = implode(',', $stories2Link) . ',' . trim($story->$type, ',');
        $this->dao->update(TABLE_STORY)->set($type)->eq(trim($stories, ','))->where('id')->eq($storyID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $action = ($type == 'linkStories') ? 'linkRelatedStory' : 'subdivideStory';
        $this->loadModel('action')->create('story', $storyID, $action, '', implode(',', $stories2Link));
    }

    /**
     * Get stories to link.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getStories2Link($storyID, $type = 'linkStories', $browseType = 'bySearch', $queryID = 0)
    {
        if($browseType == 'bySearch')
        {
            $story        = $this->getById($storyID);
            $stories2Link = $this->getBySearch($story->product, $queryID, 'id', null, '', $story->branch);
            foreach($stories2Link as $key => $story2Link)
            {
                if($story2Link->id == $storyID) unset($stories2Link[$key]);
                if(in_array($story2Link->id, explode(',', $story->$type))) unset($stories2Link[$key]);
            }
            return $stories2Link;
        }
        else
        {
            return array();
        }
    }

    /**
     * Unlink story.
     *
     * @param  int    $storyID
     * @param  string $type
     * @param  int    $story2Unlink
     * @access public
     * @return void
     */
    public function unlinkStory($storyID, $type = 'linkStories', $story2Unlink = 0)
    {
        $story = $this->getById($storyID);

        $oldLinkedStories = explode(',', trim($story->$type, ','));
        foreach($oldLinkedStories as $key => $storyId)
        {
            if($storyId == $story2Unlink) unset($oldLinkedStories[$key]);
        }
        $stories = implode(',', $oldLinkedStories);

        $this->dao->update(TABLE_STORY)->set($type)->eq($stories)->where('id')->eq($storyID)->exec();
        if(dao::isError()) die(js::error(dao::getError()));
        $action = ($type == 'linkStories') ? 'unlinkRelatedStory' : 'unlinkChildStory';
        $this->loadModel('action')->create('story', $storyID, $action, '', $story2Unlink);
    }

    /**
     * Get linked stories.
     *
     * @param  string $storyID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getLinkedStories($storyID, $type = 'linkStories')
    {
        $story = $this->getById($storyID);
        return $this->dao->select('id, title')->from(TABLE_STORY)->where('id')->in($story->$type)->fetchPairs();
    }

    /**
     * Get stories list of a product.
     * 
     * @param  int           $productID 
     * @param  array|string  $moduleIdList
     * @param  string        $status 
     * @param  string        $orderBy 
     * @param  object        $pager 
     * @access public
     * @return array
     */
    public function getProductStories($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getStories();

        if(is_array($branch))
        {
            unset($branch[0]);
            $branch = join(',', $branch);
            if($branch) $branch = "0,$branch";
        }
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->beginIF($branch)->andWhere("branch")->in($branch)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        return $this->mergePlanTitle($productID, $stories, $branch);
    }

    /**
     * Get stories pairs of a product.
     * 
     * @param  int           $productID 
     * @param  array|string  $moduleIdList 
     * @param  string        $status 
     * @param  string        $order 
     * @param  int           $limit 
     * @access public
     * @return array
     */
    public function getProductStoryPairs($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $order = 'id_desc', $limit = 0)
    {
        if($branch) $branch = "0,$branch";//Fix bug 1059.
        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productID)->andWhere('t1.product')->in($productID)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF($status and $status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($order)
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories, 'full', $limit);
    }

    /**
     * Get stories by assignedTo.
     * 
     * @param  int    $productID 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByAssignedTo($productID, $branch, $modules, $account, $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedTo', $account, $orderBy, $pager);
    }

    /**
     * Get stories by openedBy.
     * 
     * @param  int    $productID 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByOpenedBy($productID, $branch, $modules, $account, $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'openedBy', $account, $orderBy, $pager);
    }

    /**
     * Get stories by reviewedBy.
     * 
     * @param  int    $productID 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getByReviewedBy($productID, $branch, $modules, $account, $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewedBy', $account, $orderBy, $pager, 'include');
    }

    /**
     * Get stories by closedBy.
     * 
     * @param  int    $productID 
     * @param  string $account 
     * @param  string $orderBy 
     * @param  object $pager 
     * @return array
     */
    public function getByClosedBy($productID, $branch, $modules, $account, $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'closedBy', $account, $orderBy, $pager);
    }

    /**
     * Get stories by status.
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @param  string $status 
     * @access public
     * @return array
     */
    public function getByStatus($productID, $branch, $modules, $status, $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'status', $status, $orderBy, $pager);
    }

    /**
     * Get stories by a field.
     * 
     * @param  int    $productID 
     * @param  string $fieldName 
     * @param  mixed  $fieldValue 
     * @param  string $orderBy 
     * @param  object $pager 
     * @param  string $operator     equal|include
     * @access public
     * @return array
     */
    public function getByField($productID, $branch, $modules, $fieldName, $fieldValue, $orderBy, $pager, $operator = 'equal')
    {
        if(!$this->loadModel('common')->checkField(TABLE_STORY, $fieldName)) return array();
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($branch)->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->beginIF($operator == 'equal')->andWhere($fieldName)->eq($fieldValue)->fi()
            ->beginIF($operator == 'include')->andWhere($fieldName)->like("%$fieldValue%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        return $this->mergePlanTitle($productID, $stories, $branch);
    }

    /**
     * Get to be closed stories.
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  string $pager 
     * @access public
     * @return array
     */
    public function get2BeClosed($productID, $branch, $modules, $orderBy, $pager)
    {
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->beginIF($branch)->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('stage')->in('developed,released')
            ->andWhere('status')->ne('closed')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->mergePlanTitle($productID, $stories, $branch);
    }

    /**
     * Get stories through search.
     * 
     * @access public
     * @param  int    $productID 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @param  string $projectID 
     * @access public
     * @return array
     */
    public function getBySearch($productID, $queryID, $orderBy, $pager = null, $projectID = '', $branch = 0)
    {
        if($projectID != '') 
        {
            $products = $this->loadModel('project')->getProducts($projectID); 
        }
        else
        {
            $products = $this->loadModel('product')->getPairs();
        }
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
        if($query)
        {
            $this->session->set('storyQuery', $query->sql);
            $this->session->set('storyForm', $query->form);
        }
        if($this->session->storyQuery == false) $this->session->set('storyQuery', ' 1 = 1');

        $allProduct     = "`product` = 'all'";
        $storyQuery     = $this->session->storyQuery;
        $queryProductID = $productID;
        if(strpos($storyQuery, $allProduct) !== false)
        {
            $storyQuery     = str_replace($allProduct, '1', $storyQuery);
            $queryProductID = 'all';
        }
        $storyQuery = $storyQuery . ' AND `product` ' . helper::dbIN(array_keys($products));
        if($projectID != '')
        {
            foreach($products as $product) $branches[$product->branch] = $product->branch;
            unset($branches[0]);
            $branches = join(',', $branches);
            if($branches) $storyQuery .= " AND `branch`" . helper::dbIN("0,$branches"); 
            if($this->app->moduleName == 'release' or $this->app->moduleName == 'build')
            {
                $storyQuery .= " AND `status` NOT IN ('draft')";// Fix bug #990.
            }
            else
            {
                $storyQuery .= " AND `status` NOT IN ('draft', 'closed')";
            }
        }
        elseif($branch)
        {
            $allBranch = "`branch` = 'all'";
            if($branch and strpos($storyQuery, '`branch` =') === false) $storyQuery .= " AND `branch` in('0','$branch')";
            if(strpos($storyQuery, $allBranch) !== false) $storyQuery = str_replace($allBranch, '1', $storyQuery);
        }
        $storyQuery = preg_replace("/`plan` +LIKE +'%([0-9]+)%'/i", "CONCAT(',', `plan`, ',') LIKE '%,$1,%'", $storyQuery);

        return $this->getBySQL($queryProductID, $storyQuery, $orderBy, $pager);
    }

    /**
     * Get stories by a sql.
     * 
     * @param  int    $productID 
     * @param  string $sql 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getBySQL($productID, $sql, $orderBy, $pager = null)
    {
        /* Get plans. */
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq('0')
            ->beginIF($productID != 'all' and $productID != '')->andWhere('product')->eq((int)$productID)->fi()
            ->fetchPairs();

        $tmpStories = $this->dao->select('*')->from(TABLE_STORY)->where($sql)
            ->beginIF($productID != 'all' and $productID != '')->andWhere('product')->eq((int)$productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if(!$tmpStories) return array();

        /* Process plans. */
        $stories = array();
        foreach($tmpStories as $story)
        {
            $story->planTitle = '';
            $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
            $stories[] = $story;
        }
        return $stories;
    }
    
    /**
     * Get stories list of a project.
     * 
     * @param  int    $projectID 
     * @param  string $orderBy 
     * @access public
     * @return array
     */
    public function getProjectStories($projectID = 0, $orderBy = 'pri_asc,id_desc', $type = 'byModule', $param = 0, $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectStories();

        if($type == 'bySearch')
        {
            $queryID  = (int)$param;
            $products = $this->loadModel('project')->getProducts($projectID);

            if($this->session->projectStoryQuery == false) $this->session->set('projectStoryQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('projectStoryQuery', $query->sql);
                    $this->session->set('projectStoryForm', $query->form);
                }
            }

            $allProduct = "`product` = 'all'";
            $storyQuery = $this->session->projectStoryQuery;
            if(strpos($this->session->projectStoryQuery, $allProduct) !== false)
            {
                $storyQuery = str_replace($allProduct, '1', $this->session->projectStoryQuery);
            }
            $storyQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $storyQuery);

            $stories = $this->dao->select('distinct t1.*, t2.*, t3.branch as productBranch, t4.type as productType, t2.version as version')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project')
                ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t2.product = t4.id')
                ->where($storyQuery)
                ->andWhere('t1.project')->eq((int)$projectID)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $modules = ($type == 'byModule' and $param) ? $this->dao->select('*')->from(TABLE_MODULE)->where('path')->like("%,$param,%")->andWhere('type')->eq('story')->andWhere('deleted')->eq(0)->fetchPairs('id', 'id') : array();
            $stories = $this->dao->select('distinct t1.*, t2.*,t3.branch as productBranch,t4.type as productType,t2.version as version')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project')
                ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t2.product = t4.id')
                ->where('t1.project')->eq((int)$projectID)
                ->beginIF($type == 'byProduct')->andWhere('t1.product')->eq($param)->fi()
                ->beginIF($type == 'byBrach')->andWhere('t2.branch')->eq($param)->fi()
                ->beginIF($type == 'byModule' and $param)->andWhere('t2.module')->in($modules)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't2.id')
                ->fetchAll('id');
        }

        $query    = $this->dao->get();
        $branches = array();
        foreach($stories as $story)
        {
            if(empty($story->branch) and $story->productType != 'normal') $branches[$story->productBranch][$story->id] = $story->id;
        }
        foreach($branches as $branchID => $storyIDList)
        {
            $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIDList)->andWhere('branch')->eq($branchID)->fetchPairs('story', 'stage');
            foreach($stages as $storyID => $stage) $stories[$storyID]->stage = $stage;
        }

        $this->dao->sqlobj->sql = $query;
        return $stories;
    }

    /**
     * Get stories pairs of a project.
     * 
     * @param  int           $projectID 
     * @param  int           $productID 
     * @param  array|string  $moduleIdList
     * @param  string        $type
     * @access public
     * @return array
     */
    public function getProjectStoryPairs($projectID = 0, $productID = 0, $branch = 0, $moduleIdList = 0, $type = 'full')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectStoryPairs();
        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t2.product')->eq((int)$productID)->fi()
            ->beginIF($branch)->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories, $type);
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
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where("CONCAT(',', plan, ',')")->like("%,$planID,%")
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
        
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        
        return $stories;
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
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
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
    public function getUserStories($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null)
    {
        $stories = $this->dao->select('t1.*, t2.name as productTitle')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('assignedTo')->eq($account)->fi()
            ->beginIF($type == 'openedBy')->andWhere('openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere('reviewedBy')->like('%' . $account . '%')->fi()
            ->beginIF($type == 'closedBy')->andWhere('closedBy')->eq($account)->fi()
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');
        $productIdList = array();
        foreach($stories as $story) $productIdList[$story->product] = $story->product;
        
        return $this->mergePlanTitle($productIdList, $stories);
    }

    /**
     * Get story pairs of a user.
     * 
     * @param  string    $account 
     * @param  string    $limit 
     * @access public
     * @return array
     */
    public function getUserStoryPairs($account, $limit = 10)
    {
        return $this->dao->select('id, title')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('assignedTo')->eq($account)
            ->orderBy('id_desc')
            ->limit($limit)
            ->fetchAll();
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
     * Get zero case.
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getZeroCase($productID, $orderBy = 'id_desc')
    {
        $allStories   = $this->getProductStories($productID, 0, 0, 'all', $orderBy);
        $casedStories = $this->dao->select('DISTINCT story')->from(TABLE_CASE)->where('product')->eq($productID)->andWhere('story')->ne(0)->andWhere('deleted')->eq(0)->fetchAll('story');

        foreach($allStories as $key => $story)
        {
            if(isset($casedStories[$story->id])) unset($allStories[$key]);
        }

        return $allStories;
    }

    /**
     * Batch get story stage.
     * 
     * @param  array    $stories 
     * @access public
     * @return array
     */
    public function batchGetStoryStage($stories)
    {
        return $this->dao->select('*')->from(TABLE_STORYSTAGE)
            ->where('story')->in($stories)
            ->fetchGroup('story', 'branch');
    }

    /**
     * Check need confirm.
     * 
     * @param  array    $dataList 
     * @access public
     * @return array
     */
    public function checkNeedConfirm($dataList)
    {
        $storyIDList      = array();
        $storyVersionList = array();
        foreach($dataList as $key => $data)
        {
            $data->needconfirm = false;
            if($data->story)
            {
                $storyIDList[$key]      = $data->story;
                $storyVersionList[$key] = $data->storyVersion;
            }
        }

        $stories = $this->dao->select('id,version')->from(TABLE_STORY)->where('id')->in($storyIDList)->andWhere('status')->eq('active')->fetchPairs('id', 'version');
        foreach($storyIDList as $key => $storyID)
        {
            if(isset($stories[$storyID]) and $stories[$storyID] > $storyVersionList[$key]) $dataList[$key]->needconfirm = true;
        }

        return $dataList;
    }

    /**
     * Format stories 
     * 
     * @param  array    $stories 
     * @param  string   $type
     * @param  int      $limit
     * @access public
     * @return void
     */
    public function formatStories($stories, $type = 'full', $limit = 0)
    {
        /* Get module names of stories. */
        /*$modules = array();
        foreach($stories as $story) $modules[] = $story->module;
        $moduleNames = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($modules)->fetchPairs();*/

        /* Format these stories. */
        $storyPairs = array('' => '');
        $i = 0;
        foreach($stories as $story)
        {
            if($type == 'short')
            {
                $property = '[p' . $story->pri . ', ' . $story->estimate . 'h]';
            }
            else
            {
                $property = '(' . $this->lang->story->pri . ':' . $story->pri . ',' . $this->lang->story->estimate . ':' . $story->estimate . ')';
            }
            $storyPairs[$story->id] = $story->id . ':' . $story->title . $property;

            if($limit > 0 && ++$i > $limit)
            {
                $storyPairs['showmore'] = $this->lang->more . $this->lang->ellipsis;
                break;
            }
        }
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
        if(!isset($chartOption->type))    $chartOption->type    = $commonOption->type;
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
            ->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        return $datas;
    }

    /**
     * Get report data of storys per source 
     * 
     * @access public
     * @return array
     */
    public function getDataOfStorysPerSource()
    {
        $datas = $this->dao->select('source as name, count(source) as value')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('source')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        $this->lang->story->sourceList[''] = $this->lang->report->undefined;
        foreach($datas as $key => $data) $data->name = isset($this->lang->story->sourceList[$key]) ? $this->lang->story->sourceList[$key] : $this->lang->report->undefined;
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
            ->groupBy('assignedTo')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = (isset($this->users[$account]) and $this->users[$account] != '') ? $this->users[$account] : $this->lang->report->undefined;
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
            ->groupBy('version')->orderBy('value')->fetchAll();
    }

    /**
     * Adjust the action clickable.
     * 
     * @param  object $story 
     * @param  string $action 
     * @access public
     * @return void
     */
    public static function isClickable($story, $action)
    {
        $action = strtolower($action);

        if($action == 'change')   return $story->status != 'closed';
        if($action == 'review')   return $story->status == 'draft' or $story->status == 'changed';
        if($action == 'close')    return $story->status != 'closed';
        if($action == 'activate') return $story->status == 'closed';

        return true;
    }

    /**
     * Merge plan title.
     * 
     * @param  int|array    $productID 
     * @param  array    $stories 
     * @access public
     * @return array
     */
    public function mergePlanTitle($productID, $stories, $branch = 0)
    {
        $query = $this->dao->get();
        if(is_array($branch))
        {
            unset($branch[0]);
            $branch = join(',', $branch);
            if($branch) $branch = "0,$branch";
        }
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($productID)
            ->beginIF($branch)->andWhere('branch')->in($branch)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'title');
        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('branch')->in($branch)->fetchGroup('story', 'branch');

        $branch = trim(str_replace(',0,', '', ",$branch,"), ',');
        $branch = empty($branch) ? 0 : $branch;
        foreach($stories as $story)
        {
            $story->planTitle = '';
            $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
        }

        /* For save session query. */
        $this->dao->sqlobj->sql = $query;
        return $stories;
    }

    /**
     * Print cell data
     * 
     * @param  object $col 
     * @param  object $story 
     * @param  array  $users 
     * @param  array  $branches 
     * @param  array  $storyStages 
     * @param  array  $modulePairs
     * @param  array  $storyTasks
     * @param  array  $storyBugs
     * @param  array  $storyCases
     * @access public
     * @return void
     */
    public function printCell($col, $story, $users, $branches, $storyStages, $modulePairs = array(), $storyTasks, $storyBugs, $storyCases)
    {
        $storyLink = helper::createLink('story', 'view', "storyID=$story->id");
        $account   = $this->app->user->account;
        $id        = $col->id;
        if($col->show)
        {
            $class = '';
            if($id == 'status') $class .= ' story-' . $story->status;
            if($id == 'title') $class .= ' text-left';
            if($id == 'assignedTo' && $story->assignedTo == $account) $class .= ' red';

            $title = ''; 
            if($id == 'title') $title = $story->title;
            if($id == 'plan')  $title = $story->planTitle;

            echo "<td class='" . $class . "' title='$title'>";
            switch ($id)
            {
            case 'id':
                echo html::a($storyLink, sprintf('%03d', $story->id));
                break;
            case 'pri':
                echo "<span class='pri" . zget($this->lang->story->priList, $story->pri, $story->pri) . "'>";
                echo zget($this->lang->story->priList, $story->pri, $story->pri);
                echo "</span>";
                break;
            case 'title':
                if($story->branch) echo "<span class='label label-info label-badge'>{$branches[$story->branch]}</span> ";
                if($modulePairs and $story->module) echo "<span class='label label-info label-badge'>{$modulePairs[$story->module]}</span> ";
                echo html::a($storyLink, $story->title, null, "style='color: $story->color'");
                break;
            case 'plan':
                echo $story->planTitle;
                break;
            case 'branch':
                echo $branches[$story->branch];
                break;
            case 'source':
                echo zget($this->lang->story->sourceList, $story->source, $story->source);
                break;
            case 'status':
                echo $this->lang->story->statusList[$story->status];
                break;
            case 'estimate':
                echo $story->estimate;
                break;
            case 'stage':
                echo "<div" . (isset($storyStages[$story->id]) ? " class='popoverStage' data-toggle='popover' data-placement='bottom' data-target='\$next'" : '') . "'>";
                echo $this->lang->story->stageList[$story->stage];
                if(isset($storyStages[$story->id])) echo "<span><i class='icon icon-caret-down'></i></span>";
                echo '</div>';
                if(isset($storyStages[$story->id]))
                {
                    echo "<div class='popover'>";
                    foreach($storyStages[$story->id] as $storyBranch => $storyStage) echo $branches[$storyBranch] . ": " . $this->lang->story->stageList[$storyStage->stage] . '<br />';
                    echo "</div>";
                }
                break;
            case 'taskCount':
                $tasksLink = helper::createLink('story', 'tasks', "storyID=$story->id");
                $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
                break;
            case 'bugCount':
                $bugsLink = helper::createLink('story', 'bugs', "storyID=$story->id");
                $storyBugs[$story->id] > 0 ? print(html::a($bugsLink, $storyBugs[$story->id], '', 'class="iframe"')) : print(0);
                break;
            case 'caseCount':
                $casesLink = helper::createLink('story', 'cases', "storyID=$story->id");
                $storyCases[$story->id] > 0 ? print(html::a($casesLink, $storyCases[$story->id], '', 'class="iframe"')) : print(0);
                break;
            case 'openedBy':
                echo zget($users, $story->openedBy, $story->openedBy);
                break;
            case 'openedDate':
                echo substr($story->openedDate, 5, 11);
                break;
            case 'assignedTo':
                echo zget($users, $story->assignedTo, $story->assignedTo);
                break;
            case 'assignedDate':
                echo substr($story->assignedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo zget($users, $story->reviewedBy, $story->reviewedBy);
                break;
            case 'reviewedDate':
                echo substr($story->reviewedDate, 5, 11);
                break;
            case 'closedBy':
                echo zget($users, $story->closedBy, $story->closedBy);
                break;
            case 'closedDate':
                echo substr($story->closedDate, 5, 11);
                break;
            case 'closedReason':
                echo zget($this->lang->story->reasonList, $story->closedReason, $story->closedReason);
                break;
            case 'actions':
                $vars = "story={$story->id}";
                common::printIcon('story', 'change',     $vars, $story, 'list', 'random');
                common::printIcon('story', 'review',     $vars, $story, 'list', 'review');
                common::printIcon('story', 'close',      $vars, $story, 'list', 'off', '', 'iframe', true);
                common::printIcon('story', 'edit',       $vars, $story, 'list', 'pencil');
                if($this->config->global->flow != 'onlyStory') common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Set report condition.
     * 
     * @access public
     * @return string
     */
    public function reportCondition()
    {
        if(isset($_SESSION['storyQueryCondition']))
        {
            if(!$this->session->storyOnlyCondition)
            {
                preg_match_all('/' . TABLE_STORY .' AS ([\w]+) /', $this->session->storyQueryCondition, $matches);
                if(isset($matches[1][0])) return 'id in (' . preg_replace('/SELECT .* FROM/', "SELECT {$matches[1][0]}.id FROM", $this->session->storyQueryCondition) . ')';
            }
            return $this->session->storyQueryCondition;
        }
        return true;
    }

    /**
     * Check force review for user.
     * 
     * @access public
     * @return bool
     */
    public function checkForceReview()
    {
        $forceReview = false;
        if(!empty($this->config->story->forceReview)) $forceReview = strpos(",{$this->config->story->forceReview},", ",{$this->app->user->account},") !== false;

        return $forceReview;
    }

    /**
     * Send mail 
     * 
     * @param  int    $storyID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($storyID, $actionID)
    {
        $this->loadModel('mail');
        $story       = $this->getById($storyID);
        $productName = $this->loadModel('product')->getById($story->product)->name;
        $users       = $this->loadModel('user')->getPairs('noletter');

        /* Get actions. */
        $action  = $this->loadModel('action')->getById($actionID);
        $history = $this->action->getHistory($actionID);
        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':')!== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($id)
            {
                $name  = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($action->objectType, 'view', "id=$id"), "#$id " . $name);
            }
        }

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'story');
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
        $toList = $story->assignedTo;
        $ccList = str_replace(' ', '', trim($story->mailto, ','));

        /* If the action is changed or reviewed, mail to the project team. */
        if(strtolower($action->action) == 'changed' or strtolower($action->action) == 'reviewed')
        {
            $prjMembers = $this->getProjectMembers($storyID);
            if($prjMembers)
            {
                $ccList .= ',' . join(',', $prjMembers);
                $ccList = ltrim($ccList, ',');
            }
        }

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
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        elseif($toList == 'closed')
        {
            $toList = $story->openedBy;
        }

        /* Send it. */
        $this->mail->send($toList, 'STORY #' . $story->id . ' ' . $story->title . ' - ' . $productName, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
}
