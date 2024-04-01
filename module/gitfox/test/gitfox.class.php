<?php
class gitfoxTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->gitfox = $this->tester->loadModel('gitfox');
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
        $gitfox = $this->gitfox->getByID($id);
        if(empty($gitfox)) return 0;
        return $gitfox;
    }

    /**
     * Get gitfox list.
     *
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getList($orderBy = 'id_desc')
    {
        $gitfox = $this->gitfox->getList($orderBy);
        if(empty($gitfox)) return 0;
        return $gitfox;
    }

    /**
     * Get gitfox pairs
     *
     * @return string
     */
    public function getPairs()
    {
        $pairs = $this->gitfox->getPairs();
        return $pairs;
    }
}
