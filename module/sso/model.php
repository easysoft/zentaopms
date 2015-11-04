<?php
class ssoModel extends model
{
    /**
     * Check Key.
     * 
     * @access public
     * @return bool
     */
    public function checkKey()
    {
        if(!isset($this->config->sso->turnon) or !$this->config->sso->turnon) return false;
        if(empty($this->config->sso->key)) return false;
        return $this->get->hash == $this->config->sso->key;
    }
}
