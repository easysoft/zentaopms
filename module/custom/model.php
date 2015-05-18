<?php
/**
 * The model file of custom module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class customModel extends model
{
    /**
     * Get all custom lang.
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        $allCustomLang = $this->dao->select('*')->from(TABLE_LANG)->orderBy('lang,id')->fetchAll('id');

        $currentLang   = $this->app->getClientLang();
        $processedLang = array();
        foreach($allCustomLang as $id => $customLang)
        {
            if($customLang->lang != $currentLang and $customLang->lang != 'all') continue;
            $processedLang[$customLang->module][$customLang->section][$customLang->key] = $customLang->value;
        }

        return $processedLang;
    }

    /**
     * Set value of an item. 
     * 
     * @param  string      $path     zh-cn.story.soucreList.customer.1
     * @param  string      $value 
     * @access public
     * @return void
     */
    public function setItem($path, $value = '')
    {
        $level    = substr_count($path, '.');
        $section  = '';
        $system   = 1;

        if($level <= 1) return false;
        if($level == 2) list($lang, $module, $key) = explode('.', $path);
        if($level == 3) list($lang, $module, $section, $key) = explode('.', $path);
        if($level == 4) list($lang, $module, $section, $key, $system) = explode('.', $path);

        $item = new stdclass();
        $item->lang    = $lang;
        $item->module  = $module;
        $item->section = $section;
        $item->key     = $key;
        $item->value   = $value;
        $item->system  = $system;

        $this->dao->replace(TABLE_LANG)->data($item)->exec();
    }

    /**
     * Get some items 
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function getItems($paramString)
    {
        return $this->createDAO($this->parseItemParam($paramString), 'select')->orderBy('lang,id')->fetchAll('key');
    }

    /**
     * Delete items.
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function deleteItems($paramString)
    {
        $this->createDAO($this->parseItemParam($paramString), 'delete')->exec();
    }

    /**
     * Parse the param string for select or delete items.
     * 
     * @param  string    $paramString     lang=xxx&module=story&section=sourceList&key=customer and so on.
     * @access public
     * @return array
     */
    public function parseItemParam($paramString)
    {
        /* Parse the param string into array. */
        parse_str($paramString, $params); 

        /* Init fields not set in the param string. */
        $fields = 'lang,module,section,key';
        $fields = explode(',', $fields);
        foreach($fields as $field) if(!isset($params[$field])) $params[$field] = '';

        return $params;
    }

    /**
     * Create a DAO object to select or delete one or more records.
     * 
     * @param  array  $params     the params parsed by parseItemParam() method.
     * @param  string $method     select|delete.
     * @access public
     * @return object
     */
    public function createDAO($params, $method = 'select')
    {
        return $this->dao->$method('*')->from(TABLE_LANG)->where('1 = 1')
            ->beginIF($params['lang'])->andWhere('lang')->in($params['lang'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi();
    }

    /**
     * Custom by config.
     * 
     * @access public
     * @return void
     */
    public function customByConfig()
    {
        if(!isset($this->config->custom->productproject)) return true;

        $productproject = $this->config->custom->productproject;
        if(strpos($productproject, '_') === false) return true;

        list($product, $project) = explode('_', $productproject);
        if($product == 0 and $project == 0) return true;
        $change['before'] = $this->lang->custom->productproject->project[0];
        $change['after']  = $this->lang->custom->productproject->project[$project];
        $changes[] = $change;
        if($product != 0)
        {
            $change['before'] = $this->lang->custom->productproject->product[0];
            $change['after']  = $this->lang->custom->productproject->product[$product];
            $changes[] = $change;
        }

        $this->changeAllLang($this->lang, $changes);
    }

    /**
     * Change all lang.
     * 
     * @param  object $lang 
     * @param  array  $changes 
     * @access public
     * @return object
     */
    public function changeAllLang($lang, $changes = array())
    {
        if(empty($changes)) return true;
        static $changed = array();
        $type = is_array($lang) ? 'array' : 'object';
        foreach($lang as $key => $value)
        {
            if($lang == $this->lang->custom) continue;
            if(is_object($value) or is_array($value))
            {
                if($type == 'array')  $lang[$key] = $this->changeAllLang($value, $changes);
                if($type == 'object') $lang->$key = $this->changeAllLang($value, $changes);
            }
            if(is_string($value))
            {
                if(isset($changed[$value])) continue;
                if(strpos($value, $this->lang->custom->productproject->locked) !== false) continue;
                foreach($changes as $change)
                {
                    if(stripos($value, $change['before']) !== false)
                    {
                        $value = str_ireplace($change['before'], $change['after'], $value);
                        if($type == 'array') $lang[$key] = $value;
                        if($type == 'object')$lang->$key = $value;
                        $changed[$value] = true;
                    }
                }
            }
        }
        return $lang;
    }
}
