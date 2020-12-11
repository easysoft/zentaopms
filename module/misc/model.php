<?php
/**
 * The model file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class miscModel extends model
{
    public function hello()
    {
        return 'hello world from hello()<br />';
    }

    /**
     * Get table and status.
     *
     * @param  string $type
     *
     * @access public
     * @return array|false
     */
    public function getTableAndStatus($type = 'check')
    {
        if($type != 'check' and $type != 'repair') return false;
        $tables = array();
        $stmt   = $this->dao->query("show full tables");
        while($table = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $tableName = $table["Tables_in_{$this->config->db->name}"];
            $tableType = strtolower($table['Table_type']);
            if($tableType == 'base table')
            {
                $tableStatus        = $this->dao->query("$type table $tableName")->fetch();
                $tables[$tableName] = strtolower($tableStatus->Msg_text);
            }
        }
        return $tables;
    }

    /**
     * Get remind.
     * 
     * @access public
     * @return string
     */
    public function getRemind()
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

    /**
     * Check one click package.
     * 
     * @access public
     * @return array
     */
    public function checkOneClickPackage()
    {
        $weakSites = array();
        if(strpos('|/zentao/|/pro/|/biz/|', "|{$this->config->webRoot}|") !== false)
        {
            $databases = array('zentao' => 'zentao', 'zentaopro' => 'zentaopro', 'zentaobiz' => 'zentaobiz', 'zentaoep' => 'zentaoep');
            if($this->config->webRoot == '/zentao/') unset($databases['zentao']);
            if($this->config->webRoot == '/pro/') unset($databases['zentaopro']);
            if($this->config->webRoot == '/biz/')
            {
                unset($databases['zentaobiz']);
                unset($databases['zentaoep']);
            }

            $basePath = dirname($this->app->getBasePath());
            foreach($databases as $database)
            {
                $zentaoDirName = $database;
                if(!is_dir($basePath . '/' . $zentaoDirName))
                {
                    if($zentaoDirName == 'zentaobiz' and !is_dir($basePath . '/zentaoep')) continue;
                    if($zentaoDirName == 'zentaoep' and !is_dir($basePath . '/zentaobiz')) continue;
                    if($zentaoDirName == 'zentao' or $zentaoDirName == 'zentaopro') continue;

                    if($zentaoDirName == 'zentaobiz') $zentaoDirName = 'zentaoep';
                    if($zentaoDirName == 'zentaoep')  $zentaoDirName = 'zentaobiz';
                }

                try
                {
                    $webRoot = "/{$database}/";
                    if($database == 'zentao')    $webRoot = '/zentao/';
                    if($database == 'zentaopro') $webRoot = '/pro/';
                    if($database == 'zentaobiz') $webRoot = '/biz/';
                    if($database == 'zentaoep')  $webRoot = '/biz/';

                    $user = $this->dbh->query("select * from {$database}.`zt_user` where account = 'admin' and password='" . md5('123456') . "'")->fetch();
                    if($user)
                    {
                        $site = array();
                        $site['path'] = basename($basePath) . '/' . $zentaoDirName;
                        $site['database'] = $database;
                        $weakSites[$database] = $site;
                    }
                }
                catch(Exception $e){}
            }
        }

        return $weakSites;
    }
}
