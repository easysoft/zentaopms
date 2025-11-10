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
}
