<?php
class gogsTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->gogs   = $this->tester->loadModel('gogs');
    }

    /**
     * Get gogs pairs
     *
     * @return string
     */
    public function getPairs()
    {
        $pairs = $this->gogs->getPairs();
        return key($pairs);
    }

    /**
     * Create a gogs.
     *
     * @access public
     * @return object|string
     */
    public function create()
    {
        $gogsID = $this->gogs->create();
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gogs->fetchByID($gogsID);
    }

    /**
     * Update a gogs.
     *
     * @param  int    $id
     * @access public
     * @return object|string
     */
    public function update($id)
    {
        $this->gogs->update($id);
        if(dao::isError())
        {
            $errors = dao::getError();
            return key($errors);
        }

        return $this->gogs->fetchByID($id);
    }
}
