<?php
/**
 * 此文件包括ZenTaoPHP框架的三个类：router, config, lang。
 * The router, config and lang class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code. In place of 
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * router类。
 * The router class.
 *
 * @package framework
 */
include dirname(__FILE__) . '/base/router.class.php';
class router extends baseRouter
{
    /**
     * 加载语言文件，返回全局$lang对象。
     * Load lang and return it as the global lang object.
     * 
     * @param   string $moduleName     the module name
     * @param   string $appName     the app name
     * @access  public
     * @return  bool|ojbect the lang object or false.
     */
    public function loadLang($moduleName, $appName = '')
    {
        global $lang;
        if(!is_object($lang)) $lang = new language();

        /* Set productCommon and projectCommon for flow. */
        if($moduleName == 'common')
        {
            $productProject = false;
            if($this->dbh and !empty($this->config->db->name)) $productProject = $this->dbh->query('SELECT value FROM' . TABLE_CONFIG . "WHERE `owner`='system' AND `module`='custom' AND `key`='productProject'")->fetch();

            $productCommon = $projectCommon = 0;
            if($productProject)
            {
                $productProject = $productProject->value;
                list($productCommon, $projectCommon) = explode('_', $productProject);
            }
            $lang->productCommon = isset($this->config->productCommonList[$this->clientLang][(int)$productCommon]) ? $this->config->productCommonList[$this->clientLang][(int)$productCommon] : $this->config->productCommonList['zh-cn'][0];
            $lang->projectCommon = isset($this->config->projectCommonList[$this->clientLang][(int)$projectCommon]) ? $this->config->projectCommonList[$this->clientLang][(int)$projectCommon] : $this->config->projectCommonList['zh-cn'][0];
        }

        return parent::loadLang($moduleName, $appName);
    }

    /**
     * Save error info.
     * 
     * @param  int    $level 
     * @param  string $message 
     * @param  string $file 
     * @param  int    $line 
     * @access public
     * @return void
     */
    public function saveError($level, $message, $file, $line)
    {
        $fatalLevel[E_ERROR]      = E_ERROR;
        $fatalLevel[E_PARSE]      = E_PARSE;
        $fatalLevel[E_CORE_ERROR] = E_CORE_ERROR;
        $fatalLevel[E_USER_ERROR] = E_USER_ERROR;
        if(isset($fatalLevel[$level])) $this->config->debug = true;
        parent::saveError($level, $message, $file, $line);
    }
}
