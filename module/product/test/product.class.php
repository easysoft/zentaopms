<?php
class productTest
{
    /**
     * @var productModel
     * @access private
     */
    public productModel $objectModel;

    /**
     * __construct
     *
     * @param  mixed  $user
     * @access public
     * @return void
     */
    public function __construct($user = 'admin')
    {
        global $tester, $app, $config, $lang;
        $app->loadLang('custom');
        $lang->hourCommon = $lang->custom->conceptOptions->hourPoint['0'];
        $lang->SRCommon   = '研发需求';
        $lang->URCommon   = '用户需求';

        $app->rawModule  = 'product';
        $app->moduleName = 'product';

        su($user);
        $this->objectModel = $tester->loadModel('product');
        $this->objectModel->app->user->admin            = true;
        $this->objectModel->config->global->syncProduct = '';
        $tester->app->loadClass('dao');
    }

    /**
     * Test create a product.
     *
     * @param  array   $param
     * @param  string  $lineName
     * @access public
     * @return object|array
     */
    public function createObject(array $param = array(), string $lineName = ''): object|array
    {
        $createFields = array();
        $createFields['program']        = 1;
        $createFields['line']           = 0;
        $createFields['name']           = '';
        $createFields['code']           = '';
        $createFields['PO']             = 'admin';
        $createFields['QD']             = '';
        $createFields['RD']             = '';
        $createFields['reviewer']       = '';
        $createFields['type']           = 'normal';
        $createFields['status']         = 'normal';
        $createFields['desc']           = '';
        $createFields['acl']            = 'open';
        $createFields['whitelist']      = '';
        $createFields['subStatus']      = '';
        $createFields['PMT']            = '';
        $createFields['createdBy']      = $this->objectModel->app->user->account;
        $createFields['createdDate']    = helper::now();
        $createFields['createdVersion'] = $this->objectModel->config->version;

        $data = new stdclass();
        foreach($createFields as $field => $defaultValue) $data->$field = zget($param, $field, $defaultValue);

        $objectID = $this->objectModel->create($data, $lineName);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->objectModel->getByID($objectID);
            return $object;
        }
    }

    /**
     * Test get the latest project of the product.
     *
     * @param  mixed  $productID
     * @access public
     * @return object
     */
    public function testGetLatestProject($productID)
    {
        $project = $this->objectModel->getLatestProject($productID);
        if($project == false) return '没有数据';
        return $project;
    }

    /**
     * Test get all products.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getAllProducts($programID)
    {
        return $this->objectModel->getList($programID);
    }

    /**
     * Test get all products count.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getAllProductsCount($programID)
    {
        return count($this->getAllProducts($programID));
    }

    /**
     * Test get noclosed products.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getNoclosedProducts($programID)
    {
        return $this->objectModel->getList($programID, 'noclosed');
    }

    /**
     * Test get noclosed products count.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getNoclosedProductsCount($programID)
    {
        return count($this->getNoclosedProducts($programID));
    }

    /**
     * Test get closed products.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getClosedProducts($programID)
    {
        return $this->objectModel->getList($programID, 'closed');
    }

    /**
     * Test get closed products count.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getClosedProductsCount($programID)
    {
        return count($this->getClosedProducts($programID));
    }

    /**
     * Test get involved products.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getInvolvedProducts($programID)
    {
        return $this->objectModel->getList($programID, 'involved');
    }

    /**
     * Test get involved products count.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getInvolvedProductsCount($programID)
    {
        return count($this->getInvolvedProducts($programID));
    }

    /**
     * Test get products by line.
     *
     * @param  int    $programID
     * @param  int    $line
     * @access public
     * @return array
     */
    public function getProductsByLine($programID, $line = 0)
    {
        return $this->objectModel->getList($programID, 'all', 0, $line);
    }

    /**
     * Test get products count by line.
     *
     * @param  int    $programID
     * @param  int    $line
     * @access public
     * @return int
     */
    public function countProductsByLine($programID, $line = 0)
    {
        return count($this->getProductsByLine($programID, $line));
    }

    /**
     * Test get product count.
     *
     * @param  int    $programID
     * @param  string $status
     * @param  int    $line
     * @access public
     * @return int
     */
    public function getProductCount($programID, $status = 'all', $line = 0)
    {
        return count($this->getProductList($programID, $status, $line));
    }

    /**
     * Test get product pairs.
     *
     * @param  string        $mode
     * @param  int           $programID
     * @param  string|array  $append
     * @param  string|int    $shadow
     * @access public
     * @return int[]
     */
    public function getProductPairs(string $mode = '', int $programID = 0, string|array $append = '', string|int $shadow = 0): array
    {
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('deleted')->eq(1)->orderBy('id_desc')->limit(5)->exec(); /* 将最后五个产品设置为影子产品。 */
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('shadow')->eq(1)->where('id')->ge(18)->andWhere('id')->lt(23)->exec(); /* 将第18到22个产品设置为影子产品。*/
        $pairs = $this->objectModel->getPairs($mode, $programID, $append, $shadow);
        return $pairs;
    }

    /**
     * Test fetch product pairs.
     *
     * @param  string        $mode
     * @param  int           $programID
     * @param  string|array  $append
     * @param  string|int    $shadow
     * @access public
     * @return array
     */
    public function fetchPairsTest(string $mode = '', int $programID = 0, string|array $append = '', string|int $shadow = 0): array|string
    {
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('deleted')->eq(1)->orderBy('id_desc')->limit(5)->exec();
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('shadow')->eq(1)->where('id')->ge(18)->andWhere('id')->lt(23)->exec();
        $pairs = $this->objectModel->fetchPairs($mode, $programID, $append, $shadow);
        return $pairs;
    }

    /**
     * Test get all projects by product.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getAllProjectsByProduct($productID)
    {
        $projects = $this->objectModel->getProjectListByProduct($productID, 'all');
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * Test get projects by status.
     *
     * @param  int    $productID
     * @param  string $status
     * @access public
     * @return array
     */
    public function getProjectsByStatus($productID, $status)
    {
        $projects = $this->objectModel->getProjectListByProduct($productID, $browseType);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * Test get project pairs by product id.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getProjectPairsByProductID($productID)
    {
        $projects = $this->objectModel->getProjectPairsByProduct($productID, 0, 0);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * Test get append project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getAppendProject($projectID)
    {
        $project = $this->objectModel->getProjectPairsByProduct(10086, 0, $projectID);
        if($project == array()) return '没有数据';
        return $project;
    }

    /**
     * Test for judge a action is clickable.
     *
     * @param  int    $productID
     * @param  string $status
     * @access public
     * @return bool
     */
    public function testIsClickable(int $productID, string $status): bool
    {
        $product = $this->objectModel->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        return $this->objectModel->isClickable($product, $status);
    }

    /**
     * Test update a product.
     *
     * @param  int    $objectID
     * @param  array  $data
     * @access public
     * @return array|string
     */
    public function updateObject(int $objectID, array $data = array()): array|string
    {
        global $tester;
        $objectModel = $tester->loadModel('product');

        $oldProduct = $objectModel->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($objectID)->fetch();

        $newProduct = clone $oldProduct;
        foreach($data as $field => $value) $newProduct->$field = $value;
        foreach($newProduct as $field => $value) if(strpos($field, 'Date') && empty($data[$field])) unset($newProduct->$field);
        $change = $objectModel->update($objectID, $newProduct);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
    }

    /**
     * Test check privilege.
     *
     * @param  int    $productID
     * @access public
     * @return bool
     */
    public function checkPrivTest(int $productID): bool
    {
        return $this->objectModel->checkPriv($productID);
    }

    /**
     * Test get product by id.
     *
     * @param  int    $productID
     * @access public
     * @return object|array
     */
    public function getByIdTest(int $productID): object|array|false
    {
        $object = $this->objectModel->getById($productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test get product by id list.
     *
     * @param  array  $productIDList
     * @access public
     * @return object[]|string
     */
    public function getByIdListTest(array $productIDList): array|string
    {
        $products = $this->objectModel->getByIdList($productIDList);
        if(dao::isError()) return dao::getError();

        return $products;
    }

    /**
     * Test get product pairs by project.
     *
     * @param  int    $productID
     * @param  string $status
     * @access public
     * @return array
     */
    public function getProductPairsByProjectTest($projectID, $status = 'all')
    {
        $objects = $this->objectModel->getProductPairsByProject($projectID, $status);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects;
        }
    }

    /**
     * Test get product pairs by project model.
     *
     * @param  string $model
     * @access public
     * @return int
     */
    public function getPairsByProjectModelTest($model)
    {
        $objects = $this->objectModel->getPairsByProjectModel($model);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return count($objects);
        }
    }

    /**
     * Test get products by project.
     *
     * @param  int    $projectID
     * @param  string $status
     * @access public
     * @return array
     */
    public function getProductsTest(int $projectID, string $status): array
    {
        $this->objectModel->app->user->admin = true;
        $objects = $this->objectModel->getProducts($projectID, $status);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects;
        }
    }

    /**
     * Test get product id by project.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getProductIDByProjectTest($projectID)
    {
        $object = $this->objectModel->getProductIDByProject($projectID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test for get ordered products.
     *
     * @param  string     $status
     * @param  int        $num
     * @param  int        $projectID
     * @param  int|string $shadow
     * @access public
     * @return int
     */
    public function getOrderedProductsTest(string $status, int $num = 0, int $projectID = 0, int|string $shadow = 0): int
    {
        $products = $this->objectModel->getOrderedProducts($status, $num, $projectID, $shadow);
        if(dao::isError()) return dao::getError();

        return count($products);
    }

    /**
     * Test get Multi-branch product pairs.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getMultiBranchPairsTest($programID)
    {
        $objects = $this->objectModel->getMultiBranchPairs($programID);

        $title  = '';
        foreach($objects as $object) $title .=  ',' . $object;

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $title;
        }
    }

    /**
     * Test batch update products.
     *
     * @param  array  $products
     * @access public
     * @return array
     */
    public function batchUpdateTest(array $products): array
    {
        return $this->objectModel->batchUpdate($products);
    }

    /**
     * 测试doUpdate方法
     * Test doUpdate method
     *
     * @param object $product
     * @param int    $productID
     * @param int    $programID
     * @access public
     * @return object|array|null
     */
    public function doUpdateTest(object $product, int $productID, int $programID): object|array|false
    {
        $this->objectModel->doUpdate($product, $productID, $programID);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getById($productID);
    }

    /**
     * Test close a product.
     *
     * @param  int    $productID
     * @access public
     * @return array|false
     */
    public function closeTest(int $productID): array|false
    {
        $data = new stdclass();
        $data->status = 'closed';

        $changes = $this->objectModel->close($productID, $data);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * Test manage line.
     *
     * @param  array  $lines
     * @access public
     * @return void
     */
    public function manageLineTest(array $lines): array
    {
        $this->objectModel->manageLine($lines);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->dao->select('id,root,name')->from(TABLE_MODULE)->where('type')->eq('line')->orderby('id desc')->fetchAll('id');
        }
    }

    /**
     * Test get stories.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getStoriesTest($productID, $branch, $browseType, $queryID, $moduleID)
    {
        $objects = $this->objectModel->getStories($productID, $branch, $browseType, $queryID, $moduleID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return count($objects);
        }
    }

    /**
     * Test batch get story stage.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchGetStoryStageTest($stories)
    {
        $objects = $this->objectModel->batchGetStoryStage($stories);

        $stages = array();
        foreach($objects as $id => $object) $stages[$id] = $object[0]->stage;

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $stages;
        }
    }

    /**
     * 测试 getProjectStatsByProduct 方法。
     * Test getProjectStatsByProduct method.
     *
     * @param  int         $productID
     * @param  string      $browseType
     * @param  string      $branch
     * @param  bool        $involved
     * @param  string      $order
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getProjectStatsByProductTest(int $productID, string $browseType = 'all', string $branch = '', bool $involved = false, string $orderBy = 'order_desc', object|null $pager = null): array
    {
        $this->objectModel->loadModel('program')->refreshStats(true);
        $objects = $this->objectModel->getProjectStatsByProduct($productID, $browseType, $branch, $involved, $orderBy, $pager);

        $projects = array();
        foreach($objects as $object)
        {
            $project = new stdclass();
            $project->id            = $object->id;
            $project->totalConsumed = $object->consumed;
            $project->totalEstimate = $object->estimate;
            $project->totalLeft     = $object->left;
            $project->progress      = $object->progress;
            $project->teamCount     = $object->teamCount;
            $projects[$project->id] = $project;
        }

        if(dao::isError()) return dao::getError();
        return $projects;
    }

    /**
     * 获取关联了某产品的执行列表。
     * Test get executions by product and project.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getExecutionPairsByProductTest(int $productID, int $projectID = 0, string $mode = ''): array
    {
        $objects = $this->objectModel->getExecutionPairsByProduct($productID, 0, $projectID, $mode);

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 测试 buildExecutionPairs 方法。
     * Test buildExecutionPairs method.
     *
     * @param  string $mode
     * @param  bool   $withBranch
     * @access public
     * @return array
     */
    public function buildExecutionPairsTest(string $mode = '', bool $withBranch = false): array
    {
        $orderBy    = 't2.begin_desc,t2.id_desc';
        $executions = $this->objectModel->dao->select('t2.id,t2.name,t2.project,t2.grade,t2.path,t2.parent,t2.attribute,t2.multiple,t3.name as projectName')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project = t3.id')
            ->where('t1.product')->eq('1')
            ->andWhere('t2.type')->in('sprint,kanban,stage')
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');

        $objects = $this->objectModel->buildExecutionPairs($executions, $mode, $withBranch);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects;
        }
    }

    /**
     * Test get roadmap of a proejct.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getRoadmapTest($productID)
    {
        $objects = $this->objectModel->getRoadmap($productID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test process roadmap.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function processRoadmapTest($productID, $branch = '0')
    {
        global $tester;
        $releases = $tester->loadModel('release')->getList($productID, $branch);

        $roadmapGroups = array('2022' => array($releases));

        $objects = $this->objectModel->processRoadmap($roadmapGroups, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get product stat by id.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getStatByIDTest($productID)
    {
        $objects = $this->objectModel->getStatByID($productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects;
        }
    }

    /**
     * Test get product stats.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getStatsTest(array $productIdList)
    {
        $this->objectModel->refreshStats(true);
        $objects = $this->objectModel->getStats($productIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test stats for product kanban.
     *
     * @param  string $type
     * @param  bool   $getCount
     * @access public
     * @return array
     */
    public function getStats4KanbanTest($type, $getCount = false)
    {
        $this->objectModel->config->product->showAllProjects = true;
        $objects = $this->objectModel->getStats4Kanban();

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $getCount ? count($objects[$type]) : $objects[$type];
        }
    }

    /**
     * Test get product line pairs.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getLinePairsTest($programID)
    {
        $objects = $this->objectModel->getLinePairs($programID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects;
        }
    }

    /**
     * 从产品统计数据中统计项目集。
     * Statistics program data from statistics data of product.
     *
     * @param  array  $productIdList
     * @param  int    $index
     * @access public
     * @return array
     */
    public function statisticProgramTest(array $productIdList, int $index): array
    {
        $productStats = $this->objectModel->getStats($productIdList);
        $objects      = $this->objectModel->statisticProgram($productStats);

        if(dao::isError()) return dao::getError();
        return $index ? $objects[$index][$index]['products'] : $objects;
    }

    /**
     * Test statistics product data.
     *
     * @param  object $product
     * @access public
     * @return array
     */
    public function statisticDataTest($product)
    {
        if($product->line)
        {
            /* Line name. */
            $productStructure[$product->program][$product->line]['lineName'] = $product->lineName;
            $data = $this->objectModel->statisticData('line', $productStructure, $product);
        }

        if($product->program)
        {
            /* Init vars. */
            /* Program name. */
            $productStructure[$product->program]['programName'] = $product->programName;
            $data = $this->objectModel->statisticData('program', $productStructure, $product);
        }

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $data;
        }
    }

    /**
     * Test getPageProductsWithProgramIn function of tao file.
     * 测试tao文件中的 getPagerProductsWithProgramIn 函数。
     *
     * @param  array       $productIDs
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getPagerProductsWithProgramInTest(array $productIDs, object|null $pager): array
    {
        $records = $this->objectModel->getPagerProductsWithProgramIn($productIDs, $pager);
        if(!ksort($records)) return [];

        return $records;
    }

    /**
     * 测试创建产品线
     * Test for create line.
     *
     * @param  int programID
     * @param  string lineName
     * @access public
     * @return object|array
     */
    public function createLineTest(int $programID, string $lineName): object|array
    {
        $lineID = $this->objectModel->createLine($programID, $lineName);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            if(!$lineID) return array();
            $object = $this->objectModel->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($lineID)->fetch();
            return $object;
        }
    }

    /**
     * Test for concat product line.
     *
     * @param  array  $productIdList
     * @access public
     * @return object[]
     */
    public function concatProductLineTest(array $productIdList): array
    {
        global $config;
        $config->systemMode == 'ALM';
        $products = $this->objectModel->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchAll();
        return $this->objectModel->concatProductLine($products);
    }

    /**
     * 测试创建产品主库
     * Test for create main lib.
     *
     * @param  int productID
     * @access public
     * @return object|array
     */
    public function createMainLibTest(int $productID): object|array
    {
        $libID = $this->objectModel->createMainLib($productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            if(!$libID) return array();
            $object = $this->objectModel->dao->select('*')->from(TABLE_DOCLIB)->where('id')->eq($libID)->fetch();
            return $object;
        }
    }

    /**
     * 测试setMenu方法。
     * Test setMenu
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @param  string     $extra
     * @access public
     * @return array
     */
    public function setMenuTest(int $productID, string|int $branch = '', string $extra = ''): array
    {
        /* Reset data. */
        $this->objectModel->lang->product->moreSelects['willclose'] = 'willcose';
        $this->objectModel->lang->product->menu->settings['link'] = "Settings|product|view|productID=%s";
        $this->objectModel->lang->product->menu->settings['subMenu']->branch = array('link' => "@branch@|branch|manage|product=%s", 'subModule' => 'branch');

        $this->objectModel->setMenu($productID, $branch, $extra);

        $hasBranch      = (int) isset($this->objectModel->lang->product->menu->settings['subMenu']->branch);
        $requirement    = (int)!isset($this->objectModel->lang->product->moreSelects['willclose']);
        $idReplaced     = (int)(strpos($this->objectModel->lang->product->menu->settings['link'], '%s') === false);
        $branchReplaced = (int)($hasBranch and strpos($this->objectModel->lang->product->menu->settings['subMenu']->branch['link'], '@branch@') === false);

        return array('idReplaced' => $idReplaced, 'branchReplaced' => $branchReplaced, 'hasBranch' => $hasBranch, 'requirement' => $requirement);
    }

    /**
     * 测试 updateOrder 方法。
     * Test updateOrder method.
     *
     * @param  array $sortedIdList
     * @access public
     * @return string
     */
    public function updateOrderTest(array $sortedIdList): string
    {
        $this->objectModel->updateOrder($sortedIdList);

        $products = $this->objectModel->dao->select('id')->from(TABLE_PRODUCT)->orderBy('`order`')->fetchAll('id');
        return implode('|', array_keys($products));
    }

    /**
     * 删除一个产品线。
     * Delete a product line.
     *
     * @param  int    $lineID
     * @access public
     * @return object
     */
    public function deleteLineTest(int $lineID): object
    {
        $this->objectModel->deleteLine($lineID);
        return $this->objectModel->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($lineID)->fetch();
    }

    /**
     * Test deleteByID method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function deleteByIdTest(int $productID)
    {
        $this->objectModel->deleteByID($productID);
        return $this->objectModel->getByID($productID);
    }

    /**
     * 统计项目集内的产品数据。
     * Statistic product data.
     *
     * @param  string $type
     * @param  array  $productIdList
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function statisticProductDataTest(string $type, array $productIdList, int $productID)
    {
        $productStats     = $this->objectModel->getStats($productIdList);
        $product          = zget($productStats, $productID, null);
        $programStructure = $this->objectModel->statisticProgram($productStats);

        return $this->objectModel->statisticProductData($type, $programStructure, $product);
    }

    /**
     * Test build search form.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildProductSearchFormTest($queryID)
    {
        $this->objectModel->buildProductSearchForm($queryID, 'searchUrl');

        return $_SESSION['productsearchParams']['queryID'];
    }

    /**
     * 构造需求的搜索表单。
     * Build story search form.
     *
     * @param  int    $productID
     * @param  int    $queryID
     * @access public
     * @return int
     */
    public function buildSearchFormTest(int $productID, int $queryID): int
    {
        $product = $this->objectModel->getByID($productID);
        if(empty($product)) return 0;

        $products = $this->objectModel->loadModel('product')->getProducts($productID);
        $this->objectModel->buildSearchForm($productID, $products, $queryID, 'searchStory', 'productStory');

        return $_SESSION['storysearchParams']['queryID'];
    }

    /**
     * formatDataForListTest
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function formatDataForListTest($productID)
    {
        $product = $this->objectModel->getStats(array($productID));
        if(isset($product[$productID]))
        {
            $product[$productID]->totalStories = 30;
            $product[$productID]->finishedStories = 15;
            $product[$productID]->unresolvedBugs = 20;
            $product[$productID]->fixedBugs = 10;
        }

        if(!$product) return false;

        return $this->objectModel->formatDataForList($product[$productID], array());
    }

    /**
     * Test summary method.
     *
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return array
     */
    public function summaryTest($productID, $type)
    {
        $stories = $this->objectModel->getStories($productID, 'all', 'unclosed', 0, 0, $type);

        return str_replace('%', '%%', $this->objectModel->summary($stories, $type));
    }

    /**
     * 获取产品与项目关联的项目集数据列表。
     * Get the progam info of the releated project and product by product list.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getProjectProductListTest(array $productIdList): array
    {
        $productList = $this->objectModel->getByIdList($productIdList);

        return $this->objectModel->getProjectProductList($productList);
    }

    /**
     * 过滤有效的产品计划, 并返回所有父级计划。
     * Filter valid product plans.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function filterOrderedAndParentPlansTest(int $productID, string $branch): array
    {
        $planList = $this->objectModel->loadModel('productplan')->getList($productID, $branch);
        return $this->objectModel->filterOrderedAndParentPlans($planList);
    }

    /**
     * 获取发布的路线图数据。
     * Get roadmap of releases
     *
     * @param  int $productID
     * @param  string $branch
     * @param  int $count
     * @access public
     * @return array
     */
    public function getRoadmapOfReleasesTest(int $productID, string $branch, int $count): array
    {
        $releases = $this->objectModel->loadModel('release')->getList($productID, $branch);
        return $this->objectModel->getRoadmapOfReleases(array(), $releases, $branch, $count);
    }

    /**
     * 获取计划的路线图数据。
     * Get roadmap of plans.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $count
     * @access public
     * @return array
     */
    public function getRoadmapOfPlansTest(int $productID, string $branch, int $count): array
    {
        $roadmap  = array();
        $planList = $this->objectModel->loadModel('productplan')->getList($productID, $branch);
        list($orderedPlans, $parentPlans) = $this->objectModel->filterOrderedAndParentPlans($planList);

        return $this->objectModel->getRoadmapOfPlans($orderedPlans, $parentPlans, $branch, $count);
    }

    /**
     * 激活产品。
     * Activate a product.
     *
     * @param  int $productID
     * @access public
     * @return array|false
     */
    public function activateTest(int $productID): array|false
    {
        $oldProduct = $this->objectModel->getByID($productID);
        if(!$oldProduct) return false;

        $product = new stdClass();
        $product->status = 'normal';
        $changes = $this->objectModel->activate($productID, $product);
        if(dao::isError()) return dao::getError();

        return $changes;
    }

    /**
     * 获取产品下需求的统计数据。
     * Get story statistic data.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getStoryStatsTest(array $productIdList): array
    {
        $productStories = $this->objectModel->getStoryStats($productIdList);
        if(dao::isError()) return dao::getError();

        return $productStories;
    }

    /**
     * 获取产品下bug的统计数据。
     * Get bug statistic data.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getBugStatsTest(array $productIdList): array
    {
        $productBugs = $this->objectModel->getBugStats($productIdList);
        if(dao::isError()) return dao::getError();

        return $productBugs;
    }

    /**
     * 获取产品的统计数据。
     * Get summary of products to be refreshed.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getProductStatsTest(array $productIdList): array
    {
        $products = $this->objectModel->getProductStats($productIdList);
        if(dao::isError()) return dao::getError();

        return $products;
    }

    /**
     * 刷新产品的统计信息。
     * Refresh stats info of products.
     *
     * @access public
     * @return array
     */
    public function refreshStatsTest(): array
    {
        $this->objectModel->refreshStats(true);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getProducts();
    }
}
