<?php
class giteaTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->gitea  = $this->tester->loadModel('gitea');
    }

    /**
     * Get gitea pairs
     *
     * @return string
     */
    public function getPairs()
    {
        $pairs = $this->gitea->getPairs();
        return key($pairs);
    }

    /**
     * Get gitea tasks.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getTasks($id)
    {
        $tasks = $this->gitea->getTasks($id);
        if(empty($tasks)) return 0;
        return $tasks;
    }

    /**
     * Create a gitea.
     *
     * @access public
     * @return object|string
     */
    public function create()
    {
        $giteaID = $this->gitea->create();
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gitea->fetchByID($giteaID);
    }

    /**
     * Update a gitea.
     *
     * @param  int    $id
     * @access public
     * @return object|string
     */
    public function update($id)
    {
        $this->gitea->update($id);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gitea->fetchByID($id);
    }
}
