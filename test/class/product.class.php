<?php
class Product
{

    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->product = $tester->loadModel('product');
    }

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

    public function testGetLatestProject($productID)
    {
        $project = $this->product->getLatestProject($productID);
        if($project == false) return '没有数据';
        return $project;
    }

    public function getAllProducts($programID)
    {
        return $this->product->getList($programID);
    }

    public function getAllProductsCount($programID)
    {
        return count($this->getAllProducts($programID));
    }

    public function getNoclosedProducts($programID)
    {
        return $this->product->getList($programID, 'noclosed');
    }

    public function getNoclosedProductsCount($programID)
    {
        return count($this->getNoclosedProducts($programID));
    }

    public function getClosedProducts($programID)
    {
        return $this->product->getList($programID, 'closed');
    }

    public function getClosedProductsCount($programID)
    {
        return count($this->getClosedProducts($programID));
    }

    public function getInvolvedProducts($programID)
    {
        return $this->product->getList($programID, 'involved');
    }

    public function getInvolvedProductsCount($programID)
    {
        return count($this->getInvolvedProducts($programID));
    }

    public function getProductsByLine($programID, $line = 0)
    {
        return $this->product->getList($programID, 'all', 0, $line);
    }

    public function countProductsByLine($programID, $line = 0)
    {
        return count($this->getProductsByLine($programID, $line));
    }

    public function getProductList($programID, $status = 'all', $line = 0)
    {
        return $this->product->getList($programID, $status, 0, $line);
    }

    public function getProductCount($programID, $status = 'all', $line = 0)
    {
        return count($this->getProductList($programID, $status, $line));
    }
    public function getProductPairs($programID)
    {
        $pairs = $this->product->getPairs('', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getProductPairsCount($programID)
    {
        $pairsCount = count($this->getProductPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    public function getAllPairs($programID)
    {
        $pairs = $this->product->getPairs('all', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getAllPairsCount($programID)
    {
        $pairsCount = count($this->getAllPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }
    public function getNoclosedPairs($programID)
    {
        $pairs = $this->product->getPairs('noclosed', $programID);
        if($pairs == array()) return '没有数据';
        return $pairs;
    }
    public function getNoclosedPairsCount($programID)
    {
        $pairsCount = count($this->getNoclosedPairs($programID));
        if($pairsCount == '没有数据') return '0';
        return $pairsCount;
    }

    public function getProductPairsByOrder($programID, $orderBy = 'id_desc', $mode = '')
    {
        $this->product->orderBy = $orderBy;
        $pairs = $this->product->getPairs($mode, $programID);
        return checkOrder($pairs, $orderBy);
    }

    public function getAllProjectsByProduct($productID)
    {
        $projects = $this->product->getProjectListByProduct($productID, 'all');
        if($projects == array()) return '没有数据';
        return $projects;
    }

    public function getProjectsByStatus($productID, $status)
    {
        $projects = $this->product->getProjectListByProduct($productID, $browseType);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    public function getProjectPairsByProductID($productID)
    {
        $projects = $this->product->getProjectPairsByProduct($productID, 0, 0);
        if($projects == array()) return '没有数据';
        return $projects;
    }

    public function getAppendProject($projectID)
    {
        $project = $this->product->getProjectPairsByProduct(10086, 0, $projectID);
        if($project == array()) return '没有数据';
        return $project;
    }

    public function testIsClickable($productID, $status)
    {
        $product = $this->product->getById($productID);
        $isClick = $this->product->isClickable($product, $status);
        return $isClick == false ? 'false' : 'true';
    }

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
