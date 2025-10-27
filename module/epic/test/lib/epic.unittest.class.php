<?php
declare(strict_types = 1);
class epicTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('epic');
        $this->objectTao   = $tester->loadTao('epic');
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
        
        // 确保$app->control存在并能加载story模型
        if(!isset($app->control))
        {
            $app->control = $tester;
        }
        
        $result = $this->objectModel->isClickable($data, $action);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getToAndCcList method.
     *
     * @param  object $story
     * @param  string $actionType
     * @access public
     * @return mixed
     */
    public function getToAndCcListTest($story = null, $actionType = '')
    {
        $result = $this->objectModel->getToAndCcList($story, $actionType);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}