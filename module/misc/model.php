<?php
/**
 * The model file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class miscModel extends model
{
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
     * Get the notification information about plugin expiration.
     *
     * @access public
     * @return void
     */
    public function getPluginRemind()
    {
        $plugins = $this->loadModel('extension')->getExpiringPlugins();
        $remind  = '';

        $today = helper::today();
        $showPluginRemind = (empty($this->config->global->showPluginRemind) or $this->config->global->showPluginRemind != $today) ? true : false;
        if(!empty($plugins) and $this->app->user->admin and $showPluginRemind)
        {
            $pluginButton = html::a(helper::createLink('extension', 'browse'), $this->lang->misc->view, '', "id='pluginButton' class='btn btn-primary btn-wide' data-app='admin'");
            $cancelButton = html::a('javascript: void(0);', $this->lang->misc->cancel, '', "id='cancelButton' class='btn btn-back btn-wide'");
            $remind  = '<p>' . sprintf($this->lang->misc->expiredTipsForAdmin, count($plugins)) . '</p>';
            $remind .= '<p class="text-right">' . $pluginButton . $cancelButton . '</p>';

            $this->loadModel('setting')->setItem("{$this->app->user->account}.common.global.showPluginRemind", $today);
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
        if(strpos('|/zentao/|/biz/|/max/|', "|{$this->config->webRoot}|") !== false)
        {
            $databases = array('zentao' => 'zentao', 'zentaobiz' => 'zentaobiz', 'zentaoep' => 'zentaoep', 'zentaomax' => 'zentaomax');
            $basePath  = dirname($this->app->getBasePath());
            foreach($databases as $database)
            {
                $zentaoDirName = $database;
                if(!is_dir($basePath . '/' . $zentaoDirName))
                {
                    if($zentaoDirName == 'zentaobiz' and !is_dir($basePath . '/zentaoep')) continue;
                    if($zentaoDirName == 'zentaoep' and !is_dir($basePath . '/zentaobiz')) continue;
                    if($zentaoDirName == 'zentao' or $zentaoDirName == 'zentaomax') continue;

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
