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

        return count($objects);
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
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create($productID, $withMerge = false);

        unset($_POST);

        if(dao::isError()) return dao::getError()['name'][0];

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

        $branch = $this->objectModel->getByID($branchID, 0, '');

        $_POST['name']   = $branch->name;
        $_POST['status'] = $branch->status;
        $_POST['desc']   = $branch->desc;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($branchID);

        unset($_POST);

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

        $productID = 45;
        $branches  = $tester->dao->select('*')->from(TABLE_BRANCH)->where('product')->eq($productID)->fetchAll('id');

        $_POST['IDList']  = array('0' => 0, '9' => 9, '10' => 10);
        $_POST['default'] = 0;
        $_POST['name']    = array('9' => $branches['9']->name, '10' => $branches['10']->name);
        $_POST['desc']    = array('9' => $branches['9']->desc, '10' => $branches['10']->desc);
        $_POST['status']  = array('9' => $branches['9']->status, '10' => $branches['10']->status);

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->batchUpdate($productID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects[9][0];
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
     * @param  string $productIDList
     * @access public
     * @return int
     */
    public function unlinkBranch4ProjectTest($productIDList)
    {
        $this->objectModel->unlinkBranch4Project($productIDList);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->in($productIDList)->andWhere('branch')->gt(0)->fetchAll();
        return count($objects);
    }

    /**
     * Test link branches for projects when product type is not normal.
     *
     * @param  int    $productID
     * @access public
     * @return int
     */
    public function linkBranch4ProjectTest($productID)
    {
        $this->objectModel->linkBranch4Project($productID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->andWhere('branch')->gt(0)->fetchAll();
        return count($objects);
    }

    /**
     * Test get branch group by products.
     *
     * @param  int    $products
     * @param  string $params
     * @param  string $appendBranch
     * @access public
     * @return string
     */
    public function getByProductsTest($products, $params = '', $appendBranch = '')
    {
        $objects = $this->objectModel->getByProducts($products, $params, $appendBranch);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $productID => $branches)
        {
            $ids .= "$productID:";
            foreach($branches as $branchID => $branchName) $ids .= ",$branchID";
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
     * Test sort branch.
     *
     * @param  string $branches
     * @param  string $order
     * @access public
     * @return string
     */
    public function sortTest($branches, $order = '')
    {
        $_POST['orderBy']  = $order;
        $_POST['branches'] = $branches;

        $this->objectModel->sort();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_BRANCH)->where('id')->in($branches)->orderBy('order asc')->fetchAll('id');
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
    public function setDefaultTest($productID, $branchID)
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
        global $lang;

        $filePath = dirname(dirname(dirname(__FILE__))) . DS . 'module' . DS . 'branch' . DS . 'lang' . DS . 'zh-cn.php';
        include $filePath;

        $this->objectModel->changeBranchLanguage($productID);

        $createLang = $tester->lang->branch->create;

        if(dao::isError()) return dao::getError();

        return $createLang;
    }

    /**
     * Test merge multiple branches into one branch.
     *
     * @param  int    $productID
     * @param  string $mergedBranches
     * @access public
     * @return int
     */
    public function mergeBranchTest($productID, $mergedBranches, $param)
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->mergeBranch($productID, $mergedBranches);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        global $tester;
        $modules = $tester->dao->select('*')->from(TABLE_MODULE)->where('branch')->eq($objectID)->andWhere('root')->eq($productID)->andWhere('type')->eq('story')->fetchAll();
        return count($modules);
    }
}
