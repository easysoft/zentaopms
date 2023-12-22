<?php
declare(strict_types=1);
/**
 * The zen file of transfer module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng<tanghucheng@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */

class transferZen extends transfer
{
    /**
     * 获取工作流字段.
     * Get workflow fields by module.
     *
     * @param  string    $module
     * @access protected
     * @return array
     */
    protected function getWorkflowFieldsByModule(string $module): array
    {
        return $this->dao->select('t2.*')->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
            ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.field=t2.field && t1.module=t2.module')
            ->where('t1.module')->eq($model)
            ->andWhere('t1.action')->eq('exporttemplate')
            ->andWhere('t2.buildin')->eq(0)
            ->orderBy('t1.order')
            ->fetchAll();
    }

    /**
     * 获取Excel中的数据。
     * Get rows from excel.
     *
     * @access public
     * @return array
     */
    protected function getRowsFromExcel(): array
    {
        $rows = $this->file->getRowsFromExcel($this->session->fileImportFileName);
        if(is_string($rows))
        {
            if($this->session->fileImportFileName) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($rows);
            return print(js::locate('back'));
        }
        return $rows;
    }

    /**
     * 将参数转成变量存到SESSION中。
     * Set SESSION by params.
     *
     * @param  string    $module
     * @param  string    $params
     * @access protected
     * @return array
     */
    protected function saveSession(string $module, string $params = ''): array
    {
        if($params)
        {
            /* 按, 分隔params。*/
            /* Split parameters into variables (executionID=1,status=open). */
            $params = explode(',', $params);
            foreach($params as $key => $param)
            {
                $param = explode('=', $param);
                $params[$param[0]] = $param[1];
                unset($params[$key]);
            }

            /* Save params to session. */
            $this->session->set(($module . 'TransferParams'), $params);

            return $params;
        }

        return array();
    }

    /**
     * 处理Task模块导出模板数组。
     * Process Task module export template array.
     *
     * @param  string    $module
     * @param  string    $params
     * @access protected
     * @return string
     */
    protected function processTaskTemplateFields(int $executionID = 0, string $fields = ''): string
    {
        $execution = $this->loadModel('execution')->getByID($executionID);

        /* 运维类型的迭代和需求跟总结评审类型的阶段，在导出字段中隐藏需求字段。*/
        /* Hide requirement field in Ops type. */
        if(isset($execution) and $execution->type == 'ops' or in_array($execution->attribute, array('request', 'review'))) $fields = str_replace('story,', '', $fields);
        return $fields;
    }

