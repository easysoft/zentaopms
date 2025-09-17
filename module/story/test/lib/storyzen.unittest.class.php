<?php
class storyZenTest
{
    public $storyZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('story');
        $this->objectModel = $tester->loadModel('story');

        $this->storyZenTest = initReference('story');
    }

    /**
     * 获取批量创建需求的表单字段。
     * Get form fields for batch create.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getFormFieldsForBatchCreateTest(int $productID, string $branch, string $storyType): array
    {
        global $config;
        $config->story->gradeRule = $config->requirement->gradeRule = $config->epic->gradeRule = '';

        $method = $this->storyZenTest->getMethod('getFormFieldsForBatchCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->storyZenTest->newInstance(), [$productID, $branch, 0, $storyType]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setMenuForCreate method.
     *
     * @param  int    $productID
     * @param  int    $objectID
     * @param  string $extra
     * @access public
     * @return mixed
     */
    public function setMenuForCreateTest(int $productID, int $objectID, string $extra = '')
    {
        $method = $this->storyZenTest->getMethod('setMenuForCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->storyZenTest->newInstance(), [$productID, $objectID, $extra]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
