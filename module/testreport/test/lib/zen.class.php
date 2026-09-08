<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class testreportZenTest extends baseTest
{
    protected $moduleName = 'testreport';
    protected $className  = 'zen';

    /**
     * Test commonAction method.
     *
     * @access public
     * @return mixed
     */
    public function commonActionTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('commonAction', $args);
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
