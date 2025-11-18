<?php
declare(strict_types=1);
/**
 * The model file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     tutorial
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class tutorialModel extends model
{
    /**
     * 检查新手模式配置。
     * Check novice mode config.
     *
     * @access public
     * @return bool
     */
    public function checkNovice(): bool
    {
        $account = $this->app->user->account;
        if($account == 'guest') return false;
        if(!empty($this->app->user->modifyPassword)) return false;

        $count = $this->dao->select('COUNT(1) AS count')->from(TABLE_ACTION)->where('actor')->eq($account)->fetch('count');
        if($count < 10) return true;

        $this->loadModel('setting')->setItem($account . '.common.global.novice', 0);
        return false;
    }

    /**
     * 获取新手模式产品键值对。
     * Get tutorial product pairs.
     *
     * @access public
     * @return array
     */
    public function getProductPairs(): array
    {
        return array(1 => 'Test product');
    }

    /**
     * 获取新手模式模块键值对。
     * Get module pairs for tutorial.
     *
     * @access public
     * @return array
     */
    public function getModulePairs(): array
    {
        return array(1 => 'Test module');
    }

    /**
     * 获取新手模式产品信息。
     * Get tutorial product.
     *
     * @access public
     * @return object
     */
    public function getProduct(): object
    {
        $product = new stdclass();
        $product->id                = 1;
        $product->program           = 0;
        $product->line              = 0;
        $product->plan              = 0;
        $product->name              = 'Test product';
        $product->code              = 'test';
        $product->type              = 'normal';
        $product->status            = 'normal';
        $product->desc              = '';
        $product->shadow            = '0';
        $product->PO                = $this->app->user->account;
        $product->QD                = '';
        $product->RD                = '';
        $product->acl               = 'open';
        $product->createdBy         = $this->app->user->account;
        $product->createdDate       = helper::now();
        $product->createdVersion    = '8.1.3';
        $product->order             = 10;
        $product->deleted           = '0';
        $product->branch            = '';
        $product->reviewer          = $this->app->user->account;
        $product->branches          = array();
        $product->plans             = array('1' => 'Test plan');
        $product->totalEpics        = 0;
        $product->totalRequirements = 0;
        $product->feedback          = 0;
        $product->ticket            = 0;
        $product->workflowGroup     = 1;

        list($guide, $guideTask, $guideStepIndex) = empty($_SERVER['HTTP_X_ZIN_TUTORIAL']) ? array('', '', '') : explode('-', $_SERVER['HTTP_X_ZIN_TUTORIAL']);
        if($guideTask == 'branchManage')
        {
            $product->type = 'branch';
            $product->name = 'Test branch product';
        }

        return $product;
    }

    /**
     * 获取新手模式产品统计数据。
     * Get product stats for tutorial.
     *
     * @param  bool   $isArray
     * @access public
     * @return array
     */
    public function getProductStats(bool $isArray = false): array
    {
        $product = $this->getProduct();
        $product->totalStories      = 0;
        $product->draftStories      = 0;
        $product->activeStories     = 0;
        $product->changingStories   = 0;
        $product->reviewingStories  = 0;
        $product->releases          = 0;
        $product->unresolvedBugs    = 0;
        $product->fixedBugs         = 0;
        $product->lineName          = 0;
        $product->executions        = 0;
        $product->coverage          = 0;
        $product->activeBugs        = 0;
        $product->latestReleaseDate = 0;
        $product->latestRelease     = 0;
        $product->plans             = 0;

        $productStat[$product->id] = $isArray ? json_decode(json_encode($product), true) : $product;
        return $productStat;
    }

    /**
     * 获取新手模式项目。
     * Get project for tutorial;
     *
     * @access public
     * @return object
     */
    public function getProject(): object
    {
        $project = new stdclass();
        $project->id            = 2;
        $project->project       = 0;
        $project->model         = 'scrum';
        $project->type          = 'project';
        $project->name          = 'Test Project';
        $project->code          = '';
        $project->lifetime      = '';
        $project->begin         = date('Y-m-d', strtotime('-7 days'));
        $project->end           = date('Y-m-d', strtotime('+7 days'));
        $project->realBegan     = '';
        $project->realEnd       = '';
        $project->days          = 10;
        $project->status        = 'wait';
        $project->pri           = '1';
        $project->desc          = '';
        $project->goal          = '';
        $project->acl           = 'open';
        $project->parent        = 0;
        $project->path          = ",2,";
        $project->grade         = 1;
        $project->PM            = $this->app->user->account;
        $project->PO            = $this->app->user->account;
        $project->QD            = $this->app->user->account;
        $project->RD            = $this->app->user->account;
        $project->openedBy      = $this->app->user->account;
        $project->whitelist     = '';
        $project->budget        = 0;
        $project->displayCards  = 0;
        $project->fluidBoard    = 0;
        $project->deleted       = '0';
        $project->hasProduct    = 1;
        $project->multiple      = '1';
        $project->stageBy       = 'project';
        $project->progress      = 0;
        $project->consumed      = 0;
        $project->estimate      = 0;
        $project->left          = 0;
        $project->storyType     = 'story,requirement,epic';
        $project->charter       = 0;
        $project->market        = 1;
        $project->budgetUnit    = 'CNY';
        $project->deliverable   = '';
        $project->isTpl         = 0;
        $project->linkType      = '';
        $project->workflowGroup = 0;

        list($guide, $guideTask, $guideStepIndex) = empty($_SERVER['HTTP_X_ZIN_TUTORIAL']) ? array('', '', '') : explode('-', $_SERVER['HTTP_X_ZIN_TUTORIAL']);
        if($guide && strpos($guide, 'scrumProjectManage') !== false)
        {
            $project->name  = 'Scrum Project';
            $project->model = 'scrum';
        }
        if($guide && strpos($guide, 'waterfallProjectManage') !== false)
        {
            $project->name  = 'Waterfall Project';
            $project->model = 'waterfall';
        }
        if($guide && strpos($guide, 'kanbanProjectManage') !== false)
        {
            $project->name  = 'Kanban Project';
            $project->model = 'kanban';
        }
        if($guide && strpos($guide, 'taskManage') !== false)
        {
            $project->name     = 'No multiple Project';
            $project->model    = 'scrum';
            $project->multiple = '0';
        }
        if($guide && strpos($guide, 'marketManage') !== false)
        {
            $project->name  = 'Test research';
            $project->model = 'research';
        }

        return $project;
    }

    /**
     * 获取新手模式项目键值对。
     * Get tutorial project pairs.
     *
     * @access public
     * @return array
     */
    public function getProjectPairs(): array
    {
        return array(2 => 'Test Project');
    }

    /**
     * 获取新手模式项目统计数据。
     * Get project stats for tutorial
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProjectStats(string $browseType = ''): array
    {
        $project = $this->getProject();

        $project->progress    = 0;
        $project->estimate    = 0;
        $project->consumed    = 0;
        $project->left        = 0;
        $project->leftTasks   = '—';
        $project->teamMembers = array_keys($this->getTeamMembers());
        $project->teamCount   = count($project->teamMembers);

        if($browseType && $browseType != 'all') $project->name .= '-' . $browseType; // Fix bug #21096

        $projectStat[$project->id] = $project;
        return $projectStat;
    }

    /**
     * 获取新手模式迭代燃尽图数据。
     * Get execution burn data for tutorial
     *
     * @param  array $dateList
     * @access public
     * @return array
     */
    public function getExecutionBurnData(array $dateList): array
    {
        $burnData = array();
        $left     = 7;
        $value    = 6;
        foreach($dateList as $date)
        {
            $burn = new stdClass();
            $burn->name  = $date;
            $burn->value = $value --;
            $burn->left  = $left --;
            $burnData[$date] = $burn;
        }
        return $burnData;
    }

    /**
     * 获取新手模式执行统计数据。
     * Get execution stats for tutorial.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getExecutionStats($browseType = ''): array
    {
        $execution = $this->getExecution();

        $execution->progress      = 0;
        $execution->estimate      = 0;
        $execution->consumed      = 0;
        $execution->left          = 0;
        $execution->leftTasks     = '—';
        $execution->teamMembers   = array_keys($this->getTeamMembers());
        $execution->teamCount     = count($execution->teamMembers);
        $execution->hasProduct    = '1';
        $execution->multiple      = '';
        $execution->order         = 1;
        $execution->burns         = array();
        $execution->type          = 'sprint';
        $execution->projectName   = '';
        $execution->projectModel  = 'scrum';
        $execution->deliverable   = '';

        if($browseType && $browseType != 'all') $execution->name .= '-' . $browseType;

        $executionStat[0] = $execution;
        return $executionStat;
    }

    /**
     * 获取新手模式研发需求键值对。
     * Get tutorial story pairs.
     *
     * @access public
     * @return array
     */
    public function getStoryPairs(): array
    {
        $stories = $this->getStories();
        $storyPairs = array();
        foreach($stories as $story) $storyPairs[$story->id] = $story->title;
        return $storyPairs;
    }

    /**
     * 获取新手模式研发需求。
     * Get tutorial story.
     *
     * @access public
     * @return object
     */
    public function getStory(): object
    {
        $story = new stdclass();
        $story->id             = 3;
        $story->product        = 1;
        $story->branch         = 0;
        $story->parent         = array(2);
        $story->category       = 0;
        $story->module         = '';
        $story->plan           = '';
        $story->planTitle      = '';
        $story->color          = '';
        $story->source         = 'po';
        $story->sourceNote     = '';
        $story->fromBug        = 0;
        $story->title          = 'Test active story';
        $story->keywords       = '';
        $story->type           = 'story';
        $story->grade          = 1;
        $story->pri            = 3;
        $story->estimate       = 1;
        $story->status         = 'active';
        $story->stage          = 'wait';
        $story->openedBy       = $this->app->user->account;
        $story->openedDate     = helper::now();
        $story->assignedTo     = '';
        $story->assignedDate   = '';
        $story->reviewedBy     = $this->app->user->account;
        $story->reviewedDate   = helper::now();
        $story->closedBy       = '';
        $story->closedDate     = '';
        $story->closedReason   = '';
        $story->toBug          = 0;
        $story->childStories   = '';
        $story->linkStories    = '';
        $story->duplicateStory = 0;
        $story->version        = 1;
        $story->deleted        = '0';
        $story->order          = '0';
        $story->URChanged      = false;
        $story->mailto         = '';
        $story->isParent       = 0;
        $story->roadmap        = 0;
        $story->root           = 1;
        $story->path           = ',1,2,3,';
        $story->lastEditedBy   = '';
        $story->lastEditedDate = '';
        $story->twins          = '';
        $story->executions     = array();
        $story->spec           = '';
        $story->verify         = '';
        $story->files          = array();
        $story->docs           = '';
        $story->docVersions    = '';
        return $story;
    }

    /**
     * 获取新手模式业务需求。
     * Get tutorial epic.
     *
     * @access public
     * @return object
     */
    public function getEpic(): object
    {
        $epic = $this->getStory();
        $epic->id       = 1;
        $epic->title    = 'Test epic';
        $epic->type     = 'epic';
        $epic->isParent = 1;
        $epic->parent   = array(0);
        $epic->root     = 1;
        $epic->path     = ',1,';
        return $epic;
    }

    /**
     * 获取新手模式用户需求。
     * Get tutorial requirement.
     *
     * @access public
     * @return object
     */
    public function getRequirement(): object
    {
        $requirement = $this->getStory();
        $requirement->id       = 2;
        $requirement->title    = 'Test requirement';
        $requirement->type     = 'requirement';
        $requirement->status   = 'active';
        $requirement->isParent = 1;
        $requirement->parent   = array(1);
        $requirement->root     = 1;
        $requirement->path     = ',1,2,';
        return $requirement;
    }

    /**
     * 根据需求ID获取需求详情。
     * Get story by ID.
     *
     * @access public
     * @return object
     */
    public function getStoryByID(int $storyID): object
    {
        if($storyID == 1) return $this->getEpic();
        if($storyID == 2) return $this->getRequirement();
        return $this->getStory();
    }

    /**
     * 获取需求层级。
     * Get story grade.
     *
     * @access public
     * @return object
     */
    public function getStoryGrade(): array
    {
        $storyGrade = new stdClass();
        $storyGrade->type   = 'story';
        $storyGrade->grade  = 1;
        $storyGrade->name   = 'SR';
        $storyGrade->status = 'enable';

        $requirementGrade = new stdClass();
        $requirementGrade->type   = 'requirement';
        $requirementGrade->grade  = 1;
        $requirementGrade->name   = 'UR';
        $requirementGrade->status = 'enable';

        $epicGrade = new stdClass();
        $epicGrade->type   = 'epic';
        $epicGrade->grade  = 1;
        $epicGrade->name   = 'BR';
        $epicGrade->status = 'enable';

        return array($storyGrade, $requirementGrade, $epicGrade);
    }

    /**
     * 获取需求层级键值对。
     * Get story grade pairs.
     *
     * @access public
     * @return array
     */
    public function getGradePairs(string $type): array
    {
        if($type == 'story')       return array(1 => 'SR');
        if($type == 'requirement') return array(1 => 'UR');
        if($type == 'epic')        return array(1 => 'BR');
        return array();
    }

    /**
     * 获取新手模式故事点。
     * Get tutorial stories.
     *
     * @access public
     * @return array
     */
    public function getStories(): array
    {
        $activeStory    = $this->getStory();
        $reviewingStory = $this->getStory();
        $reviewingStory->id        = 4;
        $reviewingStory->status    = 'reviewing';
        $reviewingStory->notReview = array($this->app->user->account);
        $reviewingStory->title     = 'Test reviewing story';
        $reviewingStory->path      = ',1,2,4,';

        $stories = array();
        if($this->app->config->systemMode != 'light')
        {
            $stories[1] = $this->getEpic();
            $stories[2] = $this->getRequirement();
        }
        if($this->app->config->vision == 'rnd')
        {
            $stories[3] = $activeStory;
            $stories[4] = $reviewingStory;
        }
        if($this->app->config->vision == 'or')
        {
            $reviewingRequirement = $this->getRequirement();
            $reviewingRequirement->id        = 5;
            $reviewingRequirement->status    = 'reviewing';
            $reviewingRequirement->notReview = array($this->app->user->account);
            $reviewingRequirement->title     = 'Test reviewing requirement';
            $reviewingRequirement->path      = ',1,5,';
            $reviewingRequirement->isParent  = 0;
            $stories[5] = $reviewingRequirement;
        }
        return $stories;
    }

    /**
     * 获取新手模式执行键值对。
     * Get tutorial Execution pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionPairs(): array
    {
        return array(2 => 'Test Project', 3 => 'Test execution');
    }

    /**
     * 获取新手模式阶段。
     * Get tutorial stage.
     *
     * @access public
     * @return object
     */
    public function getStage(): object
    {
        $stage = new stdClass();
        $stage->id          = 3;
        $stage->name        = 'Development stage';
        $stage->percent     = 50;
        $stage->type        = 'dev';
        $stage->projectType = 'waterfall';
        $stage->createdBy   = '';
        $stage->createdDate = '';
        $stage->editedBy    = '';
        $stage->editedDate  = '';
        $stage->deleted     = 0;
        return $stage;
    }

    /**
     * 获取新手模式阶段列表。
     * Get tutorial stages.
     *
     * @access public
     * @return array
     */
    public function getStages(): array
    {
        return array(3 => $this->getStage());
    }

    /**
     * 获取新手模式执行。
     * Get tutorial execution.
     *
     * @access public
     * @return object
     */
    public function getExecution(): object
    {
        /* Fix bug #21097. */
        $hours = new stdclass();
        $hours->totalEstimate = 52;
        $hours->totalConsumed = 43;
        $hours->totalLeft     = 7;
        $hours->progress      = 86;
        $hours->totalReal     = 50;

        $execution = new stdclass();
        $execution->id            = 3;
        $execution->project       = 2;
        $execution->type          = 'sprint';
        $execution->name          = 'Test execution';
        $execution->code          = 'test';
        $execution->lifetime      = '';
        $execution->attribute     = '';
        $execution->begin         = date('Y-m-d', strtotime('-7 days'));
        $execution->end           = date('Y-m-d', strtotime('+7 days'));
        $execution->realBegan     = '';
        $execution->realEnd       = '';
        $execution->suspendedDate = '';
        $execution->days          = 10;
        $execution->status        = 'wait';
        $execution->pri           = '1';
        $execution->desc          = '';
        $execution->goal          = '';
        $execution->acl           = 'open';
        $execution->parent        = 2;
        $execution->path          = ',2,3,';
        $execution->grade         = 1;
        $execution->PM            = $this->app->user->account;
        $execution->PO            = $this->app->user->account;
        $execution->QD            = $this->app->user->account;
        $execution->RD            = $this->app->user->account;
        $execution->deleted       = '0';
        $execution->consumed      = 0;
        $execution->left          = 0;
        $execution->hours         = 0;
        $execution->estimate      = 0;
        $execution->progress      = 0;
        $execution->displayCards  = 0;
        $execution->fluidBoard    = 0;
        $execution->hours         = $hours;
        $execution->burns         = array(35, 35);
        $execution->hasProduct    = '1';
        $execution->multiple      = '';
        $execution->colWidth      = '200';
        $execution->openedDate    = helper::now();
        $execution->closedDate    = helper::now();
        $execution->milestone     = 0;
        $execution->workflowGroup = 0;
        $execution->deliverable   = '';

        list($guide, $guideTask, $guideStepIndex) = empty($_SERVER['HTTP_X_ZIN_TUTORIAL']) ? array('', '', '') : explode('-', $_SERVER['HTTP_X_ZIN_TUTORIAL']);
        if($guide && strpos($guide, 'scrumProjectManage') !== false)
        {
            $execution->name = 'Test Sprint';
            $execution->type = 'sprint';
        }
        if($guide && strpos($guide, 'waterfallProjectManage') !== false)
        {
            $execution->name      = 'Test Stage';
            $execution->type      = 'stage';
            $execution->enabled   = 'on';
            $execution->percent   = 50;
            $execution->milestone = 0;
            $execution->parallel  = 1;
        }
        if($guide && strpos($guide, 'kanbanProjectManage') !== false)
        {
            $execution->name = 'Test Kanban';
            $execution->type = 'kanban';
        }
        if($guide && strpos($guide, 'taskManage') !== false)
        {
            $execution->name     = 'No multiple execution';
            $execution->type     = 'spring';
            $execution->multiple = 0;
        }

        return $execution;
    }

    /**
     * 获取新手模式执行的产品。
     * Get tutorial execution products.
     *
     * @access public
     * @return array
     */
    public function getExecutionProducts(): array
    {
        $product = $this->getProduct();
        return array($product->id => $product);
    }

    /**
     * 获取新手模式执行的需求。
     * Get tutorial execution stories.
     *
     * @access public
     * @return array
     */
    public function getExecutionStories(): array
    {
        $stories = $this->getStories();
        $story   = $stories[3];
        return array($story->id => $story);
    }

    /**
     * 获取新手模式执行的需求键值对。
     * Get tutorial execution story pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionStoryPairs(): array
    {
        $stories = $this->getStories();
        $story   = $stories[3];
        return array($story->id => $story->title);
    }

    /**
     * 获取新手模式团队成员。
     * Get tutorial team members.
     *
     * @access public
     * @return array
     */
    public function getTeamMembers(): array
    {
        $member = new stdclass();
        $member->project     = 2;
        $member->account     = $this->app->user->account;
        $member->role        = $this->app->user->role;
        $member->join        = $this->app->user->join;
        $member->days        = 10;
        $member->hours       = 7.0;
        $member->totalHours  = 70.0;
        $member->realname    = $this->app->user->realname;
        $member->limited     = 'no';
        $member->userID      = $this->app->user->id;
        return array($member->account => $member);
    }

    /**
     * 获取团队成员键值对。
     * Get team members pairs.
     *
     * @access public
     * @return array
     */
    public function getTeamMembersPairs(): array
    {
        $account = $this->app->user->account;
        return array('' => '', $account => $this->app->user->realname);
    }

    /**
     * 获取新手模式用户键值对。
     * Get tutorial user pairs.
     *
     * @access public
     * @return array
     */
    public function getUserPairs(): array
    {
        $account = $this->app->user->account;

        $users[$account] = $account;
        $users['test']   = 'Test';
        return $users;
    }

    /**
     * 获取新手模式进度。
     * Get tutorialed.
     *
     * @access public
     * @return string
     */
    public function getTutorialed(): string
    {
        return $this->dao->select('*')->from(TABLE_CONFIG)->where('module')->eq('tutorial')->andWhere('owner')->eq($this->app->user->account)->andWhere('section')->eq('tasks')->andWhere('`key`')->eq('setting')->fetch('value');
    }

    /**
     * 获取新手模式任务。
     * Get task.
     *
     * @access public
     * @return object
     */
    public function getTask(): object
    {
        $task = new stdClass();
        $task->id                 = 1;
        $task->project            = 2;
        $task->parent             = 0;
        $task->execution          = 3;
        $task->module             = 0;
        $task->design             = 0;
        $task->story              = 0;
        $task->storyVersion       = 1;
        $task->designVersion      = 1;
        $task->fromBug            = 0;
        $task->feedback           = 0;
        $task->fromIssue          = 0;
        $task->name               = 'Test task';
        $task->type               = 'devel';
        $task->mode               = '';
        $task->pri                = 3;
        $task->estimate           = 0;
        $task->consumed           = 0;
        $task->left               = 0;
        $task->deadline           = '';
        $task->status             = 'wait';
        $task->subStatus          = '';
        $task->color              = '';
        $task->mailto             = '';
        $task->keywords           = '';
        $task->desc               = '';
        $task->version            = 1;
        $task->openedBy           = 'admin';
        $task->openedDate         = '';
        $task->assignedTo         = '';
        $task->assignedDate       = '';
        $task->estStarted         = '';
        $task->realStarted        = '';
        $task->finishedBy         = '';
        $task->finishedDate       = '';
        $task->finishedList       = '';
        $task->canceledBy         = '';
        $task->canceledDate       = '';
        $task->closedBy           = '';
        $task->closedDate         = '';
        $task->planDuration       = 0;
        $task->realDuration       = 0;
        $task->closedReason       = '';
        $task->lastEditedBy       = '';
        $task->lastEditedDate     = '';
        $task->activatedDate      = '';
        $task->order              = 0;
        $task->repo               = 0;
        $task->mr                 = 0;
        $task->entry              = '';
        $task->lines              = '';
        $task->deleted            = 0;
        $task->vision             = 'rnd';
        $task->storyID            = '';
        $task->storyTitle         = '';
        $task->product            = '';
        $task->branch             = '';
        $task->latestStoryVersion = '';
        $task->storyStatus        = '';
        $task->priOrder           = 3;
        $task->assignedToRealName = '';
        $task->needConfirm        = '';
        $task->progress           = 0;
        $task->isParent           = 0;

        return $task;
    }

    /**
     * 获取新手模式任务。
     * Get taks.
     *
     * @access public
     * @return array
     */
    public function getTasks(): array
    {
        $waitTask = $this->getTask();

        $doneTask = $this->getTask();
        $doneTask->id         = 2;
        $doneTask->name       = 'Done task';
        $doneTask->status     = 'done';
        $doneTask->finishedBy = 'test';

        $tasks = array();
        $tasks[$waitTask->id] = $waitTask;
        $tasks[$doneTask->id] = $doneTask;
        return $tasks;
    }

    /**
     * 获取新手模式版本。
     * Get build.
     *
     * @access public
     * @return object
     */
    public function getBuild(): object
    {
        $build = new stdClass();
        $build->id             = 1;
        $build->system         = 1;
        $build->project        = 2;
        $build->product        = 1;
        $build->branch         = '0';
        $build->execution      = 3;
        $build->builds         = '';
        $build->name           = 'Test build';
        $build->scmPath        = '';
        $build->filePath       = '';
        $build->date           = '';
        $build->stories        = '';
        $build->bugs           = '';
        $build->artifactRepoID = 0;
        $build->builder        = 'test';
        $build->desc           = '';
        $build->createdBy      = '';
        $build->createdDate    = '';
        $build->deleted        = 0;
        $build->executionName  = 'Test execution';
        $build->productName    = 'Test product';
        $build->productType    = 'normal';
        $build->allBugs        = '1';
        $build->allStories     = '1';
        $build->files          = array();
        return $build;
    }

    /**
     * 获取新手模式版本。
     * Get build.
     *
     * @access public
     * @return array
     */
    public function getBuilds(): array
    {
        $build  = $this->getBuild();
        $builds = array();
        $builds[$build->id] = $build;
        return $builds;
    }

    /**
     * 获取新手模式版本键值对。
     * Get build pairs.
     *
     * @access public
     * @return array
     */
    public function getBuildPairs(): array
    {
        return array(1 => 'Test build');
    }

    /**
     * 获取新手模式测试单。
     * Get run.
     *
     * @access public
     * @return object
     */
    public function getRun(): object
    {
        $run = new stdClass();
        $run->id            = 1;
        $run->task          = 1;
        $run->version       = 1;
        $run->assignedTo    = '';
        $run->lastRunner    = '';
        $run->lastRunDate   = '';
        $run->lastRunResult = '';
        $run->status        = 'normal';
        $run->case          = 1;
        return $run;
    }

    /**
     * 获取新手模式用例。
     * Get Cases.
     *
     * @access public
     * @return object
     */
    public function getCase(): object
    {
        $case = new stdClass();
        $case->project         = 2;
        $case->product         = 1;
        $case->case            = 1;
        $case->count           = 1;
        $case->version         = 1;
        $case->order           = 0;
        $case->id              = 1;
        $case->execution       = 3;
        $case->branch          = 0;
        $case->lib             = 0;
        $case->module          = 0;
        $case->path            = 0;
        $case->story           = 0;
        $case->storyVersion    = 1;
        $case->title           = 'Test case';
        $case->precondition    = '';
        $case->keywords        = '';
        $case->pri             = 3;
        $case->type            = 'feature';
        $case->auto            = 'no';
        $case->frame           = '';
        $case->stage           = 'unittest';
        $case->howRun          = '';
        $case->script          = '';
        $case->scriptedBy      = '';
        $case->scriptedDate    = '';
        $case->scriptStatus    = '';
        $case->scriptLocation  = '';
        $case->status          = 'normal';
        $case->subStatus       = '';
        $case->color           = '';
        $case->frequency       = 1;
        $case->openedBy        = '';
        $case->openedDate      = '';
        $case->reviewedBy      = '';
        $case->reviewedDate    = '';
        $case->lastEditedBy    = '';
        $case->lastEditedDate  = '';
        $case->linkCase        = '';
        $case->fromBug         = 0;
        $case->fromCaseID      = 0;
        $case->fromCaseVersion = 1;
        $case->deleted         = 0;
        $case->lastRunner      = '';
        $case->lastRunDate     = '';
        $case->lastRunResult   = 'fail';
        $case->scene           = 0;
        $case->sort            = 0;
        $case->bugs            = 1;
        $case->results         = 1;
        $case->caseFails       = 1;
        $case->stepNumber      = 1;
        $case->needconfirm     = '';
        $case->task            = 1;
        $case->case            = 1;
        $case->assignedTo      = '';
        $case->caseVersion     = '';
        $case->storyTitle      = '';
        $case->caseStatus      = 'normal';
        $case->currentVersion  = 1;

        $step1 = new stdClass();
        $step1->name   = 1;
        $step1->id     = 1;
        $step1->step   = 'Test step1';
        $step1->desc   = 'Test step1';
        $step1->expect = 'Step1 expect';
        $step1->type   = 'step';
        $step1->parent = 0;
        $step1->grade  = 1;

        $step2 = new stdClass();
        $step2->name   = 2;
        $step2->id     = 2;
        $step2->step   = 'Test step2';
        $step2->desc   = 'Test step2';
        $step2->expect = 'Step2 expect';
        $step2->type   = 'step';
        $step2->parent = 0;
        $step2->grade  = 1;
        $case->steps   = array(1 => $step1, 2 => $step2);

        return $case;
    }

    /**
     * 获取新手模式用例。
     * Get Cases.
     *
     * @access public
     * @return array
     */
    public function getCases(): array
    {
        $case  = $this->getCase();
        $cases = array();
        $cases[$case->id] = $case;
        return $cases;
    }

    /**
     * 获取新手模式用例执行结果。
     * Get result.
     *
     * @access public
     * @return object
     */
    public function getResult(): object
    {
        $result = new stdClass();
        $result->id          = 1;
        $result->run         = 0;
        $result->case        = 1;
        $result->version     = 1;
        $result->job         = 0;
        $result->compile     = 0;
        $result->caseResult  = 'fail';
        $result->stepResults = '';
        $result->ZTFResult   = '';
        $result->node        = 0;
        $result->lastRunner  = $this->app->user->account;
        $result->date        = helper::now();
        $result->duration    = 0;
        $result->xml         = '';
        $result->deploy      = 0;
        $result->build       = 0;
        $result->task        = 0;
        $result->nodeName    = '';
        $result->files       = array();
        return $result;
    }

    /**
     * 获取新手模式用例执行结果。
     * Get results.
     *
     * @access public
     * @return array
     */
    public function getResults(): array
    {
        $result = $this->getResult();
        $result->stepResults = array(
            1 => array(
                'id'      => 1,
                'parent'  => 0,
                'case'    => 1,
                'version' => 1,
                'type'    => 'step',
                'desc'    => 'Test step1',
                'expect'  => 'Step1 expect',
                'name'    => 1,
                'grade'   => 1,
                'result'  => 'fail',
                'real'    => '',
                'files'   => array()
            ),
            2 => array(
                'id'      => 2,
                'parent'  => 0,
                'case'    => 1,
                'version' => 1,
                'type'    => 'step',
                'desc'    => 'Test step2',
                'expect'  => 'Step2 expect',
                'name'    => 2,
                'grade'   => 1,
                'result'  => 'pass',
                'real'    => '',
                'files'   => array()
            )
        );
        return array($result->id => $result);
    }

    /**
     * 获取新手模式测试单。
     * Get testtask.
     *
     * @access public
     * @return object
     */
    public function getTesttask(): object
    {
        $testtask = new stdClass();
        $testtask->id               = 1;
        $testtask->project          = 2;
        $testtask->product          = 1;
        $testtask->name             = 'Test testtask';
        $testtask->execution        = 3;
        $testtask->build            = 1;
        $testtask->joint            = 0;
        $testtask->type             = '';
        $testtask->owner            = '';
        $testtask->pri              = 3;
        $testtask->begin            = helper::today();
        $testtask->end              = helper::today();
        $testtask->realBegan        = '';
        $testtask->realFinishedDate = '';
        $testtask->mailto           = '';
        $testtask->desc             = '';
        $testtask->report           = '';
        $testtask->status           = 'wait';
        $testtask->testreport       = 0;
        $testtask->auto             = 'no';
        $testtask->subStatus        = '';
        $testtask->createdBy        = $this->app->user->account;
        $testtask->createdDate      = helper::now();
        $testtask->deleted          = 0;
        $testtask->members          = '';
        $testtask->buildName        = 'Test build';
        $testtask->productName      = 'Test product';
        $testtask->productType      = 'normal';
        $testtask->branch           = '0';
        $testtask->executionName    = 'Test execution';
        $testtask->buildName        = 'Test build';
        $testtask->files            = array();
        return $testtask;
    }

    /**
     * 获取新手模式测试单列表。
     * Get testtasks.
     *
     * @access public
     * @return array
     */
    public function getTesttasks(): array
    {
        $testtask  = $this->getTesttask();
        $testtasks = array();
        $testtasks[$testtask->id] = $testtask;
        return $testtasks;
    }

    /**
     * 获取新手模式测试单键值对。
     * Get testtask pairs.
     *
     * @access public
     * @return array
     */
    public function getTesttaskPairs(): array
    {
        return array(1 => 'Test testtask');
    }

    /**
     * 获取新手模式测试报告。
     * Get testreport.
     *
     * @access public
     * @return object
     */
    public function getTestReport(): object
    {
        $testreport = new stdClass();
        $testreport->id            = 1;
        $testreport->project       = 2;
        $testreport->product       = 1;
        $testreport->execution     = 3;
        $testreport->tasks         = '1';
        $testreport->builds        = '1';
        $testreport->title         = 'Test testreport';
        $testreport->begin         = helper::today();
        $testreport->end           = helper::today();
        $testreport->owner         = $this->app->user->account;
        $testreport->members       = '';
        $testreport->stories       = '';
        $testreport->bugs          = '';
        $testreport->cases         = '';
        $testreport->report        = '';
        $testreport->objectType    = 'execution';
        $testreport->objectID      = 3;
        $testreport->createdBy     = $this->app->user->account;
        $testreport->createdDate   = helper::now();
        $testreport->deleted       = 0;
        $testreport->taskName      = 'Test testtask';
        return $testreport;
    }

    /**
     * 获取新手模式测试报告列表。
     * Get testreports.
     *
     * @access public
     * @return array
     */
    public function getTestReports(): array
    {
        $testreport  = $this->getTestReport();
        $testreports = array();
        $testreports[$testreport->id] = $testreport;
        return $testreports;
    }

    /**
     * 获取新手模式Bug。
     * Get bug.
     *
     * @access public
     * @return object
     */
    public function getBug(): object
    {
        $bug = new stdClass();
        $bug->id            = 1;
        $bug->project       = 2;
        $bug->product       = 1;
        $bug->injection     = 0;
        $bug->identify      = 0;
        $bug->branch        = 0;
        $bug->module        = 0;
        $bug->execution     = 3;
        $bug->plan          = 0;
        $bug->story         = 0;
        $bug->storyVersion  = 0;
        $bug->task          = 0;
        $bug->toTask        = 0;
        $bug->toStory       = 0;
        $bug->title         = 'Test bug-active';
        $bug->keywords      = '';
        $bug->severity      = 3;
        $bug->pri           = 3;
        $bug->type          = 'codeerror';
        $bug->os            = '';
        $bug->browser       = '';
        $bug->hardware      = '';
        $bug->found         = '';
        $bug->steps         = '';
        $bug->status        = 'active';
        $bug->subStatus     = '';
        $bug->color         = '';
        $bug->confirmed     = 0;
        $bug->activatedCount= 0;
        $bug->activatedDate = '';
        $bug->feedbackBy    = '';
        $bug->notifyEmail   = '';
        $bug->mailto        = '';
        $bug->openedBy      = '';
        $bug->openedDate    = '';
        $bug->openedBuild   = '1';
        $bug->assignedTo    = 'Test';
        $bug->assignedDate  = '';
        $bug->deadline      = '';
        $bug->resolvedBy    = '';
        $bug->resolution    = '';
        $bug->resolvedBuild = '';
        $bug->resolvedDate  = '';
        $bug->closedBy      = '';
        $bug->closedDate    = '';
        $bug->duplicateBug  = 0;
        $bug->relatedBug    = '';
        $bug->case          = 0;
        $bug->caseVersion   = 0;
        $bug->feedback      = 0;
        $bug->result        = 0;
        $bug->repo          = 0;
        $bug->mr            = 0;
        $bug->entry         = '';
        $bug->lines         = '';
        $bug->v1            = '';
        $bug->v2            = '';
        $bug->repoType      = '';
        $bug->issueKey      = '';
        $bug->testtask      = 0;
        $bug->lastEditedBy  = '';
        $bug->lastEditedDate= '';
        $bug->deleted       = 0;
        $bug->priOrder      = 3;
        $bug->severityOrder = 3;
        $bug->isParent      = '';
        return $bug;

    }

    /**
     * 获取新手模式Bug列表。
     * Get bugs.
     *
     * @access public
     * @return array
     */
    public function getBugs(): array
    {
        $activeBug = $this->getBug();
        $activeBug->id     = 1;
        $activeBug->status = 'active';
        $activeBug->title  = 'Test bug-active';

        $resolvedBug = $this->getBug();
        $resolvedBug->id     = 2;
        $resolvedBug->status = 'resolved';
        $resolvedBug->title  = 'Test bug-resolved';

        $bugs = array();
        $bugs[$activeBug->id]   = $activeBug;
        $bugs[$resolvedBug->id] = $resolvedBug;
        return $bugs;
    }

    /**
     * 获取新手模式问题。
     * Get issue.
     *
     * @access public
     * @return object
     */
    public function getIssue(): object
    {
        $issue = new stdClass();
        $issue->id               = 1;
        $issue->resolvedBy       = '';
        $issue->project          = 2;
        $issue->execution        = 0;
        $issue->title            = 'Test issue-unconfirmed';
        $issue->desc             = '';
        $issue->pri              = 3;
        $issue->severity         = 1;
        $issue->type             = 'design';
        $issue->activity         = '';
        $issue->deadline         = '';
        $issue->resolution       = '';
        $issue->resolutionComment= '';
        $issue->objectID         = '';
        $issue->resolvedDate     = '';
        $issue->status           = 'unconfirmed';
        $issue->owner            = 'admin';
        $issue->lib              = 0;
        $issue->from             = 0;
        $issue->version          = 1;
        $issue->createdBy        = '';
        $issue->createdDate      = '';
        $issue->editedBy         = '';
        $issue->editedDate       = '';
        $issue->activateBy       = '';
        $issue->activateDate     = '';
        $issue->closedBy         = '';
        $issue->closedDate       = '';
        $issue->assignedTo       = '';
        $issue->assignedBy       = '';
        $issue->assignedDate     = '';
        $issue->approvedDate     = '';
        $issue->deleted          = 0;

        return $issue;
    }

    /**
     * 获取新手模式问题列表。
     * Get issues.
     *
     * @access public
     * @return array
     */
    public function getIssues(): array
    {
        $unconfirmedIssue = $this->getIssue();
        $confirmedIssue   = $this->getIssue();
        $confirmedIssue->id     = 2;
        $confirmedIssue->title  = 'Test issue-confirmed';
        $confirmedIssue->status = 'confirmed';

        $issues = array();
        $issues[$unconfirmedIssue->id] = $unconfirmedIssue;
        $issues[$confirmedIssue->id]   = $confirmedIssue;
        return $issues;
    }

    /**
     * 获取新手模式风险。
     * Get risk.
     *
     * @access public
     * @return object
     */
    public function getRisk(): object
    {
        $risk = new stdClass();
        $risk->id               = 1;
        $risk->project          = 2;
        $risk->execution        = 0;
        $risk->name             = 'Test risk';
        $risk->source           = '';
        $risk->category         = '';
        $risk->strategy         = '';
        $risk->status           = 'active';
        $risk->impact           = 3;
        $risk->probability      = 3;
        $risk->rate             = 9;
        $risk->pri              = 'middle';
        $risk->identifiedDate   = '';
        $risk->prevention       = '';
        $risk->remedy           = '';
        $risk->plannedClosedDate= '';
        $risk->actualClosedDate = '';
        $risk->lib              = 0;
        $risk->from             = 0;
        $risk->version          = 1;
        $risk->createdBy        = '';
        $risk->createdDate      = '';
        $risk->editedBy         = '';
        $risk->editedDate       = '';
        $risk->resolution       = '';
        $risk->resolvedBy       = '';
        $risk->activateBy       = '';
        $risk->activateDate     = '';
        $risk->assignedTo       = '';
        $risk->closedBy         = '';
        $risk->closedDate       = '';
        $risk->cancelBy         = '';
        $risk->cancelDate       = '';
        $risk->cancelReason     = '';
        $risk->hangupBy         = '';
        $risk->hangupDate       = '';
        $risk->trackedBy        = '';
        $risk->trackedDate      = '';
        $risk->assignedDate     = '';
        $risk->approvedDate     = '';
        $risk->deleted          = 0;
        return $risk;
    }

    /**
     * 获取新手模式风险列表。
     * Get risks.
     *
     * @access public
     * @return array
     */
    public function getRisks(): array
    {
        $risk = $this->getRisk();
        return array($risk->id => $risk);
    }

    /**
     * 获取新手模式设计。
     * Get design.
     *
     * @access public
     * @return array
     */
    public function getDesign(): object
    {
        $design = new stdClass();
        $design->id           = 1;
        $design->project      = 2;
        $design->product      = 0;
        $design->commit       = '';
        $design->commitedBy   = '';
        $design->execution    = 0;
        $design->name         = 'Test Design';
        $design->status       = '';
        $design->createdBy    = $this->app->user->account;
        $design->createdDate  = helper::now();
        $design->editedBy     = '';
        $design->editedDate   = '';
        $design->assignedTo   = '';
        $design->assignedBy   = '';
        $design->assignedDate = '';
        $design->deleted      = 0;
        $design->story        = 0;
        $design->desc         = 'Design Description';
        $design->version      = 1;
        $design->type         = 'HLDS';
        $design->files        = array();
        $design->productName  = 'Test product';
        return $design;
    }

    /**
     * 获取新手模式设计列表。
     * Get designs.
     *
     * @access public
     * @return array
     */
    public function getDesigns(): array
    {
        $design = $this->getDesign();
        return array($design->id => $design);
    }

    /**
     * 获取新手模式评审。
     * Get review.
     *
     * @access public
     * @return object
     */
    public function getReview(): object
    {
        $review = new stdClass();
        $review->id              = 1;
        $review->project         = 2;
        $review->title           = 'Test Review';
        $review->object          = 1;
        $review->template        = 0;
        $review->doc             = 0;
        $review->docVersion      = 0;
        $review->status          = 'pass';
        $review->reviewedBy      = '';
        $review->auditedBy       = '';
        $review->createdBy       = '';
        $review->createdDate     = '';
        $review->begin           = '';
        $review->deadline        = '';
        $review->lastReviewedBy  = '';
        $review->lastReviewedDate= '';
        $review->lastAuditedBy   = '';
        $review->lastAuditedDate = '';
        $review->lastEditedBy    = '';
        $review->lastEditedDate  = '';
        $review->result          = '';
        $review->auditResult     = '';
        $review->deleted         = 0;
        $review->version         = '01';
        $review->category        = 'PP';
        $review->product         = 1;
        $review->approval        = 1;
        $review->isPending       = '';
        return $review;
    }

    /**
     * 获取新手模式评审列表。
     * Get reviews.
     *
     * @access public
     * @return array
     */
    public function getReviews(): array
    {
        return array(1 => $this->getReview());
    }

    /**
     * 获取新手模式看板默认区域。
     * Get region pairs.
     *
     * @access public
     * @return array
     */
    public function getRegionPairs(): array
    {
        $this->loadModel('kanban');
        return array(1 => $this->lang->kanbanregion->default);
    }
    /**
     * 获取新手模式看板组。
     * Get groups.
     *
     * @access public
     * @return array
     */
    public function getGroups(): array
    {
        $groups    = array();
        $groups[1] = array();
        foreach(array(1, 2, 3) as $key)
        {
            $group = new stdClass();
            $group->id     = $key;
            $group->kanban = 3;
            $group->region = 1;
            $group->order  = $key;
            $groups[1][$key] = $group;
        }
        return $groups;
    }

    /**
     * 获取新手模式看板泳道。
     * Get lane.
     *
     * @access public
     * @return object
     */
    public function getLaneGroup(): array
    {
        $this->loadModel('kanban');

        $laneGroup = array();
        foreach(array(1 => 'story', 2 => 'task', 3 => 'bug') as $key => $objectType)
        {
            $lane = array();
            $lane['execution']  = 3;
            $lane['region']     = 1;
            $lane['id']         = $key;
            $lane['type']       = $objectType;
            $lane['name']       = $key;
            $lane['title']      = $this->config->kanban->default->{$objectType}->name;
            $lane['color']      = $this->config->kanban->default->{$objectType}->color;
            $lane['order']      = $this->config->kanban->default->{$objectType}->order;
            $laneGroup[$key] = array($lane);
        }
        return $laneGroup;
    }

    /**
     * 获取新手模式看板列。
     * Get columns.
     *
     * @access public
     * @return array
     */
    public function getColumns(): array
    {
        $this->loadModel('kanban');

        $columnID = 1;
        $columns  = array();
        foreach(array(1 => 'story', 2 => 'task', 3 => 'bug') as $key => $objectType)
        {
            $columnList = array();
            foreach($this->lang->kanban->{$objectType . 'Column'} as $type => $name)
            {
                $column = array();
                $column['parent']     = 0;
                $column['region']     = 1;
                $column['group']      = 1;
                $column['color']      = '#333';
                $column['limit']      = -1;
                $column['actionList'] = array('setColumn', 'setWIP', 'deleteColumn');
                $column['id']         = $columnID ++;
                $column['name']       = $column['id'];
                $column['type']       = $type;
                $column['title']      = $name;
                $columnList[] = $column;
            }
            $columns[$key] = $columnList;
        }
        return $columns;
    }

    /**
     * 获取新手模式看板列。
     * Get column.
     *
     * @access public
     * @return object
     */
    public function getColumn(): object
    {
        $column = new stdClass();
        $column->id       = 1;
        $column->parent   = 0;
        $column->type     = 'backlog';
        $column->region   = 1;
        $column->group    = 1;
        $column->name     = 'Backlog';
        $column->color    = '#333';
        $column->limit    = -1;
        $column->order    = 0;
        $column->archived = 0;
        $column->deleted  = 0;
        $column->laneType = 'story';
        return $column;
    }

    /**
     * 获取新手模式看板卡片。
     * Get card.
     *
     * @access public
     * @return array
     */
    public function getCardGroup(): array
    {
        $card = array();
        $card['id']            = 1;
        $card['name']          = 1;
        $card['pri']           = 3;
        $card['color']         = '';
        $card['assignedTo']    = '';
        $card['parent']        = 0;
        $card['progress']      = 0;
        $card['group']         = '';
        $card['region']        = '';
        $card['begin']         = '';
        $card['end']           = '';
        $card['fromID']        = 0;
        $card['fromType']      = '';
        $card['desc']          = '';
        $card['originDesc']    = '';
        $card['delay']         = 0;
        $card['objectStatus']  = '';
        $card['deleted']       = 0;
        $card['date']          = '';
        $card['estimate']      = 0;
        $card['deadline']      = '';
        $card['severity']      = '';
        $card['avatarList']    = array();
        $card['realnames']     = '';
        $card['order']         = 0;
        $card['acl']           = 'open';
        $card['dbPrivs']       = array();

        $storyCard = $card;
        $storyCard['title']    = 'Test story';
        $storyCard['column']   = 1;
        $storyCard['lane']     = 1;
        $storyCard['status']   = 'active';
        $storyCard['cardType'] = 'story';

        $taskCard = $card;
        $taskCard['title']      = 'Test task';
        $taskCard['column']     = 16;
        $taskCard['lane']       = 2;
        $taskCard['left']       = 0;
        $taskCard['estStarted'] = '';
        $taskCard['mode']       = '';
        $taskCard['status']     = 'wait';
        $taskCard['cardType']   = 'task';

        $bugCard = $card;
        $bugCard['title']    = 'Test bug';
        $bugCard['column']   = 23;
        $bugCard['lane']     = 3;
        $bugCard['status']   = 'active';
        $bugCard['cardType'] = 'bug';

        return array(1 => array(1 => array(1 => array($storyCard))), 2 => array(2 => array(16 => array($taskCard))), 3 => array(3 => array(23 => array($bugCard))));
    }

    /**
     * 获取新手模式计划。
     * Get plan.
     *
     * @access public
     * @return object
     */
    public function getPlan(): object
    {
        $plan = new stdClass();
        $plan->id           = 1;
        $plan->product      = 1;
        $plan->branch       = '0';
        $plan->parent       = 0;
        $plan->title        = 'Test plan';
        $plan->status       = 'wait';
        $plan->desc         = '';
        $plan->begin        = helper::today();
        $plan->end          = helper::today();
        $plan->finishedDate = '';
        $plan->closedDate   = '';
        $plan->order        = 0;
        $plan->closedReason = '';
        $plan->createdBy    = $this->app->user->account;
        $plan->createdDate  = helper::now();
        $plan->deleted      = 0;
        $plan->bugs         = 0;
        $plan->hour         = 0;
        $plan->stories      = 0;
        $plan->projects     = array();
        $plan->expired      = '';
        $plan->branchName   = '';
        $plan->isParent     = 0;
        return $plan;
    }

    /**
     * 获取新手模式计划列表。
     * Get plans.
     *
     * @access public
     * @return array
     */
    public function getPlans(): array
    {
        return array(1 => $this->getPlan());
    }

    /**
     * 获取新手模式计划键值对。
     * Get plan pairs.
     *
     * @access public
     * @return array
     */
    public function getPlanPairs(): array
    {
        return array(1 => $this->getPlan()->title);
    }

    /**
     * 获取新手模式系统。
     * Get system.
     *
     * @access public
     * @return object
     */
    public function getSystem(): object
    {
        $system = new stdclass();
        $system->id            = 1;
        $system->name          = 'Test App';
        $system->product       = 1;
        $system->integrated    = '0';
        $system->latestRelease = 0;
        $system->status        = 'active';
        $system->children      = '';
        $system->desc          = '';
        return $system;
    }

    /**
     * 获取新手模式应用键值对。
     * Get system pairs.
     *
     * @access public
     * @return array
     */
    public function getSystemPairs(): array
    {
        return array(1 => $this->getSystem()->name);
    }

    /**
     * 获取新手模式产品应用列表。
     * Get product app list.
     *
     * @access public
     * @return array
     */
    public function getSystemList(): array
    {
        return array(1 => $this->getSystem());
    }

    /**
     * 获取新手模式发布。
     * Get releases.
     *
     * @access public
     * @return object
     */
    public function getRelease(): object
    {
        $release = new stdClass();
        $release->id           = 1;
        $release->system       = 1;
        $release->project      = 0;
        $release->product      = 1;
        $release->branch       = '0';
        $release->shadow       = 1;
        $release->build        = '';
        $release->name         = 'Test release';
        $release->marker       = 0;
        $release->date         = helper::today();
        $release->releasedDate = '';
        $release->stories      = '';
        $release->bugs         = '';
        $release->leftBugs     = '';
        $release->desc         = '';
        $release->mailto       = '';
        $release->notify       = '';
        $release->status       = 'wait';
        $release->subStatus    = '';
        $release->createdBy    = $this->app->user->account;
        $release->createdDate  = helper::now();
        $release->deleted      = 0;
        $release->productName  = 'Test product';
        $release->productType  = 'normal';
        $release->builds       = array();
        $release->branchName   = 'Test branch';
        $release->projectName  = '';
        $release->files        = array();
        $release->releases     = '';

        return $release;
    }

    /**
     * 获取新手模式发布列表。
     * Get releases.
     *
     * @access public
     * @return array
     */
    public function getReleases(): array
    {
        return array(1 => $this->getRelease());
    }

    /**
     * 获取新手模式项目集键值对。
     * Get program pairs.
     *
     * @access public
     * @return array
     */
    public function getProgramPairs(): array
    {
        return array(1 => 'Test program');
    }

    /**
     * 获取新手模式项目集。
     * Get program.
     *
     * @access public
     * @return object
     */
    public function getProgram(): object
    {
        $program = $this->getProject();
        $program->id      = 1;
        $program->name    = 'Test program';
        $program->project = 0;
        $program->type    = 'program';
        $program->parent  = 0;
        $program->path    = ',1,';
        $program->grade   = 1;

        return $program;
    }

    /**
     * 获取新手模式项目集列表。
     * Get programs.
     *
     * @access public
     * @return array
     */
    public function getPrograms(): array
    {
        return array(1 => $this->getProgram());
    }

    /**
     * 获取新手模式分支键值对。
     * Get branch pairs.
     *
     * @access public
     * @return array
     */
    public function getBranchPairs(): array
    {
        $this->loadModel('branch');
        return array(0 => $this->lang->branch->main, 1 => 'Test branch');
    }

    /**
     * 获取新手模式项目关联分支。
     * Get branch by project.
     *
     * @access public
     * @return array
     */
    public function getBranchesByProject(): array
    {
        $branch = new stdClass();
        $branch->project = 2;
        $branch->product = 1;
        $branch->branch  = 0;
        $branch->plan    = 0;
        $branch->roadmap = 0;

        return array(1 => array($branch));
    }

    /**
     * 获取新手模式分支列表。
     * Get branches.
     *
     * @access public
     * @return array
     */
    public function getBranches(): array
    {
        $this->loadModel('branch');

        $main = new stdClass();
        $main->id          = 0;
        $main->product     = 1;
        $main->name        = $this->lang->branch->main;
        $main->default     = 1;
        $main->status      = 'active';
        $main->desc        = $this->lang->branch->defaultBranch;
        $main->createdDate = helper::today();
        $main->closedDate  = '';
        $main->order       = 0;
        $main->deleted     = 0;

        $branch = new stdClass();
        $branch->id          = 1;
        $branch->product     = 1;
        $branch->name        = 'Test branch';
        $branch->default     = 0;
        $branch->status      = 'active';
        $branch->desc        = '';
        $branch->createdDate = helper::today();
        $branch->closedDate  = '';
        $branch->order       = 1;
        $branch->deleted     = 0;

        return array($main, $branch);
    }

    /**
     * 获取新手模式反馈。
     * Get feedback.
     *
     * @access public
     * @return object
     */
    public function getFeedback(): object
    {
        $feedback = new stdClass();
        $feedback->id             = 1;
        $feedback->product        = 1;
        $feedback->module         = 0;
        $feedback->title          = 'Test feedback';
        $feedback->type           = '';
        $feedback->solution       = '';
        $feedback->desc           = '';
        $feedback->pri            = 3;
        $feedback->status         = 'noreview';
        $feedback->subStatus      = '';
        $feedback->public         = 1;
        $feedback->notify         = 1;
        $feedback->notifyEmail    = '';
        $feedback->source         = '';
        $feedback->likes          = '';
        $feedback->result         = 0;
        $feedback->faq            = 0;
        $feedback->openedBy       = $this->app->user->account;
        $feedback->openedDate     = helper::now();
        $feedback->reviewedBy     = '';
        $feedback->reviewedDate   = '';
        $feedback->processedBy    = '';
        $feedback->processedDate  = '';
        $feedback->closedBy       = '';
        $feedback->closedDate     = '';
        $feedback->closedReason   = '';
        $feedback->editedBy       = '';
        $feedback->editedDate     = '';
        $feedback->assignedTo     = '';
        $feedback->assignedDate   = '';
        $feedback->activatedBy    = '';
        $feedback->activatedDate  = '';
        $feedback->feedbackBy     = '';
        $feedback->repeatFeedback = 0;
        $feedback->mailto         = '';
        $feedback->keywords       = '';
        $feedback->deleted        = 0;
        $feedback->dept           = 0;

        return $feedback;
    }

    /**
     * 获取新手模式反馈列表。
     * Get feedbacks.
     *
     * @access public
     * @return array
     */
    public function getFeedbacks(): array
    {
        $waitFeedback = $this->getFeedback();
        $waitFeedback->id     = 1;
        $waitFeedback->title  = 'Wait feedback';
        $waitFeedback->status = 'wait';

        $noReviewFeedback = $this->getFeedback();
        $noReviewFeedback->id     = 2;
        $noReviewFeedback->title  = 'Not review feedback';
        $noReviewFeedback->status = 'noreview';

        return array($waitFeedback->id => $waitFeedback, $noReviewFeedback->id => $noReviewFeedback);
    }

    /**
     * 获取新手模式团队空间或我的空间。
     * Get team spaces.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getSubSpaces($type = 'custom'): array
    {
        if($type == 'custom') return array(1 => 'Test Team Space');
        if($type == 'mine')   return array(1 => 'Test My Space');
        return array();
    }

    /**
     * 获取新手模式团队空间。
     * Get team spaces.
     *
     * @access public
     * @return array
     */
    public function getDocTemplateSpaces(): array
    {
        return array(1 => 'Doc Template Space');
    }

    /**
     * 获取新手模式文档库。
     * Get doc lib.
     *
     * @access public
     * @return object
     */
    public function getDocLib(): object
    {
        $docLib = new stdClass();
        $docLib->id         = 2;
        $docLib->type       = 'custom';
        $docLib->vision     = 'rnd';
        $docLib->parent     = 1;
        $docLib->product    = 0;
        $docLib->project    = 0;
        $docLib->execution  = 0;
        $docLib->name       = 'Test Doc Lib';
        $docLib->baseUrl    = '';
        $docLib->acl        = 'open';
        $docLib->groups     = '';
        $docLib->users      = '';
        $docLib->main       = 0;
        $docLib->collector  = '';
        $docLib->desc       = '';
        $docLib->order      = 0;
        $docLib->addedBy    = $this->app->user->account;
        $docLib->addedDate  = helper::now();
        $docLib->deleted    = 0;
        $docLib->allCount   = 2;
        return $docLib;
    }

    /**
     * 获取新手模式文档库。
     * Get doc lib.
     *
     * @access public
     * @return array
     */
    public function getDocLibs(): array
    {
        $docLib = $this->getDocLib();
        return array($docLib->id => $docLib);
    }

    /**
     * 获取新手模式文档库树。
     * Get lib tree.
     *
     * @access public
     * @return array
     */
    public function getLibTree(): array
    {
        $docLib = new stdClass();
        $docLib->id         = 2;
        $docLib->type       = 'docLib';
        $docLib->name       = 'Test Doc Lib';
        $docLib->parent     = 1;
        $docLib->order      = 0;
        $docLib->main       = 0;
        $docLib->objectType = 'custom';
        $docLib->objectID   = 1;
        $docLib->addedBy    = $this->app->user->account;
        $docLib->active     = 1;
        $docLib->children   = array();
        return array($docLib);
    }

    /**
     * 获取新手模式文档。
     * Get doc.
     *
     * @access public
     * @return object
     */
    public function getDoc(): object
    {
        $doc = new stdClass();
        $doc->id             = 1;
        $doc->vision         = 'rnd';
        $doc->project        = 0;
        $doc->product        = 0;
        $doc->execution      = 0;
        $doc->lib            = 2;
        $doc->template       = '';
        $doc->templateType   = '';
        $doc->chapterType    = '';
        $doc->module         = 0;
        $doc->title          = 'Test Doc';
        $doc->keywords       = '';
        $doc->type           = 'text';
        $doc->status         = 'normal';
        $doc->parent         = 0;
        $doc->path           = '';
        $doc->grade          = 0;
        $doc->order          = 10;
        $doc->views          = 1;
        $doc->assetLib       = 0;
        $doc->assetLibType   = '';
        $doc->from           = 0;
        $doc->fromVersion    = 1;
        $doc->draft          = "This is the description of the document.";
        $doc->collects       = 0;
        $doc->addedBy        = $this->app->user->account;
        $doc->addedDate      = helper::now();
        $doc->assignedTo     = '';
        $doc->assignedDate   = '';
        $doc->approvedDate   = '';
        $doc->editedBy       = '';
        $doc->editedDate     = '';
        $doc->editingDate    = array();
        $doc->editedList     = ",{$this->app->user->account}";
        $doc->mailto         = '';
        $doc->acl            = 'open';
        $doc->groups         = '';
        $doc->users          = '';
        $doc->version        = 2;
        $doc->deleted        = 0;
        $doc->collector      = '';
        $doc->releasedDate   = '';
        $doc->releasedBy     = $this->app->user->account;
        $doc->digest         = helper::now();
        $doc->content        = "This is the description of the document.";
        $doc->contentType    = 'html';
        $doc->contentVersion = 2;
        $doc->files          = array();
        $doc->moduleName     = '';
        $doc->executionName  = '';
        $doc->productName    = '';
        return $doc;
    }

    /**
     * 获取新手模式文档列表。
     * Get docs.
     *
     * @access public
     * @return array
     */
    public function getDocs(): array
    {
        $doc = $this->getDoc();
        return array($doc->id => $doc);
    }

    /**
     * 获取新手模式需求池。
     * Get demandpool.
     *
     * @access public
     * @return object
     */
    public function getDemandpool(): object
    {
        $demandpool = new stdClass();
        $demandpool->id          = 1;
        $demandpool->name        = 'Test demandpool';
        $demandpool->desc        = '';
        $demandpool->status      = 'normal';
        $demandpool->products    = '1';
        $demandpool->createdBy   = $this->app->user->account;
        $demandpool->createdDate = helper::today();
        $demandpool->owner       = $this->app->user->account;
        $demandpool->reviewer    = ",{$this->app->user->account},test";
        $demandpool->acl         = 'open';
        $demandpool->deleted     = 0;
        $demandpool->files       = array();
        return $demandpool;
    }

    /**
     * 获取新手模式需求池需求。
     * Get demand.
     *
     * @access public
     * @return object
     */
    public function getDemand(): object
    {
        $demand = new stdClass();
        $demand->id              = 1;
        $demand->pool            = 1;
        $demand->product         = '';
        $demand->parent          = 0;
        $demand->pri             = 3;
        $demand->title           = 'Test Demand';
        $demand->assignedTo      = '';
        $demand->status          = 'active';
        $demand->childDemands    = '';
        $demand->module          = 0;
        $demand->category        = 'feature';
        $demand->source          = '';
        $demand->sourceNote      = '';
        $demand->feedbackedBy    = '';
        $demand->email           = '';
        $demand->assignedDate    = '';
        $demand->reviewedBy      = '';
        $demand->reviewedDate    = '';
        $demand->stage           = 'wait';
        $demand->duration        = '';
        $demand->BSA             = '';
        $demand->story           = 0;
        $demand->roadmap         = 0;
        $demand->createdBy       = $this->app->user->account;
        $demand->createdDate     = helper::now();
        $demand->mailto          = '';
        $demand->duplicateDemand = '';
        $demand->version         = 1;
        $demand->parentVersion   = 0;
        $demand->vision          = 'or';
        $demand->color           = '';
        $demand->changedBy       = '';
        $demand->changedDate     = '';
        $demand->closedBy        = '';
        $demand->closedDate      = '';
        $demand->closedReason    = '';
        $demand->submitedBy      = '';
        $demand->lastEditedBy    = '';
        $demand->lastEditedDate  = '';
        $demand->activatedDate   = '';
        $demand->distributedBy   = '';
        $demand->distributedDate = '';
        $demand->feedback        = 0;
        $demand->keywords        = '';
        $demand->deleted         = 0;
        $demand->storyType       = 'demand';
        return $demand;
    }

    /**
     * 获取新手模式需求池需求列表。
     * Get demands.
     *
     * @access public
     * @return array
     */
    public function getDemands(): array
    {
        $activeDemand = $this->getDemand();

        $draftDemand = $this->getDemand();
        $draftDemand->id        = 2;
        $draftDemand->status    = 'reviewing';
        $draftDemand->title     = 'Test Reviewing Demand';
        $draftDemand->notReview = array($this->app->user->account);

        return array($activeDemand->id => $activeDemand, $draftDemand->id => $draftDemand);
    }

    /**
     * 获取新手模式调研任务列表。
     * Get research stage stats.
     *
     * @access public
     * @return array
     */
    public function getResearchStageStats(): array
    {
        $stage = $this->getExecution();
        $stage->id            = 'sid_2';
        $stage->parent        = 'sid_1';
        $stage->name          = 'Test research stage';
        $stage->path          = ',1,2,';
        $stage->project       = 1;
        $stage->model         = '';
        $stage->type          = 'stage';
        $stage->attribute     = 'research';
        $stage->canCreateTask = true;

        $waitTask = $this->getTask();
        $waitTask->id        = 1;
        $waitTask->name      = 'Wait Task';
        $waitTask->type      = 'research';
        $waitTask->project   = 1;
        $waitTask->execution = 2;
        $waitTask->parent    = 'sid_2';
        $waitTask->rawParent = 0;
        $waitTask->isParent  = 1;

        $doneTask = $this->getTask();
        $doneTask->id         = 2;
        $doneTask->name       = 'Done task';
        $doneTask->status     = 'done';
        $doneTask->finishedBy = 'test';
        $doneTask->type       = 'research';
        $doneTask->project    = 1;
        $doneTask->execution  = 2;
        $doneTask->parent     = 'sid_2';
        $doneTask->rawParent  = 0;
        $doneTask->isParent   = 1;

        return array($stage, $waitTask, $doneTask);
    }

    /**
     * 获取新手模式市场。
     * Get market.
     *
     * @access public
     * @return object
     */
    public function getMarket(): object
    {
        $market = new stdClass();
        $market->id             = 1;
        $market->name           = 'Test Market';
        $market->industry       = '';
        $market->scale          = 0.00;
        $market->maturity       = '';
        $market->speed          = '';
        $market->competition    = '';
        $market->strategy       = '';
        $market->ppm            = '';
        $market->desc           = '';
        $market->openedBy       = $this->app->user->account;
        $market->openedDate     = helper::now();
        $market->lastEditedBy   = '';
        $market->lastEditedDate = '';
        $market->deleted        = 0;
        return $market;
    }

    /**
     * 获取新手模式Charter立项。
     * Get charter.
     *
     * @access public
     * @return object
     */
    public function getCharter(): object
    {
        $charter = new stdClass();
        $charter->id              = 1;
        $charter->name            = 'Test charter';
        $charter->level           = 3;
        $charter->category        = 'IPD';
        $charter->market          = 'domestic';
        $charter->check           = 0;
        $charter->appliedBy       = $this->app->user->account;
        $charter->appliedDate     = helper::now();
        $charter->budget          = '';
        $charter->budgetUnit      = 'CNY';
        $charter->product         = ',1,';
        $charter->roadmap         = '';
        $charter->spec            = '';
        $charter->status          = 'wait';
        $charter->createdBy       = $this->app->user->account;
        $charter->createdDate     = helper::now();
        $charter->charterFiles    = '';
        $charter->closedBy        = '';
        $charter->closedDate      = '';
        $charter->closedReason    = '';
        $charter->activatedBy     = '';
        $charter->activatedDate   = '';
        $charter->reviewedBy      = '';
        $charter->reviewedResult  = '';
        $charter->reviewedDate    = '';
        $charter->meetingDate     = '';
        $charter->meetingLocation = '';
        $charter->meetingMinutes  = '';
        $charter->deleted         = 0;
        return $charter;
    }

    /**
     * 获取新手模式Charter立项列表。
     * Get charter list.
     *
     * @access public
     * @return array
     */
    public function getCharters(): array
    {
        $charter = $this->getCharter();
        return array($charter->id => $charter);
    }

    /**
     * 获取新手模式代码库键值对。
     * Get repo pairs.
     *
     * @access public
     * @return array
     */
    public function getRepoPairs(): array
    {
        return array(1 => '[git] Test repo');
    }

    /**
     * 获取新手模式代码库。
     * Get repo.
     *
     * @access public
     * @return object
     */
    public function getRepo(): object
    {
        $repo = new stdClass();
        $repo->id                 = 1;
        $repo->product            = 1;
        $repo->projects           = '1';
        $repo->name               = 'Test repo';
        $repo->path               = '';
        $repo->prefix             = '';
        $repo->encoding           = 'utf-8';
        $repo->SCM                = 'Git';
        $repo->client             = '/usr/bin/git';
        $repo->serviceHost        = 0;
        $repo->serviceProject     = '';
        $repo->commits            = 1;
        $repo->account            = '';
        $repo->password           = '';
        $repo->encrypt            = 'base64';
        $repo->synced             = 0;
        $repo->lastSync           = '';
        $repo->lastCommit         = '';
        $repo->desc               = '';
        $repo->extra              = '';
        $repo->preMerge           = 0;
        $repo->job                = 0;
        $repo->fileServerUrl      = '';
        $repo->fileServerAccount  = '';
        $repo->fileServerPassword = '';
        $repo->deleted            = 0;
        $repo->codePath           = '';
        return $repo;
    }

    /**
     * 获取新手模式提交记录。
     * Get git commits.
     *
     * @access public
     * @return array
     */
    public function getCommits(): array
    {
        $commit = new stdClass();
        $commit->id              = 1;
        $commit->repo            = 1;
        $commit->revision        = 'bedeaaf39ef7084b9a455b9d9dba71e2db357201';
        $commit->commit          = 1;
        $commit->comment         = 'Git comment.';
        $commit->committer       = $this->app->user->account;
        $commit->time            = helper::now();
        $commit->originalComment = 'Git comment.';
        return array($commit->revision => $commit);
    }
}
