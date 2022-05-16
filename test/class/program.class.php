<?php
class programTest
{
    /**
     * __construct
     *
     * @param  mixed $user
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $tester;

        $this->program = $tester->loadModel('program');
    }

    /**
     * Test create program.
     *
     * @param  array $data
     * @access public
     * @return object
     */
    public function create($data)
    {
        $_POST = $data;

        $programID = $this->program->create();

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getById($programID);

        return $program;
    }

    /**
     * createStakeholder
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function createStakeholder($programID)
    {
        $_POST['accounts'] = array('dev1', 'dev2');
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }

    /**
     * Test get list.
     *
     * @param  mixed  $status
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $type       top|child
     * @param  mixed  $idList
     * @access public
     * @return void
     */
    public function getList($status = 'all', $orderBy = 'id_asc', $pager = NULL, $type = '', $idList = '')
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status, $orderBy, $pager, $type, $idList);

        if(dao::isError()) return array('message' => dao::getError());

        return $programs;
    }


    /**
     * update
     *
     * @param  mixed  $proguamID
     * @param  mixed  $data
     * @access public
     * @return void
     */
    public function update($programID, $data)
    {
        $_POST = $data;
        $this->program->update($programID);
        if(dao::isError()) return array('message' => dao::getError());

        return $this->program->getByID($programID);
    }
}
