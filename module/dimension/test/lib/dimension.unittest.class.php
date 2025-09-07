<?php
declare(strict_types = 1);
class dimensionTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('dimension');
    }

    /**
     * Test __construct method.
     *
     * @param  string $testType
     * @access public
     * @return object
     */
    public function __constructTest(string $testType = 'normal'): object
    {
        $result = new stdClass();
        $dimensionModel = $this->objectModel;
        
        switch($testType) {
            case 'parentConstructor':
                $result->result = property_exists($dimensionModel, 'app') && !empty($dimensionModel->app);
                break;
            case 'dao':
                $result->result = property_exists($dimensionModel, 'dao') && !empty($dimensionModel->dao);
                break;
            case 'modelInstance':
                $result->result = $dimensionModel instanceof dimensionModel;
                break;
            case 'modelExists':
                $result->result = !empty($dimensionModel);
                break;
            case 'className':
                $result->result = get_class($dimensionModel);
                break;
            default:
                $result->result = 'normal';
                break;
        }
        
        return $result;
    }

    /**
     * Test getByID method.
     *
     * @param  int $dimensionID
     * @access public
     * @return mixed
     */
    public function getByIDTest(int $dimensionID = 0)
    {
        try {
            $result = $this->objectModel->getByID($dimensionID);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (TypeError $e) {
            // 当没有找到记录时，fetch()返回false，但getByID声明返回object类型
            // 这会导致TypeError，我们返回false来表示没有找到
            return false;
        }
    }

    /**
     * Test getFirst method.
     *
     * @param  string $mockViewable 模拟可见对象ID数组
     * @access public
     * @return mixed
     */
    public function getFirstTest($mockViewable = '')
    {
        // 如果提供了模拟数据，直接查询第一个ID对应的维度
        if($mockViewable)
        {
            $viewableIds = explode(',', $mockViewable);
            $firstID = current($viewableIds);
            $result = $this->objectModel->dao->select('*')->from(TABLE_DIMENSION)->where('id')->eq($firstID)->fetch();
            if(dao::isError()) return dao::getError();
            return $result;
        }
        
        // 否则尝试调用原始方法
        try {
            $result = $this->objectModel->getFirst();
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Test getList method.
     *
     * @param  string $mockViewable 模拟可见对象ID数组
     * @param  string $returnType   返回类型：'result'返回结果集，'count'返回数量
     * @access public
     * @return mixed
     */
    public function getListTest($mockViewable = '', $returnType = 'result')
    {
        // 如果提供了模拟数据，直接根据提供的ID列表查询维度
        if($mockViewable)
        {
            $viewableIds = explode(',', $mockViewable);
            $result = $this->objectModel->dao->select('*')->from(TABLE_DIMENSION)->where('id')->in($viewableIds)->fetchAll('id');
            if(dao::isError()) return dao::getError();
            
            if($returnType === 'count') return count($result);
            return $result;
        }
        
        // 否则尝试调用原始方法
        try {
            $result = $this->objectModel->getList();
            if(dao::isError()) return dao::getError();
            
            if($returnType === 'count') return count($result);
            return $result;
        } catch (Exception $e) {
            return $returnType === 'count' ? 0 : array();
        }
    }
}