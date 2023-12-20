<?php
/**
 * The model file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: model.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
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
        $story = $this->dao->select('*')->from(TABLE_STORY)
            ->where('id')->eq($storyID)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->fetch();
        if(!$story) return false;

        $this->loadModel('file');
        if(helper::isZeroDate($story->closedDate)) $story->closedDate = '';
        if($version == 0) $version = $story->version;
        $spec = $this->dao->select('title,spec,verify,files')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title  = isset($spec->title)  ? $spec->title  : '';
        $story->spec   = isset($spec->spec)   ? $spec->spec   : '';
        $story->verify = isset($spec->verify) ? $spec->verify : '';
        $story->files  = isset($spec->files)  ? $this->file->getByIdList($spec->files) : array();
        if(!empty($story->fromStory)) $story->sourceName = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($story->fromStory)->fetch('title');

        /* Check parent story. */
        if($story->parent > 0) $story->parentName = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch('title');

        $story = $this->file->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $story->executions = $this->dao->select('t1.project, t2.name, t2.status, t2.type, t2.multiple')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.type')->in('sprint,stage,kanban')
            ->beginIF($story->twins)->andWhere('t1.story')->in(ltrim($story->twins, ',') . $story->id)->fi()
            ->beginIF(!$story->twins)->andWhere('t1.story')->in($story->id)->fi()
            ->orderBy('t1.`order` DESC')
            ->fetchAll('project');

        $story->tasks  = $this->dao->select('id, name, assignedTo, execution, project, status, consumed, `left`,type')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->beginIF($story->twins)->andWhere('story')->in(ltrim($story->twins, ',') . $story->id)->fi()
            ->beginIF(!$story->twins)->andWhere('story')->in($story->id)->fi()
            ->orderBy('id DESC')
            ->fetchGroup('execution');

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
                if($plan->branch and !isset($story->stages[$plan->branch]) and empty($story->branch)) $story->stages[$plan->branch] = 'planned';
            }
        }
        $extraStories = array();
        if($story->duplicateStory) $extraStories = array($story->duplicateStory);
        if($story->linkStories)    $extraStories = explode(',', $story->linkStories);
        if($story->childStories)   $extraStories = array_merge($extraStories, explode(',', $story->childStories));
        $extraStories = array_unique($extraStories);
        if(!empty($extraStories)) $story->extraStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($extraStories)->fetchPairs();

        $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
        if($story->{$linkStoryField}) $story->linkStoryTitles = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($story->{$linkStoryField})->fetchPairs();

        $story->children = array();
        if($story->parent == '-1') $story->children = $this->dao->select('*')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll('id');

        $story->openedDate    = substr($story->openedDate, 0, 19);
        $story->assignedDate  = substr($story->assignedDate, 0, 19);
        $story->reviewedDate  = substr($story->reviewedDate, 0, 19);
        $story->closedDate    = substr($story->closedDate, 0, 19);
        $story->lastEditedDate= substr($story->lastEditedDate, 0, 19);

        return $story;
    }

    /**
     * Get stories by idList.
     *
     * @param  int|array|string    $storyIdList
     * @param  string $type requirement|story
     * @param  string $mode all
     * @access public
     * @return array
     */
    public function getByList($storyIdList = 0, $type = 'story', $mode = '')
    {
        return $this->dao->select('t1.*, t2.spec, t2.verify, t3.name as productTitle, t3.deleted as productDeleted')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.version=t2.version')
            ->beginIF($mode != 'all')->andWhere('t1.deleted')->eq(0)->fi()
            ->beginIF($storyIdList)->andWhere('t1.id')->in($storyIdList)->fi()
            ->beginIF(!$storyIdList)->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($this->config->vision == 'or')->andWhere("CONCAT(',', t1.vision, ',')")->like('%,or,%')->fi()
            ->fetchAll('id');
    }

    /**
     * Get test stories.
     *
     * @param  array  $storyIdList
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getTestStories($storyIdList, $executionID)
    {
        return $this->dao->select('story')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('type')->eq('test')->andWhere('story')->in($storyIdList)->andWhere('deleted')->eq(0)->fetchPairs('story', 'story');
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
        /* Remove closed executions. */
        if($story->executions)
        {
            foreach($story->executions as $executionID => $execution) if($execution->status == 'done') unset($story->executions[$executionID]);
        }

        /* Get team members. */
        if($story->executions)
        {
            $story->teams = $this->dao->select('account, root')
                ->from(TABLE_TEAM)
                ->where('root')->in(array_keys($story->executions))
                ->andWhere('type')->eq('project')
                ->fetchGroup('root');
        }

        /* Get affected bugs. */
        $story->bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('status')->ne('closed')
            ->beginIF($story->twins)->andWhere('story')->in(ltrim($story->twins, ',') . $story->id)->fi()
            ->beginIF(!$story->twins)->andWhere('story')->in($story->id)->fi()
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();

        /* Get affected cases. */
        $story->cases = $this->dao->select('*')->from(TABLE_CASE)
            ->where('deleted')->eq(0)
            ->beginIF($story->twins)->andWhere('story')->in(ltrim($story->twins, ',') . $story->id)->fi()
            ->beginIF(!$story->twins)->andWhere('story')->in($story->id)->fi()
            ->fetchAll();

        return $story;
    }

    /**
     *  Get requirements for story.
     *
     *  @param  int     $productID
     *  @access public
     *  @return void
     */
    public function getRequirements($productID)
    {
        return $this->dao->select('id,title')->from(TABLE_STORY)
           ->where('deleted')->eq(0)
           ->andWhere('product')->eq($productID)
           ->andWhere('type')->eq('requirement')
           ->andWhere('status')->notIN('draft,closed')
           ->fetchPairs();
    }

    /**
     * Create a story.
     *
     * @param  int    $executionID
     * @param  int    $bugID
     * @param  string $from
     * @param  string $extra
     * @access public
     * @return bool|array
     */
    public function create($executionID = 0, $bugID = 0, $from = '', $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewers);
            return false;
        }

        $now   = helper::now();
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->callFunc('title', 'trim')
            ->add('version', 1)
            ->setDefault('plan,notifyEmail', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setDefault('estimate', 0)
            ->setIF(strlen($this->post->verify) == 0, 'verify', '')
            ->setIF($this->post->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->plan > 0, 'stage', 'planned')
            ->setIF($this->post->estimate, 'estimate', (float)$this->post->estimate)
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF($executionID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->join('assignedTo', '')
            ->join('mailto', ',')
            ->stripTags($this->config->story->editor->create['id'], $this->config->allowedTags)
            ->remove('files,labels,reviewer,needNotReview,newStory,uid,contactListMenu,URS,region,lane,ticket,branches,modules,plans')
            ->get();

        /* Check repeat story. */
        $result = $this->loadModel('common')->removeDuplicate('story', $story, "product={$story->product}");
        if(isset($result['stop']) and $result['stop'])
        {
            dao::$errors[] = $this->lang->story->title . $this->lang->story->reasonList['duplicate'] . ',' . $this->lang->story->id . $result['duplicate'];
            return false;
        }
        if($story->status != 'draft' and $this->checkForceReview()) $story->status = 'reviewing';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->create['id'], $this->post->uid);

        $product = $this->loadModel('product')->getById($story->product);
        if($product->type == 'normal' or $product->type == 'branch' or $story->type == 'requirement')
        {
            if(!$this->post->branches) $this->post->branches = isset($story->branch) ? array($story->branch) : array(0 => 0);
            if(!$this->post->modules or $product->type == 'normal')  $this->post->modules  = isset($story->module) ? array($story->module) : array(0 => 0);
            if(!$this->post->plans or $product->type == 'normal') $this->post->plans = isset($story->plan) ? array($story->plan) : array(0 => 0);
        }

        /* check module */
        $requiredFields = "," . $this->config->story->create->requiredFields . ",";
        if(strpos($requiredFields, ',module,') !== false)
        {
            foreach($this->post->modules as $module)
            {
                if(empty($module))
                {
                    dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->story->module);
                    return false;
                }
            }
        }

        if(strpos($requiredFields, ',plan,') !== false)
        {
            /* Create a project with no execution, remove plan required check. */
            $project = $this->dao->findById((int)$executionID)->from(TABLE_PROJECT)->fetch();
            if(!empty($project->project)) $project = $this->dao->findById((int)$project->project)->from(TABLE_PROJECT)->fetch();

            if(empty($project->hasProduct))
            {
                if($project->model !== 'scrum' or !$project->multiple) $requiredFields = str_replace(',plan,', ',', $requiredFields);
            }
        }

        $storyIds    = array();
        $storyFile   = array();
        $mainStoryID = 0;
        foreach($this->post->branches as $key => $branch)
        {
            $story->branch = $branch ? $branch : 0;
            $story->module = $this->post->modules[$key];
            $story->plan   = $this->post->plans[$key];

            if(strpos('draft,reviewing', $story->status) !== false) $story->stage = $story->plan > 0 ? 'planned' : 'wait';
            if($story->type == 'requirement') $requiredFields = str_replace(',plan,', ',', $requiredFields);
            if(strpos($requiredFields, ',estimate,') !== false)
            {
                if(!$story->estimate or strlen(trim($story->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->story->estimate);
                $requiredFields = str_replace(',estimate,', ',', $requiredFields);
            }

            $requiredFields = trim($requiredFields, ',');

            /* If in ipd mode, set requirement status = 'launched'. */
            if($this->config->systemMode == 'PLM' and $story->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
            if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

            $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify')
                ->autoCheck()
                ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
                ->batchCheck($requiredFields, 'notempty')
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            if(!dao::isError())
            {
                $storyID = $this->dao->lastInsertID();

                /* Fix bug #21992, user story have no parent story. */
                if(isset($story->parent) and $story->parent)
                {
                    $stories = array($storyID);
                    $this->subdivide($story->parent, $stories);
                }

                if(!empty($story->plan))
                {
                    $this->updateStoryOrderOfPlan($storyID, $story->plan); // Set story order in this plan.
                    $this->loadModel('action')->create('productplan', $story->plan, 'linkstory', '', $storyID);
                }

                $this->file->updateObjectID($this->post->uid, $storyID, $story->type);
                $files = $this->file->saveUpload($story->type, $storyID, 1);
                if(empty($files) && defined('RUN_MODE') && RUN_MODE == 'api' && !empty($_SESSION['album']['used'][$this->post->uid])) $files = $_SESSION['album']['used'][$this->post->uid];
                /* Multi branch sync files */
                !empty($files) ? $storyFile = $files : $files = $storyFile;

                $data          = new stdclass();
                $data->story   = $storyID;
                $data->version = 1;
                $data->title   = $story->title;
                $data->spec    = $story->spec;
                $data->verify  = $story->verify;
                $data->files   = join(',', array_keys($files));
                $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

                /* Save the story reviewer to storyreview table. */
                if(isset($_POST['reviewer']))
                {
                    foreach($this->post->reviewer as $reviewer)
                    {
                        if(empty($reviewer)) continue;

                        $reviewData = new stdclass();
                        $reviewData->story    = $storyID;
                        $reviewData->version  = 1;
                        $reviewData->reviewer = $reviewer;
                        $reviewData->result   = '';
                        $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                    }
                }

                /* Project or execution linked story. */
                if($executionID != 0)
                {
                    $this->linkStory($executionID, $this->post->product, $storyID);
                    if(in_array($this->config->systemMode, array('ALM', 'PLM')) and $executionID != $this->session->project) $this->linkStory($this->session->project, $this->post->product, $storyID);

                    $this->loadModel('kanban');

                    $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
                    if(isset($_POST['lane'])) $laneID = $_POST['lane'];

                    $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'backlog');
                    if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

                    if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'story', $storyID);
                    if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($executionID, 'story');
                }

                if(is_array($this->post->URS))
                {
                    foreach($this->post->URS as $URID)
                    {
                        $requirement = $this->getByID($URID);
                        $data = new stdclass();
                        $data->product  = $story->product;
                        $data->AType    = 'requirement';
                        $data->relation = 'subdivideinto';
                        $data->BType    = 'story';
                        $data->AID      = $URID;
                        $data->BID      = $storyID;
                        $data->AVersion = $requirement->version;
                        $data->BVersion = 1;
                        $data->extra    = 1;

                        $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

                        $data->AType    = 'story';
                        $data->relation = 'subdividedfrom';
                        $data->BType    = 'requirement';
                        $data->AID      = $storyID;
                        $data->BID      = $URID;
                        $data->AVersion = 1;
                        $data->BVersion = $requirement->version;

                        $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();
                    }
                }

                if($bugID > 0)
                {
                    if($this->config->edition != 'open') $oldBug = $this->dao->select('feedback, status')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();

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

                    if($this->config->edition != 'open' && !dao::isError() && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, 'closed', $oldBug->status);

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
                            $file->objectID   = $storyID;
                            unset($file->id);
                            $this->dao->insert(TABLE_FILE)->data($file)->exec();
                        }
                    }
                }
                if(!defined('TUTORIAL')) $this->setStage($storyID);
                if(!dao::isError()) $this->loadModel('score')->create('story', 'create',$storyID);

                /* Callback the callable method to process the related data for object that is transfered to story. */
                if($from && is_callable(array($this, $this->config->story->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->story->fromObjects[$from]['callback']), $storyID);

                $storyIds[] = $storyID;
                if(empty($mainStoryID)) $mainStoryID = $storyID;
            }
        }

        /* bind twins story id */
        if(count($storyIds) > 1)
        {
            foreach($storyIds as $twinsStoryID)
            {
                $twinsArr = array();
                foreach($storyIds as $idItem)
                {
                    if($idItem != $twinsStoryID) $twinsArr[] = $idItem;
                    $twins = ',' . implode(',', $twinsArr) . ',';
                    $this->dao->update(TABLE_STORY)->set('twins')->eq($twins)->where('id')->eq($twinsStoryID)->exec();
                }
            }
        }

        return array('status' => 'created', 'id' => $mainStoryID, 'ids' => $storyIds);
    }

    /**
     * Create story from gitlab issue.
     *
     * @param  object    $story
     * @param  int       $executionID
     * @access public
     * @return int
     */
    public function createStoryFromGitlabIssue($story, $executionID)
    {
        $story->status       = 'active';
        $story->stage        = 'projected';
        $story->openedBy     = $this->app->user->account;
        $story->version      = 1;
        $story->pri          = 3;
        $story->assignedDate = isset($story->assignedTo) ? helper::now() : 0;

        if(isset($story->execution)) unset($story->execution);

        $requiredFields = $this->config->story->create->requiredFields;
        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify,gitlab,gitlabProject')->autoCheck()->batchCheck($requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $storyID = $this->dao->lastInsertID();

            $data          = new stdclass();
            $data->story   = $storyID;
            $data->version = 1;
            $data->title   = $story->title;
            $data->spec    = $story->spec;
            $data->verify  = $story->spec;
            $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

            /* Link story to execution. */
            $this->linkStory($executionID, $story->product, $storyID);

            return $storyID;
        }

        return false;
    }

    /**
     * Batch create stories.
     *
     * @access public
     * @return int|bool the id of the created story or false when error.
     * @return type requirement|story
     */
    public function batchCreate($productID = 0, $branch = 0, $type = 'story')
    {
        $forceReview = $this->checkForceReview();

        $this->loadModel('action');
        $branch    = (int)$branch;
        $productID = (int)$productID;
        $now       = helper::now();
        $mails     = array();
        $stories   = fixer::input('post')->get();

        $saveDraft = false;
        if(isset($stories->status))
        {
            if($stories->status == 'draft') $saveDraft = true;
            unset($stories->status);
        }

        $result  = $this->loadModel('common')->removeDuplicate('story', $stories, "product={$productID}");
        $stories = $result['data'];

        $module = 0;
        $plan   = '';
        $pri    = 0;
        $source = '';

        foreach($stories->title as $i => $title)
        {
            if(empty($title) and $this->common->checkValidRow('story', $stories, $i))
            {
                dao::$errors["title$i"][] = sprintf($this->lang->error->notempty, $this->lang->story->title);
            }

            $module = $stories->module[$i] == 'ditto' ? $module : $stories->module[$i];
            $plan   = isset($stories->plan[$i]) ? ($stories->plan[$i] == 'ditto' ? $plan : $stories->plan[$i]) : '';
            $pri    = $stories->pri[$i]    == 'ditto' ? $pri    : $stories->pri[$i];
            $source = $stories->source[$i] == 'ditto' ? $source : $stories->source[$i];
            $stories->module[$i] = (int)$module;
            $stories->plan[$i]   = $plan;
            $stories->pri[$i]    = (int)$pri;
            $stories->source[$i] = $source;
        }

        if(isset($stories->uploadImage)) $this->loadModel('file');

        $extendFields = $this->getFlowExtendFields();
        $data         = array();
        $reviewers    = '';
        foreach($stories->title as $i => $title)
        {
            if(empty($title)) continue;

            $stories->reviewer[$i] = is_array($stories->reviewer[$i]) ? array_filter($stories->reviewer[$i]) : array();
            if(empty($stories->reviewer[$i]) and empty($stories->reviewerDitto[$i])) $stories->reviewer[$i] = array();
            $reviewers = (isset($stories->reviewDitto[$i])) ? $reviewers : $stories->reviewer[$i];
            $stories->reviewer[$i] = $reviewers;
            $_POST['reviewer'][$i] = $reviewers;
            if(empty($stories->reviewer[$i]) and $forceReview)
            {
                dao::$errors["reviewer$i"][] = $this->lang->story->errorEmptyReviewedBy;
            }

            $story = new stdclass();
            $story->type       = $type;
            $story->branch     = isset($stories->branch[$i]) ? $stories->branch[$i] : 0;
            $story->module     = $stories->module[$i];
            $story->plan       = $stories->plan[$i];
            $story->color      = $stories->color[$i];
            $story->title      = $stories->title[$i];
            $story->source     = $stories->source[$i];
            $story->category   = $stories->category[$i];
            $story->pri        = $stories->pri[$i];
            $story->estimate   = $stories->estimate[$i] ? $stories->estimate[$i] : '';
            $story->spec       = $stories->spec[$i];
            $story->verify     = $stories->verify[$i];
            $story->status     = $saveDraft ? 'draft' : ((empty($stories->reviewer[$i]) and !$forceReview) ? 'active' : 'reviewing');
            $story->stage      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? 'projected' : 'wait';
            $story->keywords   = $stories->keywords[$i];
            $story->sourceNote = $stories->sourceNote[$i];
            $story->product    = $productID;
            $story->openedBy   = $this->app->user->account;
            $story->vision     = $this->config->vision;
            $story->openedDate = $now;
            $story->version    = 1;

            foreach($extendFields as $extendField)
            {
                $story->{$extendField->field} = $this->post->{$extendField->field}[$i];
                if(is_array($story->{$extendField->field})) $story->{$extendField->field} = join(',', $story->{$extendField->field});

                $story->{$extendField->field} = htmlSpecialString($story->{$extendField->field});
            }

            foreach(explode(',', $this->config->story->create->requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;
                if($type == 'requirement' and $field == 'plan') continue;

                if(!isset($story->$field)) continue;
                if(!empty($story->$field)) continue;
                if($field == 'estimate' and strlen(trim($story->estimate)) != 0) continue;

                dao::$errors["{$field}$i"][] = sprintf($this->lang->error->notempty, $this->lang->story->$field);
            }
            $data[$i] = $story;
        }

        $link2Plans = array();
        foreach($data as $i => $story)
        {
            /* If in ipd mode, set requirement status = 'launched'. */
            if($this->config->systemMode == 'PLM' and $type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
            if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

            $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify')->autoCheck()->checkFlow()->exec();
            if(!dao::isError())
            {
                $storyID = $this->dao->lastInsertID();
                $this->setStage($storyID);

                /* Update product plan stories order. */
                if($story->plan)
                {
                    $this->updateStoryOrderOfPlan($storyID, $story->plan);
                    $link2Plans[$story->plan] = empty($link2Plans[$story->plan]) ? $storyID : "{$link2Plans[$story->plan]},$storyID";
                }

                $specData = new stdclass();
                $specData->story   = $storyID;
                $specData->version = 1;
                $specData->title   = $stories->title[$i];
                $specData->spec    = '';
                $specData->verify  = '';
                if(!empty($stories->spec[$i]))  $specData->spec   = nl2br($stories->spec[$i]);
                if(!empty($stories->verify[$i]))$specData->verify = nl2br($stories->verify[$i]);

                if(!empty($stories->uploadImage[$i]) and $stories->uploadImage[$i] !== 'undefined')
                {
                    $fileName = $stories->uploadImage[$i];
                    $file     = $this->session->storyImagesFile[$fileName];

                    $realPath = $file['realpath'];
                    unset($file['realpath']);

                    if(!is_dir($this->file->savePath)) mkdir($this->file->savePath, 0777, true);
                    if($realPath and rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
                    {
                        $file['addedBy']    = $this->app->user->account;
                        $file['addedDate']  = $now;
                        $file['objectType'] = 'story';
                        $file['objectID']   = $storyID;
                        if(in_array($file['extension'], $this->config->file->imageExtensions))
                        {
                            $file['extra'] = 'editor';
                            $this->dao->insert(TABLE_FILE)->data($file)->exec();

                            $fileID = $this->dao->lastInsertID();
                            $specData->spec .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
                        }
                        else
                        {
                            $this->dao->insert(TABLE_FILE)->data($file)->exec();
                        }
                    }
                }

                $this->dao->insert(TABLE_STORYSPEC)->data($specData)->exec();

                /* Save the story reviewer to storyreview table. */
                foreach($_POST['reviewer'][$i] as $reviewer)
                {
                    if(empty($reviewer)) continue;

                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = 1;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                }

                $this->executeHooks($storyID);

                $actionID = $this->action->create('story', $storyID, 'Opened', '');
                if(!dao::isError()) $this->loadModel('score')->create('story', 'create',$storyID);
                $mails[$i] = new stdclass();
                $mails[$i]->storyID  = $storyID;
                $mails[$i]->actionID = $actionID;
            }

        }

        if(!dao::isError())
        {
            /* Remove upload image file and session. */
            if(!empty($stories->uploadImage) and $this->session->storyImagesFile)
            {
                $classFile = $this->app->loadClass('zfile');
                $file = current($_SESSION['storyImagesFile']);
                $realPath = dirname($file['realpath']);
                if(is_dir($realPath)) $classFile->removeDir($realPath);
                unset($_SESSION['storyImagesFile']);
            }

            $this->loadModel('score')->create('ajax', 'batchCreate');
            foreach($link2Plans as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);
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
        $oldStory    = $this->getById($storyID);

        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        if(strpos($this->config->story->change->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors[] = $this->lang->story->errorEmptyReviewedBy;
            return false;
        }

        $story = fixer::input('post')->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)->get();

        $oldStoryReviewers  = $this->getReviewerPairs($storyID, $oldStory->version);
        $_POST['reviewer']  = isset($_POST['reviewer']) ? $_POST['reviewer'] : array();
        $reviewerHasChanged = (array_diff(array_keys($oldStoryReviewers), $_POST['reviewer']) or array_diff($_POST['reviewer'], array_keys($oldStoryReviewers)));
        if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or $this->loadModel('file')->getCount() or $reviewerHasChanged or isset($story->deleteFiles)) $specChanged = true;

        $now   = helper::now();
        $story = fixer::input('post')
            ->callFunc('title', 'trim')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('deleteFiles', array())
            ->add('id', $storyID)
            ->add('lastEditedDate', $now)
            ->setIF($specChanged, 'version', (int)$oldStory->version + 1)
            ->setIF($specChanged, 'reviewedBy', '')
            ->setIF($specChanged, 'changedBy', $this->app->user->account)
            ->setIF($specChanged, 'changedDate', $now)
            ->setIF($specChanged, 'closedBy', '')
            ->setIF($specChanged, 'closedReason', '')
            ->setIF($specChanged and $oldStory->reviewedBy, 'reviewedDate', '0000-00-00')
            ->setIF($specChanged and $oldStory->closedBy, 'closedDate', '0000-00-00')
            ->setIF(!$specChanged, 'status', $oldStory->status)
            ->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)
            ->remove('files,labels,reviewer,comment,needNotReview,uid')
            ->get();

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
        if(isset($story->status) and $story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->change['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story, 'spec,verify,deleteFiles,relievedTwins')
            ->autoCheck()
            ->batchCheck($this->config->story->change->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq((int)$storyID)->exec();

        if(!dao::isError())
        {
            if($specChanged)
            {
                $this->file->updateObjectID($this->post->uid, $storyID, 'story');
                $addedFiles = $this->file->saveUpload($oldStory->type, $storyID, $story->version);
                $addedFiles = empty($addedFiles) ? '' : join(',', array_keys($addedFiles)) . ',';
                $storyFiles = $oldStory->files = join(',', array_keys($oldStory->files));
                foreach($story->deleteFiles as $fileID) $storyFiles = str_replace(",$fileID,", ',', ",$storyFiles,");

                $data          = new stdclass();
                $data->story   = $storyID;
                $data->version = $story->version;
                $data->title   = $story->title;
                $data->spec    = $story->spec;
                $data->verify  = $story->verify;
                $data->files   = $story->files = $addedFiles . trim($storyFiles, ',');
                $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

                /* Sync twins. */
                if(!isset($story->relievedTwins) and !empty($oldStory->twins))
                {
                    foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                    {
                        $data->story = $twinID;
                        $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();
                    }
                }

                /* IF is story and has changed, update its relation version to new. */
                if($oldStory->type == 'story')
                {
                    $newStory = $this->getById($storyID);
                    $this->dao->update(TABLE_STORY)->set('URChanged')->eq(0)->where('id')->eq($oldStory->id)->exec();
                    $this->updateStoryVersion($newStory);
                }
                else
                {
                    /* IF is requirement changed, notify its relation. */
                    $relations = $this->dao->select('BID')->from(TABLE_RELATION)
                        ->where('AType')->eq('requirement')
                        ->andWhere('BType')->eq('story')
                        ->andWhere('relation')->eq('subdivideinto')
                        ->andWhere('AID')->eq($storyID)
                        ->fetchPairs();

                    foreach($relations as $relationID) $this->dao->update(TABLE_STORY)->set('URChanged')->eq(1)->where('id')->eq($relationID)->exec();
                }

                /* Update the reviewer. */
                foreach($_POST['reviewer'] as $reviewer)
                {
                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = $story->version;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                    /* Sync twins. */
                    if(!isset($story->relievedTwins) and !empty($oldStory->twins))
                    {
                        foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                        {
                            $reviewData->story = $twinID;
                            $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                        }
                    }
                }

                if($reviewerHasChanged)
                {
                    $oldStory->reviewers = implode(',', array_keys($oldStoryReviewers));
                    $story->reviewers    = implode(',', $_POST['reviewer']);
                }
            }

            $changes = common::createChanges($oldStory, $story);

            if(isset($story->relievedTwins))
            {
                $this->dbh->exec("UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins, ',$storyID,', ',') WHERE `product` = $oldStory->product");
                $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();
                if(!dao::isError()) $this->loadModel('action')->create('story', $storyID, 'relieved');
            }
            elseif(!empty($oldStory->twins))
            {
                $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Changed');
            }

            return $changes;
        }
    }

    /**
     * Update a story.
     *
     * @param  int    $storyID
     * @access public
     * @return array  the changes of the story.
     */
    public function update($storyID)
    {
        $now      = helper::now();
        $oldStory = $this->getById($storyID);

        if($oldStory->status == 'draft' or $oldStory->status == 'changing')
        {
            if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
            if(!$this->post->needNotReview and empty($_POST['reviewer']))
            {
                dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewers);
                return false;
            }
        }

        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        if(strpos('draft,changing', $oldStory->status) !== false and $this->checkForceReview() and empty($_POST['reviewer']))
        {
            dao::$errors[] = $this->lang->story->notice->reviewerNotEmpty;
            return false;
        }

        $storyPlan = array();
        if(!empty($_POST['plan'])) $storyPlan = is_array($_POST['plan']) ? array_filter($_POST['plan']) : array($_POST['plan']);
        if(count($storyPlan) > 1)
        {
            $oldStoryPlan  = !empty($oldStory->planTitle) ? array_keys($oldStory->planTitle) : array();
            $oldPlanDiff   = array_diff($storyPlan, $oldStoryPlan);
            $storyPlanDiff = array_diff($oldStoryPlan, $storyPlan);
            if(!empty($oldPlanDiff) or !empty($storyPlanDiff))
            {
                dao::$errors[] = $this->lang->story->notice->changePlan;
                return false;
            }
        }

        if($this->config->vision != 'or')
        {
            /* Unchanged product when editing requirements on site. */
            $hasProduct = $this->dao->select('t2.hasProduct')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.product')->eq($oldStory->product)
                ->andWhere('t2.deleted')->eq(0)
                ->fetch('hasProduct');
            $_POST['product'] = (!empty($hasProduct) && !$hasProduct) ? $oldStory->product : $this->post->product;
        }

        $story = fixer::input('post')
            ->cleanInt('product,module,pri,duplicateStory')
            ->cleanFloat('estimate')
            ->setDefault('assignedDate', $oldStory->assignedDate)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('reviewedBy', $oldStory->reviewedBy)
            ->setDefault('mailto', '')
            ->setDefault('deleteFiles', array())
            ->add('id', $storyID)
            ->add('lastEditedDate', $now)
            ->setDefault('plan,notifyEmail', '')
            ->setDefault('product', $oldStory->product)
            ->setDefault('branch', $oldStory->branch)
            ->setIF(!$this->post->linkStories, 'linkStories', '')
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF($this->post->stage == 'released', 'releasedDate', $now)
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF(!empty($_POST['plan'][0]) and $oldStory->stage == 'wait', 'stage', 'planned')
            ->setIF(!isset($_POST['title']), 'title', $oldStory->title)
            ->setIF(!isset($_POST['spec']), 'spec', $oldStory->spec)
            ->setIF(!isset($_POST['verify']), 'verify', $oldStory->verify)
            ->stripTags($this->config->story->editor->edit['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->join('linkStories', ',')
            ->join('linkRequirements', ',')
            ->join('childStories', ',')
            ->remove('files,labels,comment,contactListMenu,reviewer,needNotReview')
            ->get();

        /* Relieve twins when change product. */
        if(!empty($oldStory->twins) and $story->product != $oldStory->product)
        {
            $this->dbh->exec("UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins, ',$storyID,', ',') WHERE `product` = $oldStory->product");
            $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();
            $oldStory->twins = '';
        }

        if($oldStory->type == 'story' and !isset($story->linkStories)) $story->linkStories = '';
        if($oldStory->type == 'requirement' and !isset($story->linkRequirements)) $story->linkRequirements = '';
        if($oldStory->status == 'changing' and $story->status == 'draft') $story->status = 'changing';

        if(isset($story->plan) and is_array($story->plan)) $story->plan = trim(join(',', $story->plan), ',');
        if(isset($_POST['branch']) and $_POST['branch'] == 0) $story->branch = 0;

        if(isset($story->stage) and $oldStory->stage != $story->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->edit['id'], $this->post->uid);

        if(isset($_POST['reviewer']) or isset($_POST['needNotReview']))
        {
            $_POST['reviewer'] = isset($_POST['needNotReview']) ? array() : array_filter($_POST['reviewer']);
            $oldReviewer       = $this->getReviewerPairs($storyID, $oldStory->version);

            /* Update story reviewer. */
            $this->dao->delete()->from(TABLE_STORYREVIEW)
                ->where('story')->eq($storyID)
                ->andWhere('version')->eq($oldStory->version)
                ->beginIF($oldStory->status == 'reviewing')->andWhere('reviewer')->notin(implode(',', $_POST['reviewer']))
                ->exec();

            /* Sync twins. */
            if(!empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->dao->delete()->from(TABLE_STORYREVIEW)
                        ->where('story')->eq($twinID)
                        ->andWhere('version')->eq($oldStory->version)
                        ->beginIF($oldStory->status == 'reviewing')->andWhere('reviewer')->notin(implode(',', $_POST['reviewer']))
                        ->exec();
                }
            }

            foreach($_POST['reviewer'] as $reviewer)
            {
                if($oldStory->status == 'reviewing' and in_array($reviewer, array_keys($oldReviewer))) continue;

                $reviewData = new stdclass();
                $reviewData->story    = $storyID;
                $reviewData->version  = $oldStory->version;
                $reviewData->reviewer = $reviewer;
                $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                /* Sync twins. */
                if(!empty($oldStory->twins))
                {
                    foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                    {
                        $reviewData->story = $twinID;
                        $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                    }
                }
            }

            if($oldStory->status == 'reviewing') $story = $this->updateStoryByReview($storyID, $oldStory, $story);
            if(strpos('draft,changing', $oldStory->status) != false) $story->reviewedBy = '';

            $oldStory->reviewers = implode(',', array_keys($oldReviewer));
            $story->reviewers    = implode(',', array_keys($this->getReviewerPairs($storyID, $oldStory->version)));
        }

        $this->dao->update(TABLE_STORY)
            ->data($story, 'reviewers,spec,verify,finalResult,deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->story->edit->requiredFields, 'notempty')
            ->checkIF(isset($story->closedBy), 'closedReason', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->where('id')->eq((int)$storyID)->exec();
        if(dao::isError()) return false;

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $storyID, 'story');
            $addedFiles = $this->file->saveUpload($oldStory->type, $storyID, $oldStory->version);

            if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or !empty($story->deleteFiles) or !empty($addedFiles))
            {
                $addedFiles = empty($addedFiles) ? '' : join(',', array_keys($addedFiles)) . ',';
                $storyFiles = $oldStory->files = join(',', array_keys($oldStory->files));
                foreach($story->deleteFiles as $fileID) $storyFiles = str_replace(",$fileID,", ',', ",$storyFiles,");

                $data = new stdclass();
                $data->title  = $story->title;
                $data->spec   = $story->spec;
                $data->verify = $story->verify;
                $data->files  = $story->files = $addedFiles . trim($storyFiles, ',');
                $this->dao->update(TABLE_STORYSPEC)->data($data)->where('story')->eq((int)$storyID)->andWhere('version')->eq($oldStory->version)->exec();

                /* Sync twins. */
                if(!empty($oldStory->twins))
                {
                    foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                    {
                        $this->dao->update(TABLE_STORYSPEC)->data($data)
                            ->where('story')->eq((int)$twinID)
                            ->andWhere('version')->eq($oldStory->version)
                            ->exec();
                    }
                }
            }

            if($story->product != $oldStory->product)
            {
                $this->updateStoryProduct($storyID, $story->product);
                if($oldStory->parent == '-1')
                {
                    $childStories = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchPairs('id');
                    foreach($childStories as $childStoryID) $this->updateStoryProduct($childStoryID, $story->product);
                }
            }

            $this->loadModel('action');

            if($story->plan != $oldStory->plan)
            {
                if(!empty($oldStory->plan)) $this->action->create('productplan', $oldStory->plan, 'unlinkstory', '', $storyID);
                if(!empty($story->plan)) $this->action->create('productplan', $story->plan, 'linkstory', '', $storyID);
            }

            $changed = $story->parent != $oldStory->parent;
            if($oldStory->parent > 0)
            {
                $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($oldStory->parent)->fetch();
                $this->updateParentStatus($storyID, $oldStory->parent, !$changed);

                if($changed)
                {
                    $oldChildren = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($oldStory->parent)->andWhere('deleted')->eq(0)->fetchPairs('id', 'id');
                    if(empty($oldChildren)) $this->dao->update(TABLE_STORY)->set('parent')->eq(0)->where('id')->eq($oldStory->parent)->exec();
                    $this->dao->update(TABLE_STORY)->set('childStories')->eq(join(',', $oldChildren))->set('lastEditedBy')->eq($this->app->user->account)->set('lastEditedDate')->eq(helper::now())->where('id')->eq($oldStory->parent)->exec();
                    $this->action->create('story', $storyID, 'unlinkParentStory', '', $oldStory->parent, '', false);

                    $actionID = $this->action->create('story', $oldStory->parent, 'unLinkChildrenStory', '', $storyID, '', false);

                    $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($oldStory->parent)->fetch();
                    $changes = common::createChanges($oldParentStory, $newParentStory);
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }

            if($story->parent > 0)
            {
                $parentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch();
                $this->dao->update(TABLE_STORY)->set('parent')->eq(-1)->where('id')->eq($story->parent)->exec();
                $this->updateParentStatus($storyID, $story->parent, !$changed);

                if($changed)
                {
                    $children = $this->dao->select('id')->from(TABLE_STORY)->where('parent')->eq($story->parent)->andWhere('deleted')->eq(0)->fetchPairs('id', 'id');
                    $this->dao->update(TABLE_STORY)
                        ->set('parent')->eq('-1')
                        ->set('childStories')->eq(join(',', $children))
                        ->set('lastEditedBy')->eq($this->app->user->account)
                        ->set('lastEditedDate')->eq(helper::now())
                        ->where('id')->eq($story->parent)
                        ->exec();

                    $this->action->create('story', $storyID, 'linkParentStory', '', $story->parent, '', false);
                    $actionID = $this->action->create('story', $story->parent, 'linkChildStory', '', $storyID, '', false);

                    $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch();
                    $changes = common::createChanges($parentStory, $newParentStory);
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }

            if(isset($story->closedReason) and $story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');

            /* Set new stage and update story sort of plan when story plan has changed. */
            if($oldStory->plan != $story->plan)
            {
                $this->updateStoryOrderOfPlan($storyID, $story->plan, $oldStory->plan); // Insert a new story sort in this plan.

                if(empty($oldStory->plan) or empty($story->plan)) $this->setStage($storyID); // Set new stage for this story.
            }

            if(isset($story->stage) and $oldStory->stage != $story->stage)
            {
                $executionIdList = $this->dao->select('t1.project')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                    ->where('t1.story')->eq($storyID)
                    ->andWhere('t2.deleted')->eq(0)
                    ->andWhere('t2.type')->in('sprint,stage,kanban')
                    ->fetchPairs();

                $this->loadModel('kanban');
                foreach($executionIdList as $executionID) $this->kanban->updateLane($executionID, 'story', $storyID);
            }

            unset($oldStory->parent);
            unset($story->parent);
            if($this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);

            $linkStoryField = $oldStory->type == 'story' ? 'linkStories' : 'linkRequirements';
            $linkStories    = explode(',', $story->{$linkStoryField});
            $oldLinkStories = explode(',', $oldStory->{$linkStoryField});
            $addStories     = array_diff($linkStories, $oldLinkStories);
            $removeStories  = array_diff($oldLinkStories, $linkStories);
            $changeStories  = array_merge($addStories, $removeStories);
            $changeStories  = $this->dao->select("id,$linkStoryField")->from(TABLE_STORY)->where('id')->in(array_filter($changeStories))->fetchPairs();
            foreach($changeStories as $changeStoryID => $changeStory)
            {
                if(in_array($changeStoryID, $addStories))
                {
                    $stories = empty($changeStory) ? $storyID : $changeStory . ',' . $storyID;
                    $this->dao->update(TABLE_STORY)->set($linkStoryField)->eq($stories)->where('id')->eq((int)$changeStoryID)->exec();
                }

                if(in_array($changeStoryID, $removeStories))
                {
                    $linkStories = str_replace(",$storyID,", ',', ",$changeStory,");
                    $linkStories = trim($linkStories, ',');
                    $this->dao->update(TABLE_STORY)->set($linkStoryField)->eq(implode(',', $linkStories))->where('id')->eq((int)$changeStoryID)->exec();
                }
            }

            $changes = common::createChanges($oldStory, $story);
            if($this->post->uid != '' and isset($_SESSION['album']['used'][$this->post->uid])) $files = $this->file->getPairs($_SESSION['album']['used'][$this->post->uid]);

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                if(isset($story->finalResult)) $this->recordReviewAction($story);
            }

            if(!empty($oldStory->twins)) $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Edited');

            return true;
        }
    }

    /**
     * Update story product.
     *
     * @param  int    $storyID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function updateStoryProduct($storyID, $productID)
    {
        $this->dao->update(TABLE_STORY)->set('product')->eq($productID)->where('id')->eq($storyID)->exec();
        $this->dao->update(TABLE_PROJECTSTORY)->set('product')->eq($productID)->where('story')->eq($storyID)->exec();
        $storyProjects  = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->orderBy('project')->fetchPairs('project', 'project');
        $linkedProjects = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('project')->in($storyProjects)->andWhere('product')->eq($productID)->orderBy('project')->fetchPairs('project','project');
        $unlinkedProjects = array_diff($storyProjects, $linkedProjects);
        foreach($unlinkedProjects as $projectID)
        {
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $productID;
            $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
        }
    }

    /**
     * Update parent status.
     *
     * @param  int    $storyID
     * @param  int    $parentID
     * @param  bool   $createAction
     * @access public
     * @return mixed
     */
    public function updateParentStatus($storyID, $parentID = 0, $createAction = true)
    {
        $childStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(empty($parentID)) $parentID = $childStory->parent;
        if($parentID <= 0) return true;

        $oldParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->andWhere('deleted')->eq(0)->fetch();
        if(empty($oldParentStory)) return $this->dao->update(TABLE_STORY)->set('parent')->eq('0')->where('id')->eq($storyID)->exec();
        if($oldParentStory->parent != '-1') $this->dao->update(TABLE_STORY)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();
        $this->computeEstimate($parentID);

        $childrenStatus = $this->dao->select('id,status')->from(TABLE_STORY)->where('parent')->eq($parentID)->andWhere('deleted')->eq(0)->fetchPairs('status', 'status');
        if(empty($childrenStatus)) return $this->dao->update(TABLE_STORY)->set('parent')->eq('0')->where('id')->eq($parentID)->exec();

        $status = $oldParentStory->status;
        if(count($childrenStatus) == 1 and current($childrenStatus) == 'closed') $status = current($childrenStatus); // Close parent story.
        if($oldParentStory->status == 'closed') $status = $this->getActivateStatus($parentID); // Activate parent story.

        if($status and $oldParentStory->status != $status)
        {
            $now  = helper::now();
            $story = new stdclass();
            $story->status = $status;
            $story->stage  = 'wait';
            if(strpos('launched,active,changing,draft', $status) !== false)
            {
                $story->assignedTo   = $oldParentStory->openedBy;
                $story->assignedDate = $now;
                $story->closedBy     = '';
                $story->closedReason = '';
                $story->closedDate   = '0000-00-00';
                $story->reviewedBy   = '';
                $story->reviewedDate = '0000-00-00';
            }

            if($status == 'closed')
            {
                $story->assignedTo   = 'closed';
                $story->assignedDate = $now;
                $story->closedBy     = $this->app->user->account;
                $story->closedDate   = $now;
                $story->closedReason = 'done';
                $story->closedReason = 'done';
            }

            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->parent         = '-1';
            $this->dao->update(TABLE_STORY)->data($story)->where('id')->eq($parentID)->exec();
            if(!dao::isError())
            {
                if(!$createAction) return $story;

                $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->fetch();
                $changes   = common::createChanges($oldParentStory, $newParentStory);
                $action    = '';
                $preStatus = '';
                if(strpos('launched,active,draft,changing', $status) !== false) $action = 'Activated';
                if($status == 'closed')
                {
                    /* Record the status before closed. */
                    $action    = 'closedbysystem';
                    $preStatus = $oldParentStory->status;
                    $isChanged = $oldParentStory->changedBy ? true : false;
                    if($preStatus == 'reviewing') $preStatus = $isChanged ? 'changing' : 'draft';
                }
                if($action)
                {
                    $actionID = $this->loadModel('action')->create('story', $parentID, $action, '', $preStatus, '', false);
                    $this->action->logHistory($actionID, $changes);
                }

                if($this->config->edition != 'open' && $oldParentStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldParentStory->feedback, $newParentStory->status, $oldParentStory->status);
            }
        }
        else
        {
            if(!dao::isError())
            {
                $newParentStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($parentID)->fetch();
                $changes = common::createChanges($oldParentStory, $newParentStory);
                if($changes)
                {
                    $actionID = $this->loadModel('action')->create('story', $parentID, 'Edited', '', '', '', false);
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
    }

    /**
     * If story changed, update relation table version filed.
     *
     * @param  object $story
     * @access public
     * @return void
     */
    public function updateStoryVersion($story)
    {
        $changedStories = $this->getChangedStories($story);

        if(!empty($changedStories))
        {
            foreach($changedStories as $changedStory)
            {
                $this->dao->update(TABLE_RELATION)
                    ->set('AVersion')->eq($changedStory->version)
                    ->where('AType')->eq('requirement')
                    ->andWhere('BType')->eq('story')
                    ->andWhere('relation')->eq('subdivideinto')
                    ->andWhere('AID')->eq($changedStory->id)
                    ->andWhere('BID')->eq($story->id)
                    ->exec();
            }
        }
    }

    /**
     * Update the story order of plan.
     *
     * @param  int    $storyID
     * @param  string $oldPlanIDList
     * @param  string $planIDList
     * @access public
     * @return void
     */
    public function updateStoryOrderOfPlan($storyID, $planIDList = '', $oldPlanIDList = '')
    {
        $planIDList    = $planIDList ? explode(',', $planIDList) : array();
        $oldPlanIDList = $oldPlanIDList ? explode(',', $oldPlanIDList) : array();

        /* Get the ids to be inserted and deleted by comparing plan ids. */
        $plansTobeInsert = array_diff($planIDList, $oldPlanIDList);
        $plansTobeDelete = array_diff($oldPlanIDList, $planIDList);

        /* Delete old story sort of plan. */
        if(!empty($plansTobeDelete)) $this->dao->delete()->from(TABLE_PLANSTORY)->where('story')->eq($storyID)->andWhere('plan')->in($plansTobeDelete)->exec();

        if(!empty($plansTobeInsert))
        {
            /* Get last story order of plan list. */
            $maxOrders = $this->dao->select('plan, max(`order`) as `order`')->from(TABLE_PLANSTORY)->where('plan')->in($plansTobeInsert)->groupBy('plan')->fetchPairs();

            foreach($plansTobeInsert as $planID)
            {
                /* Set story order in new plan. */
                $data = new stdClass();
                $data->plan  = $planID;
                $data->story = $storyID;
                $data->order = zget($maxOrders, $planID, 0) + 1;

                $this->dao->replace(TABLE_PLANSTORY)->data($data)->exec();
            }
        }
    }

    /**
     * Compute parent story estimate.
     *
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function computeEstimate($storyID)
    {
        if(!$storyID) return true;

        $stories = $this->dao->select('`id`,`estimate`,status')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll('id');
        if(empty($stories)) return true;

        $estimate = 0;
        foreach($stories as $story) $estimate += $story->estimate;
        $this->dao->update(TABLE_STORY)->set('estimate')->eq($estimate)->autoCheck()->where('id')->eq($storyID)->exec();
        return !dao::isError();
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
        $storyIdList = $this->post->storyIdList ? $this->post->storyIdList : array();
        $unlinkPlans = array();
        $link2Plans  = array();

        /* Init $stories. */
        if(!empty($storyIdList))
        {
            $oldStories = $this->getByList($storyIdList);

            /* Process the data if the value is 'ditto'. */
            foreach($storyIdList as $storyID)
            {
                if($data->pris[$storyID]     == 'ditto') $data->pris[$storyID]     = isset($prev['pri'])    ? $prev['pri']    : 0;
                if(isset($data->branches) and $data->branches[$storyID] == 'ditto') $data->branches[$storyID] = isset($prev['branch']) ? $prev['branch'] : 0;
                if($data->modules[$storyID]  == 'ditto') $data->modules[$storyID]  = isset($prev['module']) ? $prev['module'] : 0;
                if($data->plans[$storyID]    == 'ditto') $data->plans[$storyID]    = isset($prev['plan'])   ? $prev['plan']   : '';
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

            $extendFields = $this->getFlowExtendFields();
            foreach($storyIdList as $storyID)
            {
                $oldStory = $oldStories[$storyID];

                $story                 = new stdclass();
                $story->id             = $storyID;
                $story->lastEditedBy   = $this->app->user->account;
                $story->lastEditedDate = $now;
                $story->status         = $oldStory->status;
                $story->color          = $data->colors[$storyID];
                $story->title          = $data->titles[$storyID];
                $story->estimate       = $data->estimates[$storyID];
                $story->category       = $data->category[$storyID];
                $story->pri            = $data->pris[$storyID];
                $story->assignedTo     = $data->assignedTo[$storyID];
                $story->assignedDate   = $oldStory == $data->assignedTo[$storyID] ? $oldStory->assignedDate : $now;
                $story->branch         = isset($data->branches[$storyID]) ? $data->branches[$storyID] : 0;
                $story->module         = $data->modules[$storyID];
                $story->plan           = $oldStories[$storyID]->parent < 0 ? '' : $data->plans[$storyID];
                $story->source         = $data->sources[$storyID];
                $story->sourceNote     = $data->sourceNote[$storyID];
                $story->keywords       = $data->keywords[$storyID];
                $story->stage          = isset($data->stages[$storyID])             ? $data->stages[$storyID]             : $oldStory->stage;
                $story->closedBy       = isset($data->closedBys[$storyID])          ? $data->closedBys[$storyID]          : $oldStory->closedBy;
                $story->closedReason   = isset($data->closedReasons[$storyID])      ? $data->closedReasons[$storyID]      : $oldStory->closedReason;
                $story->duplicateStory = isset($data->duplicateStories[$storyID])   ? $data->duplicateStories[$storyID]   : $oldStory->duplicateStory;
                $story->childStories   = isset($data->childStoriesIDList[$storyID]) ? $data->childStoriesIDList[$storyID] : $oldStory->childStories;
                $story->version        = $story->title == $oldStory->title ? $oldStory->version : (int)$oldStory->version + 1;
                if($story->stage != $oldStory->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';

                if($story->title != $oldStory->title and $story->status != 'draft')  $story->status     = 'changing';
                if($story->closedBy     != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedReason != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedBy     != false  or  $story->closedReason != false) $story->status     = 'closed';
                if($story->closedReason != false  and $story->closedBy     == false) $story->closedBy   = $this->app->user->account;

                if($story->plan != $oldStory->plan)
                {
                    if($story->plan != $oldStory->plan and !empty($oldStory->plan)) $unlinkPlans[$oldStory->plan] = empty($unlinkPlans[$oldStory->plan]) ? $storyID : "{$unlinkPlans[$oldStory->plan]},$storyID";
                    if($story->plan != $oldStory->plan and !empty($story->plan))    $link2Plans[$story->plan]  = empty($link2Plans[$story->plan]) ? $storyID : "{$link2Plans[$story->plan]},$storyID";
                }


                foreach($extendFields as $extendField)
                {
                    $story->{$extendField->field} = $this->post->{$extendField->field}[$storyID];
                    if(is_array($story->{$extendField->field})) $story->{$extendField->field} = join(',', $story->{$extendField->field});

                    $story->{$extendField->field} = htmlSpecialString($story->{$extendField->field});
                }

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
                    ->checkFlow()
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
                    /* Update story sort of plan when story plan has changed. */
                    if($oldStory->plan != $story->plan) $this->updateStoryOrderOfPlan($storyID, $story->plan, $oldStory->plan);

                    $this->executeHooks($storyID);
                    if($story->type == 'story') $this->batchChangeStage(array($storyID), $story->stage);
                    if($story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
                    $allChanges[$storyID] = common::createChanges($oldStory, $story);

                    if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
                    {
                        $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                        $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
                    }
                }
                else
                {
                    return print(js::error('story#' . $storyID . dao::getError(true)));
                }
            }
        }
        if(!dao::isError())
        {
            $this->loadModel('score')->create('ajax', 'batchEdit');

            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', $planID, 'unlinkstory', '', $stories);
            foreach($link2Plans as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);

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
        if(strpos($this->config->story->review->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        if($this->post->result == false)
        {
            dao::$errors[] = $this->lang->story->mustChooseResult;
            return false;
        }

        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $date     = helper::today();
        $story    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('status', $oldStory->status)
            ->setDefault('reviewedDate', $date)
            ->stripTags($this->config->story->editor->review['id'], $this->config->allowedTags)
            ->setIF(!$this->post->assignedTo, 'assignedTo', '')
            ->setIF(!empty($_POST['assignedTo']), 'assignedDate', $now)
            ->removeIF($this->post->result != 'reject', 'closedReason, duplicateStory, childStories')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'subdivided', 'childStories')
            ->add('reviewedBy', $oldStory->reviewedBy . ',' . $this->app->user->account)
            ->add('id', $storyID)
            ->remove('result,comment')
            ->get();

        $story->reviewedBy = implode(',', array_unique(explode(',', $story->reviewedBy)));
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->review['id'], $this->post->uid);

        /* Fix bug #671. */
        $this->lang->story->closedReason = $this->lang->story->rejectedReason;

        $this->dao->update(TABLE_STORYREVIEW)
            ->set('result')->eq($this->post->result)
            ->set('reviewDate')->eq($now)
            ->where('story')->eq($storyID)
            ->andWhere('version')->eq($oldStory->version)
            ->andWhere('reviewer')->eq($this->app->user->account)
            ->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->update(TABLE_STORYREVIEW)
                    ->set('result')->eq($this->post->result)
                    ->set('reviewDate')->eq($now)
                    ->where('story')->eq($twinID)
                    ->andWhere('version')->eq($oldStory->version)
                    ->andWhere('reviewer')->eq($this->app->user->account)
                    ->exec();
            }
        }

        $story = $this->updateStoryByReview($storyID, $oldStory, $story);

        $skipFields      = 'finalResult';
        $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');
        if($isSuperReviewer === false)
        {
            $reviewers = $this->getReviewerPairs($storyID, $oldStory->version);
            if(count($reviewers) > 1) $skipFields .= ',closedReason';
        }

        $this->dao->update(TABLE_STORY)->data($story, $skipFields)
            ->autoCheck()
            ->batchCheck($this->config->story->review->requiredFields, 'notempty')
            ->checkIF($this->post->result == 'reject', 'closedReason', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)
            ->exec();
        if(dao::isError()) return false;

        if($this->post->result != 'reject') $this->setStage($storyID);

        if(isset($story->closedReason) and $isSuperReviewer === false) unset($story->closedReason);
        $changes = common::createChanges($oldStory, $story);
        if($changes)
        {
            $actionID = $this->recordReviewAction($story, $this->post->result, $this->post->closedReason);
            $this->action->logHistory($actionID, $changes);
        }

        if(!empty($oldStory->twins)) $this->syncTwins($oldStory->id, $oldStory->twins, $changes, 'Reviewed');

        return true;
    }

    /**
     * Batch review stories.
     *
     * @param  array   $storyIdList
     * @param  string  $result
     * @param  string  $reason
     * @access public
     * @return array
     */
    public function batchReview($storyIdList, $result, $reason)
    {
        $now     = helper::now();
        $actions = array();
        $this->loadModel('action');

        $reviewedTwins = array();
        $oldStories       = $this->getByList($storyIdList);
        $hasResult        = $this->dao->select('story,version,result')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->andWhere('reviewer')->eq($this->app->user->account)->andWhere('result')->ne('')->orderBy('version')->fetchAll('story');
        $reviewerList     = $this->dao->select('story,reviewer,result,version')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->orderBy('version')->fetchGroup('story', 'reviewer');
        foreach($storyIdList as $storyID)
        {
            if(!$storyID) continue;

            $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');
            $oldStory        = $oldStories[$storyID];
            if($oldStory->status != 'reviewing') continue;

            foreach($reviewerList[$storyID] as $reviewer => $reviewerInfo)
            {
                if($reviewerInfo->version != $oldStory->version) unset($reviewerList[$storyID][$reviewer]);
            }

            if(!in_array($this->app->user->account, array_keys($reviewerList[$storyID])) and $isSuperReviewer === false) continue;
            if(isset($hasResult[$storyID]) and $hasResult[$storyID]->version == $oldStories[$storyID]->version) continue;
            if($oldStory->version > 1 and $result == 'reject') continue;

            $story = new stdClass();
            $story->reviewedDate   = $now;
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->status         = $oldStory->status;

            $reviewedBy        = array_unique(explode(',', $oldStory->reviewedBy . ',' . $this->app->user->account));
            $story->reviewedBy = implode(',', $reviewedBy);

            $this->dao->update(TABLE_STORYREVIEW)->set('result')->eq($result)->set('reviewDate')->eq($now)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->andWhere('reviewer')->eq($this->app->user->account)->exec();

            /* Sync twins. */
            if(!empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->dao->update(TABLE_STORYREVIEW)
                        ->set('result')->eq($result)
                        ->set('reviewDate')->eq($now)
                        ->where('story')->eq($twinID)
                        ->andWhere('version')->eq($oldStory->version)
                        ->andWhere('reviewer')->eq($this->app->user->account)
                        ->exec();
                }
            }

            /* Update the story status by review rules. */
            $reviewedBy = explode(',', trim($story->reviewedBy, ','));
            if($isSuperReviewer !== false)
            {
                $story = $this->superReview($storyID, $oldStory, $story, $result, $reason);
            }
            if(!array_diff(array_keys($reviewerList[$storyID]), $reviewedBy))
            {
                $reviewerPairs = array();
                foreach($reviewerList[$storyID] as $reviewer => $reviewInfo) $reviewerPairs[$reviewer] = $reviewInfo->result;
                $reviewerPairs[$this->app->user->account] = $result;

                $reviewResult = $this->getReviewResult($reviewerPairs);
                $story        = $this->setStatusByReviewResult($story, $oldStory, $reviewResult, $reason);
            }

            $this->dao->update(TABLE_STORY)->data($story, 'finalResult')->autoCheck()->where('id')->eq($storyID)->exec();
            $this->setStage($storyID);

            $story->id         = $storyID;
            $story->version    = $oldStory->version;
            $actions[$storyID] = $this->recordReviewAction($story, $result, $reason);

            /* Sync twins. */
            $changes = common::createChanges($oldStory, $story);
            if(!empty($oldStory->twins))
            {
                $twins = $oldStory->twins;
                foreach(explode(',', $twins) as $twinID)
                {
                    if(in_array($twinID, $storyIdList) or isset($reviewedTwins[$twinID])) $twins = str_replace(",$twinID,", ',', $twins);
                }
                $this->syncTwins($storyID, trim($twins, ','), $changes, 'Reviewed');
                foreach(explode(',', trim($twins, ',')) as $reviewedID) $reviewedTwins[$reviewedID] = $reviewedID;
            }
        }

        return $actions;
    }

    /**
     * Recall the story review.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recallReview($storyID)
    {
        $oldStory  = $this->getById($storyID);
        $isChanged = $oldStory->changedBy ? true : false;

        $story = clone $oldStory;
        $story->status = $isChanged ? 'changing' : 'draft';
        $this->dao->update(TABLE_STORY)->set('status')->eq($story->status)->where('id')->eq($storyID)->exec();

        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)->andWhere('version')->eq($oldStory->version)->exec();
            }
        }

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'recalled');
    }

    /**
     * Recall the story change.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function recallChange($storyID)
    {
        $oldStory = $this->getById($storyID);

        /* Update story title and version and status. */
        $story = clone $oldStory;
        $story->version = $oldStory->version - 1;
        $story->title   = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($story->version)->fetch('title');
        $story->status  = 'active';

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $this->config->vision == 'rnd') $story->status = 'launched';
        if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

        $this->dao->update(TABLE_STORY)->set('title')->eq($story->title)->set('version')->eq($story->version)->set('status')->eq($story->status)->where('id')->eq($storyID)->exec();

        /* Delete versions that is after this version. */
        $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($oldStory->version)->exec();
        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($twinID)->andWHere('version')->eq($oldStory->version)->exec();
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)->andWhere('version')->eq($oldStory->version)->exec();
            }
        }

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'recalledChange');
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @access public
     * @return array|bool
     */
    public function submitReview($storyID)
    {
        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors[] = $this->lang->story->errorEmptyReviewedBy;
            return false;
        }

        $oldStory     = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $reviewerList = $this->getReviewerPairs($oldStory->id, $oldStory->version);
        $oldStory->reviewer = implode(',', array_keys($reviewerList));

        $story = fixer::input('post')
            ->setDefault('status', 'active')
            ->setDefault('reviewer', '')
            ->setDefault('reviewedBy', '')
            ->setDefault('submitedBy', $this->app->user->account)
            ->remove('needNotReview')
            ->join('reviewer', ',')
            ->get();

        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)->andWhere('version')->eq($oldStory->version)->exec();
            }
        }

        if(isset($_POST['reviewer']))
        {
            foreach($this->post->reviewer as $reviewer)
            {
                if(empty($reviewer)) continue;

                $reviewData = new stdclass();
                $reviewData->story    = $storyID;
                $reviewData->version  = $oldStory->version;
                $reviewData->reviewer = $reviewer;
                $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                /* Sync twins. */
                if(!empty($oldStory->twins))
                {
                    foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                    {
                        $reviewData->story = $twinID;
                        $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                    }
                }
            }
            $story->status = 'reviewing';
        }

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
        if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

        $this->dao->update(TABLE_STORY)->data($story, 'reviewer')->where('id')->eq($storyID)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'submitReview');
        if(!dao::isError()) return $changes;

        return false;
    }

    /**
     * Subdivide story
     *
     * @param  int    $storyID
     * @param  array  $stories
     * @access public
     * @return void
     */
    public function subdivide($storyID, $stories)
    {
        $now      = helper::now();
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if($oldStory->type == 'requirement')
        {
            foreach($stories as $id)
            {
                $data = new stdclass();
                $data->product  = $oldStory->product;
                $data->AType    = 'requirement';
                $data->relation = 'subdivideinto';
                $data->BType    = 'story';
                $data->AID      = $storyID;
                $data->BID      = $id;
                $data->AVersion = $oldStory->version;
                $data->BVersion = 1;
                $data->extra    = 1;

                $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

                $data->AType    = 'story';
                $data->relation = 'subdividedfrom';
                $data->BType    = 'requirement';
                $data->AID      = $id;
                $data->BID      = $storyID;
                $data->AVersion = 1;
                $data->BVersion = $oldStory->version;

                $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();
            }

            if(dao::isError()) return print(js::error(dao::getError()));
        }
        else
        {
            /* Set parent to child story. */
            $this->dao->update(TABLE_STORY)->set('parent')->eq($storyID)->where('id')->in($stories)->exec();
            $this->computeEstimate($storyID);

            /* Set childStories. */
            $childStories = join(',', $stories);

            $newStory = new stdClass();
            $newStory->parent         = '-1';
            $newStory->plan           = '';
            $newStory->lastEditedBy   = $this->app->user->account;
            $newStory->lastEditedDate = $now;
            $newStory->childStories   = trim($oldStory->childStories . ',' . $childStories, ',');

            /* Subdivide story. */
            $this->dao->update(TABLE_STORY)->data($newStory)->autoCheck()->where('id')->eq($storyID)->exec();

            $changes = common::createChanges($oldStory, $newStory);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('story', $storyID, 'createChildrenStory', '', $childStories);
                $this->action->logHistory($actionID, $changes);
            }
        }
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
            ->add('id', $storyID)
            ->add('status', 'closed')
            ->add('stage', 'closed')
            ->setDefault('assignedTo',     'closed')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('closedDate',     $now)
            ->setDefault('closedBy',       $this->app->user->account)
            ->setDefault('assignedDate',   $now)
            ->setDefault('duplicateStory', 0)
            ->stripTags($this->config->story->editor->close['id'], $this->config->allowedTags)
            ->removeIF($this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->closedReason != 'subdivided', 'childStories')
            ->remove('closeSync')
            ->get();

        if(!empty($story->duplicateStory))
        {
            $duplicateStoryID = $this->dao->select('id')->from(TABLE_STORY)->where('id')->eq($story->duplicateStory)->fetch();
            if(empty($duplicateStoryID))
            {
                dao::$errors[] = sprintf($this->lang->story->errorDuplicateStory, $story->duplicateStory);
                return false;
            }
        }

        $this->lang->story->comment = $this->lang->comment;
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story, 'comment')
            ->autoCheck()
            ->batchCheck($this->config->story->close->requiredFields, 'notempty')
            ->checkIF($story->closedReason == 'duplicate', 'duplicateStory', 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)->exec();

        /* Update parent story status and stage. */
        if($oldStory->parent > 0)
        {
            $this->updateParentStatus($storyID, $oldStory->parent);
            $this->setStage($oldStory->parent);
        }
        if(!dao::isError())
        {
            $this->setStage($storyID);
            $this->loadModel('score')->create('story', 'close', $storyID);

            if($this->config->edition != 'open' && $oldStory->feedback) $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
        }

        $changes = common::createChanges($oldStory, $story);
        if($this->post->closeSync)
        {
            /* batchUnset twinID from twins.*/
            $replaceSql = "UPDATE " . TABLE_STORY . " SET twins = REPLACE(twins,',$storyID,', ',') WHERE `product` = $oldStory->product";
            $this->dbh->exec($replaceSql);

            /* Update twins to empty by twinID and if twins eq ','.*/
            $this->dao->update(TABLE_STORY)->set('twins')->eq('')->where('id')->eq($storyID)->orWhere('twins')->eq(',')->exec();

            if(!dao::isError()) $this->loadModel('action')->create('story', $storyID, 'relieved');
        }
        if(!empty($oldStory->twins) and !$this->post->closeSync) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Closed');
        return $changes;
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
        $storyIdList = $data->storyIdList ? $data->storyIdList : array();

        $oldStories   = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->parent == -1) continue;
            if($oldStory->status == 'closed') continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->closedBy       = $this->app->user->account;
            $story->closedDate     = $now;
            $story->assignedDate   = $now;
            $story->status         = 'closed';
            $story->stage          = 'closed';

            $story->closedReason   = $data->closedReasons[$storyID];
            $story->duplicateStory = $data->duplicateStoryIDList[$storyID] ? $data->duplicateStoryIDList[$storyID] : $oldStory->duplicateStory;
            $story->childStories   = $data->childStoriesIDList[$storyID] ? $data->childStoriesIDList[$storyID] : $oldStory->childStories;

            if($story->closedReason != 'done') $story->plan  = '';

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
                /* Update parent story status. */
                if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);
                $this->setStage($storyID);
                $allChanges[$storyID] = common::createChanges($oldStory, $story);

                if($this->config->edition != 'open' && $oldStory->feedback && !isset($feedbacks[$oldStory->feedback]))
                {
                    $feedbacks[$oldStory->feedback] = $oldStory->feedback;
                    $this->loadModel('feedback')->updateStatus('story', $oldStory->feedback, $story->status, $oldStory->status);
                }
            }
            else
            {
                helper::end(js::error('story#' . $storyID . dao::getError(true)));
            }
            if(!dao::isError()) $this->loadModel('score')->create('story', 'close', $storyID);
        }

        return $allChanges;
    }

    /**
     * Batch change the module of story.
     *
     * @param  array  $storyIdList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($storyIdList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
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
     * @param  array  $storyIdList
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function batchChangePlan($storyIdList, $planID, $oldPlanID = 0)
    {
        /* Prepare data. */
        $now            = helper::now();
        $allChanges     = array();
        $oldStories     = $this->getByList($storyIdList);
        $plan           = $this->loadModel('productplan')->getById($planID);
        $oldStoryStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->fetchGroup('story', 'branch');
        $unlinkPlans    = array();
        $link2Plans     = array();
        if(empty($plan))
        {
            $plan = new stdClass();
            $plan->branch = BRANCH_MAIN;
        }

        /* Cycle every story and process it's plan and stage. */
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->branch != BRANCH_MAIN and !in_array($oldStory->branch, explode(',', $plan->branch)) and $plan->branch != BRANCH_MAIN) continue;

            /* Ignore parent story, closed story and story linked to this plan already. */
            if($oldStory->parent < 0) continue;
            if($oldStory->status == 'closed') continue;
            if(strpos(",{$oldStory->plan},", ",$planID,") !== false) continue;

            /* Init story and set last edited data. */
            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;

            /* Remove old plan from the plan field. */
            if($oldPlanID) $story->plan = trim(str_replace(",$oldPlanID,", ',', ",$oldStory->plan,"), ',');

            /* Update the order of the story in the plan. */
            $this->updateStoryOrderOfPlan($storyID, $planID, $oldStory->plan);

            /* Replace plan field if product is normal or not linked to plan or story linked to a branch. */
            if($this->session->currentProductType == 'normal') $story->plan = $planID;
            if(empty($oldPlanID)) $story->plan = $planID;
            if($oldStory->branch) $story->plan = $planID;

            /* Change stage. */
            if($planID)
            {
                if($oldStory->stage == 'wait') $story->stage = 'planned';
                if($this->session->currentProductType and $this->session->currentProductType != 'normal' and $oldStory->branch == 0)
                {
                    foreach(explode(',', $plan->branch) as $planBranch)
                    {
                        if(!isset($oldStoryStages[$storyID][$planBranch]))
                        {
                            $story->stage = 'planned';
                            $newStoryStage = new stdclass();
                            $newStoryStage->story  = $storyID;
                            $newStoryStage->branch = $planBranch;
                            $newStoryStage->stage  = $story->stage;
                            $this->dao->insert(TABLE_STORYSTAGE)->data($newStoryStage)->autoCheck()->exec();
                        }
                    }
                }
            }

            /* Update story and recompute stage. */
            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();

            if(!$planID) $this->setStage($storyID);

            if(!dao::isError())
            {
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
                if($story->plan != $oldStory->plan and !empty($oldStory->plan) and strpos($story->plan, ',') === false) $unlinkPlans[$oldStory->plan] = empty($unlinkPlans[$oldStory->plan]) ? $storyID : "{$unlinkPlans[$oldStory->plan]},$storyID";
                if($story->plan != $oldStory->plan and !empty($story->plan) and strpos($story->plan, ',') === false) $link2Plans[$story->plan]  = empty($link2Plans[$story->plan]) ? $storyID : "{$link2Plans[$story->plan]},$storyID";
            }
        }

        if(!dao::isError())
        {
            $this->loadModel('action');
            foreach($unlinkPlans as $planID => $stories) $this->action->create('productplan', $planID, 'unlinkstory', '', $stories);
            foreach($link2Plans as $planID => $stories) $this->action->create('productplan', $planID, 'linkstory', '', $stories);
        }

        return $allChanges;
    }

    /**
     * Batch change branch.
     *
     * @param  array  $storyIdList
     * @param  int    $branchID
     * @param  string $confirm
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function batchChangeBranch($storyIdList, $branchID, $confirm = '', $plans = array())
    {
        $now         = helper::now();
        $allChanges  = array();
        $oldStories  = $this->getByList($storyIdList);
        $story       = current($oldStories);
        $productID   = $story->product;
        $mainModules = $this->dao->select('id')->from(TABLE_MODULE)
            ->where('root')->eq($productID)
            ->andWhere('branch')->eq(0)
            ->andWhere('type')->eq('story')
            ->fetchPairs('id');

        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->branch         = $branchID;
            $story->module         = ($oldStory->branch != $branchID and !in_array($oldStory->module, $mainModules)) ? 0 : $oldStory->module;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            if(!dao::isError())
            {
                if($confirm == 'yes')
                {
                    $planIdList         = '';
                    $conflictPlanIdList = '';

                    /* Determine whether there is a conflict between the branch of the story and the linked plan. */
                    if($oldStory->branch != $branchID and $branchID != BRANCH_MAIN and isset($plans[$storyID]))
                    {
                        foreach($plans[$storyID] as $planID => $plan)
                        {
                            if($plan->branch != $branchID)
                            {
                                $conflictPlanIdList .= $planID . ',';
                            }
                            else
                            {
                                $planIdList .= $planID . ',';
                            }
                        }

                        /* If there is a conflict in the linked plan when the branch story to be modified, the linked with the conflicting plan will be removed. */
                        if($conflictPlanIdList)
                        {
                            $story->plan = $planIdList;
                            $this->dao->delete()->from(TABLE_PLANSTORY)->where('story')->eq($storyID)->andWhere('plan')->in($conflictPlanIdList)->exec();
                            $this->dao->update(TABLE_STORY)->set('plan')->eq($planIdList)->where('id')->eq($storyID)->exec();
                        }
                    }
                }
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
            }
        }
        return $allChanges;
    }

    /**
     * Batch change the stage of story.
     *
     * @param $storyIdList
     * @param $stage
     *
     * @access public
     * @return array
     */
    public function batchChangeStage($storyIdList, $stage)
    {
        $now           = helper::now();
        $allChanges    = array();
        $account       = $this->app->user->account;
        $oldStories    = $this->getByList($storyIdList);
        $ignoreStories = '';
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'draft' or $oldStory->status == 'closed')
            {
                $ignoreStories .= "#{$storyID} ";
                continue;
            }

            $story = new stdclass();
            $story->lastEditedBy   = $account;
            $story->lastEditedDate = $now;
            $story->stage          = $stage;
            $story->stagedBy       = $account;

            /* Add for record released date. */
            if($story->stage == 'released') $story->releasedDate = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
            $this->dao->update(TABLE_STORYSTAGE)->set('stage')->eq($stage)->set('stagedBy')->eq($account)->where('story')->eq((int)$storyID)->exec();
            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }
        if($ignoreStories) echo js::alert(sprintf($this->lang->story->ignoreChangeStage, $ignoreStories));
        return $allChanges;
    }

    /**
     * Batch to task.
     *
     * @param  int    $executionID
     * @param  int    $projectID
     * @access public
     * @return bool|array
     */
    public function batchToTask($executionID, $projectID = 0)
    {
        /* load Module and get the data from the post and get the current time. */
        $this->loadModel('action');
        $this->loadModel('task');

        $now     = helper::now();
        $account = $this->app->user->account;
        $tasks   = fixer::input('post')
            ->remove('syncFields')
            ->get();

        if(!empty($_POST['syncFields'])) $stories = empty($tasks->story) ? array() : $this->getByList($tasks->story);

        /* Create tasks. */
        $preStory  = 0;
        $storyIDs  = array();
        $taskNames = array();
        foreach($tasks->story as $key => $storyID)
        {
            $tasks->name[$key] = trim($tasks->name[$key]);
            if(empty($tasks->name[$key])) continue;
            if($tasks->type[$key] == 'affair') continue;
            if($tasks->type[$key] == 'ditto' and isset($tasks->type[$key - 1]) and $tasks->type[$key - 1] == 'affair') continue;

            if($storyID == 'ditto') $storyID = $preStory;
            $preStory = $storyID;

            if(!isset($tasks->story[$key - 1]) and $key > 1 and !empty($tasks->name[$key - 1]))
            {
                $storyIDs[]  = 0;
                $taskNames[] = $tasks->name[$key - 1];
            }

            $inNames = in_array($tasks->name[$key], $taskNames);
            if(!$inNames or ($inNames && !in_array($storyID, $storyIDs)))
            {
                $storyIDs[]  = $storyID;
                $taskNames[] = $tasks->name[$key];
            }
            else
            {
                dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $tasks->name[$key];
                return false;
            }
        }

        $story          = 0;
        $module         = 0;
        $type           = '';
        $assignedTo     = '';
        $estStarted     = '0000-00-00';
        $deadline       = '0000-00-00';
        $data           = array();
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";
        foreach($tasks->name as $i => $task)
        {
            $module     = (!isset($tasks->module[$i]) or $tasks->module[$i] == 'ditto')          ? $module     : $tasks->module[$i];
            $story      = (!isset($tasks->story[$i]) or $tasks->story[$i] == 'ditto')            ? $story      : $tasks->story[$i];
            $type       = (!isset($tasks->type[$i]) or $tasks->type[$i] == 'ditto')              ? $type       : $tasks->type[$i];
            $assignedTo = (!isset($tasks->assignedTo[$i]) or $tasks->assignedTo[$i] == 'ditto')  ? $assignedTo : $tasks->assignedTo[$i];
            $estStarted = (!isset($tasks->estStarted[$i]) or isset($tasks->estStartedDitto[$i])) ? $estStarted : $tasks->estStarted[$i];
            $deadline   = (!isset($tasks->deadline[$i]) or isset($tasks->deadlineDitto[$i]))     ? $deadline   : $tasks->deadline[$i];

            if(empty($tasks->name[$i])) continue;

            $data[$i]             = new stdclass();
            $data[$i]->story      = (int)$story;
            $data[$i]->type       = $type;
            $data[$i]->module     = (int)$module;
            $data[$i]->assignedTo = $assignedTo;
            $data[$i]->color      = $tasks->color[$i];
            $data[$i]->name       = $tasks->name[$i];
            $data[$i]->pri        = $tasks->pri[$i];
            $data[$i]->estimate   = $tasks->estimate[$i];
            $data[$i]->left       = $tasks->estimate[$i];
            $data[$i]->project    = $projectID;
            $data[$i]->execution  = $executionID;
            $data[$i]->estStarted = $estStarted;
            $data[$i]->deadline   = $deadline;
            $data[$i]->status     = 'wait';
            $data[$i]->openedBy   = $account;
            $data[$i]->openedDate = $now;
            $data[$i]->vision     = 'rnd';
            if($story)
            {
                $data[$i]->storyVersion = $stories[$story]->version;
                if(strpos(",{$_POST['syncFields']},", ',spec,') !== false) $data[$i]->desc = $stories[$story]->spec;
                if(strpos(",{$_POST['syncFields']},", 'mailto') !== false) $data[$i]->mailto = $stories[$story]->mailto;
            }

            if($assignedTo) $data[$i]->assignedDate = $now;
            if(strpos($requiredFields, ',estStarted,') !== false and empty($estStarted)) $data[$i]->estStarted = '';
            if(strpos($requiredFields, ',deadline,') !== false and empty($deadline))     $data[$i]->deadline   = '';
        }

        /* check data. */
        foreach($data as $i => $task)
        {
            if(!helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted)
            {
                dao::$errors['message'][] = $this->lang->task->error->deadlineSmall;
                return false;
            }

            if($task->estimate and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $task->estimate))
            {
                dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
                return false;
            }

            foreach(explode(',', $requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!empty($task->$field)) continue;
                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }

            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

            if($task->estimate) $task->estimate = (float)$task->estimate;
        }

        $taskIdList = array();
        foreach($data as $i => $task)
        {
            $task->version = 1;
            $this->dao->insert(TABLE_TASK)->data($task)
                ->autoCheck()
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->exec();

            if(dao::isError()) return false;

            $taskID       = $this->dao->lastInsertID();
            $taskIdList[] = $taskID;

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;
            $taskSpec->estStarted = $task->estStarted;
            $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            if($task->story)
            {
                $storyStage = $this->dao->select('stage')->from(TABLE_STORY)->where('id')->eq($task->story)->fetch('stage');
                if($storyStage && $storyStage != 'projected') $this->setStage($task->story);
            }

            $this->action->create('task', $taskID, 'Opened', '');
        }

        $this->loadModel('kanban')->updateLane($executionID, 'task');
        return $taskIdList;
    }

    /**
     * Assign story.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function assign($storyID)
    {
        $oldStory   = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now        = helper::now();
        $assignedTo = $this->post->assignedTo;
        if($assignedTo == $oldStory->assignedTo) return array();

        $story = fixer::input('post')
            ->add('id', $storyID)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('assignedDate', $now)
            ->stripTags($this->config->story->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq((int)$storyID)->exec();

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Assigned');
        if(!dao::isError()) return $changes;
        return false;
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
        $storyIdList = $this->post->storyIdList;
        $assignedTo  = $this->post->assignedTo;
        $oldStories  = $this->getByList($storyIdList);
        $ignoreStories = '';
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->status == 'closed')
            {
                $ignoreStories .= "#{$storyID},";
                continue;
            }
            if($assignedTo == $oldStory->assignedTo) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->assignedTo     = $assignedTo;
            $story->assignedDate   = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();

            $allChanges[$storyID] = common::createChanges($oldStory, $story);
        }

        if($ignoreStories)
        {
            $ignoreStories = trim($ignoreStories, ',');
            echo js::alert(sprintf($this->lang->story->ignoreClosedStory, $ignoreStories));
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
            ->add('id', $storyID)
            ->add('closedBy', '')
            ->add('closedReason', '')
            ->add('closedDate', '0000-00-00')
            ->add('reviewedBy', '')
            ->add('reviewedDate', '0000-00-00')
            ->add('duplicateStory', 0)
            ->add('childStories', '')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate',   $now)
            ->setDefault('activatedDate', $now)
            ->stripTags($this->config->story->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        /* Get status after activation. */
        $story->status = $this->getActivateStatus($storyID);

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
        if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq($storyID)->exec();

        if($story->status == 'active')
        {
            $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->exec();

            /* Sync twins. */
            if(!empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)->exec();
                }
            }
        }

        $this->setStage($storyID);

        /* Update parent story status. */
        if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

        $changes = common::createChanges($oldStory, $story);
        if(!empty($oldStory->twins)) $this->syncTwins($storyID, $oldStory->twins, $changes, 'Activated');
        return $changes;
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
        $this->loadModel('kanban');

        $storyID = (int)$storyID;
        $account = $this->app->user->account;

        /* Get projects which status is doing. */
        $oldStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchAll('branch');
        $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();

        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(!empty($story->stagedBy) and $story->status != 'closed') return false;

        $product    = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fetch();
        $executions = $this->dao->select('t1.project,t3.branch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project')
            ->where('t1.story')->eq($storyID)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchPairs('project', 'branch');

        $hasBranch = ($product and $product->type != 'normal' and empty($story->branch));
        $stages    = array();
        if($hasBranch and $story->plan)
        {
            $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($story->plan)->fetchPairs('branch', 'branch');
            foreach($plans as $branch) $stages[$branch] = 'planned';
        }

        /* When the status is closed, stage is also changed to closed. */
        if($story->status == 'closed')
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('closed')->where('id')->eq($storyID)->exec();
            foreach($stages as $branch => $stage) $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('closed')->exec();
            foreach($executions as $execution => $branch)
            {
                $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('closed')->exec();
                $this->kanban->updateLane($execution, 'story', $storyID);
            }
            return false;
        }

        /* If no executions, in plan, stage is planned. No plan, wait. */
        if(!$executions)
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq($storyID)->andWhere('plan', true)->eq('')->orWhere('plan')->eq(0)->markRight(1)->exec();

            foreach($stages as $branch => $stage)
            {
                if(isset($oldStages[$branch]))
                {
                    $oldStage = $oldStages[$branch];
                    if(!empty($oldStage->stagedBy))
                    {
                        $this->dao->replace(TABLE_STORYSTAGE)->data($oldStage)->exec();
                        continue;
                    }
                }
                $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq($stage)->exec();
            }
            $this->dao->update(TABLE_STORY)->set('stage')->eq('planned')->where('id')->eq($storyID)->andWhere("(plan != '' AND plan != '0')")->exec();
        }

        if($hasBranch)
        {
            foreach($executions as $executionID => $branch) $stages[$branch] = 'projected';
        }

        /* Search related tasks. */
        $tasks = $this->dao->select('type,execution,status')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('type')->in('devel,test')
            ->andWhere('story')->eq($storyID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('cancel')
            ->andWhere('closedReason')->ne('cancel')
            ->fetchGroup('type');

        /* No tasks, then the stage is projected. */
        if(!$tasks and $executions)
        {
            foreach($stages as $branch => $stage)
            {
                if(isset($oldStages[$branch]))
                {
                    $oldStage = $oldStages[$branch];
                    if(!empty($oldStage->stagedBy))
                    {
                        $this->dao->replace(TABLE_STORYSTAGE)->data($oldStage)->exec();
                        continue;
                    }
                }
                $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('projected')->exec();
            }
            $this->dao->update(TABLE_STORY)->set('stage')->eq('projected')->where('id')->eq($storyID)->exec();
        }

        /* Get current stage and set as default value. */
        $currentStage = $story->stage;
        $stage        = $currentStage;

        /* Cycle all tasks, get counts of every type and every status. */
        $branchStatusList = array();
        $branchDevelTasks = array();
        $branchTestTasks  = array();
        $statusList['devel'] = array('wait' => 0, 'doing' => 0, 'done'=> 0, 'pause' => 0);
        $statusList['test']  = array('wait' => 0, 'doing' => 0, 'done'=> 0, 'pause' => 0);
        foreach($tasks as $type => $typeTasks)
        {
            foreach($typeTasks as $task)
            {
                $status = $task->status ? $task->status : 'wait';
                $status = $status == 'closed' ? 'done' : $status;

                $branch = $executions[$task->execution];
                if(!isset($branchStatusList[$branch])) $branchStatusList[$branch] = $statusList;
                if(!isset($branchStatusList[$branch][$task->type])) $branchStatusList[$branch][$task->type] = array();
                if(!isset($branchStatusList[$branch][$task->type][$status])) $branchStatusList[$branch][$task->type][$status] = 0;
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
         * 2. some devel tasks done, all test tasks not done, set stage as developing.
         * 3. all devel tasks done, all test tasks waiting, set stage as developed.
         * 4. one test task doing, set stage as testing.
         * 5. all test tasks done, still some devel tasks not done(wait, doing), set stage as testing.
         * 6. all test tasks done, all devel tasks done, set stage as tested.
         */
        foreach($branchStatusList as $branch => $statusList)
        {
            $stage      = 'projected';
            $testTasks  = isset($branchTestTasks[$branch]) ? $branchTestTasks[$branch] : 0;
            $develTasks = isset($branchDevelTasks[$branch]) ? $branchDevelTasks[$branch] : 0;
            if($statusList['devel']['doing'] > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developing';
            if($statusList['devel']['wait'] > 0 and $statusList['devel']['done'] > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developing';
            if(($statusList['devel']['doing'] > 0 or ($statusList['devel']['wait'] > 0 and $statusList['devel']['done'] > 0)) and $statusList['test']['wait'] > 0 and $statusList['test']['done'] > 0) $stage = 'developing';
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developed';
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['wait'] > 0 and $statusList['test']['done'] > 0) $stage = 'testing';
            if($statusList['test']['doing'] > 0 or $statusList['test']['pause'] > 0) $stage = 'testing';
            if(($statusList['devel']['wait'] > 0 or $statusList['devel']['doing'] > 0) and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'testing';
            if($statusList['devel']['done'] == $develTasks and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'tested';

            $stages[$branch] = $stage;
        }

        $releases = $this->dao->select('*')->from(TABLE_RELEASE)->where("CONCAT(',', stories, ',')")->like("%,$storyID,%")->andWhere('deleted')->eq(0)->fetchPairs('branch', 'branch');
        foreach($releases as $branches)
        {
            $branches = trim($branches, ',');
            foreach(explode(',', $branches) as $branch) $stages[$branch] = 'released';
        }

        $currentStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if($story->stage != $currentStory->stage)
        {
            foreach($executions as $executionID => $branch)
            {
                $this->kanban->updateLane($executionID, 'story', $storyID);
            }
        }

        if(empty($stages)) return;
        if($hasBranch)
        {
            $stageList   = join(',', array_keys($this->lang->story->stageList));
            $minStagePos = strlen($stageList);
            $minStage    = '';
            foreach($stages as $branch => $stage)
            {
                $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq($stage)->exec();
                if(isset($oldStages[$branch]))
                {
                    $oldStage = $oldStages[$branch];
                    if(!empty($oldStage->stagedBy))
                    {
                        $this->dao->replace(TABLE_STORYSTAGE)->data($oldStage)->exec();
                        $stage = $oldStage->$stage;
                    }
                }
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

        $currentStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if($story->stage != $currentStory->stage)
        {
            foreach($executions as $executionID => $branch)
            {
                $this->kanban->updateLane($executionID, 'story', $storyID);
            }
        }

        return;
    }

    /**
     * Get stories to link.
     *
     * @param  int     $storyID
     * @param  string  $type linkStories|linkRelateSR|linkRelateUR
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $storyType
     * @param  object  $pager
     * @param  string  $excludeStories
     * @access public
     * @return array
     */
    public function getStories2Link($storyID, $type = 'linkStories', $browseType = 'bySearch', $queryID = 0, $storyType = 'story', $pager = null, $excludeStories = '')
    {
        $story         = $this->getById($storyID);
        $tmpStoryType  = $storyType == 'story' ? 'requirement' : 'story';
        $stories2Link  = array();
        if($type == 'linkRelateSR' or $type == 'linkRelateUR')
        {
            $tmpStoryType   = $story->type;
            $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
            $storyIDList    = $story->id . ',' . $excludeStories . ',' . $story->{$linkStoryField};
        }
        else
        {
            $linkedStories = $this->getRelation($storyID, $story->type);
            $linkedStories = empty($linkedStories) ? array() : $linkedStories;
            $storyIDList   = array_keys($linkedStories);
        }

        if($browseType == 'bySearch')
        {
            $stories2Link = $this->getBySearch($story->product, $story->branch, $queryID, 'id_desc', '', $tmpStoryType, $storyIDList, '', $pager);
        }
        elseif($type != 'linkRelateSR' and $type != 'linkRelateUR')
        {
            $status = $storyType == 'story' ? 'active' : 'all';
            $stories2Link = $this->getProductStories($story->product, $story->branch, 0, $status, $tmpStoryType, $orderBy = 'id_desc', true, $storyIDList, $pager);
        }

        if($type != 'linkRelateSR' and $type != 'linkRelateUR')
        {
            foreach($stories2Link as $id => $story)
            {
                if($storyType == 'story' and $story->status != 'active') unset($stories2Link[$id]);
            }
        }

        return $stories2Link;
    }

    /**
     * Get stories list of a product.
     *
     * @param  int          $productID
     * @param  int          $branch
     * @param  array|string $moduleIdList
     * @param  string       $status
     * @param  string       $type    requirement|story
     * @param  string       $orderBy
     * @param  array|string $excludeStories
     * @param  object       $pager
     * @param  bool         $hasParent
     *
     * @access public
     * @return array
     */
    public function getProductStories($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $type = 'story', $orderBy = 'id_desc', $hasParent = true, $excludeStories = '', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getStories();

        $stories        = array();
        $branchProducts = array();
        $normalProducts = array();
        $productList    = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productID)->fetchAll('id');
        foreach($productList as $product)
        {
            if($product->type != 'normal')
            {
                $branchProducts[$product->id] = $product->id;
                continue;
            }

            $normalProducts[$product->id] = $product->id;
        }

        $productQuery = '(';
        if(!empty($normalProducts)) $productQuery .= '`product` ' . helper::dbIN(array_keys($normalProducts));
        if(!empty($branchProducts))
        {
            if(!empty($normalProducts)) $productQuery .= " OR ";
            $productQuery .= "(`product` " . helper::dbIN(array_keys($branchProducts));

            if($branch !== 'all')
            {
                if(is_array($branch)) $branch = join(',', $branch);
                $productQuery .= " AND `branch` " . helper::dbIN($branch);
            }
            $productQuery .= ')';
        }
        if(empty($normalProducts) and empty($branchProducts)) $productQuery .= '1 = 1';
        $productQuery .= ') ';

        $stories = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere($productQuery)
            ->beginIF(!$hasParent)->andWhere("parent")->ge(0)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!empty($excludeStories))->andWhere('id')->notIN($excludeStories)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $this->mergePlanTitle($productID, $stories, $branch, $type);
    }

    /**
     * Get stories pairs of a product.
     *
     * @param  int           $productID
     * @param  string|int    $branch
     * @param  array|string  $moduleIdList
     * @param  string        $status
     * @param  string        $order
     * @param  int           $limit
     * @param  string        $type
     * @param  string        $storyType    requirement|story
     * @param  bool|string   $hasParent
     * @access public
     * @return array
     */
    public function getProductStoryPairs($productID = 0, $branch = 'all', $moduleIdList = 0, $status = 'all', $order = 'id_desc', $limit = 0, $type = 'full', $storyType = 'story', $hasParent = true)
    {
        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productID)->andWhere('t1.product')->in($productID)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in("0,$branch")->fi()
            ->beginIF(!$hasParent or $hasParent == 'false')->andWhere('t1.parent')->ge(0)->fi()
            ->beginIF($status and $status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->andWhere('t1.type')->eq($storyType)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($order)
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories, $type, $limit);
    }

    /**
     * Get stories by assignedTo.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedTo($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedTo', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by openedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByOpenedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'openedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by reviewedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByReviewedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewedBy', $account, $type, $orderBy, $pager, 'include');
    }

    /**
     * Get stories which need to review.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByReviewBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by closedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @return array
     */
    public function getByClosedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'closedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by status.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $status
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByStatus($productID, $branch, $modules, $status, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'status', $status, $type, $orderBy, $pager);
    }

    /**
     * Get stories by plan.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  array  $modules
     * @param  int    $plan
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     *
     * @access public
     * @return array
     */
    public function getByPlan($productID, $branch, $modules, $plan, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'plan', $plan, $type, $orderBy, $pager);
    }

    /**
     * Get stories by assignedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by a field.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  string      $modules
     * @param  string      $fieldName
     * @param  mixed       $fieldValue
     * @param  string      $type         requirement|story
     * @param  string      $orderBy
     * @param  object      $pager
     * @param  string      $operator     equal|include
     * @access public
     * @return array
     */
    public function getByField($productID, $branch, $modules, $fieldName, $fieldValue, $type = 'story', $orderBy = '', $pager = null, $operator = 'equal')
    {
        if(!$this->loadModel('common')->checkField(TABLE_STORY, $fieldName) and $fieldName != 'reviewBy' and $fieldName != 'assignedBy') return array();

        $actionIDList = array();
        if($fieldName == 'assignedBy') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($fieldValue)->fetchPairs('objectID', 'objectID');

        $sql = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1');
        if($fieldName == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version');

        $stories = $sql->where('t1.product')->in($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.type')->eq($type)
            ->beginIF($branch !== 'all')->andWhere("t1.branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("t1.module")->in($modules)->fi()
            ->beginIF($operator == 'equal' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->eq($fieldValue)->fi()
            ->beginIF($operator == 'include' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->like("%$fieldValue%")->fi()
            ->beginIF($fieldName == 'reviewBy')
            ->andWhere('t2.reviewer')->eq($this->app->user->account)
            ->andWhere('t2.result')->eq('')
            ->andWhere('t1.status')->eq('reviewing')
            ->fi()
            ->beginIF($fieldName == 'assignedBy')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->mergePlanTitle($productID, $stories, $branch, $type);
    }

    /**
     * Get to be closed stories.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $modules
     * @param  string $type requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function get2BeClosed($productID, $branch, $modules, $type = 'story', $orderBy = '', $pager = null)
    {
        $stories = $this->dao->select("*,IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('type')->eq($type)
            ->beginIF($branch and $branch != 'all')->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('stage')->in('developed,released')
            ->andWhere('status')->ne('closed')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->mergePlanTitle($productID, $stories, $branch, $type);
    }

    /**
     * Get stories through search.
     *
     * @access public
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  string      $executionID
     * @param  string      $type requirement|story
     * @param  string      $excludeStories
     * @param  string      $excludeStatus
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySearch($productID, $branch = '', $queryID = 0, $orderBy = '', $executionID = '', $type = 'story', $excludeStories = '', $excludeStatus = '', $pager = null)
    {
        $this->loadModel('product');
        $executionID = empty($executionID) ? 0 : $executionID;
        $products    = empty($executionID) ? $this->product->getList($programID = 0, $status = 'all', $limit = 0, $line = 0, $shadow = 'all') : $this->product->getProducts($executionID);

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

        if($excludeStories) $storyQuery = $storyQuery . ' AND `id` NOT ' . helper::dbIN($excludeStories);
        if($excludeStatus)  $storyQuery = $storyQuery . ' AND `status` NOT ' . helper::dbIN($excludeStatus);
        if($this->app->moduleName == 'productplan') $storyQuery .= " AND `status` NOT IN ('closed') AND `parent` >= 0 ";
        if(in_array($this->app->moduleName, array('build', 'projectrelease', 'release'))) $storyQuery .= "AND `parent` >= 0 ";
        $allBranch = "`branch` = 'all'";
        if(!empty($executionID))
        {
            $normalProducts = array();
            $branchProducts = array();
            foreach($products as $product)
            {
                if($product->type != 'normal')
                {
                    $branchProducts[$product->id] = $product;
                    continue;
                }

                $normalProducts[$product->id] = $product;
            }

            $storyQuery .= ' AND (';
            if(!empty($normalProducts)) $storyQuery .= '`product` ' . helper::dbIN(array_keys($normalProducts));
            if(!empty($branchProducts))
            {
                $branches = array(BRANCH_MAIN => BRANCH_MAIN);
                if($branch === '')
                {
                    foreach($branchProducts as $product)
                    {
                        foreach($product->branches as $branchID) $branches[$branchID] = $branchID;
                    }
                }
                else
                {
                    $branches[$branch] = $branch;
                }

                $branches    = join(',', $branches);
                if(!empty($normalProducts)) $storyQuery .= " OR ";
                $storyQuery .= "(`product` " . helper::dbIN(array_keys($branchProducts)) . " AND `branch` " . helper::dbIN($branches) . ")";
            }
            if(empty($normalProducts) and empty($branchProducts)) $storyQuery .= '1 = 1';
            $storyQuery .= ') ';

            if($this->app->moduleName == 'release' or $this->app->moduleName == 'build')
            {
                $storyQuery .= " AND `status` NOT IN ('draft')"; // Fix bug #990.
            }
            else
            {
                $storyQuery .= " AND `status` NOT IN ('draft', 'reviewing', 'changing', 'closed')";
            }

            if($this->app->rawModule == 'build' and $this->app->rawMethod == 'linkstory') $storyQuery .= " AND `parent` != '-1'";
        }
        elseif(strpos($storyQuery, $allBranch) !== false)
        {
            $storyQuery = str_replace($allBranch, '1', $storyQuery);
        }
        elseif($branch !== 'all' and $branch !== '' and strpos($storyQuery, '`branch` =') === false and $queryProductID != 'all')
        {
            if($branch and strpos($storyQuery, '`branch` =') === false) $storyQuery .= " AND `branch` " . helper::dbIN($branch);
        }

        $storyQuery = preg_replace("/`plan` +LIKE +'%([0-9]+)%'/i", "CONCAT(',', `plan`, ',') LIKE '%,$1,%'", $storyQuery);

        return $this->getBySQL($queryProductID, $storyQuery, $orderBy, $pager, $type);
    }

    /**
     * Get Story changed Revert ObjectID.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    public function getRevertStoryIDList($productID)
    {
        $review = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('product')->like("%,$productID,%")
            ->andWhere('action')->eq('reviewed')
            ->andWhere('objectType')->eq('story')
            ->andWhere('extra')->eq('Revert')
            ->groupBy('objectID')
            ->orderBy('objectID_desc')
            ->fetchPairs();
        return $review;
    }

    /**
     * Get stories by a sql.
     *
     * @param  int    $productID
     * @param  string $sql
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $type requirement|story
     * @access public
     * @return array
     */
    public function getBySQL($productID, $sql, $orderBy, $pager = null, $type = 'story')
    {
        /* Get plans. */
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq('0')
            ->beginIF($productID != 'all' and $productID != '')->andWhere('product')->eq((int)$productID)->fi()
            ->fetchPairs();

        $review = $this->getRevertStoryIDList($productID);
        $sql = str_replace(array('`product`', '`version`', '`branch`'), array('t1.`product`', 't1.`version`', 't1.`branch`'), $sql);
        if(strpos($sql, 'result') !== false)
        {
            if(strpos($sql, 'revert') !== false)
            {
                $sql  = str_replace("AND `result` = 'revert'", '', $sql);
                $sql .= " AND t1.`id` " . helper::dbIN($review);
            }
            else
            {
                $sql = str_replace(array('`result`'), array('t3.`result`'), $sql);
            }
        }

        $tmpStories = $this->dao->select("DISTINCT t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->beginIF(strpos($sql, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version')->fi()
            ->where($sql)
            ->beginIF($productID != 'all' and $productID != '')->andWhere('t1.`product`')->eq((int)$productID)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.type')->eq($type)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        if(!$tmpStories) return array();

        /* Process plans. */
        $stories = array();
        foreach($tmpStories as $story)
        {
            $story->planTitle = '';
            $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
            $stories[$story->id] = $story;
        }

        return $stories;
    }

    /**
     * Get stories list of a execution.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $orderBy
     * @param  string $type
     * @param  int    $param
     * @param  string $storyType
     * @param  string $excludeStories
     * @param  string $excludeStatus
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionStories($executionID = 0, $productID = 0, $branch = 0, $orderBy = 't1.`order`_desc', $type = 'byModule', $param = 0, $storyType = 'story', $excludeStories = '', $excludeStatus = '', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionStories();

        if(!$executionID) return array();
        $executions = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($executionID)->fetchAll('id');
        $hasProject = false;
        $hasExecution = false;
        foreach($executions as $execution)
        {
            if($execution->type == 'project') $hasProject   = true;
            if($execution->type != 'project') $hasExecution = true;
        }

        $orderBy = str_replace('branch_', 't2.branch_', $orderBy);
        $orderBy = str_replace('version_', 't2.version_', $orderBy);
        $type    = strtolower($type);

        $products = $this->loadModel('product')->getProducts($executionID);
        if($type == 'bysearch')
        {
            $queryID  = (int)$param;

            if($this->session->executionStoryQuery == false) $this->session->set('executionStoryQuery', ' 1 = 1');
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    if($this->app->rawModule == 'projectstory')
                    {
                        $this->session->set('storyQuery', $query->sql);
                        $this->session->set('storyForm', $query->form);
                    }
                    else
                    {
                        $this->session->set('executionStoryQuery', $query->sql);
                        $this->session->set('executionStoryForm', $query->form);
                    }
                }
            }

            if($this->app->rawModule == 'projectstory') $this->session->executionStoryQuery = $this->session->storyQuery;

            $allProduct = "`product` = 'all'";
            $storyQuery = $this->session->executionStoryQuery;
            if(strpos($this->session->executionStoryQuery, $allProduct) !== false)
            {
                $storyQuery = str_replace($allProduct, '1', $this->session->executionStoryQuery);
            }
            $storyQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $storyQuery);

            if($products) $productID = key($products);
            $review = $this->getRevertStoryIDList($productID);

            if(strpos($storyQuery, 'result') !== false)
            {
                if(strpos($storyQuery, 'revert') !== false)
                {
                    $storyQuery  = str_replace("AND t2.`result` = 'revert'", '', $storyQuery);
                    $storyQuery .= " AND t2.`id` " . helper::dbIN($review);
                }
                else
                {
                    $storyQuery = str_replace(array('t2.`result`'), array('t4.`result`'), $storyQuery);
                }
            }

            $stories = $this->dao->select("distinct t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->beginIF(strpos($storyQuery, 'result') !== false)->leftJoin(TABLE_STORYREVIEW)->alias('t4')->on('t2.id = t4.story and t2.version = t4.version')->fi()
                ->where($storyQuery)
                ->andWhere('t1.project')->in($executionID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t2.type')->eq($storyType)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->orderBy($orderBy)
                ->page($pager, 't2.id')
                ->fetchAll('id');
        }
        else
        {
            $productParam = ($type == 'byproduct' and $param) ? $param : $this->cookie->storyProductParam;
            $branchParam  = ($type == 'bybranch'  and $param !== '') ? $param : $this->cookie->storyBranchParam;
            $moduleParam  = ($type == 'bymodule'  and $param !== '') ? $param : $this->cookie->storyModuleParam;

            $modules = array();
            if(!empty($moduleParam) or strpos('allstory,unclosed,bymodule', $type) !== false)
            {
                $modules = $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like("%,$moduleParam,%")->andWhere('type')->eq('story')->andWhere('deleted')->eq(0)->fetchPairs();
            }

            if(strpos($branchParam, ',') !== false) list($productParam, $branchParam) = explode(',', $branchParam);

            $unclosedStatus = $this->lang->story->statusList;
            unset($unclosedStatus['closed']);

            /* Get story id list of linked executions. */
            $storyIdList = array();
            if($type == 'linkedexecution' or $type == 'unlinkedexecution')
            {
                $executions  = $this->loadModel('execution')->getPairs($executionID);
                $storyIdList = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in(array_keys($executions))->fetchPairs();
            }

            $type = (strpos('bymodule|byproduct', $type) !== false and $this->session->storyBrowseType) ? $this->session->storyBrowseType : $type;

            $stories = $this->dao->select("distinct t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder, t3.type as productType, t2.version as version")->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->where('t1.project')->in($executionID)
                ->andWhere('t2.type')->eq($storyType)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->beginIF($hasProject)
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($type == 'bybranch' and $branchParam !== '')->andWhere('t2.branch')->in("0,$branchParam")->fi()
                ->beginIF($type == 'linkedexecution')->andWhere('t2.id')->in($storyIdList)->fi()
                ->beginIF($type == 'unlinkedexecution')->andWhere('t2.id')->notIn($storyIdList)->fi()
                ->fi()
                ->beginIF($hasExecution)
                ->beginIF(!empty($productParam))->andWhere('t1.product')->eq($productParam)->fi()
                ->beginIF($this->session->executionStoryBrowseType and strpos('changing|', $this->session->executionStoryBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
                ->fi()
                ->beginIF(strpos('draft|reviewing|changing|closed', $type) !== false)->andWhere('t2.status')->eq($type)->fi()
                ->beginIF($type == 'unclosed')->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
                ->beginIF($excludeStatus)->andWhere('t2.status')->notIN($excludeStatus)->fi()
                ->beginIF($this->session->storyBrowseType and strpos('changing|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
                ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t3.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't2.id')
                ->fetchAll('id');
        }

        $query = $this->dao->get();

        /* Get the stories of main branch. */
        $branchStoryList = $this->dao->select('t1.*,t2.branch as productBranch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.project = t2.project')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.story')->in(array_keys($stories))
            ->andWhere('t1.branch')->eq(BRANCH_MAIN)
            ->andWhere('t3.type')->ne('normal')
            ->fetchAll();

        $branches       = array();
        $stageOrderList = 'wait,planned,projected,developing,developed,testing,tested,verified,released,closed';

        foreach($branchStoryList as $story) $branches[$story->productBranch][$story->story] = $story->story;

        /* Set up story stage. */
        foreach($branches as $branchID => $storyIdList)
        {
            $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->andWhere('branch')->eq($branchID)->fetchPairs('story', 'stage');

            /* Take the earlier stage. */
            foreach($stages as $storyID => $stage) if(strpos($stageOrderList, $stories[$storyID]->stage) > strpos($stageOrderList, $stage)) $stories[$storyID]->stage = $stage;
        }

        $this->dao->sqlobj->sql = $query;
        return $this->mergePlanTitle($productID, $stories, $branch, $storyType);
    }

    /**
     * Get stories pairs of a execution.
     *
     * @param  int           $executionID
     * @param  int           $productID
     * @param  int           $branch
     * @param  array|string  $moduleIdList
     * @param  string        $type full|short
     * @param  string        $status all|unclosed|review
     * @param  string        $storyType story|requirement
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs($executionID = 0, $productID = 0, $branch = 'all', $moduleIdList = 0, $type = 'full', $status = 'all', $storyType = 'story')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionStoryPairs();
        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.type')->eq($storyType)
            ->beginIF($productID)->andWhere('t2.product')->eq((int)$productID)->fi()
            ->beginIF($branch !== 'all')->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($status == 'unclosed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF($status == 'review')->andWhere('t2.status')->in('draft,changing')->fi()
            ->beginIF($status == 'active')->andWhere('t2.status')->eq('active')->fi()
            ->orderBy('t1.`order` desc, t1.`story` desc')
            ->fetchAll('id');
        return empty($stories) ? array() : $this->formatStories($stories, $type);
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
        if(strpos($orderBy, 'module') !== false)
        {
            $orderBy = (strpos($orderBy, 'module_asc') !== false) ? 't3.path asc' : 't3.path desc';
            $stories = $this->dao->select('distinct t1.story, t1.plan, t1.order, t2.*')
                ->from(TABLE_PLANSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module = t3.id')
                ->where('t1.plan')->eq($planID)
                ->beginIF($status and $status != 'all')->andWhere('t2.status')->in($status)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $stories = $this->dao->select("distinct t1.story, t1.plan, t1.order, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) as priOrder")
                ->from(TABLE_PLANSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where('t1.plan')->eq($planID)
                ->beginIF($status and $status != 'all')->andWhere('t2.status')->in($status)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)
                ->fetchAll('id');
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

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
     * Get stories by plan id list.
     *
     * @param  string|array $planIdList
     * @access public
     * @return array
     */
    public function getStoriesByPlanIdList($planIdList = '')
    {
        return $this->dao->select('t1.plan as planID, t2.*')->from(TABLE_PLANSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t2.deleted')->eq(0)
            ->beginIF($planIdList)->andWhere('t1.plan')->in($planIdList)->fi()
            ->fetchGroup('planID', 'id');
    }

    /**
     * Get parent story pairs.
     *
     * @param  int    $productID
     * @param  string $append
     * @access public
     * @return void
     */
    public function getParentStoryPairs($productID, $append = '')
    {
        $stories = $this->dao->select('id, title')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->le(0)
            ->andWhere('type')->eq('story')
            ->andWhere('stage')->eq('wait')
            ->andWhere('status')->eq('active')
            ->andWhere('product')->eq($productID)
            ->andWhere('plan')->in('0,')
            ->andWhere('twins')->eq('')
            ->beginIF($append)->orWhere('id')->in($append)->fi()
            ->fetchPairs();
        return array(0 => '') + $stories ;
    }

    /**
     * Close requirement if all son story for segmentation has been closed.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function closeParentRequirement($storyID)
    {
        $parentID = $this->dao->select('BID')->from(TABLE_RELATION)->where('AID')->eq($storyID)->fetch();
        if(empty($parentID)) return;

        $stories  = $this->dao->select('t2.id, t2.status')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t2.id=t1.AID')
            ->where('t1.BType')->eq('requirement')
            ->andWhere('t1.BID')->eq($parentID->BID)
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.type')->eq('story')
            ->fetchPairs();
        if(empty($stories)) $this->close($parentID->BID);
    }

    /**
     * Get stories of a user.
     *
     * @param  string     $account
     * @param  string     $type         the query type
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  string     $storyType    requirement|story
     * @param  string|int $shadow       all | 0 | 1
     * @access public
     * @return array
     */
    public function getUserStories($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null, $storyType = 'story', $includeLibStories = true, $shadow = 0)
    {
        $sql = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t2.shadow as shadow")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id');
        if($type == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version');

        $stories = $sql->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq($storyType)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('t1.assignedTo')->eq($account)->fi()
            ->beginIF($type == 'reviewBy')->andWhere('t3.reviewer')->eq($account)->andWhere('t3.result')->eq('')->andWhere('t1.status')->in('reviewing,changing')->fi()
            ->beginIF($type == 'openedBy')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,$account,%")->fi()
            ->beginIF($type == 'closedBy')->andWhere('t1.closedBy')->eq($account)->fi()
            ->fi()
            ->beginIF($includeLibStories == false and ($this->config->edition == 'max' or $this->config->edition == 'ipd'))->andWhere('t1.lib')->eq('0')->fi()
            ->beginIF($shadow !== 'all')->andWhere('t2.shadow')->eq((int)$shadow)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);
        $productIdList = array();
        foreach($stories as $story) $productIdList[$story->product] = $story->product;

        return $this->mergePlanTitle($productIdList, $stories, 0, $storyType);
    }

    /**
     * Get story pairs of a user.
     *
     * @param  string    $account
     * @param  string    $limit
     * @param  string    $type requirement|story
     * @param  array     $skipProductIDList
     * @param  int|array $appendStoryID
     * @access public
     * @return array
     */
    public function getUserStoryPairs($account, $limit = 10, $type = 'story', $skipProductIDList = array(), $appendStoryID = 0)
    {
        return $this->dao->select('id, title')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->andWhere('type')->eq($type)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->andWhere('assignedTo')->eq($account)
            ->andWhere('product')->ne(0)
            ->beginIF(!empty($skipProductIDList))->andWhere('product')->notin($skipProductIDList)->fi()
            ->beginIF(!empty($appendStoryID))->orWhere('id')->in($appendStoryID)->fi()
            ->orderBy('id_desc')
            ->limit($limit)
            ->fetchPairs('id', 'title');
    }

    /**
     * Get the story ID list of the linked to task.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getIdListWithTask($executionID)
    {
        return $this->dao->select('story')->from(TABLE_TASK)
            ->where('execution')->eq($executionID)
            ->andWhere('story')->ne(0)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get team members for a project or execution.
     *
     * @param  int    $storyID
     * @param  string $actionType
     * @access public
     * @return array
     */
    public function getTeamMembers($storyID, $actionType)
    {
        $teamMembers = array();
        if($actionType == 'changed')
        {
            $executions = $this->dao->select('execution')->from(TABLE_TASK)
                ->where('story')->eq($storyID)
                ->andWhere('status')->ne('cancel')
                ->andWhere('deleted')->eq(0)
                ->fetchPairs();
            if($executions) $teamMembers = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->in($executions)->andWhere('type')->eq('execution')->fetchPairs('account');
        }
        else
        {
            $projects = $this->dao->select('t1.project')
                ->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.story')->eq((int)$storyID)
                ->andWhere('t2.status')->eq('doing')
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs();
            if($projects) $teamMembers = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->in($projects)->andWhere('type')->eq('project')->fetchPairs('account');
        }
        return $teamMembers;
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
        return (int)$this->dao->select('version')->from(TABLE_STORY)->where('id')->eq((int)$storyID)->fetch('version');
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
     * @param  int     $productID
     * @param  int     $branchID
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getZeroCase($productID, $branchID = 0, $orderBy = 'id_desc')
    {
        $allStories   = $this->getProductStories($productID, $branchID, 0, 'all', 'story', $orderBy, $hasParent = false, '', null);
        $casedStories = $this->dao->select('DISTINCT story')->from(TABLE_CASE)->where('product')->eq($productID)->andWhere('story')->ne(0)->andWhere('deleted')->eq(0)->fetchAll('story');

        foreach($allStories as $key => $story)
        {
            if(isset($casedStories[$story->id])) unset($allStories[$key]);
        }

        return $allStories;
    }

    /**
     * Get changed stories.
     *
     * @param  object $story
     * @access public
     * @return void
     */
    public function getChangedStories($story)
    {
        if($story->type == 'requirement') return array();

        $relations = $this->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere('BID')->eq($story->id)
            ->fetchAll('AID');

        if(empty($relations)) return array();

        $stories = $this->getByList(array_keys($relations));
        foreach($stories as $id => $story)
        {
            $version = $relations[$story->id]->AVersion;
            if($version > $story->version) unset($stories[$id]);
        }

        return $stories;
    }

    public function getAllStorySort($planID, $planOrder)
    {
        $orderBy = $this->post->orderBy;
        if(strpos($orderBy, 'order') !== false) $orderBy = str_replace('order', 'id', $orderBy);

        $stories     = $this->loadModel('story')->getPlanStories($planID, 'all');
        $storyIDList = array_keys($stories);

        if(strpos($this->post->orderBy, 'order') !== false and !empty($planOrder)) $stories = $this->sortPlanStory($stories, $planOrder, $orderBy);

        $frontCount   = (int)$this->post->recPerPage * ((int)$this->post->pageID - 1);
        $behindCount  = (int)$this->post->recPerPage * (int)$this->post->pageID;
        $frontIDList  = array_slice($storyIDList, 0, $frontCount);
        $behindIDList = array_slice($storyIDList, $behindCount, count($storyIDList) - $behindCount);

        $frontIDList  = !empty($frontIDList)  ? implode(',', $frontIDList) . ',' : '';
        $behindIDList = !empty($behindIDList) ? implode(',', $behindIDList) : '';
        return $frontIDList . $this->post->stories . $behindIDList;
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
     * @param  array|object    $object
     * @access public
     * @return array|object
     */
    public function checkNeedConfirm($data)
    {
        $objectList = is_object($data) ? array($data->id => $data) : $data;

        $storyIdList      = array();
        $storyVersionList = array();

        foreach($objectList as $key => $object)
        {
            $object->needconfirm = false;
            if($object->story)
            {
                $storyIdList[$key]      = $object->story;
                $storyVersionList[$key] = $object->storyVersion;
            }
        }

        $stories = $this->dao->select('id,version')->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('status')->eq('active')->fetchPairs('id', 'version');
        foreach($storyIdList as $key => $storyID)
        {
            if(isset($stories[$storyID]) and $stories[$storyID] > $storyVersionList[$key]) $objectList[$key]->needconfirm = true;
        }

        return is_object($data) ? reset($objectList) : $objectList;
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
        /* Format these stories. */
        $storyPairs = array(0 => '');
        $i = 0;
        foreach($stories as $story)
        {
            if($type == 'short')
            {
                $property = '[p' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ', ' . $story->estimate . "{$this->config->hourUnit}]";
            }
            elseif($type == 'full')
            {
                $property = '(' . $this->lang->story->pri . ':' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ',' . $this->lang->story->estimate . ':' . $story->estimate . ')';
            }
            else
            {
                $property = '';
            }
            $storyPairs[$story->id] = $story->id . ':' . $story->title . ' ' . $property;
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
        $datas = $this->dao->select('module as name, count(module) as value, product, branch')
            ->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('module,product,branch')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $branchIDList = array();
        foreach($datas as $key => $project)
        {
            if(!$project->branch) continue;
            $branchIDList[$project->branch] = $project->branch;
        }

        $branchs  = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->andWhere('deleted')->eq(0)->fetchALL('id');
        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas));

        foreach($datas as $moduleID => $data)
        {
            $branch = '';
            if(isset($branchs[$data->branch]->name))
            {
                $branch = '/' . $branchs[$data->branch]->name;
            }

            $data->name = $branch . (isset($modules[$moduleID]) ? $modules[$moduleID] : '/');
        }

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

        /* Separate for multi-plan key. */
        foreach($datas as $planID => $data)
        {
            if(strpos($planID, ',') !== false)
            {
                $planIdList = explode(',', $planID);
                foreach($planIdList as $multiPlanID)
                {
                    if(empty($datas[$multiPlanID]))
                    {
                        $datas[$multiPlanID] = new stdclass();
                        $datas[$multiPlanID]->name  = $multiPlanID;
                        $datas[$multiPlanID]->value = 0;
                    }
                    $datas[$multiPlanID]->value += $data->value;
                }
                unset($datas[$planID]);
            }
        }

        /* Fix bug #2697. */
        if(isset($datas['']))
        {
            if(empty($datas[0]))
            {
                $datas[0] = new stdclass();
                $datas[0]->name  = 0;
                $datas[0]->value = 0;
            }
            $datas[0]->value += $datas['']->value;
            unset($datas['']);
        }

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
     * Get kanban group data.
     *
     * @param  array    $stories
     * @access public
     * @return array
     */
    public function getKanbanGroupData($stories)
    {
        $storyGroup = array();
        foreach($stories as $story) $storyGroup[$story->stage][$story->id] = $story;

        return $storyGroup;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $story
     * @param  string    $actionType
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($story, $actionType)
    {
        /* Set toList and ccList. */
        $toList = $story->assignedTo;
        $ccList = str_replace(' ', '', trim($story->mailto, ','));

        /* If the action is changed or reviewed, mail to the project or execution team. */
        if(strtolower($actionType) == 'changed' or strtolower($actionType) == 'reviewed')
        {
            $teamMembers = $this->getTeamMembers($story->id, $actionType);
            if($teamMembers)
            {
                $ccList .= ',' . join(',', $teamMembers);
                $ccList = ltrim($ccList, ',');
            }
        }

        if(strtolower($actionType) == 'changed' or strtolower($actionType) == 'opened')
        {
            $reviewerList = $this->getReviewerPairs($story->id, $story->version);
            unset($reviewerList[$story->assignedTo]);

            $ccList .= ',' . join(',', array_keys($reviewerList));
        }

        if(empty($toList))
        {
            if(empty($ccList)) return false;
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
        elseif($story->status == 'closed')
        {
            $ccList .= ',' . $story->openedBy;
        }

        return array($toList, $ccList);
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
        global $app, $config;
        $action = strtolower($action);

        if($action == 'recall')     return strpos('reviewing,changing', $story->status) !== false;
        if($action == 'close')      return $story->status != 'closed';
        if($action == 'activate')   return $story->status == 'closed';
        if($action == 'assignto')   return $story->status != 'closed';
        if($action == 'batchcreate' and $story->parent > 0) return false;
        if($action == 'batchcreate' and !empty($story->twins)) return false;
        if($action == 'batchcreate' and $story->type == 'requirement' and $story->status != 'closed') return strpos('draft,reviewing,changing', $story->status) === false;
        if($action == 'submitreview' and strpos('draft,changing', $story->status) === false) return false;

        static $shadowProducts = array();
        static $taskGroups     = array();
        static $hasShadow      = true;
        if($hasShadow and empty($shadowProducts[$story->product]))
        {
            $stmt = $app->dbQuery('SELECT id FROM ' . TABLE_PRODUCT . " WHERE shadow = 1")->fetchAll();
            if(empty($stmt)) $hasShadow = false;
            foreach($stmt as $row) $shadowProducts[$row->id] = $row->id;
        }

        if($hasShadow and empty($taskGroups[$story->id])) $taskGroups[$story->id] = $app->dbQuery('SELECT id FROM ' . TABLE_TASK . " WHERE story = $story->id")->fetch();

        if($story->parent < 0 and strpos($config->story->list->actionsOpratedParentStory, ",$action,") === false) return false;

        if($action == 'batchcreate')
        {
            if($config->vision == 'lite' and ($story->status == 'active' and in_array($story->stage, array('wait', 'projected')))) return true;

            if($story->status != 'active' or !empty($story->plan)) return false;
            if(isset($shadowProducts[$story->product]) && (!empty($taskGroups[$story->id]) or $story->stage != 'projected')) return false;
            if(!isset($shadowProducts[$story->product]) && $story->stage != 'wait') return false;
        }

        $story->reviewer  = isset($story->reviewer)  ? $story->reviewer  : array();
        $story->notReview = isset($story->notReview) ? $story->notReview : array();
        $isSuperReviewer = strpos(',' . trim(zget($config->story, 'superReviewers', ''), ',') . ',', ',' . $app->user->account . ',');

        if($action == 'change') return (($isSuperReviewer !== false or count($story->reviewer) == 0 or count($story->notReview) == 0) and $story->status == 'active');
        if($action == 'review') return (($isSuperReviewer !== false or in_array($app->user->account, $story->notReview)) and $story->status == 'reviewing');

        return true;
    }

    /**
     * Build operate menu.
     *
     * @param  object $story
     * @param  string $type
     * @param  object $execution
     * @param  string $storyType story|requirement
     * @access public
     * @return string
     */
    public function buildOperateMenu($story, $type = 'view', $execution = '', $storyType = 'story')
    {
        $menu   = '';
        $params = "storyID=$story->id";

        static $taskGroups = array();

        if($type == 'browse')
        {
            if(!common::canBeChanged('story', $story)) return $this->buildMenu('story', 'close', $params . "&from=&storyType=$story->type", $story, 'list', '', '', 'iframe', true);

            $storyReviewer = isset($story->reviewer) ? $story->reviewer : array();
            if($story->URChanged) return $this->buildMenu('story', 'processStoryChange', $params, $story, $type, 'ok', '', 'iframe', true, '', $this->lang->confirm);

            $isClick = $this->isClickable($story, 'change');
            $title   = $isClick ? '' : $this->lang->story->changeTip;
            $menu   .= $this->buildMenu('story', 'change', $params . "&from=&storyType=$story->type", $story, $type, 'alter', '', 'showinonlybody', false, '', $title);

            if($story->status != 'reviewing')
            {
                $menu .= $this->buildMenu('story', 'submitReview', "storyID=$story->id&storyType=$story->type", $story, $type, 'confirm', '', 'iframe', true, "data-width='50%'");
            }
            else
            {
                $isClick = $this->isClickable($story, 'review');
                $title   = $this->lang->story->review;
                if(!$isClick and $story->status != 'closed')
                {
                    if($story->status == 'active')
                    {
                        $title = $this->lang->story->reviewTip['active'];
                    }
                    elseif($storyReviewer and in_array($this->app->user->account, $storyReviewer))
                    {
                        $title = $this->lang->story->reviewTip['reviewed'];
                    }
                    elseif($storyReviewer and !in_array($this->app->user->account, $storyReviewer))
                    {
                        $title = $this->lang->story->reviewTip['notReviewer'];
                    }
                }
                $menu .= $this->buildMenu('story', 'review', $params . "&from=&storyType=$story->type", $story, $type, 'search', '', 'showinonlybody', false, '', $title);
            }

            $isClick = $this->isClickable($story, 'recall');
            $title   = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
            $title   = $isClick ? $title : $this->lang->story->recallTip['actived'];
            $menu   .= $this->buildMenu('story', 'recall', $params . "&from=list&confirm=no&storyType=$story->type", $story, $type, 'undo', 'hiddenwin', 'showinonlybody', false, '', $title);
            $menu   .= $this->buildMenu('story', 'edit', $params . "&kanbanGroup=default&storyType=$story->type", $story, $type, '', '', 'showinonlybody');

            $vars            = "storyType={$story->type}";
            $canChange       = common::hasPriv('story', 'change', '', $vars);
            $canRecall       = common::hasPriv('story', 'recall', '', $vars);
            $canSubmitReview = (strpos('draft,changing', $story->status) !== false and common::hasPriv('story', 'submitReview', '', $vars));
            $canReview       = (strpos('draft,changing', $story->status) === false and common::hasPriv('story', 'review', '', $vars));
            $canEdit         = common::hasPriv('story', 'edit', '', $vars);
            $canBatchCreate  = ($this->app->tab == 'product' and (common::hasPriv('story', 'batchCreate', '', 'storyType=story')));
            $canCreateCase   = ($story->type == 'story' and common::hasPriv('testcase', 'create'));
            $canClose        = common::hasPriv('story', 'close', '', $vars);
            $canUnlinkStory  = ($this->app->tab == 'project' and common::hasPriv('projectstory', 'unlinkStory'));

            if(in_array($this->app->tab, array('product', 'project')))
            {
                if(($canChange or $canRecall or $canSubmitReview or $canReview or $canEdit) and ($canCreateCase or $canBatchCreate or $canClose or $canUnlinkStory))
                {
                    $menu .= "<div class='dividing-line'></div>";
                }
            }

            if($this->app->tab == 'product' and $storyType == 'requirement')
            {
                if($story->status != 'closed')
                {
                    $menu .= $this->buildMenu('story', 'close', $params . "&from=&storyType=$story->type", $story, $type, '', '', 'iframe', true);
                }
                else
                {
                    $menu .= $this->buildMenu('story', 'activate', $params . "&storyType=$story->type", $story, $type, '', '', 'iframe showinonlybody', true);
                }

                if($canClose and ($canBatchCreate or $canCreateCase)) $menu .= "<div class='dividing-line'></div>";
            }

            if($story->type != 'requirement' and $this->config->vision != 'lite') $menu .= $this->buildMenu('testcase', 'create', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$params", $story, $type, 'sitemap', '', 'iframe showinonlybody', true, "data-app='{$this->app->tab}'");

            $shadow = $this->dao->findByID($story->product)->from(TABLE_PRODUCT)->fetch('shadow');
            if($this->app->rawModule != 'projectstory' OR $this->config->vision == 'lite' OR $shadow OR $story->type == 'requirement')
            {
                if($shadow and empty($taskGroups[$story->id])) $taskGroups[$story->id] = $this->dao->select('id')->from(TABLE_TASK)->where('story')->eq($story->id)->fetch('id');

                $isClick = $this->isClickable($story, 'batchcreate');
                $title   = $story->type == 'story' ? $this->lang->story->subdivideSR : $this->lang->story->subdivide;
                if(!$isClick and $story->status != 'closed')
                {
                    if($story->parent > 0)
                    {
                        $title = $this->lang->story->subDivideTip['subStory'];
                    }
                    elseif(!empty($story->twins))
                    {
                        $title = $this->lang->story->subDivideTip['twinsSplit'];
                    }
                    else
                    {
                        if($story->status != 'active') $title = sprintf($this->lang->story->subDivideTip['notActive'], $story->type == 'story' ? $this->lang->SRCommon : $this->lang->URCommon);
                        if($story->status == 'active' and $story->stage != 'wait') $title = sprintf($this->lang->story->subDivideTip['notWait'], zget($this->lang->story->stageList, $story->stage));
                        if($story->status == 'active' and !empty($taskGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedTask);
                    }
                }

                $executionID = empty($execution) ? 0 : $execution->id;
                if($this->config->vision != 'or') $menu .= $this->buildMenu('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0&storyType=story", $story, $type, 'split', '', 'showinonlybody', '', '', $title);
            }

            if(($this->app->rawModule == 'projectstory' or ($this->app->tab != 'product' and $storyType == 'requirement')) and $this->config->vision != 'lite')
            {
                if($canClose) $menu .= "<div class='dividing-line'></div>";

                $menu .= $this->buildMenu('story', 'close', $params . "&from=&storyType=$story->type", $story, $type, '', '', 'iframe', true);
                if(!empty($execution) and $execution->hasProduct and !($storyType == 'requirement' and $story->type == 'story')) $menu .= $this->buildMenu('projectstory', 'unlinkStory', "projectID={$this->session->project}&$params", $story, $type, 'unlink', 'hiddenwin', 'showinonlybody');
            }

            if($this->app->tab == 'product' and $storyType == 'story')
            {
                if(($canBatchCreate or $canCreateCase) and $canClose) $menu .= "<div class='dividing-line'></div>";

                $menu .= $this->buildMenu('story', 'close', $params . "&from=&storyType=$story->type", $story, $type, '', '', 'iframe', true);
            }
        }

        if($type == 'view')
        {
            $menu .= $this->buildMenu('story', 'change', $params . "&from=&storyType=$story->type", $story, $type, 'alter', '', 'showinonlybody');
            if($story->status != 'reviewing') $menu .= $this->buildMenu('story', 'submitReview', $params . "&storyType=$story->type", $story, $type, 'confirm', '', 'showinonlybody iframe', true, "data-width='50%'");

            $title = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
            $menu .= $this->buildMenu('story', 'recall', $params . "&from=view&confirm=no&storyType=$story->type", $story, $type, 'undo', 'hiddenwin', 'showinonlybody', false, '', $title);

            $menu .= $this->buildMenu('story', 'review', $params . "&from={$this->app->tab}&storyType=$story->type", $story, $type, 'search', '', 'showinonlybody');

            $executionID = empty($execution) ? 0 : $execution->id;
            if(!isonlybody())
            {
                $subdivideTitle = $story->type == 'story' ? $this->lang->story->subdivideSR : $this->lang->story->subdivide;
                if($this->config->vision != 'or') $menu .= $this->buildMenu('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&$params&executionID=$executionID&plan=0&storyType=story", $story, $type, 'split', '', 'divideStory', true, "data-toggle='modal' data-type='iframe' data-width='95%'", $subdivideTitle);

            }

            $menu .= $this->buildMenu('story', 'assignTo', $params . "&kanbanGroup=default&from=&storyType=$story->type", $story, $type, '', '', 'iframe showinonlybody', true);
            $menu .= $this->buildMenu('story', 'close',    $params . "&from=&storyType=$story->type", $story, $type, '', '', 'iframe showinonlybody', true);
            $menu .= $this->buildMenu('story', 'activate', $params . "&storyType=$story->type", $story, $type, '', '', 'iframe showinonlybody', true);

            $disabledFeatures = ",{$this->config->disabledFeatures},";
            if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->app->tab == 'project' and common::hasPriv('story', 'importToLib') and strpos($disabledFeatures, ',assetlibStorylib,') === false and strpos($disabledFeatures, ',assetlib,') === false)
            {
                $menu .= html::a('#importToLib', "<i class='icon icon-assets'></i> " . $this->lang->story->importToLib, '', 'class="btn" data-toggle="modal"');
            }

            /* Print testcate actions. */
            if($story->parent >= 0 and $story->type != 'requirement' and (common::hasPriv('testcase', 'create', $story) or common::hasPriv('testcase', 'batchCreate', $story)))
            {
                $this->app->loadLang('testcase');
                $menu .= "<div class='btn-group dropup'>";
                $menu .= "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><i class='icon icon-sitemap'></i> " . $this->lang->testcase->common . " <span class='caret'></span></button>";
                $menu .= "<ul class='dropdown-menu' id='createCaseActionMenu'>";

                $misc = "data-toggle='modal' data-type='iframe' data-width='95%'";
                if(isonlybody()) $misc = '';

                if(common::hasPriv('testcase', 'create', $story))
                {
                    $link  = helper::createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=0&from=&param=0&$params", '', true);
                    $menu .= "<li>" . html::a($link, $this->lang->testcase->create, '', $misc) . "</li>";
                }

                if(common::hasPriv('testcase', 'batchCreate'))
                {
                    $link  = helper::createLink('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=0&$params", '', true);
                    $menu .= "<li>" . html::a($link, $this->lang->testcase->batchCreate, '', $misc) . "</li>";
                }

                $menu .= "</ul></div>";
            }

            if(($this->app->tab == 'execution' or (!empty($execution) and $execution->multiple === '0')) and $story->status == 'active' and $story->type == 'story') $menu .= $this->buildMenu('task', 'create', "execution={$this->session->execution}&{$params}&moduleID=$story->module", $story, $type, 'plus', '', 'showinonlybody');

            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildFlowMenu('story', $story, $type, 'direct');
            $menu .= "<div class='divider'></div>";

            $menu .= $this->buildMenu('story', 'edit', $params . "&kanbanGroup=default&storyType=$story->type", $story, $type);
            $menu .= $this->buildMenu('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&{$params}&executionID=0&bugID=0&planID=0&todoID=0&extra=&storyType=$story->type", $story, $type, 'copy', '', '', '', "data-width='1050'");
            $menu .= $this->buildMenu('story', 'delete', $params . "&confirm=no&from=&storyType=$story->type", $story, 'button', 'trash', 'hiddenwin', 'showinonlybody');
        }

        if($type == 'execution')
        {
            $hasDBPriv    = common::hasDBPriv($execution, 'execution');
            $canBeChanged = common::canModify('execution', $execution);
            if($canBeChanged)
            {
                $executionID = empty($execution) ? $this->session->execution : $execution->id;
                $param       = "executionID=$executionID&story={$story->id}&moduleID={$story->module}";

                $story->reviewer  = isset($story->reviewer)  ? $story->reviewer  : array();
                $story->notReview = isset($story->notReview) ? $story->notReview : array();

                $canSubmitReview    = (strpos('draft,changing', $story->status) !== false and common::hasPriv('story', 'submitReview'));
                $canReview          = (strpos('draft,changing', $story->status) === false and common::hasPriv('story', 'review'));
                $canRecall          = common::hasPriv('story', 'recall');
                $canCreateTask      = common::hasPriv('task', 'create');
                $canBatchCreateTask = common::hasPriv('task', 'batchCreate');
                $canCreateCase      = ($hasDBPriv and common::hasPriv('testcase', 'create'));
                $canEstimate        = common::hasPriv('execution', 'storyEstimate', $execution);
                $canUnlinkStory     = (common::hasPriv('execution', 'unlinkStory', $execution) and ($execution->hasProduct or $execution->multiple));

                if(strpos('draft,changing', $story->status) !== false)
                {
                    if($canSubmitReview) $menu .= common::buildIconButton('story', 'submitReview', "storyID=$story->id&from=story", $story, 'list', 'confirm', '', 'iframe', true, "data-width='50%'");
                }
                else
                {
                    if($canReview)
                    {
                        $reviewDisabled = in_array($this->app->user->account, $story->notReview) and ($story->status == 'draft' or $story->status == 'changing') ? '' : 'disabled';
                        $story->from = 'execution';
                        $menu .= common::buildIconButton('story', 'review', "story={$story->id}&from=execution", $story, 'list', 'search', '', $reviewDisabled, false, "data-group=execution");
                    }
                }

                if($canRecall)
                {
                    $recallDisabled = empty($story->reviewedBy) and strpos('draft,changing', $story->status) !== false and !empty($story->reviewer) ? '' : 'disabled';
                    $title  = $story->status == 'changing' ? $this->lang->story->recallChange : $this->lang->story->recall;
                    $menu  .= common::buildIconButton('story', 'recall', "story={$story->id}", $story, 'list', 'undo', 'hiddenwin', $recallDisabled, '', '', $title);
                }
                if(!$execution->hasProduct) $menu .= common::buildIconButton('story', 'edit', $params . "&kanbanGroup=default&storyType=$story->type", $story, 'list', '', '', 'showinonlybody');

                $this->lang->task->create = $this->lang->execution->wbs;
                $toTaskDisabled = $story->status == 'active' ? '' : 'disabled';
                if(commonModel::isTutorialMode())
                {
                    $wizardParams = helper::safe64Encode($param);
                    $menu .=  html::a(helper::createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams"), "<i class='icon-plus'></i>",'', "class='btn btn-task-create' title='{$this->lang->execution->wbs}' data-app='{$this->app->tab}'");
                }
                else
                {
                    if($hasDBPriv and $storyType == 'story') $menu .= common::buildIconButton('task', 'create', $param, '', 'list', 'plus', '', 'btn-task-create ' . $toTaskDisabled);
                }

                $this->lang->task->batchCreate = $this->lang->execution->batchWBS;
                if($hasDBPriv and $storyType == 'story') $menu .= common::buildIconButton('task', 'batchCreate', "executionID=$executionID&story={$story->id}", '', 'list', 'pluses', '', $toTaskDisabled);

                if(($canSubmitReview or $canReview or $canRecall or $canCreateTask or $canBatchCreateTask) and ($canCreateCase or $canEstimate or $canUnlinkStory)) $menu .= "<div class='dividing-line'></div>";
                if($canEstimate and $storyType == 'story') $menu .= common::buildIconButton('execution', 'storyEstimate', "executionID=$executionID&storyID=$story->id", '', 'list', 'estimate', '', 'iframe', true, "data-width='470px'");

                $this->lang->testcase->batchCreate = $this->lang->testcase->create;
                if($canCreateCase and $storyType == 'story') $menu .= common::buildIconButton('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&form=&param=0&storyID=$story->id", '', 'list', 'sitemap', '', 'iframe', true, "data-app='{$this->app->tab}'");

                if(($canEstimate or $canCreateCase) and $canUnlinkStory) $menu .= "<div class='dividing-line'></div>";

                $executionID = empty($execution) ? 0 : $execution->id;

                /* Adjust code, hide split entry. */
                if(common::hasPriv('story', 'batchCreate') and !$execution->multiple and !$execution->hasProduct)
                {
                    if(empty($taskGroups[$story->id])) $taskGroups[$story->id] = $this->dao->select('id')->from(TABLE_TASK)->where('story')->eq($story->id)->fetch('id');

                    $isClick = $this->isClickable($story, 'batchcreate');
                    $title   = $story->type == 'story' ? $this->lang->story->subdivideSR : $this->lang->story->subdivide;
                    if(!$isClick and $story->status != 'closed')
                    {
                        if($story->parent > 0)
                        {
                            $title = $this->lang->story->subDivideTip['subStory'];
                        }
                        else
                        {
                            if($story->status != 'active') $title = sprintf($this->lang->story->subDivideTip['notActive'], $story->type == 'story' ? $this->lang->SRCommon : $this->lang->URCommon);
                            if($story->status == 'active' and $story->stage != 'wait') $title = sprintf($this->lang->story->subDivideTip['notWait'], zget($this->lang->story->stageList, $story->stage));
                            if($story->status == 'active' and !empty($taskGroups[$story->id])) $title = sprintf($this->lang->story->subDivideTip['notWait'], $this->lang->story->hasDividedTask);
                        }
                    }

                    $menu .= $this->buildMenu('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID=$executionID&plan=0&storyType=story", $story, 'browse', 'split', '', 'showinonlybody', '', '', $title);
                }

                if(common::hasPriv('story', 'close', "storyType={$story->type}") and !$execution->multiple and !$execution->hasProduct) $menu .= $this->buildMenu('story', 'close', $params . "&from=&storyType=$story->type", $story, 'browse', '', '', 'iframe', true);
                if($canUnlinkStory) $menu .= common::buildIconButton('execution', 'unlinkStory', "executionID=$executionID&storyID=$story->id&confirm=no", '', 'list', 'unlink', 'hiddenwin');
            }
        }

        return $menu;
    }

    /**
     * Merge plan title.
     *
     * @param  int|array $productID
     * @param  array     $stories
     * @param  int       $branch
     *
     * @access public
     * @return array
     */
    public function mergePlanTitle($productID, $stories, $branch = 0, $type = 'story')
    {
        $query = $this->dao->get();
        if(is_array($branch))
        {
            unset($branch[0]);
            $branch = join(',', $branch);
            if($branch) $branch = "0,$branch";
        }
        $plans = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)
            ->Where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->in($productID)->fi()
            ->fetchPairs('id', 'title');

        /* For requirement children. */
        if($type == 'requirement')
        {
            $relations = $this->dao->select('DISTINCT AID, BID')->from(TABLE_RELATION)
              ->where('AType')->eq('requirement')
              ->andWhere('BType')->eq('story')
              ->andWhere('relation')->eq('subdivideinto')
              ->andWhere('AID')->in(array_keys($stories))
              ->fetchAll();

            $group = array();
            foreach($relations as $relation) $group[$relation->AID][] = $relation->BID;
            foreach($stories as $story)
            {
                if(!isset($group[$story->id])) continue;
                $story->children = $this->getByList($group[$story->id]);

                /* export requirement linkstories. */
                foreach($story->children as $child) $story->linkStories .= $child->title . ',';
            }
        }

        $parents      = array();
        $tmpStories   = array();
        $childStories = array();
        foreach($stories as $story)
        {
            $tmpStories[$story->id] = $story;
            if($story->parent > 0) $parents[$story->parent] = $story->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($parents)->fetchAll('id');

        foreach($stories as $storyID => $story)
        {
            /* export story linkstories. */
            if($story->parent == -1)
            {
                $childrenTitle      = $this->dao->select('title')->from(TABLE_STORY)->where('parent')->eq($story->id)->fetchAll();
                $childrenTitle      = helper::arrayColumn($childrenTitle, 'title');
                $story->linkStories = implode(',', $childrenTitle);
            }

            if($story->parent > 0)
            {
                if(isset($stories[$story->parent]))
                {
                    $stories[$story->parent]->children[$story->id] = $story;
                    unset($stories[$storyID]);
                }
                else
                {
                    $parent = $parents[$story->parent];
                    $story->parentName = $parent->title;
                }
            }

            $story->planTitle = '';
            $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID) $story->planTitle .= zget($plans, $planID, '') . ' ';
        }

        /* For save session query. */
        $this->dao->sqlobj->sql = $query;
        return $stories;
    }

    /**
     * Merge story reviewers.
     *
     * @param  array|object  $stories
     * @param  bool          $isObject
     * @access public
     * @return array|object
     */
    public function mergeReviewer($stories, $isObject = false)
    {
        if($isObject)
        {
            $story   = $stories;
            $stories = (array)$stories;
            $stories[$story->id] = $story;
        }

        /* Set child story id into array. */
        $storyIdList = isset($stories['id']) ? array($stories['id'] => $stories['id']) : array_keys($stories);
        if(isset($stories['id']) and isset($story->children)) $storyIdList = array_merge($storyIdList, array_keys($story->children));
        if(!isset($stories['id']))
        {
            foreach($stories as $story)
            {
                if(isset($story->children)) $storyIdList = array_merge($storyIdList, array_keys($story->children));
            }
        }

        $allReviewers = $this->dao->select('story,reviewer,result')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.version=t2.version and t1.id=t2.story')
            ->where('story')->in($storyIdList)
            ->fetchGroup('story', 'reviewer');

        foreach($allReviewers as $storyID => $reviewerList)
        {
            if(isset($stories[$storyID]))
            {
                $stories[$storyID]->reviewer  = array_keys($reviewerList);
                $stories[$storyID]->notReview = array();
                foreach($reviewerList as $reviewer => $reviewInfo)
                {
                    if($reviewInfo->result == '') $stories[$storyID]->notReview[] = $reviewer;
                }
            }
            else
            {
                foreach($stories as $id => $story)
                {
                    if(!isset($story->children)) continue;
                    if(isset($story->children[$storyID]))
                    {
                        $story->children[$storyID]->reviewer  = array_keys($reviewerList);
                        $story->children[$storyID]->notReview = array();
                        foreach($reviewerList as $reviewer => $reviewInfo)
                        {
                            if($reviewInfo->result == '') $story->children[$storyID]->notReview[] = $reviewer;
                        }
                    }
                }
            }
        }

        if($isObject) return $stories[$story->id];
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
    public function printCell($col, $story, $users, $branches, $storyStages, $modulePairs = array(), $storyTasks = array(), $storyBugs = array(), $storyCases = array(), $mode = 'datatable', $storyType = 'story', $execution = '', $isShowBranch = '')
    {
        $tab         = $this->app->tab;
        $executionID = empty($execution) ? $this->session->execution : $execution->id;
        $account     = $this->app->user->account;
        $storyLink   = helper::createLink('story', 'view', "storyID=$story->id&version=0&param=&storyType=$story->type") . "#app=$tab";
        $canView     = common::hasPriv($story->type, 'view', null, "storyType=$story->type");
        if($this->config->vision == 'or') $this->app->loadLang('demand');

        if($tab == 'project')
        {
            if($this->session->multiple)
            {
                $storyLink = helper::createLink('projectstory', 'view', "storyID=$story->id&project={$this->session->project}");
                $canView   = common::hasPriv('projectstory', 'view');
            }
            else
            {
                $storyLink = helper::createLink('story', 'view', "storyID=$story->id&version=0&param={$this->session->execution}&storyType=$story->type");
            }
        }
        elseif($tab == 'execution')
        {
            $storyLink = helper::createLink('execution', 'storyView', "storyID=$story->id&execution={$this->session->execution}");
            $canView   = common::hasPriv('execution', 'storyView');
        }

        /* Check the product is closed. */
        $canBeChanged = common::canBeChanged('story', $story);
        $canOrder     = common::hasPriv('execution', 'storySort');

        $canBatchEdit         = common::hasPriv('story',        'batchEdit');
        $canBatchClose        = common::hasPriv($story->type,   'batchClose');
        $canBatchReview       = common::hasPriv('story',        'batchReview');
        $canBatchChangeStage  = common::hasPriv('story',        'batchChangeStage');
        $canBatchChangeBranch = common::hasPriv($story->type,   'batchChangeBranch');
        $canBatchChangeModule = common::hasPriv($story->type,   'batchChangeModule');
        $canBatchChangePlan   = common::hasPriv('story',        'batchChangePlan');
        $canBatchAssignTo     = common::hasPriv($story->type,   'batchAssignTo');
        $canBatchUnlinkStory  = common::hasPriv('projectstory', 'batchUnlinkStory');
        $canBatchUnlink       = common::hasPriv('execution',    'batchUnlinkStory');

        if($tab == 'execution')
        {
            $checkObject = new stdclass();
            $checkObject->execution = $executionID;

            $canBatchToTask = common::hasPriv('story', 'batchToTask', $checkObject);
        }

        if($tab == 'execution')
        {
            $canBatchAction = ($canBeChanged and ($canBatchEdit or $canBatchClose or $canBatchChangeStage or $canBatchUnlink or $canBatchToTask));
        }
        elseif($tab == 'project')
        {
            $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeBranch or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo or $canBatchUnlinkStory);
        }
        else
        {
            $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeBranch or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo);
        }

        $id = $col->id;
        if($col->show)
        {
            $class = "c-{$id}";
            $title = '';
            $style = '';

            if($id == 'assignedTo')
            {
                $title = zget($users, $story->assignedTo, $story->assignedTo);
                if($story->assignedTo == $account) $class .= ' red';
            }
            elseif($id == 'openedBy')
            {
                $title = zget($users, $story->openedBy, $story->openedBy);
            }
            elseif($id == 'title')
            {
                $title  = $story->title;
                $class .= ' text-ellipsis';
                if(!empty($story->children)) $class .= ' has-child';
            }
            elseif($id == 'plan')
            {
                $title  = isset($story->planTitle) ? $story->planTitle : '';
                $class .= ' text-ellipsis';
            }
            elseif($id == 'branch')
            {
                $title  = zget($branches, $story->branch, '');
                $class .= ' text-ellipsis';
            }
            elseif($id == 'sourceNote')
            {
                $title  = $story->sourceNote;
                $class .= ' text-ellipsis';
            }
            elseif($id == 'category')
            {
                $title  = zget($this->lang->story->categoryList, $story->category);
            }
            elseif($id == 'estimate')
            {
                $title = $story->estimate . ' ' . $this->lang->hourCommon;
            }
            elseif($id == 'reviewedBy')
            {
                $reviewedBy = '';
                foreach(explode(',', $story->reviewedBy) as $user) $reviewedBy .= zget($users, $user) . ' ';
                $story->reviewedBy = trim($reviewedBy);

                $title  = $reviewedBy;
                $class .= ' text-ellipsis';
            }
            elseif($id == 'stage')
            {
                $style .= 'overflow: visible;';

                $maxStage    = $story->stage;
                $stageList   = join(',', array_keys($this->lang->story->stageList));
                $maxStagePos = strpos($stageList, $maxStage);
                if(isset($storyStages[$story->id]))
                {
                    foreach($storyStages[$story->id] as $storyBranch => $storyStage)
                    {
                        if(strpos($stageList, $storyStage->stage) !== false and strpos($stageList, $storyStage->stage) > $maxStagePos)
                        {
                            $maxStage    = $storyStage->stage;
                            $maxStagePos = strpos($stageList, $storyStage->stage);
                        }
                    }
                }
                $title .= $this->lang->story->stageList[$maxStage];
            }
            elseif($id == 'feedbackBy')
            {
                $title = $story->feedbackBy;
            }
            elseif($id =='version')
            {
                $title = $story->version;
                $class = 'text-center';
            }
            elseif($id == 'notifyEmail')
            {
                $title = $story->notifyEmail;
            }
            elseif($id == 'actions')
            {
                $class .= ' text-left';
            }
            elseif($id == 'order')
            {
                $class = 'sort-handler c-sort';
            }

            echo "<td class='" . $class . "' title='$title' style='$style'>";
            switch($id)
            {
            case 'id':
                if($canBatchAction and ($storyType == 'story' or ($storyType == 'requirement' and $story->type == 'requirement'))) echo html::checkbox('storyIdList', array($story->id => ''));
                if($canBatchAction and $storyType == 'requirement' and $story->type == 'story') echo "<span class='c-span'></span>";
                echo $canView ? html::a($storyLink, sprintf('%03d', $story->id), '', "data-app='$tab'") : sprintf('%03d', $story->id);
                break;
            case 'order':
                echo "<i class='icon-move'>";
                break;
            case 'pri':
                echo "<span class='" . ($story->pri ? "label-pri label-pri-" . $story->pri : '') . "' title='" . zget($this->lang->story->priList, $story->pri, $story->pri) . "'>";
                echo zget($this->lang->story->priList, $story->pri, $story->pri);
                echo "</span>";
                break;
            case 'title':
                if($tab == 'project')
                {
                    $showBranch = isset($this->config->projectstory->story->showBranch) ? $this->config->projectstory->story->showBranch : 1;
                }
                elseif($tab == 'execution')
                {
                    $showBranch = 0;
                    if($isShowBranch) $showBranch = isset($this->config->execution->story->showBranch) ? $this->config->execution->story->showBranch : 1;
                }
                else
                {
                    $showBranch = isset($this->config->product->browse->showBranch) ? $this->config->product->browse->showBranch : 1;
                }
                $titleHtml = '';
                if($storyType == 'requirement' and $story->type == 'story') $titleHtml .= '<span class="label label-badge label-light">SR</span> ';
                if($story->parent > 0 and isset($story->parentName)) $titleHtml .= "{$story->parentName} / ";
                if(isset($branches[$story->branch]) and $showBranch and $this->config->vision != 'lite') $titleHtml .= "<span class='label label-outline label-badge' title={$branches[$story->branch]}>{$branches[$story->branch]}</span> ";
                if($story->module and isset($modulePairs[$story->module])) $titleHtml .= "<span class='label label-gray label-badge'>{$modulePairs[$story->module]}</span> ";
                if($story->parent > 0) $titleHtml .= '<span class="label label-badge label-light" title="' . $this->lang->story->children . '">' . $this->lang->story->childrenAB . '</span> ';
                echo $canView ? html::a($storyLink, $titleHtml . $story->title, '', "title='$story->title' style='color: $story->color' data-app='$tab'") : "<span style='color: $story->color'>{$titleHtml}{$story->title}</span>";
                if(!empty($story->children)) echo '<a class="story-toggle" data-id="' . $story->id . '"><i class="icon icon-angle-right"></i></a>';
                break;
            case 'plan':
                echo isset($story->planTitle) ? $story->planTitle : '';
                break;
            case 'branch':
                echo zget($branches, $story->branch, '');
                break;
            case 'keywords':
                echo $story->keywords;
                break;
            case 'source':
                echo zget($this->lang->story->sourceList, $story->source, $story->source);
                break;
            case 'sourceNote':
                echo $story->sourceNote;
                break;
            case 'category':
                echo zget($this->lang->story->categoryList, $story->category);
                break;
            case 'status':
                if($story->URChanged)
                {
                    print("<span class='status-story status-changed'>{$this->lang->story->URChanged}</span>");
                    break;
                }
                echo "<span class='status-{$story->status}'>";
                echo $this->processStatus('story', $story);
                echo '</span>';
                break;
            case 'estimate':
                echo $story->estimate . $this->config->hourUnit;
                break;
            case 'stage':
                echo $this->lang->story->stageList[$maxStage];
                break;
            case 'taskCount':
                $tasksLink = helper::createLink('story', 'tasks', "storyID=$story->id", '', 'class="iframe"');
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
                echo helper::isZeroDate($story->openedDate) ? '' : substr($story->openedDate, 5, 11);
                break;
            case 'assignedTo':
                $this->printAssignedHtml($story, $users);
                break;
            case 'assignedDate':
                echo helper::isZeroDate($story->assignedDate) ? '' : substr($story->assignedDate, 5, 11);
                break;
            case 'activatedDate':
                echo helper::isZeroDate($story->activatedDate) ? '' : substr($story->activatedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo $story->reviewedBy;
                break;
            case 'reviewedDate':
                echo helper::isZeroDate($story->reviewedDate) ? '' : substr($story->reviewedDate, 5, 11);
                break;
            case 'closedBy':
                echo zget($users, $story->closedBy, $story->closedBy);
                break;
            case 'closedDate':
                echo helper::isZeroDate($story->closedDate) ? '' : substr($story->closedDate, 5, 11);
                break;
            case 'closedReason':
                echo zget($this->lang->story->reasonList, $story->closedReason, $story->closedReason);
                break;
            case 'lastEditedBy':
                echo zget($users, $story->lastEditedBy, $story->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo helper::isZeroDate($story->lastEditedDate) ? '' : substr($story->lastEditedDate, 5, 11);
                break;
            case 'feedbackBy':
                echo $story->feedbackBy;
                break;
            case 'notifyEmail':
                echo $story->notifyEmail;
                break;
            case 'mailto':
                $mailto = explode(',', $story->mailto);
                foreach($mailto as $account)
                {
                    $account = trim($account);
                    if(empty($account)) continue;
                    echo zget($users, $account) . ' &nbsp;';
                }
                break;
            case 'version':
                echo $story->version;
                break;
            case 'duration':
                echo zget($this->lang->demand->durationList, $story->duration);
                break;
            case 'BSA':
                echo zget($this->lang->demand->bsaList, $story->BSA);
                break;
            case 'actions':
                if($tab == 'execution' or ($tab == 'project' and isset($_SESSION['multiple']) and empty($_SESSION['multiple'])))
                {
                    $menuType = 'execution';
                    if($storyType == 'requirement') $menuType = 'browse';
                }
                else
                {
                    $menuType = 'browse';
                }
                echo $this->buildOperateMenu($story, $menuType, $execution, $storyType);
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Product module story page add assignment function.
     *
     * @param  object    $story
     * @param  array     $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($story, $users, $print = true)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $story->assignedTo);

        if(empty($story->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->task->noAssigned;
        }

        if($story->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($story->assignedTo) and $story->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $story->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('story', 'assignTo', "storyID=$story->id&kanbanGroup=default&from=&storyType=$story->type", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span>{$assignedToText}</span>", '', "class='$btnClass' data-toggle='modal'");
        $assignToHtml = !common::hasPriv($story->type, 'assignTo', $story) ? "<span style='padding-left: 21px' class='$btnTextClass'>{$assignedToText}</span>" : $assignToHtml;

        if(!$print) return $assignToHtml;
        print($assignToHtml);
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
                preg_match_all('/[`"]' . trim(TABLE_STORY, '`') .'[`"] AS ([\w]+) /', $this->session->storyQueryCondition, $matches);

                $tableAlias = isset($matches[1][0]) ? $matches[1][0] . '.' : '';
                return 'id in (' . preg_replace('/SELECT .* FROM/', "SELECT $tableAlias" . "id FROM", $this->session->storyQueryCondition) . ')';
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

        $forceField       = $this->config->story->needReview == 0 ? 'forceReview' : 'forceNotReview';
        $forceReviewRoles = !empty($this->config->story->{$forceField . 'Roles'}) ? $this->config->story->{$forceField . 'Roles'} : '';
        $forceReviewDepts = !empty($this->config->story->{$forceField . 'Depts'}) ? $this->config->story->{$forceField . 'Depts'} : '';

        $forceUsers = '';
        if(!empty($this->config->story->{$forceField})) $forceUsers = $this->config->story->{$forceField};

        if(!empty($forceReviewRoles) or !empty($forceReviewDepts))
        {
            $users = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq(0)
                ->andWhere(0, true)
                ->beginIF(!empty($forceReviewRoles))
                ->orWhere('(role', true)->in($forceReviewRoles)
                ->andWhere('role')->ne('')
                ->markRight(1)
                ->fi()
                ->beginIF(!empty($forceReviewDepts))->orWhere('dept')->in($forceReviewDepts)->fi()
                ->markRight(1)
                ->fetchAll('account');

            $forceUsers .= "," . implode(',', array_keys($users));
        }

        $forceReview = $this->config->story->needReview == 0 ? strpos(",{$forceUsers},", ",{$this->app->user->account},") !== false : strpos(",{$forceUsers},", ",{$this->app->user->account},") === false;

        return $forceReview;
    }

    /**
     * Get tracks.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $projectID
     * @param  object $pager
     * @access public
     * @return bool|array
     */
    public function getTracks($productID = 0, $branch = 0, $projectID = 0, $pager = null)
    {
        $tracks         = array();
        $sourcePageID   = $pager->pageID;
        $excludeStories = false;

        if($this->config->URAndSR)
        {
            $projectStories = array();
            if($projectID)
            {
                $requirements = $this->dao->select('t3.*')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_RELATION)->alias('t2')->on("t1.story=t2.AID && t2.AType='story'")
                    ->leftJoin(TABLE_STORY)->alias('t3')->on("t2.BID=t3.id && t2.BType='requirement' && t3.deleted='0'")
                    ->where('t1.project')->eq($projectID)
                    ->andWhere('t1.product')->eq($productID)
                    ->andWhere('t3.id')->ne('')
                    ->page($pager, 't3.id')
                    ->fetchAll('id');
                $projectStories = $this->getExecutionStories($projectID, $productID, $branch, '`order`_desc', 'all', 0, 'story');
            }
            else
            {
                $requirements = $this->getProductStories($productID, $branch, 0, 'all', 'requirement', 'id_desc', true, '', $pager);
            }

            if($pager->pageID != $sourcePageID)
            {
                $requirements  = array();
                $pager->pageID = $sourcePageID;
            }

            foreach($requirements as $requirement)
            {
                $stories = $this->getRelation($requirement->id, 'requirement', array('id', 'title', 'parent'));
                $stories = empty($stories) ? array() : $stories;
                foreach($stories as $id => $story)
                {
                    if($projectStories and !isset($projectStories[$id]))
                    {
                        unset($stories[$id]);
                        continue;
                    }

                    $stories[$id] = new stdclass();
                    $stories[$id]->parent = $story->parent;
                    $stories[$id]->title  = $story->title;
                    $stories[$id]->cases  = $this->loadModel('testcase')->getStoryCases($id);
                    $stories[$id]->bugs   = $this->loadModel('bug')->getStoryBugs($id);
                    $stories[$id]->tasks  = $this->loadModel('task')->getStoryTasks($id);
                    if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                    {
                        $stories[$id]->designs   = $this->dao->select('id, name')->from(TABLE_DESIGN)
                            ->where('story')->eq($id)
                            ->andWhere('deleted')->eq('0')
                            ->fetchAll('id');
                        $stories[$id]->revisions = $this->dao->select('BID, t2.comment')->from(TABLE_RELATION)->alias('t1')
                            ->leftjoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.BID = t2.id')
                            ->where('t1.AType')->eq('design')
                            ->andWhere('t1.BType')->eq('commit')
                            ->andWhere('t1.AID')->in(array_keys($stories[$id]->designs))
                            ->fetchPairs();
                    }
                }

                $requirement->track = $stories;
            }

            $tracks = $requirements;

            /* Get no requirements story. */
            $excludeStories = $this->dao->select('t1.BID')->from(TABLE_RELATION)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on("t1.AID=t2.id")
                ->where('t2.deleted')->eq('0')
                ->andWhere('t1.AType')->eq('requirement')
                ->andWhere('t1.BType')->eq('story')
                ->andWhere('t1.relation')->eq('subdivideinto')
                ->andWhere('t1.product')->eq($productID)
                ->fetchPairs('BID', 'BID');
            if($projectID)
            {
                $stories = $this->getExecutionStories($projectID, $productID, $branch, '`order`_desc', 'all', 0, 'story', $excludeStories);
            }
            else
            {
                $stories = $this->getProductStories($productID, $branch, 0, 'all', 'story', 'id_desc', true, $excludeStories);
            }
        }
        else
        {
            if($projectID)
            {
                $stories = $this->getExecutionStories($projectID, $productID, $branch, '`order`_desc', 'all', 0, 'story', $excludeStories, '', $pager);
            }
            else
            {
                $stories = $this->getProductStories($productID, $branch, 0, 'all', 'story', 'id_desc', true, $excludeStories, $pager);
            }
        }

        if(count($tracks) < $pager->recPerPage)
        {
            /* Show sub stories. */
            $storiesCopy = array();
            foreach($stories as $id => $story)
            {
                $storiesCopy[$id] = $story;

                if(!isset($story->children) or count($story->children) == 0) continue;
                foreach($story->children as $childID => $children)
                {
                    $storiesCopy[$childID] = $children;
                }
            }
            $stories = $storiesCopy;

            foreach($stories as $id => $story)
            {
                $stories[$id] = new stdclass();
                $stories[$id]->parent = $story->parent;
                $stories[$id]->title  = $story->title;
                $stories[$id]->cases  = $this->loadModel('testcase')->getStoryCases($id);
                $stories[$id]->bugs   = $this->loadModel('bug')->getStoryBugs($id);
                $stories[$id]->tasks  = $this->loadModel('task')->getStoryTasks($id, 0, $projectID);
                if($this->config->edition == 'max' or $this->config->edition == 'ipd')
                {
                    $stories[$id]->designs   = $this->dao->select('id, name')->from(TABLE_DESIGN)
                        ->where('story')->eq($id)
                        ->andWhere('deleted')->eq('0')
                        ->fetchAll('id');
                    $stories[$id]->revisions = $this->dao->select('BID, t2.comment')->from(TABLE_RELATION)->alias('t1')
                        ->leftjoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.BID = t2.id')
                        ->where('t1.AType')->eq('design')
                        ->andWhere('t1.BType')->eq('commit')
                        ->andWhere('t1.AID')->in(array_keys($stories[$id]->designs))
                        ->fetchPairs();
                }
            }

            $tracks['noRequirement'] = $stories;
            if($this->config->URAndSR) $pager->recTotal += 1;
        }

        return $tracks;
    }

    /**
     * Get track by id.
     *
     * @param  int  $storyID
     * @access public
     * @return bool|array
     */
    public function getTrackByID($storyID)
    {
        $requirement = $this->getByID($storyID);

        $stories = $this->getRelation($requirement->id, 'requirement');
        $track   = array();
        $stories = empty($stories) ? array() : $stories;
        foreach($stories as $id => $title)
        {
            $track[$id] = new stdclass();
            $track[$id]->title = $title;
            $track[$id]->case  = $this->loadModel('testcase')->getStoryCases($id);
            $track[$id]->bug   = $this->loadModel('bug')->getStoryBugs($id);
            $track[$id]->story = $this->getByID($id);
            $track[$id]->task  = $this->loadModel('task')->getStoryTasks($id);
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $track[$id]->design   = $this->dao->select('id, name')->from(TABLE_DESIGN)
                    ->where('story')->eq($id)
                    ->andWhere('deleted')->eq('0')
                    ->fetchAll('id');
                $track[$id]->revision = $this->dao->select('BID, extra')->from(TABLE_RELATION)->where('AType')->eq('design')->andWhere('BType')->eq('commit')->andWhere('AID')->in(array_keys($track[$id]->design))->fetchPairs();
            }
        }

        return $track;
    }

    /**
     * Obtain the direct relationship between UR and SR.
     *
     * @param  int    $storyID
     * @param  string $storyType
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function getStoryRelation($storyID, $storyType, $fields = array())
    {
        $conditionField = $storyType == 'story' ? 'BID' : 'AID';
        $storyType      = $storyType == 'story' ? 'AID' : 'BID';

        $relations = $this->dao->select($storyType)->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere($conditionField)->eq($storyID)
            ->fetchPairs();

        if(empty($relations)) return array();

        $fields = empty($fields) ? '*' : implode(',', $fields);
        $story  = $this->dao->select($fields)->from(TABLE_STORY)
            ->where('id')->in($relations)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchAll();

        return $story;
    }

    /**
     * Get story relation by Ids.
     *
     * @param  array  $storyIdList
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getStoryRelationByIds($storyIdList, $storyType)
    {
        $conditionField = $storyType == 'story' ? 'BID' : 'AID';
        $storyType      = $storyType == 'story' ? 'BID, GROUP_CONCAT(`AID` SEPARATOR ",")' : 'AID, GROUP_CONCAT(`BID` SEPARATOR ",")';

        $relations = $this->dao->select($storyType)->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere($conditionField)->in($storyIdList)
            ->groupBy($conditionField)
            ->fetchPairs();

        return $relations;
    }

    /**
     * Link a story.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function linkStory($executionID, $productID, $storyID)
    {
        $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->limit(1)->fetch('order');
        $this->dao->replace(TABLE_PROJECTSTORY)
            ->set('project')->eq($executionID)
            ->set('product')->eq($productID)
            ->set('story')->eq($storyID)
            ->set('version')->eq(1)
            ->set('order')->eq($lastOrder + 1)
            ->exec();
    }

    /**
     * Link stories.
     *
     * @param  int $storyID
     * @access public
     * @return void
     */
    public function linkStories($storyID)
    {
        $story   = $this->getByID($storyID);
        $stories = $this->post->stories;
        $isStory = ($story->type == 'story');

        foreach($stories as $id)
        {
            $requirement = $this->getByID($id);
            $data = new stdclass();
            $data->AType    = 'requirement';
            $data->BType    = 'story';
            $data->product  = $story->product;
            $data->relation = 'subdivideinto';
            $data->AID      = $isStory ? $id : $storyID;
            $data->BID      = $isStory ? $storyID : $id;
            $data->AVersion = $isStory ? $requirement->version : $story->version;
            $data->BVersion = $isStory ? $story->version : $requirement->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'story';
            $data->BType    = 'requirement';
            $data->relation = 'subdividedfrom';
            $data->product  = $story->product;
            $data->AID      = $isStory ? $storyID : $id;
            $data->BID      = $isStory ? $id : $storyID;
            $data->AVersion = $isStory ? $story->version : $requirement->version;
            $data->BVersion = $isStory ? $requirement->version : $story->version;

            $this->dao->insert(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }
    }

    /**
     * Unlink story.
     *
     * @param  int $storyID
     * @param  int $linkedStoryID
     * @access public
     * @return void
     */
    public function unlinkStory($storyID, $linkedStoryID)
    {
        $idList = "$storyID,$linkedStoryID";

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->in('story,requirement')
            ->andWhere('BType')->in('story,requirement')
            ->andWhere('relation')->in('subdivideinto,subdividedfrom')
            ->andWhere('AID')->in($idList)
            ->andWhere('BID')->in($idList)
            ->exec();

        return !dao::isError();
    }

    /**
     * Get associated requirements.
     *
     * @param  int     $storyID
     * @param  string  $storyType
     * @param  array   $fields
     * @access public
     * @return array
     */
    public function getRelation($storyID, $storyType, $fields = array())
    {
        $BType    = $storyType == 'story' ? 'requirement' : 'story';
        $relation = $storyType == 'story' ? 'subdividedfrom' : 'subdivideinto';

        $relations = $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('AType')->eq($storyType)
            ->andWhere('BType')->eq($BType)
            ->andWhere('relation')->eq($relation)
            ->andWhere('AID')->eq($storyID)
            ->fetchPairs();

        if(empty($relations)) return array();

        $queryFields = empty($fields) ? 'id,title' : implode(',', $fields);
        if(!empty($fields)) return $this->dao->select($queryFields)->from(TABLE_STORY)->where('id')->in($relations)->andWhere('deleted')->eq(0)->fetchAll('id');
        return $this->dao->select($queryFields)->from(TABLE_STORY)->where('id')->in($relations)->andWhere('deleted')->eq(0)->fetchPairs();
    }

    /**
     * Get software requirements associated with user needs.
     *
     * @param  array  $storyID
     * @param  string $storyType
     * @access public
     * @return int
     */
    public function getStoryRelationCounts($storyID, $storyType = '')
    {
        $selectField    = ($storyType == 'story') ? 'AID' : 'BID';
        $conditionField = ($storyType == 'story') ? 'BID' : 'AID';

        $relations = $this->dao->select('count('. $selectField .') as id')->from(TABLE_RELATION)
            ->where('AType')->eq('requirement')
            ->andWhere('BType')->eq('story')
            ->andWhere('relation')->eq('subdivideinto')
            ->andWhere($conditionField)->eq($storyID)
            ->fetch('id');

        return $relations;
    }

    /**
     * Get estimate info
     *
     * @param  int    $storyID
     * @param  int    $round
     * @access public
     * @return bool|array
     */
    public function getEstimateInfo($storyID, $round = 0)
    {
        $estimateInfo = $this->dao->select('*')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->beginIf($round)->andWhere('round')->eq($round)->fi()
            ->orderBy('round_desc')
            ->fetch();

        if(!empty($estimateInfo)) $estimateInfo->estimate = json_decode($estimateInfo->estimate);
        return $estimateInfo;
    }

    /**
     * Get estimate rounds.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getEstimateRounds($storyID)
    {
        $lastRound = $this->dao->select('round')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->orderBy('round_desc')
            ->fetch('round');
        if(!$lastRound) return array();

        $rounds = array();
        for($i = 1; $i <= $lastRound; $i++)
        {
            $rounds[$i] = sprintf($this->lang->story->storyRound, $i);
        }

        return $rounds;
    }

    /**
     * Save estimate information.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function saveEstimateInfo($storyID)
    {
        $data = fixer::input('post')->get();

        $lastRound = $this->dao->select('round')->from(TABLE_STORYESTIMATE)
            ->where('story')->eq($storyID)
            ->orderBy('round_desc')
            ->fetch('round');

        $estimates = array();
        foreach($data->account as $key => $account)
        {
            $estimates[$account]['account']  = $account;
            if(!empty($data->estimate[$key]) and !is_numeric($data->estimate[$key]))
            {
                dao::$errors[] = $this->lang->story->estimateMustBeNumber;
                return false;
            }

            if(!empty($data->estimate[$key]) and $data->estimate[$key] < 0)
            {
                dao::$errors[] = $this->lang->story->estimateMustBePlus;
                return false;
            }
            $estimates[$account]['estimate'] = strpos($data->estimate[$key], '-') !== false ? (int)$data->estimate[$key] : (float)$data->estimate[$key];
        }


        $storyEstimate = new stdclass();
        $storyEstimate->story      = $storyID;
        $storyEstimate->round      = empty($lastRound) ? 1 : $lastRound + 1;
        $storyEstimate->estimate   = json_encode($estimates);
        $storyEstimate->average    = $data->average;
        $storyEstimate->openedBy   = $this->app->user->account;
        $storyEstimate->openedDate = helper::now();

        $this->dao->insert(TABLE_STORYESTIMATE)->data($storyEstimate)->exec();
    }

    /**
     * Update the story order according to the plan.
     *
     * @param  int    $planID
     * @param  array  $sortIDList
     * @param  string $orderBy
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return void
     */
    public function sortStoriesOfPlan($planID, $sortIDList, $orderBy = 'id_desc', $pageID = 1, $recPerPage = 100)
    {
        /* Append id for secend sort. */
        $orderBy = common::appendOrder($orderBy);

        /* Get all stories by plan. */
        $stories     = $this->getPlanStories($planID, 'all', $orderBy);
        $storyIDList = array_keys($stories);

        /* Calculate how many numbers there are before the sort list and after the sort list. */
        $frontStoryCount   = $recPerPage * ($pageID - 1);
        $behindStoryCount  = $recPerPage * $pageID;
        $frontStoryIDList  = array_slice($storyIDList, 0, $frontStoryCount);
        $behindStoryIDList = array_slice($storyIDList, $behindStoryCount, count($storyIDList) - $behindStoryCount);

        /* Merge to get a new sort list. */
        $newSortIDList = array_merge($frontStoryIDList, $sortIDList, $behindStoryIDList);
        if(strpos($orderBy, 'order_desc') !== false) $newSortIDList = array_reverse($newSortIDList);

        /* Loop update the story order of plan. */
        $order = 1;
        foreach($newSortIDList as $storyID)
        {
            $this->dao->update(TABLE_PLANSTORY)->set('`order`')->eq($order)->where('story')->eq($storyID)->andWhere('plan')->eq($planID)->exec();
            $order++;
        }
    }

    /**
     * Replace story lang to requirement.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function replaceURLang($type)
    {
        if($type == 'requirement')
        {
            $storyLang = $this->lang->story;
            $SRCommon  = $this->lang->SRCommon;
            $URCommon  = $this->lang->URCommon;

            $storyLang->create             = str_replace($SRCommon, $URCommon, $storyLang->create);
            $storyLang->changeAction       = str_replace($SRCommon, $URCommon, $storyLang->changeAction);
            $storyLang->changed            = str_replace($SRCommon, $URCommon, $storyLang->changed);
            $storyLang->assignAction       = str_replace($SRCommon, $URCommon, $storyLang->assignAction);
            $storyLang->reviewAction       = str_replace($SRCommon, $URCommon, $storyLang->reviewAction);
            $storyLang->subdivideAction    = str_replace($SRCommon, $URCommon, $storyLang->subdivideAction);
            $storyLang->closeAction        = str_replace($SRCommon, $URCommon, $storyLang->closeAction);
            $storyLang->activateAction     = str_replace($SRCommon, $URCommon, $storyLang->activateAction);
            $storyLang->deleteAction       = str_replace($SRCommon, $URCommon, $storyLang->deleteAction);
            $storyLang->view               = str_replace($SRCommon, $URCommon, $storyLang->view);
            $storyLang->linkStory          = str_replace($SRCommon, $URCommon, $storyLang->linkStory);
            $storyLang->unlinkStory        = str_replace($SRCommon, $URCommon, $storyLang->unlinkStory);
            $storyLang->exportAction       = str_replace($SRCommon, $URCommon, $storyLang->exportAction);
            $storyLang->zeroCase           = str_replace($SRCommon, $URCommon, $storyLang->zeroCase);
            $storyLang->zeroTask           = str_replace($SRCommon, $URCommon, $storyLang->zeroTask);
            $storyLang->copyTitle          = str_replace($SRCommon, $URCommon, $storyLang->copyTitle);
            $storyLang->common             = str_replace($SRCommon, $URCommon, $storyLang->common);
            $storyLang->title              = str_replace($SRCommon, $URCommon, $storyLang->title);
            $storyLang->spec               = str_replace($SRCommon, $URCommon, $storyLang->spec);
            $storyLang->children           = str_replace($SRCommon, $URCommon, $storyLang->children);
            $storyLang->linkStories        = str_replace($SRCommon, $URCommon, $storyLang->linkStories);
            $storyLang->childStories       = str_replace($SRCommon, $URCommon, $storyLang->childStories);
            $storyLang->duplicateStory     = str_replace($SRCommon, $URCommon, $storyLang->duplicateStory);
            $storyLang->newStory           = str_replace($SRCommon, $URCommon, $storyLang->newStory);
            $storyLang->copy               = str_replace($SRCommon, $URCommon, $storyLang->copy);
            $storyLang->total              = str_replace($SRCommon, $URCommon, $storyLang->total);
            $storyLang->released           = str_replace($SRCommon, $URCommon, $storyLang->released);
            $storyLang->legendLifeTime     = str_replace($SRCommon, $URCommon, $storyLang->legendLifeTime);
            $storyLang->legendLinkStories  = str_replace($SRCommon, $URCommon, $storyLang->legendLinkStories);
            $storyLang->legendChildStories = str_replace($SRCommon, $URCommon, $storyLang->legendChildStories);
            $storyLang->legendSpec         = str_replace($SRCommon, $URCommon, $storyLang->legendSpec);

            $storyLang->report->charts['storysPerProduct'] = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storysPerProduct']);
            $storyLang->report->charts['storysPerModule']  = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storysPerModule']);
            $storyLang->report->charts['storysPerSource']  = str_replace($SRCommon, $URCommon, $storyLang->report->charts['storysPerSource']);
        }
    }

    /**
     * Get story reviewer pairs.
     *
     * @param  int  $storyID
     * @param  int  version
     * @access public
     * @return array
     */
    public function getReviewerPairs($storyID, $version)
    {
        return $this->dao->select('reviewer,result')->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetchPairs('reviewer', 'result');
    }

    /**
     * Set story status by review rules.
     *
     * @param  array  $reviewerList
     * @access public
     * @return string
     */
    public function getReviewResult($reviewerList)
    {
        $results      = '';
        $passCount    = 0;
        $rejectCount  = 0;
        $revertCount  = 0;
        $clarifyCount = 0;
        $reviewRule   = $this->config->story->reviewRules;
        foreach($reviewerList as $reviewer => $result)
        {
            $passCount    = $result == 'pass'    ? $passCount    + 1 : $passCount;
            $rejectCount  = $result == 'reject'  ? $rejectCount  + 1 : $rejectCount;
            $revertCount  = $result == 'revert'  ? $revertCount  + 1 : $revertCount;
            $clarifyCount = $result == 'clarify' ? $clarifyCount + 1 : $clarifyCount;

            $results .= $result . ',';
        }

        $finalResult = '';
        if($reviewRule == 'allpass' and $passCount == count($reviewerList)) $finalResult = 'pass';
        if($reviewRule == 'halfpass' and $passCount >= floor(count($reviewerList) / 2) + 1) $finalResult = 'pass';

        if(empty($finalResult))
        {
            if($clarifyCount >= floor(count($reviewerList) / 2) + 1) return 'clarify';
            if($revertCount  >= floor(count($reviewerList) / 2) + 1) return 'revert';
            if($rejectCount  >= floor(count($reviewerList) / 2) + 1) return 'reject';

            if(strpos($results, 'clarify') !== false) return 'clarify';
            if(strpos($results, 'revert')  !== false) return 'revert';
            if(strpos($results, 'reject')  !== false) return 'reject';
        }

        return $finalResult;
    }

    /**
     * Set story status by reeview result.
     *
     * @param  int    $story
     * @param  int    $oldStory
     * @param  int    $result
     * @param  string $reason
     * @access public
     * @return array
     */
    public function setStatusByReviewResult($story, $oldStory, $result, $reason = 'cancel')
    {
        if($result == 'pass') $story->status = 'active';

        if($result == 'clarify')
        {
            /* When the review result of the changed story is clarify, the status should be changing. */
            $isChanged = $oldStory->changedBy ? true : false;
            $story->status = $isChanged ? 'changing' : 'draft';
        }

        if($result == 'revert')
        {
            $story->status  = 'active';
            $story->version = $oldStory->version - 1;
            $story->title   = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($story->id)->andWHere('version')->eq($oldStory->version - 1)->fetch('title');

            /* Delete versions that is after this version. */
            $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($story->id)->andWHere('version')->in($oldStory->version)->exec();
            $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($story->id)->andWhere('version')->in($oldStory->version)->exec();

            /* Sync twins. */
            if(!empty($oldStory->twins))
            {
                foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
                {
                    $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($twinID)->andWHere('version')->in($oldStory->version)->exec();
                    $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($twinID)->andWhere('version')->in($oldStory->version)->exec();
                }
            }
        }

        if($result == 'reject')
        {
            $now    = helper::now();
            $reason = (!empty($story->closedReason)) ? $story->closedReason : $reason;

            $story->status       = 'closed';
            $story->closedBy     = $this->app->user->account;
            $story->closedDate   = $now;
            $story->assignedTo   = 'closed';
            $story->assignedDate = $now;
            $story->stage        = $reason == 'done' ? 'released' : 'closed';
            $story->closedReason = $reason;
        }

        $story->finalResult = $result;

        /* If in ipd mode, set requirement status = 'launched'. */
        if($this->config->systemMode == 'PLM' and $oldStory->type == 'requirement' and $story->status == 'active' and $this->config->vision == 'rnd') $story->status = 'launched';
        if($story->status == 'launched' and $this->app->tab != 'product') $story->status = 'developing';

        return $story;
    }

    /**
     * Record story review actions.
     *
     * @param  object $story
     * @param  string $result
     * @param  string $reason
     * @access public
     * @return int|string
     */
    public function recordReviewAction($story, $result = '', $reason = '')
    {
        $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');

        $comment = isset($_POST['comment']) ? $this->post->comment : '';

        if($isSuperReviewer !== false and $this->app->rawMethod != 'edit')
        {
            $actionID = $this->loadModel('action')->create('story', $story->id, 'Reviewed', $comment, ucfirst($result) . '|superReviewer');
            return $actionID;
        }

        $reasonParam = $result == 'reject' ? ',' . $reason : '';
        $actionID    = !empty($result) ? $this->loadModel('action')->create('story', $story->id, 'Reviewed', $comment, ucfirst($result) . $reasonParam) : '';

        if(isset($story->finalResult))
        {
            $isChanged = $story->changedBy ? true : false;
            if($story->finalResult == 'reject')  $this->action->create('story', $story->id, 'ReviewRejected', '', $isChanged ? 'changing' : 'draft');
            if($story->finalResult == 'pass')    $this->action->create('story', $story->id, 'ReviewPassed');
            if($story->finalResult == 'clarify') $this->action->create('story', $story->id, 'ReviewClarified');
            if($story->finalResult == 'revert')  $this->action->create('story', $story->id, 'ReviewReverted');
        }

        return $actionID;
    }

    /**
     * Update the story fields value by review.
     *
     * @param  int    $storyID
     * @param  object $oldStory
     * @param  object $story
     * @access public
     * @return object
     */
    public function updateStoryByReview($storyID, $oldStory, $story)
    {
        $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');
        if($isSuperReviewer !== false) return $this->superReview($storyID, $oldStory, $story);

        $reviewerList = $this->getReviewerPairs($storyID, $oldStory->version);
        $reviewedBy   = explode(',', trim($story->reviewedBy, ','));
        if(!array_diff(array_keys($reviewerList), $reviewedBy))
        {
            $reviewResult = $this->getReviewResult($reviewerList);
            $story        = $this->setStatusByReviewResult($story, $oldStory, $reviewResult);
        }

        return $story;
    }

    /**
     * To review for super reviewer.
     *
     * @param  int     $storyID
     * @param  object  $oldStory
     * @param  object  $story
     * @param  string  $result
     * @param  string  $reason
     * @access public
     * @return object
     */
    public function superReview($storyID, $oldStory, $story, $result = '', $reason = '')
    {
        $result = isset($_POST['result']) ? $this->post->result : $result;
        if(empty($result)) return $story;

        $reason = isset($_POST['closedReason']) ? $_POST['closedReason'] : $reason;
        $story  = $this->setStatusByReviewResult($story, $oldStory, $result, $reason);

        $this->dao->delete()->from(TABLE_STORYREVIEW)
            ->where('story')->eq($storyID)
            ->andWhere('version')->eq($oldStory->version)
            ->andWhere('result')->eq('')
            ->exec();

        /* Sync twins. */
        if(!empty($oldStory->twins))
        {
            foreach(explode(',', trim($oldStory->twins, ',')) as $twinID)
            {
                $this->dao->delete()->from(TABLE_STORYREVIEW)
                    ->where('story')->eq($twinID)
                    ->andWhere('version')->eq($oldStory->version)
                    ->andWhere('result')->eq('')
                    ->exec();
            }
        }

        return $story;
    }

    /**
     * Get related objects id lists.
     *
     * @param  int    $object
     * @param  string $pairs
     * @access public
     * @return void
     */
    public function getRelatedObjects($object, $pairs = '')
    {
        $storys = $this->loadModel('transfer')->getQueryDatas('story');

        /* Get related objects id lists. */
        $relatedObjectIdList = array();
        $relatedObjects      = array();

        foreach($storys as $story) $relatedObjectIdList[$story->$object]  = $story->$object;

        if($object == 'plan') $object = 'productplan';
        /* Get related objects title or names. */
        $table = $this->config->objectTables[$object];
        if($table) $relatedObjects = $this->dao->select($pairs)->from($table) ->where('id')->in($relatedObjectIdList)->fetchPairs();
        return $relatedObjects;
    }

    /**
     * Get export storys .
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function getExportStories($executionID, $orderBy = 'id_desc', $storyType = 'story')
    {
        $this->loadModel('file');
        $this->loadModel('branch');

        $this->replaceURLang($storyType);
        $storyLang   = $this->lang->story;
        $storyConfig = $this->config->story;

        /* Create field lists. */
        $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $storyConfig->list->exportFields);
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($storyLang->$fieldName) ? $storyLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        /* Get stories. */
        $stories        = array();
        $selectedIDList = $this->post->checkedItem ? $this->post->checkedItem : '0';
        if($this->session->storyOnlyCondition)
        {
            if($this->post->exportType == 'selected')
            {
                $stories = $this->dao->select('id,title,linkStories,childStories,parent,mailto,reviewedBy')->from(TABLE_STORY)->where('id')->in($selectedIDList)->orderBy($orderBy)->fetchAll('id');
            }
            else
            {
                $stories = $this->dao->select('id,title,linkStories,childStories,parent,mailto,reviewedBy')->from(TABLE_STORY)->where($this->session->storyQueryCondition)->orderBy($orderBy)->fetchAll('id');
            }
        }
        else
        {
            $field = $executionID ? 't2.id' : 't1.id';
            if($this->post->exportType == 'selected')
            {
                $stmt  = $this->app->dbQuery("SELECT * FROM " . TABLE_STORY . "WHERE `id` IN({$selectedIDList})" . " ORDER BY " . strtr($orderBy, '_', ' '));
            }
            else
            {
                $stmt  = $this->app->dbQuery($this->session->storyQueryCondition . " ORDER BY " . strtr($orderBy, '_', ' '));
            }
            while($row = $stmt->fetch()) $stories[$row->id] = $row;
        }

        if(empty($stories)) return $stories;

        $storyIdList = array_keys($stories);
        $children    = array();
        foreach($stories as $story)
        {
            if($story->parent > 0 and isset($stories[$story->parent]))
            {
                $children[$story->parent][$story->id] = $story;
                unset($stories[$story->id]);
            }
        }

        if(!empty($children))
        {
            $reorderStories = array();
            foreach($stories as $story)
            {
                $reorderStories[$story->id] = $story;
                if(isset($children[$story->id]))
                {
                    foreach($children[$story->id] as $childrenID => $childrenStory)
                    {
                        $reorderStories[$childrenID] = $childrenStory;
                    }
                }
                unset($stories[$story->id]);
            }
            $stories = $reorderStories;
        }

        /* Get users, products and relations. */
        $users           = $this->loadModel('user')->getPairs('noletter');
        $products        = $this->loadModel('product')->getPairs('nocode');
        $relatedStoryIds = array();

        foreach($stories as $story) $relatedStoryIds[$story->id] = $story->id;

        $storyTasks = $this->loadModel('task')->getStoryTaskCounts($relatedStoryIds);
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($relatedStoryIds);
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($relatedStoryIds);

        /* Get related objects title or names. */
        $relatedSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('`story`')->in($storyIdList)->orderBy('version desc')->fetchGroup('story');
        $relatedStories = $this->dao->select('*')->from(TABLE_STORY)->where('`id`')->in($relatedStoryIds)->fetchPairs('id', 'title');

        $fileIdList = array();
        foreach($relatedSpecs as $storyID => $relatedSpec)
        {
            if(!empty($relatedSpec[0]->files)) $fileIdList[] = $relatedSpec[0]->files;
        }
        $fileIdList   = array_unique($fileIdList);
        $relatedFiles = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->in($storyIdList)->andWhere('extra')->ne('editor')->fetchGroup('objectID');
        $filesInfo    = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('id')->in($fileIdList)->andWhere('extra')->ne('editor')->fetchAll('id');

        foreach($stories as $story)
        {
            $story->spec   = '';
            $story->verify = '';
            if(isset($relatedSpecs[$story->id]))
            {
                $storySpec     = $relatedSpecs[$story->id][0];
                $story->title  = $storySpec->title;
                $story->spec   = $storySpec->spec;
                $story->verify = $storySpec->verify;

                if(!empty($storySpec->files) and empty($relatedFiles[$story->id]) and !empty($filesInfo[$storySpec->files]))
                {
                    $relatedFiles[$story->id][0] = $filesInfo[$storySpec->files];
                }
            }

            if($this->post->fileType == 'csv')
            {
                $story->spec = htmlspecialchars_decode($story->spec);
                $story->spec = str_replace("<br />", "\n", $story->spec);
                $story->spec = str_replace('"', '""', $story->spec);
                $story->spec = str_replace('&nbsp;', ' ', $story->spec);

                $story->verify = htmlspecialchars_decode($story->verify);
                $story->verify = str_replace("<br />", "\n", $story->verify);
                $story->verify = str_replace('"', '""', $story->verify);
                $story->verify = str_replace('&nbsp;', ' ', $story->verify);
            }
            /* fill some field with useful value. */

            if(isset($storyTasks[$story->id])) $story->taskCountAB = $storyTasks[$story->id];
            if(isset($storyBugs[$story->id]))  $story->bugCountAB  = $storyBugs[$story->id];
            if(isset($storyCases[$story->id])) $story->caseCountAB = $storyCases[$story->id];

            if($story->linkStories)
            {
                $tmpLinkStories    = array();
                $linkStoriesIdList = explode(',', $story->linkStories);
                foreach($linkStoriesIdList as $linkStoryID)
                {
                    $linkStoryID = trim($linkStoryID);
                    $tmpLinkStories[] = zget($relatedStories, $linkStoryID);
                }
                $story->linkStories = join("; \n", $tmpLinkStories);
            }

            if($story->childStories)
            {
                $tmpChildStories = array();
                $childStoriesIdList = explode(',', $story->childStories);
                foreach($childStoriesIdList as $childStoryID)
                {
                    if(empty($childStoryID)) continue;

                    $childStoryID = trim($childStoryID);
                    $tmpChildStories[] = zget($relatedStories, $childStoryID);
                }
                $story->childStories = join("; \n", $tmpChildStories);
            }

            /* Set related files. */
            $story->files = '';
            if(isset($relatedFiles[$story->id]))
            {
                foreach($relatedFiles[$story->id] as $file)
                {
                    $fileURL = common::getSysURL() . helper::createLink('file', 'download', "fileID=$file->id");
                    $story->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                }
            }

            $story->mailto = trim(trim($story->mailto), ',');
            $mailtos = explode(',', $story->mailto);
            $story->mailto = '';
            foreach($mailtos as $mailto)
            {
                $mailto = trim($mailto);
                if(isset($users[$mailto])) $story->mailto .= $users[$mailto] . ',';
            }
            $story->mailto = rtrim($story->mailto, ',');

            $story->reviewedBy = trim(trim($story->reviewedBy), ',');
            $reviewedBys = explode(',', $story->reviewedBy);
            $story->reviewedBy = '';
            foreach($reviewedBys as $reviewedBy)
            {
                $reviewedBy = trim($reviewedBy);
                if(isset($users[$reviewedBy])) $story->reviewedBy .= $users[$reviewedBy] . ',';
            }
            $story->reviewedBy = rtrim($story->reviewedBy, ',');

            /* Set child story title. */
            if($story->parent > 0 && strpos($story->title, htmlentities('>', ENT_COMPAT | ENT_HTML401, 'UTF-8')) !== 0) $story->title = '>' . $story->title;
        }

        return $stories;
    }

    /**
     * Get the story status after activation.
     *
     * @param  int    $storyID
     * @param  bool   $hasTwins
     * @access public
     * @return void
     */
    public function getActivateStatus($storyID, $hasTwins = true)
    {
        $status     = 'active';
        $action     = 'closed,reviewrejected,closedbysystem';
        $action     = $hasTwins ? $action . ',synctwins' : $action;
        $lastRecord = $this->dao->select('action,extra')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('objectID')->eq($storyID)
            ->andWhere('action')->in($action)
            ->orderBy('id_desc')
            ->fetch();

        $lastAction = $lastRecord->action;
        if($lastAction == 'reviewrejected') $status = $lastRecord->extra;
        if($lastAction == 'closed') $status = strpos($lastRecord->extra, '|') !== false ? substr($lastRecord->extra, strpos($lastRecord->extra, '|') + 1) : 'active';

        /* Activate parent story. */
        if($lastAction == 'closedbysystem')
        {
            $status = $lastRecord->extra ? $lastRecord->extra : 'active';
            if($status == 'active')
            {
                /* If the parent story is not reviewed before closing, it will be activated to the status in changing. */
                $hasNotReviewed = $this->dao->select('t1.*')->from(TABLE_STORYREVIEW)->alias('t1')
                    ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id and t2.version = t1.version')
                    ->where('t1.story')->eq($storyID)
                    ->andWhere('t1.result')->eq('')
                    ->fetchAll();
                if(!empty($hasNotReviewed)) $status = 'changing';
            }
        }

        /* When activating twin story, you need to check the status of the twin story selected when closing. */
        if($lastAction == 'synctwins')
        {
            $syncStoryID = strpos($lastRecord->extra, '|') !== false ? substr($lastRecord->extra, strpos($lastRecord->extra, '|') + 1) : 0;
            $status      = $this->getActivateStatus($syncStoryID, false);
        }

        return $status;
    }

    /**
     * Get reviewer pairs for story .
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function getStoriesReviewer($productID = 0)
    {
        $this->loadModel('user');
        $product   = $this->loadModel('product')->getByID($productID);
        $reviewers = $product->reviewer;
        if(!$reviewers and $product->acl != 'open') $reviewers = $this->user->getProductViewListUsers($product, '', '', '', '');
        return $this->user->getPairs('noclosed|nodeleted', '', 0, $reviewers);
    }

    /**
     * Get the last reviewer.
     *
     * @param  int $storyID
     * @access public
     * @return string
     */
    public function getLastReviewer($storyID)
    {
        $lastReviewer = $this->dao->select('t2.new')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_HISTORY)->alias('t2')->on('t1.id = t2.action')
            ->where('t1.objectType')->eq('story')
            ->andWhere('t1.objectID')->eq($storyID)
            ->andWhere('t2.field')->in('reviewer,reviewers')
            ->andWhere('t2.new')->ne('')
            ->orderBy('t1.id_desc')
            ->fetch('new');

        return $lastReviewer;
    }

    /**
     * Sync twins.
     *
     * @param  int    $storyID
     * @param  string $twins
     * @param  array  $changes
     * @param  string $operate
     * @access public
     * @return void
     */
    public function syncTwins($storyID, $twins, $changes, $operate)
    {
        if(empty($twins) or empty($changes)) return;

        /* Get the fields and values to be synchronized. */
        $syncFieldList = array();
        foreach($changes as $changeInfo)
        {
            $fieldName  = $changeInfo['field'];
            $fieldValue = $changeInfo['new'];

            if(strpos('product,branch,module,plan,stage,stagedBy,spec,verify,files,reviewers', $fieldName) !== false) continue;
            $syncFieldList[$fieldName] = $fieldValue;
        }

        if(empty($syncFieldList)) return;

        /* Synchronize and record dynamics. */
        $this->loadModel('action');
        $twins = explode(',', trim($twins, ','));
        foreach($twins as $twinID)
        {
            $this->dao->update(TABLE_STORY)->data($syncFieldList)->where('id')->eq((int)$twinID)->exec();
            if(!dao::isError())
            {
                $this->setStage($twinID);

                $actionID = $this->action->create('story', $twinID, 'synctwins', '', "$operate|$storyID");
                $this->action->logHistory($actionID, $changes);
            }
        }
    }

    /**
     * Get product stroies by status.
     *
     * @param  string $status noclosed || active
     * @access public
     * @return array || string
     */
    public function getStatusList($status)
    {
        $storyStatus = '';
        if($status == 'noclosed')
        {
            $storyStatus = $this->lang->story->statusList;
            unset($storyStatus['closed']);
            $storyStatus = array_keys($storyStatus);
        }

        if($status == 'active') $storyStatus = $status;

        return $storyStatus;
    }

    /**
     * Build for datatable columns.
     *
     * @param  string $orderBy
     * @param  string $storyType
     * @param  bool   $hasChildren
     * @access public
     * @return array
     */
    public function generateCol($orderBy = '', $storyType = 'story', $hasChildren = false)
    {
        $setting   = $this->loadModel('datatable')->getSetting('product');
        $fieldList = $this->config->story->datatable->fieldList;

        foreach($fieldList as $field => $items)
        {
            if(isset($items['title'])) continue;

            $title    = $field == 'id' ? 'ID' : zget($this->lang->story, $field, zget($this->lang, $field, $field));
            $fieldList[$field]['title'] = $title;
        }

        if(empty($setting))
        {
            $setting = $this->config->story->datatable->defaultField;
            $order   = 1;
            foreach($setting as $key => $value)
            {
                $set = new stdclass();;
                $set->id    = $value;
                $set->order = $order ++;
                $set->show  = true;
                $setting[$key] = $set;
            }
        }

        $viewType    = $this->app->getViewType();
        $shownFields = array();
        foreach($setting as $key => $set)
        {
            if($storyType == 'requirement' and in_array($set->id, array('plan', 'stage', 'taskCount', 'bugCount', 'caseCount'))) $set->show = false;
            if($viewType == 'xhtml' and !in_array($set->id, array('title', 'id', 'pri', 'status'))) $set->show = false;
            if(empty($set->show)) continue;

            $sortType = '';
            if(!strpos($orderBy, ',') && strpos($orderBy, $set->id) !== false)
            {
                $sort = str_replace("{$set->id}_", '', $orderBy);
                $sortType = $sort == 'asc' ? 'up' : 'down';
            }

            $set->name  = $set->id;
            $set->title = $fieldList[$set->id]['title'];

            if(isset($fieldList[$set->id]['checkbox']))     $set->checkbox     = $fieldList[$set->id]['checkbox'];
            if(isset($fieldList[$set->id]['nestedToggle'])) $set->nestedToggle = $fieldList[$set->id]['nestedToggle'];
            if(isset($fieldList[$set->id]['fixed']))        $set->fixed        = $fieldList[$set->id]['fixed'];
            if(isset($fieldList[$set->id]['type']))         $set->type         = $fieldList[$set->id]['type'];
            if(isset($fieldList[$set->id]['sortType']))     $set->sortType     = $fieldList[$set->id]['sortType'];
            if(isset($fieldList[$set->id]['flex']))         $set->flex         = $fieldList[$set->id]['flex'];
            if(isset($fieldList[$set->id]['minWidth']))     $set->minWidth     = $fieldList[$set->id]['minWidth'];
            if(isset($fieldList[$set->id]['maxWidth']))     $set->maxWidth     = $fieldList[$set->id]['maxWidth'];
            if(isset($fieldList[$set->id]['pri']))          $set->pri          = $fieldList[$set->id]['pri'];
            if(isset($fieldList[$set->id]['map']))          $set->map          = $fieldList[$set->id]['map'];

            if($sortType) $set->sortType = $sortType;

            if(isset($set->fixed) && $set->fixed == 'no') unset($set->fixed);
            if(isset($set->width)) $set->width = str_replace('px', '', $set->width);
            unset($set->id);
            $shownFields[$set->name] = $set;
        }

        if(!$hasChildren) $shownFields['title']->nestedToggle = false;
        usort($shownFields, array('datatableModel', 'sortCols'));
        return array_values($shownFields);
    }

    /**
     * Build for datatable rows.
     *
     * @param  array    $stories
     * @param  array    $cols
     * @param  array    $options
     * @param  object   $execution
     * @param  string   $storyType
     * @access public
     * @return array
     */
    public function generateRow($stories, $cols, $options, $execution, $storyType)
    {
        $users         = zget($options, 'users',         array());
        $branches      = zget($options, 'branchOption',  array());
        $branchOptions = zget($options, 'branchOptions', array());
        $modulePairs   = zget($options, 'modulePairs',   array());
        $storyStages   = zget($options, 'storyStages',   array());
        $products      = zget($options, 'products',      array());
        $isShowBranch  = zget($options, 'isShowBranch',  '');

        $userFields  = array('openedBy', 'closedBy', 'lastEditedBy', 'feedbackBy');
        $dateFields  = array('assignedDate', 'openedDate', 'closedDate', 'lastEditedDate', 'reviewedDate', 'activatedDate');
        $executionID = empty($execution) ? $this->session->execution : $execution->id;
        $showBranch  = isset($this->config->product->browse->showBranch) ? $this->config->product->browse->showBranch : 1;
        $canView     = common::hasPriv($storyType, 'view', null, "storyType=$storyType");
        $tab         = $this->app->tab;
        $rows        = array();

        if($this->config->edition != 'open')
        {
            $this->loadModel('flow');
            $extendFields = $this->loadModel('workflowfield')->getList('story');
        }

        $storyIdList = array_keys($stories);
        $storyTasks  = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs   = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases  = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);

        if($this->config->vision == 'or') $this->app->loadLang('demand');
        foreach($stories as $story)
        {
            $data     = new stdclass();
            $menuType = 'browse';
            if(($tab == 'execution' || ($tab == 'project' and !$this->session->multiple)) && $storyType == 'story') $menuType = 'execution';

            if(!empty($branchOptions)) $branches = zget($branchOptions, $story->product, array());
            $data->id           = $story->id;
            $data->estimateNum  = $story->estimate;
            $data->caseCountNum = zget($storyCases, $story->id, 0);
            $data->actions      = '<div class="c-actions">' . $this->buildOperateMenu($story, $menuType, $execution, $storyType) . '</div>';
            foreach($cols as $col)
            {
                if($col->name == 'assignedTo')   $data->assignedTo   = $this->printAssignedHtml($story, $users, false);
                if($col->name == 'order')        $data->order        = "<i class='icon-move'>";
                if($col->name == 'pri')          $data->pri          = "<span class='" . ($story->pri ? "label-pri label-pri-" . $story->pri : '') . "' title='" . zget($this->lang->story->priList, $story->pri, $story->pri) . "'>" . zget($this->lang->story->priList, $story->pri, $story->pri) . "</span>";
                if($col->name == 'plan')         $data->plan         = isset($story->planTitle) ? $story->planTitle : '';
                if($col->name == 'product')      $data->product      = "<span title='" . zget($products, $story->product, '') . "'>" . zget($products, $story->product, '') . '</span>';
                if($col->name == 'branch')       $data->branch       = zget($branches, $story->branch, '');
                if($col->name == 'module')       $data->module       = zget($modulePairs, $story->module, '');
                if($col->name == 'source')       $data->source       = zget($this->lang->story->sourceList, $story->source);
                if($col->name == 'sourceNote')   $data->sourceNote   = $story->sourceNote;
                if($col->name == 'keywords')     $data->keywords     = $story->keywords;
                if($col->name == 'version')      $data->version      = $story->version;
                if($col->name == 'feedbackBy')   $data->feedbackBy   = $story->feedbackBy;
                if($col->name == 'notifyEmail')  $data->notifyEmail  = $story->notifyEmail;
                if($col->name == 'closedReason') $data->closedReason = zget($this->lang->story->reasonList, $story->closedReason, '');
                if($col->name == 'category')     $data->category     = zget($this->lang->story->categoryList, $story->category);
                if($col->name == 'duration')     $data->duration     = zget($this->lang->demand->durationList, $story->duration);
                if($col->name == 'BSA')          $data->BSA          = zget($this->lang->demand->bsaList, $story->BSA);
                if($col->name == 'taskCount')    $data->taskCount    = $storyTasks[$story->id] > 0 ? html::a(helper::createLink('story', 'tasks', "storyID=$story->id"), $storyTasks[$story->id], '', 'class="iframe" data-toggle="modal"') : '0';
                if($col->name == 'bugCount')     $data->bugCount     = $storyBugs[$story->id]  > 0 ? html::a(helper::createLink('story', 'bugs', "storyID=$story->id"),  $storyBugs[$story->id],  '', 'class="iframe" data-toggle="modal"') : '0';
                if($col->name == 'caseCount')    $data->caseCount    = $storyCases[$story->id] > 0 ? html::a(helper::createLink('story', 'cases', "storyID=$story->id"),  $storyCases[$story->id], '', 'class="iframe" data-toggle="modal"') : '0';
                if($col->name == 'estimate')     $data->estimate     = (float)$story->estimate . $this->config->hourUnit;
                if($col->name == 'reviewedBy')
                {
                    $reviewers = array_unique(array_filter(explode(',', $story->reviewedBy)));
                    $reviewers = array_map(function($reviewer) use($users){return zget($users, $reviewer);}, $reviewers);
                    $data->reviewedBy = join(' ', $reviewers);
                }
                if($col->name == 'reviewer')
                {
                    $reviewers = array_unique(array_filter($story->reviewer));
                    $reviewers = array_map(function($reviewer) use($users){return zget($users, $reviewer);}, $reviewers);
                    $story->reviewer = join(' ', $reviewers);
                    $data->reviewer  = "<span title='{$story->reviewer}'>" . $story->reviewer . '</span>';
                }
                if($col->name == 'stage')
                {
                    $maxStage = $story->stage;
                    if(isset($storyStages[$story->id]))
                    {
                        $stageList   = join(',', array_keys($this->lang->story->stageList));
                        $maxStagePos = strpos($stageList, $maxStage);
                        foreach($storyStages[$story->id] as $storyBranch => $storyStage)
                        {
                            if(strpos($stageList, $storyStage->stage) !== false and strpos($stageList, $storyStage->stage) > $maxStagePos)
                            {
                                $maxStage    = $storyStage->stage;
                                $maxStagePos = strpos($stageList, $storyStage->stage);
                            }
                        }
                    }
                    $data->stage = zget($this->lang->story->stageList, $maxStage);
                }
                if($col->name == 'status')
                {
                    $data->status = "<span class='status-{$story->status}'>" . $this->processStatus('story', $story) . '</span>';
                    if($story->URChanged) $data->status = "<span class='status-story status-changed'>{$this->lang->story->URChanged}</span>";
                }
                if($col->name == 'title')
                {
                    $storyTitle = '';
                    $storyLink  = helper::createLink('story', 'view', "storyID=$story->id&version=0&param=&storyType=$story->type") . "#app=$tab";
                    if($tab == 'project')
                    {
                        $showBranch = isset($this->config->projectstory->story->showBranch) ? $this->config->projectstory->story->showBranch : 1;
                        $storyLink  = helper::createLink('story', 'view', "storyID=$story->id&version=0&param={$this->session->execution}&storyType=$story->type");
                        if($this->session->multiple)
                        {
                            $storyLink = helper::createLink('projectstory', 'view', "storyID=$story->id&project={$this->session->project}");
                            $canView   = common::hasPriv('projectstory', 'view');
                        }
                    }
                    elseif($tab == 'execution')
                    {
                        $storyLink  = helper::createLink('execution', 'storyView', "storyID=$story->id&execution={$this->session->execution}");
                        $canView    = common::hasPriv('execution', 'storyView');
                        $showBranch = 0;
                        if($isShowBranch) $showBranch = isset($this->config->execution->story->showBranch) ? $this->config->execution->story->showBranch : 1;
                    }

                    if($storyType == 'requirement' and $story->type == 'story') $storyTitle .= '<span class="label label-badge label-light">SR</span> ';
                    if($story->parent > 0 and isset($story->parentName)) $storyTitle .= "{$story->parentName} / ";
                    if(isset($branches[$story->branch]) and $showBranch and $this->config->vision != 'lite') $storyTitle .= "<span class='label label-outline label-badge' title={$branches[$story->branch]}>{$branches[$story->branch]}</span> ";
                    if($story->module and isset($modulePairs[$story->module])) $storyTitle .= "<span class='label label-gray label-badge'>{$modulePairs[$story->module]}</span> ";
                    if($story->parent > 0 and !($storyType == 'requirement' and $story->type == 'story')) $storyTitle .= '<span class="label label-badge label-light" title="' . $this->lang->story->children . '">' . $this->lang->story->childrenAB . '</span> ';
                    $storyTitle .= $canView ? html::a($storyLink, $story->title, '', "title='$story->title' style='color: $story->color' data-app='$tab'") : "<span style='color: $story->color'>{$story->title}</span>";
                    $data->title = $storyTitle;
                }
                if($col->name == 'mailto')
                {
                    $mailto = array_map(function($account) use($users){$account = trim($account); return zget($users, $account);}, explode(',', $story->mailto));
                    $data->mailto = implode(' ', $mailto);
                }
                if($col->name == 'URS' || $col->name == 'SRS')
                {
                    $link    = helper::createLink('story', 'relation', "storyID=$story->id&storyType=$story->type");
                    $storySR = $this->getStoryRelationCounts($story->id, $story->type);
                    $data->{$col->name} = $storySR > 0 ? html::a($link, $storySR, '', 'class="iframe" data-toggle="modal"') : 0;
                }
                if(in_array($col->name, $userFields)) $data->{$col->name} = zget($users, $story->{$col->name});
                if(in_array($col->name, $dateFields)) $data->{$col->name} = helper::isZeroDate($story->{$col->name}) ? '' : substr($story->{$col->name}, 5, 11);
                if($this->config->edition != 'open')
                {
                    if(isset($extendFields[$col->name]) && !$extendFields[$col->name]->buildin)
                    {
                        $data->{$col->name} = $this->flow->printFlowCell('story', $story, $col->name, true);
                    }
                }
            }

            $data->isParent = false;
            $data->parent   = $story->parent;
            if($data->parent == -1)
            {
                $data->isParent = true;
                $data->parent   = 0;
            }

            $rows[] = $data;
            if(!empty($story->children)) $rows = array_merge($rows, $this->generateRow($story->children, $cols, $options, $execution, $storyType));
        }
        return $rows;
    }

    /**
     * 更新需求的发布日期
     * Update the released date of story.
     *
     * @param  string $stories
     * @param  string $releasedDate
     * @access public
     * @return bool
     */
    public function updateStoryReleasedDate(string $stories, string $releasedDate): bool
    {
        $this->dao->update(TABLE_STORY)
            ->set('releasedDate')->eq($releasedDate)
            ->where('id')->in($stories)
            ->exec();

        return !dao::isError();
    }
}
