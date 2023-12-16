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
}
