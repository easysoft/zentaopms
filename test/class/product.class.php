<?php
class Product
{

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
        $this->product = $tester->loadModel('product');
    }

    /**
     * createObject
     *
     * @param  array  $param
     * @access public
     * @return void
     */
    public function createObject($param = array())
    {
        global $createFields;
        $whitelist = array();
        $createFields = array('program' => 1, 'line' => 0, 'lineName' => '', 'newLine' => 0, 'name' => '', 'code' => '', 'PO' => 'admin', 'QD' => '', 'RD' => '',
            'reviewer' => '', 'type' => 'normal', 'status' => 'normal', 'desc' => '', 'acl' => 'open', 'uid' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        //global $tester;
        //$objectModel = $tester->loadModel($module);
        //$objectID = $this->objectModel->create();
        $objectID = $this->product->create();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = $this->product->getByID($objectID);
            return $object;
        }
    }

    /**
     * testGetLatestProject
     *
     * @param  mixed  $productID
     * @access public
     * @return void
     */
    public function testGetLatestProject($productID)
    {
        $project = $this->product->getLatestProject($productID);
        if($project == false) return '没有数据';
        return $project;
    }

    /**
     * getAllProducts
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getAllProducts($programID)
    {
        return $this->product->getList($programID);
    }

    /**
     * getAllProductsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getAllProductsCount($programID)
    {
        return count($this->getAllProducts($programID));
    }

    /**
     * getNoclosedProducts
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getNoclosedProducts($programID)
    {
        return $this->product->getList($programID, 'noclosed');
    }

    /**
     * getNoclosedProductsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getNoclosedProductsCount($programID)
    {
        return count($this->getNoclosedProducts($programID));
    }

    /**
     * getClosedProducts
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getClosedProducts($programID)
    {
        return $this->product->getList($programID, 'closed');
    }

    /**
     * getClosedProductsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getClosedProductsCount($programID)
    {
        return count($this->getClosedProducts($programID));
    }

    /**
     * getInvolvedProducts
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getInvolvedProducts($programID)
    {
        return $this->product->getList($programID, 'involved');
    }

    /**
     * getInvolvedProductsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getInvolvedProductsCount($programID)
    {
        return count($this->getInvolvedProducts($programID));
    }

    /**
     * getProductsByLine
     *
     * @param int    mixed $programID
     * @param int    $line
     * @access public
     * @return void
     */
    public function getProductsByLine($programID, $line = 0)
    {
        return $this->product->getList($programID, 'all', 0, $line);
    }

    /**
     * countProductsByLine
     *
     * @param int    mixed $programID
     * @param int    $line
     * @access public
     * @return void
     */
    public function countProductsByLine($programID, $line = 0)
    {
        return count($this->getProductsByLine($programID, $line));
    }

    /**
     * getProductList
     *
     * @param int    mixed $programID
     * @param string $status
     * @param int    $line
     * @access public
     * @return void
     */
    public function getProductList($programID, $status = 'all', $line = 0)
    {
        return $this->product->getList($programID, $status, 0, $line);
    }

    /**
     * getProductCount
     *
     * @param int    mixed $programID
     * @param string $status
     * @param int    $line
     * @access public
     * @return void
     */
    public function getProductCount($programID, $status = 'all', $line = 0)
    {
        return count($this->getProductList($programID, $status, $line));
    }
    /**
     * getProductPairs
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getProductPairs($programID)
    {
        $pairs = $this->product->getPairs('', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    /**
     * getProductPairsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getProductPairsCount($programID)
    {
        $pairsCount = count($this->getProductPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    /**
     * getAllPairs
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getAllPairs($programID)
    {
        $pairs = $this->product->getPairs('all', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    /**
     * getAllPairsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getAllPairsCount($programID)
    {
        $pairsCount = count($this->getAllPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }
    /**
     * getNoclosedPairs
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getNoclosedPairs($programID)
    {
        $pairs = $this->product->getPairs('noclosed', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    /**
     * getNoclosedPairsCount
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getNoclosedPairsCount($programID)
    {
        $pairsCount = count($this->getNoclosedPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    /**
     * getProductPairsByOrder
     *
     * @param int    mixed $programID
     * @param string $orderBy
     * @param string $mode
     * @access public
     * @return void
     */
    public function getProductPairsByOrder($programID, $orderBy = 'id_desc', $mode = '')
    {
        $this->product->orderBy = $orderBy;
        $pairs = $this->product->getPairs($mode, $programID);
        return checkOrder($pairs, $orderBy);
    }

    /**
     * getAllProjectsByProduct
     *
     * @param  int    mixed $productID
     * @access public
     * @return void
     */
    public function getAllProjectsByProduct($productID)
    {
        $projects = $this->product->getProjectListByProduct($productID, 'all');
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * getProjectsByStatus
     *
     * @param  int    mixed $productID
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getProjectsByStatus($productID, $status)
    {
        $projects = $this->product->getProjectListByProduct($productID, $browseType);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * getProjectPairsByProductID
     *
     * @param  int    mixed $productID
     * @access public
     * @return void
     */
    public function getProjectPairsByProductID($productID)
    {
        $projects = $this->product->getProjectPairsByProduct($productID, 0, 0);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    /**
     * getAppendProject
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getAppendProject($projectID)
    {
        $project = $this->product->getProjectPairsByProduct(10086, 0, $projectID);
        if($project == array()) return '没有数据';
        return $project;
    }

    /**
     * testIsClickable
     *
     * @param  int    mixed $productID
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function testIsClickable($productID, $status)
    {
        $product = $this->product->getById($productID);
        $isClick = $this->product->isClickable($product, $status);
        return $isClick == false ? 'false' : 'true';
    }

    /**
     * updateObject
     *
     * @param  string mixed $module
     * @param  int    mixed $objectID
     * @param  array  $param
     * @access public
     * @return void
     */
    public function updateObject($module, $objectID, $param = array())
    {
        global $tester;
        $objectModel = $tester->loadModel($module);

        $object = $objectModel->getById($objectID);
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field] = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

        $change = $objectModel->update($objectID);
        if($change == array()) $change = '没有数据更新';
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
    }
}
