<?php
/**
 * The config entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class configEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @param  int    $name language,version,timezone etc.
     * @access public
     * @return void
     */
    public function get($name)
    {
        $config = array('name' => $name);

        switch($name)
        {
            case 'language':
                $config['value'] = $this->config->default->lang;
                break;
            case 'version':
                $config['value'] = $this->config->version;
                break;
            case 'charset':
                $config['value'] = $this->config->charset;
                break;
            case 'timezone':
                $config['value'] = $this->config->timezone;
                break;
            case 'systemMode':
                $config['value'] = $this->config->systemMode;
                break;
            default:
                $this->sendError(400, 'No configuration.');
                return;
        }

        $this->send(200, $config);
    }
}
