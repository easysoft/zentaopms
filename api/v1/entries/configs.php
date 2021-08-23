<?php
/**
 * 禅道API的configs资源类
 * 版本V1
 *
 * The configs entry point of zentaopms
 * Version 1
 */
class configsEntry extends entry 
{
    public function get()
    {
        $configs = array();

        $configs[] = array('key' => 'language', 'value' => $this->config->default->lang);
        $configs[] = array('key' => 'version',  'value' => $this->config->version);
        $configs[] = array('key' => 'charset',  'value' => $this->config->charset);
        $configs[] = array('key' => 'timezone', 'value' => $this->config->timezone);

        $this->send(200, $configs);
    }
}
