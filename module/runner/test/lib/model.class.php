<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class runnerModelTest extends baseTest
{
    protected $moduleName = 'runner';
    protected $className  = 'model';

    /**
     * Test deleteRunner method.
     *
     * @access public
     * @return mixed
     */
    public function deleteRunnerTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('deleteRunner', $args);
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
     * Test getLabels method.
     *
     * @access public
     * @return mixed
     */
    public function getLabelsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getLabels', $args);
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


    /**
     * Test isClickable method.
     *
     * @access public
     * @return mixed
     */
    public function isClickableTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('isClickable', $args);
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
     * Test update method.
     *
     * @access public
     * @return mixed
     */
    public function updateTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('update', $args);
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
