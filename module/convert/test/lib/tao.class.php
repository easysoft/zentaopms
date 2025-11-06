<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class convertTaoTest extends baseTest
{
    protected $moduleName = 'convert';
    protected $className  = 'tao';

    /**
     * Test importJiraIssue method.
     *
     * @param  array $dataList
     * @access public
     * @return mixed
     */
    public function importJiraIssueTest($dataList = array())
    {
        $result = $this->invokeArgs('importJiraIssue', array($dataList));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
