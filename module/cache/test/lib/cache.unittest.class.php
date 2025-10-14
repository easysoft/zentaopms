<?php
declare(strict_types = 1);
class cacheTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('cache');
    }

    /**
     * Test clear method.
     *
     * @param  bool $needStart 是否需要重启缓存
     * @access public
     * @return mixed
     */
    public function clearTest($needStart = true)
    {
        $result = $this->objectModel->clear($needStart);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}