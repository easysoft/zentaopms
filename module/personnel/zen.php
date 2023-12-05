<?php
declare(strict_types=1);
/**
 * The zen file of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     personnel
 * @link        https://www.zentao.net
 */
class personnelZen extends personnel
{
    /**
     * 设置选择对象提示信息
     * Set select object tips.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $module
     * @access public
     * @return void
     */
    protected function setSelectObjectTips(int $objectID, string $objectType, string $module): void
    {
        $this->app->loadLang('execution');
        $objectName = $this->lang->projectCommon . $this->lang->execution->or . $this->lang->execution->common;;
        if($objectType == 'program') $objectName = $this->lang->program->common;
        if($objectType == 'product') $objectName = $this->lang->productCommon;
        if($objectType == 'project') $objectName = $this->lang->projectCommon;
        $this->lang->personnel->selectObjectTips = sprintf($this->lang->personnel->selectObjectTips, $objectName);

        if($objectType == 'sprint' && $module == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            $this->lang->personnel->selectObjectTips = !empty($execution) && $execution->type == 'kanban' ? str_replace($this->lang->execution->common, $this->lang->execution->kanban, $this->lang->personnel->selectObjectTips) : $this->lang->personnel->selectObjectTips;
        }

        $this->view->objectName = $objectName;
    }
}

