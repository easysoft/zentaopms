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
        return = $this->dao->select('t2.*')->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
            ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.field=t2.field && t1.module=t2.module')
            ->where('t1.module')->eq($model)
            ->andWhere('t1.action')->eq('exporttemplate')
            ->andWhere('t2.buildin')->eq(0)
            ->orderBy('t1.order')
            ->fetchAll();
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
     * @param string $module
     * @param string $fields
     * @access protected
     * @return array
     */
    protected function formatFields(string $module, string $fields = array()): array
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
}
