<?php
class fileZenTest
{
    public $fileZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('file');
        $tester->loadModel('file');

        $this->fileZenTest = initReference('file');
    }

    /**
     * Test getDownloadMode method.
     *
     * @param  object $file  文件对象
     * @param  string $mouse 鼠标操作类型
     * @access public
     * @return string
     */
    public function getDownloadModeZenTest($file = null, string $mouse = ''): string
    {
        $method = $this->fileZenTest->getMethod('getDownloadMode');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->fileZenTest->newInstance(), array($file, $mouse));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildDownloadTable method.
     *
     * @param  array  $fields   字段配置
     * @param  array  $rows     数据行
     * @param  string $kind     业务类型
     * @param  array  $rowspans 行合并配置
     * @param  array  $colspans 列合并配置
     * @access public
     * @return string
     */
    public function buildDownloadTableZenTest($fields = array(), $rows = array(), $kind = '', $rowspans = array(), $colspans = array()): string
    {
        $method = $this->fileZenTest->getMethod('buildDownloadTable');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->fileZenTest->newInstance(), array($fields, $rows, $kind, $rowspans, $colspans));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test unlinkRealFile method.
     *
     * @param  object $file 文件对象
     * @access public
     * @return mixed
     */
    public function unlinkRealFileZenTest($file): array
    {
        // 记录调用前的状态
        $beforeCount = 0;
        if(!empty($file) && isset($file->pathname))
        {
            $beforeCount = $this->tester->dao->select('COUNT(1) as count')->from(TABLE_FILE)->where('pathname')->eq($file->pathname)->fetch('count');
        }

        try 
        {
            $method = $this->fileZenTest->getMethod('unlinkRealFile');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->fileZenTest->newInstance(), array($file));
        }
        catch(TypeError $e)
        {
            // 处理类型错误，比如null对象
            return array(
                'beforeCount' => $beforeCount,
                'afterCount' => $beforeCount,
                'called' => false,
                'error' => 'type_error'
            );
        }
        catch(Exception $e)
        {
            return array(
                'beforeCount' => $beforeCount,
                'afterCount' => $beforeCount,
                'called' => false,
                'error' => $e->getMessage()
            );
        }
        
        // 检查调用后的状态
        $afterCount = 0;
        if(!empty($file) && isset($file->pathname))
        {
            $afterCount = $this->tester->dao->select('COUNT(1) as count')->from(TABLE_FILE)->where('pathname')->eq($file->pathname)->fetch('count');
        }

        if(dao::isError()) return dao::getError();
        
        return array(
            'beforeCount' => $beforeCount,
            'afterCount' => $afterCount,
            'called' => true
        );
    }

    /**
     * Test updateFileName method.
     *
     * @param  int    $fileID   文件ID
     * @param  string $fileName 文件名
     * @param  string $extension 扩展名
     * @access public
     * @return array
     */
    public function updateFileNameZenTest(int $fileID, string $fileName = '', string $extension = ''): array
    {
        // 设置POST数据
        $_POST['fileName'] = $fileName;
        $_POST['extension'] = $extension;

        try
        {
            $method = $this->fileZenTest->getMethod('updateFileName');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->fileZenTest->newInstance(), array($fileID));

            if(dao::isError())
            {
                $result = array('result' => 'fail', 'message' => 'Database error');
            }
        }
        catch(TypeError $e)
        {
            $result = array('result' => 'fail', 'message' => 'File not found or invalid data');
        }
        catch(Exception $e)
        {
            $result = array('result' => 'fail', 'message' => $e->getMessage());
        }
        finally
        {
            // 清理POST数据
            unset($_POST['fileName']);
            unset($_POST['extension']);
        }

        return $result;
    }
}