<?php
/**
 * 禅道API的config资源类
 * 版本V1
 *
 * The config entry point of zentaopms
 * Version 1
 */
class configEntry extends entry 
{
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
        default:
            $this->sendError(400, 'No configuration.');
            return;
        }

        $this->send(200, $config);
    }
}
