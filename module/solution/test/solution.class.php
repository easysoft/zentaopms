<?php
declare(strict_types=1);

use function zin\wg;

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

    /**
     * Test getByID method.
     *
     * @param  int    $solutionID
     * @access public
     * @return object|null
     */
    public function getByIdTest(int $solutionID): object|null
    {
        return $this->objectModel->getByID($solutionID);
    }

    /**
     * Test getLastSolution method.
     *
     * @access public
     * @return object|false
     */
    public function getLastSolutionTest(): object|false
    {
        dao::$cache = array();
        return $this->objectModel->getLastSolution();
    }

    /**
     * Test saveLog method.
     *
     * @param  string $message
     * @access public
     * @return int
     */
    public function saveLogTest(string $message): int
    {
        global $app;
        $errorFile = $app->logRoot . 'php.' . date('Ymd') . '.log.php';
        file_put_contents($errorFile, '');

        $file = $this->objectModel->saveLog($message);
        return strlen(file_get_contents($file));
    }
}
