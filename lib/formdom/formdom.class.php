<?php
/**
 * The formdom class file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zhu Jinyong<zhujinyong@easycorp.ltd>
 * @package     form
 * @link        https://www.zentao.net
 */

/**
 * 解析form dom为数组数据。
 * Parse Form dom to array data.
 */
class formdom
{
    /**
     * Errors
     *
     * @var array
     * @access private
     */
    private $errors = array();

    /**
     * Options
     *
     * @var array
     * @access private
     */
    private $options = array(
        'include_disabled'         => false,
        'include_empty_checkboxes' => false,
        'default_radio_value'      => null,
        'debug'                    => false
    );

    /**
     * Constructor
     *
     * @param  array  $options
     * @access public
     * @return void
     */
    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Parse html dom
     *
     * @param  string $html
     * @param  string $formSelector
     * @access public
     * @return array
     */
    public function parse($html, $formSelector = null)
    {
        $this->errors = array();

        if(empty($html))
        {
            $this->errors[] = "HTML is empty";
            return array();
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        /* 查找表单 */
        $form = null;
        if($formSelector)
        {
            // 通过 id 查找
            $forms = $xpath->query("//form[@id='$formSelector']");
            if($forms->length > 0)
            {
                $form = $forms->item(0);
            }
            else
            {
                /* 通过 name 查找 */
                $forms = $xpath->query("//form[@name='$formSelector']");
                if($forms->length > 0)
                {
                    $form = $forms->item(0);
                }
            }
        } else {
            /* 获取第一个表单 */
            $forms = $xpath->query("//form");
            if ($forms->length > 0) {
                $form = $forms->item(0);
            }
        }

        if(!$form)
        {
            $this->errors[] = "Form not found";
            return array();
        }

        /* 提取表单数据 */
        $result = $this->extractFormData($xpath, $form);
        return $result;
    }

    /**
     * Extract form data
     *
     * @param  object $xpath
     * @param  object $form
     * @access private
     * @return array
     */
    private function extractFormData($xpath, $form)
    {
        $data = [];

        /* 处理所有表单元素 */
        $this->processInputs($xpath, $form, $data);
        $this->processSelects($xpath, $form, $data);
        $this->processTextareas($xpath, $form, $data);
        $this->processZinPickers($xpath, $form, $data);
        $this->processZinEditors($xpath, $form, $data);

        return $data;
    }

    /**
     * Process inputs
     *
     * @param  object $xpath
     * @param  object $form
     * @param  array  $data
     * @access private
     * @return void
     */
    private function processInputs($xpath, $form, &$data)
    {
        // 查找所有 input 元素（包括嵌套的）
        $inputs = $xpath->query(".//input[@name]", $form);

        $radioGroups = array();

        foreach($inputs as $input)
        {
            $name     = $input->getAttribute('name');
            $type     = strtolower($input->getAttribute('type') ?: 'text');
            $value    = $input->getAttribute('value');
            $disabled = $input->hasAttribute('disabled');

            /* 跳过禁用的元素（除非配置允许） */
            if(!$this->options['include_disabled'] && $disabled) continue;

            switch($type)
            {
                case 'checkbox':
                    if($input->hasAttribute('checked'))
                    {
                        $this->addValue($data, $name, $value ?: 'on');
                    }
                    elseif($this->options['include_empty_checkboxes'])
                    {
                        $this->addValue($data, $name, '');
                    }
                    break;
                case 'radio':
                    $baseName = $this->getBaseName($name);
                    if(!isset($radioGroups[$baseName]))
                    {
                        $radioGroups[$baseName] = array(
                            'selected'     => null,
                            'hasSelection' => false
                        );
                    }
                    if($input->hasAttribute('checked'))
                    {
                        $radioGroups[$baseName]['selected']     = $value;
                        $radioGroups[$baseName]['hasSelection'] = true;
                    }
                    break;
                case 'file':
                    $this->addValue($data, $name, '');
                    break;
                case 'submit':
                case 'button':
                case 'image':
                case 'reset':
                    break;

                default:
                    /* 包括 hidden, text, password, email 等所有其他类型 */
                    $this->addValue($data, $name, $value ?: '');
                    break;
            }
        }

        /* 处理 radio 组 */
        foreach ($radioGroups as $name => $group) {
            if ($group['hasSelection']) {
                $data[$name] = $group['selected'];
            } elseif ($this->options['default_radio_value'] !== null) {
                $data[$name] = $this->options['default_radio_value'];
            }
        }
    }

    /**
     * Process selects
     *
     * @param  object $xpath
     * @param  object $form
     * @param  array  $data
     * @access private
     * @return void
     */
    private function processSelects($xpath, $form, &$data)
    {
        $selects = $xpath->query(".//select[@name]", $form);

        foreach($selects as $select)
        {
            if(!$this->options['include_disabled'] && $select->hasAttribute('disabled'))  continue;

            $name = $select->getAttribute('name');
            $multiple = $select->hasAttribute('multiple');

            $options = $xpath->query(".//option", $select);

            if($multiple)
            {
                $selectedValues = [];
                foreach($options as $option)
                {
                    if($option->hasAttribute('selected'))
                    {
                        $value = $option->hasAttribute('value') ? $option->getAttribute('value') : trim($option->textContent);
                        $selectedValues[] = $value;
                    }
                }

                /* 对于多选，即使没有选中项，也要处理默认值 */
                foreach($options as $option)
                {
                    $value = $option->hasAttribute('value') ? $option->getAttribute('value') : trim($option->textContent);
                    if(!empty($value))
                    {
                        $selectedValues[] = $value;
                        break; // 只取第一个有值的选项作为默认
                    }
                }

                if(!empty($selectedValues))
                {
                    foreach($selectedValues as $val) $this->addValue($data, $name, $val);
                }
            }
            else
            {
                $selectedValue = null;
                $firstValue = null;

                foreach($options as $index => $option)
                {
                    $value = $option->hasAttribute('value')
                        ? $option->getAttribute('value')
                        : trim($option->textContent);

                    if($index === 0) $firstValue = $value;

                    if($option->hasAttribute('selected'))
                    {
                        $selectedValue = $value;
                        break;
                    }
                }

                $finalValue = $selectedValue !== null ? $selectedValue : $firstValue;
                if($finalValue !== null) $this->addValue($data, $name, $finalValue);
            }
        }
    }

    /**
     * Process textarea
     *
     * @param  object $xpath
     * @param  object $form
     * @param  array  $data
     * @access private
     * @return void
     */
    private function processTextareas($xpath, $form, &$data)
    {
        $textareas = $xpath->query(".//textarea[@name]", $form);

        foreach($textareas as $textarea)
        {
            if(!$this->options['include_disabled'] && $textarea->hasAttribute('disabled'))  continue;

            $name = $textarea->getAttribute('name');
            $value = $textarea->textContent;

            $this->addValue($data, $name, $value);
        }
    }

    /**
     * Process zin pickers and date pickers.
     *
     * @param  object $xpath
     * @param  object $form
     * @param  array  $data
     * @access private
     * @return void
     */
    private function processZinPickers($xpath, $form, &$data)
    {
        /* 查找所有包含 zui-create-picker 属性的元素（通常是 div.picker-box） */
        $pickers = $xpath->query(".//*[@zui-create]", $form);

        foreach ($pickers as $picker) {
            /* 1. 跳过禁用的picker（如果配置不允许） */
            $pickerDisabled = $picker->hasAttribute('disabled') || $picker->getAttribute('disabled') === 'true';
            if(!$this->options['include_disabled'] && $pickerDisabled) continue;

            /* 2. 获取 zui-create-picker 属性的JSON值 */
            if($picker->hasAttribute('zui-create-picker'))
            {
                $pickerConfig = $picker->getAttribute('zui-create-picker');
            }
            elseif($picker->hasAttribute('zui-create-datepicker'))
            {
                $pickerConfig = $picker->getAttribute('zui-create-datepicker');
            }

            if(empty($pickerConfig)) continue;

            /* 3. 提取name（关键：用正则匹配name字段）*/
            $name = null;
            if(preg_match('/"name"\s*:\s*"([^"]+)"/i', $pickerConfig, $nameMatches))
            {
                $name = $nameMatches[1]; // 捕获引号中的name值
            }
            elseif(preg_match("/'name'\s*:\s*'([^']+)'/i", $pickerConfig, $nameMatches))
            {
                $name = $nameMatches[1]; // 兼容单引号
            }

            if(empty($name)) continue;

            /* 4. 提取defaultValue（核心：兼容JS函数和非JSON格式）*/
            $defaultValue = '';
            /* 正则规则：匹配 "defaultValue": 值 或 'defaultValue': 值 */
            /* 支持的值类型：字符串（单/双引号）、数字、true/false/null */
            $valuePattern = '/"defaultValue"\s*:\s*(?:"([^"]+)"|\'([^\']+)\'|(\d+\.?\d*)|(true|false|null))/i';
            if(preg_match($valuePattern, $pickerConfig, $valueMatches))
            {
                /* 匹配优先级：双引号字符串 > 单引号字符串 > 数字 > 布尔/null */
                $defaultValue = !empty($valueMatches[1]) ? $valueMatches[1]
                    : (!empty($valueMatches[2]) ? $valueMatches[2]
                    : (!empty($valueMatches[3]) ? $valueMatches[3]
                    : $valueMatches[4] ?? null));

                /* 转换类型（如"true"→true，"123"→123）*/
                if ($defaultValue === 'true') $defaultValue = true;
                elseif ($defaultValue === 'false') $defaultValue = false;
                elseif ($defaultValue === 'null') $defaultValue = null;
                elseif (is_numeric($defaultValue)) $defaultValue = (float)$defaultValue;
            }

            /* 5. 写入数据 */
            $this->addValue($data, $name, $defaultValue);
        }
    }

    /**
     * Process zin editors.
     *
     * @param  object $xpath
     * @param  object $form
     * @param  array  $data
     * @access private
     * @return void
     */
    private function processZinEditors($xpath, $form, &$data)
    {
        $editors = $xpath->query("//zen-editor", $form);

        foreach($editors as $editor)
        {
            $name  = $editor->getAttribute('name');
            $value = '';

            $contentNode = $xpath->query(".//article[@slot='content']", $editor)->item(0);
            if($contentNode)
            {
                /* 获取内部HTML内容 */
                $dom = $contentNode->ownerDocument;
                foreach($contentNode->childNodes as $child) $value .= $dom->saveHTML($child);
            }

            $this->addValue($data, $name, $value);
        }
    }

    /**
     * Add value to data.
     *
     * @param  array  $data
     * @param  string $name
     * @param  mixed  $value
     * @access private
     * @return void
     */
    private function addValue(&$data, $name, $value)
    {
        /* 使用 parse_str 来处理名称 */
        if($value === null) $value = '';
        parse_str($name . '=' . urlencode($value), $temp);

        /* 合并到数据中 */
        foreach($temp as $key => $val)
        {
            if(!isset($data[$key]))
            {
                $data[$key] = $val;
            }
            elseif(is_array($data[$key]) && is_array($val))
            {
                /* 递归合并数组 */
                foreach($val as $v) $data[$key][] = $v;
            }
            else
            {
                /* 覆盖（处理重复的非数组字段） */
                $data[$key] = $val;
            }
        }
    }

    /**
     * Get base name of field.
     *
     * @param  string $name
     * @access private
     * @return string
     */
    private function getBaseName($name)
    {
        if(preg_match('/^([^\[]+)/', $name, $matches)) return $matches[1];
        return $name;
    }

    /**
     * Get errors.
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
