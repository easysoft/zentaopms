<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class artifactZenTest extends baseTest
{
    protected $moduleName = 'artifact';
    protected $className  = 'zen';

    /**
     * Test buildParentPickerChildren method.
     *
     * @access public
     * @return mixed
     */
    public function buildParentPickerChildrenTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildParentPickerChildren', $args);
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
     * Test buildTreeAction method.
     *
     * @access public
     * @return mixed
     */
    public function buildTreeActionTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildTreeAction', $args);
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
     * Test getArtifactLibPickerItems method.
     *
     * @access public
     * @return mixed
     */
    public function getArtifactLibPickerItemsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getArtifactLibPickerItems', $args);
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
     * Test getArtifactLibTreeData method.
     *
     * @access public
     * @return mixed
     */
    public function getArtifactLibTreeDataTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getArtifactLibTreeData', $args);
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
     * Test getBreadCrumbs method.
     *
     * @access public
     * @return mixed
     */
    public function getBreadCrumbsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getBreadCrumbs', $args);
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
     * Test getNodeByPath method.
     *
     * @access public
     * @return mixed
     */
    public function getNodeByPathTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getNodeByPath', $args);
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
     * Test getParentPickerItems method.
     *
     * @access public
     * @return mixed
     */
    public function getParentPickerItemsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getParentPickerItems', $args);
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
