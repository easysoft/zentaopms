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
     * Get jenkins tasks.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getTasks($id)
    {
        $tasks = $this->jenkins->getTasks($id);
        if(empty($tasks)) return 0;
        return $tasks;
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
