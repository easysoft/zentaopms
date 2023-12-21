<?php
declare(strict_types=1);
/**
 * The control file of transfer module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng <tanghucheng@cnezsoft.com>
 * @package     transfer
 * @link        https://www.zentao.net
 */
class transferModel extends model
{
    /* transfer Module configs. */
    public $transferConfig;

    /* From module configs. */
    public $moduleConfig;

    /* From module lang. */
    public $moduleLang;

    public $maxImport;

    public $moduleFieldList;

    public $templateFields;

    public $exportFields;

    public $moduleListFields;

    /**
     * The construc method, to do some auto things.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->maxImport  = isset($_COOKIE['maxImport']) ? $_COOKIE['maxImport'] : 0;
        $this->transferConfig = $this->config->transfer;
    }

    /**
     * Transfer 公共方法（格式化模块语言项，Config，字段）。
     * Common actions.
     *
     * @param  int    $module
     * @access public
     * @return void
     */
    public function commonActions(string $module = '')
    {
        if($module)
        {
            $this->loadModel($module);
            $this->moduleConfig     = $this->config->$module;
            $this->moduleLang       = $this->lang->$module;
            $this->moduleFieldList  = $this->config->$module->dtable->fieldList ?? array();
            $this->moduleListFields = explode(',', $this->config->$module->listFields ?? '');
        }
    }

    /**
     * 生成导出数据
     * Export module data.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function export(string $module = '')
    {
        /* 设置PHP最大运行内存和最大执行时间。 */
        /* Set PHP max running memory and max execution time. */
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','100');

        $fields = $this->post->exportFields;

        /* Init config fieldList. */
        $fieldList = $this->initFieldList($module, $fields);

        /* 生成该模块的导出数据。 */
        /* Generate export datas. */
        $rows = $this->getRows($module, $fieldList);
        if($module == 'story')
        {
            $product = $this->loadModel('product')->getByID((int)$this->session->storyTransferParams['productID']);
            if($product and $product->shadow) foreach($rows as $id => $row) $rows[$id]->product = '';
        }

        /* 设置Excel下拉数据。 */
        /* Set Excel dropdown data. */
        $list = $this->setListValue($module, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        /* Get export rows and fields datas. */
        $exportDatas = $this->getExportDatas($fieldList, $rows);

        $fields = $exportDatas['fields'];
        $rows   = !empty($exportDatas['rows']) ? $exportDatas['rows'] : array();

        if($this->config->edition != 'open') list($fields, $rows) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $rows, $module);

