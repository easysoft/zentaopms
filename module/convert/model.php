<?php
declare(strict_types=1);
/**
 * The model file of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class convertModel extends model
{
    /**
     * 数据库连接句柄。
     * Database link handle.
     *
     * @var    object
     * @access public
     */
    public $sourceDBH;

    /**
     * 连接数据库。
     * Connect to db.
     *
     * @param  string        $dbName
     * @access public
     * @return object|string
     */
    public function connectDB(string $dbName = ''): object|string
    {
        try
        {
            $params = clone $this->config->db;
            $params->name = $dbName;

            $dbh = new dbh($params);
            $dbh->exec("SET NAMES {$params->encoding}");
            $dbh->setAttribute(PDO::ATTR_CASE , PDO::CASE_LOWER);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->sourceDBH = $dbh;

            return $dbh;
        }
        catch (PDOException $exception)
        {
            return $exception->getMessage();
        }
    }

    /**
     * 检查数据库是否存在。
     * Check database exits or not.
     *
     * @param  string       $dbName
     * @access public
     * @return object|false
     */
    public function dbExists(string $dbName = ''): object|false
    {
        if(!$this->checkDBName($dbName)) return false;
        $quotedDbName = $this->dbh->quote($dbName);
        return $this->dbh->query("SHOW DATABASES like {$quotedDbName}")->fetch();
    }

    /**
     * 检查数据表是否存在。
     * Check table exits or not.
     *
     * @param  string       $table
     * @access public
     * @return object|false
     */
    public function tableExists(string $table): object|false
    {
        $sql = "SHOW tables like '$table'";
        return $this->dbh->query($sql)->fetch();
    }

    /**
     * 检查jira数据库表是否存在。
     * Check table of jira databases exits or not.
     *
     * @param  string       $dbName
     * @param  string       $table
     * @access public
     * @return object|false
     */
    public function tableExistsOfJira(string $dbName, string $table): object|false
    {
        $this->connectDB($dbName);
        $sql = "SHOW tables like '$table'";
        return $this->dao->dbh($this->sourceDBH)->query($sql)->fetch();
    }

    /**
     * 保存每个表的最大ID。
     * Save the max id of every table. Thus when we convert again, when can delete id larger then the saved max id.
     *
     * @access public
     * @return void
     */
    public function saveState(): void
    {
        /* Get user defined tables. */
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* These tables needn't save. */
        unset($userConstants['TABLE_BURN']);
        unset($userConstants['TABLE_GROUPPRIV']);
        unset($userConstants['TABLE_PROJECTPRODUCT']);
        unset($userConstants['TABLE_PROJECTSTORY']);
        unset($userConstants['TABLE_STORYSPEC']);
        unset($userConstants['TABLE_TEAM']);
        unset($userConstants['TABLE_USERGROUP']);
        unset($userConstants['TABLE_STORYSTAGE']);
        unset($userConstants['TABLE_SEARCHDICT']);

        /* Get max id of every table. */
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;
            $state[$value] = (int)$this->dao->select('MAX(id) AS id')->from($value)->fetch('id');
        }
        $this->session->set('state', $state);
    }

    /**
     * 获取jira的数据。
     * Get jira data.
     *
     * @param  int    $method
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getJiraData(string $method, string $module, int $lastID = 0, int $limit = 0): array
    {
        if($method == 'db')
        {
            $originDBH = $this->dbh;
            $this->connectDB($this->session->jiraDB);
            $result = $this->getJiraDataFromDB($module, $lastID, $limit);
            $this->dao->dbh($originDBH);
            return $result;
        }
        else
        {
            return $this->getJiraDataFromFile($module, $lastID, $limit);
        }
    }

    /**
     * 从数据库获取jira数据。
     * Get jira data from db.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getJiraDataFromDB(string $module = '', int $lastID = 0, int $limit = 0): array
    {
        $dataList = array();
        $table    = zget($this->config->convert->objectTables, $module, '');
        if($module == 'user')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('t1.`ID`, t1.`lower_user_name` as account, t1.`lower_display_name` as realname, t1.`lower_email_address` as email, t1.created_date as `join`, t2.user_key as userCode')->from(JIRA_USERINFO)->alias('t1')
                ->leftJoin(JIRA_USER)->alias('t2')->on('t1.`lower_user_name` = t2.`lower_user_name`')
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('t1.ID')->gt($lastID)->fi()
                ->orderBy('t1.ID asc')->limit($limit)
                ->fetchAll('id');
        }
        elseif($module == 'nodeassociation')
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from($table)->limit($lastID, $limit)->fetchAll();
        }
        elseif($module == 'fixversion' || $module == 'affectsversion')
        {
            $dataList = array();
        }
        elseif(!empty($table))
        {
            $dataList = $this->dao->dbh($this->sourceDBH)->select('*')->from($table)
                ->where('1 = 1')
                ->beginIF($lastID)->andWhere('ID')->gt($lastID)->fi()
                ->orderBy('ID asc')->limit($limit)
                ->fetchAll('id', false);
        }

        return $dataList;
    }

    /**
     * 从文件中获取jira数据。
     * Get jira data from file.
     *
     * @param  string $module
     * @param  int    $lastID
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getJiraDataFromFile(string $module, int $lastID = 0, int $limit = 0): array
    {
        $fileName = $module;
        if($module == 'build') $fileName = 'version';
        if($module == 'file')  $fileName = 'fileattachment';

        $filePath = $this->app->getTmpRoot() . 'jirafile/' . $fileName . '.xml';
        if(!file_exists($filePath)) return array();

        $xmlContent = file_get_contents($filePath);
        $xmlContent = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xmlContent);

        $reader = new XMLReader();
        $dom    = new DOMDocument();

        $reader->XML($xmlContent);
        $reader->read();
        $domNode = $reader->expand($dom);
        $reader->close();

        $parsedXML = simplexml_import_dom($domNode);
        $parsedXML = $this->object2Array($parsedXML);

        $dataList  = array();
        foreach($parsedXML as $key => $xmlArray)
        {
            if(strtolower($key) != strtolower($fileName)) continue;
            foreach($xmlArray as $key => $attributes)
            {
                if(is_array($attributes) && isset($attributes['status']) && $attributes['status'] == 'deleted') break;

                if(is_numeric($key))
                {
                    $desc    = isset($attributes['description']) ? $attributes['description'] : '';
                    $summary = isset($attributes['summary']) ? $attributes['summary'] : '';
                    $body    = isset($attributes['body']) ? $attributes['body'] : '';

                    $data   = array();
                    $dataID = 0;
                    foreach($attributes as $k => $value)
                    {
                        if(empty($value)) continue;
                        if(is_array($value))
                        {
                            if(isset($value['status']) && $value['status'] == 'deleted') break;

                            if(!empty($desc))    $value['description'] = $desc;
                            if(!empty($summary)) $value['summary']     = $summary;
                            if(!empty($body))    $value['body']        = $body;
                            $dataID = !empty($value['id']) ? $value['id'] : ($key + 1);
                            $data   = array_merge($data, $value);
                        }
                        else
                        {
                            $data = array_merge($data, array($k => $value));
                        }
                    }
                    if(!empty($dataID)) $dataList[$dataID] = $data;
                }
                else
                {
                    if(is_array($attributes))
                    {
                        if(isset($attributes['status']) && $attributes['status'] == 'deleted') continue;

                        $dataID = !empty($attributes['id']) ? $attributes['id'] : $key;
                        $dataList[$dataID] = $attributes;
                    }
                    else
                    {
                        if(!empty($dataID)) $dataList[$dataID][$key] = $attributes;
                    }
                }
            }
        }

        if($limit)
        {
            $dataList = array_slice($dataList, $lastID, $limit, true);
            if(empty($dataList)) return array();
        }

        if(in_array($module, array_keys($this->config->convert->objectTables)))
        {
            foreach($dataList as $key => $data)
            {
                $buildFunction  = 'build' . ucfirst($module) . 'Data';
                $dataList[$key] = $this->$buildFunction($data);
            }
        }

        return $dataList;
    }

    /**
     * 分割jira文件。
     * Split jira file.
     *
     * @access public
     * @return void
     */
    public function splitFile(): void
    {
        $filePath = $this->app->getTmpRoot() . 'jirafile/';
        $fileName = 'entities.xml';
        $file     = $filePath . $fileName;
        $handle   = fopen($file, "r");

        $tagList = array('<Action' => '</Action>', '<Project' => '</Project>', '<Status' => '</Status>', '<Resolution' => '</Resolution>', '<User' => '</User>', '<Issue' => '</Issue>', '<ChangeGroup' => '</ChangeGroup>', '<ChangeItem' => '</ChangeItem>', '<IssueLink' => '</IssueLink>', '<IssueLinkType' => '</IssueLinkType>', '<FileAttachment' => '</FileattAchment>', '<Version' => '</Version>', '<IssueType' => '</IssueType>', '<NodeAssociation' => '</NodeAssociation>', '<ApplicationUser' => '</ApplicationUser>', '<FieldScreenLayoutItem' => '<FieldScreenLayoutItem>', '<Workflow' => '</Workflow>', '<WorkflowScheme' => '</WorkflowScheme>', '<FieldConfigSchemeIssueType' => '</FieldConfigSchemeIssueType>', '<FieldConfigScheme' => '</FieldConfigScheme>', '<CustomField' => '</CustomField>', '<CustomFieldOption' => '</CustomFieldOption>', '<CustomFieldValue' => '</CustomFieldValue>', '<OSPropertyEntry' => '</OSPropertyEntry>', '<Worklog' => '</Worklog>', '<AuditLog' => '</AuditLog>', '<Group' => '</Group>', '<Membership' => '</Membership>', '<ProjectRoleActor' => '</ProjectRoleActor>', '<Priority' => '</Priority>', '<ConfigurationContext' => '</ConfigurationContext>', '<OptionConfiguration' => '</OptionConfiguration>', '<FixVersion' => '</FixVersion>', '<AffectsVersion' => '</AffectsVersion>');

        while(!feof($handle))
        {
            $itemStr = fgets($handle);
            if(empty($itemStr)) continue;
            foreach($tagList as $startName => $endName)
            {
                $startName .= ' ';
                if(strpos($itemStr, $startName) === false) continue;

                if(strpos($itemStr, '/>') === false)
                {
                    while(true)
                    {
                        $followItemStr = fgets($handle);
                        $itemStr      .= $followItemStr;
                        if(strpos($itemStr, $endName) !== false) break;
                    }
                }

                $object = str_replace('<', '', $startName);
                $object = trim(strtolower($object));
                $data   = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $itemStr);
                if(!file_exists($filePath . $object . '.xml')) $data = "<?xml version='1.0' encoding='UTF-8'?>\n  <entity-engine-xml>\n" . $data;
                file_put_contents($filePath . $object . '.xml', $data, FILE_APPEND);
            }
        }

        foreach($tagList as $startName => $endName)
        {
            $object   = str_replace('<', '', $startName);
            $object   = strtolower($object);
            $filename = $filePath . $object . '.xml';
            if(file_exists($filename)) file_put_contents($filename, '</entity-engine-xml>', FILE_APPEND);
        }

        fclose($handle);
    }

    /**
     * 创建jira数据表。
     * Create tmp table for import jira.
     *
     * @access public
     * @return void
     */
    public function createTmpTable4Jira(): void
    {
$sql = <<<EOT
CREATE TABLE `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

        try
        {
            $this->dbh->exec($sql);
        }
        catch(Exception $e){}
    }

    /**
     * 使用接口导入Jira数据。
     * Import jira from REST API.
     *
     * @param  string $type user|project|issue|build|issuelink|action|file
     * @param  int    $lastID
     * @param  bool   $createTable
     * @access public
     * @return array
     */
    public function importJiraData(string $type = '', int $lastID = 0, bool $createTable = false): array
    {
        if($createTable) $this->createTmpTable4Jira();

        if($this->session->jiraMethod == 'db') $this->connectDB($this->session->jiraDB);

        $limit = 1000;
        $nextObject = false;
        if(empty($type)) $type = key($this->lang->convert->jira->objectList);

        foreach(array_keys($this->lang->convert->jira->objectList) as $module)
        {
            if($module != $type && !$nextObject) continue;
            if($module == $type) $nextObject = true;

            $this->convertTao->sourceDBH = $this->sourceDBH;

            while(true)
            {
                $dataList = $this->getJiraData($this->session->jiraMethod, $module, $lastID, $limit);

                if(empty($dataList))
                {
                    $lastID = 0;
                    break;
                }

                if($module == 'user')       $this->convertTao->importJiraUser($dataList);
                if($module == 'project')    $this->convertTao->importJiraProject($dataList);
                if($module == 'issue')      $this->convertTao->importJiraIssue($dataList);
                if($module == 'build')      $this->convertTao->importJiraBuild($dataList);
                if($module == 'issuelink')  $this->convertTao->importJiraIssueLink($dataList);
                if($module == 'worklog')    $this->convertTao->importJiraWorkLog($dataList);
                if($module == 'action')     $this->convertTao->importJiraAction($dataList);
                if($module == 'changeitem') $this->convertTao->importJiraChangeItem($dataList);
                if($module == 'file')       $this->convertTao->importJiraFile($dataList);

                $offset = $lastID + $limit;

                return array('type' => $module, 'count' => count($dataList), 'lastID' => $this->session->jiraMethod == 'db' ? max(array_keys($dataList)) : $offset);
            }
        }

        if($this->session->jiraMethod == 'file') $this->deleteJiraFile();

        /* 更新各项目的统计数据。 */
        $projectList = $this->dao->dbh($this->dbh)->select('BID')->from(JIRA_TMPRELATION)->where('BType')->in('zproject,zexecution')->fetchPairs();
        $this->loadModel('program')->updateStats($projectList);

        unset($_SESSION['jiraDB']);
        unset($_SESSION['jiraMethod']);
        unset($_SESSION['jiraRelation']);
        unset($_SESSION['stepStatus']);
        unset($_SESSION['jiraUser']);
        return array('finished' => true);
    }

    /**
     * 删除jira文件。
     * Delete jira backip file.
     *
     * @access public
     * @return void
     */
    public function deleteJiraFile(): void
    {
        $fileList = array('action', 'project', 'status', 'resolution', 'user', 'issue', 'changegroup', 'changeitem', 'issuelink', 'issuelinktype', 'fileattachment', 'version', 'issuetype', 'nodeassociation', 'applicationuser', 'fieldscreenlayoutitem', 'workflow', 'workflowscheme', 'fieldconfigscheme', 'fieldconfigschemeissuetype', 'customfield', 'customfieldoption', 'customfieldvalue', 'ospropertyentry', 'worklog', 'auditlog', 'group', 'membership', 'projectroleactor', 'priority', 'configurationcontext', 'optionconfiguration', 'fixversion', 'affectsversion');
        foreach($fileList as $fileName)
        {
            $filePath = $this->app->getTmpRoot() . 'jirafile/' . $fileName . '.xml';
            if(file_exists($filePath)) @unlink($filePath);
        }
    }

    /**
     * 检查数据库名称。
     * Check dbName is valide.
     *
     * @param  string $dbName
     * @access public
     * @return bool
     */
    public function checkDBName(string $dbName): bool
    {
        if(preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $dbName)) return true;
        return false;
    }

    /**
     * 获取禅道对象列表。
     * Get zentao object list.
     *
     * @access public
     * @return array
     */
    public function getZentaoObjectList(): array
    {
        $objectList = $this->lang->convert->jira->zentaoObjectList;
        if(!$this->config->enableER) unset($objectList['epic']);
        if(!$this->config->URAndSR)  unset($objectList['requirement']);
        return $objectList;
    }

    /**
     * 获取禅道关联关系列表。
     * Get zentao relation list.
     *
     * @access public
     * @return array
     */
    public function getZentaoRelationList(): array
    {
        return $this->lang->convert->jira->zentaoLinkTypeList;
    }

    /**
     * 获取禅道字段列表。
     * Get zentao fields.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getZentaoFields(string $module): array
    {
        $this->app->loadLang($module);

        $fields = array();
        if(isset($this->config->convert->objectFields[$module]))
        {
            foreach($this->config->convert->objectFields[$module] as $field)
            {
                $fields[$field] = $this->lang->{$module}->$field;
            }
        }

        return $fields;
    }

    /**
     * 获取禅道状态列表。
     * Get zentao status.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getZentaoStatus(string $module): array
    {
        $this->loadModel($module);
        $statusList = $this->lang->{$module}->statusList;
        if($module == 'testcase') $statusList = array_merge($statusList, array('add_case_status' => $this->lang->convert->add));
        return $statusList;
    }

    /**
     * 获取导入jira的步骤列表。
     * Get import jira data steps.
     *
     * @param  array  $jiraData
     * @param  array  $issueTypeList
     * @access public
     * @return array
     */
    public function getJiraStepList(array $jiraData, array $issueTypeList = array()): array
    {
        $stepList    = array();
        $objectSteps = array();
        if(empty($issueTypeList)) $issueTypeList = $this->getJiraData($this->session->jiraMethod, 'issuetype');
        if(!empty($jiraData['jiraObject']))
        {
            foreach($jiraData['jiraObject'] as $objectID)
            {
                if($jiraData['zentaoObject'][$objectID] == 'add_custom') continue;
                if($objectID) $objectSteps[$objectID] = zget($issueTypeList[$objectID], 'pname', '') . $this->lang->convert->jira->steps['objectData'];
            }
        }

        foreach($this->lang->convert->jira->steps as $currentStep => $stepLabel)
        {
            if($currentStep == 'objectData')
            {
                if(!empty($objectSteps))
                {
                    $stepList = $stepList + $objectSteps;
                    continue;
                }
            }
            $stepList[$currentStep] = $stepLabel;
        }
        return $stepList;
    }

    /**
     * 校验导入jira数据时配置信息完整性。
     * Check import jira.
     *
     * @param  string $step
     * @access public
     * @return bool
     */
    public function checkImportJira(string $step): bool
    {
        $data = fixer::input('post')->get();
        if($step == 'object')
        {
            $jiraObject   = $data->jiraObject;
            $zentaoObject = $data->zentaoObject;
            foreach($jiraObject as $typeID)
            {
                if(empty($zentaoObject[$typeID]))  dao::$errors['message'] = sprintf($this->lang->error->notempty, $this->lang->convert->jira->zentaoObject);
            }
        }

        return !dao::isError();
    }

    /**
     * 获取导入Jira时禅道的对象属性默认值。
     * Get object default value.
     *
     * @param  string $step
     * @access public
     * @return array
     */
    public function getObjectDefaultValue(string $step): array
    {
        if($step == 'object') return $this->config->convert->importDeafaultValue;

        $jiraRelation = $this->session->jiraRelation;
        $jiraRelation = $jiraRelation ? json_decode($jiraRelation, true) : array();
        if(empty($jiraRelation['zentaoObject'][$step])) return array();
        $zentaoObject = $jiraRelation['zentaoObject'][$step];

        $defaultValue        = array();
        $importDeafaultValue = $this->config->convert->importDeafaultValue;
        $defaultValue["zentaoField$step"]      = !empty($importDeafaultValue[$zentaoObject]['field'])      ? $importDeafaultValue[$zentaoObject]['field']      : array();
        $defaultValue["zentaoStatus$step"]     = !empty($importDeafaultValue[$zentaoObject]['status'])     ? $importDeafaultValue[$zentaoObject]['status']     : array();
        $defaultValue["zentaoAction$step"]     = !empty($importDeafaultValue[$zentaoObject]['action'])     ? $importDeafaultValue[$zentaoObject]['action']     : array();
        $defaultValue["zentaoResolution$step"] = !empty($importDeafaultValue[$zentaoObject]['resolution']) ? $importDeafaultValue[$zentaoObject]['resolution'] : array();
        $defaultValue["zentaoReason$step"]     = !empty($importDeafaultValue[$zentaoObject]['reason'])     ? $importDeafaultValue[$zentaoObject]['reason']     : array();
        return $defaultValue;
    }

    /**
     * 获取Jira事务类型列表。
     * Get jira type list.
     *
     * @access public
     * @return array
     */
    public function getJiraTypeList(): array
    {
        $issues   = $this->getJiraData($this->session->jiraMethod, 'issue');
        $typeList = $this->getJiraData($this->session->jiraMethod, 'issuetype');

        $jiraTypeList = array();
        foreach($issues as $issue)
        {
            if(empty($issue->issuetype) || empty($typeList[$issue->issuetype])) continue;

            $issueType = $typeList[$issue->issuetype];
            $jiraTypeList[$issue->issuetype] = $issueType;
        }
        return $jiraTypeList;
    }

    /**
     * 获取Jira状态列表。
     * Get jira status list.
     *
     * @param  string|int $step
     * @param  array      $relations
     * @access public
     * @return array
     */
    public function getJiraStatusList(string|int $step, array $relations): array
    {
        if(empty($relations['zentaoObject']) || !in_array($step, array_keys($relations['zentaoObject']))) return array();

        $issues     = $this->getJiraData($this->session->jiraMethod, 'issue');
        $statusList = $this->getJiraData($this->session->jiraMethod, 'status');

        $jiraStatusList = array();
        foreach($issues as $issue)
        {
            if($issue->issuetype != $step || empty($issue->issuestatus)) continue;
            if(empty($statusList[$issue->issuestatus])) continue;

            $status = $statusList[$issue->issuestatus];
            $jiraStatusList[$issue->issuestatus] = $status->pname;
        }
        return $jiraStatusList;
    }

    /**
     * 获取Jira自定义字段列表。
     * Get jira custom fields.
     *
     * @param  string|int $step
     * @param  array      $relations
     * @access public
     * @return array
     */
    public function getJiraCustomField(string|int $step, array $relations): array
    {
        if($this->config->edition == 'open') return array();
        if(empty($relations['zentaoObject']) || !in_array($step, array_keys($relations['zentaoObject']))) return array();

        $issues      = $this->getJiraData($this->session->jiraMethod, 'issue');
        $fields      = $this->getJiraData($this->session->jiraMethod, 'customfield');
        $fieldValue  = $this->getJiraData($this->session->jiraMethod, 'customfieldvalue');

        $jiraFields = array();
        foreach($fieldValue as $value)
        {
            if(empty($issues[$value->issue]) || empty($fields[$value->customfield])) continue;

            $issue = $issues[$value->issue];
            $field = $fields[$value->customfield];
            if($issue->issuetype != $step) continue;

            if(in_array($field->customfieldtypekey, array('com.pyxis.greenhopper.jira:gh-sprint', 'com.pyxis.greenhopper.jira:gh-epic-label', 'com.pyxis.greenhopper.jira:gh-epic-status', 'com.pyxis.greenhopper.jira:gh-epic-color'))) continue;
            $jiraFields[$value->customfield] = $field->cfname;
        }
        return $jiraFields;
    }

    /**
     * 获取Jira按照项目分组的自定义字段。
     * Get jira custom fields group by project.
     *
     * @param  array  $relations
     * @access public
     * @return array
     */
    public function getJiraFieldGroupByProject($relations): array
    {
        $fieldList  = $this->dao->dbh($this->dbh)->select('AID, extra, BID AS field')->from(JIRA_TMPRELATION)->where('AType')->eq('jcustomfield')->andWhere('BType')->eq('zworkflowfield')->fetchGroup('AID', 'extra');
        $issues     = $this->getJiraData($this->session->jiraMethod, 'issue');
        $fieldValue = $this->getJiraData($this->session->jiraMethod, 'customfieldvalue');
        $jiraFields = array();
        foreach($fieldValue as $value)
        {
            if(empty($issues[$value->issue])) continue;

            $issue        = $issues[$value->issue];
            $zentaoObject = $relations['zentaoObject'][$issue->issuetype];

            if(!empty($fieldList[$value->customfield][$zentaoObject]))
            {
                $field = $fieldList[$value->customfield][$zentaoObject];
                $jiraFields[$issue->project][$zentaoObject][$field->field] = $field;
            }
        }

        return $jiraFields;
    }

    /**
     * 获取Jira工作流里的动作列表。
     * Get jira workflow actions.
     *
     * @access public
     * @return array
     */
    public function getJiraWorkflowActions(): array
    {
        if($this->config->edition == 'open') return array();

        $workflows       = $this->getJiraData($this->session->jiraMethod, 'workflow');
        $workflowActions = array();
        $actionNameList  = array();
        foreach($workflows as $workflowID => $workflow)
        {
            $descriptor = simplexml_load_string($workflow->descriptor);
            $descriptor = $this->object2Array($descriptor);

            foreach($descriptor as $id => $actions)
            {
                if(!empty($actions['action']))
                {
                    $actionInfo = array();
                    foreach($actions['action'] as $key => $actionList)
                    {
                        if(is_numeric($key))
                        {
                            foreach($actionList as $k => $action)
                            {
                                $actionInfo = array_merge($actionInfo, $k == '@attributes' ? $action : array($k => $action));
                                if(empty($actionNameList[$actionInfo['name']]) && strpos($actionInfo['name'], 'Issue') === false) $workflowActions['actions'][] = $actionInfo;
                                $actionNameList[$actionInfo['name']] = $actionInfo['name'];
                            }
                        }
                        else
                        {
                            $actionInfo = array_merge($actionInfo, $key == '@attributes' ? $actionList : array($key => $actionList));
                            if(empty($actionNameList[$actionInfo['name']]) && strpos($actionInfo['name'], 'Issue') === false) $workflowActions['actions'][] = $actionInfo;
                            $actionNameList[$actionInfo['name']] = $actionInfo['name'];
                        }
                    }
                }
                elseif(!empty($actions['step']))
                {
                    foreach($actions['step'] as $step)
                    {
                        if(empty($step['meta'])) continue;
                        $workflowActions['steps'][$step['@attributes']['id']] = $step['meta'];
                    }
                }
            }
        }
        return $workflowActions;
    }

    /**
     * 验证Jira api接口能否访问。
     * Check jira api.
     *
     * @access public
     * @return bool
     */
    public function checkJiraApi(): bool
    {
        $jiraApi = json_decode($this->session->jiraApi, true);
        if(empty($jiraApi['domain'])) return false;

        $token     = base64_encode("{$jiraApi['admin']}:{$jiraApi['token']}");
        $url       = $jiraApi['domain'] . '/rest/agile/1.0/board';
        $boardList = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));
        if(empty($boardList->values))
        {
            dao::$errors['message'] = $this->lang->convert->jira->apiError;
            return false;
        }
        return true;
    }

    /**
     * 调用Jira api接口。
     * Call Jira API.
     *
     * @param  string $url
     * @param  int    $start
     * @access public
     * @return array
     */
    public function callJiraAPI($url, $start = 0)
    {
        if(empty($_SESSION['jiraApi'])) return array();
        $jiraApi = json_decode($this->session->jiraApi, true);
        if(empty($jiraApi['domain'])) return array();

        $token   = base64_encode("{$jiraApi['admin']}:{$jiraApi['token']}");
        $httpURL = $jiraApi['domain'] . $url . "&startAt=$start";
        $result  = json_decode(commonModel::http($httpURL, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

        $dataList = array();
        if(!empty($result->values) || !empty($result->issues))
        {
            $dataList = !empty($result->values) ? $result->values : $result->issues;
            if(empty($result->isLast)) $dataList = array_merge($dataList, $this->callJiraAPI($url, $start + $result->maxResults));
        }

        return $dataList;
    }

    /**
     * 获取jira用户。
     * Get jira account.
     *
     * @param  string $userKey
     * @access public
     * @return string
     */
    public function getJiraAccount(string $userKey): string
    {
        if(empty($userKey)) return '';

        $users = $this->getJiraData($this->session->jiraMethod, 'user');

        if(strpos($userKey, 'JIRAUSER') !== false)
        {
            $userID = str_replace('JIRAUSER', '', $userKey);
            if(!isset($users[$userID])) return '';
            return $this->processJiraUser($users[$userID]->account, $users[$userID]->email);
        }
        else
        {
            foreach($users as $user)
            {
                if($user->account == $userKey) return $this->processJiraUser($user->account, $user->email);
            }
        }
        return $userKey;
    }

    /**
     * 处理Jira用户名为禅道格式。
     * Process jira user.
     *
     * @param  string $jiraAccount
     * @param  string $jiraEmail
     * @access public
     * @return string
     */
    public function processJiraUser(string $jiraAccount, string $jiraEmail): string
    {
        $userConfig = $this->session->jiraUser;
        $account    = substr($jiraAccount, 0, 30);
        if($userConfig['mode'] == 'email' && $jiraEmail)
        {
            if(strpos($jiraEmail, '@') !== false)
            {
                $account = substr(substr($jiraEmail, 0, strpos($jiraEmail, '@')), 0, 30);
            }
            else
            {
                $account = substr($jiraEmail, 0, 30);
            }
        }
        return preg_replace("/[^a-zA-Z0-9]/", "", $account);
    }

    /**
     * 从jira文件中获取版本信息。
     * Get version group from jira file.
     *
     * @access public
     * @return array
     */
    public function getVersionGroup(): array
    {
        $filePath = $this->app->getTmpRoot() . 'jirafile/nodeassociation.xml';
        if(!file_exists($filePath)) return array();

        $xmlContent = file_get_contents($this->app->getTmpRoot() . 'jirafile/nodeassociation.xml');
        $xmlContent = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xmlContent);
        $parsedXML  = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
        if(empty($parsedXML)) return array();

        $dataList  = array();
        $parsedXML = $this->object2Array($parsedXML);
        foreach($parsedXML as $key => $xmlArray)
        {
            if(strtolower($key) != 'nodeassociation') continue;
            foreach($xmlArray as $key => $attributes)
            {
                foreach($attributes as $value)
                {
                    if(!is_array($value)) continue;
                    if($value['sinkNodeEntity'] != 'Version') continue;

                    $data = new stdclass();
                    $data->versionid = $value['sinkNodeId'];
                    $data->issueid   = $value['sourceNodeId'];
                    $data->relation  = $value['associationType'];
                    $dataList[$value['sinkNodeId']][] = $data;
                }
            }
        }

        return $dataList;
    }

    /**
     * 将对象转换为数组。
     * Convert object to array.
     *
     * @param  object|array $parsedXML
     * @access public
     * @return array
     */
    public function object2Array(object|array $parsedXML): array
    {
        if(is_object($parsedXML))
        {
            $parsedXML = (array)$parsedXML;
        }

        if(is_array($parsedXML))
        {
            foreach($parsedXML as $key => $value)
            {
                if(is_object($value) && empty($value))
                {
                    $parsedXML[$key] = (string)$value;
                    continue;
                }
                if(!is_object($value) && !is_array($value)) continue;
                $parsedXML[$key] = $this->object2Array($value);
            }
        }

        return $parsedXML;
    }

    /**
     * 转换需求阶段。
     * Convert stage.
     *
     * @param  string $jiraStatus
     * @param  string $issueType
     * @param  array  $relations
     * @access public
     * @return string
     */
    public function convertStage(string $jiraStatus, string $issueType, array $relations = array()): string
    {
        if(empty($relations))
        {
            $jiraRelation = $this->session->jiraRelation;
            $relations    = $jiraRelation ? json_decode($jiraRelation, true) : array();
        }

        $stage = 'wait';
        if(!empty($relations["zentaoStage$issueType"][$jiraStatus])) $stage = $relations["zentaoStage$issueType"][$jiraStatus];

        return $stage;
    }

    /**
     * 转换状态。
     * Convert jira status.
     *
     * @param  string $objectType
     * @param  string $jiraStatus
     * @param  string $issueType
     * @param  array  $relations
     * @access public
     * @return string
     */
    public function convertStatus(string $objectType, string $jiraStatus, string $issueType, array $relations = array()): string
    {
        if(empty($relations))
        {
            $jiraRelation = $this->session->jiraRelation;
            $relations    = $jiraRelation ? json_decode($jiraRelation, true) : array();
        }

        if(!empty($relations["zentaoStatus{$issueType}"][$jiraStatus])) return (string)$relations["zentaoStatus{$issueType}"][$jiraStatus];

        if($objectType == 'testcase' && empty($this->config->testcase->needReview)) return 'normal';
        if($objectType == 'feedback' && empty($this->config->feedback->needReview)) return 'normal';
        if($objectType == 'ticket'   && empty($this->config->feedback->ticket))     return 'normal';

        return in_array($objectType, array('task', 'testcase', 'feedback', 'ticket', 'flow')) ? 'wait' : 'active';
    }



    /**
     * 获取Jira的所有冲刺。
     * Get jira sprint.
     *
     * @param  array  $projectList
     * @access public
     * @return array
     */
    public function getJiraSprint(array $projectList): array
    {
        $sprintGroup = array();
        if($this->session->jiraMethod == 'file')
        {
            foreach($projectList as $projectID)
            {
                $boardList = $this->callJiraAPI("/rest/agile/1.0/board?projectKeyOrId={$projectID}&maxResults=50");
                foreach($boardList as $board)
                {
                    $sprintList = $this->callJiraAPI("/rest/agile/1.0/board/$board->id/sprint?maxResults=50");
                    foreach($sprintList as $sprint)
                    {
                        $sprintGroup[$projectID][$sprint->id] = $sprint;
                    }
                }
            }
        }
        else
        {
            $sprintGroup = $this->dao->dbh($this->sourceDBH)->select('project.id as pid, sprint.*')->from('ao_60db71_sprint')->alias('sprint')
                ->leftJoin('ao_60db71_rapidview')->alias('rapview')->on('rapview.id=sprint.RAPID_VIEW_ID')
                ->leftJoin('searchrequest')->alias('search')->on('search.ID=rapview.SAVED_FILTER_ID')
                ->leftJoin('project')->on("search.reqcontent like concat('%project = ', project.pkey, ' O%')")
                ->where('project.id')->notNULL()
                ->fetchGroup('pid', 'id');
        }

        return $sprintGroup;
    }

    /**
     * 获取Jira issue的所属sprint。
     * Get jira sprint issue.
     *
     * @access public
     * @return array
     */
    public function getJiraSprintIssue(): array
    {
        $issueGroup     = array();
        $sprintRelation = $this->dao->dbh($this->dbh)->select('AID,BID')->from(JIRA_TMPRELATION)->where('AType')->eq('jsprint')->andWhere('BType')->eq('zexecution')->fetchPairs();
        if($this->session->jiraMethod == 'file')
        {
            foreach($sprintRelation as $sprintID => $executionID)
            {
                $issueList = $this->callJiraAPI("/rest/agile/1.0/sprint/{$sprintID}/issue?maxResults=50");
                foreach($issueList as $issue) $issueGroup[$issue->id] = $executionID;
            }
        }

        return $issueGroup;
    }

    /**
     * 获取Jira已归档的项目。
     * Get jira archived project.
     *
     * @param  array  $dataList
     * @access public
     * @return array
     */
    public function getJiraArchivedProject($dataList): array
    {
        $archivedProject = array();
        $auditLog = $this->getJiraData($this->session->jiraMethod, 'auditlog');
        if($auditLog)
        {
            foreach($auditLog as $log)
            {
                if($log->summary == 'Project archived' && $log->object_type == 'project') $archivedProject[$log->object_id] = $log->object_id;
            }
        }

        if($this->session->jiraMethod == 'file')
        {
            if(empty($_SESSION['jiraApi'])) return $archivedProject;
            $jiraApi = json_decode($this->session->jiraApi, true);
            if(empty($jiraApi['domain'])) return $archivedProject;

            $token  = base64_encode("{$jiraApi['admin']}:{$jiraApi['token']}");
            $url    = $jiraApi['domain'] . '/rest/api/2/project/';
            $result = json_decode(commonModel::http($url, array(), array(), array("Authorization: Basic $token"), 'json', 'GET', 10));

            $projectList = array();
            if(!empty($result))
            {
                foreach($result as $project)
                {
                    if(!empty($project->id) && empty($project->archived)) $projectList[$project->id] = $project->id; // 没有被归档的项目。
                }
            }

            /* 过滤掉没被归档的项目，剩下的都是被归档的项目。 */
            foreach($dataList as $project)
            {
                if(empty($projectList[$project->id])) $archivedProject[$project->id] = $project->id;
            }
        }
        else
        {
            $sql = "SHOW tables like 'ao_c77861_audit_entity';";
            if($this->dao->dbh($this->sourceDBH)->query($sql)->fetch())
            {
                $auditEntity = $this->dao->dbh($this->sourceDBH)->select('PRIMARY_RESOURCE_ID')->from('ao_c77861_audit_entity')
                    ->where('ACTION_T_KEY')->eq('jira.auditing.project.archived')
                    ->andWhere('PRIMARY_RESOURCE_TYPE')->eq('PROJECT')
                    ->fetchPairs();

                $archivedProject = array_merge($archivedProject, $auditEntity);
            }
        }

        return $archivedProject;
    }

    /**
     * 获取Jira项目角色与成员。
     * Get jira project role actor.
     *
     * @access public
     * @return array
     */
    public function getJiraProjectRoleActor(): array
    {
        $projectRoleActor = $this->getJiraData($this->session->jiraMethod, 'projectroleactor');
        $memberShip       = $this->getJiraData($this->session->jiraMethod, 'membership');

        $projectMember = array();
        foreach($projectRoleActor as $role)
        {
            if(empty($role->pid)) continue;
            if($role->roletype == 'atlassian-user-role-actor')
            {
                $projectMember[$role->pid][$role->roletypeparameter] = $role->roletypeparameter;
            }
            if($role->roletype == 'atlassian-group-role-actor')
            {
                foreach($memberShip as $member)
                {
                    if($member->parent_name == $role->roletypeparameter) $projectMember[$role->pid]["JIRAUSER{$member->child_id}"] = 'JIRAUSER' . $member->child_id;
                }
            }
        }
        return $projectMember;
    }

    /**
     * 获取按照项目分组的Jira事务类型。
     * get Jira issue type group by project.
     *
     * @param  array  $relations
     * @access public
     * @return array
     */
    public function getIssueTypeList(array $relations): array
    {
        $schemeproject        = $this->getJiraData($this->session->jiraMethod, 'configurationcontext');
        $schemeissuetype      = $this->getJiraData($this->session->jiraMethod, 'optionconfiguration');
        $projectIssueTypeList = array();
        foreach($schemeproject as $projectRelation)
        {
            if(!empty($projectRelation->project) && $projectRelation->customfield == 'issuetype')
            {
                foreach($schemeissuetype as $issueTypeRelation)
                {
                    if($issueTypeRelation->fieldconfig == $projectRelation->fieldconfigscheme && $issueTypeRelation->fieldid == 'issuetype' && !empty($issueTypeRelation->optionid))
                    {
                        if(!empty($relations['zentaoObject'][$issueTypeRelation->optionid])) $projectIssueTypeList[$projectRelation->project][] = $relations['zentaoObject'][$issueTypeRelation->optionid];
                    }
                }
            }
        }
        return $projectIssueTypeList;
    }
}
