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
}
