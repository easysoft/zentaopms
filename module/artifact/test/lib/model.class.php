<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class artifactModelTest extends baseTest
{
    protected $moduleName = 'artifact';
    protected $className  = 'model';

    /**
     * Test buildArtifactLibPickerItems method.
     *
     * @access public
     * @return mixed
     */
    public function buildArtifactLibPickerItemsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildArtifactLibPickerItems', $args);
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
     * Test getArtifactLibNodes method.
     *
     * @access public
     * @return mixed
     */
    public function getArtifactLibNodesTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getArtifactLibNodes', $args);
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
     * Test getAssetByIdList method.
     *
     * @access public
     * @return mixed
     */
    public function getAssetByIdListTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getAssetByIdList', $args);
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
     * Test getAssetListByNodeID method.
     *
     * @access public
     * @return mixed
     */
    public function getAssetListByNodeIDTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getAssetListByNodeID', $args);
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
     * Test getByRepoID method.
     *
     * @access public
     * @return mixed
     */
    public function getByRepoIDTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getByRepoID', $args);
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
     * Test getLibList method.
     *
     * @access public
     * @return mixed
     */
    public function getLibListTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getLibList', $args);
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
     * Test getLibListByScope method.
     *
     * @access public
     * @return mixed
     */
    public function getLibListByScopeTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getLibListByScope', $args);
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
     * Test getLibPairs method.
     *
     * @access public
     * @return mixed
     */
    public function getLibPairsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getLibPairs', $args);
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
     * Test getLibsByProduct method.
     *
     * @access public
     * @return mixed
     */
    public function getLibsByProductTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('getLibsByProduct', $args);
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
     * Test parseArtifactSize method.
     *
     * @access public
     * @return mixed
     */
    public function parseArtifactSizeTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('parseArtifactSize', $args);
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
     * Test parseDirname method.
     *
     * @access public
     * @return mixed
     */
    public function parseDirnameTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('parseDirname', $args);
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
     * Test restoreEntity method.
     *
     * @access public
     * @return mixed
     */
    public function restoreEntityTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('restoreEntity', $args);
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
     * Test uploadArtifact method.
     *
     * @access public
     * @return mixed
     */
    public function uploadArtifactTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('uploadArtifact', $args);
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
