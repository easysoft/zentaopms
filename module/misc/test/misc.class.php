<?php
class Misc
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->misc = $tester->loadModel('misc');
    }

    /**
     * hello
     *
     * @access public
     * @return void
     */
    public function hello()
    {
        $miscs = $this->misc->hello();
        return $miscs;
    }

    /**
     * getTableAndStatus
     * 传入参数来获取表状态
     * @param  string $type
     * @access public
     * @return void
     */
    public function getTableAndStatus($type = 'check')
    {
        $miscs = $this->misc->getTableAndStatus($type = 'check');
        return $miscs;
    }

    /**
     * getRemind
     *
     * @access public
     * @return void
     */
    public function getRemind()
    {
        $miscs = $this->misc->getRemind();
        return $miscs;
    }

    /**
     * checkOneClickPackage
     *
     * @access public
     * @return void
     */
    public function checkOneClickPackage()
    {
        $miscs = $this->misc->checkOneClickPackage();
        return $miscs;
    }
}
?>