        $this->post->set('rows',   $rows);
        $this->post->set('fields', $fields);
        $this->post->set('kind',   $module);
    }

    /**
     * Init FieldList.
     *
     * @param  string $module
     * @param  string $fields
     * @param  bool   $withKey
     * @access public
     * @return array
     */
    public function initFieldList($module, $fields = '', $withKey = true)
    {
        $this->commonActions($module);
        $this->mergeConfig($module);

        $this->transferConfig->sysDataList = $this->initSysDataFields();
        $transferFieldList = $this->transferConfig->fieldList;

        if(empty($fields)) return false;

        if(!is_array($fields)) $fields = explode(',', $fields);

        $fieldList = array();
        /* build module fieldList. */
        foreach($fields as $field)
        {
            $field = trim($field);
            if($module == 'bug' and $this->session->currentProductType == 'normal' and $field == 'branch') continue;

            $moduleFieldList = isset($this->moduleFieldList[$field]) ? $this->moduleFieldList[$field] : array();

            foreach($transferFieldList as $transferField => $value)
            {
                if((!isset($moduleFieldList[$transferField])) or $transferField == 'title')
                {
                    $moduleFieldList[$transferField] = $this->transferConfig->fieldList[$transferField];

                    if(strpos($this->transferConfig->initFunction, $transferField) !== false)
                    {
                        $funcName = 'init' . ucfirst($transferField);
                        $moduleFieldList[$transferField] = $this->$funcName($module, $field);
                    }
                }
            }

            $moduleFieldList['values'] = $this->initValues($module, $field, $moduleFieldList, $withKey);
            $fieldList[$field] = $moduleFieldList;
        }

        if(!empty($fieldList['mailto']))
        {
            $fieldList['mailto']['control'] = 'multiple';
            $fieldList['mailto']['values']  = $this->transferConfig->sysDataList['user'];
        }

        if($this->config->edition != 'open')
        {
            /* Set workflow fields. */
            $workflowFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
                ->where('module')->eq($module)
                ->andWhere('buildin')->eq(0)
                ->fetchAll('id');

            foreach($workflowFields as $field)
            {
                if(!in_array($field->control, array('select', 'radio', 'multi-select'))) continue;
                if(!isset($fields[$field->field]) and !array_search($field->field, $fields)) continue;
                if(empty($field->options)) continue;

                $field   = $this->loadModel('workflowfield')->processFieldOptions($field);
                $options = $this->workflowfield->getFieldOptions($field, true);
                if($options)
                {
                    $control = $field->control == 'multi-select' ? 'multiple' : 'select';
                    $fieldList[$field->field]['title']   = $field->name;
                    $fieldList[$field->field]['control'] = $control;
                    $fieldList[$field->field]['values']  = $options;
                    $fieldList[$field->field]['from']    = 'workflow';
                    $this->config->$module->listFields .=  ',' . $field->field;
                }
            }
        }

        return $fieldList;
    }

    /**
     * Init Values.
     *
     * @param  int    $model
     * @param  int    $field
     * @param  string $fieldValue
     * @param  int    $withKey
     * @access public
     * @return void
     */
    public function initValues($model, $field, $fieldValue = '', $withKey = true)
    {
        $values = $fieldValue['values'];

        if($values and (strpos($this->transferConfig->sysDataFields, $values) !== false)) return $this->transferConfig->sysDataList[$values];

        if(!$fieldValue['dataSource']) return $values;

        extract($fieldValue['dataSource']); // $module, $method, $params, $pairs, $sql, $lang

        if(!empty($module) and !empty($method))
        {
            $params = !empty($params) ? $params : '';
            $pairs  = !empty($pairs)  ? $pairs : '';
            $values = $this->transferTao->getSourceByModuleMethod($model, $module, $method, $params, $pairs);
        }
        elseif(!empty($lang))
        {
            $values = isset($this->moduleLang->$lang) ? $this->moduleLang->$lang : '';
        }

        /* If empty values put system datas. */
        if(empty($values))
        {
            if(strpos($this->moduleConfig->sysLangFields, $field) !== false and !empty($this->moduleLang->{$field.'List'})) return $this->moduleLang->{$field.'List'};
            if(strpos($this->moduleConfig->sysDataFields, $field) !== false and !empty($this->transferConfig->sysDataList[$field])) return $this->transferConfig->sysDataList[$field];
        }

        if(is_array($values) and $withKey)
        {
            unset($values['']);
            foreach($values as $key => $value) $values[$key] = $value . "(#$key)";
        }

        return $values;
    }

    /**
     * 初始化名称字段。
     * Init Title.
     *
     * @param  string $module
     * @param  string $field
     * @access public
     * @return string
     */
    public function initTitle(string $module, string $field = ''): string
    {
        if(!$field) return '';

        $this->commonActions($module);

        /* 如果存在config->dtable->titles,则使用config->dtable->titles。 */
        /* If exists config->dtable->titles, use config->dtable->titles. */
        if(!empty($this->moduleConfig->fieldList[$field]['title'])) return $this->moduleLang->{$this->moduleConfig->fieldList[$field]['title']};

        /* 如果存在该字段的语言项,则使用该语言项。 */
        /* If exists the field's language item, use it. */
        if(isset($this->moduleLang->$field)) return $this->moduleLang->$field;

        /* 如果存在该字段的语言项别名,则使用该语言项别名。 */
        /* If exists the field's language item alias, use it. */
        if(isset($this->moduleLang->{$field . 'AB'})) return $this->moduleLang->{$field . 'AB'};

        return $field;
    }

    /**
     * 设置字段的控件类型(是否是下拉菜单)。
     * Init Control.
     *
     * @param  string $module
     * @param  string $field
     * @access public
     * @return void
     */
    public function initControl(string $module, string $field)
    {
        if(isset($this->moduleFieldList[$field]['control']))    return $this->moduleFieldList[$field]['control'];
        if(isset($this->moduleLang->{$field.'List'}))           return 'select'; //如果存在该字段的语言项列表,则控件为select
        if(isset($this->moduleFieldList[$field]['dataSource'])) return 'select'; //如果定义了dataSource,则控件为select

        /* 如果Transfer系统字段中存在该字段,则control为下拉。 */
        /* If Transfer system field exists the field, control is select. */
        if(strpos($this->transferConfig->sysDataFields, $field) !== false) return 'select';
        return $this->transferConfig->fieldList['control'];
    }

    /**
     * 设置字段是否必填。
     * Init Required.
     *
     * @param  string $module
     * @param  string $field
     * @access public
     * @return void
     */
    public function initRequired(string $module, string $field)
    {
        if(!$field) return 'no';
        $this->commonActions($module);

        /* 检查必填字段中是否存在该字段，如果存在返回yes，否则返回no。 */
        /* Check whether the required field contains the field. If yes, return yes. Otherwise, return no. */
        if(empty($this->moduleConfig->create->requiredFields)) return 'no';
        $requiredFields = "," . $this->moduleConfig->create->requiredFields . ",";
        if(strpos($requiredFields, $field) !== false) return 'yes';

        return 'no';
    }

    /**
     * 初始化系统数据字段列表(project,execution,product,user)。
     * Init system datafields list.
     *
     * @access public
     * @return array
     */
    public function initSysDataFields()
    {
        $this->commonActions();
        $dataList      = array();
        $sysDataFields = explode(',', $this->transferConfig->sysDataFields);

        foreach($sysDataFields as $field)
        {
            /* 调用对应模块getPairs方法获取id => name 关联数据。 */
            /* Call getPairs method of corresponding module to get id => name related data. */
            $dataList[$field] = $this->loadModel($field)->getPairs();
            if(!isset($dataList[$field][0])) $dataList[$field][0] = '';

            sort($dataList[$field]);

            if($field == 'user')
            {
                $dataList[$field] = $this->loadModel($field)->getPairs('noclosed|nodeleted|noletter');

                unset($dataList[$field]['']);

                if(!in_array(strtolower($this->app->methodName), array('ajaxgettbody', 'ajaxgetoptions', 'showimport'))) foreach($dataList[$field] as $key => $value) $dataList[$field][$key] = $value . "(#$key)";
            }
        }

        return $dataList;
    }

    /**
     * 将被操作模块与Transfer模块的配置合并。
     * Merge config.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function mergeConfig(string $module)
    {
        $this->commonActions($module);
        $transferConfig = $this->transferConfig;
        $moduleConfig   = $this->moduleConfig;
        if(!isset($moduleConfig->export)) $moduleConfig->export = new stdClass();
        if(!isset($moduleConfig->import)) $moduleConfig->export = new stdClass();

        $this->moduleConfig->dateFields     = isset($moduleConfig->dateFields)     ? $moduleConfig->dateFields     : $transferConfig->dateFields;
        $this->moduleConfig->listFields     = isset($moduleConfig->listFields)     ? $moduleConfig->listFields     : $transferConfig->listFields;
        $this->moduleConfig->sysLangFields  = isset($moduleConfig->sysLangFields)  ? $moduleConfig->sysLangFields  : $transferConfig->sysLangFields;
        $this->moduleConfig->sysDataFields  = isset($moduleConfig->sysDataFields)  ? $moduleConfig->sysDataFields  : $transferConfig->sysDataFields;
        $this->moduleConfig->datetimeFields = isset($moduleConfig->datetimeFields) ? $moduleConfig->datetimeFields : $transferConfig->datetimeFields;
    }

    /**
     * Get ExportDatas.
     *
     * @param  array  $fieldList
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function getExportDatas($fieldList, $rows = array())
    {
        if(empty($fieldList)) return array();

        $exportDatas    = array();
        $dataSourceList = array();

        foreach($fieldList as $key => $field)
        {
            $exportDatas['fields'][$key] = $field['title'];
            if($field['values'])
            {
                $exportDatas[$key] = $field['values'];
                $dataSourceList[]  = $key;
            }
        }

        if(empty($rows)) return $exportDatas;

        $exportDatas['user'] = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');

        foreach($rows as $id => $values)
        {
            foreach($values as $field => $value)
            {
                if(isset($fieldList[$field]['from']) and $fieldList[$field]['from'] == 'workflow') continue;
                if(in_array($field, $dataSourceList))
                {
                    if($fieldList[$field]['control'] == 'multiple')
                    {
                        $multiple     = '';
                        $separator    = $field == 'mailto' ? ',' : "\n";
                        $multipleLsit = explode(',', $value);

                        foreach($multipleLsit as $key => $tmpValue) $multipleLsit[$key] = zget($exportDatas[$field], $tmpValue);
                        $multiple = implode($separator, $multipleLsit);
                        $rows[$id]->$field = $multiple;
                    }
                    else
                    {
                        $rows[$id]->$field = zget($exportDatas[$field], $value, $value);
                    }
                }
                elseif(strpos($this->config->transfer->userFields, $field) !== false)
                {
                    /* if user deleted when export set userFields is itself. */
                    $rows[$id]->$field = zget($exportDatas['user'], $value);
                }

                /* if value = 0 or value = 0000:00:00 set value = ''. */
                if(is_string($value) and ($value == '0' or substr($value, 0, 4) == '0000')) $rows[$id]->$field = '';
            }
        }

        $exportDatas['rows'] = array_values($rows);
        return $exportDatas;
    }

    /**
     * 获取当前选中数据的所有附件。
     * Get related files.
     *
     * @param  string $module
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function getFiles(string $module, array $rows)
    {
        if(empty($rows)) return array();

        /* 获取附件分组。*/
        /* Get file groups. */
        $fileGroups = $this->transferTao->getFileGroups($module, array_keys($rows));
        if(empty($fileGroups)) return $rows;

        /* 将附件追加到数组中并设置下载链接。*/
        /* Set related files. */
        foreach($rows as $row)
        {
            $row->files = '';
            if(!isset($fileGroups[$row->id])) continue;

            foreach($fileGroups[$row->id] as $file)
            {
                /* 设置下载链接。*/
                /* Set download link. */
                $fileURL     = common::getSysURL() . helper::createLink('file', 'download', "fileID={$file->id}");
                $row->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
            }
        }
        return $rows;
    }

    /**
     * 设置下拉字段数据。
     * Set list value.
     *
     * @param  string $module
     * @param  array  $fieldList
     * @access public
     * @return array
     */
    public function setListValue(string $module, array $fieldList)
    {
        $lists = array();

        if(!empty($this->moduleListFields))
        {
            $listFields = $this->moduleListFields;

            /* 从fieldList和sysLangFields中为下拉字段赋值。*/
            /* Set value from fieldList and sysLangFields. */
            foreach($listFields as $field)
            {
                if(empty($field)) continue;
                $listName = $field . 'List'; // 下拉字段以字段名 + List命名。
                if(!empty($_POST[$listName])) continue;

                $lists[$listName] = array();
                if(!empty($fieldList[$field]))
                {
                    $lists[$listName] = $fieldList[$field]['values'];

                    /* 从语言项里赋值。*/
                    /* Set value from lang. */
                    if(strpos($this->moduleConfig->sysLangFields, $field)) $lists[$listName] = implode(',', $fieldList[$field]['values']);
                }

                /* 将下拉字段赋值给excel->sysDataField。*/
                /* Set value to excel->sysDataField. */
                if(isset($this->config->excel->sysDataField) && is_array($lists[$listName])) $this->config->excel->sysDataField[] = $field;
            }

            $lists['listStyle'] = $listFields;
        }

        /* 如果有级联字段,则加入级联字段列表(如用例的模块和相关需求)。*/
        /* If has cascade field, add cascade field list. */
        if(!empty($this->moduleConfig->cascade))
        {
            $lists = $this->transferTao->getCascadeList($module, $lists);
            $lists['cascade'] = $this->moduleConfig->cascade;
        }

        return $lists;
    }

    /**
     * 获取导出数据。
     * Get Rows.
     *
     * @param  string       $module
     * @param  object|array $fieldList
     * @access public
     * @return array
     */
    public function getRows(string $module, object|array $fieldList)
    {
        $moduleDatas = $this->getQueryDatas($module); // 根据SESSION中的条件查询数据。

        if(is_object($fieldList))      $fieldList = (array) $fieldList;
        if(isset($fieldList['files'])) $moduleDatas = $this->getFiles($module, $moduleDatas); // 如果有附件字段则获取附件。

        /* 如果存在rows则用rows中的数据覆盖查询的数据。*/
        /* If has rows, use rows data to cover query data. */
        $rows = !empty($_POST['rows']) ? $_POST['rows'] : array();
        foreach($rows as $id => $row) $moduleDatas[$id] = (object) array_merge((array)$moduleDatas[$id], (array)$row);

        /* 设置子数据。*/
        /* Deal children datas and multiple tasks. */
        if($moduleDatas) $moduleDatas = $this->updateChildDatas($moduleDatas);

        /* Deal linkStories datas. */
        if($moduleDatas and isset($fieldList['linkStories'])) $moduleDatas = $this->updateLinkStories($moduleDatas);

        return $moduleDatas;
    }

    /**
     * Update LinkStories datas.
     *
     * @param  array $stories
     * @access public
     * @return array
     */
    public function updateLinkStories($stories)
    {
        $productIDList = array();
        foreach($stories as $story) $productIDList[] = $story->product;
        $productIDList = array_unique($productIDList);

        $storyDatas = end($stories);
        $lastType   = $storyDatas->type;

        if($storyDatas->type == 'requirement')
        {
            $stories = $this->loadModel('story')->mergePlanTitleAndChildren($productIDList, $stories, $lastType);
        }
        elseif($storyDatas->type == 'story')
        {
            return $stories;
        }

        return $stories;
    }

    /**
     * 获取查询数据。
     * Get query datas.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryDatas(string $module = '')
    {
        $moduleDatas    = array();
        $checkedItem    = $this->post->checkedItem ? $this->post->checkedItem : $this->cookie->checkedItem;
        $onlyCondition  = $this->session->{$module . 'OnlyCondition'};
        $queryCondition = $this->session->{$module . 'QueryCondition'};

        /* 插入用例场景数据。*/
        /* Fetch the scene's cases. */
        if($module == 'testcase') $queryCondition = preg_replace("/AND\s+t[0-9]\.scene\s+=\s+'0'/i", '', $queryCondition);

        /* 根据SESSION中的条件查询数据。*/
        /* Fetch datas by session condition. */
        if($onlyCondition && $queryCondition)
        {
            if($module == 'story') $queryCondition = str_replace('story', 'id', $queryCondition);

            $table       = zget($this->config->objectTables, $module); //获取对应的表
            $moduleDatas = $this->dao->select('*')->from($table)->alias('t1')
                ->where($queryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($checkedItem)->fi()
                ->fetchAll('id');
        }
        elseif($queryCondition)
        {
            $selectKey = 'id';
            if($module == 'testcase') $module = 'case';

            /* 字段前加入表别名。*/
            /* Add table alias to field. */
            preg_match_all('/[`"]' . $this->config->db->prefix . $module .'[`"] AS ([\w]+) /', $queryCondition, $matches);
            if(isset($matches[1][0])) $selectKey = "{$matches[1][0]}.id";

            $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND $selectKey IN(" . ($checkedItem ? $checkedItem : '0') . ")" : ''));
            while($row = $stmt->fetch())
            {
                if($selectKey !== 't1.id' and isset($row->$module) and isset($row->id)) $row->id = $row->$module;
                $moduleDatas[$row->id] = $row;
            }
        }
        return $moduleDatas;
    }

    /**
     * Get nature datas.
     *
     * @param  int    $module
     * @param  int    $datas
     * @param  string $filter
     * @param  string $fields
     * @access public
     * @return void
     */
    public function getNatureDatas($module, $datas, $filter = '', $fields = '')
    {
        $fieldList = $this->initFieldList($module, array_keys($fields), false);
        $lang = $this->lang->$module;

        foreach($datas as $key => $data)
        {
            foreach($data as $field => $cellValue)
            {
                if(empty($cellValue)) continue;
                if(strpos($this->transferConfig->dateFields, $field) !== false and helper::isZeroDate($cellValue)) $datas[$key]->$field = '';
                if(strpos($this->moduleConfig->dateFields, $field) !== false or strpos($this->moduleConfig->datetimeFields, $field) !== false) $datas[$key]->$field = $this->loadModel('common')->formatDate($cellValue);
                if(is_array($cellValue)) continue;

                if(!empty($fieldList[$field]['from']) and in_array($fieldList[$field]['control'], array('select', 'multiple')))
                {
                    $control = $fieldList[$field]['control'];
                    if($control == 'multiple')
                    {
                        $cellValue = explode("\n", $cellValue);
                        foreach($cellValue as &$value) $value = array_search($value, $fieldList[$field]['values'], true);
                        $datas[$key]->$field = implode(',', $cellValue);
                    }
                    else
                    {
                        $datas[$key]->$field = array_search($cellValue, $fieldList[$field]['values']);
                    }
                }
                elseif(strrpos($cellValue, '(#') === false)
                {
                    if(!isset($lang->{$field . 'List'}) or !is_array($lang->{$field . 'List'})) continue;

                    /* When the cell value is key of list then eq the key. */
                    $listKey = array_keys($lang->{$field . 'List'});
                    unset($listKey[0]);
                    unset($listKey['']);

                    $fieldKey = array_search($cellValue, $lang->{$field . 'List'});
                    if($fieldKey) $datas[$key]->$field = array_search($cellValue, $lang->{$field . 'List'});
                }
                else
                {
                    $id = trim(substr($cellValue, strrpos($cellValue,'(#') + 2), ')');
                    $datas[$key]->$field = $id;
                    $control = !empty($this->moduleFieldList[$field]['control']) ? $this->moduleFieldList[$field]['control'] : '';
                    if($control == 'multiple')
                    {
                        $cellValue = explode("\n", $cellValue);
                        foreach($cellValue as &$value)
                        {
                            $value = trim(substr($value, strrpos($value,'(#') + 2), ')');
                        }
                        $cellValue = array_filter($cellValue, function($v) {return (empty($v) && $v == '0') || !empty($v);});
                        $datas[$key]->$field = implode(',', $cellValue);
                    }
                }
            }

        }
        return $datas;
    }

    /**
     * Get pagelist for datas.
     *
     * @param  int    $datas
     * @param  int    $pagerID
     * @access public
     * @return void
     */
    public function getPageDatas($datas, $pagerID = 1)
    {
        $result = new stdClass();
        $result->allCount = count($datas);
        $result->allPager = 1;
        $result->pagerID  = $pagerID;

        $maxImport = $this->maxImport;
        if($result->allCount > $this->config->file->maxImport)
        {
            if(empty($maxImport))
            {
                $result->maxImport = $maxImport;
                $result->datas     = $datas;
                return $result;
            }

            $result->allPager = ceil($result->allCount / $maxImport);
            $datas = array_slice($datas, ($pagerID - 1) * $maxImport, $maxImport, true);
        }

        if(!$maxImport) $this->maxImport = $result->allCount;
        $result->maxImport = $maxImport;
        $result->isEndPage = $pagerID >= $result->allPager;
        $result->datas     = $datas;

        $this->session->set('insert', true);

        foreach($datas as $data)
        {
            if(isset($data->id)) $this->session->set('insert', false);
        }

        if(empty($datas)) return print(js::locate('back'));
        return $result;
    }

    /**
     * Get import fields.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getImportFields($module = '')
    {
        $this->commonActions($module);
        $moduleLang = $this->lang->$module;
        $fields    = explode(',', $this->moduleConfig->templateFields);

        array_unshift($fields, 'id');
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($moduleLang->$fieldName) ? $moduleLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        if($this->config->edition != 'open')
        {
            $appendFields = $this->loadModel('workflowaction')->getFields($module, 'showimport', false);
            foreach($appendFields as $appendField)
            {
                if(!$appendField->buildin and $appendField->show) $fields[$appendField->field] = $appendField->name;
            }
        }

        return $fields;
    }

    /**
     * Update children datas.
     *
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function updateChildDatas($datas)
    {
        $children = array();
        foreach($datas as $data)
        {
            $id = $data->id;
            if(!empty($data->mode)) $datas[$id]->name = '[' . $this->lang->task->multipleAB . '] ' . $data->name;
            if(!empty($data->parent) and isset($datas[$data->parent]))
            {
                if(!empty($data->name)) $data->name = '>' . $data->name;
                elseif(!empty($data->title)) $data->title = '>' . $data->title;
                $children[$data->parent][$id] = $data;
                unset($datas[$id]);
            }
        }

        /* Move child data after parent data. */
        if(!empty($children))
        {
            $position = 0;
            foreach($datas as $data)
            {
                $position ++;
                if(isset($children[$data->id]))
                {
                    array_splice($datas, $position, 0, $children[$data->id]);
                    $position += count($children[$data->id]);
                }
            }
        }

        return $datas;
    }

    /**
     * Process rows for fields.
     *
     * @param  array  $rows
     * @param  array  $fields
     * @access public
     * @return void
     */
    public function processRows4Fields($rows = array(), $fields = array())
    {
        $objectDatas = array();

        foreach($rows as $currentRow => $row)
        {
            $tmpArray = new stdClass();
            foreach($row as $currentColumn => $cellValue)
            {
                if($currentRow == 1)
                {
                    $field = array_search($cellValue, $fields);
                    $columnKey[$currentColumn] = $field ? $field : '';
                    continue;
                }

                if(empty($columnKey[$currentColumn]))
                {
                    $currentColumn++;
                    continue;
                }

                $field = $columnKey[$currentColumn];
                $currentColumn++;

                /* Check empty data. */
                if(empty($cellValue))
                {
                    $tmpArray->$field = '';
                    continue;
                }

                $tmpArray->$field = $cellValue;
            }

            if(!empty($tmpArray->title) and !empty($tmpArray->name)) $objectDatas[$currentRow] = $tmpArray;
            unset($tmpArray);
        }

        if(empty($objectDatas))
        {
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($this->lang->excel->noData);
            return print(js::locate('back'));
        }

        return $objectDatas;
    }
}
