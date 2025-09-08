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

    /**
     * Test getMarks method.
     *
     * @param  array  $objects
     * @param  string $objectType
     * @param  string $mark
     * @access public
     * @return array
     */
    public function getMarksTest(array $objects, string $objectType, string $mark): array
    {
        $result = $this->objectModel->getMarks($objects, $objectType, $mark);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isMark method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $version
     * @param  string $mark
     * @access public
     * @return mixed
     */
    public function isMarkTest(string $objectType, int $objectID, string $version, string $mark = 'view')
    {
        $result = $this->objectModel->isMark($objectType, $objectID, $version, $mark);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}