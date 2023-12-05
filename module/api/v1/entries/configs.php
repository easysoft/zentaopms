<?php
/**
 * The configs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class configsEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $configs = array();

        $configs[] = array('key' => 'language',    'value' => $this->config->default->lang);
        $configs[] = array('key' => 'version',     'value' => $this->config->version);
        $configs[] = array('key' => 'charset',     'value' => $this->config->charset);
        $configs[] = array('key' => 'timezone',    'value' => $this->config->timezone);
        $configs[] = array('key' => 'systemMode',  'value' => $this->config->systemMode);
        $configs[] = array('key' => 'hourUnit',    'value' => $this->config->hourUnit);
        $configs[] = array('key' => 'CRProduct',   'value' => $this->config->CRProduct);
        $configs[] = array('key' => 'CRExecution', 'value' => $this->config->CRExecution);

        return $this->send(200, $configs);
    }
}
