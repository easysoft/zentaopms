<?php
/**
 * The model file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class blockModel extends model
{
    /**
     * Save params
     *
     * @param  int    $id
     * @param  string $type
     * @param  string $appName
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function save($id, $source, $type, $module = 'my')
    {
        $block = $id ? $this->getByID($id) : null;
        $data = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->stripTags('html', $this->config->allowedTags)
            ->setIF($id, 'id', $id)
            ->add('order', $block ? $block->order : ($this->getLastKey($module) + 1))
            ->add('module', $module)
            ->add('hidden', 0)
            ->setDefault('grid', '4')
            ->setDefault('source', $source)
            ->setDefault('block', $type)
            ->setDefault('vision', $this->config->vision)
            ->setDefault('params', array())
            ->remove('uid,actionLink,modules,moduleBlock')
            ->get();

        if($this->post->moduleBlock)
        {
            $data->source = $this->post->modules;
            $data->block  = $this->post->moduleBlock;
        }
        else
        {
            $data->source = '';
            $data->block  = $this->post->modules;
        }

        if($block) $data->height = $block->height;
        if($type == 'html')
        {
            $uid  = $this->post->uid;
            $data = $this->loadModel('file')->processImgURL($data, 'html', $uid);
            $data->params['html'] = $data->html;
            unset($data->html);
            unset($_SESSION['album'][$uid]);
        }

        $data->params = helper::jsonEncode($data->params);
        $this->dao->replace(TABLE_BLOCK)->data($data)->exec();
        if(!dao::isError()) $this->loadModel('score')->create('block', 'set');
    }

    /**
     * Get block by ID.
     *
     * @param  int    $blockID
     * @access public
     * @return object
     */
    public function getByID($blockID)
    {
        $block = $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('id')->eq($blockID)
            ->fetch();
        if(empty($block)) return false;

        $block->params = json_decode($block->params);
        if(empty($block->params)) $block->params = new stdclass();
        if($block->block == 'html') $block->params->html = $this->loadModel('file')->setImgSize($block->params->html);
        return $block;
    }

    /**
     * Get saved block config.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getBlock($id)
    {
        $block = $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('`id`')->eq($id)
            ->andWhere('account')->eq($this->app->user->account)
            ->fetch();
        if(empty($block)) return false;

        $block->params = json_decode($block->params);
        if(empty($block->params)) $block->params = new stdclass();
        return $block;
    }

    /**
     * Get last key.
     *
     * @param  string $appName
     * @access public
     * @return int
     */
    public function getLastKey($module = 'my')
    {
        $order = $this->dao->select('`order`')->from(TABLE_BLOCK)
            ->where('module')->eq($module)
            ->andWhere('account')->eq($this->app->user->account)
            ->orderBy('order desc')
            ->limit(1)
            ->fetch('order');
        return $order ? $order : 0;
    }

    /**
     * Get block list for account.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function getBlockList($module = 'my', $type = '')
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)->where('account')->eq($this->app->user->account)
            ->andWhere('module')->eq($module)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('hidden')->eq(0)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->orderBy('`order`')
            ->fetchAll('id');
    }

    /**
     * Get hidden blocks
     *
     * @access public
     * @return array
     */
    public function getHiddenBlocks($module = 'my')
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)->where('account')->eq($this->app->user->account)
            ->andWhere('module')->eq($module)
            ->andWhere('hidden')->eq(1)
            ->orderBy('`order`')
            ->fetchAll('order');
    }

    /**
     * Get data of welcome block.
     *
     * @access public
     * @return array
     */
    public function getWelcomeBlockData()
    {
        $today = date('Y-m-d');

        $data = array();

        /* Story. */
        $data['stories'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->fetch('count');

        /* Task. */
        $tasks = $this->dao->select("t1.id,t1.status,t1.deadline")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on("t1.execution = t3.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on("t4.task = t1.id and t4.account = '{$this->app->user->account}'")
            ->where("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t4.`account` = '{$this->app->user->account}' and t1.status != 'closed' and t4.status != 'done') )")
            ->andWhere('(t2.status')->ne('suspended')
            ->orWhere('t3.status')->ne('suspended')
            ->markRight(1)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t1.status')->notin('closed,cancel')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t3.vision')->eq($this->config->vision)->fi()
            ->fetchAll();

        $totalTasks = array();
        $delayTasks = array();
        $doneTasks  = array();
        foreach($tasks as $task)
        {
            if($task->status == 'done') $doneTasks[$task->id] = true;
            if(in_array($task->status, array('wait', 'doing')) && !helper::isZeroDate($task->deadline) && $task->deadline < $today) $delayTasks[$task->id] = true;
            $totalTasks[$task->id] = true;
        }
        $data['tasks']     = count($totalTasks);
        $data['doneTasks'] = count($doneTasks);
        $data['delayTask'] = count($delayTasks);

        /* Bug. */
        $bugs = $this->dao->select('t1.id,t1.status,t1.deadline')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on("t1.product = t2.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->ne('closed')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();

        $totalBugs = array();
        $delayBugs = array();
        foreach($bugs as $bug)
        {
            if($bug->status == 'active' && !helper::isZeroDate($bug->deadline) && $bug->deadline < $today) $delayBugs[$bug->id] = true;
            $totalBugs[$bug->id] = true;
        }
        $data['bugs']     = count($totalBugs);
        $data['delayBug'] = count($delayBugs);

        return $data;
    }

    /**
     * Init block when account use first.
     *
     * @param  string    $module project|product|execution|qa|my
     * @param  string    $type   scrum|waterfall|kanban
     * @access public
     * @return bool
     */
    public function initBlock($module, $type = '')
    {
        if(empty($module)) return;

        $flow    = isset($this->config->global->flow) ? $this->config->global->flow : 'full';
        $account = $this->app->user->account;
        $vision  = $this->config->vision;

        if($module == 'project')
        {
            $blocks = $this->lang->block->default[$type]['project'];

            /* Mark project block has init. */
            $this->loadModel('setting')->setItem("$account.$module.{$type}common.blockInited@$vision", '1');
        }
        else
        {
            $blocks = $module == 'my' ? $this->lang->block->default[$flow][$module] : $this->lang->block->default[$module];

            /* Mark this app has init. */
            $this->loadModel('setting')->setItem("$account.$module.common.blockInited@$vision", '1');
        }

        $this->loadModel('setting')->setItem("$account.$module.block.initVersion", $this->config->block->version);
        foreach($blocks as $index => $block)
        {
            $block['order']   = $index;
            $block['module']  = $module;
            $block['type']    = $type;
            $block['account'] = $account;
            $block['params']  = isset($block['params']) ? helper::jsonEncode($block['params']) : '';
            $block['vision']  = $this->config->vision;
            if(!isset($block['source'])) $block['source'] = $module;

            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
        }
        return !dao::isError();
    }

    /**
     * Get block list.
     *
     * @param  string $module
     * @param  string $dashboard
     * @param  object $model
     *
     * @access public
     * @return string
     */
    public function getAvailableBlocks($module = '', $dashboard = '', $model = '')
    {
        $blocks = $this->lang->block->availableBlocks;
        if($dashboard == 'project')
        {
            $blocks = $this->lang->block->modules[$model]['index']->availableBlocks;
        }
        else
        {
            if($module and isset($this->lang->block->modules[$module])) $blocks = $this->lang->block->modules[$module]->availableBlocks;
        }

        if(isset($this->config->block->closed))
        {
            foreach($blocks as $blockKey => $blockName)
            {
                if(strpos(",{$this->config->block->closed},", ",{$module}|{$blockKey},") !== false) unset($blocks->$blockKey);
            }
        }
        return json_encode($blocks);
    }

    /**
     * Get list params for product|project|todo
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getListParams($module = '')
    {
        if($module == 'product')   return $this->getProductParams();
        if($module == 'project')   return $this->getProjectParams();
        if($module == 'execution') return $this->getExecutionParams();

        $params = new stdclass();
        $params = $this->appendCountParams($params);
        return json_encode($params);
    }

    /**
     * Get todo params.
     *
     * @access public
     * @return json
     */
    public function getTodoParams($module = '')
    {
        return $this->getListParams($module);
    }

    /**
     * Get task params.
     *
     * @access public
     * @return string
     */
    public function getTaskParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->task;
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'id_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->task;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get Bug Params.
     *
     * @access public
     * @return json
     */
    public function getBugParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->bug;
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'id_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->bug;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get case params.
     *
     * @access public
     * @return json
     */
    public function getCaseParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->case;
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'id_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->case;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get testtask params.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getTesttaskParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->testtask;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get story params.
     *
     * @access public
     * @return json
     */
    public function getStoryParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->story;
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'id_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->story;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get plan params.
     *
     * @access public
     * @return json
     */
    public function getPlanParams()
    {
        $params = $this->appendCountParams();
        return json_encode($params);
    }

    /**
     * Get Release params.
     *
     * @access public
     * @return json
     */
    public function getReleaseParams()
    {
        $params = $this->appendCountParams();
        return json_encode($params);
    }

    /**
     * Get project params.
     *
     * @access public
     * @return json
     */
    public function getProjectParams()
    {
        $this->app->loadLang('project');
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->project->featureBar['browse'];
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->product;
        $params->orderBy['control'] = 'select';

        return json_encode($this->appendCountParams($params));
    }

    /**
     * Get project team params.
     *
     * @access public
     * @return json
     */
    public function getProjectTeamParams()
    {
        $this->app->loadLang('project');
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->project->featureBar['browse'];
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->project;
        $params->orderBy['control'] = 'select';

        return json_encode($this->appendCountParams($params));
    }
    /**
     * Get Build params.
     *
     * @access public
     * @return json
     */
    public function getBuildParams()
    {
        $params = $this->appendCountParams();
        return json_encode($params);
    }

    /**
     * Get product params.
     *
     * @access public
     * @return json
     */
    public function getProductParams()
    {
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->product;
        $params->type['control'] = 'select';

        return json_encode($this->appendCountParams($params));
    }

    /**
     * Get statistic params.
     *
     * @param  string $module product|project|execution|qa
     * @access public
     * @return string
     */
    public function getStatisticParams($module = 'product')
    {
        if($module == 'product')   return $this->getProductStatisticParams();
        if($module == 'project')   return $this->getProjectStatisticParams();
        if($module == 'execution') return $this->getExecutionStatisticParams();
        if($module == 'qa')        return $this->getQaStatisticParams();
        if($module == 'doc')       return $this->getDocStatisticParams();

        $params = new stdclass();
        $params = $this->appendCountParams($params);
        return json_encode($params);
    }

    /**
     * Get product statistic params.
     *
     * @access public
     * @return void
     */
    public function getProductStatisticParams()
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->product;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get project statistic params.
     *
     * @access public
     * @return void
     */
    public function getProjectStatisticParams()
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->project;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get execution statistic params.
     *
     * @access public
     * @return void
     */
    public function getExecutionStatisticParams()
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->execution;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get qa statistic params.
     *
     * @access public
     * @return void
     */
    public function getQaStatisticParams()
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->product;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get document statistic params.
     *
     * @access public
     * @return bool
     */
    public function getDocStatisticParams()
    {
        return false;
    }

    /**
     * Get recent project pararms.
     *
     * @access public
     * @return bool
     */
    public function getRecentProjectParams()
    {
        return false;
    }

    /**
     * Get product overview pararms.
     *
     * @access public
     * @return bool
     */
    public function getOverviewParams()
    {
        return false;
    }

    /**
     * Get waterfall project report pararms.
     *
     * @access public
     * @return bool
     */
    public function getWaterfallReportParams()
    {
        return false;
    }

    /**
     * Get waterfall general report params.
     *
     * @access public
     * @return bool
     */
    public function getWaterfallGeneralReportParams()
    {
        return false;
    }

    /**
     * Get project estimate pararms.
     *
     * @access public
     * @return bool
     */
    public function getWaterfallEstimateParams()
    {
        return false;
    }

    /**
     * Get project gantt pararms.
     *
     * @access public
     * @return string
     */
    public function getWaterfallGanttParams()
    {
        return false;
    }

    /**
     * Get project progress pararms.
     *
     * @access public
     * @return string
     */
    public function getWaterfallProgressParams()
    {
        return false;
    }

    /**
     * Get waterfall issue params.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getWaterfallIssueParams($module = '')
    {
        $this->app->loadLang('issue');

        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->issue->featureBar['browse'];
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->product;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get waterfall risk params.
     *
     * @param  string $module▫
     * @access public
     * @return void
     */
    public function getWaterfallRiskParams($module = '')
    {
        $this->app->loadLang('risk');
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->risk->featureBar['browse'];
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->product;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get scrum issue params.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getScrumIssueParams($module = '')
    {
        $this->app->loadLang('issue');

        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->issue->typeList;
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->product;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get scrum risk params.
     *
     * @param  string $module▫
     * @access public
     * @return void
     */
    public function getScrumRiskParams($module = '')
    {
        $this->app->loadLang('risk');
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->risk->featureBar['browse'];
        $params->type['control'] = 'select';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['options'] = $this->lang->block->orderByList->product;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get execution params.
     *
     * @access public
     * @return json
     */
    public function getExecutionParams()
    {
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->execution;
        $params->type['control'] = 'select';

        return json_encode($this->appendCountParams($params));
    }

    /**
     * Get assign to me params.
     *
     * @access public
     * @return json
     */
    public function getAssignToMeParams()
    {
        $params = new stdclass();
        $params->todoCount['name']    = $this->lang->block->todoCount;
        $params->todoCount['default'] = 20;
        $params->todoCount['control'] = 'input';

        $params->reviewCount['name']    = $this->lang->block->reviewCount;
        $params->reviewCount['default'] = 20;
        $params->reviewCount['control'] = 'input';

        $params->taskCount['name']    = $this->lang->block->taskCount;
        $params->taskCount['default'] = 20;
        $params->taskCount['control'] = 'input';

        $params->bugCount['name']    = $this->lang->block->bugCount;
        $params->bugCount['default'] = 20;
        $params->bugCount['control'] = 'input';

        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            if(helper::hasFeature('risk'))
            {
                $params->riskCount['name']    = $this->lang->block->riskCount;
                $params->riskCount['default'] = 20;
                $params->riskCount['control'] = 'input';
            }

            if(helper::hasFeature('issue'))
            {
                $params->issueCount['name']    = $this->lang->block->issueCount;
                $params->issueCount['default'] = 20;
                $params->issueCount['control'] = 'input';
            }

            if(helper::hasFeature('meeting'))
            {
                $params->meetingCount['name']    = $this->lang->block->meetingCount;
                $params->meetingCount['default'] = 20;
                $params->meetingCount['control'] = 'input';
            }

            $params->feedbackCount['name']    = $this->lang->block->feedbackCount;
            $params->feedbackCount['default'] = 20;
            $params->feedbackCount['control'] = 'input';
        }

        $params->storyCount['name']    = $this->lang->block->storyCount;
        $params->storyCount['default'] = 20;
        $params->storyCount['control'] = 'input';

        return json_encode($params);
    }

    /**
     * Get closed block pairs.
     *
     * @param  string $closedBlock
     * @access public
     * @return array
     */
    public function getClosedBlockPairs($closedBlock)
    {
        $blockPairs = array();
        if(empty($closedBlock)) return $blockPairs;

        foreach(explode(',', $closedBlock) as $block)
        {
            $block = trim($block);
            if(empty($block)) continue;

            list($moduleName, $blockKey) = explode('|', $block);
            if(empty($moduleName))
            {
                if(isset($this->lang->block->$blockKey)) $blockPairs[$block] = $this->lang->block->$blockKey;
                if($blockKey == 'html')    $blockPairs[$block] = 'HTML';
                if($blockKey == 'guide')   $blockPairs[$block] = $this->lang->block->guide;
                if($blockKey == 'dynamic') $blockPairs[$block] = $this->lang->block->dynamic;
                if($blockKey == 'welcome') $blockPairs[$block] = $this->lang->block->welcome;
            }
            else
            {
                $blockName = $blockKey;
                if(isset($this->lang->block->modules[$moduleName]->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules[$moduleName]->availableBlocks->$blockKey;
                if(isset($this->lang->block->availableBlocks->$blockKey)) $blockName = $this->lang->block->availableBlocks->$blockKey;
                if(isset($this->lang->block->modules['scrum']['index']->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules['scrum']['index']->availableBlocks->$blockKey;
                if(isset($this->lang->block->modules['waterfall']['index']->availableBlocks->$blockKey)) $blockName = $this->lang->block->modules['waterfall']['index']->availableBlocks->$blockKey;

                $blockPairs[$block]  = isset($this->lang->block->moduleList[$moduleName]) ? "{$this->lang->block->moduleList[$moduleName]}|" : '';
                $blockPairs[$block] .= $blockName;
            }
        }

        return $blockPairs;
    }

    /**
     * Append count params.
     *
     * @param  object $params
     * @access public
     * @return object
     */
    public function appendCountParams($params = '')
    {
        if(empty($params)) $params = new stdclass();

        $params->count = array();
        $params->count['name']    = $this->lang->block->count;
        $params->count['default'] = 20;
        $params->count['control'] = 'input';

        return $params;
    }

    /**
     * Check whether long block.
     *
     * @param  object    $block
     * @access public
     * @return book
     */
    public function isLongBlock($block)
    {
        if(empty($block)) return true;
        return $block->grid >= 6;
    }

    /**
     * Check API for ranzhi
     *
     * @param  string    $hash
     * @access public
     * @return bool
     */
    public function checkAPI($hash)
    {
        if(empty($hash)) return false;

        $key = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('sso')
            ->andWhere('`key`')->eq('key')
            ->fetch('value');

        return $key == $hash;
    }

    /**
     * Get testtask params.
     *
     * @access public
     * @return string
     */
    public function getScrumTestParams()
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->testtask;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get scrum project list params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getScrumListParams($module = '')
    {
        $params = $this->appendCountParams();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->scrum;
        $params->type['control'] = 'select';

        return json_encode($params);
    }

    /**
     * Get scrum overall list params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getScrumOverviewParams($module = '')
    {
        return false;
    }

    /**
     * Get scrum roadmap list params.
     *
     * @access public
     * @return bool
     */
    public function getScrumRoadMapParams()
    {
        return false;
    }

    /**
     * Get scrum product list params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getScrumProductParams($module = '')
    {
        $params = $this->appendCountParams();

        return json_encode($params);
    }


    /**
     * Get project dynamic params.
     *
     * @access public
     * @return string
     */
    public function getProjectDynamicParams()
    {
        $params = $this->appendCountParams();

        return json_encode($params);
    }

    /**
     * Get scrum project list params.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getSprintParams($module = '')
    {
        return false;
    }

    /**
     * Get document dynamic params.
     *
     * @access public
     * @return bool
     */
    public function getDocDynamicParams()
    {
        return false;
    }

    /**
     * Get my collection params.
     *
     * @access public
     * @return bool
     */
    public function getDocMyCollectionParams()
    {
        return false;
    }

    /**
     * Get recent update params.
     *
     * @access public
     * @return bool
     */
    public function getDocRecentUpdateParams()
    {
        return false;
    }

    /**
     * Get view list params.
     *
     * @access public
     * @return bool
     */
    public function getDocViewlistParams()
    {
        return false;
    }

    /**
     * Get product document params.
     *
     * @access public
     * @return bool
     */
    public function getProductDocParams()
    {
        $params = $this->appendCountParams();
        return json_encode($params);
    }

    /**
     * Get collect list params.
     *
     * @access public
     * @return bool
     */
    public function getDocCollectListParams()
    {
        return false;
    }

    /**
     * Get project document params.
     *
     * @access public
     * @return bool
     */
    public function getProjectDocParams()
    {
        $params = $this->appendCountParams();
        return json_encode($params);
    }

    /**
     * Get the total estimated man hours required.
     *
     * @param  array $storyID
     * @access public
     * @return string
     */
    public function getStorysEstimateHours($storyID)
    {
        return $this->dao->select('count(estimate) as estimate')->from(TABLE_STORY)->where('id')->in($storyID)->fetch('estimate');
    }

    /**
     * Get zentao.net data.
     *
     * @param  string $minTime
     * @access public
     * @return array
     */
    public function getZentaoData($minTime = '')
    {
        return $this->dao->select('type,params')->from(TABLE_BLOCK)
            ->where('account')->eq('system')
            ->andWhere('vision')->eq('rnd')
            ->andWhere('module')->eq('zentao')
            ->beginIF($minTime)->andWhere('source')->ge($minTime)->fi()
            ->andWhere('type')->in('plugin,patch,publicclass,news')
            ->fetchPairs('type');
    }

    /**
     * Set zentao data.
     *
     * @param  string $type
     * @param  string $params
     * @access public
     * @return void
     */
    public function setZentaoData($type = 'patch', $params = '')
    {
        $data = new stdclass();
        $data->account = 'system';
        $data->vision  = 'rnd';
        $data->module  = 'zentao';
        $data->type    = $type;
        $data->source  = date('Y-m-d');
        $data->params  = json_encode($params);

        $this->dao->replace(TABLE_BLOCK)->data($data)->exec();
    }
}
