<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class chartTaoTest extends baseTest
{
    protected $moduleName = 'chart';
    protected $className  = 'tao';

    /**
     * Test getRows method.
     *
     * @access public
     * @return mixed
     */
    public function getRowsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getRows', $args);
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
     * Test processRows method.
     *
     * @access public
     * @return mixed
     */
    public function processRowsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('processRows', $args);
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
     * Test switchFieldName method.
     *
     * @access public
     * @return mixed
     */
    public function switchFieldNameTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('switchFieldName', $args);
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
