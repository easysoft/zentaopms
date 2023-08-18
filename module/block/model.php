<?php
declare(strict_types=1);
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
     * 检查ZDOO请求时发起的哈希值是否匹配。
     * Check if the hash value matches when requesting ZDOO.
     *
     * @param  string $hash
     * @access public
     * @return bool
     */
    public function checkAPI(string $hash): bool
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
     * 根据区块ID获取区块信息。
     * Get a block by id.
     *
     * @param  int          $blockID
     * @access public
     * @return object|false
     */
    public function getByID(int $blockID): object|false
    {
        if(!$blockID) return false;

        $block = $this->dao->select('*')->from(TABLE_BLOCK)->where('id')->eq($blockID)->fetch();
        if(empty($block)) return false;

        $block->params = json_decode($block->params);
        if(empty($block->params)) $block->params = new stdclass();

        if($block->code == 'html') $block->params->html = $this->loadModel('file')->setImgSize($block->params->html);
        return $block;
    }

    /**
     * 获取被永久关闭的区块数据。
     * Get closed block pairs.
     *
     * @param  string $closedBlock
     * @access public
     * @return array
     */
    public function getClosedBlockPairs(string $closedBlock): array
    {
        $blockPairs = array();
        if(empty($closedBlock)) return $blockPairs;

        foreach(explode(',', $closedBlock) as $block)
        {
            $block = trim($block);
            if(empty($block)) continue;

            list($moduleName, $blockKey) = explode('|', $block);
            if($moduleName == $blockKey)
            {
                $blockPairs[$block] = zget($this->lang->block->moduleList, $moduleName);
            }
            else
            {
                $blockName = $blockKey;
                if(isset($this->lang->block->modules[$moduleName]->availableBlocks[$blockKey])) $blockName = $this->lang->block->modules[$moduleName]->availableBlocks[$blockKey];
                if(isset($this->lang->block->availableBlocks[$blockKey])) $blockName = $this->lang->block->availableBlocks[$blockKey];
                $moduleName = zget($this->lang->block->moduleList, $moduleName);
                $blockPairs[$block] = "{$moduleName}|{$blockName}";
            }
        }

        return $blockPairs;
    }

    /**
     * 获取当前用户的区块列表。
     * Get block list of current user.
     *
     * @param  string      $module
     * @param  int         $hidden
     * @access public
     * @return array|false
     */
    public function getMyDashboard(string $dashboard): array|false
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('dashboard')->eq($dashboard)
            ->andWhere('hidden')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('width_desc,top_asc,id_asc')
            ->fetchAll();
    }

    /**
     * Get data of welcome block.
     *
     * @access public
     * @return array
     */
    public function getWelcomeBlockData()
    {
        $data = array();

        $tasks = $this->dao->select("count(distinct t1.id) as tasks, count(distinct if(t1.status = 'done', 1, null)) as doneTasks")->from(TABLE_TASK)->alias('t1')
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
            ->fetch();

        $data['tasks']     = isset($tasks->tasks)     ? $tasks->tasks : 0;
        $data['doneTasks'] = isset($tasks->doneTasks) ? $tasks->doneTasks : 0;

        $data['bugs']       = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on("t1.product = t2.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->ne('closed')
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('count');
        $data['stories']    = (int)$this->dao->select('count(*) AS count')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->fetch('count');
        $data['executions'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_EXECUTION)
            ->where('status')->notIN('done,closed')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch('count');
        $data['products']   = (int)$this->dao->select('count(*) AS count')->from(TABLE_PRODUCT)
            ->where('status')->ne('closed')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        $today = date('Y-m-d');
        $data['delayTask'] = (int)$this->dao->select('count(t1.id) AS count')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on("t1.project = t2.id")
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on("t1.execution = t3.id")
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('(t2.status')->ne('suspended')
            ->orWhere('t3.status')->ne('suspended')
            ->markRight(1)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('t1.deadline')->notZeroDate()
            ->andWhere('t1.deadline')->lt($today)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetch('count');
        $data['delayBug'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($this->app->user->account)
            ->andWhere('t1.status')->eq('active')
            ->andWhere('t1.deadline')->notZeroDate()
            ->andWhere('t1.deadline')->lt($today)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('count');
        $data['delayProject'] = (int)$this->dao->select('count(*) AS count')->from(TABLE_PROJECT)
            ->where('status')->in('wait,doing')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('end')->lt($today)
            ->andWhere('deleted')->eq(0)
            ->fetch('count');

        return $data;
    }

    /**
     * 根据区块索引获取靠后的一个区块ID。
     * Get my block id by block code,
     *
     * @param  string    $dashboard
     * @param  string    $module
     * @param  string    $code
     * @access public
     * @return int|false
     */
    public function getSpecifiedBlockID(string $dashboard, string $module, string $code): int|false
    {
        if(!$dashboard || !$module || !$code) return false;

        $blockID = $this->dao->select('id')->from(TABLE_BLOCK)
            ->where('account')->eq($this->app->user->account)
            ->andWhere('dashboard')->eq($dashboard)
            ->andWhere('module')->eq($module)
            ->andWhere('code')->eq($code)
            ->orderBy('id_desc')
            ->limit(1)
            ->fetch('id');

        return $blockID ? $blockID : false;
    }

    /**
     * 获取区块是否已经初始化的状态。
     * get block is initiated or not.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function getBlockInitStatus(string $dashboard): bool
    {
        if(!$dashboard) return false;

        $result = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('module')->eq($dashboard)
            ->andWhere('owner')->eq($this->app->user->account)
            ->andWhere('`section`')->eq('common')
            ->andWhere('`key`')->eq('blockInited')
            ->andWhere('vision')->eq($this->config->vision)
            ->fetch('value');

        return !empty($result);
    }

    /**
     * 新增一个区块。
     * Create a block.
     *
     * @param  object $formData
     * @access public
     * @return int
     */
    public function create(object $formData): int|false
    {
        $this->dao->insert(TABLE_BLOCK)->data($formData)
            ->autoCheck()
            ->batchCheck($this->config->block->create->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        return (int)$this->dao->lastInsertID();
    }

    /**
     * 修改一个区块。
     * Update a block.
     *
     * @param  object    $formData
     * @access public
     * @return int|false
     */
    public function update(object $formData): int|false
    {
        $this->dao->update(TABLE_BLOCK)->data($formData)
            ->where('id')->eq($formData->id)
            ->autoCheck()
            ->batchCheck($this->config->block->edit->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        return (int)$formData->id;
    }

    /**
     * 对应仪表盘删除所有区块并更新为待初始化状态。
     * Reset dashboard blocks.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function reset(string $dashboard): bool
    {
        /* 删除当前仪表盘下该用户的所有区块。 */
        $this->dao->delete()->from(TABLE_BLOCK)
            ->where('dashboard')->eq($dashboard)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('account')->eq($this->app->user->account)
            ->exec();

        /* 重置初始化状态为未初始化。 */
        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('module')->eq($dashboard)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('owner')->eq($this->app->user->account)
            ->andWhere('`key`')->eq('blockInited')
            ->exec();

        return !dao::isError();
    }

    /**
     * 根据ID删除一个区块或者根据代号删除多个区块。
     * Delete a block based on id or multiple blocks based on code.
     *
     * @param  int    $blockID
     * @access public
     * @return bool
     */
    public function deleteBlock(int $blockID = 0, string $module = '', string $code = ''): bool
    {
        /* 指定BlockID时删除该区块，指定module和code时候会删除所有人的区块。 */
        $this->dao->delete()->from(TABLE_BLOCK)
            ->where('1=1')
            ->beginIF($blockID)
            ->andWhere('id')->eq($blockID)
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->fi()
            ->beginIF($module && $code)
            ->andWhere('module')->eq($module)
            ->andWhere('code')->eq($code)
            ->fi()
            ->exec();

        return !dao::isError();
    }

    /**
     * 计算区块距离顶部的高度。
     * compute block top height.
     *
     * @param  object $block
     * @access public
     * @return int
     */
    public function computeBlockTop(object $block): int
    {
        $top = $this->dao->select('max(`top` + `height`) AS top')->from(TABLE_BLOCK)
            ->where('dashboard')->eq($block->dashboard)
            ->andWhere('width', true)->eq($block->width)
            ->orWhere('width')->eq(3)
            ->markRight(1)
            ->andWhere('vision')->eq($block->vision)
            ->andWhere('hidden')->eq('0')
            ->fetch('top');

        if(!$top) $top = 0;
        return $top;
    }
}
