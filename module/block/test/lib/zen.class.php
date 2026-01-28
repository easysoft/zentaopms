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
        $this->invokeArgs('printBuildBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
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
        $this->invokeArgs('printCaseBlock', [$block]);
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return ['count' => count($view->cases)];
    }

    /**
     * Test printDocDynamicBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocDynamicBlockTest()
    {
        $this->invokeArgs('printDocDynamicBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return ['actionsCount' => count($view->actions), 'usersCount' => count($view->users)];
    }

    /**
     * Test printDocMyCollectionBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCollectionBlockTest()
    {
        $this->invokeArgs('printDocMyCollectionBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->docList + ['count' => count($view->docList)];
    }

    /**
     * Test printDocCollectListBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocCollectListBlockTest()
    {
        $this->invokeArgs('printDocCollectListBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->docList + ['count' => count($view->docList)];
    }

    /**
     * Test printDocMyCreatedBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocMyCreatedBlockTest()
    {
        $this->invokeArgs('printDocMyCreatedBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->docList + ['count' => count($view->docList)];
    }

    /**
     * Test printDocRecentUpdateBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocRecentUpdateBlockTest()
    {
        $this->invokeArgs('printDocRecentUpdateBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->docList + ['count' => count($view->docList)];
    }

    /**
     * Test printDocViewListBlock method.
     *
     * @access public
     * @return object
     */
    public function printDocViewListBlockTest()
    {
        $this->invokeArgs('printDocViewListBlock');
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->docList + ['count' => count($view->docList)];
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
        $this->invokeArgs('printExecutionListBlock', [$block]);
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->executions + ['count' => count($view->executions)];
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
        $this->invokeArgs('printExecutionStatisticBlock', [$block, $params]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
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
        $this->invokeArgs('printGuideBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printMonthlyProgressBlock method.
     *
     * @access public
     * @return object
     */
    public function printMonthlyProgressBlockTest()
    {
        $this->invokeArgs('printMonthlyProgressBlock');
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->doneStoryEstimateCount = isset($view->doneStoryEstimate) ? count($view->doneStoryEstimate) : 0;
        $result->doneStoryCountCount    = isset($view->doneStoryCount)    ? count($view->doneStoryCount)    : 0;
        $result->createStoryCountCount  = isset($view->createStoryCount)  ? count($view->createStoryCount)  : 0;
        $result->fixedBugCountCount     = isset($view->fixedBugCount)     ? count($view->fixedBugCount)     : 0;
        $result->createBugCountCount    = isset($view->createBugCount)    ? count($view->createBugCount)    : 0;
        $result->totalDataArrays        = $result->doneStoryEstimateCount + $result->doneStoryCountCount + $result->createStoryCountCount + $result->fixedBugCountCount + $result->createBugCountCount;
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
        $this->invokeArgs('printPlanBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
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
        $this->invokeArgs('printProductDocBlock', [$block, $params]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');
        $view->usersCount    = count($view->users);
        $view->productsCount = count($view->products);
        $view->docGroupCount = count($view->docGroup);
        return $view;
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
        $this->invokeArgs('printProductListBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');
        $view->productStatsCount = count($view->productStats);
        $view->usersCount        = count($view->users);
        $view->avatarListCount   = count($view->avatarList);
        return $view;
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
        $this->invokeArgs('printProductStatisticBlock', [$block]);
        if(dao::isError()) return dao::getError();
        $view = $this->getProperty('view');
        return $view->products + ['productsCount' => count($view->products)];
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
        $this->invokeArgs('printProjectOverviewBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

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
                    $result->$groupKey->title     = isset($group->title) ? $group->title : '';
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
        $this->invokeArgs('printProjectStatisticBlock', [$block]);
        ob_get_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->projectCount = isset($view->projects) ? count($view->projects) : 0;
        $result->userCount    = isset($view->users)    ? count($view->users)    : 0;

        if(isset($view->projects))
        {
            foreach($view->projects as $index => $project)
            {
                $projectKey = 'project' . $index;
                $result->$projectKey = new stdClass();
                $result->$projectKey->id     = isset($project->id)     ? $project->id     : 0;
                $result->$projectKey->name   = isset($project->name)   ? $project->name   : '';
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
        $this->invokeArgs('printScrumOverviewBlock');
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->projectID  = isset($view->projectID) ? $view->projectID : 0;
        $result->hasProject = isset($view->project)   ? 1 : 0;

        if(isset($view->project))
        {
            $project = $view->project;
            $result->projectName     = isset($project->name)        ? $project->name              : '';
            $result->projectModel    = isset($project->model)       ? $project->model             : '';
            $result->executionsCount = isset($project->executions)  ? count($project->executions) : 0;
            $result->storyPoints     = isset($project->storyPoints) ? $project->storyPoints       : 0;
            $result->tasks           = isset($project->tasks)       ? $project->tasks             : 0;
            $result->bugs            = isset($project->bugs)        ? $project->bugs              : 0;
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
        $this->invokeArgs('printScrumProductBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productsCount = isset($view->products) ? count($view->products) : 0;
        $result->storiesCount  = isset($view->stories)  ? count($view->stories)  : 0;
        $result->bugsCount     = isset($view->bugs)     ? count($view->bugs)     : 0;
        $result->releasesCount = isset($view->releases) ? count($view->releases) : 0;

        if(isset($view->products))
        {
            foreach($view->products as $productID => $productName)
            {
                $productKey = 'product' . $productID;

                $result->$productKey = new stdClass();
                $result->$productKey->name         = $productName;
                $result->$productKey->storyTotal   = isset($view->stories[$productID])  ? $view->stories[$productID]  : 0;
                $result->$productKey->bugTotal     = isset($view->bugs[$productID])     ? $view->bugs[$productID]     : 0;
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
        $this->invokeArgs('printScrumRoadMapBlock', [$productID, $roadMapID]);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productsCount = isset($view->products)  ? count($view->products) : 0;
        $result->roadmapsCount = isset($view->roadmaps)  ? count($view->roadmaps) : 0;
        $result->productID     = isset($view->productID) ? $view->productID       : 0;
        $result->roadMapID     = isset($view->roadMapID) ? $view->roadMapID       : 0;
        $result->sync          = isset($view->sync)      ? $view->sync            : 0;

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
        $this->invokeArgs('printScrumTestBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->projectID   = (isset($view->project) && $view->project) ? $view->project->id   : 0;
        $result->projectName = (isset($view->project) && $view->project) ? $view->project->name : '';
        $result->type        = isset($block->params->type) ? $block->params->type : '';
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

    /**
     * Test printSingleBugStatisticBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printSingleBugStatisticBlockTest(object $block)
    {
        $this->invokeArgs('printSingleBugStatisticBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productID         = isset($view->productID)      ? $view->productID           : 0;
        $result->totalBugs         = isset($view->totalBugs)      ? $view->totalBugs           : 0;
        $result->closedBugs        = isset($view->closedBugs)     ? $view->closedBugs          : 0;
        $result->unresovledBugs    = isset($view->unresovledBugs) ? $view->unresovledBugs      : 0;
        $result->resolvedRate      = isset($view->resolvedRate)   ? $view->resolvedRate        : 0;
        $result->monthsCount       = isset($view->months)         ? count($view->months)       : 0;
        $result->activateBugsCount = isset($view->activateBugs)   ? count($view->activateBugs) : 0;
        $result->closeBugsCount    = isset($view->closeBugs)      ? count($view->closeBugs)    : 0;
        return $result;
    }

    /**
     * Test printSingleDynamicBlock method.
     *
     * @access public
     * @return object
     */
    public function printSingleDynamicBlockTest()
    {
        ob_start();
        $this->invokeArgs('printSingleDynamicBlock');
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productID    = isset($view->productID) ? $view->productID      : 0;
        $result->actionsCount = isset($view->actions)   ? count($view->actions) : 0;
        $result->usersCount   = isset($view->users)     ? count($view->users)   : 0;

        if(isset($view->actions))
        {
            foreach($view->actions as $index => $action)
            {
                $result->$index = $action;
            }
        }
        return $result;
    }

    /**
     * Test printSingleMonthlyProgressBlock method.
     *
     * @param  int $productID
     * @access public
     * @return object
     */
    public function printSingleMonthlyProgressBlockTest($productID = 1)
    {
        // 设置session中的产品ID
        $this->instance->session->product = $productID;

        // 调用方法
        ob_start();
        $this->invokeArgs('printSingleMonthlyProgressBlock');
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productID = $productID;

        // 获取view中设置的数据
        $result->doneStoryEstimate = isset($view->doneStoryEstimate) ? $view->doneStoryEstimate : [];
        $result->doneStoryCount    = isset($view->doneStoryCount)    ? $view->doneStoryCount    : [];
        $result->createStoryCount  = isset($view->createStoryCount)  ? $view->createStoryCount  : [];
        $result->fixedBugCount     = isset($view->fixedBugCount)     ? $view->fixedBugCount     : [];
        $result->createBugCount    = isset($view->createBugCount)    ? $view->createBugCount    : [];
        $result->releaseCount      = isset($view->releaseCount)      ? $view->releaseCount      : [];

        // 统计数据
        $result->doneStoryEstimateCount = count($result->doneStoryEstimate);
        $result->doneStoryCountCount    = count($result->doneStoryCount);
        $result->createStoryCountCount  = count($result->createStoryCount);
        $result->fixedBugCountCount     = count($result->fixedBugCount);
        $result->createBugCountCount    = count($result->createBugCount);
        $result->releaseCountCount      = count($result->releaseCount);

        return $result;
    }

    /**
     * Test printSinglePlanBlock method.
     *
     * @param  object $block 区块对象
     * @param  int    $productID 产品ID
     * @access public
     * @return object
     */
    public function printSinglePlanBlockTest(object $block, int $productID = 1)
    {
        // 设置session中的产品ID
        $this->instance->session->product = $productID;

        // 调用方法
        ob_start();
        $this->invokeArgs('printSinglePlanBlock', [$block]);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->plansCount    = isset($view->plans)    ? count($view->plans)    : 0;
        $result->productsCount = isset($view->products) ? count($view->products) : 0;

        if(isset($view->plans))
        {
            foreach($view->plans as $index => $plan)
            {
                $result->$index = $plan;
            }
        }

        if(isset($view->products))
        {
            $result->productNames = $view->products;
        }

        return $result;
    }

    /**
     * Test printSingleReleaseBlock method.
     *
     * @param  object $block 区块对象
     * @param  int    $productID 产品ID
     * @access public
     * @return object
     */
    public function printSingleReleaseBlockTest(object $block, int $productID = 1)
    {
        // 设置session中的产品ID
        $this->instance->session->product = $productID;

        // 调用方法
        ob_start();
        $this->invokeArgs('printSingleReleaseBlock', [$block]);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->releasesCount = isset($view->releases) ? count($view->releases) : 0;
        $result->buildsCount   = isset($view->builds)   ? count($view->builds)   : 0;

        if(isset($view->releases))
        {
            foreach($view->releases as $index => $release)
            {
                $result->$index = $release;
            }
        }

        if(isset($view->builds))
        {
            $result->builds = $view->builds;
        }

        return $result;
    }

    /**
     * Test printSingleStatisticBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printSingleStatisticBlockTest(object $block)
    {
        $this->invokeArgs('printSingleStatisticBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->productID         = isset($view->product) && isset($view->product->id)                ? $view->product->id                : 0;
        $result->productName       = isset($view->product) && isset($view->product->name)              ? $view->product->name              : '';
        $result->storyDeliveryRate = isset($view->product) && isset($view->product->storyDeliveryRate) ? $view->product->storyDeliveryRate : 0;
        $result->totalStories      = isset($view->product) && isset($view->product->totalStories)      ? $view->product->totalStories      : 0;
        $result->closedStories     = isset($view->product) && isset($view->product->closedStories)     ? $view->product->closedStories     : 0;
        $result->unclosedStories   = isset($view->product) && isset($view->product->unclosedStories)   ? $view->product->unclosedStories   : 0;
        $result->hasNewPlan        = isset($view->product) && isset($view->product->newPlan)      && $view->product->newPlan      ? 1 : 0;
        $result->hasNewExecution   = isset($view->product) && isset($view->product->newExecution) && $view->product->newExecution ? 1 : 0;
        $result->hasNewRelease     = isset($view->product) && isset($view->product->newRelease)   && $view->product->newRelease   ? 1 : 0;
        $result->monthFinishCount  = isset($view->product) && isset($view->product->monthFinish)  ? count($view->product->monthFinish)  : 0;
        $result->monthCreatedCount = isset($view->product) && isset($view->product->monthCreated) ? count($view->product->monthCreated) : 0;
        return $result;
    }

    /**
     * Test printStoryBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printStoryBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printStoryBlock', [$block]);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->storiesCount = isset($view->stories) ? count($view->stories) : 0;

        if(isset($view->stories))
        {
            foreach($view->stories as $index => $story)
            {
                $result->$index = $story;
            }
        }

        return $result;
    }

    /**
     * Test printSingleStoryBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printSingleStoryBlockTest(object $block)
    {
        ob_start();
        $this->invokeArgs('printSingleStoryBlock', [$block]);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->storiesCount = isset($view->stories) ? count($view->stories) : 0;

        if(isset($view->stories))
        {
            foreach($view->stories as $index => $story)
            {
                $result->$index = $story;
            }
        }

        return $result;
    }

    /**
     * Test printTaskBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printTaskBlockTest(object $block)
    {
        $this->invokeArgs('printTaskBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->tasksCount = isset($view->tasks) ? count($view->tasks) : 0;

        if(isset($view->tasks))
        {
            foreach($view->tasks as $index => $task)
            {
                $result->$index = $task;
            }
        }

        return $result;
    }

    /**
     * Test printTesttaskBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printTesttaskBlockTest(object $block)
    {
        $this->invokeArgs('printTesttaskBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->testtasksCount = isset($view->testtasks) ? count($view->testtasks) : 0;
        $result->type           = $block->params->type  ?? '';
        $result->count          = $block->params->count ?? 0;

        if(isset($view->testtasks))
        {
            foreach($view->testtasks as $index => $testtask)
            {
                $result->$index = $testtask;
            }
        }

        if(isset($view->projects))
        {
            $result->projectsCount = count($view->projects);
        }

        return $result;
    }

    /**
     * Test printTeamAchievementBlock method.
     *
     * @access public
     * @return object
     */
    public function printTeamAchievementBlockTest()
    {
        $this->invokeArgs('printTeamAchievementBlock');
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->finishedTasks    = isset($view->finishedTasks)    ? $view->finishedTasks    : 0;
        $result->yesterdayTasks   = isset($view->yesterdayTasks)   ? $view->yesterdayTasks   : 0;
        $result->createdStories   = isset($view->createdStories)   ? $view->createdStories   : 0;
        $result->yesterdayStories = isset($view->yesterdayStories) ? $view->yesterdayStories : 0;
        $result->closedBugs       = isset($view->closedBugs)       ? $view->closedBugs       : 0;
        $result->yesterdayBugs    = isset($view->yesterdayBugs)    ? $view->yesterdayBugs    : 0;
        $result->runCases         = isset($view->runCases)         ? $view->runCases         : 0;
        $result->yesterdayCases   = isset($view->yesterdayCases)   ? $view->yesterdayCases   : 0;
        $result->consumedHours    = isset($view->consumedHours)    ? $view->consumedHours    : 0;
        $result->yesterdayHours   = isset($view->yesterdayHours)   ? $view->yesterdayHours   : 0;

        return $result;
    }

    /**
     * Test printWaterfallEstimateBlock method.
     *
     * @access public
     * @return object
     */
    public function printWaterfallEstimateBlockTest()
    {
        $this->invokeArgs('printWaterfallEstimateBlock');
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->people    = isset($view->people)    ? $view->people    : 0;
        $result->members   = isset($view->members)   ? $view->members   : 0;
        $result->consumed  = isset($view->consumed)  ? $view->consumed  : 0;
        $result->totalLeft = isset($view->totalLeft) ? $view->totalLeft : 0;
        $result->hasBudget = isset($view->budget)    ? 1 : 0;

        if(isset($view->budget))
        {
            $result->budgetScale          = isset($view->budget->scale)          ? $view->budget->scale          : 0;
            $result->budgetProductivity   = isset($view->budget->productivity)   ? $view->budget->productivity   : 0;
            $result->budgetDuration       = isset($view->budget->duration)       ? $view->budget->duration       : 0;
            $result->budgetUnitLaborCost  = isset($view->budget->unitLaborCost)  ? $view->budget->unitLaborCost  : 0;
            $result->budgetTotalLaborCost = isset($view->budget->totalLaborCost) ? $view->budget->totalLaborCost : 0;
        }

        return $result;
    }

    /**
     * Test printWaterfallGeneralReportBlock method.
     *
     * @access public
     * @return object
     */
    public function printWaterfallGeneralReportBlockTest()
    {
        $this->invokeArgs('printWaterfallGeneralReportBlock');
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->pv       = isset($view->pv)       ? $view->pv       : 0;
        $result->ev       = isset($view->ev)       ? $view->ev       : 0;
        $result->ac       = isset($view->ac)       ? $view->ac       : 0;
        $result->sv       = isset($view->sv)       ? $view->sv       : 0;
        $result->cv       = isset($view->cv)       ? $view->cv       : 0;
        $result->progress = isset($view->progress) ? $view->progress : 0;

        return $result;
    }

    /**
     * Test printWaterfallProgressBlock method.
     *
     * @param  int $projectID 项目ID
     * @access public
     * @return object
     */
    public function printWaterfallProgressBlockTest(int $projectID = 1)
    {
        $this->instance->session->project = $projectID;

        $this->invokeArgs('printWaterfallProgressBlock');
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        $result->hasCharts = isset($view->charts)       ? 1 : 0;
        $result->hasPV     = isset($view->charts['pv']) ? 1 : 0;
        $result->hasEV     = isset($view->charts['ev']) ? 1 : 0;
        $result->hasAC     = isset($view->charts['ac']) ? 1 : 0;
        $result->pvCount   = isset($view->charts['pv']) ? count($view->charts['pv']) : 0;
        $result->evCount   = isset($view->charts['ev']) ? count($view->charts['ev']) : 0;
        $result->acCount   = isset($view->charts['ac']) ? count($view->charts['ac']) : 0;

        return $result;
    }

    /**
     * Test printWaterfallRiskBlock method.
     *
     * @param  object $block 区块对象
     * @access public
     * @return object
     */
    public function printWaterfallRiskBlockTest(object $block)
    {
        $this->instance->session->project = $block->params->projectID ?? 1;

        $this->invokeArgs('printWaterfallRiskBlock', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        $result = new stdClass();
        if(isset($view->risks))
        {
            $result->count = count($view->risks);

            $index = 1;
            foreach($view->risks as $risk)
            {
                $result->$index = $risk;
                $index++;
            }
        }
        else
        {
            $result->count = 0;
        }

        $result->hasUsers   = isset($view->users) ? 1 : 0;
        $result->usersCount = isset($view->users) ? count($view->users) : 0;

        return $result;
    }

    /**
     * Test processBlockForRender method.
     *
     * @param  array $blocks    区块列表
     * @param  int   $projectID 项目ID
     * @access public
     * @return array
     */
    public function processBlockForRenderTest(array $blocks, int $projectID = 0)
    {
        $result = $this->invokeArgs('processBlockForRender', [$blocks, $projectID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProjectStatistic method.
     *
     * @param  object $project
     * @param  array  $data
     * @param  object $pager
     * @access public
     * @return object
     */
    public function buildProjectStatisticTest($project, $data, $pager = null)
    {
        $result = $this->invokeArgs('buildProjectStatistic', [$project, $data, $pager]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAvailableCodes method in zen layer.
     *
     * @param  string $module
     * @access public
     * @return array|bool
     */
    public function getAvailableCodesTest(string $module)
    {
        $result = $this->invokeArgs('getAvailableCodes', [$module]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAvailableModules method in zen layer.
     *
     * @param  string $dashboard
     * @access public
     * @return array
     */
    public function getAvailableModulesTest(string $dashboard)
    {
        $result = $this->invokeArgs('getAvailableModules', [$dashboard]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAvailableParams method in zen layer.
     *
     * @param  string $module
     * @param  string $code
     * @access public
     * @return array
     */
    public function getAvailableParamsTest(string $module, string $code)
    {
        $this->invokeArgs('getAvailableParams', [$module, $code]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBlockTitle method in zen layer.
     *
     * @param  array  $modules
     * @param  string $module
     * @param  array  $codes
     * @param  string $code
     * @param  array  $params
     * @access public
     * @return string
     */
    public function getBlockTitleTest(array $modules, string $module, array $codes, string $code, array $params)
    {
        $result = $this->invokeArgs('getBlockTitle', [$modules, $module, $codes, $code, $params]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProjectsStatisticData method.
     *
     * @param  array $projectIdList
     * @access public
     * @return array
     */
    public function getProjectsStatisticDataTest($projectIdList = array())
    {
        $result = $this->invokeArgs('getProjectsStatisticData', [$projectIdList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initBlock method in zen layer.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function zenInitBlockTest(string $dashboard)
    {
        $result = $this->invokeArgs('initBlock', [$dashboard]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isExternalCall method.
     *
     * @access public
     * @return bool
     */
    public function isExternalCallTest()
    {
        $result = $this->invokeArgs('isExternalCall');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test organizaExternalData method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function organizaExternalDataTest(?object $block = null)
    {
        $this->invokeArgs('organizaExternalData', [$block]);
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        // 返回用户信息和视图数据
        $result = new stdclass();
        $result->user = $this->instance->app->user ?? null;
        $result->sso  = $view->sso  ?? '';
        $result->sign = $view->sign ?? '';

        return $result;
    }

    /**
     * Test printAssignToMeBlock method.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printAssignToMeBlockTest($block = null)
    {
        $this->invokeArgs('printAssignToMeBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printBlock4Json method.
     *
     * @access public
     * @return object
     */
    public function printBlock4JsonTest()
    {
        $this->invokeArgs('printBlock4Json');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printDocStatisticBlock method in zen layer.
     *
     * @access public
     * @return object
     */
    public function printDocStatisticBlockTest()
    {
        $this->invokeArgs('printDocStatisticBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printDynamicBlock method in zen layer.
     *
     * @access public
     * @return object
     */
    public function printDynamicBlockTest()
    {
        $this->invokeArgs('printDynamicBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printExecutionOverviewBlock method in zen layer.
     *
     * @param  object $block
     * @param  array  $params
     * @param  string $code
     * @param  int    $project
     * @param  bool   $showClosed
     * @access public
     * @return object
     */
    public function printExecutionOverviewBlockTest($block = null, $params = array(), $code = 'executionoverview', $project = 0, $showClosed = false)
    {
        $this->invokeArgs('printExecutionOverviewBlock', [$block, $params, $code, $project, $showClosed]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printLongProductOverview method.
     *
     * @param  array $params
     * @access public
     * @return object
     */
    public function printLongProductOverviewTest($params = array())
    {
        $this->invokeArgs('printLongProductOverview', [$params]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printProductOverviewBlock method.
     *
     * @param  object $block
     * @param  array  $params
     * @access public
     * @return object
     */
    public function printProductOverviewBlockTest($block, $params = array())
    {
        $this->invokeArgs('printProductOverviewBlock', [$block, $params]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printProjectBlock method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printProjectBlockTest(object $block)
    {
        $this->invokeArgs('printProjectBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printProjectDocBlock method in zen layer.
     *
     * @param  object $block
     * @param  array  $params
     * @access public
     * @return object
     */
    public function printProjectDocBlockTest($block = null, $params = array())
    {
        $this->invokeArgs('printProjectDocBlock', [$block, $params]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printProjectDynamicBlock method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printProjectDynamicBlockTest(?object $block = null)
    {
        $this->invokeArgs('printProjectDynamicBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printProjectTeamBlock method.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printProjectTeamBlockTest($block = null)
    {
        $this->invokeArgs('printProjectTeamBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printQaOverviewBlock method.
     *
     * @param  object $block
     * @param  bool   $clearData
     * @access public
     * @return array
     */
    public function printQaOverviewBlockTest($block, $clearData = false)
    {
        $this->invokeArgs('printQaOverviewBlock', [$block, $clearData]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printQaStatisticBlock method.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printQaStatisticBlockTest($block)
    {
        $this->invokeArgs('printQaStatisticBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printRecentProjectBlock method.
     *
     * @access public
     * @return object
     */
    public function printRecentProjectBlockTest()
    {
        $this->invokeArgs('printRecentProjectBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printReleaseBlock method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printReleaseBlockTest($block)
    {
        $this->invokeArgs('printReleaseBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printReleaseStatisticBlock method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printReleaseStatisticBlockTest(object $block)
    {
        $this->invokeArgs('printReleaseStatisticBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printRoadmapBlock method in zen layer.
     *
     * @param  object $block
     * @access public
     * @return object
     */
    public function printRoadmapBlockTest(object $block)
    {
        $this->invokeArgs('printRoadmapBlock', [$block]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printShortProductOverview method.
     *
     * @access public
     * @return object
     */
    public function printShortProductOverviewTest()
    {
        $this->invokeArgs('printShortProductOverview');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printWaterfallGanttBlock method in zen layer.
     *
     * @param  object $block
     * @param  array  $params
     * @access public
     * @return object
     */
    public function printWaterfallGanttBlockTest(object $block, array $params = array())
    {
        $this->invokeArgs('printWaterfallGanttBlock', [$block, $params]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printWaterfallReportBlock method in zen layer.
     *
     * @access public
     * @return object
     */
    public function printWaterfallReportBlockTest()
    {
        $this->invokeArgs('printWaterfallReportBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printWelcomeBlock method in zen layer.
     *
     * @access public
     * @return object
     */
    public function printWelcomeBlockTest()
    {
        $this->invokeArgs('printWelcomeBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test printZentaoDynamicBlock method in zen layer.
     *
     * @access public
     * @return object
     */
    public function printZentaoDynamicBlockTest()
    {
        $this->invokeArgs('printZentaoDynamicBlock');
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }
}
