<?php
class branchTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('branch');
    }

    /**
     * Test get name by id.
     *
     * @param  int    $branchID
     * @param  int    $productID
     * @param  string $field
     * @access public
     * @return object
     */
    public function getByIdTest($branchID, $productID = 0, $field = 'name')
    {
        $object = $this->objectModel->getById($branchID, $productID, $field);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get branch list.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $browseType
     * @param  bool   $withMainBranch
     * @access public
     * @return string
     */
    public function getListTest($productID, $executionID = 0, $browseType = 'active', $withMainBranch = true)
    {
        $objects = $this->objectModel->getList($productID, $executionID, $browseType, 'order', null, $withMainBranch);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $object) $ids .= ',' . $object->id;
        return $ids;
    }

    /**
     * Test get pairs.
     *
     * @param  int    $productID
     * @param  string $params
     * @param  int    $executionID
     * @param  string $mergedBranches
     * @access public
     * @return string
     */
    public function getPairsTest($productID, $params = '', $executionID = 0, $mergedBranches = '')
    {
        $objects = $this->objectModel->getPairs($productID, $params, $executionID, $mergedBranches);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $id => $object) $ids .= ',' . $id;
        return $ids;
    }

    /**
     * Test get all pairs.
     *
     * @param  string $params
     * @access public
     * @return int
     */
    public function getAllPairsTest($params = '')
    {
        $objects = $this->objectModel->getAllPairs($params);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get status list.
     *
     * @param  int $productID
     * @access public
     * @return array
     */
    public function getStatusListTest($productID)
    {
        $result = $this->objectModel->getStatusList($productID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test create a branch.
     *
     * @param  int    $productID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function createTest($productID, $param = array())
    {
        $objectID = $this->objectModel->create($productID, (object) $param);
        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($objectID)->fetch();
        return $object;
    }

    /**
     * Test update branch.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function updateTest($param)
    {
        $branchID = 1;
        $branch   = $this->objectModel->getByID((string)$branchID, 0, '');

        $newBranch = new stdclass();
        $newBranch->name   = $branch->name;
        $newBranch->status = $branch->status;
        $newBranch->desc   = $branch->desc;

        foreach($param as $key => $value) $newBranch->$key = $value;

        $objects = $this->objectModel->update($branchID, $newBranch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test batch update branch.
     *
     * @param  array  $param
     * @access public
     * @return object
     */
    public function batchUpdateTest($param = array())
    {
        global $tester;

        $productID = 6;
        $branches  = $tester->dao->select('*')->from(TABLE_BRANCH)->where('product')->eq($productID)->fetchAll('id');

        $newBranches = array();
        foreach($branches as $branch)
        {
            $newBranch = new stdclass();
            $newBranch->branchID = $branch->id;
            $newBranch->name     = $branch->name;
            $newBranch->desc     = $branch->desc;
            $newBranch->status   = $branch->status;

            foreach($param as $key => $setting)
            {
                foreach($setting as $id => $value)
                {
                    if($id != $branch->id) continue;

                    $newBranch->$key = $value;
                }
            }

            $newBranches[$branch->id] = $newBranch;
        }

        $objects = $this->objectModel->batchUpdate($productID, $newBranches);

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test close a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return object
     */
    public function closeTest($branchID)
    {
        $this->objectModel->close($branchID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
        return $object;
    }

    /**
     * Test activate a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return object
     */
    public function activateTest($branchID)
    {
        $this->objectModel->activate($branchID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
        return $object;
    }

    /**
     * Test unlink branches for projects when product type is normal.
     *
     * @param  array     $productIDList
     * @access public
     * @return array|int
     */
    public function unlinkBranch4ProjectTest(array $productIDList): array|int
    {
        $this->objectModel->unlinkBranch4Project($productIDList);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->in($productIDList)->andWhere('branch')->gt(0)->fetchAll();
        return count($objects);
    }

    /**
     * 产品改为多分支类型时，处理分支关联项目。
     * Test link branches for projects when product type is not normal.
     *
     * @param  int|array $productID
     * @access public
     * @return int|array
     */
    public function linkBranch4ProjectTest(int|array $productID): array|int
    {
        $this->objectModel->linkBranch4Project($productID);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->in($productID)->andWhere('branch')->gt(0)->fetchAll();
        return count($objects);
    }

    /**
     * 按照产品分组获取分支数据。
     * Test get branch group by products.
     *
     * @param  array        $productIdList
     * @param  string       $params
     * @param  array        $appendBranch
     * @access public
     * @return array|string
     */
    public function getByProductsTest(array $productIdList, string $params = '', array $appendBranch = array()): array|string
    {
        $objects = $this->objectModel->getByProducts($productIdList, $params, $appendBranch);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $productID => $branches)
        {
            $ids .= "$productID:";
            foreach($branches as $branchID => $branchName) $ids .= "|$branchID";
            $ids .= ';';
        }
        return $ids;
    }

    /**
     * Test get product bype by branch.
     *
     * @param  int    $branchID
     * @access public
     * @return string
     */
    public function getProductTypeTest($branchID)
    {
        $type = $this->objectModel->getProductType($branchID);

        if(dao::isError()) return dao::getError();

        return $type;
    }

    /**
     * 分支排序。
     * Test sort branch.
     *
     * @param  array        $branchOrderList
     * @access public
     * @return array|string
     */
    public function sortTest(array $branchOrderList): array|string
    {
        $branchIdList = array_keys($branchOrderList);
        $this->objectModel->sort($branchOrderList);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->dao->select('*')->from(TABLE_BRANCH)->where('id')->in($branchIdList)->orderBy('order asc')->fetchAll('id');
        return implode(',', array_keys($objects));
    }

    /**
     * Test check branch data.
     *
     * @param  int    $branchID
     * @access public
     * @return int
     */
    public function checkBranchDataTest($branchID)
    {
        $objects = $this->objectModel->checkBranchData($branchID);

        if(dao::isError()) return dao::getError();

        return $objects ? 1 : 2;
    }

    /**
     * Test check branch data.
     *
     * @param  int    $branchID
     * @access public
     * @return int
     */
    public function setDefaultTest(int $productID,  int $branchID)
    {
        $this->objectModel->setDefault($productID, $branchID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getById($branchID, $productID, '');

        return $object;
    }

    /**
     * Test get branches of product which linked project.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @access public
     * @return string
     */
    public function getPairsByProjectProductTest($projectID, $productID)
    {
        $objects = $this->objectModel->getPairsByProjectProduct($projectID, $productID);

        if(dao::isError()) return dao::getError();

        $names = '';
        foreach($objects as $id => $name) $names .= "$id:$name;";
        return $names;
    }

    /**
     * Test display of branch label.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function showBranchTest($productID, $moduleID = 0, $executionID = 0)
    {
        $show = $this->objectModel->showBranch($productID, $moduleID = 0, $executionID = 0);

        if(dao::isError()) return dao::getError();

        return $show ? 1 : 2;
    }

    /**
     * Test change branch language.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function changeBranchLanguageTest($productID)
    {
        global $tester;
        global $app;

        $app::$loadedLangs = array();
        $app->loadLang('branch');

        $result = $this->objectModel->changeBranchLanguage($productID);

        if(dao::isError()) return dao::getError();

        if($result === false) return '0';

        $createLang = $tester->lang->branch->create;
        return $createLang;
    }

    /**
     * 将多个分支合并到一个分支。
     * Test merge multiple branches into one branch.
     *
     * @param  int       $productID
     * @param  string    $mergedBranches
     * @param  object    $data
     * @access public
     * @return array
     */
    public function mergeBranchTest(int $productID, string $mergedBranches, object $data): array
    {
        $data->status      = 'active';
        $data->createdDate = helper::today();
        $objectID = $this->objectModel->mergeBranch($productID, $mergedBranches, $data);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_BRANCH)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetchAll();
    }

    /**
     * 合并分支后的其他数据处理。
     * other data process after merge branch.
     *
     * @param  int       $productID
     * @param  string    $mergedBranches
     * @param  object    $data
     * @access public
     * @return array|int
     */
    public function afterMergeTest(int $productID, string $mergedBranches, object $data): array|int
    {
        $targetBranch = $data->targetBranch;
        $objectID     = $this->objectModel->afterMerge($productID, $targetBranch, $mergedBranches, $data);

        if(dao::isError()) return dao::getError();

        $releases = $this->objectModel->dao->select('*')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('branch')->in($data->mergedBranchIDList)->fetchAll();
        return count($releases);
    }

    /**
     * Test for judge a action is clickable.
     *
     * @param  int    $branchID
     * @param  string $status
     * @access public
     * @return bool
     */
    public function testIsClickable(int $branchID, string $status): bool
    {
        $branch = $this->objectModel->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
        return $this->objectModel->isClickable($branch, $status);
    }

    /**
     * Test manage branch.
     *
     * @param  int    $productID
     * @param  array  $branchData
     * @param  array  $newBranches
     * @access public
     * @return mixed
     */
    public function manageTest(int $productID, array $branchData = array(), array $newBranches = array())
    {
        global $tester;

        // 清空之前的POST数据
        $_POST = array();

        // 设置POST数据以模拟实际提交
        $_POST['branch'] = $branchData; // 确保branch总是存在,即使是空数组
        $_POST['newbranch'] = $newBranches; // 确保newbranch总是存在

        // 调用被测方法
        $result = $this->objectModel->manage($productID);

        if(dao::isError()) return dao::getError();

        // 如果返回false，表示DAO错误或其他问题
        if($result === false) return false;

        // 如果结果包含JavaScript代码（空分支名错误），返回错误标识
        if(!is_array($result)) return 'error';

        // 返回新创建分支的数量
        return count($result);
    }
}
