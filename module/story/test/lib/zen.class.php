<?php
declare(strict_types = 1);
require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class storyZenTest extends baseTest
{
    protected $moduleName = 'story';
    protected $className  = 'zen';

    /**
     * 检查数据是否有变化。
     * Check if data has changed.
     *
     * @param  int       $storyID
     * @param  object    $storyData
     * @param  array     $postData
     * @access public
     * @return bool|array
     */
    public function checkDataChangedTest(int $storyID, object $storyData, array $postData): bool|array
    {
        $_POST = $postData;
        $result = $this->invokeArgs('checkDataChanged', [$storyID, $storyData]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
