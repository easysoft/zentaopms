<?php
declare(strict_types=1);
/**
 * The model file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

    /**
     * 更新区块布局（保存每个区块的坐标信息）。
     * Update block layout.
     *
     * @param  array $layout
     * @access public
     * @return bool
     */
    public function updateLayout(array $layout): bool
    {
        foreach($layout as $blockID => $block)
        {
            $this->dao->update(TABLE_BLOCK)
                ->set('left')->eq($block['left'])
                ->set('top')->eq($block['top'])
                ->where('id')->eq($blockID)
                ->exec();

            if(dao::isError()) return false;
        }
        return true;
    }
}
