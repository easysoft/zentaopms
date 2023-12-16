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
}
