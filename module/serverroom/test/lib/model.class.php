<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class serverroomModelTest extends baseTest
{
    protected $moduleName = 'serverroom';
    protected $className  = 'model';

    /**
     * Test create method.
     *
     * @param  object $roomData
     * @access public
     * @return mixed
     */
    public function createTest(object $roomData)
    {
        $result = $this->instance->create($roomData);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test update method.
     *
     * @param  int    $roomID
     * @param  object $roomData
     * @access public
     * @return array|bool
     */
    public function updateTest(int $roomID, object $roomData): array|bool
    {
        $result = $this->instance->update($roomID, $roomData);
        if($result) return $result;

        return dao::getError();
    }

    /**
     * Test getPairs method.
     *
     * @access public
     * @return array
     */
    public function getPairsTest(): array
    {
        $result = $this->instance->getPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
