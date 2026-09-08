<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class chartModelTest extends baseTest
{
    protected $moduleName = 'chart';
    protected $className  = 'model';

    /**
     * Test getByID method.
     *
     * @param  int $chartID
     * @access public
     * @return object|false
     */
    public function getByIdTest(int $chartID): object|false
    {
        $result = $this->instance->getByID($chartID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTreeMenu method.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function getTreeMenuTest(int $groupID): array
    {
        $result = $this->instance->getTreeMenu($groupID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDefaultCharts method.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function getDefaultChartsTest(int $groupID): array
    {
        $result = $this->instance->getDefaultCharts($groupID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test switchFieldName method.
     *
     * @param  array  $fields
     * @param  array  $langs
     * @param  array  $metrics
     * @param  string $index
     * @access public
     * @return string
     */
    public function switchFieldNameTest(array $fields, array $langs, array $metrics, string $index): string
    {
        $result = $this->objectTao->switchFieldName($fields, $langs, $metrics, $index);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isChartHaveData method.
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return bool
     */
    public function isChartHaveDataTest(array $options, string $type): bool
    {
        $result = $this->instance->isChartHaveData($options, $type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  int    $chartID
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function isClickableTest(int $chartID, string $action)
    {
        $chart = $this->getByIdTest($chartID);
        if(!$chart) return false;

        $result = chartModel::isClickable($chart, $action);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('__construct', $args);
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
     * Test addFormatter4Echart method.
     *
     * @access public
     * @return mixed
     */
    public function addFormatter4EchartTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('addFormatter4Echart', $args);
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
     * Test addRotate4Echart method.
     *
     * @access public
     * @return mixed
     */
    public function addRotate4EchartTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('addRotate4Echart', $args);
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
     * Test checkAccess method.
     *
     * @access public
     * @return mixed
     */
    public function checkAccessTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkAccess', $args);
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
     * Test genCluBar method.
     *
     * @access public
     * @return mixed
     */
    public function genCluBarTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('genCluBar', $args);
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
     * Test genLineChart method.
     *
     * @access public
     * @return mixed
     */
    public function genLineChartTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('genLineChart', $args);
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
     * Test genPie method.
     *
     * @access public
     * @return mixed
     */
    public function genPieTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('genPie', $args);
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
     * Test genRadar method.
     *
     * @access public
     * @return mixed
     */
    public function genRadarTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('genRadar', $args);
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
     * Test genWaterpolo method.
     *
     * @access public
     * @return mixed
     */
    public function genWaterpoloTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('genWaterpolo', $args);
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
     * Test getEchartOptions method.
     *
     * @access public
     * @return mixed
     */
    public function getEchartOptionsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getEchartOptions', $args);
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
     * Test getFilterFormat method.
     *
     * @access public
     * @return mixed
     */
    public function getFilterFormatTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getFilterFormat', $args);
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
     * Test getFirstGroup method.
     *
     * @access public
     * @return mixed
     */
    public function getFirstGroupTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getFirstGroup', $args);
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
     * Test getMultiData method.
     *
     * @access public
     * @return mixed
     */
    public function getMultiDataTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getMultiData', $args);
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
     * Test getSysOptions method.
     *
     * @access public
     * @return mixed
     */
    public function getSysOptionsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getSysOptions', $args);
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
     * Test processChart method.
     *
     * @access public
     * @return mixed
     */
    public function processChartTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('processChart', $args);
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
