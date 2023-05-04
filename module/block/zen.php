<?php
class blockZen extends block
{
    /**
     * Get module options when adding or editing blocks.
     * 添加或编辑区块时获取可使用的模块选项
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
     * 添加或编辑区块时获取可使用的区块选项
     *
     * @param  string $dashboard
     * @param  string $module
     * @access protected
     * @return string[]|true
     */
    protected function getAvailableBlocks($dashboard, $module): array|bool
    {
        if(!$this->selfCall)
        {
            $lang = str_replace('_', '-', $this->get->lang);
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');

            if(!$this->block->checkAPI($this->get->hash)) return array();
        }

        if($dashboard == 'my')
        {
            if($module and isset($this->lang->block->modules[$module]))
            {
                $blocks = $this->lang->block->modules[$module]->availableBlocks;
            }
            else
            {
                $blocks = array();
            }
        }
        else
        {
            if($dashboard and isset($this->lang->block->modules[$dashboard]))
            {
                $blocks = $this->lang->block->modules[$dashboard]->availableBlocks;
            }
            else
            {
                $blocks = $this->lang->block->availableBlocks;
            }
        }

        if(isset($this->config->block->closed))
        {
            foreach($blocks as $blockKey => $blockName)
            {
                if(strpos(",{$this->config->block->closed},", ",{$module}|{$blockKey},") !== false) unset($blocks[$blockKey]);
            }
        }

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
     * @return array
     */
    protected function getAvailableParams(string $dashboard, string $module = '', string $code = ''): array
    {
        if($code == 'todo' || $code == 'list' || $module == 'assigntome')
        {
            $code = $module;
        }
        elseif($code == 'statistic')
        {
            $code = $module . $code;
        }

        $params = zget($this->config->block->params, $code, '');
        $params = json_decode(json_encode($params), true);

        return !empty($params) ? $params : array();
    }

    /**
     * 处理每个区块以渲染 UI。
     * Process each block for render UI.
     *
     * @param  object[] $blocks
     * @param  int      $projectID
     * @return object[]
     */
    protected function processBlockForRender(array $blocks, int $projectID): array
    {
        $acls = $this->app->user->rights['acls'];
        foreach($blocks as $key => $block)
        {
            if($block->code == 'waterfallrisk' and !helper::hasFeature("waterfall_risk"))   continue;
            if($block->code == 'waterfallissue' and !helper::hasFeature("waterfall_issue")) continue;
            if($block->code == 'scrumrisk' and !helper::hasFeature("scrum_risk"))           continue;
            if($block->code == 'scrumissue' and !helper::hasFeature("scrum_issue"))         continue;

            if(!empty($block->source) and $block->source != 'todo' and !empty($acls['views']) and !isset($acls['views'][$block->source]))
            {
                unset($blocks[$key]);
                continue;
            }

            $block->params = json_decode($block->params);
            if(isset($block->params->num) and !isset($block->params->count)) $block->params->count = $block->params->num;

            $this->getBlockMoreLink($block, $projectID);
        }

        return $blocks;
    }

    /**
     * 获取区块的更多链接。
     * Get the more link of the block.
     *
     * @param  object $block
     * @param  int    $projectID
     * @return void
     */
    private function getBlockMoreLink(object $block, int $project): void
    {
        $code   = $block->code;
        $source = empty($block->source) ? 'common' : $block->source;

        $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&module=$block->module");
        $block->moreLink  = '';
        if(isset($this->config->block->modules[$source]->moreLinkList->{$code}))
        {
            list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$source]->moreLinkList->{$code}, isset($block->params->type) ? $block->params->type : ''));

            /* The list assigned to me jumps to the work page when click more button. */
            $block->moreLink = $this->createLink($moduleName, $method, $vars);
            if($moduleName == 'my' and strpos($this->config->block->workMethods, $method) !== false)
            {
                $block->moreLink = $this->createLink($moduleName, 'work', 'mode=' . $method . '&' . $vars);
            }
            elseif($moduleName == 'project' and $method == 'dynamic')
            {
                $block->moreLink = $this->createLink('project', 'dynamic', "projectID=$projectID&type=all");
            }
            elseif($moduleName == 'project' and $method == 'execution')
            {
                $block->moreLink = $this->createLink('project', 'execution', "status=all&projectID=$projectID");
            }
            elseif($moduleName == 'project' and $method == 'testtask')
            {
                $block->moreLink = $this->createLink('project', 'testtask', "projectID=$projectID");
            }
            elseif($moduleName == 'testtask' and $method == 'browse')
            {
                $block->moreLink = $this->createLink('testtask', 'browse', "productID=0&branch=0&type=all,totalStatus");
            }
        }
        elseif($block->code == 'dynamic')
        {
            $block->moreLink = $this->createLink('company', 'dynamic');
        }
    }

    /**
     * 将区块数组拆分为短区块数组和长区块数组。
     * Split blocks array into short blocks and long blocks.
     *
     * @param  array   $blocks
     * @return array[]
     */
    protected function splitBlocksByLen(array $blocks): array
    {
        $shortBlocks = $longBlocks = array();
        foreach($blocks as $key => $block)
        {
            if($this->block->isLongBlock($block))
            {
                $longBlocks[$key] = $block;
            }
            else
            {
                $shortBlocks[$key] = $block;
            }
        }

        return array($shortBlocks, $longBlocks);
    }
}
