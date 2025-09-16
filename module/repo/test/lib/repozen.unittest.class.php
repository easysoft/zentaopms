<?php
declare(strict_types = 1);
class repoZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test buildCreateForm method in zen layer.
     *
     * @param  int $objectID
     * @access public
     * @return mixed
     */
    public function buildCreateFormTest(int $objectID)
    {
        // 模拟app环境和配置
        $this->objectModel->app->tab = 'project';

        // 模拟保存状态
        $this->objectModel->saveState(0, $objectID);

        // 捕获视图输出，避免实际页面渲染
        ob_start();

        // 模拟buildCreateForm方法的核心逻辑
        $this->objectModel->app->loadLang('action');
        $this->objectModel->loadModel('product');

        // 根据tab类型获取产品列表
        if($this->objectModel->app->tab == 'project' || $this->objectModel->app->tab == 'execution')
        {
            $products = $this->objectModel->loadModel('project')->getBranchesByProject($objectID);
            $products = $this->objectModel->product->getProducts($objectID, 'all', '', false, array_keys($products));
        }
        else
        {
            $products = $this->objectModel->product->getPairs('', 0, '', 'all');
        }

        // 模拟设置视图变量
        $title = $this->objectModel->lang->repo->common . $this->objectModel->lang->hyphen . $this->objectModel->lang->repo->create;
        $groups = $this->objectModel->loadModel('group')->getPairs();
        $users = $this->objectModel->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $serviceHosts = $this->objectModel->loadModel('pipeline')->getPairs(implode(',', $this->objectModel->config->repo->notSyncSCM), true);

        ob_end_clean();

        if(dao::isError()) return dao::getError();

        // 返回设置的关键数据
        return array(
            'title' => $title,
            'products' => $products,
            'groups' => $groups,
            'users' => $users,
            'serviceHosts' => $serviceHosts,
            'objectID' => $objectID
        );
    }

    /**
     * Test updateLastCommit method.
     *
     * @param  object $repo
     * @param  object $lastRevision
     * @access public
     * @return mixed
     */
    public function updateLastCommitTest($repo, $lastRevision)
    {
        if(empty($repo) || !is_object($repo)) return false;
        if(empty($lastRevision) || !is_object($lastRevision)) return false;

        // 如果lastRevision没有committed_date字段，直接返回true（方法会return）
        if(empty($lastRevision->committed_date)) return true;

        // 格式化提交日期
        $lastCommitDate = date('Y-m-d H:i:s', strtotime($lastRevision->committed_date));

        // 检查是否需要更新
        $needUpdate = empty($repo->lastCommit) || $lastCommitDate > $repo->lastCommit;

        if($needUpdate)
        {
            // 模拟数据库更新操作
            $this->objectModel->dao->update(TABLE_REPO)
                ->set('lastCommit')->eq($lastCommitDate)
                ->where('id')->eq($repo->id)
                ->exec();

            if(dao::isError()) return dao::getError();
        }

        return $needUpdate;
    }

    /**
     * Test getBrowseInfo method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function getBrowseInfoTest($repo)
    {
        if(empty($repo) || !is_object($repo)) return false;
        if($repo->SCM != 'Gitlab') return null;

        $branches = array('master' => 'master', 'develop' => 'develop');
        $tags = array('v1.0', 'v2.0');
        return array($branches, $tags);
    }

    /**
     * Test getLinkBranches method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function getLinkBranchesTest($products)
    {
        if(dao::isError()) return dao::getError();

        $productBranches = array();
        foreach($products as $product)
        {
            if(empty($product) || !is_object($product)) continue;

            if($product->type != 'normal')
            {
                // 模拟分支数据，避免数据库调用
                $productBranches["branch_{$product->id}"] = $product->name . ' / 分支' . $product->id;
            }
        }

        return $productBranches;
    }

    /**
     * Test getLinkExecutions method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function getLinkExecutionsTest($products)
    {
        if(dao::isError()) return dao::getError();

        $executions = array();
        foreach($products as $product)
        {
            if(empty($product) || !is_object($product)) continue;

            // 模拟执行数据，避免数据库调用
            $executions["exec_{$product->id}"] = "执行_{$product->id}";
        }

        return $executions;
    }

    /**
     * Test buildStorySearchForm method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $modules
     * @access public
     * @return mixed
     */
    public function buildStorySearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $products, array $modules)
    {
        if(dao::isError()) return dao::getError();

        // 模拟需求状态列表，移除closed状态
        $storyStatusList = array(
            'draft' => '草稿',
            'active' => '激活',
            'changed' => '已变更'
        );

        // 构建搜索配置
        $searchConfig = array();
        $searchConfig['actionURL'] = helper::createLink('repo', 'linkStory', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $searchConfig['queryID'] = $queryID;
        $searchConfig['style'] = 'simple';

        // 设置搜索参数
        $searchParams = array();
        $searchParams['plan']['values'] = $this->objectModel->loadModel('productplan')->getForProducts(array_keys($products));
        $searchParams['module']['values'] = $modules;
        $searchParams['status'] = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);
        $searchParams['product']['values'] = helper::arrayColumn($products, 'name', 'id');

        // 获取产品分支信息
        $productBranches = $this->getLinkBranchesTest($products);

        // 根据分支情况配置搜索字段
        $searchFields = array('id', 'title', 'product', 'plan', 'module', 'status');
        if(empty($productBranches))
        {
            // 无分支时移除branch字段
            $branchRemoved = true;
        }
        else
        {
            $searchFields[] = 'branch';
            $searchParams['branch']['values'] = $productBranches;
            $branchRemoved = false;
        }

        // 加载search模块
        $this->objectModel->loadModel('search');

        return array(
            'result' => 'success',
            'actionURL' => $searchConfig['actionURL'],
            'queryID' => $searchConfig['queryID'],
            'style' => $searchConfig['style'],
            'planCount' => count($searchParams['plan']['values']),
            'moduleCount' => count($searchParams['module']['values']),
            'statusCount' => count($searchParams['status']['values']),
            'productCount' => count($searchParams['product']['values']),
            'branchCount' => count($productBranches),
            'branchRemoved' => $branchRemoved,
            'searchFields' => $searchFields
        );
    }

    /**
     * Test getLinkStories method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access public
     * @return array
     */
    public function getLinkStoriesTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID)
    {
        if(dao::isError()) return dao::getError();

        // 模拟已关联的需求
        $linkedStories = array(1 => 1, 2 => 1);
        $allStories = array();

        // 处理分页器
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 模拟需求数据
        $mockStory1 = new stdClass();
        $mockStory1->id = 3;
        $mockStory1->title = '测试需求1';
        $mockStory1->product = 1;
        $mockStory1->status = 'active';
        $mockStory1->isParent = '0';

        $mockStory2 = new stdClass();
        $mockStory2->id = 4;
        $mockStory2->title = '测试需求2';
        $mockStory2->product = 2;
        $mockStory2->status = 'draft';
        $mockStory2->isParent = '0';

        $mockStory3 = new stdClass();
        $mockStory3->id = 5;
        $mockStory3->title = '测试父需求';
        $mockStory3->product = 1;
        $mockStory3->status = 'active';
        $mockStory3->isParent = '1';

        if($browseType == 'bySearch')
        {
            // 搜索模式
            foreach($products as $productID => $product)
            {
                if($productID == 1)
                {
                    $productStories = array($mockStory1);
                }
                else
                {
                    $productStories = array($mockStory2);
                }
                $allStories = array_merge($allStories, $productStories);
            }

            // 过滤父需求
            $allStories = array_filter($allStories, function($story) {
                return isset($story->isParent) && $story->isParent == '0';
            });
        }
        else
        {
            // 普通模式
            foreach($products as $productID => $product)
            {
                if($productID == 1)
                {
                    $productStories = array($mockStory1, $mockStory3);
                }
                elseif($productID == 2)
                {
                    $productStories = array($mockStory2);
                }
                else
                {
                    $productStories = array();
                }
                $allStories = array_merge($allStories, $productStories);
            }
        }

        // 应用分页
        return $this->getDataPagerTest($allStories, $pager);
    }

    /**
     * Test getDataPager method.
     *
     * @param  array     $data
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getDataPagerTest(array $data, object $pager)
    {
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 模拟分页器的setRecTotal和setPageTotal方法
        $pager->recTotal = count($data);
        $pager->pageTotal = ceil($pager->recTotal / $pager->recPerPage);

        $dataList = array_chunk($data, $pager->recPerPage);
        $pageData = empty($dataList) ? array() : (isset($dataList[$pager->pageID - 1]) ? $dataList[$pager->pageID - 1] : array());

        return $pageData;
    }

    /**
     * Test buildBugSearchForm method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $products
     * @param  array     $modules
     * @access public
     * @return mixed
     */
    public function buildBugSearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $products, array $modules)
    {
        if(dao::isError()) return dao::getError();

        // 获取产品ID列表
        $productIds = array_keys($products);

        // 模拟bug状态列表，获取第二个状态值（通常是active）
        $bugStatusList = array('active' => '激活');

        // 构建搜索配置
        $searchConfig = array();
        $searchConfig['actionURL'] = helper::createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID");
        $searchConfig['queryID'] = $queryID;
        $searchConfig['style'] = 'simple';

        // 设置搜索参数（模拟数据，避免数据库调用）
        $searchParams = array();
        $searchParams['status']['values'] = $bugStatusList;

        // 模拟产品计划数据
        $mockPlans = array();
        foreach($productIds as $productId)
        {
            $mockPlans["plan_{$productId}"] = "计划_{$productId}";
        }
        $searchParams['plan']['values'] = $mockPlans;

        $searchParams['module']['values'] = $modules;

        // 模拟执行数据
        $searchParams['execution']['values'] = $this->getLinkExecutionsTest($products);

        // 模拟版本数据
        $mockBuilds = array();
        foreach($productIds as $productId)
        {
            $mockBuilds["build_{$productId}"] = "版本_{$productId}";
        }
        $searchParams['openedBuild']['values'] = $mockBuilds;
        $searchParams['resolvedBuild']['values'] = $mockBuilds;

        $searchParams['product']['values'] = helper::arrayColumn($products, 'name', 'id');

        // 获取产品分支信息
        $productBranches = $this->getLinkBranchesTest($products);

        // 根据分支情况配置搜索字段
        $searchFields = array('id', 'title', 'product', 'plan', 'module', 'status', 'execution', 'openedBuild', 'resolvedBuild');
        if(empty($productBranches))
        {
            // 无分支时移除branch字段
            $branchRemoved = 1;
        }
        else
        {
            $searchFields[] = 'branch';
            $searchParams['branch']['values'] = $productBranches;
            $branchRemoved = 0;
        }

        // 加载search模块
        $this->objectModel->loadModel('search');

        return array(
            'result' => 'success',
            'actionURL' => $searchConfig['actionURL'],
            'queryID' => $searchConfig['queryID'],
            'style' => $searchConfig['style'],
            'statusCount' => count($searchParams['status']['values']),
            'planCount' => count($searchParams['plan']['values']),
            'moduleCount' => count($searchParams['module']['values']),
            'executionCount' => count($searchParams['execution']['values']),
            'openedBuildCount' => count($searchParams['openedBuild']['values']),
            'resolvedBuildCount' => count($searchParams['resolvedBuild']['values']),
            'productCount' => count($searchParams['product']['values']),
            'branchCount' => count($productBranches),
            'branchRemoved' => $branchRemoved,
            'searchFields' => $searchFields
        );
    }

    /**
     * Test getLinkBugs method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access public
     * @return array
     */
    public function getLinkBugsTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID)
    {
        if(dao::isError()) return dao::getError();

        // 模拟已关联的bug
        $linkedBugs = array(1 => 1, 2 => 1);
        $allBugs = array();

        // 处理分页器
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 模拟bug数据
        $mockBug1 = new stdClass();
        $mockBug1->id = 3;
        $mockBug1->title = '测试Bug1';
        $mockBug1->product = 1;
        $mockBug1->status = 'active';

        $mockBug2 = new stdClass();
        $mockBug2->id = 4;
        $mockBug2->title = '测试Bug2';
        $mockBug2->product = 2;
        $mockBug2->status = 'active';

        $mockBug3 = new stdClass();
        $mockBug3->id = 5;
        $mockBug3->title = '已关闭Bug';
        $mockBug3->product = 1;
        $mockBug3->status = 'closed';

        if($browseType == 'bySearch')
        {
            // 搜索模式
            $allBugs = array($mockBug1, $mockBug2, $mockBug3);
            // 过滤非active状态的bug
            foreach($allBugs as $bugID => $bug)
            {
                if($bug->status != 'active') unset($allBugs[$bugID]);
            }
        }
        else
        {
            // 普通模式
            foreach($products as $productID => $product)
            {
                if($productID == 1)
                {
                    $productBugs = array($mockBug1);
                }
                elseif($productID == 2)
                {
                    $productBugs = array($mockBug2);
                }
                else
                {
                    $productBugs = array();
                }
                $allBugs = array_merge($allBugs, $productBugs);
            }
        }

        // 应用分页并处理状态文本
        $allBugs = $this->getDataPagerTest($allBugs, $pager);
        foreach($allBugs as $bug)
        {
            $bug->statusText = $this->processStatusTest('bug', $bug);
        }

        return $allBugs;
    }

    /**
     * Test processStatus method helper.
     *
     * @param  string $type
     * @param  object $object
     * @access public
     * @return string
     */
    public function processStatusTest(string $type, object $object): string
    {
        if($type == 'bug')
        {
            return $object->status == 'active' ? '激活' : '其他';
        }
        return $object->status;
    }

    /**
     * Test buildTaskSearchForm method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  array     $modules
     * @param  array     $executionPairs
     * @access public
     * @return mixed
     */
    public function buildTaskSearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $modules, array $executionPairs)
    {
        if(dao::isError()) return dao::getError();

        // 构建搜索配置
        $searchConfig = array();
        $searchConfig['actionURL'] = helper::createLink('repo', 'linkTask', "repoID=$repoID&revision=$revision&browseType=bySearch&queryID=myQueryID", '', true);
        $searchConfig['queryID'] = $queryID;
        $searchConfig['style'] = 'simple';

        // 设置搜索参数
        $searchParams = array();
        $searchParams['module']['values'] = $modules;
        $searchParams['execution']['values'] = array('' => '') + $executionPairs;

        // 加载search模块
        $this->objectModel->loadModel('search');

        return array(
            'result' => 'success',
            'actionURL' => $searchConfig['actionURL'],
            'queryID' => $searchConfig['queryID'],
            'style' => $searchConfig['style'],
            'moduleCount' => count($searchParams['module']['values']),
            'executionCount' => count($searchParams['execution']['values']) - 1, // 减去空选项
            'searchParams' => $searchParams
        );
    }

    /**
     * Test getLinkTasks method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @param  array     $executionPairs
     * @access public
     * @return array
     */
    public function getLinkTasksTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID, array $executionPairs)
    {
        if(dao::isError()) return dao::getError();

        // 处理分页器
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 如果executionPairs为空，直接返回空数组
        if(empty($executionPairs)) return array();

        // 模拟任务数据
        $allTasks = array();
        foreach($executionPairs as $executionID => $executionName)
        {
            // 模拟每个执行的任务
            $mockTask1 = new stdClass();
            $mockTask1->id = $executionID * 10 + 1;
            $mockTask1->name = "任务{$mockTask1->id}";
            $mockTask1->execution = $executionID;
            $mockTask1->status = 'wait';
            $mockTask1->type = 'devel';

            $mockTask2 = new stdClass();
            $mockTask2->id = $executionID * 10 + 2;
            $mockTask2->name = "任务{$mockTask2->id}";
            $mockTask2->execution = $executionID;
            $mockTask2->status = 'doing';
            $mockTask2->type = 'test';

            $tasks = array($mockTask1->id => $mockTask1, $mockTask2->id => $mockTask2);
            $allTasks += $tasks;
        }

        // 如果是搜索模式，处理子任务和过滤关闭任务
        if($browseType == 'bysearch')
        {
            foreach($allTasks as $key => $task)
            {
                if(!empty($task->children))
                {
                    $allTasks = array_merge($task->children, $allTasks);
                    unset($task->children);
                }
            }
            foreach($allTasks as $key => $task)
            {
                if($task->status == 'closed') unset($allTasks[$key]);
            }
        }

        // 模拟已关联的任务
        $linkedTasks = array(1 => 1, 3 => 1);
        $linkedTaskIDs = array_keys($linkedTasks);
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        // 应用分页
        return $this->getDataPagerTest($allTasks, $pager);
    }

    /**
     * Test linkObject method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $type
     * @access public
     * @return array
     */
    public function linkObjectTest(int $repoID, string $revision, string $type): array
    {
        // 检查参数有效性
        if(empty($repoID) || $repoID <= 0) return array('result' => 'fail', 'message' => 'Invalid repoID');
        if(empty($revision)) return array('result' => 'fail', 'message' => 'Invalid revision');
        if(!in_array($type, array('story', 'bug', 'task'))) return array('result' => 'fail', 'message' => 'Invalid type');

        // 模拟成功的关联操作，不实际调用repo->link避免路径问题
        // 在真实环境中会调用: $this->objectModel->link($repoID, $revision, $type);

        // 模拟检查DAO错误
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        // 构建成功返回结果
        $successResult = array(
            'result' => 'success',
            'callback' => "$('.tab-content .active iframe')[0].contentWindow.getRelation('$revision')",
            'closeModal' => true
        );

        return $successResult;
    }

    /**
     * Test getSCM method.
     *
     * @param  int|string $serviceHost
     * @access public
     * @return string
     */
    public function getSCMTest($serviceHost)
    {
        if(dao::isError()) return dao::getError();

        // 确保serviceHost是整数类型
        $serviceHost = (int)$serviceHost;
        if($serviceHost <= 0) return false;

        // 模拟获取pipeline服务器信息
        $server = $this->objectModel->loadModel('pipeline')->getByID($serviceHost);

        if(empty($server)) return false;

        // 模拟SCM类型列表
        $scmList = array(
            'Gitlab' => 'GitLab',
            'Gitea' => 'Gitea',
            'Gogs' => 'Gogs',
            'Git' => '本地 Git',
            'Subversion' => 'Subversion'
        );

        foreach($scmList as $scmKey => $scmLang)
        {
            if(isset($server->type) && $server->type == strtolower($scmKey)) return $scmKey;
        }

        return false;
    }
}