<?php
declare(strict_types = 1);
class repoZenSetBranchTagTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test setBranchTag method.
     *
     * @param  object $repo
     * @param  string $branchID
     * @access public
     * @return mixed
     */
    public function setBranchTagTest($repo, $branchID = '')
    {
        if(empty($repo) || !is_object($repo)) return false;

        if(!in_array($repo->SCM, $this->objectModel->config->repo->gitTypeList))
        {
            helper::setcookie('repoBranch', '', 0, $this->objectModel->config->webRoot, '', false, false);
            return array($branchID, array(), array());
        }

        $branches = ($repo->SCM == 'Gitlab') ? array('master' => 'master', 'develop' => 'develop', 'feature/test' => 'feature/test') : array('master' => 'master', 'develop' => 'develop');
        $tags = ($repo->SCM == 'Gitlab') ? array('v1.0' => 'v1.0', 'v2.0' => 'v2.0', 'v1.1' => 'v1.1') : array('v1.0' => 'v1.0', 'v2.0' => 'v2.0');

        if(empty($branchID) && !empty($_COOKIE['repoBranch'])) $branchID = $_COOKIE['repoBranch'];
        if(!isset($branches[$branchID]) && !isset($tags[$branchID])) $branchID = (string)key($branches);

        if($branchID)
        {
            helper::setcookie('repoBranch', $branchID, 0, $this->objectModel->config->webRoot, '', false, false);
            $_COOKIE['repoBranch'] = $branchID;
        }

        return array($branchID, $branches, $tags);
    }
}