<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class datatableModelTest extends baseTest
{
    protected $moduleName = 'datatable';
    protected $className  = 'model';

    /**
     * Test get field list.
     *
     * @param string $module
     * @param string $method
     * @access public
     * @return void
     */
    public function getFieldListTest($module, $method)
    {
        $module = zget($this->instance->config->datatable->moduleAlias, "$module-$method", $module);
        $result = $this->instance->getFieldList($module);
        return $result;
    }

    /**
     * Test get save setting field.
     *
     * @param string $module
     * @param string $method
     * @access public
     * @return void
     */
    public function getSettingTest($module, $method)
    {
        $this->instance->app->methodName = $method;
        $result = $this->instance->getSetting($module);
        return $result;
    }

    /**
     * Test print table head.
     *
     * @param array $data
     * @access public
     * @return mixed
     */
    public function printHeadTest($data)
    {
        // 设置应用环境以避免方法名为空的问题
        if(!$this->instance->app->getMethodName()) {
            $this->instance->app->methodName = 'browse';
        }

        ob_start();
        $this->instance->printHead($data['cols'], $data['orderBy'], $data['vars'], $data['checkBox']);
        $head = ob_get_clean();

        // 如果列不显示，直接返回输出内容的长度（应该为0）
        if(!$data['cols']->show) return strlen($head);

        // 对于显示的列，检查特定的输出内容
        $col = $data['cols'];
        if($col->id == 'actions') {
            // actions列应该包含actions文本
            return strpos($head, 'actions') !== false ? 1 : 0;
        } elseif(isset($col->sort) && $col->sort == 'no') {
            // 不可排序列应该直接显示标题，不含排序链接
            return (strpos($head, '<th') !== false && strpos($head, 'sort-') === false) ? 1 : 0;
        } elseif($col->id == 'id' && $data['checkBox']) {
            // id列且开启复选框应该包含复选框
            return strpos($head, 'checkbox-primary') !== false ? 1 : 0;
        } else {
            // 普通列应该包含th标签
            return strpos($head, '<th data-flex') !== false ? 1 : 0;
        }
    }

    /**
     * Test set fixed field width.
     *
     * @param object $setting
     * @access public
     * @return void
     */
    public function setFixedFieldWidthTest($setting)
    {
        $result = $this->instance->setFixedFieldWidth($setting);
        return $result;
    }

    /**
     * Test get old field list.
     *
     * @param string $module
     * @access public
     * @return array
     */
    public function getOldFieldListTest($module)
    {
        $result = $this->instance->getOldFieldList($module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getOldSetting method.
     *
     * @param string $module
     * @access public
     * @return mixed
     */
    public function getOldSettingTest($module)
    {
        $result = $this->instance->getOldSetting($module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sortOldCols method.
     *
     * @param object $a
     * @param object $b
     * @access public
     * @return mixed
     */
    public function sortOldColsTest($a, $b)
    {
        $result = datatableModel::sortOldCols($a, $b);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test appendWorkflowFields method.
     *
     * @param string $module
     * @param string $method
     * @access public
     * @return mixed
     */
    public function appendWorkflowFieldsTest($module, $method = '')
    {
        // 在开源版中，工作流模块不存在，直接返回空数组
        if($this->instance->config->edition == 'open')
        {
            return array();
        }

        $result = $this->instance->appendWorkflowFields($module, $method);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sortCols method.
     *
     * @param array $a
     * @param array $b
     * @access public
     * @return int
     */
    public function sortColsTest($a, $b)
    {
        $result = datatableModel::sortCols($a, $b);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
