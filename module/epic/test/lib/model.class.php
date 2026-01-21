<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class epicModelTest extends baseTest
{
    protected $moduleName = 'epic';
    protected $className  = 'model';

    /**
     * Test getToAndCcList method.
     *
     * @param  object $story      故事对象
     * @param  string $actionType 操作类型
     * @access public
     * @return mixed
     */
    public function getToAndCcListTest($story, $actionType)
    {
        $result = $this->invokeArgs('getToAndCcList', array($story, $actionType));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  object $data
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function isClickableTest($data = null, $action = '')
    {
        global $app, $tester;

        // 确保$this->instance->appcontrol存在并能加载story模型
        if(!isset($this->instance->appcontrol))
        {
            $this->instance->appcontrol = $tester;
        }

        $result = $this->instance->isClickable($data, $action);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
