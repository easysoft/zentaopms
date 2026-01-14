<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class markModelTest extends baseTest
{
    protected $moduleName = 'mark';
    protected $className  = 'model';

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
        $result = $this->instance->getNeededMarks($objectIDs, $objectType, $version, $mark);
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
        $result = $this->instance->getMarks($objects, $objectType, $mark);
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
        $result = $this->instance->isMark($objectType, $objectID, $version, $mark);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test hasMark method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $version
     * @param  string $mark
     * @param  bool   $onlyMajor
     * @access public
     * @return bool
     */
    public function hasMarkTest(string $objectType, int $objectID, string $version = 'all', string $mark = 'view', bool $onlyMajor = false): bool
    {
        $result = $this->instance->hasMark($objectType, $objectID, $version, $mark, $onlyMajor);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setMark method.
     *
     * @param  array  $objectIDs
     * @param  string $objectType
     * @param  string $version
     * @param  string $mark
     * @param  string $extra
     * @access public
     * @return bool
     */
    public function setMarkTest(array $objectIDs, string $objectType, string $version, string $mark, string $extra = '')
    {
        $result = $this->instance->setMark($objectIDs, $objectType, $version, $mark, $extra);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}