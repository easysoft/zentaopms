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
     * 测试获取 Jenkins 流水线。
     * Test get jobs by jenkins .
     *
     * @param  int    $jenkinsID
     * @access public
     * @return string
     */
    public function getJobPairsTest(int $jenkinsID): string
    {
        $jobs = $this->jenkins->getJobPairs($jenkinsID);
        $return = '';
        foreach($jobs as $jobID => $job) $return .= "{$jobID}:{$job},";
        return trim($return, ',');
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
