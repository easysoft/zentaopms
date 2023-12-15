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
}
