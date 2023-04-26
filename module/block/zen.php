<?php
class blockZen extends block
{
    /**
     * Build a form for create block page.
     * 构造新增区块页面的表单
     * 
     * @param  string $module 
     * @access protected
     * @return void
     */
    protected function buildCreateForm(string $dashboard, string $module, string $block)
    {
        $this->buildCreateAndEditForm($dashboard, $module, $block);
        $this->view->title = $this->lang->block->createBlock;
    }

    protected function buildEditForm(int $blockID, string $dashboard)
    {
        $this->buildCreateAndEditForm($dashboard);
        $this->view->title = $this->lang->block->editBlock;
        $this->view->block = $this->block->getByID($blockID);
    }

    private function buildCreateAndEditForm($dashboard, $module, $block)
    {
        if($dashboard == 'my')
        {
            return $this->buildCreateAndEditFormByTerritory($dashboard, $module, $block);
        }
        else
        {
            return $this->buildCreateAndEditFormByModule($dashboard);
        }
    }

    private function buildCreateAndEditFormByTerritory($dashboard, $module, $block)
    {
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
        $this->view->modules   = $modules;
        $this->view->blocks    = $this->getAvailableBlocks($dashboard);
        $this->view->params    = $this->getAvailableParams($dashboard, $module, $block);
        $this->view->dashboard = $dashboard;
        $this->view->module    = '';
    }

    private function buildCreateAndEditFormByModule($dashboard)
    {
        if($this->config->edition == 'max' and strpos($dashboard, 'Project') !== false)
        {
            if($dashboard == 'scrumProject')
            {
                if(!helper::hasFeature("scrum_issue")) unset($this->lang->block->modules['scrum']['index']->availableBlocks->scrumissue);
                if(!helper::hasFeature("scrum_risk"))  unset($this->lang->block->modules['scrum']['index']->availableBlocks->scrumrisk);
            }
            if($dashboard == 'waterfallProject')
            {
                if(!helper::hasFeature("waterfall_issue")) unset($this->lang->block->modules['waterfall']['index']->availableBlocks->waterfallissue);
                if(!helper::hasFeature("waterfall_risk"))  unset($this->lang->block->modules['waterfall']['index']->availableBlocks->waterfallrisk);
            }
        }

        $this->view->blocks    = $this->getAvailableBlocks($dashboard);
        $this->view->dashboard = $dashboard;
        $this->view->module    = $dashboard;
    }

    private function getAvailableBlocks($dashboard)
    {
        $module = $this->get->module;
        $blocks = $this->block->getAvailableBlocks($dashboard, $module);

        if(!$this->selfCall)
        {
            echo json_encode($blocks);
            return true;
        }

        return !empty($blocks) ? $blocks : array();
    }

    private function getAvailableParams(string $dashboard, string $module = '', string $block = '') : array
    {
        if(!isset($this->lang->block->moduleList[$module])) return array();

        if(!$block) return array();

        $params = $this->block->getParams($module, $module);
        return !empty($params) ? json_decode($params, true) : array();
    }
}
