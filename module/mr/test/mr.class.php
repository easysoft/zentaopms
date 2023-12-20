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

        /* Init gitlab mr data. */
        $this->objectModel->apiReopenMR(1, '3', 36);
        $this->objectModel->apiCloseMR(1, '3', 38);
        $this->objectModel->apiCloseMR(1, '3', 138);
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
     * Test createMR method.
     *
     * @param  object  $MR
     * @access public
     * @return array|object
     */
    public function createMrTester(object $MR): array|object
    {
        $this->objectModel->createMR($MR);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->fetchByID($this->objectModel->dao->lastInsertID());
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
     * Test afterApiCreate method.
     *
     * @param  int     $MRID
     * @param  object  $MR
     * @access public
     * @return array|object
     */
    public function afterApiCreateTester(int $MRID, object $MR): array|object
    {
        $this->objectModel->afterApiCreate($MRID, $MR);
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
     * @return array|object
     */
    public function apiCreateMrTester(int $hostID, string $project, object $params): array|object
    {
        $result = $this->objectModel->apiCreateMR($hostID, $project, $params);
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
        $result = $this->objectModel->apiCreateMR($hostID, $project, $params);
        $mrID   = empty($result->iid) ? $result->id : $result->iid;
        $this->objectModel->apiDeleteMR($hostID, $project, $mrID);
        return $this->objectModel->apiGetSingleMR($hostID, $project, $mrID);
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
        $this->objectModel->apiCloseMR($hostID, $project, $mrID);
        return $this->objectModel->apiGetSingleMR($hostID, $project, $mrID);
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
        return $this->objectModel->apiGetSingleMR($hostID, $project, $mriid);
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
        $result = $this->objectModel->link($MRID, 1, $type, array(1, 2));
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
        $result = $this->objectModel->unlink($MRID, 1, $type, 1);
        if(!$result) return $result;

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->fetch();
    }
}
