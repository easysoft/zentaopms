<?php
/**
 * The model file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class miscModel extends model
{
    /**
     * 获取禅道数据库的表名和状态。
     * Get table and status.
     *
     * @param  string      $type check|repair
     * @access public
     * @return array|false
     */
    public function getTableAndStatus(string $type = 'check'): array|false
    {
        if($type != 'check' && $type != 'repair') return false;

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
     * 获取新增年度总结功能的通知。
     * Get remind.
     *
     * @access public
     * @return string
     */
    public function getRemind(): string
    {
        $remind = '';
        if(!empty($this->config->global->showAnnual) && empty($this->config->global->annualShowed))
        {
            $remind  = '<h4>' . $this->lang->misc->showAnnual . '</h4>';
            $remind .= '<p>' . sprintf($this->lang->misc->annualDesc, helper::createLink('report', 'annualData')) . '</p>';
            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.annualShowed", 1);
        }
        return $remind;
    }

    /**
     * 获取插件到期通知。
     * Get the notification information about plugin expiration.
     *
     * @access public
     * @return string
     */
    public function getPluginRemind(): string
    {
        $plugins = $this->loadModel('extension')->getExpiringPlugins();
        $remind  = '';

        $today = helper::today();
        $showPluginRemind = (empty($this->config->global->showPluginRemind) || $this->config->global->showPluginRemind != $today) ? true : false;
        if(!empty($plugins) && $this->app->user->admin && $showPluginRemind)
        {
            $pluginButton = html::a(helper::createLink('extension', 'browse'), $this->lang->misc->view, '', "id='pluginButton' class='btn primary wide mr-2' data-app='admin'");
            $remind  = '<p>' . sprintf($this->lang->misc->expiredTipsForAdmin, count($plugins)) . '</p>';
            $remind .= '<p class="text-center mt-4">' . $pluginButton . '</p>';

            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.showPluginRemind", $today);
        }
        return $remind;
    }

    /**
     * 检查一键安装包的安全性。
     * Check one click package.
     *
     * @access public
     * @return array
     */
    public function checkOneClickPackage(): array
    {
        $weakSites = array();
        if(strpos('|/zentao/|/biz/|/max/|', "|{$this->config->webRoot}|") !== false)
        {
            $databases = array('zentao' => 'zentao', 'zentaobiz' => 'zentaobiz', 'zentaoep' => 'zentaoep', 'zentaomax' => 'zentaomax');
            $basePath  = dirname($this->app->getBasePath());
            foreach($databases as $database)
            {
                $zentaoDirName = $database;
                if(!is_dir($basePath . '/' . $zentaoDirName))
                {
                    if($zentaoDirName == 'zentaobiz' && !is_dir($basePath . '/zentaoep'))  continue;
                    if($zentaoDirName == 'zentaoep'  && !is_dir($basePath . '/zentaobiz')) continue;
                    if($zentaoDirName == 'zentao'    || $zentaoDirName == 'zentaomax')     continue;

                    if($zentaoDirName == 'zentaobiz') $zentaoDirName = 'zentaoep';
                }

                try
                {
                    $webRoot = "/{$database}/";
                    if($database == 'zentao')    $webRoot = '/zentao/';
                    if($database == 'zentaobiz') $webRoot = '/biz/';
                    if($database == 'zentaoep')  $webRoot = '/biz/';
                    if($database == 'zentaomax') $webRoot = '/max/';

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
