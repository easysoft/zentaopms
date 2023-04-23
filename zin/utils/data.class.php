<?php
/**
 * The data class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\utils;

require_once 'dataset.class.php';

/**
 * Manage data for html element and widgets
 */
class data extends dataset
{
    public function __constructor()
    {
        $list = func_get_args();

        foreach($list as $data) $this->set($data);
    }

    /**
     * Method for sub class to modify value on setting it
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return dataset
     */
    protected function setVal($prop, $value, $removeEmpty = false)
    {
        if($prop[0] === '$') $prop = substr($prop, 1);

        if($value === NULL || ($removeEmpty && empty($value))) return $this->remove($prop);

        $names = explode('.', $prop);
        $lastName = array_pop($names);
        $data = &$this->data;
        if(!empty($names))
        {
            foreach($names as $name)
            {
                if(!is_array($data))
                {
                    return $this;
                }

                if(!isset($data[$name])) $data[$name] = array();
                $data = &$data[$name];
            }
        }

        if($value === NULL || ($removeEmpty && empty($value)))
        {
            if(isset($data[$lastName])) unset($data[$lastName]);
            return $this;
        }

        $data[$lastName] = $value;
        return $this;
    }

    protected function getVal($prop)
    {
        if($prop[0] === '$') $prop = substr($prop, 1);

        $names = explode('.', $prop);
        $data = &$this->data;
        foreach($names as $name)
        {
            if(!is_array($data)) return NULL;
            $data = &$data[$name];
        }
        return $data;
    }
}
