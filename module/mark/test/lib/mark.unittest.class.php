<?php
declare(strict_types = 1);
class markTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('mark');
    }

    /**
     * Test getNeededMarks method.
     *
     * @param  array  $objectIDs
     * @param  string $objectType
     * @param  string $version
     * @param  string $mark
     * @access public
     * @return mixed
     */
    public function getNeededMarksTest(array $objectIDs, string $objectType, string $version, string $mark)
    {
        $result = $this->objectModel->getNeededMarks($objectIDs, $objectType, $version, $mark);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}