    /**
     * 初始化字段列表并拼接下拉菜单数据。
     * Init field list and append dropdown menu data.
     *
     * @param  string    $module
     * @param  string    $fields
     * @access protected
     * @return string
     */
    protected function initTemplateFields(string $module, string $fields = ''): array
    {
        /* 构造该模块的导出模板字段数据。*/
        /* Construct export template field data. */
        $fieldList = $this->transfer->initFieldList($module, $fields);

        /* 获取下拉字段的数据列表。*/
        /* Get dropdown field data list. */
        $list = $this->transfer->setListValue($module, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        $fields = $this->transfer->getExportDatas($fieldList);

        $this->post->set('fields', $fields['fields']);
        $this->post->set('kind', isset($_POST['kind']) ? $_POST['kind'] : $module);
        $this->post->set('rows', array());
        $this->post->set('extraNum', $this->post->num);
        $this->post->set('fileName', isset($_POST['fileName']) ? $_POST['fileName'] : $module . 'Template');
    }

    /**
     * 处理导入字段。
     * Process import fields.
     *
     * @param  string    $module
     * @param  array     $fields
     * @access protected
     * @return array
     */
    protected function formatFields(string $module, array $fields = array()): array
    {
        if($module == 'story')
        {
            $product = $this->loadModel('product')->getByID($this->session->storyTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
            if($this->session->storyType == 'requirement') unset($fields['plan']);
        }
        if($module == 'bug')
        {
            $product = $this->loadModel('product')->getByID($this->session->bugTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
            if($product->shadow and ($this->app->tab == 'execution' or $this->app->tab == 'project')) unset($fields['product']);
        }
        if($module == 'testcase')
        {
            $product = $this->loadModel('product')->getByID($this->session->testcaseTransferParams['productID']);
            if($product->type == 'normal') unset($fields['branch']);
        }

        return $fields;
    }

    /**
     * 创建临时文件。
     * Create tmpFile.
     *
     * @param  array  $objectDatas
     * @access public
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

    /**
     * 检查临时文件是否存在。
     * Check tmp file.
     *
     * @access protected
     * @return void
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
     * 检查suhosin信息。
     * Check suhosin info.
     *
     * @param  array  $datas
     * @access public
     * @return string
     */
    protected function checkSuhosinInfo(array $datas = array()): string
    {
        if(empty($datas)) return '';
        $current = (array)current($datas);

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($datas) * count($current); // Count all post datas
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) return extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
        return '';
    }

    /**
     * 读取excel并格式化数据。
     * Read excel and format data.
     *
     * @param  string $module
     * @param  int    $pagerID
     * @param  string $insert
     * @param  string $filter
     * @access public
     * @return object
     */
    protected function readExcel(string $module = '', int $pagerID = 1, string $insert = '', string $filter = ''): object
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','100');

        /* 格式化数据。*/
        /* Formatting excel data. */
        $formatDatas = $this->format($module, $filter);

        /* 获取分页后的数据。*/
        /* Get page by datas. */
        $datas        = $this->transfer->getPageDatas($formatDatas, $pagerID);
        $suhosinInfo  = $this->checkSuhosinInfo($datas->datas);
        $importFields = !empty($_SESSION[$module . 'TemplateFields']) ? $_SESSION[$module . 'TemplateFields'] : $this->config->$module->templateFields;

        $datas->requiredFields = $this->config->$module->create->requiredFields;
        $datas->allPager       = isset($datas->allPager) ? $datas->allPager : 1;
        $datas->pagerID        = $pagerID;
        $datas->isEndPage      = $pagerID >= $datas->allPager;
        $datas->maxImport      = $this->transfer->maxImport;
        $datas->dataInsert     = $insert;
        $datas->fields         = $this->transfer->initFieldList($module, $importFields, false);
        $datas->suhosinInfo    = $suhosinInfo;
        $datas->module         = $module;

        return $datas;
    }

    /**
     * 构建Html表单。
     * Build NextList.
     *
     * @param  array  $list
     * @param  int    $lastID
     * @param  string $fields
     * @param  int    $pagerID
     * @param  string $module
     * @access public
     * @return string
     */
    protected function buildNextList(array $list = array(), int $lastID = 0, string $fields = '', int $pagerID = 1, string $module = ''): string
    {
        $html  = '';
        $key   = key($list);
        $addID = 1;
        if($module == 'task') $members = $this->loadModel('user')->getTeamMemberPairs($this->session->taskTransferParams['executionID'], 'execution');

        $showImportCount = $this->config->transfer->lazyLoading ? $this->config->transfer->showImportCount : $this->maxImport;
        $lastRow         = $lastID + $key + $showImportCount;

        foreach($list as $row => $object)
        {
            if($row <= $lastID) continue;
            if($row > $lastRow) break;

            $tmpList[$row] = $object;
            $trClass = '';
            if($row == $lastRow) $trClass = 'showmore';
            $html .= "<tr class='text-top $trClass' data-id=$row>";
            $html .= '<td>';

            if(!empty($object->id))
            {
                $html .= $object->id . html::hidden("id[$row]", $object->id);
            }
            else
            {
                $sub = " <sub style='vertical-align:sub;color:gray'>{$this->lang->transfer->new}</sub>";
                if($module == 'task') $sub = (strpos($object->name, '>') === 0) ? " <sub style='vertical-align:sub;color:red'>{$this->lang->task->children}</sub>" : $sub;
                $addID ++;
                $html .= $addID . $sub;
            }
            $html .= "</td>";

            foreach($fields as $field => $value)
            {
                $control  = $value['control'];
                $values   = $value['values'];
                $name     = "{$field}[$row]";
                $selected = isset($object->$field) ? $object->$field : '';
                if($module and $control == 'hidden' and isset($this->session->{$module.'TransferParams'}[$field. 'ID'])) $selected = $this->session->{$module . 'TransferParams'}[$field. 'ID'];

                $options = array();
                if($control == 'select')
                {
                    if(!empty($values[$selected])) $options = array($selected => $values[$selected]);
                    if(empty($options) and is_array($values)) $options = array_slice($values, 0, 1, true);
                    if(!isset($options['']) and !in_array($field, $this->config->transfer->requiredFields)) $options[''] = '';
                }

                if($control == 'select')       $html .= '<td>' . html::select("$name", $options, $selected, "class='form-control picker-select nopicker' data-field='{$field}' data-index='{$row}'") . '</td>';

                elseif($control == 'multiple') $html .= '<td>' . html::select($name . "[]", $values, $selected, "multiple class='form-control picker-select nopicker' data-field='{$field}' data-index='{$row}'") . '</td>';

                elseif($control == 'date')     $html .= '<td>' . html::input("$name", $selected, "class='form-control form-date' autocomplete='off'") . '</td>';

                elseif($control == 'datetime') $html .= '<td>' . html::input("$name", $selected, "class='form-control form-datetime' autocomplete='off'") . '</td>';

                elseif($control == 'hidden')   $html .= html::hidden("$name", $selected);

                elseif($control == 'textarea')
                {
                    if($module == 'bug' and $field == 'steps') $selected = str_replace("\n\n\n\n\n\n", '', $selected);
                    $html .= '<td>' . html::textarea("$name", $selected, "class='form-control' cols='50' rows='1'") . '</td>';
                }
                elseif($field == 'stepDesc' or $field == 'stepExpect' or $field == 'precondition')
                {
                    $stepDesc = $this->process4Testcase($field, $tmpList, $row);
                    if($stepDesc) $html .= '<td>' . $stepDesc . '</td>';
                }
                elseif($field == 'estimate')
                {
                    $html .= '<td>';
                    if(!empty($object->estimate) and is_array($object->estimate))
                    {
                        $html .= "<table class='table-borderless'>";
                        foreach($object->estimate as $account => $estimate)
                        {
                            $html .= '<tr>';
                            $html .= '<td class="c-team">' . html::select("team[$row][]", $members, $account, "class='form-control chosen'") . '</td>';
                            $html .= '<td class="c-estimate-1">' . html::input("estimate[$row][]", $estimate, "class='form-control' autocomplete='off'")  . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= "</table>";
                    }
                    else
                    {
                        $html .= html::input("estimate[$row]", !empty($object->estimate) ? $object->estimate : '', "class='form-control'");
                    }
                    $html .= '</td>';
                }
                elseif(strpos($this->transferConfig->textareaFields, $field) !== false) $html .= '<td>' . html::textarea("$name", $selected, "class='form-control' style='overflow:hidden;'") . '</td>';

                else $html .= '<td>' . html::input("$name", $selected, "class='form-control autocomplete='off'") . '</td>';
            }

            if(in_array($module, $this->config->transfer->actionModule)) $html .= '<td><a onclick="delItem(this)"><i class="icon-close"></i></a></td>';
            $html .= '</tr>' . "\n";
        }

        return $html;
    }

    /**
     * 处理用例步骤描述和预期结果。
     * Process stepExpect、stepDesc and precondition for testcase.
     *
     * @param  int    $field
     * @param  int    $datas
     * @param  int    $key
     * @access public
     * @return string
     */
    protected function process4Testcase(string $field, array $datas, int $key = 0): string
    {
        $stepData = $this->loadModel('testcase')->processDatas($datas);

        $html = '';
        if($field == 'precondition' and isset($datas[$key])) return html::textarea("precondition[$key]", htmlSpecialString($datas[$key]->precondition), "class='form-control' style='overflow:hidden'");
        if($field == 'stepExpect') return $html;
        if(isset($stepData[$key]['desc']))
        {
            $html = "<table class='w-p100 bd-0'>";
            $hasStep = false;
            foreach($stepData[$key]['desc'] as $id => $desc)
            {
                if(empty($desc['content'])) continue;
                $hasStep = true;

                $html .= "<tr class='step'>";
                $html .= '<td>' . $id . html::hidden("stepType[$key][$id]", $desc['type']) . '</td>';
                $html .= '<td>' . html::textarea("desc[$key][$id]", htmlSpecialString($desc['content']), "class='form-control'") . '</td>';
                if($desc['type'] != 'group') $html .= '<td>' . html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]['content']) ? htmlSpecialString($stepData[$key]['expect'][$id]['content']) : '', "class='form-control'") . '</td>';
                $html .= "</tr>";
            }

            if(!$hasStep)
            {
                $html .= "<tr class='step'>";
                $html .= "<td>1" . html::hidden("stepType[$key][1]", 'step') . " </td>";
                $html .= "<td>" . html::textarea("desc[$key][1]", '', "class='form-control'") . "</td>";
                $html .= "<td>" . html::textarea("expect[$key][1]", '', "class='form-control'") . "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }
        else
        {
            $html  = "<table class='w-p100 bd-0'>";
            $html .= "<tr class='step'>";
            $html .= "<td>1" . html::hidden("stepType[$key][1]", 'step') . " </td>";
            $html .= "<td>" . html::textarea("desc[$key][1]", '', "class='form-control'") . "</td>";
            $html .= "<td>" . html::textarea("expect[$key][1]", '', "class='form-control'") . "</td>";
            $html .= "</tr>";
            $html .= "</table>";
        }

        return $html;
    }

    /**
     * 格式化导入导出标准数据格式。
     * Format standard data format.
     *
     * @param  string $module
     * @param  string $filter
     * @access public
     * @return array
     */
    public function format(string $module = '', string $filter = '')
    {
        /* Bulid import paris (field => name). */
        $fields  = $this->transferZen->getImportFields($module);

        /* 检查临时文件是否存在并返回完成路径。 */
        /* Check tmpfile. */
        $tmpFile = $this->transferZen->checkTmpFile();

        /* 如果临时文件存在,则读取临时文件i，否则就创建临时文件。 */
        /* If tmp file exists, read tmp file, otherwise create tmp file. */
        if(!$tmpFile)
        {
            $rows       = $this->getRowsFromExcel();  // 从Excel中获取数据
            $moduleData = $this->transferTao->processRows4Fields($rows, $fields);  // 将读取到的数据格式化成关联数组
            $moduleData = $this->transferTao->parseExcelDropdownValues($module, $moduleData, $filter, $fields); // 解析Excel中下拉字段的数据，转换成具体value

            $this->transferZen->createTmpFile($moduleData); //将格式化后的数据写入临时文件中
        }
        else
        {
            $moduleData = unserialize(file_get_contents($file));
        }

        if(isset($fields['id'])) unset($fields['id']);
        $this->session->set($module . 'TemplateFields',  implode(',', array_keys($fields))); // 将模板字段到SESSION中

        return $moduleData;
    }

    /**
     * 初始化工作流字段。
     * Init workflow fieldList.
     *
     * @param  string $module
     * @param  array  $fieldList
     * @access public
     * @return void
     */
    public function initWorkflowFieldList(string $module, array $fieldList)
    {
        $this->loadModel($module);
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
}
