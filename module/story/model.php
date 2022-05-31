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
        $story = $this->dao->select('*')->from(TABLE_STORY)
            ->where('id')->eq($storyID)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch();
        if(!$story) return false;

        if(helper::isZeroDate($story->closedDate)) $story->closedDate = '';
        if($version == 0) $version = $story->version;
        $spec = $this->dao->select('title,spec,verify')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWhere('version')->eq($version)->fetch();
        $story->title  = isset($spec->title)  ? $spec->title  : '';
        $story->spec   = isset($spec->spec)   ? $spec->spec   : '';
        $story->verify = isset($spec->verify) ? $spec->verify : '';
        if(!empty($story->fromStory)) $story->sourceName = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($story->fromStory)->fetch('title');

        /* Check parent story. */
        if($story->parent > 0) $story->parentName = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch('title');

        $story = $this->loadModel('file')->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $story->executions = $this->dao->select('t1.project, t2.name, t2.status, t2.type')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->andWhere('t2.type')->in('sprint,stage,kanban')
            ->orderBy('t1.`order` DESC')
            ->fetchAll('project');
        $story->tasks  = $this->dao->select('id, name, assignedTo, execution, status, consumed, `left`,type')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('deleted')->eq(0)->orderBy('id DESC')->fetchGroup('execution');
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

        $story->children = array();
        if($story->parent == '-1') $story->children = $this->dao->select('*')->from(TABLE_STORY)->where('parent')->eq($storyID)->andWhere('deleted')->eq(0)->fetchAll('id');

        return $story;
    }

    /**
     * Get stories by idList.
     *
     * @param  int|array|string    $storyIdList
     * @param  string $type requirement|story
     * @access public
     * @return array
     */
    public function getByList($storyIdList = 0, $type = 'story')
    {
        return $this->dao->select('t1.*, t2.spec, t2.verify, t3.name as productTitle')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.version=t2.version')
            ->beginIF($storyIdList)->andWhere('t1.id')->in($storyIdList)->fi()
            ->beginIF(!$storyIdList)->andWhere('t1.type')->eq($type)->fi()
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
        $story->bugs = $this->dao->findByStory($story->id)->from(TABLE_BUG)
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();

        /* Get affected cases. */
        $story->cases = $this->dao->findByStory($story->id)->from(TABLE_CASE)->andWhere('deleted')->eq(0)->fetchAll();

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
     * @return int|bool the id of the created story or false when error.
     */
    public function create($executionID = 0, $bugID = 0, $from = '', $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(isset($_POST['reviewer'])) $_POST['reviewer'] = array_filter($_POST['reviewer']);
        if(!$this->post->needNotReview and empty($_POST['reviewer']))
        {
            dao::$errors[] = $this->lang->story->errorEmptyReviewedBy;
            return false;
        }

        $now   = helper::now();
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->callFunc('title', 'trim')
            ->add('version', 1)
            ->add('status', 'draft')
            ->setDefault('plan,verify,notifyEmail', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setIF($this->post->needNotReview, 'status', 'active')
            ->setIF($this->post->plan > 0, 'stage', 'planned')
            ->setIF($this->post->estimate, 'estimate', (float)$this->post->estimate)
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF($executionID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->join('mailto', ',')
            ->stripTags($this->config->story->editor->create['id'], $this->config->allowedTags)
            ->remove('files,labels,reviewer,needNotReview,newStory,uid,contactListMenu,URS,region,lane')
            ->get();

        /* Check repeat story. */
        $result = $this->loadModel('common')->removeDuplicate('story', $story, "product={$story->product}");
        if(isset($result['stop']) and $result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        if($this->checkForceReview()) $story->status = 'draft';
        if($story->status == 'draft') $story->stage  = $this->post->plan > 0 ? 'planned' : 'wait';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->create['id'], $this->post->uid);

        $requiredFields = "," . $this->config->story->create->requiredFields . ",";

        if($story->type == 'requirement') $requiredFields = str_replace(',plan,', ',', $requiredFields);
        if(strpos($requiredFields, ',estimate,') !== false)
        {
            if(strlen(trim($story->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->story->estimate);
            $requiredFields = str_replace(',estimate,', ',', $requiredFields);
        }

        $requiredFields = trim($requiredFields, ',');

        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify')
            ->autoCheck()
            ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
            ->batchCheck($requiredFields, 'notempty')
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $storyID = $this->dao->lastInsertID();

            /* Fix bug #21992, user story have no parent story. */
            if(isset($story->parent) and $story->parent)
            {
                $stories = array($storyID);
                $this->subdivide($story->parent, $stories);
            }

            $this->file->updateObjectID($this->post->uid, $storyID, $story->type);
            $this->file->saveUpload($story->type, $storyID, $extra = 1);

            if(!empty($story->plan)) $this->updateStoryOrderOfPlan($storyID, $story->plan); // Set story order in this plan.

            $data          = new stdclass();
            $data->story   = $storyID;
            $data->version = 1;
            $data->title   = $story->title;
            $data->spec    = $story->spec;
            $data->verify  = $story->verify;
            $this->dao->insert(TABLE_STORYSPEC)->data($data)->exec();

            /* Save the story reviewer to storyreview table. */
            if(isset($_POST['reviewer']))
            {
                $assignedTo = '';
                foreach($this->post->reviewer as $reviewer)
                {
                    if(empty($reviewer)) continue;

                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = 1;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                    if(empty($assignedTo)) $assignedTo = $reviewer;
                }
                if($assignedTo) $this->dao->update(TABLE_STORY)->set('assignedTo')->eq($assignedTo)->set('assignedDate')->eq(helper::now())->where('id')->eq($storyID)->exec();
            }

            /* Project or execution linked story. */
            if($executionID != 0)
            {
                $this->linkStory($executionID, $this->post->product, $storyID);
                if($this->config->systemMode == 'new' and $executionID != $this->session->project) $this->linkStory($this->session->project, $this->post->product, $storyID);

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
                        $file->objectID   = $storyID;
                        unset($file->id);
                        $this->dao->insert(TABLE_FILE)->data($file)->exec();
                    }
                }
            }
            $this->setStage($storyID);
            if(!dao::isError()) $this->loadModel('score')->create('story', 'create',$storyID);

            /* Callback the callable method to process the related data for object that is transfered to story. */
            if($from && is_callable(array($this, $this->config->story->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->story->fromObjects[$from]['callback']), $storyID);

            return array('status' => 'created', 'id' => $storyID);
        }
        return false;
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

        $reviewers = '';
        foreach($_POST['title'] as $index => $value)
        {
            if($_POST['title'][$index] and isset($_POST['reviewer'][$index]) and empty($_POST['reviewDitto'][$index])) $reviewers = $_POST['reviewer'][$index] = array_filter($_POST['reviewer'][$index]);
            if($_POST['title'][$index] and isset($_POST['reviewer'][$index]) and !empty($_POST['reviewDitto'][$index])) $_POST['reviewer'][$index] = $reviewers;
            if($_POST['title'][$index] and empty($_POST['reviewer'][$index]) and $forceReview)
            {
                dao::$errors[] = $this->lang->story->errorEmptyReviewedBy;
                return false;
            }
        }

        $this->loadModel('action');
        $branch    = (int)$branch;
        $productID = (int)$productID;
        $now       = helper::now();
        $mails     = array();
        $stories   = fixer::input('post')->get();

        $result  = $this->loadModel('common')->removeDuplicate('story', $stories, "product={$productID}");
        $stories = $result['data'];

        $module = 0;
        $plan   = '';
        $pri    = 0;
        $source = '';

        foreach($stories->title as $i => $title)
        {
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
        foreach($stories->title as $i => $title)
        {
            if(empty($title)) continue;

            if(empty($stories->reviewer[$i]) and empty($stories->reviewerDitto[$i])) $stories->reviewer[$i] = array();

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
            $story->estimate   = $stories->estimate[$i];
            $story->status     = (empty($stories->reviewer[$i]) and !$forceReview) ? 'active' : 'draft';
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

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->story->$field);
                return false;
            }
            $data[$i] = $story;
        }

        $planStories = array();

        foreach($data as $i => $story)
        {
            $this->dao->insert(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->exec();
            if(dao::isError())
            {
                echo js::error(dao::getError());
                return print(js::reload('parent'));
            }

            $storyID = $this->dao->lastInsertID();
            $this->setStage($storyID);

            /* Update product plan stories order. */
            if($story->plan) $this->updateStoryOrderOfPlan($storyID, $story->plan);

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
            $assignedTo = '';
            foreach($_POST['reviewer'][$i] as $reviewer)
            {
                if(empty($reviewer)) continue;

                $reviewData = new stdclass();
                $reviewData->story    = $storyID;
                $reviewData->version  = 1;
                $reviewData->reviewer = $reviewer;
                $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                if(empty($assignedTo)) $assignedTo = $reviewer;
            }
            if($assignedTo) $this->dao->update(TABLE_STORY)->set('assignedTo')->eq($assignedTo)->set('assignedDate')->eq($now)->where('id')->eq($storyID)->exec();

            $this->executeHooks($storyID);

            $actionID = $this->action->create('story', $storyID, 'Opened', '');
            if(!dao::isError()) $this->loadModel('score')->create('story', 'create',$storyID);
            $mails[$i] = new stdclass();
            $mails[$i]->storyID  = $storyID;
            $mails[$i]->actionID = $actionID;
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
        if(!dao::isError())  $this->loadModel('score')->create('ajax', 'batchCreate');
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
        $oldSpec     = $this->dao->select('title,spec,verify')->from(TABLE_STORYSPEC)->where('story')->eq((int)$storyID)->andWhere('version')->eq($oldStory->version)->fetch();
        $oldStory->title  = isset($oldSpec->title)  ? $oldSpec->title  : '';
        $oldStory->spec   = isset($oldSpec->spec)   ? $oldSpec->spec   : '';
        $oldStory->verify = isset($oldSpec->verify) ? $oldSpec->verify : '';

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
        if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or $this->loadModel('file')->getCount() or $reviewerHasChanged) $specChanged = true;

        $now   = helper::now();
        $story = fixer::input('post')
            ->callFunc('title', 'trim')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('id', $storyID)
            ->add('lastEditedDate', $now)
            ->setIF($specChanged, 'version', $oldStory->version + 1)
            ->setIF($specChanged and $oldStory->status == 'active' and $this->post->needNotReview == false, 'status',  'changed')
            ->setIF($oldStory->status == 'draft' and $this->post->needNotReview, 'status', 'active')
            ->setIF($specChanged, 'reviewedBy', '')
            ->setIF($specChanged, 'closedBy', '')
            ->setIF($specChanged, 'closedReason', '')
            ->setIF($specChanged and $oldStory->reviewedBy, 'reviewedDate', '0000-00-00')
            ->setIF($specChanged and $oldStory->closedBy, 'closedDate', '0000-00-00')
            ->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)
            ->remove('files,labels,reviewer,comment,needNotReview,uid')
            ->get();
        if($specChanged and isset($story->status) && $story->status == 'active' and $this->checkForceReview()) $story->status = 'changed';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->change['id'], $this->post->uid);
        $this->dao->update(TABLE_STORY)->data($story, 'spec,verify')
            ->autoCheck()
            ->batchCheck($this->config->story->change->requiredFields, 'notempty')
            ->checkFlow()
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

                $story = $this->getById($storyID);
                /* IF is story and has changed, update its relation version to new. */
                if($oldStory->type == 'story')
                {
                    $this->dao->update(TABLE_STORY)->set('URChanged')->eq(0)->where('id')->eq($oldStory->id)->exec();
                    $this->updateStoryVersion($story);
                }
                else
                {
                    /* IF is requirement changed, notify its relation. */
                    $relations = $this->dao->select('BID')->from(TABLE_RELATION)
                        ->where('AType')->eq('requirement')
                        ->andWhere('BType')->eq('story')
                        ->andWhere('relation')->eq('subdivideinto')
                        ->andWhere('AID')->eq($story->id)
                        ->fetchPairs();

                    foreach($relations as $relationID) $this->dao->update(TABLE_STORY)->set('URChanged')->eq(1)->where('id')->eq($relationID)->exec();
                }

                /* Update the reviewer. */
                $assignedTo = '';
                foreach($_POST['reviewer'] as $reviewer)
                {
                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = $story->version;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                    if(empty($assignedTo)) $assignedTo = $reviewer;
                }
                if($assignedTo and $assignedTo != $oldStory->assignedTo) $this->dao->update(TABLE_STORY)->set('assignedTo')->eq($assignedTo)->set('assignedDate')->eq(helper::now())->where('id')->eq($storyID)->exec();
            }

            $this->file->updateObjectID($this->post->uid, $storyID, 'story');

            $oldStory->reviewers = implode(',', array_keys($oldStoryReviewers));
            $story->reviewers    = implode(',', $_POST['reviewer']);
            return common::createChanges($oldStory, $story);
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
        $oldStory = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(!empty($_POST['lastEditedDate']) and $oldStory->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $story = fixer::input('post')
            ->cleanInt('product,module,pri,duplicateStory')
            ->cleanFloat('estimate')
            ->setDefault('assignedDate', $oldStory->assignedDate)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('id', $storyID)
            ->add('lastEditedDate', $now)
            ->setDefault('plan,notifyEmail', '')
            ->setDefault('status', $oldStory->status)
            ->setDefault('product', $oldStory->product)
            ->setDefault('branch', $oldStory->branch)
            ->setIF(!$this->post->linkStories, 'linkStories', '')
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'feedbackBy', '')
            ->setIF(!in_array($this->post->source, $this->config->story->feedbackSource), 'notifyEmail', '')
            ->setIF(!empty($_POST['plan'][0]) and $oldStory->stage == 'wait', 'stage', 'planned')
            ->stripTags($this->config->story->editor->edit['id'], $this->config->allowedTags)
            ->join('reviewedBy', ',')
            ->join('mailto', ',')
            ->join('linkStories', ',')
            ->join('childStories', ',')
            ->remove('files,labels,comment,contactListMenu,stages,reviewer')
            ->get();

        if(isset($story->plan) and is_array($story->plan)) $story->plan = trim(join(',', $story->plan), ',');
        if(isset($_POST['branch']) and $_POST['branch'] == 0) $story->branch = 0;
        if(!empty($_POST['stages']))
        {
            $oldStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchAll('branch');
            $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();

            $stageList   = join(',', array_keys($this->lang->story->stageList));
            $minStagePos = strlen($stageList);
            $minStage    = '';
            foreach($this->post->stages as $branch => $stage)
            {
                $newStage = new stdclass();
                $newStage->story  = $storyID;
                $newStage->branch = $branch;
                $newStage->stage  = $stage;
                if(isset($oldStages[$branch]))
                {
                    $oldStage = $oldStages[$branch];
                    $newStage->stagedBy = $oldStage->stagedBy;
                    if($stage != $oldStage->stage) $newStage->stagedBy = (strpos('tested|verified|released|closed', $stage) !== false) ? $this->app->user->account : '';
                }
                if($story->branch == 0) $this->dao->insert(TABLE_STORYSTAGE)->data($newStage)->exec();
                if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) < $minStagePos)
                {
                    $minStage    = $stage;
                    $minStagePos = strpos($stageList, $stage);
                }
            }
            $story->stage = $minStage;
        }

        if(isset($story->stage) and $oldStory->stage != $story->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';
        $story = $this->loadModel('file')->processImgURL($story, $this->config->story->editor->edit['id'], $this->post->uid);


        if(isset($_POST['reviewer']))
        {
            $_POST['reviewer'] = array_filter($_POST['reviewer']);
            $oldReviewer       = $this->getReviewerPairs($storyID, $oldStory->version);
            if(array_diff($_POST['reviewer'], array_keys($oldReviewer)) or array_diff(array_keys($oldReviewer), $_POST['reviewer']))
            {
                /* Update story reviewer. */
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->andWhere('reviewer')->notin(implode(',', $_POST['reviewer']))->exec();
                foreach($_POST['reviewer'] as $reviewer)
                {
                    if(in_array($reviewer, array_keys($oldReviewer))) continue;

                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = $oldStory->version;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();
                }

                $story->reviewedBy = $oldStory->reviewedBy;
                $story = $this->updateStoryByReview($storyID, $oldStory, $story);
            }

            $oldStory->reviewers = implode(',', array_keys($oldReviewer));
            $story->reviewers    = implode(',', array_keys($this->getReviewerPairs($storyID, $oldStory->version)));
        }

        $this->dao->update(TABLE_STORY)
            ->data($story, 'reviewers')
            ->autoCheck()
            ->checkIF(isset($story->closedBy), 'closedReason', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'done', 'stage', 'notempty')
            ->checkIF(isset($story->closedReason) and $story->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkIF($story->notifyEmail, 'notifyEmail', 'email')
            ->checkFlow()
            ->where('id')->eq((int)$storyID)->exec();

        if(!dao::isError())
        {
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

            if($oldStory->stage != $story->stage)
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
            return common::createChanges($oldStory, $story);
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
        if(count($childrenStatus) == 1 and $oldParentStory->status != 'changed')
        {
            $status = current($childrenStatus);
            if($status == 'draft' or $status == 'changed') $status = 'active';
        }
        elseif(count($childrenStatus) != 1 and $oldParentStory->status == 'closed')
        {
            $status = 'active';
        }

        if($status and $oldParentStory->status != $status)
        {
            $now  = helper::now();
            $story = new stdclass();
            $story->status = $status;
            $story->stage  = 'wait';
            if($status == 'active')
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
                $changes = common::createChanges($oldParentStory, $newParentStory);
                $action  = '';
                if($status == 'active') $action = 'Activated';
                if($status == 'closed') $action = 'Closed';
                if($action)
                {
                    $actionID = $this->loadModel('action')->create('story', $parentID, $action, '', '', '', false);
                    $this->action->logHistory($actionID, $changes);
                }
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
                $story->version        = $story->title == $oldStory->title ? $oldStory->version : $oldStory->version + 1;
                if($story->stage != $oldStory->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';

                if($story->title != $oldStory->title and $story->status != 'draft')    $story->status     = 'changed';
                if($story->closedBy     != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedReason != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedBy     != false  or  $story->closedReason != false) $story->status     = 'closed';
                if($story->closedReason != false  and $story->closedBy     == false) $story->closedBy   = $this->app->user->account;

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
                }
                else
                {
                    return print(js::error('story#' . $storyID . dao::getError(true)));
                }
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchEdit');
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
        if($this->post->result == 'revert' and $this->post->preVersion == false) return print(js::alert($this->lang->story->mustChoosePreVersion));

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
            ->setIF($this->post->result == 'revert', 'version', $this->post->preVersion)
            ->setIF($this->post->result == 'clarify', 'assignedTo', $oldStory->lastEditedBy)
            ->removeIF($this->post->result != 'reject', 'closedReason, duplicateStory, childStories')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'subdivided', 'childStories')
            ->add('reviewedBy', $oldStory->reviewedBy . ',' . $this->app->user->account)
            ->add('id', $storyID)
            ->remove('result,preVersion,comment')
            ->get();

        /* Fix bug #671. */
        $this->lang->story->closedReason = $this->lang->story->rejectedReason;

        $this->dao->update(TABLE_STORYREVIEW)->set('result')->eq($this->post->result)->set('reviewDate')->eq($now)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->andWhere('reviewer')->eq($this->app->user->account)->exec();

        $story = $this->updateStoryByReview($storyID, $oldStory, $story);

        $skipFields      = '';
        $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');
        if($isSuperReviewer === false)
        {
            $reviewers = $this->getReviewerPairs($storyID, $oldStory->version);
            if(count($reviewers) > 1) $skipFields = 'closedReason';
        }

        $this->dao->update(TABLE_STORY)->data($story, $skipFields)
            ->autoCheck()
            ->batchCheck($this->config->story->review->requiredFields, 'notempty')
            ->checkIF($this->post->result == 'reject', 'closedReason', 'notempty')
            ->checkIF($this->post->result == 'reject' and $this->post->closedReason == 'duplicate',  'duplicateStory', 'notempty')
            ->checkFlow()
            ->where('id')->eq($storyID)->exec();
        if($this->post->result == 'revert')
        {
            $preTitle = $this->dao->select('title')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->eq($this->post->preVersion)->fetch('title');
            $this->dao->update(TABLE_STORY)->set('title')->eq($preTitle)->where('id')->eq($storyID)->exec();

            /* Delete versions that is after this version. */
            $deleteVersion = array();
            for($version = $oldStory->version; $version > $story->version; $version --) $deleteVersion[] = $version;
            if($deleteVersion)
            {
                $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->in($deleteVersion)->exec();
                $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->in($deleteVersion)->exec();
            }

            $this->dao->delete()->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->andWhere('extra')->eq($oldStory->version)->exec();
        }
        if($this->post->result != 'reject') $this->setStage($storyID);

        if(isset($story->closedReason) and $isSuperReviewer === false) unset($story->closedReason);
        return common::createChanges($oldStory, $story);
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

        $oldStories   = $this->getByList($storyIdList);
        $hasResult    = $this->dao->select('story,version,result')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->andWhere('reviewer')->eq($this->app->user->account)->andWhere('result')->ne('')->orderBy('version')->fetchAll('story');
        $reviewerList = $this->dao->select('story,reviewer,result,version')->from(TABLE_STORYREVIEW)->where('story')->in($storyIdList)->orderBy('version')->fetchGroup('story', 'reviewer');
        foreach($storyIdList as $storyID)
        {
            if(!$storyID) continue;
            $oldStory = $oldStories[$storyID];
            $isSuperReviewer = strpos(',' . trim(zget($this->config->story, 'superReviewers', ''), ',') . ',', ',' . $this->app->user->account . ',');

            if($oldStory->status != 'draft' and $oldStory->status != 'changed') continue;
            if(!in_array($this->app->user->account, array_keys($reviewerList[$storyID])) and $isSuperReviewer === false) continue;
            if(isset($hasResult[$storyID]) and $hasResult[$storyID]->version == $oldStories[$storyID]->version) continue;

            $story = new stdClass();
            $story->reviewedDate   = $now;
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->reviewedBy     = $oldStory->reviewedBy . ',' . $this->app->user->account;
            $story->status         = $oldStory->status;

            $this->dao->update(TABLE_STORYREVIEW)->set('result')->eq($result)->set('reviewDate')->eq($now)->where('story')->eq($storyID)->andWhere('version')->eq($oldStory->version)->andWhere('reviewer')->eq($this->app->user->account)->exec();

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

                $status = $this->setStatusByReviewRules($reviewerPairs);
                $story->status = $status ? $status : $oldStory->status;

                if($story->status == 'closed')
                {
                    $story->closedBy   = $this->app->user->account;
                    $story->closedDate = $now;
                    $story->assignedTo = 'closed';
                    $story->stage      = 'closed';
                    if($reason == 'done') $story->stage = 'released';
                }
            }

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
            $this->setStage($storyID);

            $story->id         = $storyID;
            $story->version    = $oldStory->version;
            $actions[$storyID] = $this->recordReviewAction($story, $result, $reason);
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
    public function recall($storyID)
    {
        $story = $this->getById($storyID);
        $this->dao->update(TABLE_STORY)->set('status')->eq('draft')->where('id')->eq($storyID)->exec();
        $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->andWhere('version')->eq($story->version)->exec();
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

            $isonlybody = isonlybody();
            unset($_GET['onlybody']);
            return print(js::locate(helper::createLink('product', 'browse', "productID=$oldStory->product&branch=0&browseType=unclosed&queryID=0&type=story"), $isonlybody ? 'parent.parent' : 'parent'));
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
            ->add('assignedTo', 'closed')
            ->add('status', 'closed')
            ->add('stage', 'closed')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('closedDate',     $now)
            ->setDefault('closedBy',       $this->app->user->account)
            ->setDefault('assignedDate',   $now)
            ->removeIF($this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->closedReason != 'subdivided', 'childStories')
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
        }
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
            $story->assignedTo     = 'closed';
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
            }
            else
            {
                return print(js::error('story#' . $storyID . dao::getError(true)));
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

        /* Cycle every story and process it's plan and stage. */
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($oldStory->branch != BRANCH_MAIN and $oldStory->branch != $plan->branch and $plan->branch != BRANCH_MAIN) continue;

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

            /* Append the plan id to plan field if product is multi and story is all branch. */
            if($this->session->currentProductType != 'normal' and empty($story->branch) and $planID != 0)
            {
                $branchPlanPairs = $this->dao->select('branch, id as plan')->from(TABLE_PRODUCTPLAN)
                    ->where('product')->eq($oldStory->product)
                    ->andWhere('id')->in($oldStory->plan)
                    ->fetchPairs();
                if(isset($branchPlanPairs[$plan->branch])) $branchPlanPairs[$plan->branch] = $planID;

                $story->plan = $oldStory->plan ? ',' . implode(',', $branchPlanPairs) : ",$planID";
            }

            /* Change stage. */
            if($planID)
            {
                if($oldStory->stage == 'wait') $story->stage = 'planned';
                if($this->session->currentProductType and $this->session->currentProductType != 'normal' and $oldStory->branch == 0)
                {
                    if(!isset($oldStoryStages[$storyID][$plan->branch]))
                    {
                        $story->stage = 'planned';
                        $newStoryStage = new stdclass();
                        $newStoryStage->story  = $storyID;
                        $newStoryStage->branch = $plan->branch;
                        $newStoryStage->stage  = $story->stage;
                        $this->dao->insert(TABLE_STORYSTAGE)->data($newStoryStage)->autoCheck()->exec();
                    }
                }
            }

            /* Update story and recompute stage. */
            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();

            if(!$planID) $this->setStage($storyID);

            if(!dao::isError()) $allChanges[$storyID] = common::createChanges($oldStory, $story);
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
        $data = fixer::input('post')->get();
        $now  = helper::now();

        /* Judgment of required items. */
        if(empty($data->type))
        {
            dao::$errors['type'] = sprintf($this->lang->error->notempty, $this->lang->task->type);
        }

        if(isset($data->hourPointValue) and empty($data->hourPointValue))
        {
            dao::$errors['hourPointValue'] = sprintf($this->lang->error->notempty, $this->lang->story->convertRelations);
        }
        elseif(isset($data->hourPointValue) and !is_numeric($data->hourPointValue))
        {
            dao::$errors['hourPointValue'] = sprintf($this->lang->error->float, $this->lang->story->convertRelations);
        }
        if(dao::isError()) return false;

        /* Create tasks. */
        $tasks   = array();
        $stories = empty($data->storyIdList) ? array() : $this->getByList($data->storyIdList);
        foreach($stories as $story)
        {
            if(strpos('draft,closed', $story->status) !== false) continue;

            $task = new stdclass();
            $task->execution    = $executionID;
            $task->project      = $projectID ? $projectID : 0;
            $task->name         = $story->title;
            $task->story        = $story->id;
            $task->type         = $data->type;
            $task->estimate     = isset($data->hourPointValue) ? ($story->estimate * $data->hourPointValue) : $story->estimate;
            $task->left         = $task->estimate;
            $task->openedBy     = $this->app->user->account;
            $task->openedDate   = $now;
            $task->storyVersion = $story->version;

            if(isset($data->fields))
            {
                foreach($data->fields as $field)
                {
                    $task->$field = $story->$field;

                    if($field == 'assignedTo') $task->assignedDate = $now;
                    if($field == 'spec')
                    {
                        unset($task->$field);
                        $task->desc = $story->$field;
                    }
                }
            }

            $this->dao->insert(TABLE_TASK)->data($task)
                ->autoCheck()
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->exec();

            if(dao::isError()) return false;
            $taskID  = $this->dao->lastInsertID();
            $tasks[] = $taskID;
            $this->action->create('task', $taskID, 'Opened', '');
        }

        $this->loadModel('kanban')->updateLane($executionID, 'task');
        return $tasks;
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
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq((int)$storyID)->exec();
        if(!dao::isError()) return common::createChanges($oldStory, $story);
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
        foreach($storyIdList as $storyID)
        {
            $oldStory = $oldStories[$storyID];
            if($assignedTo == $oldStory->assignedTo) continue;

            $story = new stdclass();
            $story->lastEditedBy   = $this->app->user->account;
            $story->lastEditedDate = $now;
            $story->assignedTo     = $assignedTo;
            $story->assignedDate   = $now;

            $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();

            $allChanges[$storyID] = common::createChanges($oldStory, $story);
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
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->checkFlow()->where('id')->eq($storyID)->exec();

        if($this->post->status == 'active') $this->dao->delete()->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->exec();

        $this->setStage($storyID);

        /* Update parent story status. */
        if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);

        return common::createChanges($oldStory, $story);
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
            $this->dao->update(TABLE_STORY)->set('stage')->eq('wait')->where('id')->eq($storyID)->andWhere('plan')->eq('')->exec();

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
        foreach($releases as $branch) $stages[$branch] = 'released';

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
     * @param  string  $type
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  varchar $storyType
     * @access public
     * @return array
     */
    public function getStories2Link($storyID, $type = 'linkStories', $browseType = 'bySearch', $queryID = 0, $storyType = 'story')
    {
        $story         = $this->getById($storyID);
        $linkedStories = $this->getRelation($storyID, $story->type);
        $linkedStories = empty($linkedStories) ? array() : $linkedStories;
        $tmpStoryType  = $storyType == 'story' ? 'requirement' : 'story';

        if($browseType == 'bySearch')
        {
            $stories2Link = $this->getBySearch($story->product, $story->branch, $queryID, 'id', '', $tmpStoryType);
            foreach($stories2Link as $key => $story2Link)
            {
                if($story2Link->id == $storyID) unset($stories2Link[$key]);
                if(in_array($story2Link->id, explode(',', $story->$type))) unset($stories2Link[$key]);
            }
        }
        else
        {
            $status = $storyType == 'story' ? 'active' : 'all';
            $stories2Link = $this->getProductStories($story->product, $story->branch, 0, $status, $tmpStoryType, $orderBy = 'id_desc');
        }

        foreach($stories2Link as $id => $story)
        {
            if(in_array($story->id, array_keys($linkedStories))) unset($stories2Link[$id]);
            if($storyType == 'story' && $story->status == 'draft') unset($stories2Link[$id]);
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

        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere($productQuery)
            ->beginIF(!$hasParent)->andWhere("parent")->ge(0)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!empty($excludeStories))->andWhere('id')->notIN($excludeStories)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
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
     * @param  bool          $hasParent
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
            ->beginIF(!$hasParent)->andWhere('t1.parent')->ge(0)->fi()
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

        $sql = $this->dao->select('t1.*')->from(TABLE_STORY)->alias('t1');
        if($fieldName == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t2')->on('t1.id = t2.story and t1.version = t2.version');

        $stories = $sql->where('t1.product')->in($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq($type)
            ->beginIF($branch != 'all')->andWhere("t1.branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("t1.module")->in($modules)->fi()
            ->beginIF($operator == 'equal' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->eq($fieldValue)->fi()
            ->beginIF($operator == 'include' and $fieldName != 'reviewBy' and $fieldName != 'assignedBy')->andWhere('t1.' . $fieldName)->like("%$fieldValue%")->fi()
            ->beginIF($fieldName == 'reviewBy')
            ->andWhere('t2.reviewer')->eq($this->app->user->account)
            ->andWhere('t2.result')->eq('')
            ->andWhere('t1.status')->in('draft,changed')
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
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('type')->eq($type)
            ->beginIF($branch)->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->andWhere('deleted')->eq(0)
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
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySearch($productID, $branch = '', $queryID = 0, $orderBy = '', $executionID = '', $type = 'story', $excludeStories = '', $pager = null)
    {
        $this->loadModel('product');
        $executionID = empty($executionID) ? 0 : $executionID;
        $products    = empty($executionID) ? $this->product->getList() : $this->product->getProducts($executionID);

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
        if($this->app->moduleName == 'productplan') $storyQuery .= " AND `status` NOT IN ('closed') AND `parent` >= 0 ";
        $allBranch = "`branch` = 'all'";
        if($executionID != '')
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
                $storyQuery .= " AND `status` NOT IN ('draft', 'closed')";
            }

            if($this->app->rawModule == 'build' and $this->app->rawMethod == 'linkstory') $storyQuery .= " AND `parent` != '-1'";
        }
        elseif(strpos($storyQuery, $allBranch) !== false)
        {
            $storyQuery = str_replace($allBranch, '1', $storyQuery);
        }
        elseif($branch !== 'all' and $queryProductID != 'all')
        {
            if($branch and strpos($storyQuery, '`branch` =') === false) $storyQuery .= " AND `branch` in($branch)";
        }
        $storyQuery = preg_replace("/`plan` +LIKE +'%([0-9]+)%'/i", "CONCAT(',', `plan`, ',') LIKE '%,$1,%'", $storyQuery);

        return $this->getBySQL($queryProductID, $storyQuery, $orderBy, $pager, $type);
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

        $sql = str_replace(array('`product`', '`version`', '`branch`'), array('t1.`product`', 't1.`version`', 't1.`branch`'), $sql);
        $tmpStories = $this->dao->select('DISTINCT t1.*')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where($sql)
            ->beginIF($productID != 'all' and $productID != '')->andWhere('t1.`product`')->eq((int)$productID)->fi()
            ->andWhere('t1.deleted')->eq(0)
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
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionStories($executionID = 0, $productID = 0, $branch = 0, $orderBy = 't1.`order`_desc', $type = 'byModule', $param = 0, $storyType = 'story', $excludeStories = '', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionStories();

        if(!$executionID) return array();
        $execution = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch();

        $type = strtolower($type);
        if($type == 'bysearch')
        {
            $queryID  = (int)$param;
            $products = $this->loadModel('product')->getProducts($executionID);

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

            $stories = $this->dao->select('distinct t1.*, t2.*, t3.type as productType, t2.version as version')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->where($storyQuery)
                ->andWhere('t1.project')->eq((int)$executionID)
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

            $type = ($type == 'bymodule' and $this->session->storyBrowseType) ? $this->session->storyBrowseType : $type;

            $stories = $this->dao->select('distinct t1.*, t2.*,t3.type as productType,t2.version as version')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
                ->where('t1.project')->eq((int)$executionID)
                ->andWhere('t2.type')->eq($storyType)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->beginIF($execution->type == 'project')
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)
                ->beginIF($type == 'bybranch' and $branchParam !== '')->andWhere('t2.branch')->in("0,$branchParam")->fi()
                ->beginIF(strpos('changed|closed', $type) !== false)->andWhere('t2.status')->eq($type)->fi()
                ->beginIF($type == 'unclosed')->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
                ->beginIF($type == 'linkedexecution')->andWhere('t2.id')->in($storyIdList)->fi()
                ->beginIF($type == 'unlinkedexecution')->andWhere('t2.id')->notIn($storyIdList)->fi()
                ->fi()
                ->beginIF($execution->type != 'project')
                ->beginIF(!empty($productParam))->andWhere('t1.product')->eq($productParam)->fi()
                ->beginIF($this->session->executionStoryBrowseType and strpos('changed|', $this->session->executionStoryBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
                ->fi()
                ->beginIF($this->session->storyBrowseType and strpos('changed|', $this->session->storyBrowseType) !== false)->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
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
        return $this->mergePlanTitle($productID, $stories, $branch, $type);
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
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs($executionID = 0, $productID = 0, $branch = 'all', $moduleIdList = 0, $type = 'full', $status = 'all')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionStoryPairs();
        $stories = $this->dao->select('t2.id, t2.title, t2.module, t2.pri, t2.estimate, t3.name AS product')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.project')->eq((int)$executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t2.product')->eq((int)$productID)->fi()
            ->beginIF($branch !== 'all')->andWhere('t2.branch')->in("0,$branch")->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($status == 'unclosed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF($status == 'review')->andWhere('t2.status')->in('draft,changed')->fi()
            ->orderBy('t1.`order` desc')
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
        $stories = $this->dao->select('distinct t1.story, t1.plan, t1.order, t2.*')
            ->from(TABLE_PLANSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.plan')->eq($planID)
            ->beginIF($status and $status != 'all')->andWhere('t2.status')->in($status)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');

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
            ->andWhere('status')->notin('closed,draft')
            ->andWhere('product')->eq($productID)
            ->andWhere('plan')->in('0,')
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
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.type')->eq('story')
            ->fetchPairs();
        if(empty($stories)) $this->close($parentID->BID);
    }

    /**
     * Get stories of a user.
     *
     * @param  string $account
     * @param  string $type         the query type
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $storyType    requirement|story
     * @access public
     * @return array
     */
    public function getUserStories($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null, $storyType = 'story', $includeLibStories = true)
    {
        $sql = $this->dao->select('t1.*, t2.name as productTitle')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id');
        if($type == 'reviewBy') $sql = $sql->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story and t1.version = t3.version');

        $stories = $sql->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->eq($storyType)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('t1.assignedTo')->eq($account)->fi()
            ->beginIF($type == 'reviewBy')->andWhere('t3.reviewer')->eq($account)->andWhere('t3.result')->eq('')->andWhere('t1.status')->in('draft,changed')->fi()
            ->beginIF($type == 'openedBy')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere("CONCAT(',', t1.reviewedBy, ',')")->like("%,$account,%")->fi()
            ->beginIF($type == 'closedBy')->andWhere('t1.closedBy')->eq($account)->fi()
            ->fi()
            ->beginIF($includeLibStories == false and $this->config->edition == 'max')->andWhere('t1.lib')->eq('0')->fi()
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
            ->andWhere('vision')->eq($this->config->vision)
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
        $datas = $this->dao->select('module as name, count(module) as value, product, branch')->from(TABLE_STORY)
            ->where($this->reportCondition())
            ->groupBy('module')->orderBy('value DESC')->fetchAll('name');
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
        elseif($toList == 'closed')
        {
            $toList = $story->openedBy;
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
        $action = strtolower($action);

        global $app, $config;

        if($story->parent < 0 and strpos($config->story->list->actionsOpratedParentStory, ",$action,") === false) return false;

        $story->reviewer  = isset($story->reviewer)  ? $story->reviewer  : array();
        $story->notReview = isset($story->notReview) ? $story->notReview : array();

        $isSuperReviewer = strpos(',' . trim(zget($config->story, 'superReviewers', ''), ',') . ',', ',' . $app->user->account . ',');

        if($action == 'change')     return ($isSuperReviewer !== false or count($story->reviewer) == 0 or count($story->notReview) == 0) and $story->status != 'closed';
        if($action == 'review')     return (($isSuperReviewer !== false or in_array($app->user->account, $story->notReview)) and ($story->status == 'draft' or $story->status == 'changed'));
        if($action == 'recall')     return empty($story->reviewedBy) and strpos('draft,changed', $story->status) !== false and !empty($story->reviewer) and ($app->user->account == $story->openedBy);
        if($action == 'close')      return $story->status != 'closed';
        if($action == 'activate')   return $story->status == 'closed';
        if($action == 'assignto')   return $story->status != 'closed';
        if($action == 'createcase') return $story->type != 'requirement';
        if($action == 'batchcreate' and $story->parent > 0) return false;
        if($action == 'batchcreate' and $story->type == 'requirement' and $story->status != 'closed') return $story->status != 'draft';
        if($action == 'batchcreate' and $config->vision == 'lite' and ($story->status == 'active' and ($story->stage == 'wait' or $story->stage == 'projected'))) return true;
        if($action == 'batchcreate' and ($story->status != 'active' or $story->stage != 'wait' or !empty($story->plan))) return false;

        return true;
    }

    /**
     * Build operate menu.
     *
     * @param  object $story
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($story, $type = 'view')
    {
        $menu   = '';
        $params = "storyID=$story->id";

        if($type == 'browse')
        {
            if(common::canBeChanged('story', $story))
            {
                if($story->URChanged) return $this->buildMenu('story', 'processStoryChange', $params, $story, $type, 'search', '', 'iframe', true, '', $this->lang->confirm);

                $menu .= $this->buildMenu('story', 'change', $params . "&from=$story->from", $story, $type, 'alter');
                $menu .= $this->buildMenu('story', 'review', $params . "&from=$story->from", $story, $type, 'search');
                $menu .= $this->buildMenu('story', 'recall', $params, $story, $type, 'undo', 'hiddenwin', '', '', '', $this->lang->story->recall);
                $menu .= $this->buildMenu('story', 'close', $params, $story, $type, '', '', 'iframe', true);
                $menu .= $this->buildMenu('story', 'edit', $params . "&from=$story->from", $story, $type);

                if($story->type != 'requirement' and $this->config->vision != 'lite') $menu .= $this->buildMenu('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$params", $story, $type, 'sitemap', '', '', false, "data-app='qa'");
                if($this->app->rawModule != 'projectstory' OR $this->config->vision == 'lite')  $menu .= $this->buildMenu('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=$story->module&$params&executionID={$this->session->project}", $story, $type, 'split', '', '', '', '', $this->lang->story->subdivide);
                if($this->app->rawModule == 'projectstory' and $this->config->vision != 'lite') $menu .= $this->buildMenu('projectstory', 'unlinkStory', "projectID={$this->session->project}&$params", $story, $type, 'unlink', 'hiddenwin');
            }
            else
            {
                return $this->buildMenu('story', 'close', $params, $story, 'list', '', '', 'iframe', true);
            }
        }

        if($type == 'view')
        {
            $menu .= $this->buildMenu('story', 'change', $params, $story, $type, 'alter', '', 'showinonlybody');
            $menu .= $this->buildMenu('story', 'recall', $params, $story, $type, 'undo', '', 'showinonlybody');
            $menu .= $this->buildMenu('story', 'review', $params, $story, $type, 'search', '', 'showinonlybody');

            if(!isonlybody()) $menu .= $this->buildMenu('story', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&$params", $story, $type, 'split', '', 'divideStory', true, "data-toggle='modal' data-type='iframe' data-width='95%'", $this->lang->story->subdivide);

            $menu .= $this->buildMenu('story', 'assignTo', $params, $story, $type, '', '', 'iframe showinonlybody', true);
            $menu .= $this->buildMenu('story', 'close',    $params, $story, $type, '', '', 'iframe showinonlybody', true);
            $menu .= $this->buildMenu('story', 'activate', $params, $story, $type, '', '', 'iframe showinonlybody', true);

            if($this->config->edition == 'max' and $this->app->tab == 'project' and common::hasPriv('story', 'importToLib')) $menu .= html::a('#importToLib', "<i class='icon icon-assets'></i> " . $this->lang->story->importToLib, '', 'class="btn" data-toggle="modal"');

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
                    $link = helper::createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=0&from=&param=0&$params", '', true);
                    $menu .= "<li>" . html::a($link, $this->lang->testcase->create, '', $misc) . "</li>";
                }

                if(common::hasPriv('testcase', 'batchCreate'))
                {
                    $link = helper::createLink('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=0&$params", '', true);
                    $menu .= "<li>" . html::a($link, $this->lang->testcase->batchCreate, '', $misc) . "</li>";
                }

                $menu .= "</ul></div>";
            }

            if($this->app->tab == 'execution' and strpos('draft,closed', $story->status) === false) $menu .= $this->buildMenu('task', 'create', "execution={$this->session->execution}&{$params}&moduleID=$story->module", $story, $type, 'plus', '', 'showinonly body');

            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildFlowMenu('story', $story, $type, 'direct');
            $menu .= "<div class='divider'></div>";

            $menu .= $this->buildMenu('story', 'edit', $params, $story, $type);
            $menu .= $this->buildMenu('story', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&{$params}&executionID=0&bugID=0&planID=0&todoID=0&extra=&type=$story->type", $story, $type, 'copy', '', '', '     ', "data-width='1050'");
            $menu .= $this->buildMenu('story', 'delete', $params, $story, 'button', 'trash', 'hiddenwin', 'showinonlybody', true);
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
            }
        }

        $parents    = array();
        $tmpStories = array();
        foreach($stories as $story)
        {
            $tmpStories[$story->id] = $story;
            if($story->parent > 0) $parents[$story->parent] = $story->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($parents)->fetchAll('id');

        foreach($stories as $storyID => $story)
        {
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
    public function printCell($col, $story, $users, $branches, $storyStages, $modulePairs = array(), $storyTasks = array(), $storyBugs = array(), $storyCases = array(), $mode = 'datatable', $storyType = 'story')
    {
        /* Check the product is closed. */
        $canBeChanged = common::canBeChanged('story', $story);

        $canBatchEdit         = common::hasPriv('story',        'batchEdit');
        $canBatchClose        = common::hasPriv('story',        'batchClose');
        $canBatchReview       = common::hasPriv('story',        'batchReview');
        $canBatchChangeStage  = common::hasPriv('story',        'batchChangeStage');
        $canBatchChangeBranch = common::hasPriv('story',        'batchChangeBranch');
        $canBatchChangeModule = common::hasPriv('story',        'batchChangeModule');
        $canBatchChangePlan   = common::hasPriv('story',        'batchChangePlan');
        $canBatchAssignTo     = common::hasPriv('story',        'batchAssignTo');
        $canBatchUnlinkStory  = common::hasPriv('projectstory', 'batchUnlinkStory');

        $canBatchAction       = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeBranch or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo or $canBatchUnlinkStory);

        $module    = $this->app->rawModule == 'projectstory' ? 'projectstory' : 'story';
        $canView   = common::hasPriv($module, 'view');
        $tab       = $this->app->rawModule == 'projectstory' ? 'project' : 'product';
        $storyLink = helper::createLink($module, 'view', "storyID=$story->id");
        $account   = $this->app->user->account;
        $id        = $col->id;
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
                $title = $story->title;
                if(!empty($story->children)) $class .= ' has-child';
            }
            elseif($id == 'plan')
            {
                $title  = isset($story->planTitle) ? $story->planTitle : '';
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
                if(isset($storyStages[$story->id]))
                {
                    foreach($storyStages[$story->id] as $storyBranch => $storyStage)
                    {
                        if(isset($branches[$storyBranch])) $title .= $branches[$storyBranch] . ": " . $this->lang->story->stageList[$storyStage->stage] . "\n";
                    }
                }
            }
            elseif($id == 'feedbackBy')
            {
                $title = $story->feedbackBy;
            }
            elseif($id == 'notifyEmail')
            {
                $title = $story->notifyEmail;
            }
            elseif($id == 'actions')
            {
                $class .= ' text-center';
            }

            echo "<td class='" . $class . "' title='$title' style='$style'>";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('story', $story, $id);
            switch($id)
            {
            case 'id':
                if($canBatchAction)
                {
                    echo html::checkbox('storyIdList', array($story->id => '')) . html::a($storyLink, sprintf('%03d', $story->id), '', "data-app='$tab'");
                }
                else
                {
                    printf('%03d', $story->id);
                }
                break;
            case 'pri':
                echo "<span class='label-pri label-pri-" . $story->pri . "' title='" . zget($this->lang->story->priList, $story->pri, $story->pri) . "'>";
                echo zget($this->lang->story->priList, $story->pri, $story->pri);
                echo "</span>";
                break;
            case 'title':
                if($this->app->tab == 'project')
                {
                    $showBranch = isset($this->config->projectstory->story->showBranch) ? $this->config->projectstory->story->showBranch : 1;
                }
                else
                {
                    $showBranch = isset($this->config->product->browse->showBranch) ? $this->config->product->browse->showBranch : 1;
                }
                if($storyType == 'requirement') echo '<span class="label label-badge label-light">SR</span> ';
                if($story->parent > 0 and isset($story->parentName)) $story->title = "{$story->parentName} / {$story->title}";
                if(isset($branches[$story->branch]) and $showBranch and $this->config->vision == 'rnd') echo "<span class='label label-outline label-badge' title={$branches[$story->branch]}>{$branches[$story->branch]}</span> ";
                if($story->module and isset($modulePairs[$story->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$story->module]}</span> ";
                if($story->parent > 0) echo '<span class="label label-badge label-light" title="' . $this->lang->story->children . '">' . $this->lang->story->childrenAB . '</span> ';
                echo $canView ? html::a($storyLink, $story->title, '', "style='color: $story->color' data-app='$tab'") : "<span style='color: $story->color'>{$story->title}</span>";
                if(!empty($story->children)) echo '<a class="story-toggle" data-id="' . $story->id . '"><i class="icon icon-angle-double-right"></i></a>';
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
                if(isset($storyStages[$story->id]) and !empty($branches))
                {
                    echo "<div class='dropdown dropdown-hover'>";
                    echo $this->lang->story->stageList[$story->stage];
                    echo "<span class='caret'></span>";
                    echo "<ul class='dropdown-menu pull-right'>";
                    foreach($storyStages[$story->id] as $storyBranch => $storyStage)
                    {
                        if(isset($branches[$storyBranch])) echo '<li class="text-ellipsis">' . $branches[$storyBranch] . ": " . $this->lang->story->stageList[$storyStage->stage] . '</li>';
                    }
                    echo "</ul>";
                    echo '</div>';
                }
                else
                {
                    echo $this->lang->story->stageList[$story->stage];
                }
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
                echo substr($story->openedDate, 5, 11);
                break;
            case 'assignedTo':
                $this->printAssignedHtml($story, $users);
                break;
            case 'assignedDate':
                echo substr($story->assignedDate, 5, 11);
                break;
            case 'reviewedBy':
                echo $story->reviewedBy;
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
            case 'lastEditedBy':
                echo zget($users, $story->lastEditedBy, $story->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo substr($story->lastEditedDate, 5, 11);
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
            case 'actions':
                echo $this->buildOperateMenu($story, 'browse');
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
    public function printAssignedHtml($story, $users)
    {
        $btnTextClass   = '';
        $assignedToText = zget($users, $story->assignedTo);

        if(empty($story->assignedTo))
        {
            $btnTextClass   = 'text-primary';
            $assignedToText = $this->lang->task->noAssigned;
        }
        if($story->assignedTo == $this->app->user->account) $btnTextClass = 'text-red';

        $btnClass     = $story->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass     = "iframe btn btn-icon-left btn-sm {$btnClass}";
        $assignToLink = helper::createLink('story', 'assignTo', "storyID=$story->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('story', 'assignTo', $story) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
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

        if(isset($this->config->story->forceReviewAll) and $this->config->story->forceReviewAll) return true;

        $forceUsers = '';
        if(!empty($this->config->story->forceReview)) $forceUsers = $this->config->story->forceReview;

        if(!empty($this->config->story->forceReviewRoles) or !empty($this->config->story->forceReviewDepts))
        {
            $users = $this->dao->select('account')->from(TABLE_USER)
                ->where('deleted')->eq(0)
                ->andWhere(true, true)
                ->beginIF(!empty($this->config->story->forceReviewRoles))
                ->andWhere('role', true)->in($this->config->story->forceReviewRoles)
                ->andWhere('role')->ne('')
                ->markRight(1)
                ->fi()
                ->beginIF(!empty($this->config->story->forceReviewDepts))->orWhere('dept')->in($this->config->story->forceReviewDepts)->fi()
                ->markRight(1)
                ->fetchAll('account');

            $forceUsers .= "," . implode(',', array_keys($users));
        }

        $forceReview = strpos(",{$forceUsers},", ",{$this->app->user->account},") !== false;

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
                $stories = $this->getRelation($requirement->id, 'requirement');
                $stories = empty($stories) ? array() : $stories;
                foreach($stories as $id => $title)
                {
                    if($projectStories and !isset($projectStories[$id]))
                    {
                        unset($stories[$id]);
                        continue;
                    }

                    $stories[$id] = new stdclass();
                    $stories[$id]->title = $title;
                    $stories[$id]->cases = $this->loadModel('testcase')->getStoryCases($id);
                    $stories[$id]->bugs  = $this->loadModel('bug')->getStoryBugs($id);
                    $stories[$id]->tasks = $this->loadModel('task')->getStoryTasks($id);
                    if($this->config->edition == 'max')
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
                $stories = $this->getExecutionStories($projectID, $productID, $branch, '`order`_desc', 'all', 0, 'story', $excludeStories, $pager);
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
                $stories[$id]->tasks  = $this->loadModel('task')->getStoryTasks($id);
                if($this->config->edition == 'max')
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
            if($this->config->edition == 'max')
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
        $this->dao->insert(TABLE_PROJECTSTORY)
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
            $storyLang->allStories         = str_replace($SRCommon, $URCommon, $storyLang->allStories);
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
    public function setStatusByReviewRules($reviewerList)
    {
        $status      = '';
        $passCount   = 0;
        $rejectCount = 0;
        $reviewRule  = $this->config->story->reviewRules;
        foreach($reviewerList as $reviewer => $reviewResult)
        {
            $passCount   = $reviewResult == 'pass'   ? $passCount   + 1 : $passCount;
            $rejectCount = $reviewResult == 'reject' ? $rejectCount + 1 : $rejectCount;
        }

        if($reviewRule == 'allpass')
        {
            if($passCount   == count($reviewerList)) $status = 'active';
            if($rejectCount == count($reviewerList)) $status = 'closed';
        }

        if($reviewRule == 'halfpass')
        {
            if($passCount   >= floor(count($reviewerList) / 2) + 1) $status = 'active';
            if($rejectCount >= floor(count($reviewerList) / 2) + 1) $status = 'closed';
        }

        return $status;
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
        $reviewers   = $this->getReviewerPairs($story->id, $story->version);
        $reviewedBy  = explode(',', trim($story->reviewedBy, ','));

        $actionID = !empty($result) ? $this->loadModel('action')->create('story', $story->id, 'Reviewed', $comment, ucfirst($result) . $reasonParam) : '';

        if($result != 'revert')
        {
            if($story->status == 'closed') $this->action->create('story', $story->id, 'ReviewRejected');
            if($story->status == 'active') $this->action->create('story', $story->id, 'ReviewPassed');
            if(!array_diff(array_keys($reviewers), $reviewedBy) and ($story->status == 'draft' or $story->status == 'changed')) $this->action->create('story', $story->id, 'ReviewClarified');
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

        $now          = helper::now();
        $reviewerList = $this->getReviewerPairs($storyID, $oldStory->version);
        $reviewedBy   = explode(',', trim($story->reviewedBy, ','));
        if(!array_diff(array_keys($reviewerList), $reviewedBy))
        {
            $status        = $this->post->result == 'revert' ? 'active' : $this->setStatusByReviewRules($reviewerList);
            $story->status = $status ? $status : $oldStory->status;
            if($story->status == 'closed')
            {
                $story->closedBy     = $this->app->user->account;
                $story->closedDate   = $now;
                $story->assignedTo   = 'closed';
                $story->assignedDate = $now;
                $story->stage        = 'closed';
                if($this->post->closedReason == 'done') $story->stage = 'released';
            }
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
        $now    = helper::now();
        $status = '';
        $result = isset($_POST['result']) ? $this->post->result : $result;
        if(strpos('revert,pass', $result) !== false) $status = 'active';
        if($result == 'reject')  $status = 'closed';
        if($result == 'clarify' or empty($status)) $status = $oldStory->status;

        $story->status = $status;

        if($story->status == 'closed')
        {
            $story->closedBy     = $this->app->user->account;
            $story->closedDate   = $now;
            $story->assignedTo   = 'closed';
            $story->assignedDate = $now;
            $story->stage        = 'closed';
            $story->closedReason = isset($_POST['closedReason']) ? $_POST['closedReason'] : $reason;
            if($_POST['closedReason'] == 'done') $story->stage = 'released';
        }

        $this->dao->delete()->from(TABLE_STORYREVIEW)
            ->where('story')->eq($storyID)
            ->andWhere('version')->eq($oldStory->version)
            ->andWhere('result')->eq('')
            ->exec();

        return $story;
    }
}
