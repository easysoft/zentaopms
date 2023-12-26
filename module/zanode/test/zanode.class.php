<?php
declare(strict_types = 1);
class zanodeTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('zanode');
    }

    /**
     * 魔术方法，调用zanode模型中的方法。
     * Magic method, call the method in the zanode model.
     *
     * @param  string $name
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->objectModel->$name(...$arguments);
    }

    /**
     * 测试自动化设置。
     * Test set automation setting.
     *
     * @param  object       $object
     * @access public
     * @return object|array
     */
    public function setAutomationSettingTest(object $object): object|array
    {
        $resultID = $this->setAutomationSetting($object);
        if(dao::isError()) return dao::getError();
        $return = $this->objectModel->getAutomationByID($resultID);
        return $return;
        return $this->objectModel->getAutomationByID($resultID);
    }

    /**
     * 测试判断按钮是否可点击。
     * Test judge an action is clickable or not.
     *
     * @param  string $action
     * @param  string $status
     * @param  string $hostType
     * @access public
     * @return bool
     */
    public function isClickableTest(string $action, string $status, string $hostType): bool
    {
        $node = new stdclass();
        $node->status   = $status;
        $node->hostType = $hostType;
        return $this->isClickable($node, $action);
    }
}
