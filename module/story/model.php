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

        /* Check parent story. */
        if($story->parent > 0) $story->parentName = $this->dao->findById($story->parent)->from(TABLE_STORY)->fetch('title');

        $story = $this->loadModel('file')->replaceImgURL($story, 'spec,verify');
        if($setImgSize) $story->spec   = $this->file->setImgSize($story->spec);
        if($setImgSize) $story->verify = $this->file->setImgSize($story->verify);

        $story->projects = $this->dao->select('t1.project, t2.name, t2.status')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.story')->eq($storyID)
            ->orderBy('t1.`order` DESC')
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
        return $this->dao->select('t1.*, t2.spec, t2.verify')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
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
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getTestStories($storyIdList, $projectID)
    {
        return $this->dao->select('story')->from(TABLE_TASK)->where('project')->eq($projectID)->andWhere('type')->eq('test')->andWhere('story')->in($storyIdList)->fetchPairs('story', 'story');
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
            $story->teams = $this->dao->select('account, root')
                ->from(TABLE_TEAM)
                ->where('root')->in(array_keys($story->projects))
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
     * Create a story.
     *
     * @access public
     * @return int|bool the id of the created story or false when error.
     */
    public function create($projectID = 0, $bugID = 0, $from = '')
    {
        $now   = helper::now();
        $story = fixer::input('post')
            ->cleanInt('product,module,pri,plan')
            ->cleanFloat('estimate')
            ->callFunc('title', 'trim')
            ->add('assignedDate', 0)
            ->add('version', 1)
            ->add('status', 'draft')
            ->setDefault('plan,verify', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->needNotReview or $projectID > 0, 'status', 'active')
            ->setIF($this->post->plan > 0, 'stage', 'planned')
            ->setIF($projectID > 0, 'stage', 'projected')
            ->setIF($bugID > 0, 'fromBug', $bugID)
            ->join('mailto', ',')
            ->stripTags($this->config->story->editor->create['id'], $this->config->allowedTags)
            ->remove('files,labels,needNotReview,newStory,uid,contactListMenu')
            ->get();

        /* Check repeat story. */
        $result = $this->loadModel('common')->removeDuplicate('story', $story, "product={$story->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

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

        $this->dao->insert(TABLE_STORY)->data($story, 'spec,verify')->autoCheck()->batchCheck($requiredFields, 'notempty')->exec();
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
                $lastOrder = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
                $this->dao->insert(TABLE_PROJECTSTORY)
                    ->set('project')->eq($projectID)
                    ->set('product')->eq($this->post->product)
                    ->set('story')->eq($storyID)
                    ->set('version')->eq(1)
                    ->set('order')->eq($lastOrder + 1)
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
     * Create a batch stories.
     *
     * @access public
     * @return int|bool the id of the created story or false when error.
     * @return type requirement|story
     */
    public function batchCreate($productID = 0, $branch = 0, $type = 'story')
    {
        $this->loadModel('action');
        $branch    = (int)$branch;
        $productID = (int)$productID;
        $now       = helper::now();
        $mails     = array();
        $stories   = fixer::input('post')->get();

        $result  = $this->loadModel('common')->removeDuplicate('story', $stories, "product={$productID}");
        $stories = $result['data'];

        $module = 0;
        $plan   = 0;
        $pri    = 0;
        $source = '';

        foreach($stories->title as $i => $title)
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

        $extendFields = $this->getFlowExtendFields();
        $forceReview  = $this->checkForceReview();
        $data         = array();
        foreach($stories->title as $i => $title)
        {
            if(empty($title)) continue;
            $story = new stdclass();
            $story->type       = $type;
            $story->branch     = isset($stories->branch[$i]) ? $stories->branch[$i] : 0;
            $story->module     = $stories->module[$i];
            $story->plan       = $stories->plan[$i];
            $story->color      = $stories->color[$i];
            $story->title      = $stories->title[$i];
            $story->source     = $stories->source[$i];
            $story->pri        = $stories->pri[$i];
            $story->estimate   = $stories->estimate[$i];
            $story->status     = ($stories->needReview[$i] == 0 and !$forceReview) ? 'active' : 'draft';
            $story->keywords   = $stories->keywords[$i];
            $story->product    = $productID;
            $story->openedBy   = $this->app->user->account;
            $story->openedDate = $now;
            $story->version    = 1;

            foreach($extendFields as $extendField)
            {
                $story->{$extendField->field} = htmlspecialchars($this->post->{$extendField->field}[$i]);
                $message = $this->checkFlowRule($extendField, $story->{$extendField->field});
                if($message) die(js::alert($message));
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

        foreach($data as $i => $story)
        {
            $this->dao->insert(TABLE_STORY)->data($story)->autoCheck()->exec();
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

        $story = fixer::input('post')->stripTags($this->config->story->editor->change['id'], $this->config->allowedTags)->get();
        if($story->spec != $oldStory->spec or $story->verify != $oldStory->verify or $story->title != $oldStory->title or $this->loadModel('file')->getCount()) $specChanged = true;

        $now   = helper::now();
        $story = fixer::input('post')
            ->callFunc('title', 'trim')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
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
        if($specChanged and $story->status == 'active' and $this->checkForceReview()) $story->status = 'changed';
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
            ->cleanInt('product,module,pri,duplicateStory')
            ->cleanFloat('estimate')
            ->setDefault('assignedDate', $oldStory->assignedDate)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->setDefault('plan', '')
            ->setDefault('status', $oldStory->status)
            ->setDefault('product', $oldStory->product)
            ->setDefault('branch', $oldStory->branch)
            ->setIF(!$this->post->linkStories, 'linkStories', '')
            ->setIF($this->post->assignedTo   != $oldStory->assignedTo, 'assignedDate', $now)
            ->setIF($this->post->closedBy     != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedReason != false and $oldStory->closedDate == '', 'closedDate', $now)
            ->setIF($this->post->closedBy     != false or  $this->post->closedReason != false, 'status', 'closed')
            ->setIF($this->post->closedReason != false and $this->post->closedBy     == false, 'closedBy', $this->app->user->account)
            ->setIF(!empty($_POST['plan'][0]) and $oldStory->stage == 'wait', 'stage', 'planned')
            ->stripTags($this->config->story->editor->edit['id'], $this->config->allowedTags)
            ->join('reviewedBy', ',')
            ->join('mailto', ',')
            ->join('linkStories', ',')
            ->join('childStories', ',')
            ->remove('files,labels,comment,contactListMenu,stages')
            ->get();
        if(isset($story->plan) and is_array($story->plan)) $story->plan = trim(join(',', $story->plan), ',');
        if(empty($_POST['product'])) $story->branch = $oldStory->branch;
        if(empty($_POST['branch']))  $story->branch = 0;
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
                $this->dao->insert(TABLE_STORYSTAGE)->data($newStage)->exec();
                if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) < $minStagePos)
                {
                    $minStage    = $stage;
                    $minStagePos = strpos($stageList, $stage);
                }
            }
            $story->stage = $minStage;
        }
        if(isset($story->stage) and $oldStory->stage != $story->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';

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

            unset($oldStory->parent);
            unset($story->parent);
            $this->setStage($storyID);
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

            $extendFields = $this->getFlowExtendFields();
            foreach($storyIdList as $storyID)
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
                if($story->stage != $oldStory->stage) $story->stagedBy = (strpos('tested|verified|released|closed', $story->stage) !== false) ? $this->app->user->account : '';

                if($story->title != $oldStory->title and $story->status != draft)    $story->status     = 'changed';
                if($story->plan         !== false and $story->plan == '')            $story->plan       = 0;
                if($story->closedBy     != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedReason != false  and $oldStory->closedDate == '')   $story->closedDate = $now;
                if($story->closedBy     != false  or  $story->closedReason != false) $story->status     = 'closed';
                if($story->closedReason != false  and $story->closedBy     == false) $story->closedBy   = $this->app->user->account;

                foreach($extendFields as $extendField)
                {
                    $story->{$extendField->field} = htmlspecialchars($this->post->{$extendField->field}[$storyID]);
                    $message = $this->checkFlowRule($extendField, $story->{$extendField->field});
                    if($message) die(js::alert($message));
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
                    $this->executeHooks($storyID);

                    $this->setStage($storyID);
                    if($story->closedReason == 'done') $this->loadModel('score')->create('story', 'close');
                    $allChanges[$storyID] = common::createChanges($oldStory, $story);
                }
                else
                {
                    die(js::error('story#' . $storyID . dao::getError(true)));
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
        if($this->post->result == false)   die(js::alert($this->lang->story->mustChooseResult));
        if($this->post->result == 'revert' and $this->post->preVersion == false) die(js::alert($this->lang->story->mustChoosePreVersion));

        if(strpos($this->config->story->review->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        $now      = helper::now();
        $date     = helper::today();
        $story = fixer::input('post')
            ->setDefault('reviewedDate', $date)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('reviewedBy', '')
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'draft',   'status', 'active')
            ->setIF($this->post->result == 'pass' and $oldStory->status == 'changed', 'status', 'active')
            ->setIF($this->post->result == 'reject', 'closedBy',   $this->app->user->account)
            ->setIF($this->post->result == 'reject', 'closedDate', $now)
            ->setIF($this->post->result == 'reject', 'assignedTo', 'closed')
            ->setIF($this->post->result == 'reject', 'status', 'closed')
            ->setIF($this->post->result == 'reject', 'stage', 'closed')
            ->setIF($this->post->result == 'revert', 'version', $this->post->preVersion)
            ->setIF($this->post->result == 'revert', 'status',  'active')
            ->setIF($this->post->closedReason == 'done', 'stage', 'released')
            ->removeIF($this->post->result != 'reject', 'closedReason, duplicateStory, childStories')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'duplicate', 'duplicateStory')
            ->removeIF($this->post->result == 'reject' and $this->post->closedReason != 'subdivided', 'childStories')
            ->join('reviewedBy', ',')
            ->remove('result,preVersion,comment')
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

            /* Delete versions that is after this version. */
            $deleteVersion = array();
            for($version = $oldStory->version; $version > $story->version; $version --) $deleteVersion[] = $version;
            if($deleteVersion) $this->dao->delete()->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->andWHere('version')->in($deleteVersion)->exec();

            $this->dao->delete()->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->andWhere('extra')->eq($oldStory->version)->exec();
        }
        if($this->post->result != 'reject') $this->setStage($storyID);

        return common::createChanges($oldStory, $story);
    }

    /**
     * Batch review stories.
     *
     * @param  array   $storyIdList
     * @access public
     * @return array
     */
    function batchReview($storyIdList, $result, $reason)
    {
        $now     = helper::now();
        $date    = helper::today();
        $actions = array();
        $this->loadModel('action');

        $oldStories = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
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
     * @return void
     */
    public function subdivide($storyID, $stories)
    {
        $now      = helper::now();
        $oldStory = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();

        /* Set parent to child story. */
        $this->dao->update(TABLE_STORY)->set('parent')->eq($storyID)->where('id')->in($stories)->exec();
        $this->computeEstimate($storyID);

        /* Set childStories. */
        $childStories = join(',', $stories);

        $newStory = new stdClass();
        $newStory->parent         = '-1';
        $newStory->plan           = 0;
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
            ->add('assignedTo',   'closed')
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

        $this->lang->story->comment = $this->lang->comment;
        $this->dao->update(TABLE_STORY)->data($story, 'comment')
            ->autoCheck()
            ->batchCheck($this->config->story->close->requiredFields, 'notempty')
            ->checkIF($story->closedReason == 'duplicate', 'duplicateStory', 'notempty')
            ->where('id')->eq($storyID)->exec();

        /* Update parent story status. */
        if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);
        $this->setStage($storyID);
        if(!dao::isError()) $this->loadModel('score')->create('story', 'close', $storyID);
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
                /* Update parent story status. */
                if($oldStory->parent > 0) $this->updateParentStatus($storyID, $oldStory->parent);
                $this->setStage($storyID);
                $allChanges[$storyID] = common::createChanges($oldStory, $story);
            }
            else
            {
                die(js::error('story#' . $storyID . dao::getError(true)));
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
        $now         = helper::now();
        $allChanges  = array();
        $oldStories  = $this->getByList($storyIdList);
        $plan        = $this->loadModel('productplan')->getById($planID);
        foreach($storyIdList as $storyID)
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
     * @param  array  $storyIdList
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function batchChangeBranch($storyIdList, $branchID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldStories = $this->getByList($storyIdList);
        foreach($storyIdList as $storyID)
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

        $story = new stdclass();
        $story->lastEditedBy   = $this->app->user->account;
        $story->lastEditedDate = $now;
        $story->assignedTo     = $assignedTo;
        $story->assignedDate   = $now;

        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq((int)$storyID)->exec();
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
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_STORY)->data($story)->autoCheck()->where('id')->eq($storyID)->exec();
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
        $storyID = (int)$storyID;
        $account = $this->app->user->account;

        /* Get projects which status is doing. */
        $oldStages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->fetchAll('branch');
        $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->exec();

        $story = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch();
        if(!empty($story->stagedBy) and $story->status != 'closed') return false;

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

        /* When the status is closed, phase = is also changed to closed. */
        if($story->status == 'closed')
        {
            $this->dao->update(TABLE_STORY)->set('stage')->eq('closed')->where('id')->eq($storyID)->exec();
            foreach($stages as $branch => $stage) $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('closed')->exec();
            foreach($projects as $project => $branch) $this->dao->replace(TABLE_STORYSTAGE)->set('story')->eq($storyID)->set('branch')->eq($branch)->set('stage')->eq('closed')->exec();
            return false;
        }

        /* If no projects, in plan, stage is planned. No plan, wait. */
        if(!$projects)
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
        $stage = $currentStage;

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

                $branch = $projects[$task->project];
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
         * 2. all devel tasks done, all test tasks waiting, set stage as developed.
         * 3. one test task doing, set stage as testing.
         * 4. all test tasks done, still some devel tasks not done(wait, doing), set stage as testing.
         * 5. all test tasks done, all devel tasks done, set stage as tested.
         */
        foreach($branchStatusList as $branch => $statusList)
        {
            $stage      = 'projected';
            $testTasks  = isset($branchTestTasks[$branch]) ? $branchTestTasks[$branch] : 0;
            $develTasks = isset($branchDevelTasks[$branch]) ? $branchDevelTasks[$branch] : 0;
            if($statusList['devel']['doing'] > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developing';
            if($statusList['devel']['wait'] > 0 and $statusList['devel']['done'] > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developing';
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['wait'] == $testTasks) $stage = 'developed';
            if($statusList['devel']['done'] == $develTasks and $develTasks > 0 and $statusList['test']['wait'] > 0 and $statusList['test']['done'] > 0) $stage = 'testing';
            if($statusList['test']['doing'] > 0 or $statusList['test']['pause'] > 0) $stage = 'testing';
            if(($statusList['devel']['wait'] > 0 or $statusList['devel']['doing'] > 0) and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'testing';
            if($statusList['devel']['done'] == $develTasks and $statusList['test']['done'] == $testTasks and $testTasks > 0) $stage = 'tested';

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

        return;
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
            $stories2Link = $this->getBySearch($story->product, $story->branch, $queryID, 'id');
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

        if(is_array($branch))
        {
            unset($branch[0]);
            $branch = join(',', $branch);
            if($branch) $branch = "0,$branch";
        }
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->beginIF(!$hasParent)->andWhere("parent")->ge(0)->fi()
            ->beginIF($branch)->andWhere("branch")->in($branch)->fi()
            ->beginIF(!empty($moduleIdList))->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!empty($excludeStories))->andWhere('id')->notIN($excludeStories)->fi()
            ->beginIF($status and $status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq($type)
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
        return $this->mergePlanTitle($productID, $stories, $branch, $type);
    }

    /**
     * Get stories pairs of a product.
     *
     * @param  int           $productID
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
    public function getProductStoryPairs($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $order = 'id_desc', $limit = 0, $type = 'full', $storyType = 'story', $hasParent = true)
    {
        if($branch) $branch = "0,$branch";//Fix bug 1059.
        $stories = $this->dao->select('t1.id, t1.title, t1.module, t1.pri, t1.estimate, t2.name AS product')
            ->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($productID)->andWhere('t1.product')->in($productID)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF(!$hasParent)->andWhere('t1.parent')->ge(0)->fi()
            ->beginIF($status and $status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq($storyType)
            ->orderBy($order)
            ->fetchAll();
        if(!$stories) return array();
        return $this->formatStories($stories, $type, $limit);
    }

    /**
     * Get stories by assignedTo.
     *
     * @param  int    $productID
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAssignedTo($productID, $branch, $modules, $account, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'assignedTo', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by openedBy.
     *
     * @param  int    $productID
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByOpenedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'openedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by reviewedBy.
     *
     * @param  int    $productID
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByReviewedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'reviewedBy', $account, $type, $orderBy, $pager, 'include');
    }

    /**
     * Get stories by closedBy.
     *
     * @param  int    $productID
     * @param  string $account
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @return array
     */
    public function getByClosedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'closedBy', $account, $type, $orderBy, $pager);
    }

    /**
     * Get stories by status.
     *
     * @param  int    $productID
     * @param  string $status
     * @param  string $type    requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByStatus($productID, $branch, $modules, $status, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'status', $status, $type, $orderBy, $pager);
    }

    /**
     * Get stories by plan.
     *
     * @param int    $productID
     * @param int    $branch
     * @param array  $modules
     * @param int    $plan
     * @param string $type    requirement|story
     * @param string $orderBy
     * @param object $pager
     *
     * @return array
     */
    public function getByPlan($productID, $branch, $modules, $plan, $type = 'story', $orderBy, $pager)
    {
        return $this->getByField($productID, $branch, $modules, 'plan', $plan, $type, $orderBy, $pager);
    }

    /**
     * Get stories by a field.
     *
     * @param  int    $productID
     * @param  string $fieldName
     * @param  mixed  $fieldValue
     * @param  string $type         requirement|story
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $operator     equal|include
     * @access public
     * @return array
     */
    public function getByField($productID, $branch, $modules, $fieldName, $fieldValue, $type = 'story', $orderBy, $pager, $operator = 'equal')
    {
        if(!$this->loadModel('common')->checkField(TABLE_STORY, $fieldName)) return array();
        $stories = $this->dao->select('*')->from(TABLE_STORY)
            ->where('product')->in($productID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq($type)
            ->beginIF($branch)->andWhere("branch")->eq($branch)->fi()
            ->beginIF($modules)->andWhere("module")->in($modules)->fi()
            ->beginIF($operator == 'equal')->andWhere($fieldName)->eq($fieldValue)->fi()
            ->beginIF($operator == 'include')->andWhere($fieldName)->like("%$fieldValue%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        return $this->mergePlanTitle($productID, $stories, $branch, $type);
    }

    /**
     * Get to be closed stories.
     *
     * @param  int    $productID
     * @param  string $type requirement|story
     * @param  string $orderBy
     * @param  string $pager
     * @access public
     * @return array
     */
    public function get2BeClosed($productID, $branch, $modules, $type = 'story', $orderBy, $pager)
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
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  string $projectID
     * @param  string $type requirement|story
     * @param  string $excludeStories 
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($productID, $branch = 0, $queryID, $orderBy, $projectID = '', $type = 'story', $excludeStories = '', $pager = null)
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
        if($excludeStories) $storyQuery = $storyQuery . ' AND `id` NOT ' . helper::dbIN($excludeStories);
        if($this->app->moduleName == 'productplan') $storyQuery .= " AND `status` NOT IN ('closed') AND `parent` >= 0 ";
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

        $sql = str_replace(array('`product`', '`version`'), array('t1.`product`', 't1.`version`'), $sql);
        $tmpStories = $this->dao->select('DISTINCT t1.*')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where($sql)
            ->beginIF($productID != 'all' and $productID != '')->andWhere('t1.`product`')->eq((int)$productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('type')->eq($type)
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
            $stories[] = $story;
        }
        return $stories;
    }

    /**
     * Get stories list of a project.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $type
     * @param  int    $param
     * @param  string $storyType 
     * @param  string $excludeStories 
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectStories($projectID = 0, $orderBy = 't1.`order`_desc', $type = 'byModule', $param = 0, $storyType = 'story', $excludeStories = '', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectStories();

        $type = strtolower($type);
        if($type == 'bysearch')
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
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.type')->eq($storyType)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->orderBy($orderBy)
                ->page($pager, 't2.id')
                ->fetchAll('id');
        }
        else
        {
            $productParam = ($type == 'byproduct' and $param) ? $param : $this->cookie->storyProductParam;
            $branchParam  = ($type == 'bybranch' and $param)  ? $param : $this->cookie->storyBranchParam;
            $moduleParam  = ($type == 'bymodule' and $param)  ? $param : $this->cookie->storyModuleParam;
            $modules      = empty($moduleParam) ? array() : $this->dao->select('*')->from(TABLE_MODULE)->where('path')->like("%,$moduleParam,%")->andWhere('type')->eq('story')->andWhere('deleted')->eq(0)->fetchPairs('id', 'id');
            if(strpos($branchParam, ',') !== false) list($productParam, $branchParam) = explode(',', $branchParam);

            $unclosedStatus = $this->lang->story->statusList;
            unset($unclosedStatus['closed']);

            $stories = $this->dao->select('distinct t1.*, t2.*,t3.branch as productBranch,t4.type as productType,t2.version as version')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.project = t3.project')
                ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t2.product = t4.id')
                ->where('t1.project')->eq((int)$projectID)
                ->andWhere('t2.type')->eq($storyType)
                ->beginIF($excludeStories)->andWhere('t2.id')->notIN($excludeStories)->fi()
                ->beginIF(!empty($productParam))->andWhere('t1.product')->eq($productParam)->fi()
                ->beginIF(!empty($branchParam))->andWhere('t2.branch')->eq($branchParam)->fi()
                ->beginIF($modules)->andWhere('t2.module')->in($modules)->fi()
                ->beginIF($this->session->projectStoryBrowseType == 'unclosed')->andWhere('t2.status')->in(array_keys($unclosedStatus))->fi()
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
        foreach($branches as $branchID => $storyIdList)
        {
            $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->andWhere('branch')->eq($branchID)->fetchPairs('story', 'stage');
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
            ->orderBy('t1.`order` desc')
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
            ->andWhere('status')->notin('closed,draft')
            ->andWhere('product')->eq($productID)
            ->beginIF($append)->orWhere('id')->in($append)->fi()
            ->fetchPairs();
        return array(0 => '') + $stories ;
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
    public function getUserStories($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null, $storyType = 'story')
    {
        $stories = $this->dao->select('t1.*, t2.name as productTitle')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->eq($storyType)
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')
            ->beginIF($type == 'assignedTo')->andWhere('assignedTo')->eq($account)->fi()
            ->beginIF($type == 'openedBy')->andWhere('openedBy')->eq($account)->fi()
            ->beginIF($type == 'reviewedBy')->andWhere("CONCAT(',', reviewedBy, ',')")->like("%,$account,%")->fi()
            ->beginIF($type == 'closedBy')->andWhere('closedBy')->eq($account)->fi()
            ->fi()
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
     * @access public
     * @return array
     */
    public function getUserStoryPairs($account, $limit = 10, $type = 'story')
    {
        return $this->dao->select('id, title')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($type)
            ->andWhere('assignedTo')->eq($account)
            ->orderBy('id_desc')
            ->limit($limit)
            ->fetchPairs('id', 'title');
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
        if($projects) return($this->dao->select('account')->from(TABLE_TEAM)->where('root')->in($projects)->andWhere('type')->eq('project')->fetchPairs('account'));
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
        $allStories   = $this->getProductStories($productID, 0, 0, 'all', 'story', $orderBy, $hasParent = false, '', null);
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
        $storyIdList      = array();
        $storyVersionList = array();
        foreach($dataList as $key => $data)
        {
            $data->needconfirm = false;
            if($data->story)
            {
                $storyIdList[$key]      = $data->story;
                $storyVersionList[$key] = $data->storyVersion;
            }
        }

        $stories = $this->dao->select('id,version')->from(TABLE_STORY)->where('id')->in($storyIdList)->andWhere('status')->eq('active')->fetchPairs('id', 'version');
        foreach($storyIdList as $key => $storyID)
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
        $storyPairs = array(0 => '');
        $i = 0;
        foreach($stories as $story)
        {
            if($type == 'short')
            {
                $property = '[p' . (!empty($this->lang->story->priList[$story->pri]) ? $this->lang->story->priList[$story->pri] : 0) . ', ' . $story->estimate . 'h]';
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

        if($story->parent < 0 and $action != 'edit' and $action != 'batchcreate') return false;

        if($action == 'change')     return $story->status != 'closed';
        if($action == 'review')     return $story->status == 'draft' or $story->status == 'changed';
        if($action == 'close')      return $story->status != 'closed';
        if($action == 'activate')   return $story->status == 'closed';
        if($action == 'assignto')   return $story->status != 'closed';
        if($action == 'createcase') return $story->type != 'requirement';
        if($action == 'batchcreate' and $story->parent > 0) return false;
        if($action == 'batchcreate' and $story->type == 'requirement') return $story->status != 'draft';
        if($action == 'batchcreate' and ($story->status != 'active' or $story->stage != 'wait')) return false;

        return true;
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
            ->where('product')->in($productID)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'title');

        /* For requirement children. */
        if($type == 'requirement' && !empty($this->config->URAndSR))
        {
            $relations = $this->dao->select('DISTINCT AID, BID')->from(TABLE_RELATION)
              ->where('AID')->in(array_keys($stories))  
              ->andWhere('AType')->eq('requirement')  
              ->andWhere('BType')->eq('story')  
              ->andWhere('relation')->eq('subdivideinto')  
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
        $canView   = common::hasPriv('story', 'view');
        $storyLink = helper::createLink('story', 'view', "storyID=$story->id");
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
            else if($id == 'sourceNote')
            {
                $title  = $story->sourceNote;
                $class .= ' text-ellipsis';
            }
            else if($id == 'reviewedBy')
            {
                $reviewedBy = '';
                foreach(explode(',', $story->reviewedBy) as $user) $reviewedBy .= zget($users, $user) . ' ';
                $story->reviewedBy = $reviewedBy;

                $title  = $reviewedBy;
                $class .= ' text-ellipsis';
            }
            else if($id == 'stage')
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

            echo "<td class='" . $class . "' title='$title' style='$style'>";
            if(isset($this->config->bizVersion)) $this->loadModel('flow')->printFlowCell('story', $story, $id);
            switch($id)
            {
            case 'id':
                echo html::checkbox('storyIdList', array($story->id => '')) . html::a(helper::createLink('story', 'view', "storyID=$story->id"), sprintf('%03d', $story->id));
                break;
            case 'pri':
                echo "<span class='label-pri label-pri-" . $story->pri . "' title='" . zget($this->lang->story->priList, $story->pri, $story->pri) . "'>";
                echo zget($this->lang->story->priList, $story->pri, $story->pri);
                echo "</span>";
                break;
            case 'title':
                if($storyType == 'requirement') echo '<span class="label label-badge label-light">SR</span> ';
                if($story->parent > 0 and isset($story->parentName)) $story->title = "{$story->parentName} / {$story->title}";
                if($story->branch and isset($branches[$story->branch])) echo "<span class='label label-outline label-badge'>{$branches[$story->branch]}</span> ";
                if($story->module and isset($modulePairs[$story->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$story->module]}</span> ";
                if($story->parent > 0) echo '<span class="label label-badge label-light" title="' . $this->lang->story->children . '">' . $this->lang->story->childrenAB . '</span> ';
                echo $canView ? html::a($storyLink, $story->title, '', "style='color: $story->color'") : "<span style='color: $story->color'>{$story->title}</span>";
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
            case 'status':
                echo "<span class='status-{$story->status}'>";
                echo $this->processStatus('story', $story);
                echo '</span>';
                break;
            case 'estimate':
                echo $story->estimate;
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
                $vars = "story={$story->id}";
                common::printIcon('story', 'change',     $vars, $story, 'list', 'fork');
                common::printIcon('story', 'review',     $vars, $story, 'list', 'glasses');
                common::printIcon('story', 'close',      $vars, $story, 'list', '', '', 'iframe', true);
                common::printIcon('story', 'edit',       $vars, $story, 'list');
                if($this->config->global->flow != 'onlyStory') common::printIcon('story', 'createCase', "productID=$story->product&branch=$story->branch&module=0&from=&param=0&$vars", $story, 'list', 'sitemap');
                common::printIcon('story', 'batchCreate', "productID=$story->product&branch=$story->branch&module=0&storyID=$story->id", $story, 'list', 'treemap-alt', '', '', '', '', $this->lang->story->subdivide);
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
        $story = $this->getById($storyID);
        $users = $this->loadModel('user')->getPairs('noletter');

        /* Get actions. */
        $action  = $this->loadModel('action')->getById($actionID);
        $history = $this->action->getHistory($actionID);
        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($id)
            {
                $name = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($action->objectType, 'view', "id=$id", 'html'), "#$id " . $name);
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

        $sendUsers = $this->getToAndCcList($story, $action->action);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $subject = $this->getSubject($story);

        /* Send it. */
        $this->mail->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Get mail subject.
     *
     * @param  object    $story
     * @access public
     * @return string
     */
    public function getSubject($story)
    {
        $productName = $this->loadModel('product')->getById($story->product)->name;
        return 'STORY #' . $story->id . ' ' . $story->title . ' - ' . $productName;
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

        /* If the action is changed or reviewed, mail to the project team. */
        if(strtolower($actionType) == 'changed' or strtolower($actionType) == 'reviewed')
        {
            $prjMembers = $this->getProjectMembers($story->id);
            if($prjMembers)
            {
                $ccList .= ',' . join(',', $prjMembers);
                $ccList = ltrim($ccList, ',');
            }
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
}
