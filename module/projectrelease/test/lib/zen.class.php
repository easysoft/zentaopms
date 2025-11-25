<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class projectreleaseZenTest extends baseTest
{
    protected $moduleName = 'projectrelease';
    protected $className  = 'zen';

    /**
     * Test buildReleaseForCreate method.
     *
     * @param  int    $projectID
     * @access public
     * @return mixed
     */
    public function buildReleaseForCreateTest(int $projectID = 0)
    {
        try {
            /* Clear previous errors. */
            dao::$errors = array();

            $result = $this->invokeArgs('buildReleaseForCreate', [$projectID]);

            /* Check for DAO errors after invocation. */
            if(dao::isError()) return dao::getError();

            /* If result is false, also check for errors. */
            if($result === false)
            {
                if(dao::isError()) return dao::getError();
                return false;
            }

            return $result;
        } catch (EndResponseException $e) {
            /* This exception is thrown by form validation when it fails. */
            /* Return the DAO errors that were set before the exception. */
            if(dao::isError()) return dao::getError();
            return false;
        } catch (Exception $e) {
            /* Check if we have DAO errors. */
            if(dao::isError()) return dao::getError();
            return false;
        } catch (Throwable $e) {
            /* For PHP 7+ compatibility, also catch Throwable. */
            if(dao::isError()) return dao::getError();
            return false;
        }
    }

    /**
     * Test commonAction method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return mixed
     */
    public function commonActionTest(int $projectID = 0, int $productID = 0, string $branch = '')
    {
        $errorLevel = error_reporting();
        error_reporting($errorLevel & ~E_WARNING);
        global $app;

        try {
            dao::$errors = array();
            $this->objectModel->app->user->admin = true;
            $app->user->admin = true;
            $result = $this->invokeArgs('commonAction', [$projectID, $productID, $branch]);
            if(dao::isError()) return dao::getError();

            $returnData = new stdclass();
            $returnData->products = $this->objectModel->view->products ?? array();
            $returnData->product  = $this->objectModel->view->product ?? new stdclass();
            $returnData->branches = $this->objectModel->view->branches ?? array();
            $returnData->branch   = $this->objectModel->view->branch ?? '';
            $returnData->project  = $this->objectModel->view->project ?? new stdclass();
            $returnData->appList  = $this->objectModel->view->appList ?? array();
            error_reporting($errorLevel);
            return $returnData;
        } catch (EndResponseException $e) {
            error_reporting($errorLevel);
            return 'redirect';
        } catch (Exception | Throwable $e) {
            error_reporting($errorLevel);
            if(dao::isError()) return dao::getError();
            return false;
        }
    }
}
