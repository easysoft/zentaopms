<?php
class productTest
{
    /**
     * @var productModel
     * @access private
     */
    private productModel $objectModel;

    /**
     * __construct
     *
     * @param  mixed  $user
     * @access public
     * @return void
     */
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->objectModel = $tester->loadModel('product');
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
        $createFields['createdBy']      = $this->objectModel->app->user->account;
        $createFields['createdDate']    = helper::now();
        $createFields['createdVersion'] = $this->objectModel->config->version;

        $data = new stdclass();
        foreach($createFields as $field => $defaultValue) $data->$field = zget($param, $field, $defaultValue);

        $objectID = $this->objectModel->create($data, '', $lineName);

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
     * Test get product list.
     *
     * @param  int    $programID
     * @param  string $status
     * @param  int    $line
     * @access public
     * @return array
     */
    public function getProductList($programID, $status = 'all', $line = 0)
    {
        return $this->objectModel->getList($programID, $status, 0, $line);
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
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('deleted')->eq(1)->orderBy('id_desc')->limit(5)->exec();
        $this->objectModel->dao->update(TABLE_PRODUCT)->set('shadow')->eq(1)->where('id')->ge(18)->andWhere('id')->lt(23)->exec();
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
     * Test judge an action is clickable or not.
     *
     * @param  int    $productID
     * @param  string $status
     * @access public
     * @return string
     */
    public function testIsClickable($productID, $status)
    {
        $product = $this->objectModel->getById($productID);
        $isClick = $this->objectModel->isClickable($product, $status);
        return $isClick == false ? 'false' : 'true';
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
     * @return int
     */
    public function checkPrivTest($productID)
    {
        $object = $this->objectModel->checkPriv($productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $object ? 1 : 2;
        }
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
     * @return array
     */
    public function getByIdListTest($productIDList)
    {
        $objects = $this->objectModel->getByIdList($productIDList);

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
    public function getProductsTest($projectID, $status)
    {
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
     * Test get ordered products.
     *
     * @param  string $status
     * @access public
     * @return int
     */
    public function getOrderedProductsTest($status)
    {
        $objects = $this->objectModel->getOrderedProducts($status);

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
     * @param  array  $param
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchUpdateTest($param, $productID)
    {
        $batchUpdateFields['productIDList'] = array('1' => '1', '2' => '2', '3' => '3');
        $batchUpdateFields['programs']      = array('1' => '', '2' => '1', '3' => '2');
        $batchUpdateFields['names']         = array('1' => '正常产品1', '2' => '正常产品2', '3' => '正常产品3');
        $batchUpdateFields['lines']         = array('1' => '0', '2' => '1', '3' => '2');
        $batchUpdateFields['POs']           = array('1' => 'po1', '2' => 'po2', '3' => 'po3');
        $batchUpdateFields['QDs']           = array('1' => 'test1', '2' => 'test2', '3' => 'test3');
        $batchUpdateFields['RDs']           = array('1' => 'dev1', '2' => 'dev2', '3' => 'dev3');
        $batchUpdateFields['types']         = array('1' => 'normal', '2' => 'normal', '3' => 'normal');
        $batchUpdateFields['statuses']      = array('1' => 'normal', '2' => 'normal', '3' => 'normal');
        $batchUpdateFields['descs']         = array('1' => '&lt;div&gt; &lt;p&gt;&lt;h1&gt;一、禅道项目管理软件是做什么的？&lt;/h1&gt; 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 &lt;/p&gt; &lt;p&gt;我是数字符号23@#$%#^$ &lt;/p&gt; &lt;p&gt;我是英文dashcuscbrewg &lt;/p&gt; &lt;/div&gt;', '2' => '&lt;div&gt; &lt;p&gt;&lt;h1&gt;一、禅道项目管理软件是做什么的？&lt;/h1&gt; 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 &lt;/p&gt; &lt;p&gt;我是数字符号23@#$%#^$ &lt;/p&gt; &lt;p&gt;我是英文dashcuscbrewg &lt;/p&gt; &lt;/div&gt;', '3' => 'i&lt;div&gt; &lt;p&gt;&lt;h1&gt;一、禅道项目管理软件是做什么的？&lt;/h1&gt; 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 &lt;/p&gt; &lt;p&gt;我是数字符号23@#$%#^$ &lt;/p&gt; &lt;p&gt;我是英文dashcuscbrewg &lt;/p&gt; &lt;/div&gt;');
        $batchUpdateFields['acls']          = array('1' => 'open', '2' => 'open', '3' => 'open');

        foreach($batchUpdateFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $changes = $this->objectModel->batchUpdate();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $changes[$productID];
        }
    }

    /**
     * Test close a product.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function closeTest($productID)
    {
        $changes = $this->objectModel->close($productID);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $changes;
        }
    }

    /**
     * Test manage line.
     *
     * @param  array  $param
     * @access public
     * @return void
     */
    public function manageLineTest($param, $moduleID = 0)
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->manageLine();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            global $tester;

            $moduleID = $moduleID ? $moduleID : $tester->dao->select('id')->from(TABLE_MODULE)->orderby('id desc')->fetch()->id;
            $object   = $tester->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();

            return $object;
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
     * Test get project stats by product.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProjectStatsByProductTest($productID, $browseType = 'all')
    {
        $objects = $this->objectModel->getProjectStatsByProduct($productID, $browseType);

        foreach($objects as $object) $projects[$object->id] = $object->name;


        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return isset($projects) ? $projects : array();
        }
    }

    /**
     * Test get executions by product and project.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getExecutionPairsByProductTest($productID, $projectID = 0)
    {
        $objects = $this->objectModel->getExecutionPairsByProduct($productID, 0, 'id_asc', $projectID);

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
     * Test get all executions by product.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getAllExecutionPairsByProductTest($productID)
    {
        $objects = $this->objectModel->getAllExecutionPairsByProduct($productID);

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
     * Test process roadmap.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function processRoadmapTest($productID)
    {
        global $tester;
        $releases = $tester->loadModel('release')->getList($productID, '0');

        $roadmapGroups = array('2022' => array($releases));

        $objects = $this->objectModel->processRoadmap($roadmapGroups);

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
     * Test get team members of a product from projects.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getTeamMemberPairsTest($productID)
    {
        $product = $this->objectModel->getByID($productID);
        $objects = $this->objectModel->getTeamMemberPairs($product);

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
     * @access public
     * @return array
     */
    public function getStatsTest()
    {
        $objects = $this->objectModel->getStats();

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
     * Test stats for product kanban.
     *
     * @param  string $type
     * @param  bool   $getCount
     * @access public
     * @return array
     */
    public function getStats4KanbanTest($type, $getCount = false)
    {
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
     * Test statistics program data.
     *
     * @param  object $productStats
     * @param  int    $index
     * @access public
     * @return array
     */
    public function statisticProgramTest($productStats, $index)
    {
        $objects = $this->objectModel->statisticProgram($productStats);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $objects[$index][$index]['products'];
        }
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
     * Test change the projects set of the program.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function updateProjectsTest($productID)
    {
        $singleLinkProjects   = array();
        $multipleLinkProjects = array();

        global $tester;
        /* Get the projects linked with this product. */
        $projectPairs = $tester->dao->select('t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchPairs();

        $projects = ',';
        if(!empty($projectPairs))
        {
            foreach($projectPairs as $projectID => $projectName)
            {
                $projects .= $projectID . ',';
                $products = $tester->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                if(count($products) == 1)
                {
                    $singleLinkProjects[$projectID] = $projectName;
                }

                if(count($products) > 1)
                {
                    $multipleLinkProjects[$projectID] = $projectName;
                }
            }
        }

        $_POST['changeProjects'] = $projects;

        $product = $this->objectModel->getById($productID);
        $_POST['program'] = $product->program == 1 ? 2 : 1;

        $this->objectModel->updateProjects($productID, $singleLinkProjects, $multipleLinkProjects);

        $object = $tester->dao->select('t2.id,t2.parent,t2.path')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchAll();

        unset($_POST);

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
}
