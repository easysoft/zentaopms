<?php
class jenkinsTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester   = $tester;
        $this->jenkins = $this->tester->loadModel('jenkins');
    }

    /**
     * 测试获取流水线列表。
     * Test get jenkins tasks.
     *
     * @param  int    $jenkinsID
     * @param  int    $depth
     * @access public
     * @return array
     */
    public function getTasks(int $jenkinsID, int $depth = 0)
    {
        return $this->jenkins->getTasks($jenkinsID, $depth);
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return object|string
     */
    public function create()
    {
        $jenkinsID = $this->jenkins->create();
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->tester->loadModel('pipeline')->getById($jenkinsID);
    }
}
