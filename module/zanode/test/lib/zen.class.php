<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class zanodeZenTest extends baseTest
{
    protected $moduleName = 'zanode';
    protected $className  = 'zen';

    /**
     * Test handleNode method.
     *
     * @access public
     * @return mixed
     */
    public function handleNodeTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('handleNode', $args);
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
     * Test prepareCreateSnapshotExtras method.
     *
     * @access public
     * @return mixed
     */
    public function prepareCreateSnapshotExtrasTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('prepareCreateSnapshotExtras', $args);
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
