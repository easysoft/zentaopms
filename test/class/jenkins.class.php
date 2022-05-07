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
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $jenkins = $this->jenkins->getByID($id);
        if(empty($jenkins)) return 0;
        return $jenkins;
    }

    /**
     * Get jenkins list.
     *
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getList($orderBy = 'id_desc')
    {
        $jenkins = $this->jenkins->getList($orderBy);
        if(empty($jenkins)) return 0;
        return array_shift($jenkins);
    }

    /**
     * Get jenkins pairs
     *
     * @return string
     */
    public function getPairs()
    {
        $pairs = $this->jenkins->getPairs();
        return key($pairs);
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

        return $this->jenkins->getById($jenkinsID);
    }

    /**
     * Update a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return object|string
     */
    public function update($id)
    {
        $this->jenkins->update($id);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->jenkins->getById($id);
    }
}
