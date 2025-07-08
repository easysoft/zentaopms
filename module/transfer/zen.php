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
     * @return void
     */
    protected function initTemplateFields(string $module, string $fields = ''): void
    {
        /* 构造该模块的导出模板字段数据。*/
        /* Construct export template field data. */
        $fieldList = $this->transfer->initFieldList($module, $fields);

        /* 获取下拉字段的数据列表。*/
        /* Get dropdown field data list. */
        $list = $this->transfer->setListValue($module, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        $fields = $this->transfer->generateExportDatas($fieldList);

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
     * 构建Html表单。
     * Build NextList.
     *
     * @param  array  $list
     * @param  int    $lastID
     * @param  string $fields
     * @param  int    $pagerID
     * @param  string $module
     * @access protected
     * @return string
     */
    protected function buildNextList(array $list = array(), int $lastID = 0, string $fields = '', int $pagerID = 1, string $module = ''): string
    {
        $html  = '';
        $key   = key($list);
        $addID = 1;

        /* 是否开启懒加载。*/
        /* Whether to enable lazy loading. */
        $showImportCount = $this->config->transfer->lazyLoading ? $this->config->transfer->showImportCount : $this->maxImport;
        $lastRow         = $lastID + $key + $showImportCount;

        foreach($list as $row => $object)
        {
            if($row <= $lastID) continue;
            if($row > $lastRow) break;

            $tmpList[$row] = $object;
            $trClass = '';
            if($row == $lastRow) $trClass = 'showmore';

            $html .= $this->printRow($module, $row, $fields, $object, $trClass, $addID);
        }

        return $html;
    }

    /**
     * 构建Html表单。
     * Build Html Form.
     *
     * @param  string  $module
     * @param  int     $row
     * @param  array   $fields
     * @param  object  $object
     * @param  string  $trClass
     * @param  int     $addID
     * @access private
     * @return void
     */
    private function printRow(string $module, int $row, array $fields, object $object, string $trClass, int $addID)
    {
        $html .= "<tr class='text-top $trClass' data-id=$row> <td>";

        /* 是否显示ID。*/
        /* Whether to display ID. */
        if(!empty($object->id))
        {
            $html .= $object->id . html::hidden("id[$row]", $object->id);
        }
        else
        {
            /* 是否新建。*/
            /* Whether new. */
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
            $options  = array();
            $html    .= $this->printCell($module, $field, $control, $name, $selected, $values, $row);
        }

        /* 是否显示删除按钮。*/
        /* Whether to display delete button. */
        if(in_array($module, $this->config->transfer->actionModule)) $html .= '<td><a onclick="delItem(this)"><i class="icon-close"></i></a></td>';
        $html .= '</tr>' . "\n";
        return $html;
    }

    /**
     * 处理表单。
     * Print cell.
     *
     * @param  string  $module
     * @param  string  $field
     * @param  string  $control
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $values
     * @param  int     $row
     * @access private
     * @return string
     */
    private function printCell(string $module = '', string $field = '', string $control = '', string $name = '', string $selected = '', array $values = array(), int $row = 0)
    {
        $html = '';
        if($module and $control == 'hidden' and isset($this->session->{$module.'TransferParams'}[$field. 'ID'])) $selected = $this->session->{$module . 'TransferParams'}[$field. 'ID'];
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
        elseif($field == 'stepDesc' or $field == 'stepExpect' or $field == 'precondition') // 用例步骤和预期
        {
            $stepDesc = $this->process4Testcase($field, $tmpList, $row);
            if($stepDesc) $html .= '<td>' . $stepDesc . '</td>';
        }
        elseif($field == 'estimate') $html .= $this->processEstimate($row, $object); // 多人任务预计工时
        elseif(strpos($this->transferConfig->textareaFields, $field) !== false) $html .= '<td>' . html::textarea("$name", $selected, "class='form-control' style='overflow:hidden;'") . '</td>';
        else $html .= '<td>' . html::input("$name", $selected, "class='form-control autocomplete='off'") . '</td>';
        return $html;
    }

    /**
     * 处理预估工时字段。
     * Process estimate.
     *
     * @param  int     $row
     * @param  object  $object
     * @access private
     * @return string
     */
    private function processEstimate(int $row = 0, ?object $object = null): string
    {
        $members = $this->loadModel('user')->getTeamMemberPairs($this->session->taskTransferParams['executionID'], 'execution');
        $html    = '<td>';
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
        return $html;
    }

    /**
     * 处理用例步骤描述和预期结果。
     * Process stepExpect、stepDesc and precondition for testcase.
     *
     * @param  string    $field
     * @param  array     $datas
     * @param  int       $key
     * @access protected
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
}
