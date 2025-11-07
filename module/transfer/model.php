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
    /* 最大导入数量。*/
    /* The max import number. */
    public $maxImport;

    /* Transfer模块的配置。*/
    /* Transfer module config. */
    public $transferConfig;

    /* 导入字段。*/
    /* Import fields. */
    public $templateFields;

    /* 导出字段。*/
    /* Export fields. */
    public $exportFields;

    /* 模块配置。*/
    /* Module config. */
    public $moduleConfig;

    /* 模块语言项。*/
    /* Module language. */
    public $moduleLang;

    /* 模块字段列表。*/
    /* Module field list. */
    public $moduleFieldList;

    /* 模块下拉字段列表。*/
    /* Module list fields. */
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

        $this->maxImport  = isset($_COOKIE['maxImport']) ? (int)$_COOKIE['maxImport'] : 0;
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
            if(in_array($module, array('epic', 'requirement'))) $module = 'story';
            if($module == 'caselib') $module = 'testcase';

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
            $parentList = array_map(function($row)
            {
                return $row->parent;
            }, $rows);
            $parentList  = array_filter($parentList);
            $parentList  = array_unique($parentList);
            $parentPairs = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($parentList)->fetchPairs();
            $product    = $this->loadModel('product')->getByID((int)$this->session->storyTransferParams['productID']);

            foreach($rows as $id => $row)
            {
                $rows[$id]->parent = $row->parent ? '#' . $row->parent . ' ' . $parentPairs[$row->parent] : '';
                if(!empty($product->shadow)) $rows[$id]->product = '';
            }
        }

        /* 设置Excel下拉数据。 */
        /* Set Excel dropdown data. */
        $list = $this->setListValue($module, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        /* Get export rows and fields datas. */
        $exportDatas = $this->generateExportDatas($fieldList, $rows);

        $fields = $exportDatas['fields'];
        $rows   = !empty($exportDatas['rows']) ? $exportDatas['rows'] : array();

        if($this->config->edition != 'open') list($fields, $rows) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $rows, $module);

        $this->post->set('rows',   $rows);
        $this->post->set('fields', $fields);
        $this->post->set('kind',   $module);
    }

    /**
     * 将字段格式化成数组。
     * Init FieldList.
     *
     * @param  string $module
     * @param  string $fields
     * @param  bool   $withKey
     * @access public
     * @return array
     */
    public function initFieldList(string $module, string|array $fields, bool $withKey = true)
    {
        if(!is_array($fields)) $fields = explode(',', $fields);

        $this->commonActions($module);
        $this->mergeConfig($module);
        $this->transferConfig->sysDataList = $this->initSysDataFields();
        $transferFieldList = $this->transferConfig->fieldList; //生成一个完整的fieldList结构。
        if(is_string($this->moduleConfig->dateFields)) $this->moduleConfig->dateFields = explode(',', $this->moduleConfig->dateFields);
        if(is_string($this->moduleConfig->datetimeFields)) $this->moduleConfig->datetimeFields = explode(',', $this->moduleConfig->datetimeFields);
        if(is_string($this->moduleConfig->textareaFields)) $this->moduleConfig->textareaFields = explode(',', $this->moduleConfig->textareaFields);

        $fieldList = array();
        /* build module fieldList. */
        foreach($fields as $field)
        {
            $field = trim($field);
            $moduleFieldList = isset($this->moduleFieldList[$field]) ? $this->moduleFieldList[$field] : array();

            /* 根据fieldList结构生成每个字段的结构。*/
            /* Generate each field structure by fieldList structure. */
            foreach($transferFieldList as $transferField => $value)
            {
                if((!isset($moduleFieldList[$transferField])) or $transferField == 'title')
                {
                    $moduleFieldList[$transferField] = $this->transferConfig->fieldList[$transferField];
                    if(strpos($this->transferConfig->initFunction, $transferField) !== false) //调用初始化方法
                    {
                        $funcName = 'init' . ucfirst($transferField);
                        $moduleFieldList[$transferField] = $this->$funcName($module, $field);
                        if($transferField == 'title') $moduleFieldList['label'] = $moduleFieldList[$transferField];
                    }
                }
            }

            if(in_array($field, $this->moduleConfig->dateFields)) $moduleFieldList['control'] = 'datePicker';
            if(in_array($field, $this->moduleConfig->datetimeFields)) $moduleFieldList['control'] = 'datetimePicker';
            if(in_array($field, $this->moduleConfig->textareaFields)) $moduleFieldList['control'] = 'textarea';
            $moduleFieldList['multiple'] = $moduleFieldList['control'] == 'multiple';
            if($moduleFieldList['control'] == 'select' || $moduleFieldList['control'] == 'multiple') $moduleFieldList['control'] = 'picker';

            $moduleFieldList['width'] = isset($this->moduleConfig->dtable->fieldList[$field]['width']) ? $this->moduleConfig->dtable->fieldList[$field]['width'] : '136px';
            if(is_numeric($moduleFieldList['width'])) $moduleFieldList['width'] .= 'px';

            $moduleFieldList['name']  = $field;
            $moduleFieldList['items'] = $this->initItems($module, $field, $moduleFieldList, $withKey);

            $fieldList[$field] = $moduleFieldList;
        }

        /* 抄送给默认多选。*/
        /* Copy to default multiple. */
        if(!empty($fieldList['mailto']))
        {
            $fieldList['mailto']['control']  = 'picker';
            $fieldList['mailto']['multiple'] = true;
            $fieldList['mailto']['items']    = $this->transferConfig->sysDataList['user'];
        }

        if($this->config->edition != 'open') $fieldList = $this->initWorkflowFieldList($module, $fieldList);
        return $fieldList;
    }

    /**
     * 初始化工作流字段。
     * Init workflow fieldList.
     *
     * @param  string    $module
     * @param  array     $fieldList
     * @access protected
     * @return array
     */
    protected function initWorkflowFieldList(string $module, array $fieldList): array
    {
        $this->loadModel($module);

        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $groupID    = $this->loadModel('workflowgroup')->getGroupIDByData($moduleName, null);
        $action     = $this->loadModel('workflowaction')->getByModuleAndAction($moduleName, $methodName, $groupID);

        if(empty($action)) return $fieldList;
        if($action->extensionType == 'none' and $action->buildin == 1) return $fieldList;

        $notEmptyRule   = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
        $workflowFields = $this->workflowaction->getPageFields($moduleName, $methodName, true, null, 0, $groupID);
        foreach($workflowFields as $field)
        {
            if(empty($fieldList[$field->field])) continue;
            if(!empty($field->buildin)) continue;
            if(empty($field->show)) continue;
            if($field->control == 'file')
            {
                unset($fieldList[$field->field]);
                continue;
            }

            $fieldList[$field->field]['name']  = $field->field;
            $fieldList[$field->field]['label'] = $field->name;
            $fieldList[$field->field]['title'] = $field->name;

            if($notEmptyRule && strpos(",{$field->rules},", ",{$notEmptyRule->id},") !== false) $fieldList[$field->field]['required'] = true;

            $fieldList[$field->field]['control'] = $this->loadModel('flow')->buildFormControl($field);
            if(in_array($field->control, array('select', 'radio', 'multi-select', 'checkbox')))
            {
                if(empty($field->options)) continue;

                $field   = $this->loadModel('workflowfield')->processFieldOptions($field);
                $options = $this->workflowfield->getFieldOptions($field, true);
                if($options)
                {
                    $fieldList[$field->field]['items']   = $options;
                    if($field->control == 'multi-select') $fieldList[$field->field]['multiple'] = true;

                    $this->moduleListFields[] = $field->field;
                    $this->config->$module->listFields .=  ',' . $field->field;
                }
            }
        }
        return $fieldList;
    }

    /**
     * 初始化导出字段下拉列表的数据。
     * Init field items.
     *
     * @param  string $model
     * @param  string $field
     * @param  array  $object
     * @param  bool   $withKey 是否需要将键值拼接到Value中
     * @access public
     * @return array
     */
    public function initItems(string $model, string $field, array $object, bool $withKey = true)
    {
        $items = $object['items'];
        if(!$object['dataSource']) return $items;

        /* 解析dataSource。*/
        /* Parse dataSource. */
        extract($object['dataSource']); // $module, $method, $params, $pairs, $sql, $lang

        /* 如果配置了系统字段,使用系统数据。*/
        /* If empty items put system datas. */
        if(empty($items))
        {
            if(strpos($this->moduleConfig->sysLangFields, $field) !== false && !empty($this->moduleLang->{$field.'List'}))
            {
                if($field == 'pri' && isset($this->moduleLang->priList[0])) unset($this->moduleLang->priList[0]);
                $items = $this->moduleLang->{$field.'List'};
            }
            if(strpos($this->moduleConfig->sysDataFields, $field) !== false && !empty($this->transferConfig->sysDataList[$field])) $items = $this->transferConfig->sysDataList[$field];
        }

        /* 如果配置了来源方法，则调用该方法。*/
        /* If config the source method, call the method. */
        if(!empty($module) && !empty($method))
        {
            $params = !empty($params) ? $params : '';
            $pairs  = !empty($pairs)  ? $pairs : '';
            $items = $this->transferTao->getSourceByModuleMethod($model, $module, $method, $params, $pairs);
        }
        elseif(!empty($lang))
        {
            /* 如果配置了语言字段,返回语言数据。*/
            /* If config the language field, return language data. */
            $items = isset($this->moduleLang->$lang) ? $this->moduleLang->$lang : '';
        }

        if(is_array($items) && $withKey)
        {
            unset($items['']);
            foreach($items as $key => $value) $items[$key] = $value . "(#$key)";
        }

        return $items;
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
        if(!$field) return false;
        $this->commonActions($module);

        /* 检查必填字段中是否存在该字段，如果存在返回yes，否则返回no。 */
        /* Check whether the required field contains the field. If yes, return true. Otherwise, return false. */
        if(empty($this->moduleConfig->create->requiredFields)) return false;
        $requiredFields = "," . $this->moduleConfig->create->requiredFields . ",";
        if(strpos($requiredFields, $field) !== false) return true;

        return false;
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

            ksort($dataList[$field]);

            if($field == 'user')
            {
                $dataList[$field] = $this->loadModel($field)->getPairs('noclosed|nodeleted|noletter');
                unset($dataList[$field]['']);
            }

            if(!in_array(strtolower($this->app->methodName), array('ajaxgettbody', 'ajaxgetoptions', 'showimport'))) foreach($dataList[$field] as $key => $value) $dataList[$field][$key] = $value . "(#$key)";
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
        $this->moduleConfig->textareaFields = isset($moduleConfig->textareaFields) ? $moduleConfig->textareaFields : $transferConfig->textareaFields;
    }

    /**
     * 生成导出数据。
     * Generate export datas.
     *
     * @param  array  $fieldList
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function generateExportDatas(array $fieldList, array $rows = array())
    {
        $exportDatas    = array();
        $dataSourceList = array();

        foreach($fieldList as $key => $field)
        {
            $exportDatas['fields'][$key] = $field['title'];
            if($field['values'] || $field['items'])
            {
                $exportDatas[$key] = $field['items'] ? $field['items'] : $field['values'];
                $dataSourceList[]  = $key;
            }
        }
        if(empty($rows)) return $exportDatas;

        $exportDatas['user'] = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');

        foreach($rows as $id => $row)
        {
            foreach($row as $field => $value)
            {
                if(isset($fieldList[$field]['from']) && $fieldList[$field]['from'] == 'workflow') continue;
                if(in_array($field, $dataSourceList))
                {
                    /* 处理下拉框数据。*/
                    /* Deal dropdown values. */
                    if($fieldList[$field]['control'] == 'select' || $fieldList[$field]['control'] == 'picker') $rows[$id]->$field = isset($exportDatas[$field][$value]) ? $exportDatas[$field][$value] : $value; // 单选下拉
                    if($fieldList[$field]['control'] == 'multiple' || $fieldList[$field]['multiple'] == true) // 多选下拉
                    {
                        $separator    = $field == 'mailto' ? ',' : "\n";
                        $multipleLsit = explode(',', (string) $value);

                        foreach($multipleLsit as $key => $tmpValue) $multipleLsit[$key] = zget($exportDatas[$field], $tmpValue);
                        $multiple = implode($separator, $multipleLsit);
                        $rows[$id]->$field = $multiple;
                    }
                }
                elseif(strpos($this->config->transfer->userFields, $field) !== false)
                {
                    /* 处理用户字段。*/
                    /* if user deleted when export set userFields is itself. */
                    $rows[$id]->$field = zget($exportDatas['user'], $value);
                }

                /* 处理为空字段的情况。*/
                /* if value = 0 or value = 0000:00:00 set value = ''. */
                if(is_string($rows[$id]->$field) && ($rows[$id]->$field == '0' || substr($rows[$id]->$field, 0, 10) == '0000:00:00')) $rows[$id]->$field = '';
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
                if(!empty($_POST[$listName]))
                {
                    if(isset($this->config->excel->sysDataField)) $this->config->excel->sysDataField[] = $field;
                    continue;
                }

                $lists[$listName] = array();
                if(!empty($fieldList[$field]))
                {
                    $lists[$listName] = !empty($fieldList[$field]['items']) ? $fieldList[$field]['items'] : $fieldList[$field]['values'];

                    /* 从语言项里赋值。*/
                    /* Set value from lang. */
                    if(strpos($this->moduleConfig->sysLangFields, $field) !== false && is_array($fieldList[$field]['values'])) $lists[$listName] = implode(',', $fieldList[$field]['values']);
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

        /* 如果要导出描述或验收标准字段，则追加。*/
        /* If export spec or verify field, append. */
        if(isset($fieldList['spec']) || isset($fieldList['verify']))
        {
            if($module == 'demand')
            {
                $editorDataList = $this->dao->select('demand,spec,verify')->from(TABLE_DEMANDSPEC)->where('demand')->in(array_keys($moduleDatas))->fetchAll('demand');
            }
            else
            {
                $editorDataList = $this->dao->select('story,spec,verify')->from(TABLE_STORYSPEC)->where('story')->in(array_keys($moduleDatas))->fetchAll('story');
            }
            foreach($moduleDatas as $moduleData)
            {
                if(isset($fieldList['spec'])) $moduleData->spec = !empty($editorDataList[$moduleData->id]) ? $editorDataList[$moduleData->id]->spec : $editorDataList[$moduleData->id]->spec;
                if(isset($fieldList['verify'])) $moduleData->verify = !empty($editorDataList[$moduleData->id]) ? $editorDataList[$moduleData->id]->verify : $editorDataList[$moduleData->id]->verify;
            }
        }

        /* 设置子数据。*/
        /* Deal children datas and multiple tasks. */
        if($moduleDatas) $moduleDatas = $this->transferTao->updateChildDatas($moduleDatas);

        return $moduleDatas;
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
        $orderBy        = $this->session->{$module . 'OrderBy'};

        /* 插入用例场景数据。*/
        /* Fetch the scene's cases. */
        if($module == 'testcase') $queryCondition = preg_replace("/AND\s+t[0-9]\.\"?scene\"?\s+=\s+'0'/i", '', $queryCondition);

        /* 根据SESSION中的条件查询数据。*/
        /* Fetch datas by session condition. */
        if($onlyCondition && $queryCondition)
        {
            if($module == 'story') $queryCondition = str_replace('`story`', '`id`', $queryCondition);

            /* 业需用需列表也可能选中软需进行导出。 */
            if(in_array($module, array('epic', 'requirement')) && $this->post->exportType == 'selected')
            {
                $queryCondition = str_replace("`type` = '$module'", '1 = 1', $queryCondition);
            }

            $selectedFields = '*';
            if($orderBy && strpos($orderBy, 'priOrder') !== false)      $selectedFields .= ",IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) AS priOrder";
            if($orderBy && strpos($orderBy, 'severityOrder') !== false) $selectedFields .= ",IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) AS severityOrder";

            $table = zget($this->config->objectTables, $module); //获取对应的表
            $moduleDatas = $this->dao->select($selectedFields)->from($table)
                ->where($queryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($checkedItem)->fi()
                ->beginIF($orderBy)->orderBy($orderBy)->fi()
                ->fetchAll('id', false);
        }
        elseif($queryCondition)
        {
            $selectKey = 'id';
            if($module == 'testcase') $module = 'case';

            /* 字段前加入表别名。*/
            /* Add table alias to field. */
            preg_match_all('/[`"]' . $this->config->db->prefix . $module .'[`"] AS ([\w]+) /', $queryCondition, $matches);
            if(isset($matches[1][0])) $selectKey = "{$matches[1][0]}.id";

            $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND $selectKey IN(" . ($checkedItem ? $checkedItem : '0') . ")" : '') . ($orderBy ? " ORDER BY $orderBy" : ''));
            while($row = $stmt->fetch())
            {
                if($selectKey !== 't1.id' and isset($row->$module) and isset($row->id)) $row->id = $row->$module;
                $moduleDatas[$row->id] = $row;
            }
        }
        return $moduleDatas;
    }

    /**
     * 处理Excel表中下拉框数据。
     * Deal excel dropdown values.
     *
     * @param  string $module 模块
     * @param  array  $rows   Excel数据行
     * @param  string $filter
     * @param  array  $fields
     * @access public
     * @return array
     */
    public function parseExcelDropdownValues(string $module, array $rows, string $filter = '', array $fields = array())
    {
        $this->commonActions($module);
        $fieldList = $this->initFieldList($module, array_keys($fields), false);
        if(is_string($this->moduleConfig->dateFields))     $this->moduleConfig->dateFields     = explode(',', $this->moduleConfig->dateFields);
        if(is_string($this->moduleConfig->datetimeFields)) $this->moduleConfig->datetimeFields = explode(',', $this->moduleConfig->datetimeFields);
        if(is_string($this->transferConfig->dateFields))   $this->transferConfig->dateFields   = explode(',', $this->transferConfig->dateFields);

        foreach($rows as $key => $data)
        {
            /* 每行的数据。*/
            /* Row data. */
            foreach($data as $field => $cellValue)
            {
                if(empty($cellValue) || is_array($cellValue)) continue;
                if(in_array($field, $this->transferConfig->dateFields) and helper::isZeroDate($cellValue)) $rows[$key]->$field = ''; // 如果是日期,并且为 0000-00-00,则转换为空
                if(in_array($field, $this->moduleConfig->dateFields) or in_array($field, $this->moduleConfig->datetimeFields)) $rows[$key]->$field = $this->loadModel('common')->formatDate((string)$cellValue); // 如果是时间类型字段,则转换为时间

                /* 获取字段的控件类型。*/
                /* Get field control type. */
                $control = isset($fieldList[$field]['control']) ? $fieldList[$field]['control'] : '';
                if(isset($control['control'])) $control = $control['control'];

                /* 如果字段是下拉字段并且在excel里不是下拉框的形式时，根据fieldList->value查找value。*/
                /* If the field is a dropdown field and the value in excel is not a dropdown box, the value is found by fieldList->value. */
                if(!in_array($control, array('select', 'multiple', 'picker', 'radioList', 'checkList'))) continue;
                $rows[$key]->$field = $this->transferTao->extractElements((string) $cellValue, $field, $fieldList[$field]['items']);
            }
        }
        return $rows;
    }

    /**
     * 处理导入分页数据。
     * Get pagelist for datas.
     *
     * @param  array     $datas
     * @param  int       $pagerID
     * @access protected
     * @return object
     */
    protected function getPageDatas(array $datas, int $pagerID = 1)
    {
        $maxImport = $this->maxImport; //每页最大导入数

        $result = new stdClass();
        $result->allCount = count($datas); //所有数据
        $result->allPager = 1;             //起始页
        $result->pagerID  = $pagerID;      //当前页

        /* 如果总数大于最大导入数,则分页。*/
        /* If the total number is greater than the maximum import number, then paging. */
        if($result->allCount > $this->config->file->maxImport && empty($maxImport))
        {
            $result->datas     = $datas;
            $result->maxImport = $maxImport;
            return $result;
        }

        /* 计算总页数。*/
        /* Calculate total pages. */
        if($maxImport)
        {
            $result->allPager = ceil($result->allCount / $maxImport); //总页数
            $datas = array_slice($datas, ($pagerID - 1) * $maxImport, $maxImport, true); //获取当前页的数据
        }

        if(!$maxImport) $this->maxImport = $result->allCount; //设置每页最大导入数

        $result->maxImport = $maxImport;
        $result->isEndPage = $pagerID >= $result->allPager; //是否是最后一页
        $result->datas     = $datas;

        $this->session->set('insert', !empty($datas) && empty(reset($datas)->id)); //如果存在ID列则在SESSION中标记insert用来判断是否是插入/更新

        if(empty($datas)) return print(js::locate('back'));
        return $result;
    }

    /**
     * 格式化导入导出标准数据格式。
     * Format standard data format.
     *
     * @param  string    $module
     * @param  string    $filter
     * @access protected
     * @return array
     */
    protected function format(string $module = '', string $filter = '')
    {
        /* Bulid import paris (field => name). */
        $fields  = $this->transferTao->getImportFields($module);

        /* 检查临时文件是否存在并返回完成路径。 */
        /* Check tmpfile. */
        $tmpFile = $this->checkTmpFile();

        /* 如果临时文件存在,则读取临时文件i，否则就创建临时文件。 */
        /* If tmp file exists, read tmp file, otherwise create tmp file. */
        if(!$tmpFile)
        {
            $rows = $this->getRowsFromExcel();  // 从Excel中获取数据
            if(dao::isError()) return false;
            $moduleData = $this->processRows4Fields($rows, $fields);  // 处理Excel中的数据过滤无效字段
            if(dao::isError()) return false;
            $moduleData = $this->parseExcelDropdownValues($module, $moduleData, $filter, $fields); // 解析Excel中下拉字段的数据，转换成具体value

            $this->createTmpFile($moduleData); //将格式化后的数据写入临时文件中
        }
        else
        {
            $moduleData = unserialize(file_get_contents($tmpFile));
        }

        if(isset($fields['id'])) unset($fields['id']);
        $this->session->set($module . 'TemplateFields',  implode(',', array_keys($fields))); // 将模板字段到SESSION中

        return $moduleData;
    }

    /**
     * 检查临时文件是否存在。
     * Check tmp file.
     *
     * @access protected
     * @return string|false
     */
    protected function checkTmpFile()
    {
        /* 从session中获取临时文件。*/
        /* Get tmp file from session. */
        $file    = $this->session->fileImportFileName;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if($this->maxImport and file_exists($tmpFile)) return $tmpFile;
        return false;
    }

    /**
     * 获取Excel中的数据。
     * Get rows from excel.
     *
     * @access protected
     * @return array|bool
     */
    protected function getRowsFromExcel(): array|bool
    {
        $rows = $this->file->getRowsFromExcel($this->session->fileImportFileName);
        if(is_string($rows))
        {
            if($this->session->fileImportFileName) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            dao::$errors['message'] = $rows;
            return false;
        }
        return $rows;
    }

    /**
     * 处理行数据。
     * Process rows for fields.
     *
     * @param  array     $rows
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function processRows4Fields($rows = array(), $fields = array())
    {
        $objectDatas = array();
        foreach($rows as $currentRow => $row)
        {
            $tmpArray = new stdClass();
            foreach($row as $currentColumn => $cellValue)
            {
                $cellValue = trim((string)$cellValue);
                /* 第一行是标题字段。*/
                /* First row is title field. */
                if($currentRow == 1)
                {
                    $field = array_search($cellValue, $fields); //找出要导入的字段
                    $columnKey[$currentColumn] = $field ? $field : '';
                    continue;
                }

                if(empty($columnKey[$currentColumn])) continue;
                $field = $columnKey[$currentColumn];
                /* Check empty data. */
                $tmpArray->$field = empty($cellValue) ? '' : $cellValue;

                $currentColumn ++;
            }

            if(!empty($tmpArray->title) || !empty($tmpArray->name)) $objectDatas[$currentRow] = $tmpArray;
            unset($tmpArray);
        }

        if(empty($objectDatas))
        {
            /* 删除临时文件和SESSION记录。*/
            /* Delete tmp file and session record. */
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            dao::$errors['message'] = $this->lang->excel->noData;
        }

        return $objectDatas;
    }

    /**
     * 创建临时文件。
     * Create tmpFile.
     *
     * @param  array     $objectDatas
     * @access protected
     * @return void
     */
    protected function createTmpFile(array $objectDatas)
    {
        $file    = $this->session->fileImportFileName;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if(file_exists($tmpFile)) unlink($tmpFile);
        file_put_contents($tmpFile, serialize($objectDatas));
        $this->session->set('tmpFile', $tmpFile);
    }
}
