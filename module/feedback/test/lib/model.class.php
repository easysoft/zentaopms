<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class feedbackModelTest extends baseTest
{
    protected $moduleName = 'feedback';
    protected $className  = 'model';

    /**
     * Test getByList method.
     *
     * @access public
     * @return mixed
     */
    public function getByListTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getByList', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test getFeedbackPairs method.
     *
     * @access public
     * @return mixed
     */
    public function getFeedbackPairsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getFeedbackPairs', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test getList method.
     *
     * @access public
     * @return mixed
     */
    public function getListTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getList', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }

}
