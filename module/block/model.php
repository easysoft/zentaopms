<?php
/**
 * The model file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
            ->setDefault('params', array())
            ->remove('uid')
            ->get();

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
    public function getBlockList($module = 'my')
    {
        $blocks = $this->dao->select('*')->from(TABLE_BLOCK)->where('account')->eq($this->app->user->account)
            ->andWhere('module')->eq($module)
            ->andWhere('hidden')->eq(0)
            ->orderBy('`order`')
            ->fetchAll('id');

        return $blocks;
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
     * Init block when account use first. 
     * 
     * @param  string    $appName 
     * @access public
     * @return bool
     */
    public function initBlock($module)
    {
        $flow    = isset($this->config->global->flow) ? $this->config->global->flow : 'full';
        $blocks  = $module == 'my' ? $this->lang->block->default[$flow][$module] : $this->lang->block->default[$module];
        $account = $this->app->user->account;

        /* Mark this app has init. */
        $this->loadModel('setting')->setItem("$account.$module.common.blockInited", true);
        foreach($blocks as $index => $block)
        {
            $block['order']   = $index;
            $block['module']  = $module;
            $block['account'] = $account;
            $block['params']  = isset($block['params']) ? helper::jsonEncode($block['params']) : '';
            if(!isset($block['source'])) $block['source'] = $module;

            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
        }

        return !dao::isError();
    }

    /**
     * Get block list.
     * 
     * @access public
     * @return string
     */
    public function getAvailableBlocks($module = '')
    {
        $blocks = $this->lang->block->availableBlocks;
        if($module and isset($this->lang->block->modules[$module])) $blocks = $this->lang->block->modules[$module]->availableBlocks;
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
     * @return void
     */
    public function getListParams($module = '')
    {
        if($module == 'product') return $this->getProductParams($module);
        if($module == 'project') return $this->getProjectParams($module);

        $params = new stdclass();
        $params = $this->onlyNumParams($module, $params);
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
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->task;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';

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
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->bug;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';

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
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->case;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';

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
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->testtask;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';

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
        $params = new stdclass();
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->story;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';

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
    public function getPlanParams($module = '')
    {
        $params = $this->onlyNumParams($module);
        return json_encode($params);
    }

    /**
     * Get Release params.
     * 
     * @access public
     * @return json
     */
    public function getReleaseParams($module = '')
    {
        $params = $this->onlyNumParams($module);
        return json_encode($params);
    }

    /**
     * Get Build params.
     * 
     * @access public
     * @return json
     */
    public function getBuildParams($module = '')
    {
        $params = $this->onlyNumParams($module);
        return json_encode($params);
    }

    /**
     * Get product params.
     * 
     * @access public
     * @return json
     */
    public function getProductParams($module = '')
    {
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->product;
        $params->type['control'] = 'select';

        return json_encode($this->onlyNumParams($module, $params));
    }

    /**
     * Get project params.
     * 
     * @access public
     * @return json
     */
    public function getProjectParams($module = '')
    {
        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->project;
        $params->type['control'] = 'select';

        return json_encode($this->onlyNumParams($module, $params));
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
                if($blockKey == 'html')      $blockPairs[$block] = 'HTML';
                if($blockKey == 'flowchart') $blockPairs[$block] = $this->lang->block->lblFlowchart;
                if($blockKey == 'dynamic')   $blockPairs[$block] = $this->lang->block->dynamic;
            }
            else
            {
                $blockPairs[$block] = "{$this->lang->block->moduleList[$moduleName]}|{$this->lang->block->modules[$moduleName]->availableBlocks->$blockKey}";
            }
        }

        return $blockPairs;
    }

    /**
     * Build number params.
     * 
     * @param  string $module 
     * @param  object $params 
     * @access public
     * @return object
     */
    public function onlyNumParams($module = '', $params = '')
    {
        if(empty($params)) $params = new stdclass();
        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 20; 
        $params->num['control'] = 'input';
        return $params;
    }
}
