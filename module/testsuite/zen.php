<?php
declare(strict_types=1);
class testsuiteZen extends testsuite
{
    /**
     * 检查当前登陆用户是否有访问testsuite的权限。
     * Check whether the current logged in user has permission to access tesusuite..
     *
     * @param  int    $suiteID
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function checkTestsuiteAccess(int $suiteID): object
    {
        if(!$suiteID) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => inlink('browse'))));
        $suite = $this->testsuite->getById($suiteID, true);
        if(!$suite) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => inlink('browse'))));
        if($suite->type == 'private' && $suite->addedBy != $this->app->user->account && !$this->app->user->admin) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => inlink('browse'))));
        return $suite;
    }
}
