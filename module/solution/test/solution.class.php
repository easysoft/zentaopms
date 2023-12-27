<?php
declare(strict_types=1);
/**
 * The test class file of solution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     solution
 * @link        https://www.zentao.net
 */
class solutionTest
{
    public function __construct()
    {
        su('admin');

        global $tester;
        $this->objectModel = $tester->loadModel('solution');
    }

    public function getByIdTest(int $solutionID): object|null
    {
        return $this->objectModel->getByID($solutionID);
    }
}
