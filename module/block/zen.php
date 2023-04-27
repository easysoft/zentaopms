<?php
class blockZen extends block
{
    /**
     * Get module options when adding or editing blocks.
     * 添加或编辑区块时获取模块选项
     * 
     * @param  string $dashboard
     * @access protected
     * @return string[]
     */
    protected function getAvailableModules(string $dashboard): array
    {
        if($dashboard != 'my') return array();        

        $modules = $this->lang->block->moduleList;
        unset($modules['doc']);

        list($programModule, $programMethod)     = explode('-', $this->config->programLink);
        list($productModule, $productMethod)     = explode('-', $this->config->productLink);
        list($projectModule, $projectMethod)     = explode('-', $this->config->projectLink);
        list($executionModule, $executionMethod) = explode('-', $this->config->executionLink);

        foreach($modules as $moduleKey => $moduleName)
        {
            if($moduleKey == 'todo') continue;
            if(in_array($moduleKey, $this->app->user->rights['acls'])) unset($modules[$moduleKey]);

            $method = 'index';
            if($moduleKey == 'program')   $method = $programMethod;
            if($moduleKey == 'product')   $method = $productMethod;
            if($moduleKey == 'project')   $method = $projectMethod;
            if($moduleKey == 'execution') $method = $executionMethod;

            if(!common::hasPriv($moduleKey, $method)) unset($modules[$moduleKey]);
        }

        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        if(strpos(",$closedBlock,", ",|assigntome,") === false) $modules['assigntome'] = $this->lang->block->assignToMe;
        if(strpos(",$closedBlock,", ",|dynamic,") === false) $modules['dynamic'] = $this->lang->block->dynamic;
        if(strpos(",$closedBlock,", ",|guide,") === false and $this->config->global->flow == 'full') $modules['guide'] = $this->lang->block->guide;
        if(strpos(",$closedBlock,", ",|welcome,") === false and $this->config->global->flow == 'full') $modules['welcome'] = $this->lang->block->welcome;
        if(strpos(",$closedBlock,", ",|html,") === false) $modules['html'] = 'HTML';
        if(strpos(",$closedBlock,", ",|contribute,") === false and $this->config->vision == 'rnd') $modules['contribute'] = $this->lang->block->contribute;
        $modules = array('' => '') + $modules;

        $hiddenBlocks = $this->block->getMyHiddenBlocks('my');
        foreach($hiddenBlocks as $block) $modules['hiddenBlock' . $block->id] = $block->title;

        return $modules;
    }

    /**
     * Get block options when adding or editing blocks.
     * 添加或编辑区块时获取区块选项
     *
     * @param  string $dashboard
     * @param  string $module
     * @access protected
     * @return string[]|true
     */
    protected function getAvailableBlocks($dashboard, $module): array|bool
    {
        $blocks = $this->block->getAvailableBlocks($dashboard, $module);

        if(!$this->selfCall)
        {
            echo json_encode($blocks);
            return true;
        }

        return !empty($blocks) ? $blocks : array();
    }

    /**
     * Get other form items when adding or editing blocks
     * 添加或编辑区块时获取其他表单项
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $block
     * @access protected
     * @return array[]
     */
    protected function getAvailableParams(string $dashboard, string $module = '', string $block = ''): array
    {
        if(!isset($this->lang->block->moduleList[$module])) return array();

        if(!$block) return array();

        $params = json_decode($this->block->getParams($block, $module), true);

        return !empty($params) ? $params : array();
    }
}
