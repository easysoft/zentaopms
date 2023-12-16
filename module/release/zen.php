<?php
declare(strict_types=1);
/**
 * The zen file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
class releaseZen extends release
{
    /**
     * 处理发布列表展示数据。
     * Process release list display data.
     *
     * @param  array     $releaseList
     * @access protected
     * @return array
     */
    protected function processReleaseListData(array $releaseList): array
    {
        $releases = array();
        $this->loadModel('project');
        $this->loadModel('execution');
        foreach($releaseList as $release)
        {
            $buildCount = count($release->builds);

            $release->rowspan = $buildCount;
            $release->actions = $this->release->buildActionList($release);

            if(!empty($release->builds))
            {
                foreach($release->builds as $build)
                {
                    $releaseInfo  = clone $release;
                    $moduleName   = $build->execution ? 'build' : 'projectbuild';
                    $canClickable = false;
                    if($moduleName == 'projectbuild' && $this->project->checkPriv((int)$build->project)) $canClickable = true;
                    if($moduleName == 'build' && $this->execution->checkPriv((int)$build->execution))    $canClickable = true;
                    $build->link = $canClickable ? $this->createLink($moduleName, 'view', "buildID={$build->id}") : '';

                    $releaseInfo->build = $build;

                    $releases[] = $releaseInfo;
                }
            }
            else
            {
                $releases[] = $release;
            }

        }

        return $releases;
    }

    /**
     * 构造待创建的发布数据。
     * Build the release data to be create.
     *
     * @param  int          $productID
     * @param  int          $branch
     * @param  int          $projectID
     * @access protected
     * @return object|false
     */
    protected function buildReleaseForCreate(int $productID, int $branch, int $projectID = 0): object|false
    {
        $productID = $this->post->product ? $this->post->product : $productID;
        $branch    = $this->post->branch ? $this->post->branch : $branch;

        $release = form::data()
            ->add('product', (int)$productID)
            ->add('branch',  (int)$branch)
            ->setIF($projectID, 'project', $projectID)
            ->setIF($this->post->build === false, 'build', 0)
            ->get();

        /* Check build if build is required. */
        if(strpos($this->config->release->create->requiredFields, 'build') !== false && empty($release->build))
        {
            dao::$errors['build'] = sprintf($this->lang->error->notempty, $this->lang->release->build);
            return false;
        }

        return $release;
    }

    /**
     * 构建搜索表单字段。
     * Build search form fields.
     *
     * @param  int       $queryID
     * @param  string    $actionURL
     * @param  object    $product
     * @param  string    $branch
     * @access protected
     * @return void
     */
    protected function buildSearchForm(int $queryID, string $actionURL, object $product, string $branch): void
    {
        $this->config->release->search['queryID']   = $queryID;
        $this->config->release->search['actionURL'] = $actionURL;

        if($product->type != 'normal') $this->config->release->search['params']['branch']['values'] = $this->loadModel('branch')->getPairs($product->id, 'all');
        $this->config->release->search['params']['build']['values'] = $this->loadmodel('build')->getBuildPairs(array($product->id), $branch, 'notrunk|withbranch|hasproject', 0, 'execution', '', false);

        $this->loadModel('search')->setSearchParams($this->config->release->search);
    }

    /**
     * 获取发布列表的搜索条件。
     * Get the search condition of release list.
     *
     * @param  int       $queryID
     * @access protected
     * @return string
     */
    protected function getSearchQuery(int $queryID): string
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('releaseQuery', $query->sql);
                $this->session->set('releaseForm', $query->form);
            }
        }

        if($this->session->releaseQuery === false) $this->session->set('releaseQuery', ' 1 = 1');
        $releaseQuery = $this->session->releaseQuery;

        /* Replace the condition of all branch to 1. */
        $allBranch = "`branch` = 'all'";
        if(strpos($releaseQuery, $allBranch) !== false) $releaseQuery = str_replace($allBranch, '1', $releaseQuery);
        $releaseQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $releaseQuery);

        return $releaseQuery;
    }

    /**
     * 生成的发布详情页面的需求数据。
     * Generate the story data for the release view page.
     *
     * @param  object    $release
     * @param  string    $type
     * @param  string    $link
     * @param  string    $param
     * @param  string    $orderBy
     * @param  object    $storyPager
     * @param  object    $bugPager
     * @param  object    $leftBugPager
     * @access protected
     * @return void
     */
    protected function assignVarsForView(object $release, string $type, string $link, string $param, string $orderBy, object $storyPager, object $bugPager, object $leftBugPager): void
    {
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $sort .= ',buildID_asc';

        $stories = $this->release->getStoryList($release->stories, $release->branch, $type == 'story' ? $sort : '', $storyPager);

        $sort = common::appendOrder($orderBy);
        $bugs = $this->release->getBugList($release->bugs, $type == 'bug' ? $sort : '', $bugPager);

        if($type == 'leftBug' && strpos($orderBy, 'severity_') !== false) $sort = str_replace('severity_', 'severityOrder_', $sort);
        $leftBugs = $this->release->getBugList($release->leftBugs, $type == 'leftBug' ? $sort : '', $leftBugPager);

        $product = $this->loadModel('product')->getByID($release->product);

        $this->view->title        = "RELEASE #$release->id $release->name/" . $product->name;
        $this->view->actions      = $this->loadModel('action')->getList('release', $release->id);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->storyPager   = $storyPager;
        $this->view->stories      = $stories;
        $this->view->release      = $release;
        $this->view->orderBy      = $orderBy;
        $this->view->type         = $type;
        $this->view->link         = $link;
        $this->view->param        = $param;
        $this->view->storyCases   = $this->loadModel('testcase')->getStoryCaseCounts(array_keys($stories));
        $this->view->summary      = $this->product->summary($stories);
        $this->view->builds       = $this->loadModel('build')->getBuildPairs(array($release->product), 'all', 'withbranch|hasproject|hasdeleted', 0, 'execution', '', true);
        $this->view->bugs         = $bugs;
        $this->view->leftBugs     = $leftBugs;
        $this->view->bugPager     = $bugPager;
        $this->view->leftBugPager = $leftBugPager;

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->bugPager);
            unset($this->view->leftBugPager);
        }
    }

    /**
     * 构造关联需求的搜索表单。
     * Build the search form of link story.
     *
     * @param  object    $release
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildLinkStorySearchForm(object $release, int $queryID): void
    {
        $this->app->loadLang('story');
        $this->loadModel('product');

        unset($this->config->product->search['fields']['product']);
        unset($this->config->product->search['fields']['project']);
        unset($this->config->product->search['params']['product']);
        unset($this->config->product->search['params']['project']);

        $this->config->product->search['actionURL'] = $this->createLink($this->app->rawModule, 'view', "releaseID={$release->id}&type=story&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getPairs($release->product, $release->branch, 'withMainPlan', true);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        $searchModules = array();
        $moduleGroups  = $this->loadModel('tree')->getOptionMenu($release->product, 'story', 0, explode(',', $release->branch));
        foreach($moduleGroups as $modules) $searchModules += $modules;
        $this->config->product->search['params']['module']['values'] = $searchModules;

        if($release->productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairsByIdList(explode(',', trim($release->branch, ',')));
            $this->config->product->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$release->productType]);
            $this->config->product->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构造关联Bug的搜索表单。
     * Build the search form of link bug.
     *
     * @param  object    $release
     * @param  int       $queryID
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function buildLinkBugSearchForm(object $release, int $queryID, string $type): void
    {
        $this->loadModel('bug');
        unset($this->config->bug->search['fields']['product']);
        unset($this->config->bug->search['fields']['project']);
        unset($this->config->bug->search['params']['product']);
        unset($this->config->bug->search['params']['project']);

        $this->config->bug->search['actionURL'] = $this->createLink($this->app->rawModule, 'view', "releaseID={$release->id}&type={$type}&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=0'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';

        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($release->product, $release->branch, 'withMainPlan', true);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($release->product, $release->branch);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs(array($release->product), 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        $searchModules = array();
        $moduleGroups  = $this->loadModel('tree')->getOptionMenu($release->product, 'bug', 0, explode(',', $release->branch));
        foreach($moduleGroups as $modules) $searchModules += $modules;
        $this->config->bug->search['params']['module']['values'] = $searchModules;

        if($release->productType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairsByIdList(explode(',', trim($release->branch, ',')));
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$release->productType]);
            $this->config->bug->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 构造导出的需求列表数据。
     * Build the story list data for export.
     *
     * @param  object    $release
     * @access protected
     * @return string
     */
    protected function buildStoryDataForExport(object $release): string
    {
        $this->loadModel('story');

        $html    = "<h3>{$this->lang->release->stories}</h3>";
        $fields  = array('id' => $this->lang->story->id, 'title' => $this->lang->story->title);
        $stories = $this->release->getStoryList($release->stories, (int)$release->branch);
        if(empty($stories)) return $html;

        $html .= '<table><tr>';
        foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
        $html .= '</tr>';

        $stories = array_map(function($story){$story->title = "<a href='" . common::getSysURL() . $this->createLink('story', 'view', "storyID=$story->id") . "' target='_blank'>$story->title</a>"; return $story;}, $stories);
        foreach($stories as $row)
        {
            $html .= "<tr valign='top'>\n";
            foreach($fields as $fieldName => $fieldLabel) $html .= "<td><nobr>" . zget($row, $fieldName, '') . "</nobr></td>\n";
            $html .= "</tr>\n";
        }
        $html .= '</table>';

        return $html;
    }

    /**
     * 构造导出的解决的Bug或遗留Bug列表数据。
     * Build the resolved or generated bug list data for export.
     *
     * @param  object    $release
     * @param  string    $type        bug|leftbug
     * @access protected
     * @return string
     */
    protected function buildBugDataForExport(object $release, string $type = 'bug'): string
    {
        $this->loadModel('bug');

        $title     = $type == 'bug' ? $this->lang->release->bugs : $this->lang->release->generatedBugs;
        $html      = "<h3>{$title}</h3>";
        $fields    = array('id' => $this->lang->bug->id, 'title' => $this->lang->bug->title);
        $bugIdList = $type == 'bug' ? $release->bugs : $release->leftBugs;
        $bugs      = $this->release->getBugList($bugIdList);
        if(empty($bugs)) return $html;

        $html .= '<table><tr>';
        foreach($fields as $fieldLabel) $html .= "<th><nobr>$fieldLabel</nobr></th>\n";
        $html .= '</tr>';
        $bugs = array_map(function($bug){$bug->title = "<a href='" . common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id") . "' target='_blank'>$bug->title</a>"; return $bug;}, $bugs);
        foreach($bugs as $row)
        {
            $html .= "<tr valign='top'>\n";
            foreach($fields as $fieldName => $fieldLabel) $html .= "<td><nobr>" . zget($row, $fieldName, '') . "</nobr></td>\n";
            $html .= "</tr>\n";
        }
        $html .= '</table>';

        return $html;
    }
}
