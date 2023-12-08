<?php
class svnTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('svn');
    }

    /**
     * Test run method.
     *
     * @access public
     * @return object|bool
     */
    public function runTest(): object|bool
    {
        ob_start();
        $this->objectModel->run();
        ob_get_clean();
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq(2)->fetch();
    }
}
