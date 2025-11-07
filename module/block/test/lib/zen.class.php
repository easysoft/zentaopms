<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockZenTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'zen';

    /**
     * Test printBuildBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printBuildBlockTest(object $block)
    {
        $this->invokeArgs('printBuildBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->builds))
        {
            $result->count = count($view->builds);
            foreach($view->builds as $index => $build)
            {
                $result->$index = $build;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printCaseBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printCaseBlockTest(object $block)
    {
        $this->invokeArgs('printCaseBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->cases))
        {
            $result->count = count($view->cases);
            foreach($view->cases as $index => $case)
            {
                $result->$index = $case;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocDynamicBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocDynamicBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocDynamicBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->actions))
        {
            $result->actionsCount = count($view->actions);
            foreach($view->actions as $index => $action)
            {
                $result->$index = $action;
            }
        }
        else
        {
            $result->actionsCount = 0;
        }

        if(isset($view->users))
        {
            $result->usersCount = count($view->users);
        }
        else
        {
            $result->usersCount = 0;
        }
        return $result;
    }

    /**
     * Test printDocMyCollectionBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCollectionBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocMyCollectionBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocMyCreatedBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCreatedBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocMyCreatedBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocRecentUpdateBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocRecentUpdateBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocRecentUpdateBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printDocViewListBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocViewListBlockTest()
    {
        ob_start();
        $this->invokeArgs('printDocViewListBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->docList))
        {
            $result->count = count($view->docList);
            foreach($view->docList as $index => $doc)
            {
                $result->$index = $doc;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printExecutionListBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printExecutionListBlockTest(object $block)
    {
        $this->invokeArgs('printExecutionListBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        if(isset($view->executions))
        {
            $result->count = count($view->executions);
            foreach($view->executions as $index => $execution)
            {
                $result->$index = $execution;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }

    /**
     * Test printExecutionStatisticBlock method.
     *
     * @param  object $block  区块对象
     * @param  array  $params 参数数组
     * @access public
     * @return object
     */
    public function printExecutionStatisticBlockTest(object $block, array $params = array())
    {
        $this->invokeArgs('printExecutionStatisticBlock', array($block, $params));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->executionsCount  = isset($view->executions) ? count($view->executions) : 0;
        $result->projectsCount    = isset($view->projects) ? count($view->projects) : 0;
        $result->hasChartData     = isset($view->chartData) ? 1 : 0;
        $result->labelsCount      = isset($view->chartData['labels']) ? count($view->chartData['labels']) : 0;
        $result->currentProjectID = isset($view->currentProjectID) ? $view->currentProjectID : 0;
        return $result;
    }

    /**
     * Test printGuideBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printGuideBlockTest(object $block)
    {
        $this->invokeArgs('printGuideBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->blockID = isset($view->blockID) ? $view->blockID : 0;
        $result->programsCount = isset($view->programs) ? count($view->programs) : 0;
        $result->programID = isset($view->programID) ? $view->programID : 0;
        $result->URSRListCount = isset($view->URSRList) ? count($view->URSRList) : 0;
        $result->URSR = isset($view->URSR) ? $view->URSR : '';
        $result->programLink = isset($view->programLink) ? $view->programLink : '';
        $result->productLink = isset($view->productLink) ? $view->productLink : '';
        $result->projectLink = isset($view->projectLink) ? $view->projectLink : '';
        $result->executionLink = isset($view->executionLink) ? $view->executionLink : '';
        return $result;
    }

    /**
     * Test printMonthlyProgressBlock method.
     *
     * @access public
     * @return object
     */
    public function printMonthlyProgressBlockTest()
    {
        $this->invokeArgs('printMonthlyProgressBlock', array());
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->doneStoryEstimateCount = isset($view->doneStoryEstimate) ? count($view->doneStoryEstimate) : 0;
        $result->doneStoryCountCount = isset($view->doneStoryCount) ? count($view->doneStoryCount) : 0;
        $result->createStoryCountCount = isset($view->createStoryCount) ? count($view->createStoryCount) : 0;
        $result->fixedBugCountCount = isset($view->fixedBugCount) ? count($view->fixedBugCount) : 0;
        $result->createBugCountCount = isset($view->createBugCount) ? count($view->createBugCount) : 0;
        $result->totalDataArrays = $result->doneStoryEstimateCount + $result->doneStoryCountCount + $result->createStoryCountCount + $result->fixedBugCountCount + $result->createBugCountCount;
        return $result;
    }

    /**
     * Test printPlanBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printPlanBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printPlanBlock', array($block));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        $result->plansCount = isset($view->plans) ? count($view->plans) : 0;
        if(isset($view->plans))
        {
            foreach($view->plans as $index => $plan)
            {
                $result->$index = $plan;
            }
        }
        return $result;
    }

    /**
     * Test printProductDocBlock method.
     *
     * @param  object $block  区块对象
     * @param  array  $params 参数数组
     * @access public
     * @return object
     */
    public function printProductDocBlockTest(object $block, array $params = array())
    {
        ob_start();
        $this->invokeArgs('printProductDocBlock', array($block, $params));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->type = isset($view->type) ? $view->type : '';
        $result->usersCount = isset($view->users) ? count($view->users) : 0;
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        $result->docGroupCount = isset($view->docGroup) ? count($view->docGroup) : 0;
        if(isset($view->products))
        {
            foreach($view->products as $index => $product)
            {
                $result->$index = $product;
            }
        }
        return $result;
    }

    /**
     * Test printProductListBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printProductListBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printProductListBlock', array($block));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->productStatsCount = isset($view->productStats) ? count($view->productStats) : 0;
        $result->usersCount = isset($view->users) ? count($view->users) : 0;
        $result->avatarListCount = isset($view->avatarList) ? count($view->avatarList) : 0;
        if(isset($view->productStats))
        {
            foreach($view->productStats as $index => $product)
            {
                $result->$index = $product;
            }
        }
        return $result;
    }

    /**
     * Test printProductStatisticBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printProductStatisticBlockTest(object $block)
    {
        $this->invokeArgs('printProductStatisticBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        if(isset($view->products))
        {
            foreach($view->products as $productID => $product)
            {
                $result->$productID = $product;
            }
        }
        return $result;
    }

    /**
     * Test printProjectOverviewBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printProjectOverviewBlockTest(object $block)
    {
        $this->invokeArgs('printProjectOverviewBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->groupsCount = isset($view->groups) ? count($view->groups) : 0;

        if(isset($view->groups))
        {
            foreach($view->groups as $index => $group)
            {
                $groupKey = 'group' . $index;
                $result->$groupKey = new stdClass();
                $result->$groupKey->type = isset($group->type) ? $group->type : '';

                if(isset($group->type) && $group->type == 'cards' && isset($group->cards))
                {
                    $result->$groupKey->cardsCount = count($group->cards);
                }

                if(isset($group->type) && $group->type == 'barChart' && isset($group->bars))
                {
                    $result->$groupKey->barsCount = count($group->bars);
                    $result->$groupKey->title = isset($group->title) ? $group->title : '';
                }
            }
        }
        return $result;
    }

    /**
     * Test printProjectStatisticBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printProjectStatisticBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printProjectStatisticBlock', array($block));
        ob_get_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->projectCount = isset($view->projects) ? count($view->projects) : 0;
        $result->userCount = isset($view->users) ? count($view->users) : 0;

        if(isset($view->projects))
        {
            foreach($view->projects as $index => $project)
            {
                $projectKey = 'project' . $index;
                $result->$projectKey = new stdClass();
                $result->$projectKey->id = isset($project->id) ? $project->id : 0;
                $result->$projectKey->name = isset($project->name) ? $project->name : '';
                $result->$projectKey->status = isset($project->status) ? $project->status : '';
            }
        }
        return $result;
    }

    /**
     * Test printScrumOverviewBlock method.
     *
     * @access public
     * @return object
     */
    public function printScrumOverviewBlockTest()
    {
        ob_start();
        $this->invokeArgs('printScrumOverviewBlock', array());
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->projectID = isset($view->projectID) ? $view->projectID : 0;
        $result->hasProject = isset($view->project) ? 1 : 0;

        if(isset($view->project))
        {
            $project = $view->project;
            $result->projectName = isset($project->name) ? $project->name : '';
            $result->projectModel = isset($project->model) ? $project->model : '';
            $result->executionsCount = isset($project->executions) ? count($project->executions) : 0;
            $result->storyPoints = isset($project->storyPoints) ? $project->storyPoints : 0;
            $result->tasks = isset($project->tasks) ? $project->tasks : 0;
            $result->bugs = isset($project->bugs) ? $project->bugs : 0;
        }

        return $result;
    }

    /**
     * Test printScrumProductBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printScrumProductBlockTest(object $block)
    {
        $this->invokeArgs('printScrumProductBlock', array($block));
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        $result->storiesCount = isset($view->stories) ? count($view->stories) : 0;
        $result->bugsCount = isset($view->bugs) ? count($view->bugs) : 0;
        $result->releasesCount = isset($view->releases) ? count($view->releases) : 0;

        if(isset($view->products))
        {
            foreach($view->products as $productID => $productName)
            {
                $productKey = 'product' . $productID;
                $result->$productKey = new stdClass();
                $result->$productKey->name = $productName;
                $result->$productKey->storyTotal = isset($view->stories[$productID]) ? $view->stories[$productID] : 0;
                $result->$productKey->bugTotal = isset($view->bugs[$productID]) ? $view->bugs[$productID] : 0;
                $result->$productKey->releaseTotal = isset($view->releases[$productID]) ? $view->releases[$productID] : 0;
            }
        }
        return $result;
    }

    /**
     * Test printScrumRoadMapBlock method.
     *
     * @param  int $productID 产品ID
     * @param  int $roadMapID 路线图ID
     * @access public
     * @return object
     */
    public function printScrumRoadMapBlockTest(int $productID = 0, int $roadMapID = 0)
    {
        ob_start();
        $this->invokeArgs('printScrumRoadMapBlock', array($productID, $roadMapID));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        $result->roadmapsCount = isset($view->roadmaps) ? count($view->roadmaps) : 0;
        $result->productID = isset($view->productID) ? $view->productID : 0;
        $result->roadMapID = isset($view->roadMapID) ? $view->roadMapID : 0;
        $result->sync = isset($view->sync) ? $view->sync : 0;

        if(isset($view->roadmaps))
        {
            foreach($view->roadmaps as $index => $roadmap)
            {
                $roadmapKey = 'roadmap' . $index;
                if(is_array($roadmap))
                {
                    $result->$roadmapKey = count($roadmap);
                }
            }
        }
        return $result;
    }

    /**
     * Test printScrumTestBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printScrumTestBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printScrumTestBlock', array($block));
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->instance->view;
        $result = new stdClass();
        $result->projectID = (isset($view->project) && $view->project) ? $view->project->id : 0;
        $result->projectName = (isset($view->project) && $view->project) ? $view->project->name : '';
        $result->type = isset($block->params->type) ? $block->params->type : '';
        if(isset($view->testtasks))
        {
            $result->count = count($view->testtasks);
            foreach($view->testtasks as $index => $testtask)
            {
                $result->$index = $testtask;
            }
        }
        else
        {
            $result->count = 0;
        }
        return $result;
    }
}
