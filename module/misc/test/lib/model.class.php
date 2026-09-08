<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class miscModelTest extends baseTest
{
    protected $moduleName = 'misc';
    protected $className  = 'model';

    /**
     * Test hello method.
     *
     * @access public
     * @return mixed
     */
    public function helloTest()
    {
        $result = $this->instance->hello();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test encodeStatistics method.
     *
     * @param  array $statistics
     * @access public
     * @return mixed
     */
    public function encodeStatisticsTest($statistics = array())
    {
        $result = $this->instance->encodeStatistics($statistics);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkOneClickPackage method.
     *
     * @access public
     * @return mixed
     */
    public function checkOneClickPackageTest()
    {
        $result = $this->instance->checkOneClickPackage();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRemind method.
     *
     * @access public
     * @return mixed
     */
    public function getRemindTest()
    {
        $result = $this->instance->getRemind();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildFeatureNoticePages method.
     *
     * @access public
     * @return mixed
     */
    public function buildFeatureNoticePagesTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildFeatureNoticePages', $args);
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
     * Test getPendingFeatureNotices method.
     *
     * @access public
     * @return mixed
     */
    public function getPendingFeatureNoticesTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getPendingFeatureNotices', $args);
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
     * Test markFeatureNoticesShown method.
     *
     * @access public
     * @return mixed
     */
    public function markFeatureNoticesShownTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('markFeatureNoticesShown', $args);
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