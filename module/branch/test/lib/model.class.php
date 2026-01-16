<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class branchModelTest extends baseTest
{
    protected $moduleName = 'branch';
    protected $className  = 'model';

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
        $object = $this->instance->getById($branchID, $productID, $field);
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
        $objects = $this->instance->getList($productID, $executionID, $browseType, 'order', null, $withMainBranch);
        if(dao::isError()) return dao::getError();

        return implode(',', array_column($objects, 'id'));
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
        $objects = $this->instance->getPairs($productID, $params, $executionID, $mergedBranches);
        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
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
        $objects = $this->instance->getAllPairs($params);
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
        $result = $this->instance->getStatusList($productID);
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
        $objectID = $this->instance->create($productID, (object) $param);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($objectID)->fetch();
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
        $branch   = $this->instance->getByID((string)$branchID, 0, '');

        $newBranch = new stdclass();
        $newBranch->name   = $branch->name;
        $newBranch->status = $branch->status;
        $newBranch->desc   = $branch->desc;

        foreach($param as $key => $value) $newBranch->$key = $value;

        $objects = $this->instance->update($branchID, $newBranch);
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
        $productID = 6;
        $branches  = $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('product')->eq($productID)->fetchAll('id');

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

        $objects = $this->instance->batchUpdate($productID, $newBranches);
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
        $this->instance->close($branchID);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
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
        $this->instance->activate($branchID);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
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
        $this->instance->unlinkBranch4Project($productIDList);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('COUNT(1) AS count')->from(TABLE_PROJECTPRODUCT)->where('product')->in($productIDList)->andWhere('branch')->gt(0)->fetch('count');
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
        $this->instance->linkBranch4Project($productID);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('COUNT(1) AS count')->from(TABLE_PROJECTPRODUCT)->where('product')->in($productIDList)->andWhere('branch')->gt(0)->fetch('count');
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
        $objects = $this->instance->getByProducts($productIdList, $params, $appendBranch);
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
        $type = $this->instance->getProductType($branchID);
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

        $this->instance->sort($branchOrderList);
        if(dao::isError()) return dao::getError();

        $idList = $this->instance->dao->select('id')->from(TABLE_BRANCH)->where('id')->in($branchIdList)->orderBy('`order`')->fetchPairs();
        return implode(',', $idList);
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
        $objects = $this->instance->checkBranchData($branchID);
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
        $this->instance->setDefault($productID, $branchID);
        if(dao::isError()) return dao::getError();

        return $this->instance->getById($branchID, $productID, '');
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
        $objects = $this->instance->getPairsByProjectProduct($projectID, $productID);
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
        $show = $this->instance->showBranch($productID, $moduleID = 0, $executionID = 0);
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
        $result = $this->instance->changeBranchLanguage($productID);
        if(dao::isError()) return dao::getError();

        if($result === false) return '0';

        return $this->instance->lang->branch->create;
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
        $objectID = $this->instance->mergeBranch($productID, $mergedBranches, $data);

        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetchAll('', false);
    }

    /**
     * Test for judge a action is clickable.
     *
     * @param  int    $branchID
     * @param  string $status
     * @access public
     * @return bool
     */
    public function isClickableTest(int $branchID, string $status): bool
    {
        $branch = $this->instance->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();
        return $this->instance->isClickable($branch, $status);
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
        // 设置POST数据以模拟实际提交
        $_POST = [];
        $_POST['branch']    = $branchData; // 确保branch总是存在,即使是空数组
        $_POST['newbranch'] = $newBranches; // 确保newbranch总是存在

        // 调用被测方法
        $result = $this->instance->manage($productID);
        if(dao::isError()) return dao::getError();

        // 如果返回false，表示DAO错误或其他问题
        if($result === false) return false;

        // 如果结果包含JavaScript代码（空分支名错误），返回错误标识
        if(!is_array($result)) return 'error';

        // 返回新创建分支的数量
        return count($result);
    }
}
