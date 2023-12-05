<?php
declare(strict_types=1);
/**
 * The zen file of misc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
class miscZen extends misc
{
    /**
     * 打印 hello world。
     * print hello world.
     *
     * @access public
     * @return string
     */
    public function hello(): string
    {
        return 'hello world from hello()<br />';
    }

    /**
     * 获取新增年度总结功能的通知。
     * Get remind.
     *
     * @access public
     * @return string
     */
    public function getRemind(): string
    {
        $remind = '';
        if(!empty($this->config->global->showAnnual) and empty($this->config->global->annualShowed))
        {
            $remind  = '<h4>' . $this->lang->misc->showAnnual . '</h4>';
            $remind .= '<p>' . sprintf($this->lang->misc->annualDesc, helper::createLink('report', 'annualData')) . '</p>';
            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.annualShowed", 1);
        }
        return $remind;
    }
}
