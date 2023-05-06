<?php
declare(strict_types=1);
/**
 * The zen file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lanzongjun <lanzongjun@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class programplanZen extends programplan
{
    /**
     * 处理请求数据
     * Processing request data.
     *
     * @param  object $formData
     * @param  string $owner
     * @param  string $module
     * @access protected
     * @return object
     */
    protected function beforeAjaxCustom(object $formData, string $owner, string $module): object
    {
        $data = $formData->get();
        
        $zooming     = empty($data->zooming)     ? '' : $data->zooming;
        $stageCustom = empty($data->stageCustom) ? '' : implode(',', $data->stageCustom);
        $ganttFields = empty($data->ganttFields) ? '' : implode(',', $data->ganttFields);

        $this->setting->setItem("$owner.$module.browse.stageCustom", $stageCustom);
        $this->setting->setItem("$owner.$module.ganttCustom.ganttFields", $ganttFields);
        $this->setting->setItem("$owner.$module.ganttCustom.zooming", $zooming);
        
        return $data;
    }

    /**
     * 生成自定义设置视图。
     * Build custom setting view form data.
     *
     * @param  string $owner
     * @param  string $module
     * @access protected
     * @return void
     */
    protected function buildAjaxCustomView(string $owner, string $module, array $customFields)
    {
        $stageCustom = $this->setting->getItem("owner=$owner&module=$module&section=browse&key=stageCustom");
        $ganttFields = $this->setting->getItem("owner=$owner&module=$module&section=ganttCustom&key=ganttFields");
        $zooming     = $this->setting->getItem("owner=$owner&module=$module&section=ganttCustom&key=zooming");
        
        $this->view->zooming      = $zooming;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->programplan->ganttCustom->ganttFields;
        $this->view->ganttFields  = $ganttFields;
        $this->view->stageCustom  = $stageCustom;

        $this->display();
    }
}
