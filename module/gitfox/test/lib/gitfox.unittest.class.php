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
