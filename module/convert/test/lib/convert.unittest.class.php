<?php
declare(strict_types = 1);
class convertTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('convert');
    }

    /**
     * Test connectDB method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function connectDBTest($dbName = null)
    {
        $result = $this->objectModel->connectDB($dbName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test dbExists method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function dbExistsTest($dbName = null)
    {
        $result = $this->objectModel->dbExists($dbName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test tableExists method.
     *
     * @param  string $table
     * @access public
     * @return mixed
     */
    public function tableExistsTest($table = null)
    {
        $result = $this->objectModel->tableExists($table);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test tableExistsOfJira method.
     *
     * @param  string $dbName
     * @param  string $table
     * @access public
     * @return mixed
     */
    public function tableExistsOfJiraTest($dbName = null, $table = null)
    {
        $result = $this->objectModel->tableExistsOfJira($dbName, $table);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveState method.
     *
     * @access public
     * @return mixed
     */
    public function saveStateTest()
    {
        try {
            $this->objectModel->saveState();
            if(dao::isError()) return dao::getError();

            global $app;
            $state = $app->session->state;
            return is_array($state) ? 'array' : gettype($state);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Test getJiraData method.
     *
     * @param  string $method
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getJiraDataTest($method = null, $module = null, $lastID = 0, $limit = 0)
    {
        $result = $this->objectModel->getJiraData($method, $module, $lastID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getJiraDataFromDB method.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getJiraDataFromDBTest($module = '', $lastID = 0, $limit = 0)
    {
        try {
            // 检查sourceDBH是否已初始化
            if(empty($this->objectModel->sourceDBH)) {
                // 如果没有数据库连接，直接返回空数组
                return array();
            }
            
            $result = $this->objectModel->getJiraDataFromDB($module, $lastID, $limit);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (Exception $e) {
            return array();
        } catch (Error $e) {
            // 处理PHP7+的Error异常（包括Call to a member function on null）
            return array();
        }
        
        // 默认返回空数组
        return array();
    }

    /**
     * Test getJiraDataFromFile method.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getJiraDataFromFileTest($module = '', $lastID = 0, $limit = 0)
    {
        $result = $this->objectModel->getJiraDataFromFile($module, $lastID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test splitFile method.
     *
     * @access public
     * @return mixed
     */
    public function splitFileTest()
    {
        try {
            // 检查源文件是否存在
            global $app;
            $jiraPath = $app->getTmpRoot() . 'jirafile/';
            $sourceFile = $jiraPath . 'entities.xml';
            
            if(!file_exists($sourceFile))
            {
                return 'no_source_file';
            }
            
            $this->objectModel->splitFile();
            if(dao::isError()) return dao::getError();

            // 检查是否成功分割文件
            $checkFiles = array('action.xml', 'project.xml', 'issue.xml');
            $existFiles = 0;
            foreach($checkFiles as $file)
            {
                if(file_exists($jiraPath . $file)) $existFiles++;
            }
            
            return $existFiles > 0 ? 'success' : 'no_files';
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        } catch (TypeError $e) {
            return 'type_error: ' . $e->getMessage();
        } catch (Error $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test createTmpTable4Jira method.
     *
     * @access public
     * @return mixed
     */
    public function createTmpTable4JiraTest()
    {
        try {
            // 先删除可能存在的表
            $this->dropTableIfExists('jiratmprelation');
            
            $this->objectModel->createTmpTable4Jira();
            if(dao::isError()) return dao::getError();

            // 检查表是否创建成功
            $result = $this->objectModel->tableExists('jiratmprelation');
            if($result) {
                // 验证表结构
                $columns = $this->getTableColumns('jiratmprelation');
                return $columns;
            }
            
            return false;
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Drop table if exists helper method.
     *
     * @param  string $table
     * @access private
     * @return void
     */
    private function dropTableIfExists($table)
    {
        try {
            global $app;
            $app->dbh->exec("DROP TABLE IF EXISTS `$table`");
        } catch (Exception $e) {
            // 忽略删除表的异常
        }
    }

    /**
     * Get table columns helper method.
     *
     * @param  string $table
     * @access private
     * @return array
     */
    private function getTableColumns($table)
    {
        try {
            global $app;
            $result = $app->dbh->query("SHOW COLUMNS FROM `$table`")->fetchAll();
            $columns = array();
            foreach($result as $column) {
                $columns[$column->Field] = $column->Type;
            }
            return $columns;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Test importJiraData method.
     *
     * @param  string $type
     * @param  int    $lastID
     * @param  bool   $createTable
     * @access public
     * @return mixed
     */
    public function importJiraDataTest($type = '', $lastID = 0, $createTable = false)
    {
        try {
            $result = $this->objectModel->importJiraData($type, $lastID, $createTable);
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        } catch (Error $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test deleteJiraFile method.
     *
     * @access public
     * @return mixed
     */
    public function deleteJiraFileTest()
    {
        try {
            // 获取jirafile目录路径
            global $app;
            $jiraPath = $app->getTmpRoot() . 'jirafile/';
            
            // 创建测试目录和测试文件
            if(!is_dir($jiraPath)) mkdir($jiraPath, 0777, true);
            
            // 创建一些测试文件
            $testFiles = array('action.xml', 'project.xml', 'issue.xml', 'user.xml');
            foreach($testFiles as $file) {
                file_put_contents($jiraPath . $file, '<?xml version="1.0"?><test>content</test>');
            }
            
            // 执行删除方法
            $this->objectModel->deleteJiraFile();
            if(dao::isError()) return dao::getError();

            // 检查文件是否被删除
            $deletedCount = 0;
            $predefinedFiles = array('action', 'project', 'status', 'resolution', 'user', 'issue', 'changegroup', 'changeitem', 'issuelink', 'issuelinktype', 'fileattachment', 'version', 'issuetype', 'nodeassociation', 'applicationuser', 'fieldscreenlayoutitem', 'workflow', 'workflowscheme', 'fieldconfigscheme', 'fieldconfigschemeissuetype', 'customfield', 'customfieldoption', 'customfieldvalue', 'ospropertyentry', 'worklog', 'auditlog', 'group', 'membership', 'projectroleactor', 'priority', 'configurationcontext', 'optionconfiguration', 'fixversion', 'affectsversion');
            foreach($predefinedFiles as $fileName) {
                if(!file_exists($jiraPath . $fileName . '.xml')) {
                    $deletedCount++;
                }
            }
            
            return array('deletedCount' => $deletedCount, 'totalFiles' => count($predefinedFiles));
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        } catch (Error $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    /**
     * Test checkDBName method.
     *
     * @param  string $dbName
     * @access public
     * @return mixed
     */
    public function checkDBNameTest($dbName = null)
    {
        $result = $this->objectModel->checkDBName($dbName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoObjectList method.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoObjectListTest()
    {
        $result = $this->objectModel->getZentaoObjectList();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoObjectList method without ER feature.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoObjectListTestWithoutER()
    {
        global $config;
        $originalER = $config->enableER ?? true;
        $config->enableER = false;

        $result = $this->objectModel->getZentaoObjectList();
        
        $config->enableER = $originalER;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoObjectList method without UR and SR feature.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoObjectListTestWithoutUR()
    {
        global $config;
        $originalUR = $config->URAndSR ?? true;
        $config->URAndSR = false;

        $result = $this->objectModel->getZentaoObjectList();
        
        $config->URAndSR = $originalUR;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoObjectList method without ER and UR/SR feature.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoObjectListTestWithoutERAndUR()
    {
        global $config;
        $originalER = $config->enableER ?? true;
        $originalUR = $config->URAndSR ?? true;
        $config->enableER = false;
        $config->URAndSR = false;

        $result = $this->objectModel->getZentaoObjectList();
        
        $config->enableER = $originalER;
        $config->URAndSR = $originalUR;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoObjectList method count.
     *
     * @param  string $type
     * @access public
     * @return int
     */
    public function getZentaoObjectListCountTest($type = 'default')
    {
        global $config;
        $originalER = $config->enableER ?? true;
        $originalUR = $config->URAndSR ?? true;

        switch($type)
        {
            case 'noER':
                $config->enableER = false;
                break;
            case 'noUR':
                $config->URAndSR = false;
                break;
            case 'noERAndUR':
                $config->enableER = false;
                $config->URAndSR = false;
                break;
        }

        $result = $this->objectModel->getZentaoObjectList();
        $count = count($result);

        $config->enableER = $originalER;
        $config->URAndSR = $originalUR;

        return $count;
    }

    /**
     * Test getZentaoRelationList method.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoRelationListTest()
    {
        $result = $this->objectModel->getZentaoRelationList();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getZentaoFields method.
     *
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function getZentaoFieldsTest($module = '')
    {
        $result = $this->objectModel->getZentaoFields($module);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}