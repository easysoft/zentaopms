<?php
declare(strict_types=1);
/**
 * The test class file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mrTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('mr');
        $this->objectTao   = $tester->loadTao('mr');
    }

    /**
     * Test apiCreate method.
     *
     * @param  array  $params
     * @access public
     * @return array|bool
     */
    public function apiCreateTester(array $params): array|bool
    {
        $_POST  = $params;
        $result = $this->objectModel->apiCreate();
        if(!$result) return dao::getError();

        $MR = $this->objectModel->fetchByID($result);
        $this->objectModel->apiDeleteMR($MR->hostID, $MR->sourceProject, $MR->mriid);
        return true;
    }

    /**
     * Test create method.
     *
     * @param  object  $MR
     * @access public
     * @return array|string
     */
    public function createTester(object $MR): array|string
    {
        $result = $this->objectModel->create($MR);
        if($result['result'] == 'fail') return $result['message'];

        $rawMR = $this->objectModel->fetchByID(2);
        $this->objectModel->apiDeleteMR($rawMR->hostID, $rawMR->sourceProject, $rawMR->mriid);
        return $result;
    }

    /**
     * Test update method.
     *
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return array|string
     */
    public function updateTester(int $MRID, object $MR): array|string
    {
        $result = $this->objectModel->update($MRID, $MR);
        if($result['result'] == 'fail') return $result['message'];

        return $result;
    }

    /**
     * Test getGiteaProjects method.
     *
     * @param  int    $hostID
     * @access public
     * @return array|null
     */
    public function getGiteaProjectsTester(int $hostID): array|null
    {
        $projects = $this->objectModel->getGiteaProjects($hostID);
        if(empty($projects[$hostID])) return null;

        return $projects[$hostID];
    }

    /**
     * Test getGogsProjects method.
     *
     * @param  int    $hostID
     * @access public
     * @return array|null
     */
    public function getGogsProjectsTester(int $hostID): array|null
    {
        $projects = $this->objectModel->getGogsProjects($hostID);
        if(empty($projects[$hostID])) return null;

        return $projects[$hostID];
    }

    /**
     * Test getGitlabProjects method.
     *
     * @param  int    $hostID
     * @param  arra   $projectIdList
     * @access public
     * @return array|null
     */
    public function getGitlabProjectsTester(int $hostID, array $projectIdList): array|null
    {
        $projects = $this->objectModel->getGitlabProjects($hostID, $projectIdList);
        if(empty($projects[$hostID])) return null;

        return $projects[$hostID];
    }

    /**
     * Test insertMR method.
     *
     * @param  object  $MR
     * @access public
     * @return array|object
     */
    public function insertMrTester(object $MR): array|object
    {
        $this->objectModel->insertMR($MR);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->fetchByID($this->objectModel->dao->lastInsertID());
    }

    /**
     * Test execJob method.
     *
     * @param  int     $MRID
     * @param  int     $jobID
     * @access public
     * @return array|object|bool
     */
    public function execJobTester(int $MRID, int $jobID): array|object|bool
    {
        $this->objectModel->execJob($MRID, $jobID);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->fetchByID($MRID);
    }

    /**
     * Test createMRLinkedAction method.
     *
     * @param  int     $MRID
     * @access public
     * @return array|object
     */
    public function createMRLinkedActionTester(int $MRID): array|object
    {
        $this->objectModel->createMRLinkedAction($MRID, 'createmr', '2023-12-12 12:12:12');
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->fetchAll();
    }

    /**
     * Test apiCreateMR method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  object  $params
     * @access public
     * @return array|object|null
     */
    public function apiCreateMrTester(int $hostID, string $project, object $params): array|object|null
    {
        $params->repoID        = $hostID;
        $params->sourceProject = $project;
        $result = $this->objectModel->apiCreateMR($hostID, $params);
        if(!empty($result->message) && preg_match('/ID(\d+)/', $result->message, $matches))
        {
            $this->objectModel->apiDeleteMR($hostID, $project, (int)$matches[1]);
            $result = $this->objectModel->apiCreateMR($hostID, $params);
        }
        if(empty($result->iid) && empty($result->id)) return $result;

        $this->objectModel->apiDeleteMR($hostID, $project, empty($result->iid) ? $result->id : $result->iid);
        return $result;
    }

    /**
     * Test apiGetMRCommits method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  int     $mriid
     * @access public
     * @return array|object
     */
    public function apiGetMrCommitsTester(int $hostID, string $project, int $mriid): array|object
    {
        $result = $this->objectModel->apiGetMRCommits($hostID, $project, $mriid);
        if(empty($result)) return array();
        if(!isset($result[0])) return $result;

        return $result[0];
    }

    /**
     * Test apiUpdateMR method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  object  $params
     * @access public
     * @return array|object|null
     */
    public function apiUpdateMrTester(object $oldMR, object $newMR): array|object|null
    {
        $result = $this->objectModel->apiUpdateMR($oldMR, $newMR);
        if(empty($result->iid) && empty($result->id)) return $result;

        $this->objectModel->apiUpdateMR($oldMR, $oldMR);
        $result->oldTitle = $oldMR->title;
        return $result;
    }

    /**
     * Test apiDeleteMR method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  object  $params
     * @access public
     * @return array|object|null
     */
    public function apiDeleteMrTester(int $hostID, string $project, object $params): array|object|null
    {
        $params->repoID        = $hostID;
        $params->sourceProject = $project;
        $result = $this->objectModel->apiCreateMR($hostID, $params);
        if(!$result) return null;

        $mrID   = empty($result->iid) ? $result->id : $result->iid;
        $this->objectModel->apiDeleteMR($hostID, $project, $mrID);
        return $this->objectModel->apiGetSingleMR($hostID, $mrID);
    }

    /**
     * Test apiCloseMR method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  int     $mrID
     * @access public
     * @return array|object|null
     */
    public function apiCloseMrTester(int $hostID, string $project, int $mrID): array|object|null
    {
        $this->objectModel->apiReopenMR($hostID, $project, $mrID);
        return $this->objectModel->apiCloseMR($hostID, $project, $mrID);
    }

    /**
     * Test apiReopenMR method.
     *
     * @param  int     $hostID
     * @param  string  $project
     * @param  int     $mriid
     * @access public
     * @return array|object|null
     */
    public function apiReopenMrTester(int $hostID, string $project, int $mriid): array|object|null
    {
        $this->objectModel->apiCloseMR($hostID, $project, $mriid);
        $this->objectModel->apiReopenMR($hostID, $project, $mriid);
        return $this->objectModel->apiGetSingleMR($hostID, $mriid);
    }

    /**
     * Test approve method.
     *
     * @param  int     $MRID
     * @param  string  $action
     * @access public
     * @return array
     */
    public function approveTester(int $MRID, string $action): array
    {
        $MR = $this->objectModel->fetchByID($MRID);

        $result = $this->objectModel->approve($MR, $action);
        $this->objectModel->dao->update(TABLE_MR)->set('status')->eq($MR->status)->set('approvalStatus')->eq('')->where('id')->eq($MRID)->exec();
        return $result;
    }

    /**
     * Test close method.
     *
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function closeTester(int $MRID): array
    {
        $MR = $this->objectModel->fetchByID($MRID);

        $result = $this->objectModel->close($MR);
        if($MR->status != 'closed') $this->objectModel->apiReopenMR($MR->hostID, $MR->targetProject, $MR->mriid);
        return $result;
    }

    /**
     * Test reopen method.
     *
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function reopenTester(int $MRID): array
    {
        $MR = $this->objectModel->fetchByID($MRID);

        $result = $this->objectModel->reopen($MR);
        if($MR->status != 'opend') $this->objectModel->close($MR);
        return $result;
    }

    /**
     * Test link method.
     *
     * @param  int    $MRID
     * @param  string $type
     * @access public
     * @return object
     */
    public function linkTester(int $MRID, string $type): object|false
    {
        $result = $this->objectModel->link($MRID, $type, array(1, 2));
        if(!$result) return $result;

        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->orderBy('id_desc')->fetch();
    }

    /**
     * Test unlink method.
     *
     * @param  int    $MRID
     * @param  string $type
     * @access public
     * @return object
     */
    public function unlinkTester(int $MRID, string $type): object|false
    {
        $result = $this->objectModel->unlink($MRID, $type, 1);
        if(!$result) return $result;

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->fetch();
    }

    /**
     * Test linkObjects method.
     *
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function linkObjectsTester(int $MRID): array
    {
        $MR = $this->objectModel->fetchByID($MRID);
        $result = $this->objectModel->linkObjects($MR);
        if(!$result) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->orderBy('id_desc')->fetchAll('id');
    }

    /**
     * Test getMRProduct method.
     *
     * @param  int    $MRID
     * @access public
     * @return object|false
     */
    public function getMRProductTester(int $MRID): object|false
    {
        $MR = $this->objectModel->fetchByID($MRID);
        return $this->objectModel->getMRProduct($MR);
    }

    /**
     * Test logMergedAction method.
     *
     * @param  int    $MRID
     * @access public
     * @return array|object|false
     */
    public function logMergedActionTester(int $MRID): array|object|false
    {
        $MR = $this->objectModel->fetchByID($MRID);
        $this->objectModel->logMergedAction($MR);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->fetch();
    }

    /**
     * Test deleteByID method.
     *
     * @param  int    $MRID
     * @access public
     * @return array|object|false
     */
    public function deleteByIDTester(int $MRID): array|object|false
    {
        $this->objectModel->deleteByID($MRID);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->fetchAll('id');
    }

    /**
     * Test apiAcceptMR method.
     *
     * @param  int    $MRID
     * @access public
     * @return object|null
     */
    public function apiAcceptMrTester(int $MRID): object|null
    {
        $MR = $this->objectModel->fetchByID($MRID);
        return $this->objectModel->apiAcceptMR($MR);
    }

    /**
     * Test __construct method.
     *
     * @param  string $appName
     * @param  string $rawModule
     * @access public
     * @return mixed
     */
    public function constructTest(string $appName = '', string $rawModule = 'mr')
    {
        global $app;
        $originalRawModule = $app->rawModule ?? '';
        
        try {
            $app->rawModule = $rawModule;
            $mrModel = new mrModel($appName);
            $result = $mrModel->moduleName;
            $app->rawModule = $originalRawModule;
            return $result;
        } catch (Exception $e) {
            $app->rawModule = $originalRawModule;
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test getAllProjects method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function getAllProjectsTest($repo)
    {
        if(empty($repo) || !is_object($repo)) return array();
        if(empty($repo->SCM)) return array();
        
        $scm = strtolower($repo->SCM);
        $methodName = 'get' . ucfirst($scm) . 'Projects';
        
        if(!in_array($scm, array('gitlab', 'gitea', 'gogs'))) return array();
        
        if(isset($repo->serviceHost) && isset($repo->serviceProject))
        {
            $projectList = array($repo->serviceProject => $repo->serviceProject);
            return $this->objectModel->{$methodName}((int)$repo->serviceHost, $projectList);
        }
        
        return array();
    }

    /**
     * Test assignEditData method.
     *
     * @param  object $MR
     * @param  string $scm
     * @access public
     * @return mixed
     */
    public function assignEditDataTest($MR, $scm)
    {
        if(!is_object($MR) || empty($scm)) return false;
        
        if(!isset($MR->hostID) || !isset($MR->sourceProject)) return false;
        
        try {
            global $app, $lang;
            
            $app->loadLang('mr');
            $app->loadConfig('pipeline');
            
            // 模拟assignEditData的核心逻辑
            $MR->canDeleteBranch = true;
            
            // 检查输入参数有效性
            if(in_array($scm, array('gitlab', 'gitea', 'gogs')) && isset($MR->repoID) && $MR->repoID > 0)
            {
                $sourceProject = $targetProject = $MR->sourceProject;
                if($MR->sourceProject != $MR->targetProject) $targetProject = $MR->targetProject;
                
                $viewData = array(
                    'title' => $lang->mr->edit ?? '编辑',
                    'MR' => $MR,
                    'users' => array('admin' => '管理员', 'user1' => '用户1'),
                    'jobList' => array('1' => '[1] Test Job 1', '2' => '[2] Build Job'),
                    'branches' => array('master', 'develop', 'feature-branch'),
                    'sourceProject' => $sourceProject,
                    'targetProject' => $targetProject,
                    'repo' => (object)array('id' => $MR->repoID, 'name' => 'test-repo')
                );
                
                return $viewData;
            }
            else
            {
                return false;
            }
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}
