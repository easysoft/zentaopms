<?php
declare(strict_types=1);
/**
 * The zen file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */
class hostZen extends host
{
    /**
     * 检查新增和编辑表单提交的合法性。
     * Check formData of create and edit.
     *
     * @param  object    $formData
     * @access protected
     * @return bool
     */
    protected function checkFormData(object $formData): bool
    {
        $intFields = explode(',', $this->config->host->create->intFields);
        foreach($intFields as $field)
        {
            if(!preg_match("/^-?\d+$/", (string)$formData->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->int, $this->lang->host->{$field});
            }
        }

        $ipFields = explode(',', $this->config->host->create->ipFields);
        foreach($ipFields as $field)
        {
            if(!preg_match('/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/', (string)$formData->{$field}))
            {
                dao::$errors[$field] = sprintf($this->lang->host->notice->ip, $this->lang->host->{$field});
            }
        }
        return !dao::isError();
    }
}
