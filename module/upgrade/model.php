<?php
/**
 * The model file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: model.php 5019 2013-07-05 02:02:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class upgradeModel extends model
{
    static $errors = array();

    public $fromVersion = '';

    public $fromEdition = '';

    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if($this->config->edition != 'lite') $this->config->vision = 'rnd';
        $this->loadModel('setting');
    }

    /**
     * Get versions to update
     *
     * @param  mixed  $openVersion
     * @param  string $fromEdition open|pro|biz|max
     * @return array
     */
    public function getVersionsToUpdate($openVersion, $fromEdition)
    {
        $versions = array();

        /* Always update open sql. */
        foreach($this->lang->upgrade->fromVersions as $version => $versionName)
        {
            if(!is_numeric($version[0])) continue;
            if(version_compare(str_replace('_', '.', $version), str_replace('_', '.', $openVersion)) < 0) continue;
            $versions[$version] = array('pro' => array(), 'biz' => array(), 'max' => array(), 'ipd' => array());
        }
        if($fromEdition == 'open') return $versions;

        /* Update pro sql from pro|biz|max. */
        foreach($this->config->upgrade->proVersion as $pro => $open)
        {
            if(isset($versions[$open])) $versions[$open]['pro'][] = $pro;
        }
        if($fromEdition == 'pro') return $versions;

        /* Update biz sql from biz|max. */
        foreach($this->config->upgrade->bizVersion as $biz => $open)
        {
            if(isset($versions[$open])) $versions[$open]['biz'][] = $biz;
        }
        if($fromEdition == 'biz') return $versions;

        /* Update max sql only from max. */
        foreach($this->config->upgrade->maxVersion as $max => $open)
        {
            if(isset($versions[$open])) $versions[$open]['max'][] = $max;
        }

        /* Update ipd sql only from ipd. */
        foreach($this->config->upgrade->ipdVersion as $ipd => $open)
        {
            if(isset($versions[$open])) $versions[$open]['ipd'][] = $ipd;
        }
        return $versions;
    }

    /**
     * The execute method. According to the $fromVersion call related methods.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        set_time_limit(0);

        if(!isset($this->app->user)) $this->loadModel('user')->su();

        $fromEdition = $this->getEditionByVersion($fromVersion);

        $this->fromVersion = $fromVersion;
        $this->fromEdition = $fromEdition;

        /* If the 'current openVersion' is not equal the 'from openVersion', must update structure. */
        $currentVersion  = str_replace('.', '_', $this->config->version);

        /* Execute. */
        $fromOpenVersion = $this->getOpenVersion($fromVersion);
        $versions        = $this->getVersionsToUpdate($fromOpenVersion, $fromEdition);

        /* Get total sqls and write in tmp file. */
        dao::$realTimeFile = $this->getLogFile();
        if(file_exists(dao::$realTimeFile)) unlink(dao::$realTimeFile);
        if(is_writable($this->app->getTmpRoot()))
        {
            file_put_contents($this->app->getTmpRoot() . 'upgradeSqlLines', '0-0');
            $confirm        = $this->getConfirm($fromVersion);
            $updateTotalSql = count(explode(';', $confirm));
            file_put_contents($this->app->getTmpRoot() . 'upgradeSqlLines', $updateTotalSql . '-0');
        }

        foreach($versions as $openVersion => $chargedVersions)
        {
            $executedXuanxuan = false;
            if($openVersion == '10_1') $executedXuanxuan = true;
            if($openVersion == '16_4' && !empty($this->config->isINT)) $executedXuanxuan = true;

            /* Execute open. */
            $this->saveLogs("Execute $openVersion");
            $this->execSQL($this->getUpgradeFile(str_replace('_', '.', $openVersion)));
            $this->executeOpen($openVersion, $executedXuanxuan);

            /* Execute pro. */
            foreach($chargedVersions['pro'] as $proVersion)
            {
                $this->saveLogs("Execute $proVersion");
                $this->execSQL($this->getUpgradeFile(str_replace('_', '.', $proVersion)));
                $this->executePro($proVersion);
            }

            /* Execute biz. */
            foreach($chargedVersions['biz'] as $bizVersion)
            {
                $this->saveLogs("Execute $bizVersion");
                $this->execSQL($this->getUpgradeFile(str_replace('_', '.', $bizVersion)));
                $this->executeBiz($bizVersion, $executedXuanxuan);
            }

            /* Execute max. */
            foreach($chargedVersions['max'] as $maxVersion)
            {
                $maxVersion = array_search($openVersion, $this->config->upgrade->maxVersion);
                $this->saveLogs("Execute $maxVersion");
                $this->execSQL($this->getUpgradeFile(str_replace('_', '.', $maxVersion)));
                $this->executeMax($maxVersion);
            }
        }

        /* Means open source/pro upgrade to biz or max. */
        if($this->config->edition != 'open')
        {
            if($fromEdition == 'open' or $fromEdition == 'pro')
            {
                $this->importBuildinModules();
                $this->importLiteModules();
                $this->addSubStatus();
            }
        }

        $this->saveLogs("Run Method program-refreshStats");
        $this->loadModel('program')->refreshStats(true);
        $this->saveLogs("Run Method product-refreshStats");
        $this->loadModel('product')->refreshStats(true);
        $this->deletePatch();
    }

    /**
     * Process data for open source.
     *
     * @param  string $openVersion
     * @param  bool   $executedXuanxuan
     * @access public
     * @return void
     */
    public function executeOpen($openVersion, $executedXuanxuan)
    {
        $this->executeByConfig($openVersion, $executedXuanxuan);
        return true;
    }

    /**
     * Process data for pro.
     *
     * @param  string $proVersion
     * @access public
     * @return void
     */
    public function executePro($proVersion)
    {
        if($proVersion == 'pro1_1_1') $this->execSQL($this->getUpgradeFile('pro1.1'));
        if($proVersion == 'pro8_3')   $this->execSQL($this->getUpgradeFile('pro8.2')); //Fix bug #1752.

        $this->executeByConfig($proVersion);
    }

    /**
     * Process data for biz.
     *
     * @param  int   $bizVersion
     * @param  bool  $executedXuanxuan
     * @access public
     * @return void
     */
    public function executeBiz($bizVersion, $executedXuanxuan)
    {
        $this->executeByConfig($bizVersion, $executedXuanxuan);
    }

    /**
     * Process data for max.
     *
     * @param  int   $maxVersion
     * @access public
     * @return void
     */
    public function executeMax($maxVersion)
    {
        $this->executeByConfig($maxVersion);
    }

    /**
     * Process data for ipd.
     *
     * @param  int   $ipdVersion
     * @access public
     * @return void
     */
    public function executeIpd($ipdVersion)
    {
    }

    /**
     * Execute upgrade methods by config.
     *
     * @param  string  $version
     * @param  bool    $executedXuanxuan
     * @access public
     * @return void
     */
    public function executeByConfig($version, $executedXuanxuan = false)
    {
        $execConfig  = zget($this->config->upgrade->execFlow, $version, array());
        $functions   = zget($execConfig, 'functions', '');
        $params      = zget($execConfig, 'params', array());
        $xxsqls      = zget($execConfig, 'xxsqls', '');
        $xxfunctions = zget($execConfig, 'xxfunctions', '');

        foreach(array_filter(explode(',', $functions)) as $function) $this->executeUpgradeMethod($function, zget($params, $function, array()));

        $needExecXX = !$executedXuanxuan;
        if($version == '10_1') $needExecXX = true;
        if($version == '16_4' && !empty($this->config->isINT)) $needExecXX = true;

        if($needExecXX)
        {
            foreach(array_filter(explode(',', $xxsqls)) as $sqlFile) $this->execSQL($sqlFile);
            foreach(array_filter(explode(',', $xxfunctions)) as $function) $this->executeUpgradeMethod($function, zget($params, $function, array()));
        }
    }

    /**
     * Execute single upgrade method.
     *
     * @param  string $method
     * @param  array  $params
     * @access public
     * @return void
     */
    public function executeUpgradeMethod($method, $params)
    {
        $this->saveLogs("Run Method {$method}");

        $class = $this;
        if(strpos($method, '-') !== false)
        {
            list($className, $method) = explode('-', $method);
            $class = $this->loadModel($className);
        }

        dao::$realTimeLog = true;
        call_user_func_array(array($class, $method), $params);
        dao::$realTimeLog = false;
    }

    /**
     * Create the confirm contents.
     *
     * @param  string $fromVersion
     * @access public
     * @return string
     */
    public function getConfirm($fromVersion)
    {
        $confirmContent = '';
        $openVersion    = $fromVersion;

        if($fromVersion[0] == 'p')
        {
            $openVersion     = $this->config->upgrade->proVersion[$fromVersion];
            $confirmContent .= $this->getProConfirm($fromVersion);
        }
        elseif($fromVersion[0] == 'b')
        {
            $openVersion     = $this->config->upgrade->bizVersion[$fromVersion];
            $proVersion      = array_search($openVersion, $this->config->upgrade->proVersion);

            $confirmContent .= $this->getProConfirm($proVersion);
            $confirmContent .= $this->getBizConfirm($fromVersion);
        }
        elseif($fromVersion[0] == 'm')
        {
            $openVersion     = $this->config->upgrade->maxVersion[$fromVersion];
            $proVersion      = array_search($openVersion, $this->config->upgrade->proVersion);
            $bizVersion      = array_search($openVersion, $this->config->upgrade->bizVersion);

            $confirmContent .= $this->getProConfirm($proVersion);
            $confirmContent .= $this->getBizConfirm($bizVersion);
            $confirmContent .= $this->getMaxConfirm($fromVersion);
        }

        $confirmContent = $this->getOpenConfirm($openVersion, $fromVersion) . $confirmContent;
        return str_replace('zt_', $this->config->db->prefix, $confirmContent);
    }

    /**
     * Get open source confirm contents.
     *
     * @param  string  $openVersion
     * @param  string  $fromVersion
     * @access public
     * @return void
     */
    public function getOpenConfirm($openVersion, $fromVersion)
    {
        $confirmContent = '';
        switch($openVersion)
        {
            case '0_3beta':    $confirmContent .= file_get_contents($this->getUpgradeFile('0.3'));
            case '0_4beta':    $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
            case '0_5beta':    $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            case '0_6beta':    $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            case '1_0beta':    $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
            case '1_0rc1':     $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
            case '1_0rc2':
            case '1_0':
            case '1_0_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
            case '1_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
            case '1_2':        $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
            case '1_3':        $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
            case '1_4':        $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
            case '1_5':        $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
            case '2_0':        $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
            case '2_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
            case '2_2':        $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
            case '2_3':        $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
            case '2_4':        $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
            case '3_0_beta1':  $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
            case '3_0_beta2':
            case '3_0':
            case '3_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('3.1'));
            case '3_2':        $confirmContent .= file_get_contents($this->getUpgradeFile('3.2'));
            case '3_2_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('3.2.1'));
            case '3_3':        $confirmContent .= file_get_contents($this->getUpgradeFile('3.3'));
            case '4_0_beta1':  $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.beta1'));
            case '4_0_beta2':  $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.beta2'));
            case '4_0':        $confirmContent .= file_get_contents($this->getUpgradeFile('4.0'));
            case '4_0_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.1'));
            case '4_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('4.1'));
            case '4_2_beta':   $confirmContent .= file_get_contents($this->getUpgradeFile('4.2'));
            case '4_3_beta':   $confirmContent .= file_get_contents($this->getUpgradeFile('4.3'));
            case '5_0_beta1':
            case '5_0_beta2':
            case '5_0':
            case '5_1':
            case '5_2':
            case '5_2_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('5.2.1'));
            case '5_3':
            case '6_0_beta1':  $confirmContent .= file_get_contents($this->getUpgradeFile('6.0.beta1'));
            case '6_0':        $confirmContent .= file_get_contents($this->getUpgradeFile('6.0'));
            case '6_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('6.1'));
            case '6_2':
            case '6_3':
            case '6_4':
            case '7_0':        $confirmContent .= file_get_contents($this->getUpgradeFile('7.0'));
            case '7_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('7.1'));
            case '7_2':
            case '7_2_4':      $confirmContent .= file_get_contents($this->getUpgradeFile('7.2.4'));
            case '7_2_5':
            case '7_3':        $confirmContent .= file_get_contents($this->getUpgradeFile('7.3'));
            case '7_4_beta':   $confirmContent .= file_get_contents($this->getUpgradeFile('7.4.beta'));
            case '8_0':
            case '8_0_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.0.1'));
            case '8_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('8.1'));
            case '8_1_3':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.1.3'));
            case '8_2_beta':
            case '8_2':
            case '8_2_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.2.1'));
            case '8_2_2':
            case '8_2_3':
            case '8_2_4':
            case '8_2_5':
            case '8_2_6':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.2.6'));
            case '8_3':
            case '8_3_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.3.1'));
            case '8_4':
            case '8_4_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('8.4.1'));
            case '9_0_beta':   $confirmContent .= file_get_contents($this->getUpgradeFile('9.0.beta'));
            case '9_0':
            case '9_0_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.0.1'));
            case '9_1':        $confirmContent .= file_get_contents($this->getUpgradeFile('9.1'));
            case '9_1_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.1.1'));
            case '9_1_2':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.1.2'));
            case '9_2':
            case '9_2_1':
            case '9_3_beta':   $confirmContent .= file_get_contents($this->getUpgradeFile('9.3.beta'));
            case '9_4':        $confirmContent .= file_get_contents($this->getUpgradeFile('9.4'));
            case '9_5':        $confirmContent .= file_get_contents($this->getUpgradeFile('9.5'));
            case '9_5_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.5.1'));
            case '9_6':        $confirmContent .= file_get_contents($this->getUpgradeFile('9.6'));
            case '9_6_1':
            case '9_6_2':
            case '9_6_3':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.6.3'));
            case '9_7':        $confirmContent .= file_get_contents($this->getUpgradeFile('9.7'));
            case '9_8':
            case '9_8_1':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.8.1'));
            case '9_8_2':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.8.2'));
            case '9_8_3':      $confirmContent .= file_get_contents($this->getUpgradeFile('9.8.3'));
            case '10_0_alpha': $confirmContent .= file_get_contents($this->getUpgradeFile('10.0.alpha'));
            case '10_0_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('10.0.beta'));
            case '10_0':       $confirmContent .= file_get_contents($this->getUpgradeFile('10.0'));
            case '10_1':       $confirmContent .= file_get_contents($this->app->getAppRoot() . 'db' . DS . 'xuanxuan.sql');
            case '10_2':
            case '10_3':
            case '10_3_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('10.3.1'));
            case '10_4':       $confirmContent .= file_get_contents($this->getUpgradeFile('10.4'));
            case '10_5':
            case '10_5_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('10.5.1'));
            case '10_6':
                $confirmContent .= file_get_contents($this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.1.0.sql');
                $confirmContent .= file_get_contents($this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.2.0.sql');
            case '11_0':
            case '11_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('11.1'));
                if(empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.3.0.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case '11_2': $confirmContent .= file_get_contents($this->getUpgradeFile('11.2'));
            case '11_3': $confirmContent .= file_get_contents($this->getUpgradeFile('11.3'));
            case '11_4': $confirmContent .= file_get_contents($this->getUpgradeFile('11.4'));
            case '11_4_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('11.4.1'));
                if(empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.4.0.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.5.0.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case '11_5'   : $confirmContent .= file_get_contents($this->getUpgradeFile('11.5'));
            case '11_5_1' :
            case '11_5_2' : $confirmContent .= file_get_contents($this->getUpgradeFile('11.5.2'));
            case '11_6'   : $confirmContent .= file_get_contents($this->getUpgradeFile('11.6'));
            case '11_6_1' :
            case '11_6_2' :
            case '11_6_3' :
            case '11_6_4' : $confirmContent .= file_get_contents($this->getUpgradeFile('11.6.4'));
            case '11_6_5' :
                $confirmContent .= file_get_contents($this->getUpgradeFile('11.6.5'));
                if(empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.5.7.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan3.0.0-beta.1.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan3.0-beta3.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case '11_7' : $confirmContent .= file_get_contents($this->getUpgradeFile('11.7'));
            case '12_0' :
            case '12_0_1': $confirmContent .= file_get_contents($this->getUpgradeFile('12.0.1'));
            case '12_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('12.1'));
                if(empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan3.1.1.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case '12_2': $confirmContent .= file_get_contents($this->getUpgradeFile('12.2'));
            case '12_3':
            case '12_3_1':
            case '12_3_2': $confirmContent .= file_get_contents($this->getUpgradeFile('12.3.2'));
            case '12_3_3': $confirmContent .= file_get_contents($this->getUpgradeFile('12.3.3'));
            case '12_4':   $confirmContent .= file_get_contents($this->getUpgradeFile('12.4'));
            case '12_4_1': $confirmContent .= file_get_contents($this->getUpgradeFile('12.4.1'));
            case '12_4_2': $confirmContent .= file_get_contents($this->getUpgradeFile('12.4.2'));
            case '12_4_3':
            case '12_4_4': $confirmContent .= file_get_contents($this->getUpgradeFile('12.4.4'));
            case '12_5':
            case '12_5_1':
            case '12_5_2':
            case '12_5_3': $confirmContent .= file_get_contents($this->getUpgradeFile('12.5.3'));
            case '15_0_rc1':
            case '15_0_rc2': $confirmContent .= file_get_contents($this->getUpgradeFile('15.0.rc2'));
            case '15_0_rc3': $confirmContent .= file_get_contents($this->getUpgradeFile('15.0.rc3'));
            case '15_0': $confirmContent .= file_get_contents($this->getUpgradeFile('15.0'));
            case '15_0_1':
            case '15_0_2': $confirmContent .= file_get_contents($this->getUpgradeFile('15.0.2'));
            case '15_0_3': $confirmContent .= file_get_contents($this->getUpgradeFile('15.0.3'));
            case '15_2': $confirmContent .= file_get_contents($this->getUpgradeFile('15.2'));
            case '15_3': $confirmContent .= file_get_contents($this->getUpgradeFile('15.3'));
            case '15_4':
                $confirmContent .= file_get_contents($this->getUpgradeFile('15.4'));
                if(empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan4.2.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan4.4.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case '15_5': $confirmContent .= file_get_contents($this->getUpgradeFile('15.5'));
            case '15_6': $confirmContent .= file_get_contents($this->getUpgradeFile('15.6'));
            case '15_7': $confirmContent .= file_get_contents($this->getUpgradeFile('15.7'));
            case '15_7_1': $confirmContent .= file_get_contents($this->getUpgradeFile('15.7.1'));
            case '16_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('16.0.beta1'));
            case '16_0': $confirmContent .= file_get_contents($this->getUpgradeFile('16.0'));
            case '16_1': $confirmContent .= file_get_contents($this->getUpgradeFile('16.1'));
            case '16_2': $confirmContent .= file_get_contents($this->getUpgradeFile('16.2'));
            case '16_3': $confirmContent .= file_get_contents($this->getUpgradeFile('16.3'));
            case '16_4':
                if(strpos($fromVersion, 'pro') === false and strpos($fromVersion, 'biz') === false and strpos($fromVersion, 'max') === false)
                {
                    $confirmContent .= file_get_contents($this->getUpgradeFile('proinstall'));
                }

                if(strpos($fromVersion, 'biz') === false and strpos($fromVersion, 'max') === false)
                {
                    $confirmContent .= file_get_contents($this->getUpgradeFile('bizinstall'));
                }

                if(strpos($fromVersion, 'max') === false)
                {
                    $confirmContent .= file_get_contents($this->getUpgradeFile('maxinstall'));
                    $confirmContent .= file_get_contents($this->getUpgradeFile('functions'));
                }

                $confirmContent .= file_get_contents($this->getUpgradeFile('16.4'));

            case '16_5_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('16.5.beta1'));
            case '16_5': $confirmContent .= file_get_contents($this->getUpgradeFile('16.5'));
            case '17_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('17.0.beta1'));
            case '17_0_beta2': $confirmContent .= file_get_contents($this->getUpgradeFile('17.0.beta2'));
            case '17_0': $confirmContent .= file_get_contents($this->getUpgradeFile('17.0'));
            case '17_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.1'));
                $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan5.6.sql';
                $confirmContent .= file_get_contents($xuanxuanSql);
            case '17_2':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.2'));
            case '17_3':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.3'));
                $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan6.0.1.sql';
                $confirmContent .= file_get_contents($xuanxuanSql);
            case '17_4':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.4'));
            case '17_5':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.5'));
            case '17_6':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.6'));
            case '17_6_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.6.1'));
            case '17_6_2':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.6.2'));
                $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan6.4.sql';
                $confirmContent .= file_get_contents($xuanxuanSql);
            case '17_7': $confirmContent .= file_get_contents($this->getUpgradeFile('17.7'));
            case '17_8':
                $confirmContent .= file_get_contents($this->getUpgradeFile('17.8'));
                $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan6.5.sql';
                $confirmContent .= file_get_contents($xuanxuanSql);
            case '18_0_beta1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.0.beta1'));
                $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan6.6.sql';
                $confirmContent .= file_get_contents($xuanxuanSql);
            case '18_0_beta2':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.0.beta2'));
            case '18_0_beta3':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.0.beta3'));
             case '18_0':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.0'));
             case '18_1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.1'));
             case '18_2':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.2'));
             case '18_3':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.3'));
             case '18_4_alpha1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.4.alpha1'));
             case '18_4_beta1':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.4.beta1'));
             case '18_4':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.4'));
             case '18_5':
                $ipdinstall  = false;
                if(is_numeric($fromVersion[0]) and version_compare($fromVersion, '18.5', '<'))             $ipdinstall = true;
                if(strpos($fromVersion, 'biz') !== false and version_compare($fromVersion, 'biz8.5', '<')) $ipdinstall = true;
                if(strpos($fromVersion, 'max') !== false and version_compare($fromVersion, 'max4.5', '<')) $ipdinstall = true;
                if($ipdinstall) $confirmContent .= file_get_contents($this->getUpgradeFile('ipdinstall'));

                $confirmContent .= file_get_contents($this->getUpgradeFile('18.5'));
             case '18_6':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.6'));
             case '18_7':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.7'));
             case '18_8':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.8'));
             case '18_9':
                $confirmContent .= file_get_contents($this->getUpgradeFile('18.9')); // confirm insert position.
        }

        return $confirmContent;
    }

    /**
     * Get pro version confirm contents.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function getProConfirm($fromVersion)
    {
        $confirmContent = '';
        switch($fromVersion)
        {
            case 'pro1_0':   $confirmContent .= file_get_contents($this->getUpgradeFile('pro1.0'));
            case 'pro1_1':
            case 'pro1_1_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro1.1'));
            case 'pro1_2':
            case 'pro1_3':   $confirmContent .= file_get_contents($this->getUpgradeFile('pro1.3'));
            case 'pro2_0':
            case 'pro2_0_1':
            case 'pro2_1':   $confirmContent .= file_get_contents($this->getUpgradeFile('pro2.1'));
            case 'pro2_2_beta':
            case 'pro2_3_beta':
            case 'pro3_0_beta1':
            case 'pro3_0':   $confirmContent .= file_get_contents($this->getUpgradeFile('pro3.0'));
            case 'pro3_1':
            case 'pro3_2':
            case 'pro3_2_1':
            case 'pro3_3':
            case 'pro4_0_beta1':
            case 'pro4_0': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.0'));
            case 'pro4_1_beta':
            case 'pro4_2': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.2'));
            case 'pro4_3': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.3'));
            case 'pro4_4': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.4'));
            case 'pro4_5': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.5'));
            case 'pro4_6': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.6'));
            case 'pro4_7':
            case 'pro4_7_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro4.7.1'));
            case 'pro5_0':
            case 'pro5_0_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro5.0.1'));
            case 'pro5_1':
            case 'pro5_1_3': $confirmContent .= file_get_contents($this->getUpgradeFile('pro5.1.3'));
            case 'pro5_2':
            case 'pro5_2_1':
            case 'pro5_3':
            case 'pro5_3_1':
            case 'pro5_3_2':
            case 'pro5_3_3':
            case 'pro5_4':
            case 'pro5_4_1':
            case 'pro5_5':
            case 'pro5_5_1':
            case 'pro6_0_beta':
            case 'pro6_0':
            case 'pro6_0_1':
            case 'pro6_1':
            case 'pro6_2':
            case 'pro6_3':
            case 'pro6_3_1':
            case 'pro6_4': $confirmContent .= file_get_contents($this->getUpgradeFile('pro6.4'));
            case 'pro6_5':
            case 'pro6_5_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro6.5.1'));
            case 'pro6_6':
            case 'pro6_6_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro6.6.1'));
            case 'pro6_7':
            case 'pro6_7_1':
            case 'pro6_7_2':
            case 'pro6_7_3':
            case 'pro7_0_beta': $confirmContent .= file_get_contents($this->getUpgradeFile('pro7.0.beta'));
            case 'pro7_1':
            case 'pro7_2':
            case 'pro7_3':
            case 'pro7_4':
            case 'pro7_5':
            case 'pro7_5_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro7.5.1'));
            case 'pro8_0':
            case 'pro8_1':
            case 'pro8_2': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.2'));
            case 'pro8_3': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.3'));
            case 'pro8_3_1':
            case 'pro8_4': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.4'));
            case 'pro8_5':
            case 'pro8_5_1': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.5.1'));
            case 'pro8_5_2':
            case 'pro8_5_3':
            case 'pro8_6': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.6'));
            case 'pro8_7':
            case 'pro8_8':
            case 'pro8_8_1':
            case 'pro8_8_2':
            case 'pro8_8_3':
            case 'pro8_9':
            case 'pro8_9_1':
            case 'pro8_9_2':
            case 'pro8_9_3': $confirmContent .= file_get_contents($this->getUpgradeFile('pro8.9.3'));
            case 'pro8_9_4':
            case 'pro9_0':
            case 'pro9_0_1':
            case 'pro9_0_2':
            case 'pro9_0_3': $confirmContent .= file_get_contents($this->getUpgradeFile('pro9.0.3'));
            case 'pro10_0_rc1':
            case 'pro10_0':
            case 'pro10_0_1':
            case 'pro10_0_2': $confirmContent .= file_get_contents($this->getUpgradeFile('pro10.0.2'));
            case 'pro10_1':
            case 'pro10_2':
            case 'pro10_3':
            case 'pro10_3_1':
            case 'pro11_0_beta1':
        }

        return $confirmContent;
    }

    /**
     * Get biz version confirm contents.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function getBizConfirm($fromVersion)
    {
        $confirmContent = '';
        switch($fromVersion)
        {
            case 'biz1_0': $confirmContent .= file_get_contents($this->getUpgradeFile('biz1.0'));
            case 'biz1_1':
            case 'biz1_1_1':
            case 'biz1_1_2':
            case 'biz1_1_3':
            case 'biz1_1_4':
            case 'biz2_0_beta':
            case 'biz2_1':
            case 'biz2_2': $confirmContent .= file_get_contents($this->getUpgradeFile('biz2.2'));
            case 'biz2_3':
            case 'biz2_3_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz2.3.1'));
            case 'biz2_4': $confirmContent .= file_get_contents($this->getUpgradeFile('biz2.4'));
            case 'biz3_0':
                if(!empty($this->config->isINT))
                {
                    $xuanxuanSql     = $this->app->getAppRoot() . 'db' . DS . 'upgradexuanxuan2.3.0.sql';
                    $confirmContent .= file_get_contents($xuanxuanSql);
                }
            case 'biz3_1':
            case 'biz3_2': $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.2'));
            case 'biz3_2_1':
            case 'biz3_3':       $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.3'));
            case 'biz3_4':       $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.4'));
            case 'biz3_5_alpha': $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.5.alpha'));
            case 'biz3_5_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.5.beta'));
            case 'biz3_5':
            case 'biz3_5_1':
            case 'biz3_6':
            case 'biz3_6_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.6.1'));
            case 'biz3_7':   $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.7'));
            case 'biz3_7_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.7.1'));
            case 'biz3_7_2': $confirmContent .= file_get_contents($this->getUpgradeFile('biz3.7.2'));
            case 'biz4_0':   $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.0'));
            case 'biz4_0_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.0.1'));
            case 'biz4_0_2': $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.0.2'));
            case 'biz4_0_3': $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.0.3'));
            case 'biz4_0_4': $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.0.4'));
            case 'biz4_1':
            case 'biz4_1_1':
            case 'biz4_1_2':
            case 'biz4_1_3': $confirmContent .= file_get_contents($this->getUpgradeFile('biz4.1.3'));
            case 'biz5_0_rc1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz5.0.rc1'));
            case 'biz5_0':
            case 'biz5_0_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz5.0.1'));
            case 'biz5_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz5.1'));
            case 'biz5_2':
            case 'biz5_3':
            case 'biz5_3_1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz5.3.1'));
            case 'biz6_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('biz6.0.beta1'));
            case 'biz6_0':
            case 'biz6_1':
        }

        return $confirmContent;
    }

    /**
     * Get max version confirm  contents.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function getMaxConfirm($fromVersion)
    {
        $confirmContent = '';
        if($fromVersion == 'max2_0_beta4' && $this->config->version != 'max2.0.rc1') $fromVersion = 'max2_0_rc1';

        switch($fromVersion)
        {
            case 'max2_0_rc1':
            case 'max2_0_beta4': $confirmContent .= file_get_contents($this->getUpgradeFile('max2.0.beta4'));
            case 'max2_0': $confirmContent .= file_get_contents($this->getUpgradeFile('max2.0'));
            case 'max2_1':
            case 'max2_2': $confirmContent .= file_get_contents($this->getUpgradeFile('max2.2'));
            case 'max2_3':
            case 'max2_3_1': $confirmContent .= file_get_contents($this->getUpgradeFile('max2.3.1'));
            case 'max2_4_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('max2.4.beta1'));
            case 'max2_4':
        }

        return $confirmContent;
    }

    /**
     * Get edition by version.
     *
     * @param  string    $version
     * @access public
     * @return string
     */
    public function getEditionByVersion($version)
    {
        $editions = array('p' => 'pro', 'b' => 'biz', 'm' => 'max', 'i' => 'ipd');
        return is_numeric($version[0]) ? 'open' : $editions[$version[0]];
    }

    /**
     * Get openVersion
     *
     * @param  int    $version.
     * @access public
     * @return string
     */
    public function getOpenVersion($version)
    {
        $edition = $this->getEditionByVersion($version);
        return is_numeric($version[0]) ? $version : zget($this->config->upgrade->{$edition . 'Version'}, $version);
    }

    /**
     * Update company field.
     *
     * This method is used to update since 1.0 beta. Any new tables added after 1.0 beta should skip.
     *
     * @access public
     * @return void
     */
    public function updateCompany()
    {
        /* Get user defined constants. */
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* Update tables. */
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;

            $table  = $value;
            $result = $this->dbh->query("SHOW TABLES LIKE '$table'");
            if($result->rowCount() > 0)
            {
                $this->dbh->query("UPDATE $table SET company = '{$this->app->company->id}'");
            }
        }
    }

    /**
     * Get standard SQL.
     *
     * @param  string version
     * @access private
     * @return string;
     */
    private function getStandardSQL($version)
    {
        if(empty($version)) $version = $this->config->installedVersion;

        $editions     = array('p' => 'proVersion', 'b' => 'bizVersion', 'm' => 'maxVersion', 'i' => 'ipdVersion');
        $version      = str_replace('.', '_', $version);
        $fromEdition  = is_numeric($version[0]) ? 'open' : $editions[$version[0]];
        $openVersion  = is_numeric($version[0]) ? $version : $this->config->upgrade->{$fromEdition}[$version];
        $openVersion  = str_replace('_', '.', $openVersion);
        $checkVersion = version_compare($openVersion, '16.5', '<') ? str_replace('_', '.', $version) : $openVersion;

        $standardSQL = $this->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'zentao' . $checkVersion . '.sql';
        if(!file_exists($standardSQL)) return '';

        $sqls = file_get_contents($standardSQL);
        if(empty($this->config->isINT))
        {
            $xVersion     = $openVersion;
            $xStandardSQL = $this->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'xuanxuan' . $xVersion . '.sql';
            if(file_exists($xStandardSQL)) $sqls .= "\n" . file_get_contents($xStandardSQL);
        }

        return $sqls;
    }

    /**
     * Check table consistency.
     *
     * @param  string $sql
     * @access private
     * @return array
     */
    private function checkTableConsistency($sql)
    {
        $changes = array();

        $lines      = explode("\n", $sql);
        $createHead = array_shift($lines);
        $createFoot = array_pop($lines);

        preg_match_all('/ENGINE=(\w+) /', $createFoot, $out);
        $stdEngine = isset($out[1][0]) ? $out[1][0] : 'InnoDB';

        preg_match_all('/CREATE TABLE `([^`]*)`/', $createHead, $out);
        if(!isset($out[1][0])) return $changes;

        $table  = str_replace('zt_', $this->config->db->prefix, $out[1][0]);
        $fields = array();

        try
        {
            $dbCreateSQL = $this->dbh->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $dbSQLLines  = explode("\n", $dbCreateSQL['Create Table']);
            $dbSQLHead   = array_shift($dbSQLLines);
            $dbSQLFoot   = array_pop($dbSQLLines);

            preg_match_all('/ENGINE=(\w+) /', $dbSQLFoot, $out);
            $dbEngine = isset($out[1][0]) ? $out[1][0] : 'InnoDB';

            foreach($dbSQLLines as $dbSQLLine)
            {
                $dbSQLLine = trim($dbSQLLine);
                if(!preg_match('/^`([^`]*)` /', $dbSQLLine)) continue;   // Skip no describe field line.

                $dbSQLLine = rtrim($dbSQLLine, ',');
                $dbSQLLine = str_replace('utf8 COLLATE utf8_general_ci', 'utf8', $dbSQLLine);
                $dbSQLLine = preg_replace('/ DEFAULT (\-?\d+\.?\d*)$/', " DEFAULT '$1'", $dbSQLLine);

                list($field) = explode(' ', $dbSQLLine);
                $fields[$field] = rtrim($dbSQLLine, ',');
            }
        }
        catch(PDOException $e)
        {
            $errorInfo = $e->errorInfo;
            $errorCode = $errorInfo[1];

            /* If table is not extists, try create this table by create sql. */
            if($errorCode == '1146') return array($sql);
        }

        $changes = array();

        // MYSQL5.5 dont support InnoDB perfectly, ignore engine changes.
        // if($stdEngine != $dbEngine) $changes[] = "ALTER TABLE `{$table}` ENGINE='{$stdEngine}'";

        foreach($lines as $line)
        {
            $change = $this->checkFieldConsistency($table, $line, $fields);
            if($change) $changes[] = $change;
        }

        return $changes;
    }

    /**
     * Check field consistency.
     *
     * @param  string $table
     * @param  string $line
     * @param  string $fields
     * @access private
     * @return array
     */
    private function checkFieldConsistency($table, $line, $fields)
    {
        $line = trim($line);
        if(!preg_match('/^`([^`]*)` /', $line)) return '';   // Skip no describe field line.

        $line = rtrim($line, ',');
        $line = str_replace('utf8 COLLATE utf8_general_ci', 'utf8', $line);
        $line = preg_replace('/ DEFAULT (\-?\d+\.?\d*)$/', " DEFAULT '$1'", $line);

        list($field) = explode(' ', $line);
        if(isset($fields[$field]) and strpos($fields[$field], ' NULL') === false and strpos($line, ' DEFAULT NULL') !== false) $line = str_replace(' DEFAULT NULL', '', $line); // e.g. standard sql like [ `content` text DEFAULT NULL ] , but current db sql like [ `content` text ].

        /* Dont change if matched. */
        if(isset($fields[$field]) && $line == $fields[$field]) return '';

        if(!isset($fields[$field]))
        {
            $execSQL = "ALTER TABLE `$table` ADD $line"; // Add field.
        }
        else
        {
            $execSQL = $this->checkFieldSQL($table, $line, $fields[$field]); // Modify field.
        }

        if(stripos($execSQL, 'auto_increment') !== false) $execSQL .= ' FIRST';

        return $execSQL;
    }

    /**
     * Check field SQL.
     *
     * @param  string $table
     * @param  string $stdField
     * @param  string $dbField
     * @access private
     * @return string
     */
    private function checkFieldSQL($table, $stdField, $dbField)
    {
        $needModify = false;

        if($this->checkFieldTypeDiff($stdField, $dbField))    $needModify = true;
        if($this->checkFieldNullDiff($stdField, $dbField))    $needModify = true;
        if($this->checkFieldDefaultDiff($stdField, $dbField)) $needModify = true;

        $fieldName = explode(' ', $dbField)[0];
        return $needModify ? "ALTER TABLE `$table` CHANGE $fieldName $stdField" : '';
    }

    /**
     * Check field null diff.
     *
     * @param  string $stdField
     * @param  string $dbField
     * @access private
     * @return bool
     */
    private function checkFieldNullDiff($stdField, $dbField)
    {
        $dbNotNull  = stripos($dbField, 'NOT NULL') !== false;
        $stdNotNull = stripos($stdField, 'NOT NULL') !== false;

        if($dbNotNull && !$stdNotNull) return true;
    }

    /**
     * Check field default diff.
     *
     * @param  string $stdField
     * @param  string $dbField
     * @access private
     * @return bool
     */
    private function checkFieldDefaultDiff($stdField, $dbField)
    {
        $defaultPos = stripos($stdField, ' DEFAULT ');
        if($defaultPos === false) return false; // No default in std, don't change.

        $stdDefault = substr($stdField, $defaultPos + 9);
        if($stdDefault == 'NULL') return false; // Default is NULL, don't change.
        if(strpos($stdField, 'text') !== false && $stdDefault == "''") return false; // Default is '' and text type, don't change.

        $defaultPos = stripos($dbField,  ' DEFAULT ');
        if($defaultPos === false) return true; // No default in db, change it.

        $dbDefault = substr($dbField, $defaultPos + 9);

        return $stdDefault != $dbDefault;
    }

    /**
     * Check field type diff .
     *
     * @param  string $stdField
     * @param  string $dbField
     * @access private
     * @return bool
     */
    private function checkFieldTypeDiff($stdField, $dbField)
    {
        $stdConfigs = explode(' ', $stdField);
        $dbConfigs  = explode(' ', $dbField);

        $stdType = $stdConfigs[1];
        $dbType  = $dbConfigs[1];

        if($stdType == $dbType) return false;

        $stdLength = 0;
        $dbLength  = 0;

        preg_match_all('/^(\w+)(\((\d+)\))?$/', $stdConfigs[1], $stdOutput);
        if(!empty($stdOutput[1][0])) $stdType   = $stdOutput[1][0];
        if(!empty($stdOutput[3][0])) $stdLength = $stdOutput[3][0];

        preg_match_all('/^(\w+)(\((\d+)\))?$/', $dbConfigs[1], $dbOutput);
        if(!empty($dbOutput[1][0])) $dbType   = $dbOutput[1][0];
        if(!empty($dbOutput[3][0])) $dbLength = $dbOutput[3][0];

        $stdIsInt     = stripos($stdType, 'int') !== false;
        $stdIsVarchar = stripos($stdType, 'varchar') !== false;
        $stdIsText    = stripos($stdType, 'text') !== false;
        $stdIsFloat   = preg_match('/float|decimal|double/i', $stdType);
        $dbIsInt      = stripos($dbType, 'int') !== false;
        $dbIsVarchar  = stripos($dbType, 'varchar') !== false;
        $dbIsText     = stripos($dbType, 'text') !== false;
        $dbIsFloat    = preg_match('/int|float|decimal|double/i', $dbType);

        if($dbIsInt)
        {
            if($stdIsFloat || $stdIsText || $stdIsVarchar) return true;
            if($dbLength != 0 && $stdLength > $dbLength) return true; // MySQL 8.0 int has no length.
        }
        elseif($dbIsVarchar)
        {
            if($stdIsText) return true;
            if($dbLength && $stdLength > $dbLength) return true;
        }

        return false;
    }

    /**
     * Check consistency.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function checkConsistency($version = '')
    {
        $alterSQL = array();

        $sqls = $this->getStandardSQL($version);
        foreach(explode(';', $sqls) as $sql)
        {
            $sql = trim($sql);
            if(strpos($sql, 'CREATE TABLE ') !== 0) continue;

            $changes = $this->checkTableConsistency($sql);
            if(!empty($changes)) $alterSQL = array_merge($alterSQL, $changes);
        }

        return implode(";\n", $alterSQL);
    }

    /**
     * Exec fix sql for consistency.
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function fixConsistency($version)
    {
        $logFile = $this->getConsistencyLogFile();
        if(file_exists($logFile)) unlink($logFile);

        $hasError = false;
        $fixSqls  = $this->checkConsistency($version);
        if($fixSqls) $fixSqls = "SET @@sql_mode= '';\n" . $fixSqls;

        $sqlLines = explode(';', $fixSqls);
        file_put_contents($logFile, count($sqlLines) . "\n", FILE_APPEND);
        foreach($sqlLines as $fixSQL)
        {
            file_put_contents($logFile, $fixSQL, FILE_APPEND);
            try
            {
                $this->dbh->exec($fixSQL);
            }
            catch(PDOException $e)
            {
                $hasError = true;
                file_put_contents($logFile, $e->getMessage() . "\n", FILE_APPEND);
            }
        }

        $finishTag = "\nFinished";
        if($hasError) $finishTag = "\nHasError" . $finishTag;
        file_put_contents($logFile, $finishTag, FILE_APPEND);
    }

    /**
     * Delete tmp model files.
     *
     * @access public
     * @return void
     */
    public function deleteTmpModel()
    {
        $tmpModelDir = $this->app->getTmpRoot() . 'model/';
        foreach(glob($tmpModelDir . '/*.php') as $tmpModelFile) unlink($tmpModelFile);
    }

    /**
     * Delete Useless Files
     *
     * @access public
     * @return array
     */
    public function deleteFiles()
    {
        $result = array();
        $zfile  = $this->app->loadClass('zfile');

        foreach($this->config->delete as $deleteFiles)
        {
            $basePath = $this->app->getBasePath();

            foreach($deleteFiles as $file)
            {
                if(isset($this->config->excludeFiles[$file])) continue;

                $fullPath = $basePath . str_replace('/', DS, $file);
                if(file_exists($fullPath))
                {
                    $isDir = is_dir($fullPath);
                    if(!is_writable($fullPath) or ($isDir and !$zfile->removeDir($fullPath)) or
                       (!$isDir and !$zfile->removeFile($fullPath)))
                    {
                        $result[] = 'rm -f ' . ($isDir ? '-r ' : '') . $fullPath;
                    }
                }
            }
        }

        /* Delete all patch files when upgrade zentao. */
        $patchPath = $this->app->getTmpRoot() . 'patch';
        $isDir     = is_dir($patchPath);
        if(file_exists($patchPath))
        {
            if(!is_writable($patchPath) or ($isDir and !$zfile->removeDir($patchPath)) or
                (!$isDir and !$zfile->removeDir($patchPath)))
            {
                $result[] = 'rm -f ' . ($isDir ? '-r ' : '') . $patchPath;
            }
        }

        return $result;
    }

    /**
     * Update ubb code in bug table and user Templates table to html.
     *
     * @access public
     * @return void
     */
    public function updateUBB()
    {
        $this->app->loadClass('ubb', true);

        $bugs = $this->dao->select('id, steps')->from(TABLE_BUG)->fetchAll();
        $userTemplates = $this->dao->select('id, content')->from($this->config->db->prefix . 'userTPL')->fetchAll();

        foreach($bugs as $id => $bug)
        {
            $bug->steps = ubb::parseUBB($bug->steps);
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
        }
        foreach($userTemplates as $template)
        {
            $template->content = ubb::parseUBB($template->content);
            $this->dao->update($this->config->db->prefix . 'userTPL')->data($template)->where('id')->eq($template->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.2 version.
     *
     * @access public
     * @return void
     */
    public function updateNL1_2()
    {
        $tasks     = $this->dao->select('id, `desc`')->from(TABLE_TASK)->fetchAll();
        $stories   = $this->dao->select('story, version, spec')->from($this->config->db->prefix . 'storySpec')->fetchAll();
        $todos     = $this->dao->select('id, `desc`')->from(TABLE_TODO)->fetchAll();
        $testTasks = $this->dao->select('id, `desc`')->from($this->config->db->prefix . 'testTask')->fetchAll();

        foreach($tasks as $task)
        {
            $task->desc = nl2br($task->desc);
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();
        }
        foreach($stories as $story)
        {
            $story->spec = nl2br($story->spec);
            $this->dao->update($this->config->db->prefix . 'storySpec')->data($story)->where('story')->eq($story->story)->andWhere('version')->eq($story->version)->exec();
        }

        foreach($todos as $todo)
        {
            $todo->desc = nl2br($todo->desc);
            $this->dao->update(TABLE_TODO)->data($todo)->where('id')->eq($todo->id)->exec();
        }

        foreach($testTasks as $testtask)
        {
            $testtask->desc = nl2br($testtask->desc);
            $this->dao->update($this->config->db->prefix . 'testTask')->data($testtask)->where('id')->eq($testtask->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.3 version.
     *
     * @access public
     * @return void
     */
    public function updateNL1_3()
    {
        $products = $this->dao->select('id, `desc`')->from(TABLE_PRODUCT)->fetchAll();
        $plans    = $this->dao->select('id, `desc`')->from($this->config->db->prefix . 'productPlan')->fetchAll();
        $releases = $this->dao->select('id, `desc`')->from(TABLE_RELEASE)->fetchAll();
        $projects = $this->dao->select('id, `desc`, goal')->from(TABLE_PROJECT)->fetchAll();
        $builds   = $this->dao->select('id, `desc`')->from(TABLE_BUILD)->fetchAll();
        $account  = isset($this->app->user->account) ? $this->app->user->account : '';

        foreach($products as $product)
        {
            $product->desc = nl2br($product->desc);
            $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($product->id)->exec();
        }

        foreach($plans as $plan)
        {
            $plan->desc = nl2br($plan->desc);
            $this->dao->update($this->config->db->prefix . 'productPlan')->data($plan)->where('id')->eq($plan->id)->exec();
        }

        foreach($releases as $release)
        {
            $release->desc = nl2br($release->desc);
            $this->dao->update(TABLE_RELEASE)->data($release)->where('id')->eq($release->id)->exec();
        }

        foreach($projects as $project)
        {
            $project->desc           = nl2br($project->desc);
            $project->goal           = nl2br($project->goal);
            $project->lastEditedBy   = $account;
            $project->lastEditedDate = helper::now();
            $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq($project->id)->exec();
        }

        foreach($builds as $build)
        {
            $build->desc = nl2br($build->desc);
            $this->dao->update(TABLE_BUILD)->data($build)->where('id')->eq($build->id)->exec();
        }
    }

    /**
     * Update task fields.
     *
     * @access public
     * @return void
     */
    public function updateTasks()
    {
        /* Get all actions of tasks. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('task')
            ->orderBy('id')
            ->fetchAll('id');

        /* Get histories about status field. */
        $histories = $this->dao->select()->from(TABLE_HISTORY)
            ->where('action')->in(array_keys($actions))
            ->andWhere('field')->eq('status')
            ->orderBy('id')
            ->fetchGroup('action');

        $tasks = array();
        foreach($actions as $action)
        {
            if(!isset($tasks[$action->objectID]))
            {
                $tasks[$action->objectID] = new stdclass;
            }
            $task = $tasks[$action->objectID];

            $task->id   = $action->objectID;
            $actionType = strtolower($action->action);

            /* Set the openedBy info. */
            if($actionType == 'opened')
            {
                $task->openedBy   = $action->actor;
                $task->openedDate = $action->date;
            }
            else
            {
                if(!isset($histories[$action->id])) continue;

                $actionHistories = $histories[$action->id];
                foreach($actionHistories as $history)
                {
                    /* Finished by. */
                    if($history->new == 'done')
                    {
                        $task->finishedBy   = $action->actor;
                        $task->finishedDate = $action->date;
                        $action->action     = 'finished';
                    }
                    /* Canceled By. */
                    elseif($history->new == 'cancel')
                    {
                        $task->canceledBy   = $action->actor;
                        $task->canceledDate = $action->date;
                        $action->action     = 'canceled';
                    }
                }

                /* Last edited by .*/
                $task->lastEditedBy   = $action->actor;
                $task->lastEditedDate = $action->date;

                /* Update action type. */
                $this->dao->update(TABLE_ACTION)->set('action')->eq($action->action)->where('id')->eq($action->id)->exec();
            }
        }

        /* Update db. */
        foreach($tasks as $task) $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();

        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = finishedDate')->where('status')->eq('done')->exec();
        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = canceledDate')->where('status')->eq('cancel')->exec();
    }

    /**
     * Update activated count of Bug.
     *
     * @access public
     * @return void
     */
    public function updateActivatedCountOfBug()
    {
        $bugActivatedActions = $this->dao->select('*')->from(TABLE_ACTION)->where('action')->eq('activated')->andWhere('objectType')->eq('bug')->fetchAll();
        if(!empty($bugActivatedActions))
        {
            foreach($bugActivatedActions as $action)
            {
                if(!isset($counts[$action->objectID]))  $counts[$action->objectID] = 0;
                $counts[$action->objectID] ++;
            }
            foreach($counts as $key => $count) $this->dao->update(TABLE_BUG)->set('activatedCount')->eq($count)->where('id')->eq($key)->exec();
        }
    }

    /**
     * Update lastRun and lastResult field in zt_case
     *
     * @access public
     * @return void
     */
    public function updateCases()
    {
        $results = $this->dao->select('`case`, date, caseResult')->from($this->config->db->prefix . 'testResult')->orderBy('id desc')->fetchGroup('case');
        foreach($results as $result)
        {
            $this->dao->update(TABLE_CASE)
                ->set('lastRun')->eq($result[0]->date)
                ->set('lastResult')->eq($result[0]->caseResult)
                ->where('id')->eq($result[0]->case)
                ->exec();
        }
    }

    /**
     * Update type of projects.
     *
     * @access public
     * @return void
     */
    public function updateProjectType()
    {
        $projects = $this->dao->select('root')->from(TABLE_MODULE)->where('type')->eq('task')->fetchPairs('root');
        $this->dao->update(TABLE_PROJECT)->set('type')->eq('waterfall')->where('id')->in($projects)->exec();
        return true;
    }

    /**
     * Update estimate priv.
     *
     * @access public
     * @return void
     */
    public function updateEstimatePriv()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $groups = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('task')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($groups as $group)
        {
            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('recordEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('recordEstimate')
                ->exec();

            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('editEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('editEstimate')
                ->exec();

            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('deleteEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('deleteEstimate')
                ->exec();
        }
        return true;
    }

    /**
     * Update the data of action.
     *
     * @access public
     * @return void
     */
    public function updateAction()
    {
        /* Get projects and tasks from action table. */
        $projects = $this->dao->select('id')->from(TABLE_PROJECT)->fetchPairs('id');
        $tasks    = $this->dao->select('id, project')->from(TABLE_TASK)->fetchPairs('id');

        /* Get products of projects and tasks. */
        $projectProducts = $this->dao->select('project,product')->from($this->config->db->prefix . 'projectProduct')->where('project')->in(array_keys($projects))->fetchGroup('project', 'product');
        $taskProducts    = $this->dao->select('t1.id, t2.product')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.id')->in(array_keys($tasks))
            ->fetchPairs('id');

        /* Process project actions. */
        foreach($projects as $projectID)
        {
            $productList = isset($projectProducts[$projectID]) ? join(',', array_keys($projectProducts[$projectID])) : '';
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('project')->andWhere('objectID')->eq($projectID)->exec();
        }

        /* Process task actions. */
        foreach($tasks as $taskID => $projectID)
        {
            $productList = '';
            if($taskProducts[$taskID])
            {
                $productList = $taskProducts[$taskID];
            }
            else
            {
                $productList = isset($projectProducts[$projectID]) ? join(',', array_keys($projectProducts[$projectID])) : '';
            }
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('task')->andWhere('objectID')->eq($taskID)->andWhere('project')->eq($projectID)->exec();
        }

        $this->dao->update(TABLE_ACTION)->set("product = concat(',',product,',')")->exec();
        return true;
    }

    /**
     * Init the data of product and project order field.
     *
     * @access public
     * @return void
     */
    public function setOrderData()
    {
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->orderBy('code')->fetchAll('id');
        foreach(array_keys($products) as $key => $productID)
        {
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq(($key + 1) * 10)->where('id')->eq($productID)->exec();
        }
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)->andWhere('deleted')->eq(0)->orderBy('status, id desc')->fetchAll('id');
        foreach(array_keys($projects) as $key => $projectID)
        {
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq(($key + 1) * 10)->where('id')->eq($projectID)->exec();
        }
        return true;
    }

    /**
     * Update task assignedTo.
     *
     * @access public
     * @return void
     */
    public function updateTaskAssignedTo()
    {
        $this->dao->update(TABLE_TASK)->set('assignedTo')->eq('closed')
            ->where('status')->eq('closed')
            ->andWhere('assignedTo')->eq('')
            ->exec();
        return true;
    }

    /**
     * Delete the patch record.
     *
     * @access public
     * @return void
     */
    public function deletePatch()
    {
        $this->dao->delete()->from(TABLE_EXTENSION)->where('type')->eq('patch')->exec();
        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->in('zentaopatch,patch')->exec();
    }

    /**
     * Get the upgrade sql file.
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . DS . 'update' . $version . '.sql';
    }

    /**
     * Gets program key-value pairs.
     *
     * @access public
     * @return string
     */
    public function getProgramPairs()
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)->where('deleted')->eq(0)->andWhere('type')->eq('program')->orderBy('id_asc')->fetchPairs('id', 'name');
    }

    /**
     * Get the project of the program it belongs to.
     *
     * @param  string $programID
     * @access public
     * @return string
     */
    public function getProjectPairsByProgram($programID = 0)
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq(0)->andWhere('type')->eq('project')->andWhere('parent')->eq($programID)->fetchPairs('id', 'name');
    }

    /**
     * Execute a sql.
     *
     * @param  string  $sqlFile
     * @access public
     * @return void
     */
    public function execSQL($sqlFile)
    {
        if(!file_exists($sqlFile)) return false;

        $this->saveLogs('Run Method ' . __FUNCTION__);

        static $mysqlVersion;
        if($mysqlVersion === null) $mysqlVersion = $this->loadModel('install')->getDatabaseVersion();

        $ignoreCode = '|1050|1054|1060|1091|1061|1062|';
        $sqls       = $this->parseToSqls($sqlFile);
        foreach($sqls as $sql)
        {
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
            }

            $sqlToLower = strtolower($sql);
            if(strpos($sqlToLower, 'fulltext') !== false and strpos($sqlToLower, 'innodb') !== false and $mysqlVersion < 5.6)
            {
                $sql = str_replace('ENGINE=InnoDB', 'ENGINE=MyISAM', $sql);
            }

            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            $sql = str_replace('__DELIMITER__', ';', $sql);
            $sql = str_replace('__TABLE__', $this->config->db->name, $sql);
            try
            {
                $this->saveLogs($sql);

                /* Calculate the number of sql runs completed. */
                if(is_writable($this->app->getTmpRoot()))
                {
                    $sqlLines    = file_get_contents($this->app->getTmpRoot() . 'upgradeSqlLines');
                    $sqlLines    = explode('-', $sqlLines);
                    $executeLine = $sqlLines[1];
                    $executeLine ++;
                    file_put_contents($this->app->getTmpRoot() . 'upgradeSqlLines', $sqlLines[0] . '-' . $executeLine);
                }

                $this->dbh->exec($sql);
            }
            catch(PDOException $e)
            {
                $this->saveLogs($e->getMessage());
                $errorInfo = $e->errorInfo;
                $errorCode = $errorInfo[1];
                if(strpos($ignoreCode, "|$errorCode|") === false) static::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /**
     * Parse sql file to sqls.
     *
     * @param  string $sqlFile
     * @access public
     * @return array
     */
    public function parseToSqls($sqlFile)
    {
        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        $sqlList = array();
        foreach($sqls as $key => $line)
        {
            $line = trim($line);
            if(!$line) continue;
            /* Skip sql that is note. */
            if(!preg_match('/^--|^#|^\/\*/', $line)) $sqlList[] = $line;
        }

        return array_filter(explode(';', join("\n", $sqlList)));
    }


    /**
     * Add priv for version 4.0.1
     *
     * @access public
     * @return void
     */
    public function addPriv4_0_1()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('company')
            ->andWhere('method')->eq('edit')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('company')
                ->set('method')->eq('view')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('todo')
            ->andWhere('method')->eq('finish')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('todo')
                ->set('method')->eq('batchFinish')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for version 4.1
     *
     * @access public
     * @return bool
     */
    public function addPriv4_1()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('tree')
            ->andWhere('method')->eq('browse')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('tree')
                ->set('method')->eq('browseTask')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for version 8.1
     *
     * @access public
     * @return bool
     */
    public function addPriv8_1()
    {
        $privTable = $this->config->db->prefix . 'grouppriv';

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('bug')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('bug')
                ->set('method')->eq('linkBugs')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('bug')
                ->set('method')->eq('unlinkBug')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('story')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('story')
                ->set('method')->eq('linkStory')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('story')
                ->set('method')->eq('unlinkStory')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('testcase')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('testcase')
                ->set('method')->eq('linkCases')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('testcase')
                ->set('method')->eq('unlinkCase')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for version 12.3.3
     *
     * @access public
     * @return bool
     */
    public function addPriv12_3_3()
    {
        $privTable = $this->config->db->prefix . 'grouppriv';

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('todo')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('todo')
                ->set('method')->eq('start')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for 8.2.
     *
     * @access public
     * @return bool
     */
    public function addPriv8_2_beta()
    {
        $privTable = $this->config->db->prefix . 'grouppriv';

        /* Change product-all priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('product')->andWhere('`method`')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('product')->set('method')->eq('all')->set('`group`')->eq($group)->exec();
        }

        /* Change project-all priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('project')->andWhere('`method`')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('all')->set('`group`')->eq($group)->exec();
        }

        /* Add kanban and tree priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('project')->andWhere('`method`')->eq('task')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('kanban')->set('`group`')->eq($group)->exec();
            $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('tree')->set('`group`')->eq($group)->exec();
        }

        /* Change manageContacts priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('user')->andWhere('`method`')->eq('manageContacts')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('my')->set('method')->eq('manageContacts')->set('`group`')->eq($group)->exec();
        }

        /* Change deleteContacts priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('user')->andWhere('`method`')->eq('deleteContacts')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('my')->set('method')->eq('deleteContacts')->set('`group`')->eq($group)->exec();
        }

        /* Change batchChangeModule priv. */
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where("(`module`='story'      and `method`='edit')")
            ->orWhere("(`module`='task'     and `method`='edit')")
            ->orWhere("(`module`='bug'      and `method`='edit')")
            ->orWhere("(`module`='testcase' and `method`='edit')")
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)->set('module')->eq($item->module)->set('method')->eq('batchChangeModule')->set('`group`')->eq($item->group)->exec();
        }

        return true;
    }

    /**
     * Add or view priv for adminer.
     *
     * @access public
     * @return void
     */
    public function addORPriv()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);

        $admins = $this->dao->select('admins')->from(TABLE_COMPANY)->where('deleted')->eq(0)->fetchAll();

        foreach($admins as $key => $admin) $admins[$key] = trim($admin->admins, ',');

        $admins = implode(',', $admins);

        $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->in($admins)
            ->fetchPairs('account', 'visions');

        foreach($user as $account => $visions)
        {
            if(strpos($visions, 'or') !== false) continue;

            $visions = 'or,' . $visions;
            $this->dao->update(TABLE_USER)->set('visions')->eq($visions)->where('account')->eq($account)->exec();
            $this->saveLogs($this->dao->get());
        }

        include('priv.php');
        foreach($orData as $role => $name)
        {
            $this->dao->insert(TABLE_GROUP)->set('vision')->eq('or')->set('name')->eq($name)->set('role')->eq($role)->set('desc')->eq($name)->exec();
            $this->saveLogs($this->dao->get());
            if(dao::isError()) continue;
            $groupID = $this->dao->lastInsertID();
            $sql     = 'REPLACE INTO' . TABLE_GROUPPRIV . '(`group`, `module`, `method`) VALUES ' . str_replace('GROUPID', $groupID, ${$role . 'Priv'});
            $this->dao->exec($sql);
            $this->saveLogs($sql);
        }
    }

    /**
     * Adjust config section and key.
     *
     * @access public
     * @return bool
     */
    public function adjustConfigSectionAndKey()
    {
        $this->dao->update(TABLE_CONFIG)->set('`key`')->eq('productProject')->where('`key`')->eq('productproject')->andWhere('module')->eq('custom')->exec();

        $this->dao->update(TABLE_CONFIG)->set('section')->eq('bugBrowse')->where('section')->eq('bugbrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('productBrowse')->where('section')->eq('productbrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('projectTask')->where('section')->eq('projecttask')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('testcaseBrowse')->where('section')->eq('testcasebrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('testtaskCases')->where('section')->eq('testtaskcases')->andWhere('module')->eq('datatable')->exec();

        return true;
    }

    /**
     * To lower table.
     *
     * @param  string $build
     * @access public
     * @return bool
     */
    public function toLowerTable($build = 'basic')
    {
        $results    = $this->dao->query("show Variables like '%table_names'")->fetchAll();
        $hasLowered = false;
        foreach($results as $result)
        {
            if(strtolower($result->Variable_name) == 'lower_case_table_names' and $result->Value == 1)
            {
                $hasLowered = true;
                break;
            }
        }
        if($hasLowered) return true;

        if($build == 'basic') $tables2Rename = $this->config->upgrade->lowerTables;
        if($build == 'pro') $tables2Rename = $this->config->upgrade->lowerProTables;
        if(!isset($tables2Rename)) return false;

        $tablesExists = $this->dao->query('SHOW TABLES')->fetchAll();
        foreach($tablesExists as $key => $table) $tablesExists[$key] = current((array)$table);
        $tablesExists = array_flip($tablesExists);

        foreach($tables2Rename as $oldTable => $newTable)
        {
            if(!isset($tablesExists[$oldTable])) continue;

            $upgradebak = $newTable . '_othertablebak';
            if(isset($tablesExists[$upgradebak]))
            {
                $this->dao->query("DROP TABLE `$upgradebak`");
            }
            if(isset($tablesExists[$newTable]))
            {
                $this->dao->query("RENAME TABLE `$newTable` TO `$upgradebak`");
            }

            $tempTable = $oldTable . '_zentaotmp';
            $this->dao->query("RENAME TABLE `$oldTable` TO `$tempTable`");
            $this->dao->query("RENAME TABLE `$tempTable` TO `$newTable`");
        }

        return true;
    }

    /**
     * Process finishedBy and finishedDate of task.
     *
     * @access public
     * @return bool
     */
    public function processTaskFinish()
    {
        $this->dao->update(TABLE_TASK)
            ->set('finishedBy = lastEditedBy')
            ->set('finishedDate = lastEditedDate')
            ->where('status')->in('done,closed')
            ->andWhere('finishedBy')->eq('')
            ->exec();

        return true;
    }

    /**
     * Process bugs which import to project tasks but canceled.
     *
     * @access public
     * @return void
     */
    public function processImport2TaskBugs()
    {
        $bugs = $this->dao->select('t1.id')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.toTask = t2.id')
            ->where('t1.toTask')->ne(0)
            ->andWhere('t1.status')->eq('active')
            ->andWhere('t2.canceledBy')->ne('')
            ->fetchPairs();

        $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->in($bugs)->exec();

        return true;
    }

    /**
     * Delete company field for the table of zt_config and zt_groupPriv.
     *
     * @access public
     * @return void
     */
    public function deleteCompany()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        /* Delete priv that is not in this company. Prevent conflict when delete company's field.*/
        $this->dao->delete()->from($privTable)->where('company')->ne($this->app->company->id)->exec();
        $this->dao->exec("ALTER TABLE " . $privTable . " DROP `company`;");

        /* Delete config that don't conform to the rules. Prevent conflict when delete company's field.*/
        $rows    = $this->dao->select('*')->from(TABLE_CONFIG)->orderBy('id desc')->fetchAll('id');
        $items   = array();
        $delList = array();
        foreach($rows as $config)
        {
            if(isset($items[$config->owner][$config->module][$config->section][$config->key]))
            {
                $delList[] = $config->id;
                continue;
            }

            $items[$config->owner][$config->module][$config->section][$config->key] = $config->id;
        }
        if($delList) $this->dao->delete()->from(TABLE_CONFIG)->where('id')->in($delList)->exec();

        $this->dao->exec("ALTER TABLE " . TABLE_CONFIG . " DROP `company`;");

        return true;
    }

    /**
     * Merge the goal and desc of project.
     *
     * @access public
     * @return void
     */
    public function mergeProjectGoalAndDesc()
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->fetchAll('id');
        foreach($projects as $id => $project)
        {
            if(!isset($project->goal)) continue;

            $this->dao->update(TABLE_PROJECT)
                ->set('`desc`')->eq($project->desc . '<br />' . $project->goal)
                ->where('id')->eq($id)
                ->exec();
        }
        return true;
    }

    /**
     * Fix OS info of bugs.
     *
     * @access public
     * @return void
     */
    public function fixBugOSInfo()
    {
        $this->dao->update(TABLE_BUG)->set('os')->eq('android')->where('os')->eq('andriod')->exec();
        $this->dao->update(TABLE_BUG)->set('os')->eq('osx')->where('os')->eq('mac')->exec();
    }

    /**
     * Fix finishedBy of task.
     *
     * @access public
     * @return void
     */
    public function fixTaskFinishedBy()
    {
        $tasks = $this->dao->select('t1.id,t2.actor,t2.date')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')
            ->on('t1.id = t2.objectID')
            ->leftJoin(TABLE_HISTORY)->alias('t3')
            ->on('t2.id = t3.action')
            ->where('t3.new')->eq(0)
            ->andWhere('t3.field')->eq('left')
            ->andWhere('t2.objectType')->eq('task')
            ->andWhere('t1.finishedBy')->eq('')
            ->andWhere('t1.status')->in('done,closed')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        foreach($tasks as $taskID => $task)
        {
            $this->dao->update(TABLE_TASK)
                ->set('finishedBy')->eq($task->actor)
                ->set('finishedDate')->eq($task->date)
                ->where('id')->eq($taskID)
                ->exec();
        }
    }

    /**
     * Touch index.html for upload when has not it.
     *
     * @access public
     * @return bool
     */
    public function fixDataIndex()
    {
        $savePath = $this->loadModel('file')->savePath;
        foreach(glob($savePath . '*') as $childDir)
        {
            if(is_dir($childDir) and !is_file($childDir . '/index.html')) @touch($childDir . '/index.html');
        }
        return true;
    }

    /**
     * Init order.
     *
     * @access public
     * @return bool
     */
    public function initOrder()
    {
        $dataList = $this->dao->select('id')->from(TABLE_PRODUCT)->orderBy('code_desc')->fetchAll();
        $i = 1;
        foreach($dataList as $data)
        {
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($i++)->where('id')->eq($data->id)->exec();
        }

        $dataList = $this->dao->select('id')->from(TABLE_PROJECT)->orderBy('code_desc')->fetchAll();
        $i = 1;
        foreach($dataList as $data)
        {
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($i++)->where('id')->eq($data->id)->exec();
        }

        return true;
    }

    /**
     * Adjust order for 7.3
     *
     * @access public
     * @return void
     */
    public function adjustOrder7_3()
    {
        $this->loadModel('product')->fixOrder();
        $this->loadModel('execution')->fixOrder();

        return true;
    }

    /**
     * Adjust priv for 7.4.beta
     *
     * @access public
     * @return void
     */
    public function adjustPriv7_4_beta()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('name')->ne('guest')->fetchPairs('id', 'id');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group = $groupID;
            $groupPriv->module = 'my';
            $groupPriv->method = 'unbind';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        return true;
    }

    /**
     * Adjust doc module.
     *
     * @access public
     * @return bool
     */
    public function adjustDocModule()
    {
        $this->app->loadLang('doc');
        $productDocModules = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('productdoc')->orderBy('grade,id')->fetchAll('id');
        $allProductIdList  = $this->dao->select('id,name,acl,whitelist,createdBy')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchAll('id');
        foreach($allProductIdList as $productID => $product)
        {
            $this->dao->delete()->from(TABLE_DOCLIB)->where('product')->eq($productID)->exec();

            $lib = new stdclass();
            $lib->product = $productID;
            $lib->name    = $this->lang->doclib->main['product'];
            $lib->main    = 1;
            $lib->acl     = $product->acl == 'open' ? 'open' : 'custom';
            $lib->users   = $product->createdBy;
            if($product->acl == 'custom') $lib->groups = $product->whitelist;
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            $libID = $this->dao->lastInsertID();

            $relation = array();
            foreach($productDocModules as $moduleID => $module)
            {

                unset($module->id);
                $module->root = $libID;
                $module->type = 'doc';
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();

                $newModuleID = $this->dao->lastInsertID();
                $relation[$moduleID] = $newModuleID;
                $newPaths = array();
                foreach(explode(',', trim($module->path, ',')) as $path)
                {
                    if(isset($relation[$path])) $newPaths[] = $relation[$path];
                }

                $newPaths = join(',', $newPaths);
                $this->dao->update(TABLE_MODULE)->set('path')->eq($newPaths)->set('parent')->eq($relation[$module->parent])->where('id')->eq($newModuleID)->exec();
                $this->dao->update(TABLE_DOC)->set('module')->eq($newModuleID)->where('product')->eq($productID)->andWhere('module')->eq($moduleID)->andWhere('lib')->eq('product')->exec();
            }
            $this->dao->update(TABLE_DOC)->set('lib')->eq($libID)->where('product')->eq($productID)->exec();
        }
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->in(array_keys($productDocModules))->exec();

        $projectDocModules = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('projectdoc')->orderBy('grade,id')->fetchAll('id');
        $allProjectIdList  = $this->dao->select('id,name,acl,whitelist')->from(TABLE_PROJECT)->where('deleted')->eq('0')->fetchAll('id');
        foreach($allProjectIdList as $projectID => $project)
        {
            $this->dao->delete()->from(TABLE_DOCLIB)->where('project')->eq($projectID)->exec();

            $lib = new stdclass();
            $lib->project = $projectID;
            $lib->name    = $this->lang->doclib->main['project'];
            $lib->main    = 1;
            $lib->acl     = $project->acl == 'open' ? 'open' : 'custom';

            $teams = $this->dao->select('project, account')->from(TABLE_TEAM)->where('project')->eq($projectID)->fetchPairs('account', 'account');
            $lib->users = join(',', $teams);
            if($project->acl == 'custom') $lib->groups = $project->whitelist;
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            $libID = $this->dao->lastInsertID();

            $docLibs = $this->dao->select('id,users')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.product=t2.product')
                ->where('t2.project')->eq($projectID)
                ->andWhere('t1.acl')->eq('custom')
                ->fetchAll('id');
            foreach($docLibs as $lib)
            {
                $docUsers = $teams + explode(',', $lib->users);
                $docUsers = array_unique($docUsers);
                $this->dao->update(TABLE_DOCLIB)->set('users')->eq(join(',', $docUsers))->where('id')->eq($lib->id)->exec();
            }

            $relation = array();
            foreach($projectDocModules as $moduleID => $module)
            {
                unset($module->id);
                $module->root = $libID;
                $module->type = 'doc';
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();

                $newModuleID = $this->dao->lastInsertID();
                $relation[$moduleID] = $newModuleID;
                $newPaths = array();
                foreach(explode(',', trim($module->path, ',')) as $path)
                {
                    if(isset($relation[$path])) $newPaths[] = $relation[$path];
                }

                $newPaths = join(',', $newPaths);
                $newPaths = ",$newPaths,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($newPaths)->where('id')->eq($newModuleID)->exec();
                $this->dao->update(TABLE_DOC)->set('module')->eq($newModuleID)->where('project')->eq($projectID)->andWhere('module')->eq($moduleID)->exec();
            }
            $this->dao->update(TABLE_DOC)->set('lib')->eq($libID)->where('project')->eq($projectID)->exec();
        }
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->in(array_keys($projectDocModules))->exec();

        return true;
    }

    /**
     * Update file objectID in editor.
     *
     * @access public
     * @return bool
     */
    public function updateFileObjectID($type = '', $lastID = 0)
    {
        $limit = 100;
        if(empty($type)) $type = 'comment';
        $result['type']   = $type;
        $result['lastID'] = 0;
        if($type == 'comment')
        {
            $comments = $this->dao->select('id,objectType,objectID,comment')->from(TABLE_ACTION)->where('comment')->like('%data/upload/%')->andWhere('id')->gt($lastID)->orderBy('id')->limit($limit)->fetchAll('id');
            foreach($comments as $action)
            {
                $files = array();
                preg_match_all('/"data\/upload\/.*1\/([0-9]{6}\/[^"]+)"/', $action->comment, $output);
                foreach($output[1] as $path)$files[$path] = $path;
                $this->dao->update(TABLE_FILE)->set('objectType')->eq($action->objectType)->set('objectID')->eq($action->objectID)->set('extra')->eq('editor')->where('pathname')->in($files)->exec();
            }
            if(count($comments) < $limit)
            {
                $result['type']   = 'doc';
                $result['count']  = count($comments);
                $result['lastID'] = 0;
            }
            else
            {
                $result['type']   = 'comment';
                $result['count']  = count($comments);
                $result['lastID'] = $action->id;
            }
            return $result;
        }

        $editors['doc']         = array('table' => TABLE_DOCCONTENT,  'fields' => 'doc,`content`,`digest`');
        $editors['project']     = array('table' => TABLE_PROJECT,     'fields' => 'id,`desc`');
        $editors['bug']         = array('table' => TABLE_BUG,         'fields' => 'id,`steps`');
        $editors['release']     = array('table' => TABLE_RELEASE,     'fields' => 'id,`desc`');
        $editors['productplan'] = array('table' => TABLE_PRODUCTPLAN, 'fields' => 'id,`desc`');
        $editors['product']     = array('table' => TABLE_PRODUCT,     'fields' => 'id,`desc`');
        $editors['story']       = array('table' => TABLE_STORYSPEC,   'fields' => 'story,`spec`,`verify`');
        $editors['testtask']    = array('table' => TABLE_TESTTASK,    'fields' => 'id,`desc`,`report`');
        $editors['todo']        = array('table' => TABLE_TODO,        'fields' => 'id,`desc`');
        $editors['task']        = array('table' => TABLE_TASK,        'fields' => 'id,`desc`');
        $editors['build']       = array('table' => TABLE_BUILD,       'fields' => 'id,`desc`');

        $editor = $editors[$type];
        $fields = explode(',', $editor['fields']);
        $cond   = array();
        foreach($fields as $field)
        {
            if(strpos($field, '`') !== false) $cond[]  = $field . " like '%data/upload/%'";
            if(strpos($field, '`') === false) $idField = $field;
        }
        $objects = $this->dao->select($editor['fields'])->from($editor['table'])
            ->where($idField)->gt($lastID)
            ->beginIF($cond)->andWhere('(' . join(' OR ', $cond) . ')')->fi()
            ->orderBy($idField)
            ->limit($limit)
            ->fetchAll($idField);
        foreach($objects as $object)
        {
            $files    = array();
            $objectID = 0;
            foreach($fields as $field)
            {
                if(strpos($field, '`') === false)
                {
                    $objectID = $object->$field;
                }
                else
                {
                    $field = trim($field, '`');
                    preg_match_all('/"\/?data\/upload\/.*1\/([0-9]{6}\/[^"]+)"/', $object->$field, $output);
                    foreach($output[1] as $path)$files[$path] = $path;
                }
            }
            if($files) $this->dao->update(TABLE_FILE)->set('objectType')->eq($type)->set('objectID')->eq($objectID)->set('extra')->eq('editor')->where('pathname')->in($files)->exec();
        }
        if(count($objects) < $limit)
        {
            $editorKeys = array_keys($editors);
            foreach($editorKeys as $i => $objectType)
            {
                if($type == $objectType)
                {
                    $nextType = isset($editorKeys[$i + 1]) ? $editorKeys[$i + 1] : '';
                    break;
                }
            }
            $result['type']   = empty($nextType) ? 'finish' : $nextType;
            $result['count']  = count($objects);
            $result['lastID'] = 0;
        }
        else
        {
            $result['type']   = $type;
            $result['count']  = count($objects);
            $result['lastID'] = $object->$idField;
        }
        return $result;
    }

    /**
     * Move doc content to table zt_doccontent.
     *
     * @access public
     * @return bool
     */
    public function moveDocContent()
    {
        $descDoc = $this->dao->query('DESC ' .  TABLE_DOC)->fetchAll();
        $processFields = 0;
        foreach($descDoc as $field)
        {
            if($field->Field == 'content' or $field->Field == 'digest' or $field->Field == 'url') $processFields ++;
        }
        if($processFields < 3) return true;

        $this->dao->exec('TRUNCATE TABLE ' . TABLE_DOCCONTENT);
        $stmt = $this->dao->select('id,title,digest,content,url')->from(TABLE_DOC)->query();
        $fileGroups = $this->dao->select('id,objectID')->from(TABLE_FILE)->where('objectType')->eq('doc')->fetchGroup('objectID', 'id');
        while($doc = $stmt->fetch())
        {
            $url = empty($doc->url) ? '' : urldecode($doc->url);
            $docContent = new stdclass();
            $docContent->doc      = $doc->id;
            $docContent->title    = $doc->title;
            $docContent->digest   = $doc->digest;
            $docContent->content  = $doc->content;
            $docContent->content .= empty($url) ? '' : $url;
            $docContent->version  = 1;
            $docContent->type     = 'html';
            if(isset($fileGroups[$doc->id])) $docContent->files = join(',', array_keys($fileGroups[$doc->id]));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `digest`');
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `content`');
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `url`');
        return true;
    }

    /**
     * Adjust priv 8.3
     *
     * @access public
     * @return bool
     */
    public function adjustPriv8_3()
    {
        $docPrivGroups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('doc')->andWhere('method')->eq('index')->fetchPairs('group', 'group');
        foreach($docPrivGroups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'doc';
            $data->method = 'allLibs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'showFiles';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'objectLibs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Rename main lib.
     *
     * @access public
     * @return bool
     */
    public function renameMainLib()
    {
        $this->app->loadLang('doc');
        $this->dao->update(TABLE_DOCLIB)->set('name')->eq($this->lang->doclib->main['product'])->where('product')->gt(0)->andWhere('main')->eq(1)->exec();
        $this->dao->update(TABLE_DOCLIB)->set('name')->eq($this->lang->doclib->main['project'])->where('project')->gt(0)->andWhere('main')->eq(1)->exec();
        return true;
    }

    /**
     * Adjust priv for 8.4.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv8_4()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('branch')->andWhere('method')->eq('manage')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'branch';
            $data->method = 'sort';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('story')->andWhere('method')->eq('tasks')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'story';
            $data->method = 'bugs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'cases';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Adjust priv for 9.0
     *
     * @access public
     * @return void
     */
    public function adjustPriv9_0()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testtask')->andWhere('method')->eq('results')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'testcase';
            $data->method = 'bugs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('mail')->andWhere('method')->eq('delete')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'mail';
            $data->method = 'resend';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Fix projectproduct data.
     *
     * @access public
     * @return bool
     */
    public function fixProjectProductData()
    {
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('product')->eq(0)->exec();
        return true;
    }

    /**
     * Add bug deadline for custom fields.
     *
     * @access public
     * @return bool
     */
    public function addBugDeadlineToCustomFields()
    {
        $createFieldsItems = $this->dao->select('id, value')->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('section')->eq('custom')
            ->andWhere('`key`')->eq('createFields')
            ->fetchAll();
        $batchEditFieldsItems = $this->dao->select('id, value')->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('section')->eq('custom')
            ->andWhere('`key`')->eq('batchEditFields')
            ->fetchAll();

        foreach($createFieldsItems as $createFieldsItem)
        {
            $value = empty($createFieldsItem->value) ? 'deadline' : $createFieldsItem->value . ",deadline";
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($value)->where('id')->eq($createFieldsItem->id)->exec();
        }
        foreach($batchEditFieldsItems as $batchEditFieldsItem)
        {
            $value = empty($batchEditFieldsItem->value) ? 'deadline' : $batchEditFieldsItem->value . ",deadline";
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($value)->where('id')->eq($batchEditFieldsItem->id)->exec();
        }

        return true;
    }

    /**
     * Adjust priv for 9.0.1.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv9_0_1()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testcase')->andWhere('method')->eq('edit')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testcase';
            $newMethods   = array('review', 'batchReview', 'batchCaseTypeChange', 'batchConfirmStoryChange');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }

            $data->module = 'testsuite';
            $newMethods   = array('create', 'edit', 'delete', 'linkCase', 'unlinkCase', 'batchUnlinkCases');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testtask')->andWhere('method')->eq('start')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testtask';
            $newMethods   = array('activate', 'block', 'report');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('distinct `group`')->from(TABLE_GROUPPRIV)->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testsuite';
            $newMethods   = array('index', 'browse', 'view');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return true;
    }

    /**
     * Adjust priv for 9.2.
     *
     * @access public
     * @return void
     */
    public function adjustPriv9_2()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testsuite')->andWhere('method')->eq('createCase')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testsuite';
            $newMethods   = array('batchCreateCase', 'exportTemplate', 'import', 'showImport');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('product')->andWhere('method')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'product';
            $data->method = 'build';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('custom')->andWhere('method')->eq('flow')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'custom';
            $data->method = 'working';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Adjust priv for 9.4.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv9_4()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('bug')->andWhere('method')->eq('activate')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'bug';
            $data->method = 'batchActivate';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Adjust priv for 11.4.
     *
     * @access public
     * @return bool
     */
    public function addPriv11_4()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('story')->andWhere('method')->eq('edit')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'story';
            $data->method = 'assignTo';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Add Priv for 11.5
     *
     * @access public
     * @return bool
     */
    public function addPriv11_5()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('bug')->andWhere('method')->eq('setPublic')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'user';
            $data->method = 'setPublicTemplate';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Add unique key for stage.
     *
     * @access public
     * @return bool
     */
    public function addUniqueKey4Stage()
    {
        $stmt     = $this->dao->select('story,branch')->from(TABLE_STORYSTAGE)->orderBy('story,branch')->query();
        $preStage = '';
        while($stage = $stmt->fetch())
        {
            if($preStage == "{$stage->story}_{$stage->branch}") $this->dao->delete()->from(TABLE_STORYSTAGE)->where('story')->eq($stage->story)->andWhere('branch')->eq($stage->branch)->exec();
            $preStage = "{$stage->story}_{$stage->branch}";
        }
        $this->dao->exec("ALTER TABLE " . TABLE_STORYSTAGE . " ADD UNIQUE `story_branch` (`story`, `branch`)");
        return true;
    }

    /**
     * Judge any error occurs.
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty(static::$errors);
    }

    /**
     * Get errors during the upgrading.
     *
     * @access public
     * @return array
     */
    public function getError()
    {
        $errors = static::$errors;
        static::$errors = array();
        return $errors;
    }

    /**
     * Get upgrade log file.
     *
     * @access public
     * @return string
     */
    public function getConsistencyLogFile()
    {
        return $this->app->getTmpRoot() . 'log/consistency.' . date('Ymd') . '.log.php';
    }

    /**
     * Get upgrade log file.
     *
     * @access public
     * @return string
     */
    public function getLogFile()
    {
        return $this->app->getTmpRoot() . 'log/upgrade.' . date('Ymd') . '.log.php';
    }

    /**
     * Check safe file.
     *
     * @access public
     * @return string|false
     */
    public function checkSafeFile()
    {
        return $this->loadModel('common')->checkSafeFile();
    }

    /**
     * Check weither process or not.
     *
     * @access public
     * @return array
     */
    public function checkProcess()
    {
        $fromVersion = $this->config->installedVersion;
        $needProcess = array();
        if(strpos($fromVersion, 'ipd') !== false) return $needProcess;
        if(strpos($fromVersion, 'max') === false and strpos($fromVersion, 'biz') === false and (strpos($fromVersion, 'pro') === false ? version_compare($fromVersion, '8.3', '<') : version_compare($fromVersion, 'pro5.4', '<'))) $needProcess['updateFile'] = 'process';
        if(strpos($fromVersion, 'max') === false and $this->config->systemMode == 'new')
        {
            if(strpos($fromVersion, 'pro') !== false)
            {
                if(version_compare($fromVersion, 'pro10.0', '<')) $needProcess['search'] = 'notice';
            }
            elseif(strpos($fromVersion, 'biz') !== false)
            {
                if(version_compare($fromVersion, 'biz5.0', '<')) $needProcess['search'] = 'notice';
            }
            elseif(version_compare($fromVersion, '15.0.rc1', '<'))
            {
                $needProcess['search'] = 'notice';
            }
        }

        $openVersion = $this->getOpenVersion(str_replace('.', '_', $fromVersion));
        if(version_compare($openVersion, '17_4', '<=')) $needProcess['changeEngine'] = 'notice';

        return $needProcess;
    }

    /**
     * Process customMenus for different working.
     *
     * @access public
     * @return void
     */
    public function processCustomMenus()
    {
        $this->loadModel('setting')->setItem('system.common.global.flow', 'full');
        $customMenus = $this->dao->select('*')->from(TABLE_CONFIG)->where('section')->eq('customMenu')->fetchAll();

        foreach($customMenus as $customMenu)
        {
            $this->dao->update(TABLE_CONFIG)->set('`key`')->eq("full_{$customMenu->key}")->where('id')->eq($customMenu->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Init project story order.
     *
     * @access public
     * @return bool
     */
    public function initProjectStoryOrder()
    {
        $storyGroup = $this->dao->select('t1.*')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->orderBy('t2.pri_desc,t1.story_asc')
            ->fetchGroup('project', 'story');

        foreach($storyGroup as $projectID => $stories)
        {
            $order = 1;
            foreach($stories as $storyID => $projectStory)
            {
                $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('project')->eq($projectID)->andWhere('story')->eq($storyID)->exec();
                $order++;
            }
        }
        return true;
    }

    /**
     * Fix datatable cols config.
     *
     * @access public
     * @return bool
     */
    public function fixDatatableColsConfig()
    {
        $config = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('module')->eq('datatable')
            ->andWhere('section')->eq('projectTask')
            ->andWhere('`key`')->eq('cols')
            ->fetchAll('id');

        foreach($config as $datatableCols)
        {
            $cols = json_decode($datatableCols->value);
            foreach($cols as $i => $col)
            {
                if($col->id == 'progess') $col->id = 'progress';
                if($col->id == 'actions' and $col->width == 'auto') $col->width =  '180px';
            }
            $this->dao->update(TABLE_CONFIG)->set('value')->eq(json_encode($cols))->where('id')->eq($datatableCols->id)->exec();
        }

        return true;
    }

    /**
     * Add limited group.
     *
     * @access public
     * @return bool
     */
    public function addLimitedGroup()
    {
        $limitedGroup = $this->dao->select('*')->from(TABLE_GROUP)->where('`role`')->eq('limited')->fetch();
        if(empty($limitedGroup))
        {
            $group = new stdclass();
            $group->name = 'limited';
            $group->role = 'limited';
            $group->desc = 'For limited user';
            $this->dao->insert(TABLE_GROUP)->data($group)->exec();

            $groupID = $this->dao->lastInsertID();
        }
        else
        {
            $groupID = $limitedGroup->id;
        }

        $limitedGroups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)
            ->where('module')->eq('my')
            ->andWhere('method')->eq('limited')
            ->fetchPairs('group', 'group');
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq('my')->andWhere('method')->eq('limited')->exec();

        $limitedUsers = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($limitedGroups)->fetchPairs('account', 'account');
        foreach($limitedUsers as $limitedUser)
        {
            $this->dao->replace(TABLE_USERGROUP)->set('account')->eq($limitedUser)->set('`group`')->eq($groupID)->exec();
        }

        $groupPriv = new stdclass();
        $groupPriv->group = $groupID;
        $groupPriv->module = 'my';
        $groupPriv->method = 'limited';
        $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

        return true;
    }

    /**
     * Change limited name.
     *
     * @access public
     * @return bool
     */
    public function changeLimitedName()
    {
        $this->app->loadLang('install');
        $this->dao->update(TABLE_GROUP)->set('name')->eq($this->lang->install->groupList['LIMITED']['name'])
            ->set('desc')->eq($this->lang->install->groupList['LIMITED']['desc'])
            ->where('role')->eq('limited')
            ->exec();

        return true;
    }

    /**
     * Adjust Priv for 9.7
     *
     * @access public
     * @return bool
     */
    public function adjustPriv9_7()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('method')->eq('edit')->andWhere('module')->in('story,task,bug,testcase')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'action';
            $groupPriv->method = 'comment';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Change story field width.
     *
     * @access public
     * @return bool
     */
    public function changeStoryWidth()
    {
        $projectCustom = $this->dao->select('*')->from(TABLE_CONFIG)->where('section')->eq('projectTask')->andWhere('`key`')->in('cols,tablecols')->fetchAll('id');
        foreach($projectCustom as $configID => $projectTask)
        {
            $fields = json_decode($projectTask->value);
            foreach($fields as $i => $field)
            {
                if($field->id == 'story') $field->width = '40px';
            }
            $this->dao->update(TABLE_CONFIG)->set('value')->eq(json_encode($fields))->where('id')->eq($configID)->exec();
        }
        return true;
    }

    /**
     * Change team field for 9.8.
     *
     * @access public
     * @return bool
     */
    public function changeTeamFields()
    {
        $desc   = $this->dao->query('DESC ' . TABLE_TEAM)->fetchAll();
        $fields = array();
        foreach($desc as $field)
        {
            $fieldName = $field->Field;
            $fields[$fieldName] = $fieldName;
        }
        if(isset($fields['root'])) return true;

        $this->dao->exec("ALTER TABLE " . TABLE_TEAM . " CHANGE `project` `root` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0'");
        $this->dao->exec("ALTER TABLE " . TABLE_TEAM . " ADD `type` ENUM('project', 'task') NOT NULL DEFAULT 'project' AFTER `root`");
        $this->dao->exec("UPDATE " . TABLE_TEAM . " SET `root` = `task`, `type` = 'task' WHERE `task` > '0'");
        $this->dao->exec("ALTER TABLE " . TABLE_TEAM . " DROP PRIMARY KEY");
        $this->dao->exec("ALTER TABLE " . TABLE_TEAM . " DROP `task`");
        return true;
    }

    /**
     * Move data to notify.
     *
     * @access public
     * @return bool
     */
    public function moveData2Notify()
    {
        $this->dao->exec('TRUNCATE TABLE ' . TABLE_NOTIFY);

        $mailQueueTable = '`' . $this->config->db->prefix . 'mailqueue`';
        $syncBeginDate  = date('Y-m-d', time() - 15 * 24 * 3600);
        $stmt           = $this->dao->select('*')->from($mailQueueTable)->where('addedDate')->ge($syncBeginDate)->orderBy('id')->query();
        while($mailQueue = $stmt->fetch())
        {
            $notify = new stdclass();
            $notify->objectType  = 'mail';
            $notify->toList      = $mailQueue->toList;
            $notify->ccList      = $mailQueue->ccList;
            $notify->subject     = $mailQueue->subject;
            $notify->data        = $mailQueue->body;
            $notify->createdBy   = $mailQueue->addedBy;
            $notify->createdDate = $mailQueue->addedDate;
            $notify->sendTime    = $mailQueue->sendTime;
            $notify->status      = $mailQueue->status;
            $notify->failReason  = $mailQueue->failReason;
            $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
        }

        $webhookDataTable = '`' . $this->config->db->prefix . 'webhookdatas`';
        $stmt = $this->dao->select('*')->from($webhookDataTable)->orderBy('id')->limit($offset, $rows)->query();
        while($webhookData = $stmt->fetch())
        {
            $notify = new stdclass();
            $notify->objectType  = 'webhook';
            $notify->objectID    = $webhookData->webhook;
            $notify->action      = $webhookData->action;
            $notify->data        = $webhookData->data;
            $notify->createdBy   = $webhookData->createdBy;
            $notify->createdDate = $webhookData->createdDate;
            $notify->status      = $webhookData->status;
            $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
        }

        return true;
    }

    /**
     * Adjust priv 9.8.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv9_8()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->fetchPairs('id', 'id');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'todo';
            $groupPriv->method = 'createcycle';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('mail')->orWhere('module')->eq('webhook')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'message';
            $groupPriv->method = 'index';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('project')->andWhere('method')->eq('linkStory')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'project';
            $groupPriv->method = 'importPlanStories';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Fix task finishedBy.
     *
     * @access public
     * @return bool
     */
    public function fixTaskFinishedInfo()
    {
        $stmt = $this->dao->select('t1.id as historID,t2.objectType,t2.objectID,t2.actor')->from(TABLE_HISTORY)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on('t1.action=t2.id')
            ->where('t1.field')->eq('finishedBy')
            ->andWhere('t2.objectType')->eq('task')
            ->andWhere('t2.action')->eq('finished')
            ->andWhere('t2.actor != t1.`new`')
            ->query();
        while($action = $stmt->fetch())
        {
            $this->dao->update(TABLE_HISTORY)->set('`new`')->eq($action->actor)->where('id')->eq($action->historID)->exec();
            $this->dao->update(TABLE_TASK)->set('`finishedBy`')->eq($action->actor)->where('id')->eq($action->objectID)->exec();
        }
        return true;
    }

    /**
     * Fix assignedTo for closed tasks, but assignedTo is not closed.
     *
     * @access public
     * @return bool
     */
    public function fixTaskAssignedTo()
    {
        $minParent = $this->dao->select('parent')->from(TABLE_TASK)->where('parent')->ne(0)->orderBy('parent')->limit(1)->fetch();
        if(empty($minParent)) return true;

        $needUpdateTasks = $this->dao->select('id,parent,closedBy')->from(TABLE_TASK)
            ->where('status')->eq('closed')
            ->andWhere('assignedTo')->ne('closed')
            ->andWhere('id')->ge($minParent)
            ->fetchAll('id');
        if(empty($needUpdateTasks)) return true;

        $needUpdateParentTasks = array();
        $needUpdateChildTasks  = array();
        foreach($needUpdateTasks as $taskID => $task)
        {
            if(!$task->parent)
            {
                $needUpdateParentTasks[$taskID] = $task;
            }
            else
            {
                if(!isset($needUpdateChildTasks[$task->parent])) $needUpdateChildTasks[$task->parent] = array();
                $needUpdateChildTasks[$task->parent][$taskID] = $task;
            }
        }

        /* Update parent task.*/
        $childTasks     = $this->dao->select('id,parent,assignedDate,closedBy,closedDate,closedReason')->from(TABLE_TASK)->where('parent')->in(array_keys($needUpdateParentTasks))->fetchGroup('parent');
        $lastChildTasks = array();
        foreach($childTasks as $parentID => $tasks)
        {
            foreach($tasks as $task)
            {
                if(isset($lastChildTasks[$parentID]))
                {
                    if($lastChildTasks[$parentID]->closedDate < $task->closedDate) $lastChildTasks[$parentID] = $task;
                }
                else
                {
                    $lastChildTasks[$parentID] = $task;
                }
            }
        }

        foreach($needUpdateParentTasks as $parentTask)
        {
            $lastChild = isset($lastChildTasks[$parentTask->id]) ? $lastChildTasks[$parentTask->id] : '';

            $stmt = $this->dao->update(TABLE_TASK)->set('assignedTo')->eq('closed');
            if($lastChild) $stmt->set('assignedDate')->eq($lastChild->assignedDate);
            $stmt->where('id')->eq($parentTask->id)->exec();

            if(empty($parentTask->closedBy) && !empty($lastChild->closedBy))
            {
                $this->dao->update(TABLE_TASK)->set('closedBy')->eq($lastChild->closedBy)->set('closedDate')->eq($lastChild->closedDate)->set('closedReason')->eq($lastChild->closedReason)->where('id')->eq($parentTask->id)->exec();
            }
        }

        /* Update children task.*/
        $parentTasks = $this->dao->select('id,assignedDate,closedBy,closedDate,closedReason')->from(TABLE_TASK)
            ->where('parent')->eq(0)
            ->andWhere('id')->in(array_keys($needUpdateChildTasks))
            ->fetchAll('id');

        foreach($needUpdateChildTasks as $parentID => $childTasks)
        {
            $parent = isset($parentTasks[$parentID]) ? $parentTasks[$parentID] : '';

            foreach($childTasks as $childTask)
            {
                $stmt = $this->dao->update(TABLE_TASK)->set('assignedTo')->eq('closed');
                if(!empty($parent)) $stmt->set('assignedDate')->eq($parent->assignedDate);
                $stmt->where('id')->eq($childTask->id)->exec();

                if(empty($childTask->closedBy) && !empty($parent->closedBy))
                {
                    $this->dao->update(TABLE_TASK)->set('closedBy')->eq($parent->closedBy)->set('closedDate')->eq($parent->closedDate)->set('closedReason')->eq($parent->closedReason)->where('id')->eq($childTask->id)->exec();
                }
            }
        }

        return dao::isError();
    }

    /**
     * Fix project closedBy and closedDate.
     *
     * @access public
     * @return bool
     */
    public function fixProjectClosedInfo()
    {
        $stmt = $this->dao->select('t1.id as historID, t2.id, t2.objectType,t2.objectID,t2.actor,t2.date')->from(TABLE_HISTORY)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on('t1.action=t2.id')
            ->where('t1.field')->eq('status')
            ->andWhere('t2.objectType')->eq('project')
            ->andWhere('t2.action')->eq('closed')
            ->query();

        while($action = $stmt->fetch())
        {
            $this->dao->insert(TABLE_HISTORY)->set('`new`')->eq($action->actor)->set('`field`')->eq('closedBy')->set('`action`')->eq($action->id)->exec();
            $this->dao->insert(TABLE_HISTORY)->set('`new`')->eq($action->date)->set('`old`')->eq('0000-00-00 00:00:00')->set('`field`')->eq('closedDate')->set('`action`')->eq($action->id)->exec();
            $this->dao->update(TABLE_HISTORY)->set('`new`')->eq('closed')->where('`action`')->eq($action->id)->andWhere('field')->eq('status')->exec();
            $this->dao->update(TABLE_PROJECT)
                ->set('`status`')->eq('closed')
                ->set('`closedBy`')->eq($action->actor)
                ->set('`closedDate`')->eq($action->date)
                ->where('id')->eq($action->objectID)
                ->andWhere('status')->eq('done')
                ->exec();
        }
        return !dao::isError();
    }

    /**
     * Set the value of deleted product line to 0.
     *
     * @access public
     * @return bool
     */
    public function resetProductLine()
    {
        $deletedLines = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq('1')->fetchPairs('id', 'id');
        $this->dao->update(TABLE_PRODUCT)->set('line')->eq(0)->where('line')->in($deletedLines)->exec();
        return !dao::isError();
    }

    /**
     * Add unique key to team table.
     *
     * @access public
     * @return bool
     */
    public function addUniqueKeyToTeam()
    {
        $members = $this->dao->select('root, type, account')->from(TABLE_TEAM)->groupBy('root, type, account')->having('count(*)')->gt(1)->fetchAll();

        foreach($members as $member)
        {
            $maxID = $this->dao->select('MAX(id) id')
                ->from(TABLE_TEAM)
                ->where('root')->eq($member->root)
                ->andWhere('`type`')->eq($member->type)
                ->andWhere('account')->eq($member->account)
                ->fetch('id');
            $this->dao->delete()->from(TABLE_TEAM)
                ->where('root')->eq($member->root)
                ->andWhere('`type`')->eq($member->type)
                ->andWhere('account')->eq($member->account)
                ->andWhere('id')->ne($maxID)
                ->exec();
        }
        $this->dao->exec("ALTER TABLE " . TABLE_TEAM . " ADD UNIQUE `team` (`root`, `type`, `account`)");
        return !dao::isError();
    }

    /**
     * Adjust priv for 10_0_alpha.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv10_0_alpha()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('my')->andWhere('method')->eq('todo')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'my';
            $groupPriv->method = 'calendar';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Fix project statistic block.
     *
     * @access public
     * @return void
     */
    public function fixProjectStatisticBlock()
    {
        $block = $this->dao->select('*')->from(TABLE_BLOCK)->where('module')->eq('my')->andWhere('source')->eq('project')->andWhere('block')->eq('statistic')->fetch();
        if($block)
        {
            $blockParams = json_decode($block->params);
            if($blockParams->type == 'noclosed')
            {
                $blockParams->type = 'undone';
                $this->dao->update(TABLE_BLOCK)->set('params')->eq(helper::jsonEncode($blockParams))->where('id')->eq($block->id)->exec();
                return !dao::isError();
            }
        }
        return true;
    }

    /**
     * Fix story spec title.
     *
     * @access public
     * @return bool
     */
    public function fixStorySpecTitle()
    {
        $stories = $this->dao->select('t1.id, t1.title')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story && t1.title != t2.title && t1.version = t2.version')
            ->where('t2.version')->eq(1)
            ->fetchPairs('id', 'title');

        foreach($stories as $story => $title)
        {
            $this->dao->update(TABLE_STORYSPEC)->set('title')->eq($title)->where('story')->eq($story)->andWhere('version')->eq(1)->exec();
        }

        return !dao::isError();
    }

    /**
     * Remove unlink privilege for story, bug and testcase module.
     *
     * @access public
     * @return bool
     */
    public function removeUnlinkPriv()
    {
        $this->dao->delete()->from(TABLE_GROUPPRIV)
            ->where('((module')->eq('story')
            ->andWhere('method')->eq('unlinkStory')
            ->markRight(1)
            ->orWhere('(module')->eq('bug')
            ->andWhere('method')->eq('unlinkBug')
            ->markRight(1)
            ->orWhere('(module')->eq('testcase')
            ->andWhere('method')->eq('unlinkCase')
            ->markRight(2)
            ->exec();

        return !dao::isError();
    }

    /**
     * Change task parent to -1 for 10.4 .
     * @return bool
     */
    public function changeTaskParentValue()
    {
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->gt(0)->fetchGroup('parent');
        if($tasks)
        {
            $this->dao->update(TABLE_TASK)->set('parent')->eq('-1')->where('id')->in(array_keys($tasks))->exec();
        }
        return !dao::isError();
    }

    /**
     * Remove custom menu.
     *
     * @access public
     * @return bool
     */
    public function removeCustomMenu()
    {
        $customMenuMain = $this->dao->select('*')->from(TABLE_CONFIG)->where('module')->eq('common')->andWhere('section')->eq('customMenu')->andWhere("(`key`='full_main' OR `key`='onlyTask_main' OR `key`='onlyStory_main' OR `key`='onlyTest_main')")->fetchAll('id');
        foreach($customMenuMain as $mainMenu)
        {
            $mainMenuValue = json_decode($mainMenu->value);
            foreach($mainMenuValue as $menu)
            {
                /* If has admin in custom value, then delete old custom menu config. */
                if($menu->name == 'admin')
                {
                    $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('common')
                        ->andWhere('section')->eq('customMenu')
                        ->andWhere('owner')->eq($mainMenu->owner)
                        ->exec();
                }
            }
        }

        $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('common')->andWhere('section')->eq('customMenu')->andWhere('`key`')->eq('full_project')->exec();
        $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('common')->andWhere('section')->eq('customMenu')->andWhere('`key`')->eq('onlyTask_project')->exec();
        return !dao::isError();
    }

    /**
     * Init user view.
     *
     * @access public
     * @return bool
     */
    public function initUserView()
    {
        $this->dao->delete()->from(TABLE_USERVIEW)->exec();
        return true;
    }

    /**
     * Init Xuanxuan.
     *
     * @access public
     * @return bool
     */
    public function initXuanxuan()
    {
        $this->loadModel('setting');
        $keyID = $this->dao->select('id')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('xuanxuan')->andWhere('`key`')->eq('key')->fetch('id');
        if($keyID)
        {
            $existKey = $this->dao->select('id')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('xuanxuan')->andWhere('`key`')->eq('key')->fetch('id');
            if($existKey) $this->dao->delete()->from(TABLE_CONFIG)->where('id')->eq($existKey)->exec();

            $this->dao->update(TABLE_CONFIG)->set('module')->eq('common')->set('section')->eq('xuanxuan')->where('id')->eq($keyID)->exec();
            $this->setting->setItem('system.common.xuanxuan.turnon', '0');
            $this->setting->setItem('system.common.xxserver.noticed', '1');
        }

        return true;
    }

    /**
     * Process doc lib acl.
     *
     * @access public
     * @return void
     */
    public function processDocLibAcl()
    {
        $this->dao->update(TABLE_DOCLIB)->set('acl')->eq('default')->where('type')->in('product,project')->andWhere('acl')->in('open,private')->exec();
        return !dao::isError();
    }

    /**
     * Update xuanxuan for 11_5.
     *
     * @access public
     * @return bool
     */
    public function updateXX_11_5()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('admin')->andWhere('method')->eq('xuanxuan')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group = $groupID;
            $groupPriv->module = 'setting';
            $groupPriv->method = 'xuanxuan';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        try
        {
            $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('setting')->where('module')->eq('admin')->andWhere('method')->eq('downloadxxd')->exec();
        }
        catch(PDOException $e){}
        return true;
    }

    /**
     * Adjust webhook type list when webhook use bearychat.
     *
     * @access public
     * @return void
     */
    public function adjustWebhookType()
    {
        $bearychatCount = $this->dao->select('count(*) as count')->from(TABLE_WEBHOOK)->where('type')->eq('bearychat')->fetch('count');
        if($bearychatCount)
        {
            $item = new stdclass();
            $item->module  = 'webhook';
            $item->section = 'typeList';

            foreach(array('zh-cn', 'zh-tw', 'en', 'de') as $currentLang)
            {
                $langFile = $this->app->getModuleRoot() . 'webhook' . DS . 'lang' . DS . $currentLang . '.php';
                if(!file_exists($langFile)) continue;

                $lang = new stdclass();
                $lang->webhook       = new stdclass();
                $lang->productCommon = $this->config->productCommonList[$currentLang][0];
                $lang->executionCommon = $this->config->executionCommonList[$currentLang][0];

                include $langFile;
                if(!isset($lang->webhook->typeList)) continue;

                $item->lang  = $currentLang;
                $item->key   = 'bearychat';
                $item->value = $this->config->upgrade->bearychat[$currentLang];
                $this->dao->replace(TABLE_LANG)->data($item)->exec();

                foreach($lang->webhook->typeList as $typeKey => $typeName)
                {
                    if(empty($typeKey)) continue;
                    $item->key   = $typeKey;
                    $item->value = $typeName;
                    $this->dao->replace(TABLE_LANG)->data($item)->exec();
                }
            }
        }

        return true;
    }

    /**
     * Adjust priv for 11.6.2.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv11_6_2()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('method')->eq('index')->andWhere('module')->in('message')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'message';
            $groupPriv->method = 'browser';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Adjust priv for 11.6.4.
     *
     * @access public
     * @return void
     */
    public function adjustPriv11_6_4()
    {

        $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('caselib')->set('method')->eq('browse')->where('module')->eq('testsuite')->andWhere('method')->eq('library')->exec();
        $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('caselib')->set('method')->eq('create')->where('module')->eq('testsuite')->andWhere('method')->eq('createLib')->exec();
        $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('caselib')->set('method')->eq('view')->where('module')->eq('testsuite')->andWhere('method')->eq('libView')->exec();
        $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('caselib')->where('module')->eq('testsuite')->andWhere('method')->in('exportTemplate,import,showImport,batchCreateCase,createCase')->exec();

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('testsuite')->andWhere('method')->eq('edit')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'caselib';
            $groupPriv->method = 'edit';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('testsuite')->andWhere('method')->eq('delete')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'caselib';
            $groupPriv->method = 'delete';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Fix group acl.
     *
     * @access public
     * @return bool
     */
    public function fixGroupAcl()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUP)->fetchAll();
        foreach($groups as $group)
        {
            if(empty($group->acl)) continue;

            $acl = json_decode($group->acl, true);
            if(isset($acl['products']))
            {
                $isEmpty = true;
                foreach($acl['products'] as $productID)
                {
                    if(!empty($productID)) $isEmpty = false;
                }
                if($isEmpty) unset($acl['products']);
            }

            if(isset($acl['projects']))
            {
                $isEmpty = true;
                foreach($acl['projects'] as $projectID)
                {
                    if(!empty($projectID)) $isEmpty = false;
                }
                if($isEmpty) unset($acl['projects']);
            }

            $acl = json_encode($acl);
            $this->dao->update(TABLE_GROUP)->set('acl')->eq($acl)->where('id')->eq($group->id)->exec();
        }

        return true;
    }

    /**
     * Adjust 11.7 priv.
     *
     * @access public
     * @return void
     */
    public function adjustPriv11_7()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('editor')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'dev';
            $groupPriv->method = 'editor';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('translate')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'dev';
            $groupPriv->method = 'translate';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq('translate')->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq('editor')->exec();

        return true;
    }

    /**
     * Fix bug typeList.
     *
     * @access public
     * @return bool
     */
    public function fixBugTypeList()
    {
        $customedTypeList4All = $this->dao->select('*')->from(TABLE_LANG)
            ->where('lang')->eq("all")
            ->andWhere('module')->eq('bug')
            ->andWhere('section')->eq('typeList')
            ->fetchPairs('`key`', 'value');
        foreach($this->config->upgrade->discardedBugTypes as $langCode => $types)
        {
            $bugs = $this->dao->select('distinct type')->from(TABLE_BUG)->where('type')->in(array_keys($types))->fetchAll('type');
            if(empty($bugs)) return true;

            $usedTypes        = array_keys($bugs);
            $customedTypeList = $this->dao->select('*')->from(TABLE_LANG)
                ->where('lang')->eq($langCode)
                ->andWhere('module')->eq('bug')
                ->andWhere('section')->eq('typeList')
                ->fetchPairs('`key`', 'value');

            $typesToSave = array_diff($usedTypes, empty($customedTypeList) ? $customedTypeList4All : $customedTypeList);

            if(empty($typesToSave)) continue;

            $langs = array();
            foreach($typesToSave as $type) $langs[$type] = $types[$type];

            if(empty($customedTypeList) and empty($customedTypeList4All))
            {
                $lang = new stdclass;
                $lang->bug = new stdclass;
                $lang->productCommon = '';
                $lang->executionCommon = '';
                $lang->more          = '';
                $langFile  = $this->app->getModuleRoot() . DS . 'bug' . DS . 'lang' . DS . $langCode . '.php';
                if(is_file($langFile)) include $langFile;
                $langs = array_merge($lang->bug->typeList, $langs);
            }
            elseif(empty($customedTypeList))
            {
                $langs = array_merge($customedTypeList4All, $langs);
            }

            $this->loadModel('custom');
            foreach($langs as $type => $typeName) $this->custom->setItem("{$langCode}.bug.typeList.{$type}.1", $typeName);
        }
        return true;
    }

    /**
     * Remove editor and translate.
     *
     * @access public
     * @return bool
     */
    public function rmEditorAndTranslateDir()
    {
        $zfile      = $this->app->loadClass('zfile');
        $moduleRoot = $this->app->getModuleRoot();

        $editorDir = $moduleRoot . 'editor';
        if(is_dir($editorDir)) $zfile->removeDir($editorDir);

        $translateDir = $moduleRoot . 'translate';
        if(is_dir($translateDir)) $zfile->removeDir($translateDir);

        return true;
    }

    /**
     * Set concept setted.
     *
     * @access public
     * @return bool
     */
    public function setConceptSetted()
    {
        $conceptSetted = $this->dao->select('*')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('`key`')->eq('conceptSetted')->fetchAll();

        if(empty($conceptSetted))
        {
            $setting = new stdclass();
            $setting->owner  = 'system';
            $setting->module = 'custom';

            $setting->key    = 'storyRequirement';
            $setting->value  = '0';
            $this->dao->replace(TABLE_CONFIG)->data($setting)->exec();

            $setting->key    = 'hourPoint';
            $setting->value  = '0';
            $this->dao->replace(TABLE_CONFIG)->data($setting)->exec();

            $setting->module = 'common';
            $setting->key    = 'conceptSetted';
            $setting->value  = '1';
            $this->dao->replace(TABLE_CONFIG)->data($setting)->exec();
        }

        return true;
    }

    /**
     * Adjust priv 12.0.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv12_0()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('file')->andWhere('method')->eq('delete')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'doc';
            $groupPriv->method = 'deleteFile';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        return true;
    }

    /**
     * Save repo from svn and git config.
     *
     * @access public
     * @return bool
     */
    public function importRepoFromConfig()
    {
        $this->app->loadConfig('svn');
        if(isset($this->config->svn->repos))
        {
            $scm = $this->app->loadClass('scm');
            foreach($this->config->svn->repos as $i => $repo)
            {
                $repoPath = $repo['path'];
                if(empty($repoPath)) continue;

                $existRepo = $this->dao->select('*')->from(TABLE_REPO)->where('path')->eq($repoPath)->andWhere('SCM')->eq('Subversion')->fetch();
                if($existRepo) continue;

                $svnRepo = new stdclass();
                $svnRepo->client   = $this->config->svn->client;
                $svnRepo->name     = basename($repoPath);
                $svnRepo->path     = $repoPath;
                $svnRepo->SCM      = 'Subversion';
                $svnRepo->account  = $repo['username'];
                $svnRepo->password = $repo['password'];
                $svnRepo->encrypt  = 'base64';
                $svnRepo->encoding = zget($repo, 'encoding', $this->config->svn->encoding);

                $scm->setEngine($svnRepo);
                $info = $scm->info('');
                $svnRepo->prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $svnRepo->path)), '/');
                if($svnRepo->prefix) $svnRepo->prefix = '/' . $svnRepo->prefix;

                $svnRepo->password = base64_encode($repo['password']);
                $this->dao->insert(TABLE_REPO)->data($svnRepo)->exec();
            }
        }

        $this->app->loadConfig('git');
        if(isset($this->config->git->repos))
        {
            foreach($this->config->git->repos as $i => $repo)
            {
                $repoPath = $repo['path'];
                if(empty($repoPath)) continue;

                $existRepo = $this->dao->select('*')->from(TABLE_REPO)->where('path')->eq($repoPath)->andWhere('SCM')->eq('Git')->fetch();
                if($existRepo) continue;

                $gitRepo = new stdclass();
                $gitRepo->client   = $this->config->git->client;
                $gitRepo->name     = basename($repoPath);
                $gitRepo->path     = $repoPath;
                $gitRepo->prefix   = '';
                $gitRepo->SCM      = 'Git';
                $gitRepo->account  = '';
                $gitRepo->password = '';
                $gitRepo->encrypt  = 'base64';
                $gitRepo->encoding = zget($repo, 'encoding', $this->config->git->encoding);
                $this->dao->insert(TABLE_REPO)->data($gitRepo)->exec();
            }
        }
        return true;
    }

    /**
     * Fix fromCaseVersion field for zt_case table.
     *
     * @access public
     * @return bool
     */
    public function fixFromCaseVersion()
    {
        /* Get imported cases and cases version is null. */
        $errorCasePairs = $this->dao->select('id,fromCaseID,fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->ne(0)->andWhere('fromCaseVersion')->eq(0)->fetchPairs('id', 'fromCaseID');
        if(empty($errorCasePairs)) return true;

        /* Get from case versions by from cases. */
        $fromCaseIdList   = array_unique(array_values($errorCasePairs));
        $fromCaseVersions = $this->dao->select('id,version')->from(TABLE_CASE)->where('id')->in($fromCaseIdList)->fetchPairs('id', 'version');

        /* Fix fromCaseVersion field. */
        foreach($errorCasePairs as $caseID => $fromCaseID)
        {
            $fromCaseVersion = zget($fromCaseVersions, $fromCaseID, 1);
            $this->dao->update(TABLE_CASE)->set('fromCaseVersion')->eq($fromCaseVersion)->where('id')->eq($caseID)->exec();
        }

        return true;
    }

    /**
     * Adjust priv 12.5.
     *
     * @access public
     * @return bool
     */
    public function adjustPriv12_5()
    {
        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('custom')->andWhere('method')->eq('set')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'custom';
            $groupPriv->method = 'product';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

            $groupPriv->method = 'project';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('task')->andWhere('method')->eq('create')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'story';
            $groupPriv->method = 'batchToTask';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        return true;
    }

    /**
     * Adjust priv 15.0.
     *
     * @access public
     * @return true
     */
    public function adjustPriv15_0()
    {
        $executionPriv = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('execution')->limit(1)->fetch();
        if(empty($executionPriv)) $this->dao->update(TABLE_GROUPPRIV)->set('module')->eq('execution')->where('module')->eq('project')->exec();

        $groups = $this->dao->select('id')->from(TABLE_GROUP)->fetchPairs('id', 'id');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'my';
            $groupPriv->method = 'work';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

            $groupPriv->method = 'contribute';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

            $groupPriv->method = 'team';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('my')->andWhere('method')->eq('project')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->method = 'execution';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('program')->andWhere('method')->like('PGM%')->query();
        while($grouppriv = $stmt->fetch())
        {
            $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq($grouppriv->module)->andWhere('method')->eq($grouppriv->method)->exec();
            $grouppriv->method = strtolower(str_ireplace('PGM', '', $grouppriv->method));
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();

            $grouppriv->method = 'index';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('program')->andWhere('method')->like('PRJ%')->query();
        while($grouppriv = $stmt->fetch())
        {
            $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq($grouppriv->module)->andWhere('method')->eq($grouppriv->method)->exec();
            $grouppriv->module = 'project';
            $grouppriv->method = strtolower(str_ireplace('PRJ', '', $grouppriv->method));
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();

            $grouppriv->method = 'index';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('project')->andWhere('method')->eq('story')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->module = 'projectstory';
            $grouppriv->method = 'story';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('story')->andWhere('method')->eq('view')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->module = 'projectstory';
            $grouppriv->method = 'view';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('project')->andWhere('method')->eq('linkstory')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->module = 'projectstory';
            $grouppriv->method = 'linkstory';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('project')->andWhere('method')->eq('unlinkstory')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->module = 'projectstory';
            $grouppriv->method = 'unlinkstory';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('execution')->andWhere('method')->eq('all')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->module = 'project';
            $grouppriv->method = 'execution';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();

            $grouppriv->module = 'project';
            $grouppriv->method = 'browse';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();

            $grouppriv->module = 'project';
            $grouppriv->method = 'index';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('doc')->andWhere('method')->eq('createlib')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->method = 'createLib';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        $stmt = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('module')->eq('doc')->andWhere('method')->eq('editlib')->query();
        while($grouppriv = $stmt->fetch())
        {
            $grouppriv->method = 'editLib';
            $this->dao->replace(TABLE_GROUPPRIV)->data($grouppriv)->exec();
        }

        return true;
    }

    /**
     * Adjust userview.
     *
     * @access public
     * @return bool
     */
    public function adjustUserView()
    {
        $userViews = $this->dao->select('`account`,`sprints`,`projects`')->from(TABLE_USERVIEW)->where('projects')->ne('')->fetchAll('account');

        $projectIdList     = array();
        $accountProjects   = array();
        $accountExecutions = array();
        foreach($userViews as $account => $userView)
        {
            $projects = explode(',', trim($userView->projects, ','));
            foreach($projects as $projectID)
            {
                if(empty($projectID)) continue;
                $accountProjects[$account][$projectID] = $projectID;

                if(isset($projectIdList[$projectID])) continue;
                $projectIdList[$projectID] = $projectID;
            }

            $executions = explode(',', trim($userView->sprints, ','));
            foreach($executions as $executionID)
            {
                if(empty($executionID)) continue;
                $accountExecutions[$account][$executionID] = $executionID;
            }
        }

        $executionPairs = $this->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->andWhere('type')->in('sprint,stage,kanban')->fetchAll('id', 'id');
        foreach($userViews as $account => $userView)
        {
            $projects = zget($accountProjects, $account, array());
            if(empty($projects)) continue;

            $executions = zget($accountExecutions, $account, array());
            foreach($projects as $projectID)
            {
                if(isset($executionPairs[$projectID]))
                {
                    $executions[$projectID] = $projectID;
                    unset($projects[$projectID]);
                }
            }

            $this->dao->update(TABLE_USERVIEW)->set('sprints')->eq(join(',', $executions))->set('projects')->eq(join(',', $projects))->where('account')->eq($account)->exec();
        }

        return true;
    }

    /**
     * Save Logs.
     *
     * @param  string    $log
     * @access public
     * @return void
     */
    public function saveLogs($log)
    {
        $logFile = $this->getLogFile();
        $log     = date('Y-m-d H:i:s') . ' ' . trim($log) . "\n";
        if(!file_exists($logFile)) $log = "<?php\ndie();\n?" . ">\n" . $log;

        file_put_contents($logFile, $log, FILE_APPEND);
    }

    /**
     * Create program.
     *
     * @param  array  $productIdList
     * @param  array  $projectIdList
     * @access public
     * @return int
     */
    public function createProgram($productIdList = array(), $projectIdList = array())
    {
        $this->app->loadLang('program');
        $data    = fixer::input('post')->get();
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        if(isset($data->newProgram))
        {
            if(!$this->post->longTime and !$this->post->end and isset($data->begin)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->end)));

            if(isset($data->projectName) and $data->projectType == 'execution' and empty($data->projectName)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->projectName)));

            if($data->projectType == 'project')
            {
                $projectPairs = $this->dao->select('id,name')->from(TABLE_EXECUTION)
                    ->where('deleted')->eq('0')
                    ->andWhere('id')->in($projectIdList)
                    ->fetchPairs();

                $projectNames  = array();
                $duplicateList = '';
                foreach($projectPairs as $projectID => $projectName)
                {
                    if(isset($projectNames[$projectName]))
                    {
                        $duplicateList .= "$projectID,";
                        $duplicateList .= "{$projectNames[$projectName]},";
                        continue;
                    }

                    $projectNames[$projectName] = $projectID;
                }

                if($duplicateList)
                {
                    echo "<script>new parent.$.zui.ModalTrigger({url: parent.$.createLink('upgrade', 'renameObject', 'type=project&duplicateList=$duplicateList', '', 1), type: 'iframe', width:'40%'}).show();</script>";
                    die;
                }
            }

            /* Insert program. */
            $program = new stdclass();
            $program->name          = $data->programName;
            $program->type          = 'program';
            $program->status        = $data->programStatus;
            $program->begin         = isset($data->begin) ? $data->begin : helper::now();
            $program->end           = isset($data->end) ? $data->end : LONG_TIME;
            $program->openedBy      = $account;
            $program->openedDate    = helper::now();
            $program->openedVersion = $this->config->version;
            $program->acl           = isset($data->programAcl) ? $data->programAcl : 'open';
            $program->days          = $this->computeDaysDelta($program->begin, $program->end);
            $program->PM            = $data->projectType == 'project' ? $data->PM : '';
            $program->vision        = 'rnd';

            $this->app->loadLang('program');
            $this->app->loadLang('project');
            $this->lang->project->name = $this->lang->program->name;

            $this->dao->insert(TABLE_PROJECT)->data($program)
                ->batchcheck('name,begin', 'notempty')
                ->checkIF($program->end != '', 'end', 'gt', $program->begin)
                ->check('name', 'unique', "deleted='0' and type= 'program'")
                ->exec();
            if(dao::isError()) return false;

            $programID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PROGRAM)
                ->set('grade')->eq(1)
                ->set('path')->eq(",{$programID},")
                ->set('`order`')->eq($programID * 5)
                ->where('id')->eq($programID)
                ->exec();

            $this->loadModel('action')->create('program', $programID, 'openedbysystem');
            if($data->programStatus == 'closed') $this->loadModel('action')->create('program', $programID, 'closedbysystem');
        }
        else
        {
            $programID = $data->programID ? $data->programID : $data->programs;
            $this->dao->update(TABLE_PROGRAM)->set('status')->eq($data->programStatus)->where('id')->eq($programID)->exec();
            if($data->programStatus == 'closed') $this->loadModel('action')->create('program', $programID, 'openedbysystem');
        }

        if(isset($data->newLine))
        {
            if(!empty($data->lineName))
            {
                /* Insert product line. */
                $maxOrder = $this->dao->select("max(`order`) as maxOrder")->from(TABLE_MODULE)->where('type')->eq('line')->fetch('maxOrder');
                $maxOrder = $maxOrder ? $maxOrder + 10 : 0;

                $line = new stdClass();
                $line->type   = 'line';
                $line->parent = 0;
                $line->grade  = 1;
                $line->name   = $data->lineName;
                $line->root   = $programID;
                $line->order  = $maxOrder;
                $this->dao->insert(TABLE_MODULE)->data($line)->exec();
                $lineID = $this->dao->lastInsertID();

                $path   = ",$lineID,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($path)->where('id')->eq($lineID)->exec();

                if(dao::isError()) return false;
            }

            if(empty($data->lineName)) $lineID = 0;
        }
        else
        {
            $lineID = $data->lines;
        }

        if(!isset($data->sprints)) return array($programID, 0, $lineID);

        if(isset($data->newProject))
        {
            if(!$this->post->longTime and !$this->post->end) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->end)));

            /* Create a project. */
            $this->loadModel('action');
            $this->app->loadLang('doc');
            $this->lang->project->name = $this->lang->upgrade->projectName;
            if($data->projectType == 'execution')
            {
                /* Use historical projects as execution upgrades. */
                $projectList = $this->createProject($programID, $data);
            }
            else
            {
                /* Use historical projects as project upgrades. */
                $projects = $this->dao->select('id,name,begin,end,status,PM,acl,team')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');

                $projectPairs = $this->dao->select('name,id')->from(TABLE_PROJECT)
                    ->where('deleted')->eq('0')
                    ->andWhere('type')->eq('project')
                    ->andWhere('parent')->eq($programID)
                    ->fetchPairs();

                $duplicateList = '';
                foreach($projects as $projectID => $project)
                {
                    if(isset($projectPairs[$project->name]))
                    {
                        $duplicateList .= "$projectID,";
                        $duplicateList .= "{$projectPairs[$project->name]},";
                    }
                }

                if($duplicateList)
                {
                    echo "<script>new parent.$.zui.ModalTrigger({url: parent.$.createLink('upgrade', 'renameObject', 'type=project&duplicateList=$duplicateList', '', 1), type: 'iframe', width:'40%'}).show();</script>";
                    die;
                }

                foreach($projectIdList as $projectID)
                {
                    $data->projectName   = $projects[$projectID]->name;
                    $data->begin         = $projects[$projectID]->begin;
                    $data->end           = $projects[$projectID]->end;
                    $data->projectStatus = $projects[$projectID]->status;
                    $data->team          = empty($projects[$projectID]->team) ? $projects[$projectID]->name : $projects[$projectID]->team;
                    $data->PM            = $projects[$projectID]->PM;
                    $data->projectAcl    = $projects[$projectID]->acl == 'custom' ? 'private' : $projects[$projectID]->acl;

                    $projectList[$projectID] = $this->createProject($programID, $data);
                }
            }
        }
        else
        {
            $projectList = $data->projects;
            $this->dao->update(TABLE_PROJECT)->set('status')->eq($data->projectStatus)->where('id')->eq($projectList)->exec();
            if($data->projectStatus == 'closed') $this->loadModel('action')->create('project', $projectList, 'openedbysystem');
        }

        return array($programID, $projectList, $lineID);
    }

    /**
     * Create a project.
     *
     * @param  int    $programID
     * @param  object $data
     * @access public
     * @return int|bool
     */
    public function createProject($programID = 0, $data = null)
    {
        $now     = helper::now();
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        /* Insert project. */
        $project = new stdclass();
        $project->name           = $data->projectName;
        $project->type           = 'project';
        $project->model          = 'scrum';
        $project->parent         = $programID;
        $project->status         = $data->projectStatus;
        $project->team           = !empty($data->team) ? $data->team : $data->projectName;
        $project->begin          = $data->begin;
        $project->end            = isset($data->end) ? $data->end : LONG_TIME;
        $project->days           = $this->computeDaysDelta($project->begin, $project->end);
        $project->PM             = $data->PM;
        $project->auth           = 'extend';
        $project->openedBy       = $account;
        $project->openedDate     = $now;
        $project->openedVersion  = $this->config->version;
        $project->lastEditedBy   = $account;
        $project->lastEditedDate = $now;
        $project->acl            = isset($data->projectAcl) ? $data->projectAcl : 'open';

        $programDate = $this->dao->select('begin,end')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
        if($data->begin < $programDate->begin) $this->dao->update(TABLE_PROGRAM)->set('begin')->eq($data->begin)->where('id')->eq($programID)->exec();
        if($data->end > $programDate->end)     $this->dao->update(TABLE_PROGRAM)->set('end')->eq($data->end)->where('id')->eq($programID)->exec();

        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->batchcheck('name', 'notempty')
            ->check('name', 'unique', "type='project' AND parent=$programID AND deleted='0'")
            ->exec();
        if(dao::isError()) return false;

        $projectID = $this->dao->lastInsertId();
        $this->dao->update(TABLE_PROJECT)
            ->set('grade')->eq(2)
            ->set('path')->eq(",{$programID},{$projectID},")
            ->set('`order`')->eq($projectID * 5)
            ->where('id')->eq($projectID)
            ->exec();

        /* Create doc lib. */
        $lib = new stdclass();
        $lib->project = $projectID;
        $lib->name    = $this->lang->doclib->main['project'];
        $lib->type    = 'project';
        $lib->main    = '1';
        $lib->acl     = $project->acl != 'program' ? $project->acl : 'custom';
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        $this->action->create('project', $projectID, 'openedbysystem');
        if($data->projectStatus == 'closed') $this->action->create('project', $projectID, 'closedbysystem');
        return $projectID;
    }

    /**
     * Compute delta of two days.
     *
     * @param  string begin
     * @param  string end
     * @access public
     * @return int
     */
    public function computeDaysDelta($begin, $end)
    {
        if($end == LONG_TIME) return 0;

        $delta   = helper::diffDate($end, $begin);
        $week    = date('w', strtotime($begin));
        $weekend = 0;
        for($i = 0; $i < $delta; $i++)
        {
            $week = $week % 7;
            if($week == 0 or $week == 6) $weekend ++;

            $week++;
        }

        return $delta - $weekend;
    }

    /**
     * Replace program or project id for product and project linked objects.
     *
     * @param  int    $programID
     * @param  int    $projectID
     * @param  int    $lineID
     * @param  array  $productIdList
     * @param  array  $projectIdList
     * @access public
     * @return void
     */
    public function processMergedData($programID, $projectID, $lineID = 0, $productIdList = array(), $sprintIdList = array())
    {
        /* Product linked objects. */
        $this->dao->update(TABLE_RELEASE)->set('project')->eq($projectID)->where('product')->in($productIdList)->exec();

        /* Compute product acl. */
        if($lineID) $this->dao->update(TABLE_MODULE)->set('root')->eq($programID)->where('id')->eq($lineID)->andWhere('root')->eq('0')->exec();
        $this->computeProductAcl($productIdList, $programID, $lineID);

        /* No project is created when there are no sprints. */
        if(!$sprintIdList) return;

        if(!$projectID) return print(js::alert($this->lang->upgrade->projectEmpty));

        $this->dao->update(TABLE_BUG)->set('project')->eq($projectID)->where('product')->in($productIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_TESTREPORT)->set('project')->eq($projectID)->where('product')->in($productIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_TESTSUITE)->set('project')->eq($projectID)->where('product')->in($productIdList)->andWhere('project')->eq(0)->exec();

        /* Project linked objects. */
        $this->dao->update(TABLE_TASK)->set('project')->eq($projectID)->where('execution')->in($sprintIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_BUILD)->set('project')->eq($projectID)->where('execution')->in($sprintIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_BUG)->set('project')->eq($projectID)->where('execution')->in($sprintIdList)->exec();
        $this->dao->update(TABLE_DOC)->set('project')->eq($projectID)->where("lib IN(SELECT id from " . TABLE_DOCLIB . " WHERE type = 'execution' and execution " . helper::dbIN($sprintIdList) . ')')->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_DOCLIB)->set('project')->eq($projectID)->where('type')->eq('execution')->andWhere('execution')->in($sprintIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_TESTTASK)->set('project')->eq($projectID)->where('execution')->in($sprintIdList)->andWhere('project')->eq(0)->exec();
        $this->dao->update(TABLE_EFFORT)->set('project')->eq($projectID)->where('execution')->in($sprintIdList)->andWhere('objectType')->eq('task')->exec();

        /* Put sprint stories into project story mdoule. */
        $sprintStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)
            ->where('project')->in($sprintIdList)
            ->fetchAll();

        foreach($sprintStories as $sprintStory)
        {
            $projectStory = $sprintStory;
            $projectStory->project = $projectID;
            $this->dao->replace(TABLE_PROJECTSTORY)->data($projectStory)->exec();
        }

        /* Sync testcases of executions to projects when classic mode switched to new mode. */
        $projectCases = $this->dao->select('`case`,product,project,count,version')->from(TABLE_PROJECTCASE)->where('project')->in($sprintIdList)->fetchAll();
        foreach($projectCases as $projectCase)
        {
            $projectCase->project = $projectID;
            $projectCase->order   = $projectCase->case * 5;
            $this->dao->replace(TABLE_PROJECTCASE)->data($projectCase)->exec();
        }

        /* Put sprint cases into project case table. */
        $sprintCases = $this->dao->select('t2.case,t2.version,t1.product,t1.execution as project')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.task')
            ->where('t1.execution')->in($sprintIdList)
            ->fetchAll();

        foreach($sprintCases as $sprintCase)
        {
            $sprintCase->order   = $sprintCase->case * 5;
            $sprintCase->project = $projectID;
            $this->dao->replace(TABLE_PROJECTCASE)->data($sprintCase)->exec();
        }

        /* Compute sprint path, grade and the minimum start date and end date of the project. */
        $project      = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $sprints      = $this->dao->select('id, type, acl, begin, end')->from(TABLE_PROJECT)->where('id')->in($sprintIdList)->fetchAll();
        $minBeginDate = $project->begin;
        $maxEndDate   = $project->end;
        foreach($sprints as $sprint)
        {
            $data = new stdclass();
            $data->project = $projectID;
            $data->parent  = $projectID;
            $data->grade   = 1;
            $data->path    = ",{$projectID},{$sprint->id},";
            $data->type    = 'sprint';
            $data->acl     = $sprint->acl == 'custom' ? 'private' : $sprint->acl;

            $this->dao->update(TABLE_PROJECT)->data($data)->where('id')->eq($sprint->id)->exec();

            $minBeginDate = ($sprint->begin < $minBeginDate) ? $sprint->begin : $minBeginDate;
            $maxEndDate   = $sprint->end > $maxEndDate ? $sprint->end : $maxEndDate;
        }

        /* Compute project date and status. */
        $linkedSprintIdList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('project')->eq($projectID)->fetchPairs();
        $linkedSprintIdList += $sprintIdList;
        $minRealBegan        = $this->dao->select('date')->from(TABLE_ACTION)->where('objectID')->in($linkedSprintIdList)->andWhere('objectType')->eq('project')->andWhere('action')->eq('started')->orderBy('date_asc')->fetch('date');
        $maxRealEnd          = $this->dao->select('date')->from(TABLE_ACTION)->where('objectID')->in($linkedSprintIdList)->andWhere('objectType')->eq('project')->andWhere('action')->eq('closed')->orderBy('date_desc')->fetch('date');

        /* Historical projects are used as the start and end dates of the updated projects and programs when performing upgrades. */
        if($_POST['projectType'] == 'execution')
        {
            $data = new stdClass();
            $data->realBegan = $minRealBegan ? substr($minRealBegan, 0, 10) : '0000-00-00';

            $projectStatus = $this->dao->select('status')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('status');
            if($projectStatus == 'closed')
            {
                $data->realEnd    = substr($maxRealEnd, 0, 10);
                $data->closedDate = $maxRealEnd;
            }

            if($minBeginDate != $project->begin or $maxEndDate != $project->end)
            {
                $data->begin = $minBeginDate;
                $data->end   = $maxEndDate;
                $data->days  = $this->computeDaysDelta($data->begin, $data->end);
            }

            $this->dao->update(TABLE_PROJECT)->data($data)->where('id')->eq($projectID)->exec();
            $this->dao->update(TABLE_PROGRAM)->data($data)->where('id')->eq($programID)->exec();
        }

        /* Set product and project relation. */
        $projectProducts = $this->dao->select('product,branch,plan')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->in($sprintIdList)
            ->andWhere('product')->in($productIdList)
            ->fetchGroup('product', 'branch');

        foreach($productIdList as $productID)
        {
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $productID;
            if(isset($projectProducts[$productID]))
            {
                foreach($projectProducts[$productID] as $branchID => $projectProduct)
                {
                    $data->plan   = ($_POST['projectType'] == 'project' and isset($projectProduct->plan)) ? $projectProduct->plan : 0;
                    $data->branch = $branchID;
                    $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
                }
            }
            else
            {
                $data->plan   = 0;
                $data->branch = 0;
                $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }
    }

    /**
     * Compute product acl.
     *
     * @param  array  $productIdList
     * @param  int    $programID
     * @param  int    $lineID
     * @access public
     * @return void
     */
    public function computeProductAcl($productIdList = array(), $programID = 0, $lineID = 0)
    {
        /* Compute product acl. */
        $products = $this->dao->select('id,program,acl')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchAll();
        foreach($products as $product)
        {
            if($product->program) continue;

            $data = new stdclass();
            $data->program = $programID;
            $data->acl     = $product->acl == 'custom' ? 'private' : $product->acl;
            if($lineID !== null) $data->line = $lineID;

            $this->dao->update(TABLE_PRODUCT)->data($data)->where('id')->eq($product->id)->exec();
        }
    }

    /**
     * Compute program and project members.
     *
     * @access public
     * @return void
     */
    public function computeObjectMembers()
    {
        $this->app->loadLang('user');
        $projects      = $this->dao->select('id,days,PM')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchAll('id');
        $projectIdList = array_keys($projects);

        /* Get product and sprint team. */
        $teams         = array();
        $productGroups = $this->dao->select('t1.project,t1.product,t3.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t2.id')->in($projectIdList)
            ->fetchGroup('project', 'product');

        $sprintGroups  = $this->dao->select('*')->from(TABLE_PROJECT)->where('project')->in($projectIdList)->fetchGroup('project', 'id');
        $teamGroups    = $this->dao->select('root,account')->from(TABLE_TEAM)->where('type')->eq('execution')->fetchGroup('root', 'account');
        $users         = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');
        $groupAccounts = $this->dao->select('*')->from(TABLE_USERGROUP)->fetchGroup('group', 'account');

        $projectTeams = array();
        foreach($projectIdList as $projectID)
        {
            $teams    = array();
            $products = zget($productGroups, $projectID, array());
            foreach($products as $product)
            {
                $teams[$product->PO] = $product->PO;
                $teams[$product->QD] = $product->QD;
                $teams[$product->RD] = $product->RD;
                if(isset($product->feedback)) $teams[$product->feedback] = $product->feedback;
            }

            $sprints = zget($sprintGroups, $projectID, array());
            foreach($sprints as $sprint)
            {
                $teams[$sprint->PO] = $sprint->PO;
                $teams[$sprint->PM] = $sprint->PM;
                $teams[$sprint->QD] = $sprint->QD;
                $teams[$sprint->RD] = $sprint->RD;
                if(isset($sprint->feedback)) $teams[$sprint->feedback] = $sprint->feedback;

                $sprintTeams = zget($teamGroups, $sprint->id, array());
                foreach($sprintTeams as $account => $team) $teams[$account] = $account;
            }

            $projectTeams[$projectID] = $teams;
        }

        /* Insert product and sprint team into project team. */
        $today = helper::today();
        foreach($projectTeams as $projectID => $projectMember)
        {
            if(empty($projectMember)) continue;

            $projectMember = array_filter($projectMember);
            $project       = zget($projects, $projectID, '');

            if(!empty($project) and !isset($projectMember[$project->PM])) $projectMember[$project->PM] = $project->PM;
            $members = implode(',', $projectMember);

            $this->dao->update(TABLE_DOCLIB)
                ->set('users')->eq($members)
                ->where('project')->eq($projectID)
                ->andWhere('main')->eq(1)
                ->exec();

            foreach($projectMember as $account)
            {
                if(!isset($users[$account])) continue;

                $user = $users[$account];
                $team = new stdclass();
                $team->root    = $projectID;
                $team->type    = 'project';
                $team->account = $account;
                $team->role    = zget($this->lang->user->roleList, $user->role, $user->role);
                $team->join    = $today;
                $team->days    = $project->days;
                $team->hours   = '7.0';
                $this->dao->replace(TABLE_TEAM)->data($team)->exec();
            }
        }

        /* Get all white list in sprint and product. */
        $this->loadModel('group');
        $this->loadModel('personnel');

        $customProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('whitelist')->ne('')->fetchAll('id');
        $whitelistACL   = $this->dao->select('account')->from(TABLE_ACL)->where('objectID')->in(array_keys($customProducts))->andWhere('objectType')->eq('product')->andWhere('type')->eq('whitelist')->fetchPairs('account');
        foreach($customProducts as $productID => $product)
        {
            if($product->acl != 'private') continue;

            $whitelist = array();
            foreach(explode(',', $product->whitelist) as $group)
            {
                foreach(zget($groupAccounts, $group, array()) as $account => $userGroup) $whitelist[$account] = $account;
            }

            $whitelist += zget($whitelistACL, $productID, array());

            $this->personnel->updateWhitelist($whitelist, 'product', $product->id, 'whitelist', 'upgrade', 'increase');
        }

        $customSprints = $this->dao->select('*')->from(TABLE_PROJECT)->where('whitelist')->ne('')->andWhere('type')->in('sprint,stage,kanban')->fetchAll('id');
        $whitelistACL  = $this->dao->select('account')->from(TABLE_ACL)->where('objectID')->in(array_keys($customSprints))->andWhere('objectType')->eq('sprint')->andWhere('type')->eq('whitelist')->fetchPairs('account');
        foreach($customSprints as $sprint)
        {
            if($sprint->acl != 'private') continue;

            $whitelist = array();
            foreach(explode(',', $sprint->whitelist) as $group)
            {
                foreach(zget($groupAccounts, $group, array()) as $account => $userGroup) $whitelist[$account] = $account;
            }

            $this->personnel->updateWhitelist($whitelist, 'sprint', $sprint->id, 'whitelist', 'upgrade', 'increase');
        }
    }

    /**
     * Merge repo.
     *
     * @access public
     * @return void
     */
    public function mergeRepo()
    {
        $data = fixer::input('post')
            ->join('products', ',')
            ->get();

        foreach($data->repoes as $repoID) $this->dao->update(TABLE_REPO)->set('product')->eq($data->products)->where('id')->eq($repoID)->exec();
    }

    /**
     * Set program default priv.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function setDefaultPriv()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->ne('limited')->andWhere('role')->ne('projectAdmin')->fetchPairs();
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'program';
            $data->method = 'pgmindex';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'prjbrowse';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'index';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }

        /* If is project admin, have all project priv. */
        $projectAdminGroupID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->fetch('id');

        $this->app->loadLang('group');
        foreach($this->lang->resource->program as $method => $methodLang)
        {
            $data = new stdclass();
            $data->group  = $projectAdminGroupID;
            $data->module = 'program';
            $data->method = $method;
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
    }

    /**
     * Set work to full.
     *
     * @access public
     * @return bool
     */
    public function setWork2Full()
    {
        $this->loadModel('setting')->setItem('system.common.global.flow', 'full');
        return true;
    }

    /**
     * Init story sort of plan.
     *
     * @access public
     * @return bool
     */
    public function initStoryOfPlan()
    {
        /* Get all the planned stories and story sort. */
        $stories   = $this->dao->select('id, plan')->from(TABLE_STORY)->where('plan')->ne(0)->andWhere('plan')->ne('')->orderBy('id_desc')->fetchAll('id');
        $planOrder = $this->dao->select('id, `order`')->from(TABLE_PRODUCTPLAN)->where('`order`')->ne('')->fetchAll('id');

        /* Organize the stories according to the plan. */
        $plans = array();
        foreach($stories as $storyID => $story)
        {
            $planIDList = explode(',', trim($story->plan, ','));
            foreach($planIDList as $planID) $plans[$planID][$storyID] = $storyID;
        }

        foreach($plans as $planID => $storyIDList)
        {
            /* Order the story according to the plan. */
            if(!empty($planOrder[$planID]))
            {
                $sortIDList = array();
                $storySort  = explode(',', $planOrder[$planID]->order);

                /* Reorder story id list by story order of plan. */
                foreach($storySort as $storyID)
                {
                    if(empty($storyID)) continue;
                    if(!isset($storyIDList[$storyID])) continue;
                    $sortIDList[$storyID] = $storyID;
                    unset($storyIDList[$storyID]);
                }

                if($storyIDList) $sortIDList += $storyIDList;
                $storyIDList = $sortIDList;
                unset($sortIDList);
            }

            /* Loop insert sort data by plan. */
            $order = 1;
            foreach($storyIDList as $storyID)
            {
                $this->dao->replace(TABLE_PLANSTORY)
                    ->set('plan')->eq($planID)
                    ->set('story')->eq($storyID)
                    ->set('`order`')->eq($order)
                    ->exec();
                $order++;
            }
        }

        return true;
    }

    /**
     * Unify the format of the stories and bugs fields in the zt_build table.
     *
     * @access public
     * @return bool
     */
    public function processBuildTable()
    {
        $builds = $this->dao->select('*')->from(TABLE_BUILD)->fetchAll();
        foreach($builds as $build)
        {
            $data = array();
            if(!empty($build->stories) and $build->stories[0] != ',') $data['stories'] = ',' . $build->stories;
            if(!empty($build->bugs) and $build->bugs[0] != ',')       $data['bugs']    = ',' . $build->bugs;

            if($data) $this->dao->update(TABLE_BUILD)->data($data)->where('id')->eq($build->id)->exec();
        }

        return true;
    }

    /**
     * Adjust the project field of the zt_bug table.
     *
     * @access public
     * @return bool
     */
    public function adjustBugOfProject()
    {
        if($this->config->systemMode != 'new') return true;

        $bugs       = $this->dao->select('id,execution')->from(TABLE_BUG)->where('execution')->ne('0')->andWhere('project')->eq(0)->fetchPairs('id', 'execution');
        $executions = $this->dao->select('id,project')->from(TABLE_EXECUTION)->where('id')->in(array_unique(array_values($bugs)))->fetchPairs('id', 'project');

        foreach($bugs as $id => $executionID)
        {
            if(isset($executions[$executionID])) $this->dao->update(TABLE_BUG)->set('project')->eq($executions[$executionID])->where('id')->eq($id)->exec();
        }

        return true;
    }

    /**
     * Adjust the whitelist of projects.
     *
     * @access public
     * @return bool
     */
    public function adjustWhitelistOfProject()
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('acl')->eq('custom')->andWhere('type')->eq('sprint')->fetchAll();
        foreach($projects as $project)
        {
            $groups    = explode(',', $project->whitelist);
            $accounts  = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groups)->fetchPairs('account');
            $whitelist = '';
            foreach($accounts as $account)
            {
                $acl = new stdclass();
                $acl->account    = $account;
                $acl->objectType = $project->type;
                $acl->objectID   = $project->id;
                $acl->type       = 'whitelist';
                $acl->source     = 'upgrade';

                $this->dao->insert(TABLE_ACL)->data($acl)->exec();

                $whitelist .= ',' . $account;
            }

            $this->dao->update(TABLE_PROJECT)->set('acl')->eq('private')->set('whitelist')->eq($whitelist)->where('id')->eq($project->id)->exec();
        }

        return true;
    }

    /**
     * Adjust the whitelist of projects.
     *
     * @access public
     * @return bool
     */
    public function adjustWhitelistOfProduct()
    {
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('acl')->eq('custom')->fetchAll();
        foreach($products as $product)
        {
            $groups    = explode(',', $product->whitelist);
            $accounts  = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groups)->fetchPairs('account');
            $whitelist = '';
            foreach($accounts as $account)
            {
                $acl = new stdclass();
                $acl->account    = $account;
                $acl->objectType = 'product';
                $acl->objectID   = $product->id;
                $acl->type       = 'whitelist';
                $acl->source     = 'upgrade';

                $this->dao->insert(TABLE_ACL)->data($acl)->exec();

                $whitelist .= ',' . $account;
            }

            $this->dao->update(TABLE_PRODUCT)->set('acl')->eq('private')->set('whitelist')->eq($whitelist)->where('id')->eq($product->id)->exec();
        }

        return true;
    }

    /**
     * Update execution main doclib type.
     *
     * @access public
     * @return bool
     */
    public function updateLibType()
    {
        $executionList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('type')->eq('sprint')->fetchAll('id');
        if(empty($executionList)) return true;

        $this->dao->update(TABLE_DOCLIB)->set('type')->eq('execution')->where('execution')->in(array_keys($executionList))->exec();

        return true;
    }

    /**
     * Update the testtask related cases status.
     *
     * @access public
     * @return bool
     */
    public function updateRunCaseStatus()
    {
        $this->dao->update(TABLE_TESTRUN)->set('status')->eq('normal')->where('status')->in('wait,done')->exec();

        return true;
    }

    /**
     * Fix for task link project.
     *
     * @access public
     * @return bool
     */
    public function fix4TaskLinkProject()
    {
        if($this->config->systemMode != 'new') return true;

        $executionIdList = $this->dao->select('distinct execution')->from(TABLE_TASK)->where('project')->eq(0)->fetchPairs('execution', 'execution');
        $executionPairs  = $this->dao->select('id,project')->from(TABLE_PROJECT)->where('id')->in($executionIdList)->andWhere('project')->ne('0')->fetchPairs('id', 'project');
        foreach($executionPairs as $executionID => $projectID) $this->dao->update(TABLE_TASK)->set('project')->eq($projectID)->where('execution')->eq($executionID)->exec();

        return true;
    }

    /**
     * Fix execution team.
     *
     * @access public
     * @return bool
     */
    public function fixExecutionTeam()
    {
        $errorTeams = $this->dao->select('id,root,account')->from(TABLE_TEAM)->where('type')->eq('')->fetchGroup('root', 'id');
        $duplicateTeams = $this->dao->select('root,account')->from(TABLE_TEAM)->where('root')->in(array_keys($errorTeams))->andWhere('type')->ne('')->fetchGroup('root', 'account');

        foreach($errorTeams as $root => $teams)
        {
            if(!isset($duplicateTeams[$root]))
            {
                $this->dao->update(TABLE_TASK)->set('type')->eq('execution')->where('id')->in(array_keys($teams))->exec();
            }
            else
            {
                $existsTeams = $duplicateTeams[$root];
                foreach($teams as $team)
                {
                    if(isset($existsTeams[$team->account]))
                    {
                        $this->dao->delete()->from(TABLE_TEAM)->where('id')->eq($team->id);
                    }
                    else
                    {
                        $this->dao->update(TABLE_TASK)->set('type')->eq('execution')->where('id')->in($team->id)->exec();
                    }
                }
            }
        }

        return true;
    }

    /**
     * Update the createdVersion field of the zt_product table.
     *
     * @access public
     * @return void
     */
    public function updateProductVersion()
    {
        $this->dao->update(TABLE_PRODUCT)->set('createdVersion')->eq($this->config->version)->where('createdVersion')->eq('')->andWhere('createdDate')->gt('2020-01-01')->exec();
        return true;
    }

    /**
     * Unique projectAdmin group.
     *
     * @access public
     * @return void
     */
    public function uniqueProjectAdmin()
    {
        $projectAdmins = $this->dao->select('*')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->orderBy('id')->fetchAll('id');
        if(count($projectAdmins) == 1) return true;

        $holdGroup = reset($projectAdmins);
        unset($projectAdmins[$holdGroup->id]);

        $userGroups = $this->dao->select('*')->from(TABLE_USERGROUP)->where('`group`')->in(array_keys($projectAdmins))->fetchGroup('group', 'account');
        foreach($userGroups as $groupID => $groups)
        {
            foreach($groups as $account => $userGroup)
            {
                $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($userGroup->group)->andWhere('account')->eq($userGroup->account)->exec();

                $newUserGroup = new stdclass();
                $newUserGroup->account = $account;
                $newUserGroup->project = $userGroup->project;
                $newUserGroup->group   = $holdGroup->id;
                $this->dao->replace(TABLE_USERGROUP)->data($newUserGroup)->exec();
            }
        }

        $this->dao->delete()->from(TABLE_GROUP)->where('id')->in(array_keys($projectAdmins))->exec();
        return true;
    }

    /**
     * Process gitlab repo data.
     *
     * @access public
     * @return bool
     */
    public function processGitlabRepo()
    {
        $repoList = $this->dao->select('*')->from(TABLE_REPO)->where('SCM')->eq('Gitlab')->fetchAll();
        foreach($repoList as $repo)
        {
            if(is_numeric($repo->path)) continue;

            /* Create gitlab from repo. */
            $gitlab = new stdclass;
            $gitlab->type    = 'gitlab';
            $gitlab->name    = $repo->client;
            $gitlab->url     = $repo->client;
            $gitlab->token   = $repo->encrypt == 'base64' ? base64_decode($repo->password) : $repo->password;
            $gitlab->private = md5(uniqid());
            $this->dao->insert(TABLE_PIPELINE)->data($gitlab)->exec();

            $gitlabID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_REPO)->set('client')->eq($gitlabID)->set('path')->eq($repo->extra)->where('id')->eq($repo->id)->exec();
        }
        $this->dao->update(TABLE_REPO)->set('prefix')->eq('')->where('SCM')->eq('Gitlab')->exec();
        return true;
    }

    /**
     * Process story file type to requirement.
     *
     * @access public
     * @return bool
     */
    public function processStoryFileType()
    {
        $requirementList = $this->dao->select('id')->from(TABLE_STORY)->where('type')->eq('requirement')->fetchPairs('id');

        $this->dao->update(TABLE_FILE)->set('objectType')->eq('requirement')
            ->where('objectID')->in($requirementList)
            ->andWhere('objectType')->eq('story')
            ->exec();

        return true;
    }

    /**
     * Leave the project field of the product document blank.
     *
     * @access public
     * @return bool
     */
    public function processProductDoc()
    {
        $this->dao->update(TABLE_DOC)->set('project')->eq(0)
            ->where('product')->ne(0)
            ->andWhere('project')->ne(0)
            ->exec();

        return true;
    }

    /**
     * Adjust priv 15.3
     *
     * @access public
     * @return bool
     */
    public function adjustPriv15_3()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('doc')->andWhere('method')->in('view,objectLibs')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group  = $groupID;
            $groupPriv->module = 'doc';
            $groupPriv->method = 'objectLibs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

            $groupPriv->method = 'tableContents';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();

            $groupPriv->method = 'showFiles';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }
        return true;
    }

    /**
     * Actual finished date of processing testtask.
     *
     * @access public
     * @return bool
     */
    public function processTesttaskDate()
    {
        $this->dao->update(TABLE_TESTTASK)->set("realFinishedDate = end")
            ->where('status')->eq('done')
            ->andWhere('realFinishedDate')->eq('0000-00-00 00:00:00')
            ->exec();

        return true;
    }

    /**
     * Store the body of the document in a temporary field.
     *
     * @access public
     * @return bool
     */
    public function processDocTempContent()
    {
        $docContentList = $this->dao->select('doc,content')->from(TABLE_DOCCONTENT)->fetchAll('doc');

        foreach($docContentList as $docID => $doc)
        {
            if(empty($doc->content)) continue;

            $this->dao->update(TABLE_DOC)
                ->set('draft')->eq($doc->content)
                ->where('id')->eq($docID)
                ->exec();
        }

        return true;
    }

    /**
     * Move kanban card data to kanbancell table.
     *
     * @access public
     * @return void
     *
     */
    public function moveKanbanData()
    {
        /* Move common kanban data. */
        $cards = $this->dao->select('id,kanban,`column`,lane')->from(TABLE_KANBANCARD)->fetchAll('id');

        $cellGroup = array();
        foreach($cards as $cardID => $card)
        {
            if(!$card->lane or !$card->column) continue;

            $key   = $card->kanban . '-' . $card->lane . '-' . $card->column;
            $cards = isset($cellGroup[$key]) ? $cellGroup[$key] . "$cardID," : ",$cardID,";
            $cellGroup[$key] = $cards;
        }

        foreach($cellGroup as $key => $cards)
        {
            $key = explode('-', $key);
            if(!is_array($key)) continue;

            $cell = new stdclass();
            $cell->kanban = $key[0];
            $cell->lane   = $key[1];
            $cell->column = $key[2];
            $cell->type   = 'common';
            $cell->cards  = $cards;

            $this->dao->insert(TABLE_KANBANCELL)->data($cell)->exec();
        }

        /* Drop group kanban data. */
        $groupLanePairs = $this->dao->select('id')->from(TABLE_KANBANLANE)->where('`groupby`')->ne('')->fetchPairs();
        if(!empty($groupLanePairs))
        {
            $this->dao->delete()->from(TABLE_KANBANLANE)->where('id')->in($groupLanePairs)->exec();
            $this->dao->delete()->from(TABLE_KANBANCOLUMN)->where('lane')->in($groupLanePairs)->exec();
        }

        /* Move execution kanban data. */
        $executionKanban = $this->dao->select('t1.id as `lane`, t1.execution, t1.type, t2.id as `column`, t2.cards')->from(TABLE_KANBANLANE)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.id = t2.lane')
            ->where('t1.execution')->gt(0)
            ->fetchGroup('lane');

        foreach($executionKanban as $laneID => $laneGroup)
        {
            foreach($laneGroup as $colData)
            {
                if(!$laneID or !$colData->column) continue;

                $cell = new stdclass();
                $cell->kanban = $colData->execution;
                $cell->lane   = $laneID;
                $cell->column = $colData->column;
                $cell->type   = $colData->type;
                $cell->cards  = $colData->cards;

                $this->dao->insert(TABLE_KANBANCELL)->data($cell)->exec();
            }
        }
    }

    /**
     * Update kanban space team.
     *
     * @access public
     * @return void
     */
    public function updateSpaceTeam()
    {
        $kanbanUsers = $this->dao->select("space, CONCAT(owner, ',', team, ',', whitelist) as users") ->from(TABLE_KANBAN)->fetchAll('space');
        $spaceUsers  = $this->dao->select("id, CONCAT(owner, ',', team, ',', whitelist) as users")->from(TABLE_KANBANSPACE)->fetchAll('id');

        foreach($kanbanUsers as $spaceID => $kanban)
        {
            $team = zget($spaceUsers, $spaceID)->users;
            $team = $team . ',' . $kanban->users;
            $team = explode(',', $team);
            $team = array_filter($team);
            $team = array_unique($team);
            $team = implode(',', $team);
            $team = trim($team, ',');

            $this->dao->update(TABLE_KANBANSPACE)->set('`team`')->eq($team)->where('id')->eq($spaceID)->exec();
        }
        $this->dao->update(TABLE_KANBANSPACE)->set('`whitelist`')->eq('')->exec();
        $this->dao->update(TABLE_KANBAN)->set('`whitelist`')->eq('')->exec();
    }

    /**
     * Adjust for bug required field.
     *
     * @access public
     * @return bool
     */
    public function adjustBugRequired()
    {
        $data = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('bug')
            ->andWhere('section')->eq('create')
            ->andWhere('`key`')->eq('requiredFields')
            ->fetch();
        if(empty($data)) return true;

        $data->value = ',' . $data->value . ',';
        $data->value = str_replace(',project,', ',', $data->value);
        $this->dao->update(TABLE_CONFIG)->set('`value`')->eq(trim($data->value, ','))->where('id')->eq($data->id)->exec();
        return true;
    }

    /**
     * Update branch when object have module.
     *
     * @access public
     * @return bool
     */
    public function updateObjectBranch()
    {
        $moduleBranchPairs = $this->dao->select('id,branch')->from(TABLE_MODULE)->where('branch')->ne(0)->fetchPairs();
        if(empty($moduleBranchPairs)) return true;

        $storyModulePairs = $this->dao->select('module')->from(TABLE_STORY)->where('module')->in(array_keys($moduleBranchPairs))->andWhere('branch')->eq(0)->fetchPairs();
        foreach($storyModulePairs as $moduleID)
        {
            if(!isset($moduleBranchPairs[$moduleID])) continue;

            $this->dao->update(TABLE_STORY)->set('`branch`')->eq($moduleBranchPairs[$moduleID])->where('module')->eq($moduleID)->exec();
        }

        $bugModulePairs = $this->dao->select('module')->from(TABLE_BUG)->where('module')->in(array_keys($moduleBranchPairs))->andWhere('branch')->eq(0)->fetchPairs();
        foreach($bugModulePairs as $moduleID)
        {
            if(!isset($moduleBranchPairs[$moduleID])) continue;

            $this->dao->update(TABLE_BUG)->set('`branch`')->eq($moduleBranchPairs[$moduleID])->where('module')->eq($moduleID)->exec();
        }

        $caseModulePairs = $this->dao->select('module')->from(TABLE_CASE)->where('module')->in(array_keys($moduleBranchPairs))->andWhere('branch')->eq(0)->fetchPairs();
        foreach($caseModulePairs as $moduleID)
        {
            if(!isset($moduleBranchPairs[$moduleID])) continue;

            $this->dao->update(TABLE_CASE)->set('`branch`')->eq($moduleBranchPairs[$moduleID])->where('module')->eq($moduleID)->exec();
        }

        return true;
    }

    /**
     * Update branch of project linked stories.
     *
     * @access public
     * @return bool
     */
    public function updateProjectStories()
    {
        $storyPairs = $this->dao->select('t1.story, t2.branch')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->fetchPairs();

        foreach($storyPairs as $storyID => $branch)
        {
            $this->dao->update(TABLE_PROJECTSTORY)->set('branch')->eq($branch)->where('story')->eq($storyID)->exec();
        }

        return true;
    }

    /**
     * Update project linked branch.
     *
     * @access public
     * @return void
     */
    public function updateProjectLinkedBranch()
    {
        $projectProducts = $this->dao->select('t1.project,t1.story,t2.branch,t2.product')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t2.branch')->ne(0)
            ->fetchGroup('project');

        $projectBranches = array();
        foreach($projectProducts as $projectID => $stories)
        {
            foreach($stories as $story)
            {
                if(!isset($projectBranches[$projectID])) $projectBranches[$projectID] = array();
                if(!isset($projectBranches[$projectID][$story->product])) $projectBranches[$projectID][$story->product] = array();
                $projectBranches[$projectID][$story->product][$story->branch] = $story->branch;
            }
        }

        foreach($projectBranches as $projectID => $products)
        {
            foreach($products as $productID => $branches)
            {
                foreach($branches as $branchID)
                {
                    $data = new stdClass();
                    $data->project = $projectID;
                    $data->product = $productID;
                    $data->branch  = $branchID;
                    $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
                }
            }
        }

        return true;
    }

    /**
     * Document library for updating documents.
     *
     * @access public
     * @return bool
     */
    public function updateDocField()
    {
        $hasModuleDocs = $this->dao->select('*')->from(TABLE_DOC)->where('module')->ne(0)->fetchAll('id');
        $docModules    = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('doc')->fetchAll('id');
        foreach($hasModuleDocs as $doc)
        {
            $libID = isset($docModules[$doc->module]->root) ? $docModules[$doc->module]->root : 0;
            if(!$libID or $libID == $doc->lib) continue;
            $this->dao->update(TABLE_DOC)->set('lib')->eq($libID)->where('id')->eq($doc->id)->exec();
        }
        return true;
    }

    /**
     * Update activated date by history action.
     *
     * @access public
     * @return bool
     */
    public function updateActivatedDate()
    {
        $actions = $this->dao->select('objectID, objectType, max(date) as date')->from(TABLE_ACTION)->where('action')->eq('activated')->andWhere('objectType')->in('story, task, bug')->groupBy('objectID, objectType')->fetchAll();
        foreach($actions as $action)
        {
            $table = TABLE_BUG;
            if($action->objectType == 'story') $table = TABLE_STORY;
            if($action->objectType == 'task') $table  = TABLE_TASK;

            $this->dao->update($table)->set('activatedDate')->eq($action->date)->where('id')->eq($action->objectID)->exec();
        }
        return true;
    }

    /**
     * Update group date for lite.
     *
     * @access public
     * @return bool
     * */
    public function updateGroup4Lite()
    {
        $privTable  = $this->config->db->prefix . 'grouppriv';
        $adminPrivs = $this->dao->select('module,method')->from($privTable)->where('`group`')->eq('1')->andWhere('module')->notin($this->config->upgrade->unsetModules)->fetchAll();
        $pmPrivs    = $this->dao->select('module,method')->from($privTable)->where('`group`')->eq('4')->andWhere('module')->notin($this->config->upgrade->unsetModules)->fetchAll();
        $topPrivs   = $this->dao->select('module,method')->from($privTable)->where('`group`')->eq('9')->andWhere('module')->notin($this->config->upgrade->unsetModules)->fetchAll();
        $liteGroup  = $this->dao->select('*')->from(TABLE_GROUP)->where('vision')->eq('lite')->fetchAll();

        $sql = 'REPLACE INTO ' . TABLE_GROUPPRIV . ' VALUES ';
        foreach($liteGroup as $group)
        {
            if($group->role == 'liteAdmin' and !empty($adminPrivs))
            {
                foreach($adminPrivs as $priv)
                {
                    $sql .= "($group->id, ";
                    $sql .= "'$priv->module', ";
                    $sql .= "'$priv->method'), ";
                }
            }

            if($group->role == 'liteProject' and !empty($pmPrivs))
            {
                foreach($pmPrivs as $priv)
                {
                    $sql .= "($group->id, ";
                    $sql .= "'$priv->module', ";
                    $sql .= "'$priv->method'), ";
                }
            }

            if($group->role == 'liteTeam' and !empty($topPrivs))
            {
                foreach($topPrivs as $priv)
                {
                    $sql .= "($group->id, ";
                    $sql .= "'$priv->module', ";
                    $sql .= "'$priv->method'), ";
                }
            }
        }

        $sql = rtrim($sql, ', ') . ';';

        $this->dao->exec($sql);

        return true;
    }

    /**
     * Gets the extension file to be moved.
     *
     * @access public
     * @return array
     */
    public function getExtFiles()
    {
        $files       = array();
        $allModules  = glob($this->app->moduleRoot . '*');
        $skipModules = $this->getEncryptedModules($allModules);

        foreach($allModules as $modulePath)
        {
            $module = basename($modulePath);
            if(in_array($module, $skipModules)) continue;

            $dirRoot = in_array($module, $this->config->upgrade->openModules) ? $modulePath . DS . 'ext' : $modulePath;
            $dirs    = glob($dirRoot . DS . '*');
            foreach($dirs as $dirPath)
            {
                $dir      = basename($dirPath);
                $realPath = is_file($dirPath) ? $dirRoot : $dirPath;
                $path     = in_array($module, $this->config->upgrade->openModules) ? $module . DS . 'ext' . DS . $dir : $module . DS . $dir;
                if(is_dir($realPath))
                {
                    $files += $this->getPluginFiles($module, $dir, $realPath, $path);
                }
            }
        }

        return $files;
    }

    /**
     * Get modules that are not open source.
     *
     * @param  array  $allModules
     * @access public
     * @return array
     */
    public function getEncryptedModules($allModules)
    {
        $encryptModules = array();
        foreach($allModules as $modulePath)
        {
            $customFiles = array();
            $module      = basename($modulePath);
            if(in_array($module, $this->config->upgrade->openModules))
            {
                $extRoot = $modulePath . DS . 'ext';
                if(!is_dir($extRoot))
                {
                    $encryptModules[] = $module;
                    continue;
                }
                else
                {
                    foreach(array('control', 'model') as $dir)
                    {
                        $realPath = $extRoot . DS . $dir;
                        $path     = $module . DS . 'ext' . DS . $dir;
                        if(!is_dir($realPath)) continue;
                        $customFiles += $this->getPluginFiles($module, $dir, $realPath, $path);
                    }
                }
            }
            else
            {
                foreach(array('control.php', 'model.php') as $file)
                {
                    $filePath = $modulePath . DS . $file;
                    if(!is_file($filePath)) continue;

                    $customFiles += $this->getPluginFiles($module, $file, $modulePath, $module);
                }
            }

            if(empty($customFiles) or $module == 'owt') $encryptModules[$module] = $module;
        }
        return $encryptModules;
    }

    /**
     * Get plugin files.
     *
     * @param  string $module
     * @param  string $dir
     * @param  string $realPath
     * @param  string $path
     * @access public
     * @return array
     */
    public function getPluginFiles($module, $dir, $realPath, $path)
    {
        $pluginFiles = array();
        $files       = is_file($realPath . DS . $dir) ? array($dir) : glob($realPath . DS . '*');
        foreach($files as $file)
        {
            $file     = basename($file);
            $filePath = $realPath . DS . $file;
            $fileName = is_file($realPath . DS . $dir) ? $path : $path . DS . $file;

            /* If you are currently pointing to a directory, the files in that directory are traversed. */
            if(is_dir($filePath))
            {
                $pluginFiles += $this->getPluginFiles($module, $dir, $filePath, $fileName);
            }
            else
            {
                $handle = fopen($filePath, 'r');
                $line   = fgets($handle);
                $line   = fgets($handle);
                fclose($handle);

                /* Check whether the current file is encrypted. */
                if(strpos($line, "extension_loaded('ionCube Loader')") === false)
                {
                    $systemFiles = file_get_contents('systemfiles.txt');
                    $systemFiles = str_replace('/', DS, $systemFiles);
                    if(strpos($systemFiles, ",$fileName,") !== false) continue;

                    $pluginFiles[$fileName] = $fileName;
                }
            }
        }

        return $pluginFiles;
    }

    /**
     * Get custom modules.
     *
     * @param  array  $allModules
     * @access public
     * @return array
     */
    public function getCustomModules($allModules)
    {
        $customModules = array();
        $systemFiles   = file_get_contents('systemfiles.txt');
        $systemFiles   = str_replace('/', DS, $systemFiles);
        foreach($allModules as $modulePath)
        {
            $customFiles = array();
            $module      = basename($modulePath);
            if(!in_array($module, $this->config->upgrade->openModules) and !preg_match("#$module(/[a-z]*)*(/[a-z]+.[a-z]+)+#", $systemFiles)) $customModules[$module] = $module;
        }
        return $customModules;
    }

    /**
     * Move extension files.
     *
     * @access public
     * @return array
     */
    public function moveExtFiles()
    {
        $data       = fixer::input('post')->get();
        $customRoot = $this->app->appRoot . 'extension' . DS . 'custom';
        $response   = array('result' => 'success');

        foreach($data->files as $file)
        {
            $dirRoot  = $customRoot . DS . dirname($file);
            $fileName = basename($file);
            $fromPath = $this->app->getModuleRoot() . $file;
            $toPath   = $dirRoot . DS . $fileName;
            if(!is_dir($dirRoot))
            {
                if(!mkdir($dirRoot, 0777, true))
                {
                    $response['result']  = 'fail';
                    $response['command'] = 'chmod o=rwx -R '. $this->app->appRoot . 'extension/custom';

                    return $response;
                }
            }
            copy($fromPath, $toPath);
            $this->replaceIncludePath($toPath);
        }

        return $response;
    }

    /**
     * Remove encrypted directories.
     *
     * @access public
     * @return array
     */
    public function removeEncryptedDir()
    {
        $allModules    = glob($this->app->moduleRoot . '*');
        $skipModules   = $this->getEncryptedModules($allModules);
        $customModules = $this->getCustomModules($allModules);
        $modules       = $skipModules + $customModules;
        $zfile         = $this->app->loadClass('zfile');
        $response      = array('result' => 'success');
        $command       = array();
        foreach($modules as $module)
        {
            if(in_array($module, $this->config->upgrade->openModules)) continue;

            $dirPath = $this->app->moduleRoot . $module;
            if(!$zfile->removeDir($dirPath)) $command[] = 'rm -f -r ' . $dirPath;
        }

        if(!empty($command))
        {
            $response['result']  = 'fail';
            $response['command'] = $command;
         }

        return $response;
    }

    /**
     * Replace the load path of the file.
     *
     * @param  string $filePath
     * @access public
     * @return void
     */
    public function replaceIncludePath($filePath)
    {
        $content = file_get_contents($filePath);
        if(strpos(basename($filePath), 'html'))
        {
            $content = preg_replace('#(include )(\'|")((../){2,})([a-z]+/)(?!ext/)([a-z]+/)#', '$1' . '$app->getModuleRoot() . ' . '$2$5$6', $content);

            $systemFiles = file_get_contents('systemfiles.txt');
            $systemFiles = str_replace('/', DS, $systemFiles);

            preg_match_all('#(include )(\'|")(../){2,}[a-z]+/ext/[a-z]+/(([a-z]+[.]?)+)#', $content, $matches);
            foreach($matches[0] as $fileName)
            {
                $fileName = preg_replace("#(include )('|\")((../){2,})#", "", $fileName);
                if(strpos($systemFiles, $fileName) !== false)
                {
                    $fileName = basename($fileName);
                    $content = preg_replace('#(include )(\'|")((../){2,})([a-z]+/ext/view/' . $fileName . ')#', '$1' . '$app->appRoot . ' . '$2extension/max/$5', $content);
                }
                else
                {
                    $fileName = basename($fileName);
                    $content = preg_replace('#(include )(\'|")((../){2,})([a-z]+/ext/view/' . $fileName . ')#', '$1' . '$app->appRoot . ' . '$2extension/custom/$5', $content);
                }
            }
        }
        else
        {
            $dirPath    = dirname($filePath);
            $dir        = str_replace($this->app->appRoot . 'extension' . DS . 'custom' .DS , '', $dirPath);
            $dirList    = explode(DS,  $dir);
            $moduleName = $dirList[0];

            $content = str_replace("include '../../control.php';", "helper::importControl('$moduleName');", $content);
            $content = str_replace("helper::import('../../control.php');", "helper::importControl('$moduleName');", $content);
            $content = str_replace('helper::import(dirname(dirname(dirname(__FILE__))) . "/control.php");', "helper::importControl('$moduleName');", $content);
        }
        file_put_contents($filePath, $content);
    }

    /**
     * Add groups default kanban private
     *
     * @access public
     * @return void
     */
    public function addDefaultKanbanPri()
    {
        /* Fix bug #17954. */
        $hasKanbanPri = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('kanban')->fetch();
        if(!$hasKanbanPri)
        {
            $this->app->loadLang('group');
            $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->in('admin,pm,po')->fetchPairs('id');
            foreach($groups as $groupID)
            {
                foreach($this->lang->resource->kanban as $method => $name)
                {
                    if(stripos($method, 'delete') === false)
                    {
                        $groupPriv = new stdclass();
                        $groupPriv->group  = $groupID;
                        $groupPriv->module = 'kanban';
                        $groupPriv->method = $method;

                        $this->dao->insert(TABLE_GROUPPRIV)->data($groupPriv)->exec();
                    }
                }
            }
        }
    }

    /**
     * Update the story reviewer when 12 version to 15.
     *
     * @param  string  $fromVersion
     * @access public
     * @return void
     */
    public function updateStoryReviewer()
    {
        $isOldVersion = false;
        $fromVersion  = str_replace('_', '.', $this->fromVersion);
        if(is_numeric($fromVersion[0]) and version_compare($fromVersion, '12.5.3', '<='))
        {
            $isOldVersion = true;
        }
        elseif($fromVersion[0] == 'p' and version_compare($fromVersion, 'pro9.0.3', '<='))
        {
            $isOldVersion = true;
        }
        elseif($fromVersion[0] == 'b' and version_compare($fromVersion, 'biz4.1.3', '<='))
        {
            $isOldVersion = true;
        }

        if(!$isOldVersion) return;

        $stories = $this->dao->select('t1.*,t2.PO,t2.createdBy')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.status')->in('draft,changed')
            ->fetchAll('id');

        foreach($stories as $storyID => $story)
        {
            if(!empty($story->assignedTo))
            {
                $story->reviewer = $story->assignedTo;
            }
            elseif(!empty($story->PO))
            {
                $story->reviewer = $story->PO;
            }
            else
            {
                $story->reviewer = $story->createdBy;
            }

            $data = new stdclass();
            $data->story      = $storyID;
            $data->version    = $story->version;
            $data->reviewer   = $story->reviewer;
            $data->result     = '';
            $data->reviewDate = '';

            $this->dao->insert(TABLE_STORYREVIEW)->data($data)->exec();
        }
    }

    /**
     * Update the project status.
     *
     * @access public
     * @return void
     */
    public function updateProjectStatus()
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('status')->eq('doing')
            ->andWhere('type')->eq('project')
            ->andWhere('realBegan')->eq('0000-00-00')
            ->fetchAll('id');

        if(empty($projects)) return;

        $projectIDList = array_keys($projects);

        $executions = $this->dao->select('id,project,min(realBegan) as minBegan')->from(TABLE_EXECUTION)
            ->where('status')->eq('doing')
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->in($projectIDList)
            ->groupBy('project')
            ->fetchAll();

        if(empty($executions)) return;

        foreach ($executions as $execution) $this->dao->update(TABLE_PROJECT)->set('realBegan')->eq($execution->minBegan)->where('id')->eq($execution->project)->exec();
    }

    /**
     * Change story need Review.
     *
     * @access public
     * @return void
     */
    public function changeStoryNeedReview()
    {
        $this->loadModel('story');
        $this->loadModel('setting');

        $rndNeedReview  = $this->setting->getItem('owner=system&vision=rnd&module=story&section=&key=needReview');
        $liteNeedReview = $this->setting->getItem('owner=system&vision=lite&module=story&section=&key=needReview');

        $data = new stdclass();
        $data->forceReview      = '';
        $data->forceReviewDepts = '';
        $data->forceReviewRoles = '';

        if(!empty($rndNeedReview))  $this->setting->setItems("system.story@rnd", $data);
        if(!empty($liteNeedReview)) $this->setting->setItems("system.story@lite", $data);

        $this->setting->deleteItems('owner=system&module=story&section=&key=forceReviewAll');
    }

    /**
     * The setlane permission is deleted. We need to replace setlane with editlanename and editlanecolor.
     *
     * @access public
     * @return void
     */
    public function replaceSetLanePriv()
    {
        $groupIDList = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)
            ->where('module')->eq('kanban')
            ->andWhere('method')->eq('setLane')
            ->fetchAll();

        if(!empty($groupIDList))
        {
            $this->dao->delete()->from(TABLE_GROUPPRIV)
                ->where('module')->eq('kanban')
                ->andWhere('method')->eq('setLane')
                ->exec();
        }

        foreach($groupIDList as $groupID)
        {
            $data = new stdClass();
            $data->group  = $groupID->group;
            $data->module = 'kanban';
            $data->method = 'editLaneName';
            $this->dao->insert(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'editLaneColor';
            $this->dao->insert(TABLE_GROUPPRIV)->data($data)->exec();
        }

        return true;
    }

    /**
     * Update path and grade of program, project and execution.
     *
     * @access public
     * @return bool
     */
    public function updateProjectData()
    {
        /* Process programs. */
        $programs = $this->dao->select('id,parent,grade,path')->from(TABLE_PROJECT)->where('type')->eq('program')->orderBy('parent_asc')->fetchAll('id');
        foreach($programs as $program)
        {
            if(!$program->parent)
            {
                $program->grade = 1;
                $program->path  = ",$program->id,";
            }
            else
            {
                $program->grade = $programs[$program->parent]->grade + 1;
                $program->path  = $programs[$program->parent]->path . "$program->id,";
            }

            $this->dao->update(TABLE_PROGRAM)
                ->set('path')->eq($program->path)
                ->set('grade')->eq($program->grade)
                ->where('id')->eq($program->id)->exec();
        }

        /* Process projects. */
        $projects = $this->dao->select('id,project,parent,grade,path')->from(TABLE_PROJECT)->where('type')->eq('project')->orderBy('parent_asc')->fetchAll('id');
        foreach($projects as $project)
        {
            if(!$project->parent)
            {
                $project->grade = 1;
                $project->path  = ",$project->id,";
            }
            else
            {
                $project->grade = $programs[$project->parent]->grade + 1;
                $project->path  = $programs[$project->parent]->path . "$project->id,";
            }

            $this->dao->update(TABLE_PROJECT)
                ->set('path')->eq($project->path)
                ->set('grade')->eq($project->grade)
                ->where('id')->eq($project->id)->exec();
        }

        /* Process executions. */
        $sprints = $this->dao->select('id,project,parent,grade,path')->from(TABLE_PROJECT)
            ->where('type')->ne('project')
            ->andWhere('type')->ne('program')
            ->orderBy('parent_asc')->fetchAll('id');

        foreach($sprints as $sprint)
        {
            if($sprint->parent == $sprint->project)
            {
                $sprint->grade = 1;
                $sprint->path  = ",$sprint->project,$sprint->id,";
            }
            else
            {
                $sprint->grade = 2;
                $sprint->path  = $sprints[$sprint->parent]->path . "$sprint->id,";
            }

            $this->dao->update(TABLE_EXECUTION)
                ->set('path')->eq($sprint->path)
                ->set('grade')->eq($sprint->grade)
                ->where('id')->eq($sprint->id)->exec();
        }

        return true;
    }

    /**
     * Move project admins to new table.
     *
     * @access public
     * @return void
     */
    public function moveProjectAdmins()
    {
        $adminGroupID  = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->fetch('id');
        $projectAdmins = $this->dao->select('account, project')->from(TABLE_USERGROUP)->where('`group`')->eq($adminGroupID)->fetchPairs();

        $i = 1;
        foreach($projectAdmins as $account => $projects)
        {
            if(!$account or !$projects) continue;

            $data = new stdclass();
            $data->group    = $i;
            $data->account  = $account;
            $data->projects = $projects;

            $this->dao->replace(TABLE_PROJECTADMIN)->data($data)->exec();

            $i ++;
        }

        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($adminGroupID)->exec();
    }

    /*
     * Insert story view of execution.
     *
     * @access public
     * @return bool
     */
    public function addStoryViewPriv()
    {
        $groupIdList = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)
            ->where('module')->eq('story')
            ->andWhere('method')->eq('view')
            ->fetchPairs('group');

        foreach($groupIdList as $groupID)
        {
            $this->dao->replace(TABLE_GROUPPRIV)
                ->set('`group`')->eq($groupID)
                ->set('module')->eq('execution')
                ->set('method')->eq('storyView')
                ->exec();
        }

        return true;
    }

    /*
     * Add review issue approval data.
     *
     * @access public
     * @return bool
     */
    public function addReviewIssusApprovalData()
    {
        $reviewIssues = $this->dao->select('id,review,type')->from(TABLE_REVIEWISSUE)
            ->where('type')->eq('review')
            ->andWhere('approval')->eq(0)
            ->andWhere('deleted')->eq('0')
            ->fetchAll('review');

        if(empty($reviewIssues)) return false;

        $reviewIds = array_unique(helper::arrayColumn($reviewIssues, 'review'));

        $approvalsPairs = $this->dao->select('objectID, max(id) as approval')->from(TABLE_APPROVAL)
            ->where('objectID')->in($reviewIds)
            ->andWhere('objectType')->eq('review')
            ->andWhere('result')->eq('fail')
            ->andWhere('deleted')->eq(0)
            ->groupBy('objectID')
            ->fetchAll('objectID');

        /* Add approval data. */
        foreach($reviewIssues as $reviewIssue)
        {
            if(!isset($approvalsPairs[$reviewIssue->review]->approval)) continue;
            $this->dao->update(TABLE_REVIEWISSUE)
                ->set('approval')->eq($approvalsPairs[$reviewIssue->review]->approval)
                ->where('review')->eq($reviewIssue->review)
                ->andWhere('type')->eq('review')
                ->andWhere('approval')->eq(0)
                ->andWhere('deleted')->eq('0')
                ->exec();
        }
    }

    /**
     * Xuan: Add `index` column to all message partition tables.
     *
     * @access public
     * @return bool
     */
    public function xuanAddMessageIndexColumns()
    {
        $prefix = $this->config->db->prefix;
        $tables = $this->dbh->query("SHOW TABLES LIKE '{$prefix}im_message\_%'")->fetchAll();
        $tables = array_filter(array_map(function($table) use ($prefix)
        {
            $tableName = current(array_values((array)$table));
            if(!preg_match("/{$prefix}im_message_[a-z]+/", $tableName)) return $tableName;
        },
            $tables
        ));
        if(empty($tables)) return true;

        $query = '';
        foreach($tables as $table) $query .= "ALTER TABLE `$table` ADD `index` int(11) unsigned DEFAULT 0 AFTER `date`;";

        $this->dbh->query($query);
        return !dao::isError();
    }

    /**
     * Xuan: Re-index messages.
     *
     * @access public
     * @return bool
     */
    public function xuanReindexMessages()
    {
        /** @var array[] $chatTablePairs Associations of chats and partition tables, without main table. */
        $chatTablePairs = array();

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        /* Fetch chat and message partition table associations. */
        $chatTableData = $this->dao->select('gid,tableName')->from(TABLE_IM_CHAT_MESSAGE_INDEX)->orderBy('id_asc')->fetchAll();
        foreach($chatTableData as $chatTable)
        {
            if(isset($chatTablePairs[$chatTable->gid]))
            {
                $chatTablePairs[$chatTable->gid][] = $chatTable->tableName;
                continue;
            }
            $chatTablePairs[$chatTable->gid] = array($chatTable->tableName);
        }

        /* Append all non-partitioned chats. */
        $allChats = $this->dao->select('gid')->from(TABLE_IM_CHAT)->fetchPairs();
        $nonPartitionedChats = array_diff(array_values($allChats), array_keys($chatTablePairs));
        foreach($nonPartitionedChats as $chat) $chatTablePairs[$chat] = array();

        /* Do index. */
        foreach($chatTablePairs as $chat => $tables)
        {
            $result = $this->xuanDoIndex($chat, $tables);
            if(!$result) return false;
        }

        return true;
    }

    /**
     * Xuan: Index messages of chat in partition tables and main table.
     *
     * @param  string $chat
     * @param  array  $tables
     * @return bool
     */
    public function xuanDoIndex($chat, $tables)
    {
        $messageIndex = 0;
        $tables[] = str_replace('`', '', TABLE_IM_MESSAGE);
        foreach($tables as $table)
        {
            $idIndices = array();

            $ids = $this->dao->select('id')->from("`$table`")->where('cgid')->eq($chat)->fetchAll('id');
            $ids = array_keys($ids);
            if(empty($ids)) continue;

            for($index = 1; $index <= count($ids); $index++) $idIndices[$ids[$index - 1]] = $index + $messageIndex;

            $queryData = array();
            foreach($idIndices as $id => $index) $queryData[] = "WHEN $id THEN $index";

            $query = "UPDATE `$table` SET `index` = (CASE `id` " . join(' ', $queryData) . " END) WHERE `id` IN(" . join(',', $ids) . ");";
            $this->dao->query($query);

            $messageIndex = max(array_values($idIndices));
        }
        $this->dao->update(TABLE_IM_CHAT)->set('lastMessageIndex')->eq($messageIndex)->where('gid')->eq($chat)->exec();
        return !dao::isError();
    }

    /**
     * Xuan: Set lastReadMessageIndex into table im_chatuser.
     *
     * @access public
     * @return bool
     */
    public function xuanUpdateLastReadMessageIndex()
    {
        $lastReadMessages =  $this->dao->select('lastReadMessage')->from(TABLE_IM_CHATUSER)->where('lastReadMessage')->ne(0)->fetchAll('lastReadMessage');
        $lastReadMessages = array_keys($lastReadMessages);
        if(empty($lastReadMessages)) return true;

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $messages = $this->loadModel('im')->messageGetList('', $lastReadMessages, null, '', '', false);
        if(empty($messages)) return;

        $queryData = array();
        foreach($messages as $message) $queryData[] = "WHEN {$message->id} THEN {$message->index}";

        $query = "UPDATE " . TABLE_IM_CHATUSER . " SET `lastReadMessageIndex` = (CASE `lastReadMessage` " . join(' ', $queryData) . " END) WHERE `id` IN(" . join(',', $lastReadMessages) . ");";
        $this->dao->query($query);

        return !dao::isError();
    }

    /**
     * Xuan: Fix chats without lastReadMessage.
     *
     * @access public
     * @return bool
     */
    public function xuanFixChatsWithoutLastRead()
    {
        $zeroLastReadChats = $this->dao->select('cgid')->from(TABLE_IM_CHATUSER)->where('lastReadMessage')->eq(0)->fetchAll('cgid');
        $zeroLastReadChats = array_keys($zeroLastReadChats);
        if(empty($zeroLastReadChats)) return true;

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $lastMessages = $this->dao->select('MAX(`index`), cgid')->from(TABLE_IM_MESSAGE)->where('cgid')->in($zeroLastReadChats)->groupBy('cgid')->fetchAll('cgid');
        if(empty($lastMessages)) return true;

        $maxIndex = 'MAX(`index`)';
        $queryData = array();
        foreach($lastMessages as $cgid => $lastMessage) $queryData[] = "WHEN '{$cgid}' THEN {$lastMessage->$maxIndex}";

        $query = "UPDATE " . TABLE_IM_CHATUSER . " SET `lastReadMessageIndex` = (CASE `cgid` " . join(' ', $queryData) . " END) WHERE `cgid` IN('" . join("','", array_keys($lastMessages)) . "');";
        $this->dao->query($query);

        return !dao::isError();
    }

    /**
     * Process bug link bug.
     *
     * @access public
     * @return void
     */
    public function processBugLinkBug()
    {
        $bugs = $this->dao->select('id,linkBug')->from(TABLE_BUG)->where('linkBug')->ne('')->fetchPairs();
        foreach($bugs as $bugID => $linkBugs)
        {
            $linkBugs = explode(',', $linkBugs);
            $this->dao->update(TABLE_BUG)->set("linkBug = TRIM(BOTH ',' from CONCAT(linkbug, ',$bugID'))")->where('id')->in($linkBugs)->andWhere('id')->ne($bugID)->andWhere("CONCAT(',', linkBug, ',')")->notlike("%,$bugID,%")->exec();
        }

        return !dao::isError();
    }

    /**
     * Process created information.
     *
     * @access public
     * @return void
     */
    public function processCreatedInfo()
    {
        $objectTypes = array('productplan', 'release', 'testtask', 'build');

        $actions = $this->dao->select('objectType, objectID, actor, date')->from(TABLE_ACTION)->where('objectType')->in($objectTypes)->andWhere('action')->eq('opened')->fetchGroup('objectType');
        foreach($actions as $objectType => $objectActions)
        {
            foreach($objectActions as $action)
            {
                $this->dao->update($this->config->objectTables[$objectType])->set('createdBy')->eq($action->actor)->set('createdDate')->eq($action->date)->where('id')->eq($action->objectID)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Update approval process in Workflow.
     *
     * @access public
     * @return void
     */
    public function updateApproval()
    {
        /* Judge whether the action has opened the approval process before. */
        /* 判断动作 看之前是否开启过审批流 */
        $actions = $this->dao->select('id, module, action, createdDate')->from(TABLE_WORKFLOWACTION)
            ->where('role')->eq('approval')
            ->andWhere('action')->in('submit, cancel, review')
            ->fetchAll('id');

        foreach($actions as $id => $action)
        {
            $module     = $action->module;
            $actionCode = $action->action;

            $this->dao->update(TABLE_WORKFLOWACTION)->set('action')->eq('approval' . $action->action)->where('id')->eq($id)->exec();
            /* Change the approval action of the module that has already enabled the approval function */
            /* 改原来已经开启过审批功能的模块的审批动作 */
            if(isset($this->config->upgrade->recoveryActions->{$module}->{$actionCode}))
            {
                $data = array_merge($this->config->upgrade->defaultActions, $this->config->upgrade->recoveryActions->{$module}->{$actionCode});
                if(isset($data['hasLite']) && $data['hasLite'] === true)
                {
                    unset($data['hasLite']);
                    $liteData = $data;
                    $liteData['vision'] = 'lite';
                    $this->dao->insert(TABLE_WORKFLOWACTION)->data($liteData)->exec();
                }
                $this->dao->insert(TABLE_WORKFLOWACTION)->data($data)->exec();
            }

            /* Change history */
            /* 改历史记录 */
            $this->dao->update(TABLE_ACTION)->set('action')->eq('approval' . $action->action)->where('objectType')->eq($module)->andWhere('action')->eq($action->action)->andWhere('date')->gt($action->createdDate)->exec();

            /* Change the action field of the workflowlayout table */
            /* 改workflowlayout表的action字段 */
            $this->dao->update(TABLE_WORKFLOWLAYOUT)->set('action')->eq('approval' . $action->action)->where('module')->eq($module)->andWhere('action')->eq($action->action)->exec();
        }

        return !dao::isError();
    }

    /**
     * Process createdBy of conditions.
     *
     * @access public
     * @return bool
     */
    public function processCreatedBy()
    {
        $this->app->loadLang('workflow');
        $this->app->loadModuleConfig('workflow');

        $modules = $this->dao->select('module')->from(TABLE_WORKFLOW)->where('buildin')->eq('1')->andWhere('approval')->eq('enabled')->andWhere('module')->in(array_keys($this->config->workflow->buildin->createdBy))->fetchPairs();
        $actions = $this->dao->select('id, module, conditions')->from(TABLE_WORKFLOWACTION)->where('module')->in($modules)->andWhere('action')->in('submit, cancel, edit, delete')->fetchAll('id');

        foreach($actions as $id => $action)
        {
            $conditions = json_decode($action->conditions);
            if(empty($conditions)) continue;

            foreach($conditions as $index => $condition)
            {
                foreach($condition->fields as $field)
                {
                    if($field->field == 'createdBy' && zget($this->config->workflow->buildin->createdBy, $action->module, '')) $field->field = zget($this->config->workflow->buildin->createdBy, $action->module);
                }
                $conditions[$index] = $condition;
            }

            $this->dao->update(TABLE_WORKFLOWACTION)->set('conditions')->eq(json_encode($conditions))->where('id')->eq($id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Update story search index.
     *
     * @access public
     * @return void
     */
    public function updateSearchIndex()
    {
        $requirementIds = $this->dao->select('t1.id')->from(TABLE_SEARCHINDEX)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.objectID = t2.id')
            ->where('t1.objectType')->eq('story')
            ->andWhere('t2.type')->eq('requirement')
            ->fetchPairs('id');
        $this->dao->update(TABLE_SEARCHINDEX)->set('objectType')->eq('requirement')->where('id')->in($requirementIds)->exec();
    }

    /**
     * Add required rule to the built-in workflow status field.
     *
     * @access public
     * @return void
     */
    public function addDefaultRuleToWorkflow()
    {
        $notemptyRule = $this->dao->select('id')->from(TABLE_WORKFLOWRULE)->where('rule')->eq('notempty')->fetch();
        if(empty($notemptyRule)) return false;

        $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('field')->eq('status')->andWhere('buildin')->eq(1)->fetchAll();

        foreach($fields as $field)
        {
            if(strpos(',' . $field->rules . ',', ',' . $notemptyRule->id . ',') !== false) continue;

            $rules = $notemptyRule->id;
            if(!empty($field->rules)) $rules = $field->rules . ',' . $rules;

            $this->dao->update(TABLE_WORKFLOWFIELD)->set('rules')->eq($rules)->where('id')->eq($field->id)->exec();
        }
        return !dao::isError();
    }

    /**
     * Add workflow actions.
     *
     * @param  int    $version
     * @access public
     * @return void
     */
    public function addFlowActions($version)
    {
        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $upgradeLang   = $this->lang->workflowaction->upgrade[$version];
        $upgradeConfig = $this->config->workflowaction->upgrade[$version];
        if(empty($upgradeLang) || empty($upgradeConfig)) return true;

        $this->lang->workflowaction->upgrade   = new stdclass();
        $this->config->workflowaction->upgrade = new stdclass();
        foreach($upgradeLang as $module => $labels)
        {
            $this->lang->workflowaction->upgrade->actions = $labels;
            foreach($upgradeConfig[$module] as $code => $config) $this->config->workflowaction->upgrade->$code = $config;

            $flow = $this->workflow->getByModule($module);
            $this->workflow->createActions($flow, 'upgrade');

            $this->dao->update(TABLE_WORKFLOWACTION)->set('extensionType')->eq('none')->set('role')->eq('buildin')->where('module')->eq($module)->andWhere('action')->in(array_keys($labels))->exec();
            foreach($labels as $method => $label)
            {
                $workflowAction = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($method)->fetch();
                if(!$workflowAction) continue;

                unset($workflowAction->id);
                $workflowAction->vision = $workflowAction->vision == 'lite' ? 'rnd' : 'lite';
                $this->dao->replace(TABLE_WORKFLOWACTION)->data($workflowAction)->exec();
            }
        }
        return !dao::isError();
    }

    /**
     * Add flow fields.
     *
     * @param  int    $version
     * @access public
     * @return bool
     */
    public function addFlowFields($version)
    {
        $this->loadModel('workflowfield');

        $upgradeLang   = $this->lang->workflowfield->upgrade[$version];
        $upgradeConfig = $this->config->workflowfield->upgrade[$version];

        $now = helper::now();
        foreach($upgradeLang as $module => $fields)
        {
            $field = new stdclass();
            $field->buildin     = '1';
            $field->role        = 'buildin';
            $field->module      = $module;
            $field->createdBy   = $this->app->user->account;
            $field->createdDate = $now;

            foreach($fields as $code => $name)
            {
                $field->field = $code;
                $field->name  = $name;

                $fieldConfig = isset($upgradeConfig[$module][$code]) ? $upgradeConfig[$module][$code] : array();
                foreach($fieldConfig as $key => $value) $field->$key = $value;

                $this->dao->replace(TABLE_WORKFLOWFIELD)->data($field)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Process review linkages of approval.
     *
     * @access public
     * @return bool
     */
    public function processReviewLinkages()
    {
        $linkagePairs = $this->dao->select('id, linkages')->from(TABLE_WORKFLOWACTION)->where('action')->eq('approvalreview')->andWhere('role')->eq('approval')->fetchPairs();

        $oldLinkages = array();
        $oldLinkages[0]['sources'][0]['field']    = 'reviewResult';
        $oldLinkages[0]['sources'][0]['operator'] = '==';
        $oldLinkages[0]['sources'][0]['value']    = 'reject';
        $oldLinkages[0]['targets'][0]['field']    = 'reviewOpinion';
        $oldLinkages[0]['targets'][0]['status']   = 'show';

        $newLinkages = array();
        $newLinkages[0]['sources'][0]['field']    = 'reviewResult';
        $newLinkages[0]['sources'][0]['operator'] = '==';
        $newLinkages[0]['sources'][0]['value']    = 'pass';
        $newLinkages[0]['targets'][0]['field']    = 'reviewOpinion';
        $newLinkages[0]['targets'][0]['status']   = 'hide';

        foreach($linkagePairs as $id => $linkages)
        {
            if(helper::jsonEncode($oldLinkages) == $linkages)
            {
                $this->dao->update(TABLE_WORKFLOWACTION)->set('linkages')->eq(helper::jsonEncode($newLinkages))->where('id')->eq($id)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Update story status.
     *
     * @access public
     * @return void
     */
    public function updateStoryStatus()
    {
        /* After cancel the review of changed story, the story status should be "changing". */
        $this->dao->update(TABLE_STORY)->set('status')->eq('changing')->where('status')->eq('draft')->andWhere('version')->gt(1)->exec();

        /* The draft story with reviewers should be "reviewing". */
        $reviewingStories = $this->dao->select('story')->from(TABLE_STORYREVIEW)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id and t1.version = t2.version')
            ->where('t2.status')->eq('draft')
            ->andWhere('t2.version')->eq(1)
            ->fetchPairs();
        $this->dao->update(TABLE_STORY)->set('status')->eq('reviewing')->where('id')->in($reviewingStories)->exec();

        return !dao::isError();
    }

    /**
     * Change FULLTEXT index for searchindex table.
     *
     * @access public
     * @return void
     */
    public function rebuildFULLTEXT()
    {
        try
        {
            $table   = TABLE_SEARCHINDEX;
            $stmt    = $this->dao->query("show index from $table");
            $indexes = array();
            while($index = $stmt->fetch())
            {
                if($index->Index_type != 'FULLTEXT') continue;
                $indexes[$index->Key_name] = $index->Key_name;
            }

            if(!isset($indexes['title_content'])) $this->dao->exec( "ALTER TABLE {$table} ADD FULLTEXT `title_content` (`title`, `content`)");
            if(isset($indexes['title'])) $this->dao->exec( "ALTER TABLE {$table} DROP INDEX `title`");
            if(isset($indexes['content'])) $this->dao->exec( "ALTER TABLE {$table} DROP INDEX `content`");
        }
        catch(PDOException $e){}

        return true;
    }

    /**
     * Check whether the field exists.
     *
     * @param  string  $table
     * @param  string  $field
     * @access public
     * @return bool
     */
    public function checkFieldsExists($table, $field)
    {
        $result = $this->dbh->query("show columns from `$table` like '$field'");

        return $result->rowCount() > 0;
    }

    /**
     * Update OS and browser of bug.
     *
     * @access public
     * @return bool
     */
    public function updateOSAndBrowserOfBug()
    {
        $existOSList        = $this->dao->select('distinct os')->from(TABLE_BUG)->where('os')->ne('')->fetchPairs();
        $existBrowserList   = $this->dao->select('distinct browser')->from(TABLE_BUG)->where('os')->ne('')->fetchPairs();
        $deletedOSList      = array('vista', 'win2012', 'win2008', 'win2003', 'win2000', 'wp8', 'wp7', 'symbian', 'freebsd');
        $deletedBrowserList = array('ie7', 'ie6', 'firefox4', 'firefox3', 'firefox2', 'opera11', 'opera10', 'opera9', 'maxthon', 'uc');
        $existList          = array_merge($existOSList, $existBrowserList);
        $deletedList        = array_merge($deletedOSList, $deletedBrowserList);

        foreach($deletedList as $deletedLang)
        {
            if(in_array($deletedLang, $existList)) continue;
            $this->dao->delete()->from(TABLE_LANG)->where('module')->eq('bug')->andWhere('`key`')->eq($deletedLang)->andWhere('`system`')->eq(1)->andWhere('vision')->eq('rnd')->exec();
        }

        $this->dao->update(TABLE_LANG)->set('value')->eq('Mac OS')->where('module')->eq('bug')->andWhere('`key`')->eq('osx')->andWhere('value')->eq('OS X')->exec();
        $this->dao->update(TABLE_LANG)->set('value')->eq('Opera 系列')->where('module')->eq('bug')->andWhere('`key`')->eq('opera')->andWhere('value')->eq('opera 系列')->exec();
        return true;
    }

    /**
     * Add user requirement privilege when URAndSR is open.
     *
     * @access public
     * @return bool
     */
    public function addURPriv()
    {
        if(empty($this->config->URAndSR)) return true;

        $sql = "REPLACE INTO " . TABLE_GROUPPRIV . " SELECT `group`,'requirement' as 'module',`method` FROM " . TABLE_GROUPPRIV . " WHERE `module` = 'story' AND `method` in ('create', 'batchEdit', 'edit', 'export', 'delete', 'view', 'change', 'review', 'batchReview', 'recall', 'close', 'batchClose', 'assignTo', 'batchAssignTo', 'activate', 'report', 'linkStory', 'batchChangeBranch', 'batchChangeModule', 'linkStories', 'batchEdit', 'import', 'exportTemplate')";
        $this->dbh->exec($sql);
        return true;
    }

    /**
     * Sync case to project|execution if case create from import.
     *
     * @access public
     * @return void
     */
    public function syncCase2Project()
    {
        if(strpos($this->fromVersion, 'max') === false) return;

        $linkStoryCases   = $this->dao->select('id, story, version, product')->from(TABLE_CASE)->where('story')->ne('0')->fetchAll('id');
        $linkProjectCases = $this->dao->select('`case`, project')->from(TABLE_PROJECTCASE)->where('`case`')->ne('0')->andWhere('project')->ne('0')->fetchGroup('case', 'project');

        if(empty($linkStoryCases)) return true;

        $projectList = $this->dao->select('t1.project, t1.story, t1.product')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t2.status')->ne('closed')
            ->fetchGroup('story', 'project');

        if(empty($projectList)) return true;

        foreach($linkStoryCases as $caseID => $case)
        {
            /* If story unlink project continue. */
            if(!isset($projectList[$case->story])) continue;

            $storyProjects = $projectList[$case->story];

            $lastOrder = 1;
            foreach($storyProjects as $projectID => $value)
            {
                /* If case linked project continue.*/
                if(isset($linkProjectCases[$caseID][$projectID])) continue;

                $data = new stdclass();
                $data->project = $projectID;
                $data->product = $case->product;
                $data->case    = $caseID;
                $data->version = $case->version;
                $data->order   = $lastOrder ++;
                $this->dao->insert(TABLE_PROJECTCASE)->data($data)->exec();
            }
        }
    }

    /**
     * Update story file version.
     *
     * @access public
     * @return void
     */
    public function updateStoryFile()
    {
        $storyFileList = $this->dao->select('*')->from(TABLE_FILE)->where('objectType')->in('story,requirement')->andWhere('extra')->ne('editor')->fetchAll('id');

        $storyFiles = array();
        foreach($storyFileList as $file)
        {
            if(!is_numeric($file->extra)) continue;

            if(!isset($storyFiles[$file->objectID])) $storyFiles[$file->objectID] = '';

            $storyFiles[$file->objectID] .= "$file->id,";
        }

        foreach($storyFiles as $storyID => $files) $this->dao->update(TABLE_STORYSPEC)->set('files')->eq($files)->where('story')->eq($storyID)->exec();

        return true;
    }

    /*
     * Convert task team to table: zt_taskteam.
     *
     * @access public
     * @return void
     */
    public function convertTaskteam()
    {
        $oldTeamGroup = $this->dao->select('root as task, account, estimate, consumed, `left`')->from(TABLE_TEAM)->where('type')->eq('task')->fetchGroup('task');
        foreach($oldTeamGroup as $taskID => $oldTeams)
        {
            $order = 0;
            foreach($oldTeams as $oldTeam)
            {
                $oldTeam->order  = $order;
                $oldTeam->status = 'wait';
                if($oldTeam->consumed > 0 and $oldTeam->left > 0)  $oldTeam->status = 'doing';
                if($oldTeam->consumed > 0 and $oldTeam->left == 0) $oldTeam->status = 'done';

                $this->dao->insert(TABLE_TASKTEAM)->data($oldTeam)->exec();

                $this->dao->update(TABLE_TASKESTIMATE)->set('`order`')->eq($order)->where('task')->eq($oldTeam->task)->andWhere('account')->eq($oldTeam->account)->exec();
                $this->dao->update(TABLE_EFFORT)->set('`order`')->eq($order)->where('objectType')->eq('task')->andWhere('objectID')->eq($oldTeam->task)->andWhere('account')->eq($oldTeam->account)->exec();
                $order ++;
            }
        }

        $this->dao->delete()->from(TABLE_TEAM)->where('type')->eq('task')->exec();
    }

    /**
     * Convert estimate to effort.
     *
     * @access public
     * @return void
     */
    public function convertEstToEffort()
    {
        $estimates = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->orderBy('id')->fetchAll();

        $this->app->loadLang('task');
        $this->loadModel('action');
        foreach($estimates as $estimate)
        {
            $relation = $this->action->getRelatedFields('task', $estimate->task);

            $effort = new stdclass();
            $effort->objectType = 'task';
            $effort->objectID   = $estimate->task;
            $effort->product    = $relation['product'];
            $effort->project    = (int)$relation['project'];
            $effort->execution  = (int)$relation['execution'];
            $effort->account    = $estimate->account;
            $effort->work       = empty($estimate->work) ? $this->lang->task->process : $estimate->work;
            $effort->date       = $estimate->date;
            $effort->left       = $estimate->left;
            $effort->consumed   = $estimate->consumed;
            $effort->vision     = $this->config->vision;
            $effort->order      = $estimate->order;

            $this->dao->insert(TABLE_EFFORT)->data($effort)->exec();
            $this->dao->delete()->from(TABLE_TASKESTIMATE)->where('id')->eq($estimate->id)->exec();
        }
        return true;
    }

    /**
     * Xuan: Set ownedBy for group chats without it.
     *
     * @access public
     * @return bool
     */
    public function xuanSetOwnedByForGroups()
    {
        $this->dao->update(TABLE_IM_CHAT)->set('ownedBy = createdBy')->where('ownedBy')->eq('')->exec();

        return !dao::isError();
    }

    /**
     * Xuan: Recover created date for chats.
     *
     * @access public
     * @return bool
     */
    public function xuanRecoverCreatedDates()
    {
        $chats = $this->dao->select('gid, id')->from(TABLE_IM_CHAT)
            ->where('createdDate')->eq('0000-00-00 00:00:00')
            ->fetchPairs('gid');
        if(empty($chats)) return true;

        $createdDateData = array();

        /* Try query earliest message date indexed. */
        $indexedMinDates = $this->dao->select('gid, MIN(startDate)')->from(TABLE_IM_CHAT_MESSAGE_INDEX)
            ->where('gid')->in(array_keys($chats))
            ->groupBy('gid')
            ->fetchPairs('gid');

        /* Then try query earliest message date non-indexed from master table. */
        $queryChats = array_diff(array_keys($chats), array_keys($indexedMinDates));
        $minDates = $this->dao->select('cgid, MIN(date)')->from(TABLE_IM_MESSAGE)
            ->where('cgid')->in($queryChats)
            ->groupBy('cgid')
            ->fetchPairs('cgid');

        $knownMinDates = array_merge($indexedMinDates, $minDates);

        $remainingChats = $chats;
        foreach($chats as $cgid => $cid)
        {
            if(isset($knownMinDates[$cgid]))
            {
                $createdDateData[$cid] = $knownMinDates[$cgid];
                unset($remainingChats[$cgid]);
            }
        }

        /* Use other dates for chats without messages. */
        $chatDates = $this->dao->select('id, gid, editedDate, lastActiveTime, dismissDate')->from(TABLE_IM_CHAT)
            ->where('gid')->in(array_keys($remainingChats))
            ->fetchAll('gid');
        $chatDates = array_map(function($chatDate)
        {
            $dates = array_filter(array($chatDate->editedDate, $chatDate->lastActiveTime, $chatDate->dismissDate), function($date)
            {
                return $date != '0000-00-00 00:00:00';
            });
            $minDate = min($dates);
            return $minDate;
        }, $chatDates);

        $knownMinDates = array_merge($knownMinDates, $chatDates);
        if(empty($knownMinDates)) return true;

        $queryData = array();
        foreach($knownMinDates as $gid => $date) $queryData[] = "WHEN {$chats[$gid]} THEN '{$date}'";

        if(empty($queryData)) return true;

        $query = "UPDATE " . TABLE_IM_CHAT . " SET `createdDate` = (CASE `id` " . join(' ', $queryData) . " END) WHERE `id` IN(" . join(",", array_values($chats)) . ");";
        $this->dao->query($query);

        return !dao::isError();
    }

    /**
     * Xuan: Set index range for chats in chat partition index table.
     *
     * @access public
     * @return bool
     */
    public function xuanSetPartitionedMessageIndex()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        /* Fetch chat and message partition table associations with message range. */
        $chatTableData = $this->dao->select('gid, tableName, start, end')->from(TABLE_IM_CHAT_MESSAGE_INDEX)
            ->where('startIndex')->eq(0)
            ->orWhere('endIndex')->eq(0)
            ->orderBy('id_asc')
            ->fetchAll();
        if(empty($chatTableData)) return true;

        /* Sort ranges by table. */
        $tableRanges = array();
        foreach($chatTableData as $chatTable)
        {
            if(isset($tableRanges[$chatTable->tableName]))
            {
                $tableRanges[$chatTable->tableName]->start[] = $chatTable->start;
                $tableRanges[$chatTable->tableName]->end[]   = $chatTable->end;
                continue;
            }
            $tableRanges[$chatTable->tableName] = (object)array('start' => array($chatTable->start), 'end' => array($chatTable->end));
        }

        /* Query corresponding message indice. */
        foreach($tableRanges as $tableName => $tableRange)
        {
            $ids = array_merge($tableRange->start, $tableRange->end);
            $ids = array_unique($ids);
            $indexPairs = $this->dao->select('id, `index`')->from("`$tableName`")
                ->where('id')->in($ids)
                ->fetchPairs('id');
            $tableRanges[$tableName]->indexPairs = $indexPairs;
        }

        /* Set startIndice and endIndice. */
        foreach($tableRanges as $tableRange)
        {
            $queryData = array();
            foreach($tableRange->start as $id) $queryData[] = "WHEN $id THEN {$tableRange->indexPairs[$id]}";
            $query = "UPDATE " . TABLE_IM_CHAT_MESSAGE_INDEX . " SET `startIndex` = (CASE `start` " . join(' ', $queryData) . " END) WHERE `start` IN(" . join(',', $tableRange->start) . ");";
            $this->dao->query($query);

            $queryData = array();
            foreach($tableRange->end as $id) $queryData[] = "WHEN $id THEN {$tableRange->indexPairs[$id]}";
            $query = "UPDATE " . TABLE_IM_CHAT_MESSAGE_INDEX . " SET `endIndex` = (CASE `end` " . join(' ', $queryData) . " END) WHERE `end` IN(" . join(',', $tableRange->end) . ");";
            $this->dao->query($query);
        }
    }

    /**
     * Fix weekly report.
     *
     * @access public
     * @return bool
     */
    public function fixWeeklyReport()
    {
        if(!isset($this->app->user)) $this->app->user = new stdclass();
        $this->app->user->admin = true;

        $this->loadModel('weekly');
        $projects = $this->dao->select('id,begin,end')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('model')->eq('waterfall')->fetchAll('id');

        $today = helper::today();
        foreach($projects as $projectID => $project)
        {
            if(helper::isZeroDate($project->begin) or helper::isZeroDate($project->end)) continue;

            $begin = $project->begin;
            $end   = $today > $project->end ? $project->end : $today;

            $beginTimestame = strtotime($begin);
            $endTimestame   = strtotime($end);
            while($beginTimestame <= $endTimestame)
            {
                $this->weekly->save($projectID, $begin);

                $beginTimestame += 7 * 24 * 3600;
                $begin = date('Y-m-d', $beginTimestame);
            }
        }
        return true;
    }

    /**
     * Historical projects are upgraded by project.
     *
     * @param  int    $programID
     * @param  string $fromMode
     * @access public
     * @return bool
     */
    public function upgradeInProjectMode($programID, $fromMode = '')
    {
        $this->loadModel('action');
        $now     = helper::now();
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        $noMergedSprints = $this->getNoMergedSprints();
        if(!$noMergedSprints) return true;

        foreach($noMergedSprints as $sprint)
        {
            $project = new stdclass();
            $project->name           = $sprint->name;
            $project->desc           = $sprint->desc;
            $project->type           = 'project';
            $project->model          = 'scrum';
            $project->parent         = $programID;
            $project->status         = $sprint->status;
            $project->begin          = $sprint->begin;
            $project->end            = isset($sprint->end) ? $sprint->end : LONG_TIME;
            $project->realBegan      = zget($sprint, 'realBegan', '');
            $project->realEnd        = zget($sprint, 'realEnd', '');
            $project->days           = $this->computeDaysDelta($project->begin, $project->end);
            $project->PM             = $sprint->PM;
            $project->auth           = 'extend';
            $project->openedBy       = $account;
            $project->openedDate     = $now;
            $project->openedVersion  = $this->config->version;
            $project->lastEditedBy   = $account;
            $project->lastEditedDate = $now;
            $project->grade          = 2;
            $project->acl            = $sprint->acl == 'open' ? 'open' : 'private';
            if($fromMode == 'classic')
            {
                $project->multiple = '0';
                $project->code     = $sprint->code;
                $project->team     = $sprint->team;
            }

            $this->dao->insert(TABLE_PROJECT)->data($project)->exec();
            if(dao::isError()) return false;

            $projectID = $this->dao->lastInsertId();

            $this->action->create('project', $projectID, 'openedbysystem');
            if($project->status == 'closed') $this->action->create('project', $projectID, 'closedbysystem');

            $project->id = $projectID;
            if($fromMode == 'classic')
            {
                $this->dao->update(TABLE_PROJECT)->set('multiple')->eq('0')->where('id')->eq($sprint->id)->exec();
                $this->dao->update(TABLE_DOCLIB)->set('project')->eq($projectID)->set('type')->eq('project')->set('execution')->eq(0)->where('execution')->eq($sprint->id)->andWhere('type')->eq('execution')->exec();
                $this->dao->update(TABLE_DOC)->set('project')->eq($projectID)->set('execution')->eq(0)->where('execution')->eq($sprint->id)->exec();
            }
            else
            {
                $this->createProjectDocLib($project);
            }

            $productIdList = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($sprint->id)->fetchPairs();
            $this->processMergedData($programID, $projectID, null, $productIdList, array($sprint->id));
        }

        $this->fixProjectPath($programID);

        $productIdList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq('0')->fetchPairs();
        $this->dao->update(TABLE_MODULE)->set('root')->eq($programID)->where('type')->eq('line')->andWhere('root')->eq('0')->exec();
        $this->computeProductAcl($productIdList, $programID, null);

        if(dao::isError()) return false;
        return true;
    }

    /**
     * Historical projects are upgraded by execution.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function upgradeInExecutionMode($programID)
    {
        $this->loadModel('action');
        $now     = helper::now();
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        $noMergedSprints = $this->getNoMergedSprints();
        if(!$noMergedSprints) return true;

        $projects = array();
        foreach($noMergedSprints as $sprint)
        {
            $year = date('Y', strtotime($sprint->openedDate));
            $projects[$year][$sprint->id] = $sprint;
        }

        foreach($projects as $year => $sprints)
        {
            $project = new stdclass();
            $project->name           = $year > 0 ? $year : $this->lang->upgrade->unknownDate;
            $project->type           = 'project';
            $project->model          = 'scrum';
            $project->parent         = $programID;
            $project->team           = $project->name;
            $project->auth           = 'extend';
            $project->begin          = '';
            $project->end            = '';
            $project->openedBy       = $account;
            $project->openedDate     = $now;
            $project->openedVersion  = $this->config->version;
            $project->lastEditedBy   = $account;
            $project->lastEditedDate = $now;
            $project->grade          = 2;
            $project->acl            = 'open';

            $projectStatus = 'closed';
            foreach($sprints as $sprint)
            {
                if(!$project->begin || $sprint->begin < $project->begin) $project->begin = $sprint->begin;
                if(!$project->end   || $sprint->end   > $project->end)   $project->end   = $sprint->end;
                if($sprint->status != 'closed') $projectStatus = 'doing';
            }
            $project->status = $projectStatus;
            $project->days   = $this->computeDaysDelta($project->begin, $project->end);

            $this->dao->insert(TABLE_PROJECT)->data($project)->exec();
            if(dao::isError()) return false;

            $projectID = $this->dao->lastInsertId();

            $this->action->create('project', $projectID, 'openedbysystem');
            if($project->status == 'closed') $this->action->create('project', $projectID, 'closedbysystem');

            $project->id = $projectID;
            $this->createProjectDocLib($project);

            $productIdList = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($sprints))->fetchPairs();
            $this->processMergedData($programID, $projectID, null, $productIdList, array_keys($sprints));
        }

        $this->fixProjectPath($programID);

        $productIdList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq('0')->fetchPairs();
        $this->dao->update(TABLE_MODULE)->set('root')->eq($programID)->where('type')->eq('line')->andWhere('root')->eq('0')->exec();
        $this->computeProductAcl($productIdList, $programID, null);

        if(dao::isError()) return false;
        return true;
    }

    /**
     * Get sprints has not been merged.
     *
     * @access public
     * @return array
     */
    public function getNoMergedSprints()
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('project')->eq(0)
            ->andWhere('vision')->eq('rnd')
            ->andWhere('type')->eq('sprint')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Create doc lib for project.
     *
     * @param  object  $project
     * @access public
     * @return void
     */
    public function createProjectDocLib($project)
    {
        $this->app->loadLang('doc');

        $lib = new stdclass();
        $lib->project = $project->id;
        $lib->name    = $this->lang->doclib->main['project'];
        $lib->type    = 'project';
        $lib->main    = '1';
        $lib->acl     = $project->acl != 'program' ? $project->acl : 'custom';
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
    }

    /**
     * Fix the project path under the program.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function fixProjectPath($programID)
    {
        $this->dao->update(TABLE_PROJECT)
            ->set("path = CONCAT(',{$programID},', id, ',')")->set("`order` = `id` * 5")
            ->where('type')->eq('project')
            ->andWhere('parent')->eq($programID)
            ->andWhere('grade')->eq('2')
            ->exec();
    }

    /**
     * Relate default program.
     *
     * @param  int $programID
     * @access public
     * @return bool
     */
    public function relateDefaultProgram($programID)
    {
        $this->dao->update(TABLE_PRODUCT)->set('program')->eq($programID)->where('program')->eq(0)->exec();

        $this->dao->update(TABLE_MODULE)->set('root')->eq($programID)->where('type')->eq('line')->andWhere('root')->eq('0')->exec();

        $this->dao->update(TABLE_PROJECT)->set('parent')->eq($programID)->set("path = CONCAT(',{$programID}', path)")->set('grade = grade + 1')->where('type')->eq('project')->andWhere('parent')->eq(0)->andWhere('grade')->eq(1)->exec();

        return !dao::isError();
    }

    /**
     * Check history data form light mode.
     * 检查轻量管理模式历史数据是否存在
     *
     * @access public
     * @return array
     */
    public function checkHistoryDataForLightMode()
    {
        $returnData = array(
            'ur' => false,
            'cmmi' => false,
            'waterfall' => false,
            'assetlib' => false,
        );

        if($this->config->systemMode == 'ALM')
        {
            /* User requriement */
            $requirementStory = $this->dao->select('count(1) as total')->from(TABLE_STORY)->where('type')->eq('requirement')->andWhere('deleted')->eq('0')->fetch('total');
            if($requirementStory > 0) $returnData['ur'] = true;
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                /* issue,risk,opportunity,process,QA,meeting */
                $issue = $this->dao->select('count(1) as total')->from(TABLE_ISSUE)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetch('total');
                if($issue > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }

                $risk = $this->dao->select('count(1) as total')->from(TABLE_RISK)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetch('total');
                if($risk > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }

                $opportunity = $this->dao->select('count(1) as total')->from(TABLE_OPPORTUNITY)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetch('total');
                if($opportunity > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }

                $process = $this->dao->select('count(1) as total')->from(TABLE_PROCESS)
                    ->where('deleted')->eq(0)
                    ->fetch('total');
                if($process > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }

                $auditplans = $this->dao->select('count(1) as total')->from(TABLE_AUDITPLAN)
                    ->where('deleted')->eq(0)
                    ->fetch('total');
                if($auditplans > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }

                $meeting = $this->dao->select('count(1) as total')->from(TABLE_MEETING)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetch('total');
                if($meeting > 0)
                {
                    $returnData['cmmi'] = true;
                    goto next;
                }
            }

            next:
            /* Waterfull mode */
            $waterfull = $this->dao->select('count(1) as total')->from(TABLE_PROJECT)
                ->where('model')->eq('waterfall')
                ->andWhere('deleted')->eq(0)
                ->fetch('total');
            if($waterfull > 0) $returnData['waterfall'] = true;

            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                /* assetlib */
                $assetlib = $this->dao->select('count(1) as total')->from(TABLE_ASSETLIB)
                    ->where('deleted')->eq(0)
                    ->fetch('total');
                if($assetlib > 0) $returnData['assetlib'] = true;
            }
        }

        return $returnData;
    }

    /*
     * Update the owner of the program into the product view.
     *
     * @access public
     * @return bool
     */
    public function updateProductView()
    {
        $programs = $this->dao->select('id,PM')->from(TABLE_PROGRAM)->where('type')->eq('program')->andWhere('PM')->ne('')->fetchPairs('id', 'PM');
        if(empty($programs)) return true;

        $productGroup = $this->dao->select('id,program')->from(TABLE_PRODUCT)->where('program')->in(array_keys($programs))->andWhere('acl')->ne('open')->fetchGroup('program', 'id');
        if(empty($productGroup)) return true;

        $userView = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->in(array_values($programs))->fetchAll('account');
        foreach($programs as $programID => $programPM)
        {
            if(empty($productGroup[$programID])) continue;
            $canViewProducts = zget($productGroup, $programID);
            $view            = $userView[$programPM]->products;
            foreach($canViewProducts as $productID => $product)
            {
                if(strpos(",$view,", ",$productID,") === false) $view .= ',' . $productID;
            }
            $this->dao->update(TABLE_USERVIEW)->set('products')->eq($view)->where('account')->eq($programPM)->exec();
        }
        return true;
    }

    /**
     * Process feedback module
     *
     * @access public
     * @return void
     */
    public function processFeedbackModule()
    {
        $products  = $this->dao->select('id, name')->from(TABLE_PRODUCT)->fetchAll();
        $modules   = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('feedback')->andWhere('root')->eq(0)->fetchAll('id');
        $feedbacks = $this->dao->select('*')->from(TABLE_FEEDBACK)->fetchAll();

        $allProductRelation = array();
        foreach($products as $product)
        {
            $productID = $product->id;
            $relation  = array();
            foreach($modules as $moduleID => $module)
            {
                unset($module->id);
                $module->root = $productID;
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                $newModuleID = $this->dao->lastInsertID();
                $relation[$moduleID] = $newModuleID;
                $allProductRelation[$productID][$moduleID] = $newModuleID;
                $newPaths = array();
                foreach(explode(',', trim($module->path, ',')) as $path)
                {
                    if(isset($relation[$path])) $newPaths[] = $relation[$path];
                }
                $newPaths = join(',', $newPaths);
                $parent   = !empty($module->parent) ? $relation[$module->parent] : 0;
                $this->dao->update(TABLE_MODULE)->set('path')->eq($newPaths)->set('parent')->eq($parent)->where('id')->eq($newModuleID)->exec();
            }
        }

        /* Update feedback module */
        foreach($feedbacks as $feedback)
        {
            $moduleID = $feedback->module;
            $product  = $feedback->product;
            if(empty($moduleID)) continue;
            $newModuleID = isset($allProductRelation[$product][$moduleID]) ? $allProductRelation[$product][$moduleID] : 0;
            if(empty($newModuleID)) continue;

            $this->dao->update(TABLE_FEEDBACK)->set('module')->eq($newModuleID)->where('id')->eq($feedback->id)->exec();
        }

        /* Delete history module */
        $this->dao->delete()->from(TABLE_MODULE)->where('type')->eq('feedback')->andWhere('root')->eq(0)->exec();
    }

    /**
     * Add default modules for BI.
     *
     * @param  string $type
     * @param  int    $dimension
     * @access public
     * @return array
     */
    public function addDefaultModules4BI($type = 'report', $dimension = 1)
    {
        $this->app->loadLang('dimension');

        $group = new stdclass();
        $group->root  = $dimension;
        $group->grade = 1;
        $group->type  = $type;
        $group->owner = 'system';
        $group->order = 10;

        $modules = array();
        foreach($this->lang->dimension->moduleList as $module => $name)
        {
            if(!$module || !$name) continue;

            $exist = $this->dao->select('id')->from(TABLE_MODULE)
                 ->where('root')->eq($dimension)
                 ->andWhere('collector')->eq($module)
                 ->andWhere('type')->eq($type)
                 ->fetchAll();
            if(!empty($exist)) continue;

            $group->name      = $name;
            $group->collector = $module;
            $this->dao->replace(TABLE_MODULE)->data($group)->exec();

            $modules[$module] = $this->dao->lastInsertID();

            $group->order += 10;
        }
        $this->dao->update(TABLE_MODULE)->set("`path` = CONCAT(',', `id`, ',')")
            ->where('type')->eq($type)
            ->andWhere('grade')->eq('1')
            ->andWhere('path')->eq('')
            ->exec();

        return $modules;
    }

    /**
     * Add default modules for dataview.
     *
     * @param  string $type
     * @access public
     * @return void|bool
     */
    public function processDataset()
    {
        $dataviewData = $this->dao->select('id')->from(TABLE_DATAVIEW)->fetch();
        if(!empty($dataviewData)) return true;

        $this->loadModel('dataset');
        $this->loadModel('dataview');

        /* Create built-in module. */
        $builtInModuleID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('dataview')->andWhere('name')->eq($this->lang->dataview->builtIn)->fetch('id');
        if(empty($builtInModuleID))
        {
            $group = new stdclass();
            $group->root   = 0;
            $group->name   = $this->lang->dataview->builtIn;
            $group->parent = 0;
            $group->grade  = 1;
            $group->order  = 10;
            $group->type   = 'dataview';

            $this->dao->insert(TABLE_MODULE)->data($group)->exec();
            $builtInModuleID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_MODULE)->set("`path` = CONCAT(',', `id`, ',')")->where('id')->eq($builtInModuleID)->exec();
        }

        $dataview = new stdclass();
        $dataview->group       = $builtInModuleID;
        $dataview->createdBy   = 'system';
        $dataview->createdDate = helper::now();

        /* Process built-in dataset. */
        foreach($this->lang->dataset->tables as $code => $dataset)
        {
            $sameCodeList = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('code')->eq($code)->fetchAll();
            if(!empty($sameCodeList)) continue;

            $dataview->name = $dataset['name'];
            $dataview->code = $code;
            $dataview->view = 'ztv_' . $code;

            $table = $this->dataset->getTableInfo($code);
            $dataview->sql = $this->dataset->getTableData($table->schema, 'id_desc', 100, true);

            $fields = array();
            foreach($table->schema->fields as $key => $field)
            {
                $relatedField = '';
                if($field['type'] == 'object' and isset($field['show']))
                {
                    $key = str_replace('.', '_', $field['show']);
                    $relatedField  = substr($field['show'], strpos($field['show'], '.') + 1);
                    $relatedObject = isset($field['object']) ? $field['object'] : '';
                    if(!empty($relatedObject) and isset($table->schema->objects[$relatedObject]))
                    {
                        foreach($table->schema->objects[$relatedObject] as $fieldID => $fieldName)
                        {
                            if($fieldID == $relatedField) continue;

                            $objects  = $table->schema->objects[$relatedObject];
                            $addField = "{$relatedObject}_{$fieldID}";
                            $fields[$addField] = array();
                            $fields[$addField]['name']   = isset($objects[$fieldID]['name']) ? $objects[$fieldID]['name'] : $addField;
                            $fields[$addField]['field']  = $fieldID;
                            $fields[$addField]['object'] = $relatedObject;
                            $fields[$addField]['type']   = 'object';
                        }
                    }
                }

                if(!isset($fields[$key])) $fields[$key] = array();
                $fields[$key]['name']   = $field['name'];
                $fields[$key]['field']  = empty($relatedField) ? $key : $relatedField;
                $fields[$key]['object'] = isset($field['object']) ? $field['object'] : $code;
                $fields[$key]['type']   = $field['type'];

            }
            $dataview->fields = json_encode($fields);

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) and !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }

        $customDataset = $this->dao->select('*')->from(TABLE_DATASET)->fetchAll('id');
        if(empty($customDataset)) return true;

        /* Create custom module. */
        $defaultModuleID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('dataview')->andWhere('name')->eq($this->lang->dataview->default)->fetch('id');
        if(empty($defaultModuleID))
        {
            $group = new stdclass();
            $group->root   = 0;
            $group->name   = $this->lang->dataview->default;
            $group->parent = 0;
            $group->grade  = 1;
            $group->order  = 10;
            $group->type   = 'dataview';

            $this->dao->insert(TABLE_MODULE)->data($group)->exec();
            $defaultModuleID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_MODULE)->set("`path` = CONCAT(',', `id`, ',')")->where('id')->eq($defaultModuleID)->exec();
        }

        $dataview = new stdclass();
        $dataview->group = $defaultModuleID;

        /* Process custom dataset. */
        foreach($customDataset as $datasetID => $dataset)
        {
            $dataview->name        = $dataset->name;
            $dataview->code        = 'custom_' . $datasetID;
            $dataview->view        = 'ztv_custom_' . $datasetID;
            $dataview->sql         = $dataset->sql;
            $dataview->fields      = $dataset->fields;
            $dataview->createdBy   = $dataset->createdBy;
            $dataview->createdDate = $dataset->createdDate;
            $dataview->deleted     = $dataset->deleted;

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) and !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }

        return true;
    }

    /**
     * Process report modules.
     *
     * @access public
     * @return bool
     */
    public function createDefaultDimension()
    {
        /* Create default dimension. */
        $this->loadModel('dimension');
        foreach($this->config->dimension->defaultDimension as $dimensionName)
        {
            $dimension              = new stdclass();
            $dimension->name        = $this->lang->dimension->{$dimensionName};
            $dimension->code        = $dimensionName;
            $dimension->desc        = '';
            $dimension->createdBy   = 'system';
            $dimension->createdDate = helper::now();

            $this->dao->insert(TABLE_DIMENSION)->data($dimension)->exec();
            $dimensionID = $this->dao->lastInsertID();

            $chartModules = $this->addDefaultModules4BI('chart', $dimensionID);
            $this->addSecondModule4BI($dimensionID, $dimensionName, 'chart', $chartModules);

            $pivotModules = $this->addDefaultModules4BI('pivot', $dimensionID);
            $this->addSecondModule4BI($dimensionID, $dimensionName, 'pivot', $pivotModules);

        }
        return !dao::isError();
    }

    /**
     * Update zentao.sql or update18.3.sql file insert pivot data's group.
     *
     * @access public
     * @return bool
     */
    public function updatePivotGroup()
    {
        $pivotModules = $this->dao->select('collector, id')->from(TABLE_MODULE)->where('type')->eq('pivot')->andWhere('root')->eq(1)->andWhere('collector')->ne('')->fetchPairs();
        $pivots = $this->dao->select('*')->from(TABLE_PIVOT)->fetchAll('id');
        foreach($pivots as $pivotID => $pivot)
        {
            $modules = explode(',', $pivot->group);

            /* Upgrade group only pivot written by sql, group is string (possibly use , separated). */
            $continue = false;
            foreach($modules as $module)
            {
                if(is_numeric($module)) $continue = true;
            }
            if($continue) continue;

            $insertGroup = array();
            foreach($modules as $module)
            {
                if($module) $insertGroup[] = $pivotModules[$module];
            }

            $this->dao->update(TABLE_PIVOT)->set('`group`')->eq(implode(',', $insertGroup))->where('id')->eq($pivotID)->exec();
        }

        return !dao::isError();
    }

    /**
     * Add second modules for bi, move buildin chart and pivot to second modules.
     *
     * @access public
     * @return bool
     */
    public function addSecondModule4BI($dimensionID, $dimensionName, $module, $modules)
    {
        $this->loadModel('dimension');

        $listIndex     = $module . 'Upgrade';
        $secondModules = $this->config->dimension->secondModuleList[$dimensionName][$module];
        $updateInfos   = $this->config->dimension->secondModuleList[$dimensionName][$listIndex];

        foreach($modules as $moduleName => $parentID)
        {
            $insertModules = isset($secondModules[$moduleName]) ? explode(',', $secondModules[$moduleName]) : array();
            if(empty($insertModules)) continue;

            $i = 1;
            foreach($insertModules as $moduleCode)
            {
                $data = new stdclass();
                $data->root   = $dimensionID;
                $data->name   = $this->lang->dimension->modules[$moduleCode];
                $data->parent = $parentID;
                $data->grade  = 2;
                $data->order  = 10 * $i;
                $data->type   = $module;
                $i ++;

                $this->dao->insert(TABLE_MODULE)->data($data)->exec();
                $lastGroupID = $this->dao->lastInsertID();

                $path = ',' . $parentID . ',' . $lastGroupID . ',';
                $this->dao->update(TABLE_MODULE)->set("`path`")->eq($path)->where('id')->eq($lastGroupID)->exec();

                $updateArr = isset($updateInfos[$moduleName][$moduleCode]) ? $updateInfos[$moduleName][$moduleCode] : array();
                if(!empty($updateArr))
                {
                    foreach($updateArr as $type => $idString)
                    {
                        $table  = $type == 'chart' ? TABLE_CHART : TABLE_PIVOT;
                        $idList = explode(',', $idString);

                        $this->dao->update($table)->set('group')->eq($lastGroupID)->set('dimension')->eq($dimensionID)->where('id')->in($idList)->exec();
                    }
                }
            }
        }
    }

    /**
     * Add update2BI mark to zt_config.
     *
     * @access public
     * @return bool
     */
    public function addBIUpdateMark()
    {
        $this->loadModel('setting')->setItem("system.bi.update2BI", 1);
    }

    /**
     * Xuan: Set mute and freeze for hidden groups.
     *
     * @access public
     * @return bool
     */
    public function xuanSetMuteForHiddenGroups()
    {
        $this->dao->update(TABLE_IM_CHATUSER)->set('hide')->eq('0')->set('mute')->eq('1')->set('freeze')->eq('1')->where('hide')->eq('1')->andWhere('quit')->eq('0000-00-00 00:00:00')->exec();
        return !dao::isError();
    }

    /**
     * Xuan: Notify users who have hide the group.
     *
     * @access public
     * @return bool
     */
    public function xuanNotifyGroupHiddenUsers()
    {
        $noticeUsers = $this->dao->select('user')->from(TABLE_IM_CHATUSER)->where('hide')->eq('1')->andWhere('quit')->eq('0000-00-00 00:00:00')->groupBy('user')->fetchAll();

        $sender = new stdClass();
        $sender->avatar   = commonModel::getSysURL() . '/www/favicon.ico';
        $sender->id       = 'upgradeArchive';
        $sender->realname = $this->lang->upgrade->archiveChangeNoticeTitle;
        $this->loadModel('im')->messageCreateNotify(array_keys($noticeUsers), '', '', $this->lang->upgrade->archiveChangeNoticeContent, 'text', '', array(), $sender);
        return !dao::isError();
    }

    /**
     * Init shadow builds.
     *
     * @access public
     * @return bool
     */
    public function initShadowBuilds()
    {
        $releases = $this->dao->select('id,product,shadow,build,name,date,createdBy,createdDate,deleted')->from(TABLE_RELEASE)->where('shadow')->eq(0)->fetchAll();
        foreach($releases as $release)
        {
            $shadowBuild = new stdclass();
            $shadowBuild->product     = $release->product;
            $shadowBuild->builds      = $release->build;
            $shadowBuild->name        = $release->name;
            $shadowBuild->date        = $release->date;
            $shadowBuild->createdBy   = $release->createdBy;
            $shadowBuild->createdDate = $release->createdDate;
            $shadowBuild->deleted     = $release->deleted;
            $this->dao->insert(TABLE_BUILD)->data($shadowBuild)->exec();

            $shadowBuildID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_RELEASE)->set('shadow')->eq($shadowBuildID)->where('id')->eq($release->id)->exec();
        }
        return true;
    }

    /**
     * Init review efforts.
     *
     * @access public
     * @return void
     */
    public function initReviewEfforts()
    {
        $nodes = $this->dao->select('t1.id,t3.id as reviewID,t3.title,t2.id as approvalID,t1.extra as consumed')->from(TABLE_APPROVALNODE)->alias('t1')
            ->leftJoin(TABLE_APPROVAL)->alias('t2')->on("t1.approval=t2.id")
            ->leftJoin(TABLE_REVIEW)->alias('t3')->on("t2.objectID=t3.id")
            ->where('t3.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.objectType')->eq('review')
            ->andWhere('t1.extra')->ne('')
            ->andWhere('t1.extra')->ne(0)
            ->orderBy('t1.approval,t1.id')
            ->fetchAll('id');
        $this->loadModel('effort');
        foreach($nodes as $node)
        {
            $this->dao->delete()->from(TABLE_EFFORT)->where('objectType')->eq('review')->andWhere('objectID')->eq($node->reviewID)->exec();
            $this->effort->create('review', $node->reviewID, (int)$node->consumed, $node->title, $node->approvalID);
        }
        return true;
    }

    /**
     * Update my module blocks.
     *
     * @access public
     * @return bool
     */
    public function updateMyBlocks()
    {
        /* Delete flowchart block. */
        $this->dao->delete()->from(TABLE_BLOCK)->where('module')->eq('my')->andWhere('block')->eq('flowchart')->exec();

        /* Update block order and insert guide block. */
        $visionList = array('rnd', 'lite');

        /* Set guide block data. */
        $guideBlock = new stdclass();
        $guideBlock->module = 'my';
        $guideBlock->title  = common::checkNotCN() ? 'Guides' : '使用帮助';
        $guideBlock->block  = 'guide';
        $guideBlock->order  = 3;
        $guideBlock->grid   = 8;
        foreach($visionList as $vision)
        {
            $guideBlock->vision = $vision;
            $this->dao->update(TABLE_BLOCK)
                ->set('`order` = `order` + 1')
                ->where('vision')->eq($vision)
                ->andWhere('module')->eq('my')
                ->andWhere('`order`')->ge(3)
                ->orderBy('`order` desc')
                ->exec();

            $accountList = $this->dao->select('account')->from(TABLE_BLOCK)
                ->where('vision')->eq($vision)
                ->andWhere('module')->eq('my')
                ->fetchPairs('account');

            foreach($accountList as $account)
            {
                if(empty($account)) continue;

                $guideBlock->account = $account;
                $this->dao->insert(TABLE_BLOCK)->data($guideBlock)->exec();
            }
        }

        return true;
    }

    /**
     * Process dashboard to screen.
     *
     * @access public
     * @return void
     */
    public function processDashboard()
    {
        $dashboards = $this->dao->select('*')->from(TABLE_DASHBOARD)->fetchAll();
        foreach($dashboards as $dashboard)
        {
            $screen = new stdclass();
            $screen->name        = $dashboard->name;
            $screen->dimension   = 1;
            $screen->desc        = $dashboard->desc;
            $screen->scheme      = $this->processDashboardLayout($dashboard);
            $screen->deleted     = $dashboard->deleted;
            $screen->status      = 'published';
            $screen->createdBy   = $dashboard->createdBy;
            $screen->createdDate = $dashboard->createdDate;
            $this->dao->insert(TABLE_SCREEN)->data($screen)->exec();
        }

        $this->dao->exec("ALTER TABLE " . TABLE_CHART . " DROP `dataset`");
        $this->dao->exec("ALTER TABLE " . TABLE_PIVOT . " DROP `dataset`");
    }

    /**
     * Upgrade dashboard info to screen.
     *
     * @param  object $dashboard
     * @access public
     * @return void
     */
    public function processDashboardLayout($dashboard)
    {
        $this->loadModel('screen');

        $scheme = file_get_contents($this->app->getModuleRoot() . DS . 'screen' . DS . 'json' . DS . 'screen.json');
        $scheme = json_decode($scheme);

        $layout        = json_decode($dashboard->layout);
        $canvasHeight  = $scheme->editCanvasConfig->height;
        $componentList = array();
        foreach($layout as $option)
        {
            $component = new stdclass();
            $component = $this->loadModel('screen')->setComponentDefaults($component);
            $component->id   = $option->i->id;
            $component->attr = json_decode('{"offsetX": 0, "offsetY": 0, "lockScale": false, "zIndex": -1}');
            $component->attr->x = round($scheme->editCanvasConfig->width * ($option->x / 12));
            $component->attr->y = round(54 * $option->y);
            $component->attr->w = round($scheme->editCanvasConfig->width * ($option->w / 12));
            $component->attr->h = round(54 * $option->h);

            $type  = !empty($option->i->type) ? $option->i->type : 'chart';
            $chart = $this->loadModel($type)->getByID($option->i->id);

            if($chart)
            {
                if($chart->sql)
                {
                    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                    $stmt = $this->dbh->query($chart->sql);
                    if(!$stmt) continue;
                }

                $settings = $chart->settings;
                if($type == 'chart') $chartType = $chart->builtin ? $chart->type : $settings[0]['type'];
                if($type == 'pivot') $chartType = 'table';
                $chartConfig = json_decode(zget($this->config->screen->chartConfig, $chartType));
                $chartConfig->fields   = $chart->fieldSettings;
                $chartConfig->sourceID = $option->i->id;

                $component->title       = $chart->name;
                $component->type        = $chartType;
                $component->sourceID    = $option->i->id;
                $component->key         = $chartConfig->key;
                $component->chartConfig = $chartConfig;
                $component->option      = json_decode(zget($this->config->screen->chartOption, $chartType));
                if(isset($component->option->title->text)) $component->option->title->text = $chart->name;

                $chart->fields   = json_encode($chart->fieldSettings);
                $chart->settings = json_encode($chart->settings);
                $chart->langs    = json_encode($chart->langs);

                $chartFilters = !empty($chart->filters) ? json_decode($chart->filters, true) : array();
                $component    = $this->screen->getChartOption($chart, $component, $chartFilters);
            }

            $componentList[] = $component;

            if($canvasHeight < ($component->attr->y + $component->attr->h)) $canvasHeight = $component->attr->y + $component->attr->h;
        }

        $filters       = json_decode($dashboard->filters);
        $globalFilters = array();
        foreach($filters as $filter)
        {
            $globalFilter =  new stdclass();
            $globalFilter->name         = $filter->name;
            $globalFilter->type         = $filter->type;
            $globalFilter->field        = $filter->field;
            $globalFilter->defaultValue = zget($filter, 'defaultValue' , '');

            $diagram = array();
            foreach($layout as $option)
            {
                $type   = !empty($option->i->type) ? $option->i->type : 'chart';
                $chart  = $this->loadModel($type)->getByID($option->i->id);
                $fields = explode('.', $filter->field);
                if($chart->dataset == $fields[0]) $diagram[] = array('id' => "diagram-{$chart->id}", 'field' => $fields[1]);
            }
            $globalFilter->diagram = $diagram;

            $globalFilters[] = $globalFilter;
        }

        $scheme->editCanvasConfig->height       = $canvasHeight;
        $scheme->editCanvasConfig->globalFilter = $globalFilters;
        $scheme->componentList = $componentList;
        $scheme = json_encode($scheme);
        return $scheme;
    }

    /**
     * Insert mix stage.
     *
     * @access public
     * @return bool
     */
    public function insertMixStage()
    {
        $typeList = $this->dao->select('*')->from(TABLE_LANG)
            ->where('module')->eq('stage')
            ->andWhere('section')->eq('typeList')
            ->fetchAll();
        $this->dao->delete()->from(TABLE_LANG)
            ->where('module')->eq('stage')
            ->andWhere('section')->eq('typeList')
            ->exec();

        $mixInserted = array();
        foreach($typeList as $type)
        {
            if(!isset($mixInserted[$type->lang . '-' . $type->vision]))
            {
                $langFile = $this->app->getModuleRoot() . DS . 'stage' . DS . 'lang' . DS . ($type->lang == 'all' ? $this->app->clientLang : $type->lang) . '.php';
                if(!is_file($langFile)) continue;

                $lang = new stdclass();
                $lang->stage = new stdclass();
                include $langFile;

                $this->dao->replace(TABLE_LANG)
                    ->set('module')->eq('stage')
                    ->set('section')->eq('typeList')
                    ->set('lang')->eq($type->lang)
                    ->set('vision')->eq($type->vision)
                    ->set('key')->eq('mix')
                    ->set('value')->eq($lang->stage->typeList['mix'])
                    ->exec();

                $mixInserted[$type->lang . '-' . $type->vision] = true;
            }

            if($type->key == 'mix') continue;

            $this->dao->replace(TABLE_LANG)
                ->set('module')->eq('stage')
                ->set('section')->eq('typeList')
                ->set('lang')->eq($type->lang)
                ->set('vision')->eq($type->vision)
                ->set('key')->eq($type->key)
                ->set('value')->eq($type->value)
                ->exec();
        }

        return true;
    }

    /**
     * Change book to custom lib.
     *
     * @access public
     * @return void
     */
    public function changeBookToCustomLib()
    {
        $libs = $this->dao->select('id,id')->from(TABLE_DOCLIB)->where('`type`')->eq('book')->fetchPairs();
        foreach($libs as $libID)
        {
            $chapterModulePairs = array();
            $chapters           = $this->dao->select('*')->from(TABLE_DOC)->where('lib')->eq($libID)->andWhere('`type`')->eq('chapter')->orderBy('`grade` asc, `order` asc')->fetchGroup('grade', 'id');
            foreach($chapters as $grade => $gradeChapters)
            {
                foreach($gradeChapters as $id => $chapter)
                {
                    $module = new stdclass();
                    $module->root    = $chapter->lib;
                    $module->name    = $chapter->title;
                    $module->parent  = zget($chapterModulePairs, $chapter->parent);
                    $module->grade   = $chapter->grade;
                    $module->order   = $chapter->order;
                    $module->type    = 'doc';
                    $module->deleted = $chapter->deleted;

                    $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                    $moduleID = $this->dao->lastInsertID();

                    $chapterModulePairs[$id] = $moduleID;

                    $path = explode(',', $chapter->path);
                    $path = array_filter($path);
                    foreach($path as $index => $chapterID) $path[$index] = zget($chapterModulePairs, $chapterID);
                    $path = implode(',', $path);
                    $this->dao->update(TABLE_MODULE)->set('`path`')->eq(",{$path},")->where('id')->eq($moduleID)->exec();

                    $this->dao->update(TABLE_DOC)->set('`module`')->eq($moduleID)->set('`parent`')->eq($moduleID)->set("`path` = REPLACE(`path`, '{$chapter->path}', ',{$path},')")->set('`type`')->eq('text')->where('`parent`')->eq($id)->andWhere('`type`')->eq('article')->exec();
                }
            }
            $this->dao->update(TABLE_DOCLIB)->set('`type`')->eq('custom')->where('id')->eq($libID)->exec();
            $this->dao->update(TABLE_DOC)->set('`type`')->eq('text')->where('`lib`')->eq($libID)->andWhere('grade')->eq(1)->andWhere('`type`')->eq('article')->exec();
        }

        $this->dao->update(TABLE_DOC)->set('`type`')->eq('text')->where('`type`')->eq('book')->exec();

        $this->dbh->exec("UPDATE " . TABLE_DOC . " AS t1 LEFT JOIN " . TABLE_EXECUTION . " AS t2 ON t1.`execution` = t2.`id` SET t1.`project` = t2.`project` WHERE t1.`execution` != 0 AND t1.`project` = 0 AND t2.`project` != 0");

        return true;
    }

    /**
     * Create default group.
     *
     * @param  string    $type
     * @access public
     * @return void
     */
    public function createDefaultGroup($type)
    {
        $firstGroup = new stdclass();
        $firstGroup->root   = 1;
        $firstGroup->type   = $type;
        $firstGroup->parent = 0;
        $firstGroup->grade  = 1;
        $firstGroup->order  = 1000;
        $firstGroup->name   = $this->lang->upgrade->defaultGroup;

        $this->dao->insert(TABLE_MODULE)->data($firstGroup)->autoCheck()->exec();

        $firstGroupID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_MODULE)->set('path')->eq(",{$firstGroupID},")->where('id')->eq($firstGroupID)->exec();

        $secondGroup = new stdclass();
        $secondGroup->root   = 1;
        $secondGroup->type   = $type;
        $secondGroup->parent = $firstGroupID;
        $secondGroup->grade  = 2;
        $secondGroup->order  = 1000;
        $secondGroup->name   = $this->lang->upgrade->defaultGroup;

        $this->dao->insert(TABLE_MODULE)->data($secondGroup)->autoCheck()->exec();
        $secondGroupID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_MODULE)->set('path')->eq(",{$firstGroupID},{$secondGroupID},")->where('id')->eq($secondGroupID)->exec();

        return $secondGroupID;
    }

    /**
     * Process chart.
     *
     * @access public
     * @return bool
     */
    public function processChart()
    {
        $charts               = $this->dao->select('*')->from(TABLE_CHART)->where('builtin')->eq(0)->fetchAll('id');
        $dashboardLayoutPairs = $this->dao->select('id, layout')->from(TABLE_DASHBOARD)->fetchPairs();

        $dataviewList = $this->dao->select('t1.code,t1.view,t1.fields')->from(TABLE_DATAVIEW)->alias('t1')
            ->leftJoin(TABLE_CHART)->alias('t2')->on('t1.code = t2.dataset')
            ->where('t2.id')->in(array_keys($charts))
            ->fetchAll('code');

        $defaultChartGroupID = $this->createDefaultGroup('chart');

        foreach($charts as $chart)
        {
            if($chart->type == 'table')
            {
               $pivotID = $this->upgradeToPivotTable($chart, $dataviewList);
            }
            else
            {
                $data = new stdclass();
                $data->dimension = 1;
                $data->group     = $defaultChartGroupID;
                $data->stage     = 'published';
                $data->step      = 4;
                $data->type      = $chart->type == 'bar' ? 'cluBarX' : $chart->type;

                if(isset($dataviewList[$chart->dataset]))
                {
                    $dataview     = $dataviewList[$chart->dataset];
                    $data->sql    = 'SELECT * FROM ' . $dataview->view;
                    $data->fields = isset($dataview->fields) ? $dataview->fields : '';
                }
                elseif($chart->sql)
                {
                    $fieldSettings = $this->getFieldSettings($chart->sql);
                    $data->fields = json_encode($fieldSettings);
                }


                $settings = json_decode($chart->settings);

                if($settings && (!empty($settings->group) || !empty($settings->xaxis)))
                {
                    $settings->type = $data->type;

                    $filters = array();
                    if(isset($settings->filter))
                    {
                        $filters = $settings->filter;
                        unset($settings->filter);
                    }

                    $isQuoteDataview = isset($dataviewList[$chart->dataset]);

                    if(isset($settings->xaxis))
                    {
                        $xaxisFields = $settings->xaxis;
                        foreach($xaxisFields as $xaxisIndex => $xaxisField)
                        {
                            if(isset($xaxisField->dateGroup))                                 $xaxisField->group = $xaxisField->dateGroup;
                            if($isQuoteDataview && strpos($xaxisField->field, '.') !== false) $xaxisField->field = str_replace('.', '_', $xaxisField->field);

                            $xaxisFields[$xaxisIndex] = $xaxisField;
                        }
                        $settings->xaxis = $xaxisFields;
                    }

                    if(isset($settings->group))
                    {
                        $groupFields = $settings->group;
                        foreach($groupFields as $groupIndex => $groupField)
                        {
                            if(isset($groupField->dateGroup))                                 $groupField->group = $groupField->dateGroup;
                            if($isQuoteDataview && strpos($groupField->field, '.') !== false) $groupField->field = str_replace('.', '_', $groupField->field);

                            $groupFields[$groupIndex] = $groupField;
                        }

                        $settings->group = $groupFields;
                    }

                    if(isset($settings->yaxis))
                    {
                        $yaxisFields = $settings->yaxis;
                        foreach($yaxisFields as $yaxisIndex => $yaxisField)
                        {
                            if($isQuoteDataview && strpos($yaxisField->field, '.') !== false) $yaxisField->field = str_replace('.', '_', $yaxisField->field);

                            if($yaxisField->valOrAgg == 'value')          $yaxisField->valOrAgg = 'sum';
                            if($yaxisField->valOrAgg == 'count_distinct') $yaxisField->valOrAgg = 'distinct';
                            if($yaxisField->valOrAgg == 'value_all')      $yaxisField->valOrAgg = 'count';
                            if($yaxisField->valOrAgg == 'value_distinct') $yaxisField->valOrAgg = 'distinct';

                            $yaxisFields[$yaxisIndex] = $yaxisField;
                        }
                        $settings->yaxis = $yaxisFields;
                    }

                    if(isset($settings->metric))
                    {
                        $metricFields = $settings->metric;
                        foreach($metricFields as $metricIndex => $metricField)
                        {
                            if($isQuoteDataview && strpos($metricField->field, '.') !== false) $metricField->field = str_replace('.', '_', $metricField->field);

                            if($metricField->valOrAgg == 'value')          $metricField->valOrAgg = 'sum';
                            if($metricField->valOrAgg == 'count_distinct') $metricField->valOrAgg = 'distinct';
                            if($metricField->valOrAgg == 'value_all')      $metricField->valOrAgg = 'count';
                            if($metricField->valOrAgg == 'value_distinct') $metricField->valOrAgg = 'distinct';

                            $metricFields[$metricIndex] = $metricField;
                        }
                        $settings->metric = $metricFields;
                    }

                    $data->settings = json_encode(array($settings));
                }
                else
                {
                    $data->step  = 1;
                    $data->stage = 'draft';
                }

                if(!empty($filters))
                {
                    $where = array();
                    $operatorMap = array('in' => 'IN', 'notin' => 'NOT IN', 'notnull' => 'IS NOT NULL', 'null' => 'IS NULL');
                    foreach($filters as $filter)
                    {
                        $operator = zget($operatorMap, $filter->operator);
                        $value    = '';
                        if(!in_array($filter->operator, array('null', 'notnull')))
                        {
                            if(!is_array($filter->value))
                            {
                                $value = "'" . $filter->value . "'";
                            }
                            else
                            {
                                $values = array();
                                foreach($filter->value as $v) $values[] = $type == 'number' ? $v : "'" . $v . "'";
                                $value = '(' . implode(',', $values) . ')';
                            }
                        }

                        $where[] = $filter->field . ' ' . $operator . ' ' . $value;
                    }

                    if(stripos($data->sql, 'where') === false)
                    {
                        $data->sql .= ' WHERE ' . implode(' AND ', $where);
                    }
                    else
                    {
                        $data->sql .= ' ' . implode(' AND ', $where);
                    }
                }

                $this->dao->update(TABLE_CHART)->data($data)->autoCheck()->where('id')->eq($chart->id)->exec();
            }

            /* Update chart id in dashboard. */
            foreach($dashboardLayoutPairs as $dashboardID => $layout)
            {
                $layout = json_decode($layout);
                foreach($layout as $index => $chartLayout)
                {
                    if($chartLayout->i->id != $chart->id) continue;

                    $chartLayout->i->type = $chart->type == 'table' ? 'pivot' : 'chart';
                    if($chart->type == 'table') $chartLayout->i->id = $pivotID;

                    $layout[$index] = $chartLayout;
                }
                $dashboardLayoutPairs[$dashboardID] = json_encode($layout);

                $this->dao->update(TABLE_DASHBOARD)->set('layout')->eq(json_encode($layout))->where('id')->eq($dashboardID)->exec();
            }
        }

        if(dao::isError()) return false;

        return !dao::isError();
    }

    /**
     * upgrade to pivot table.
     *
     * @param  int    $table
     * @access public
     * @return void
     */
    public function upgradeToPivotTable($table, $dataviewList)
    {
        static $defaultPivotGroupID;

        if(!$defaultPivotGroupID) $defaultPivotGroupID = $this->createDefaultGroup('pivot');

        $pivot = new stdclass();
        $pivot->dimension   = 1;
        $pivot->group       = $defaultPivotGroupID;
        $pivot->stage       = 'published';
        $pivot->step        = 4;
        $pivot->sql         = $table->sql;
        $pivot->createdBy   = $table->createdBy;
        $pivot->createdDate = $table->createdDate;
        $pivot->dataset     = $table->dataset;

        $name = array();
        $name['zh-cn'] = $table->name;
        $pivot->name   = json_encode($name);

        $desc = array();
        $desc['zh-cn'] = $table->desc;
        $pivot->desc   = json_encode($desc);

        $pivotSettings = new stdclass();
        $tableSettings = json_decode($table->settings);
        $filters       = array();
        if($tableSettings && !empty($tableSettings->column))
        {
            $isQuoteDataview = isset($dataviewList[$table->dataset]);

            $index = 1;
            foreach($tableSettings->group as $group)
            {
                $groupKey = "group{$index}";
                $pivotSettings->$groupKey = ($isQuoteDataview && strpos($group->field, '.') !== false) ? str_replace('.', '_', $group->field) : $group->field;

                $index ++;
            }

            $columns = array();
            foreach($tableSettings->column as $tableColumn)
            {
                $column = new stdclass();
                $column->field = ($isQuoteDataview && strpos($tableColumn->field, '.') !== false) ? str_replace('.', '_', $tableColumn->field) : $tableColumn->field;
                $column->stat  = $tableColumn->valOrAgg;

                if($column->stat == 'value')
                {
                    $column->stat = 'sum';

                    /* 将按值统计的列升级到透视表的分组。*/
                    $groupKey = "group{$index}";
                    $pivotSettings->$groupKey = $column->field;
                }

                if($column->stat == 'value_all') $column->stat = 'count';
                if($column->stat == 'count_distinct' || $column->stat == 'value_distinct') $column->stat = 'distinct';

                $columns[] = $column;
            }
            $pivotSettings->columns = $columns;

            if(!empty($tableSettings->filter)) $filters = $tableSettings->filter;
        }
        else
        {
            $pivot->stage = 'draft';
            $pivot->step  = 1;
        }

        $pivot->settings = json_encode($pivotSettings);

        if(isset($dataviewList[$table->dataset]))
        {
            $dataview      = $dataviewList[$table->dataset];
            $pivot->sql    = 'SELECT * FROM ' . $dataview->view;
            $pivot->fields = isset($dataview->fields) ? $dataview->fields : '';
        }
        elseif($pivot->sql)
        {
            $fieldSettings = $this->getFieldSettings($table->sql);
            $pivot->fields = json_encode($fieldSettings);
        }

        if(!empty($filters))
        {
            $where = array();
            $operatorMap = array('in' => 'IN', 'notin' => 'NOT IN', 'notnull' => 'IS NOT NULL', 'null' => 'IS NULL');
            foreach($filters as $filter)
            {
                $operator = zget($operatorMap, $filter->operator);
                $value    = '';
                if(!in_array($filter->operator, array('null', 'notnull')))
                {
                    if(!is_array($filter->value))
                    {
                        $value = "'" . $filter->value . "'";
                    }
                    else
                    {
                        $values = array();
                        foreach($filter->value as $v) $values[] = $type == 'number' ? $v : "'" . $v . "'";
                        $value = '(' . implode(',', $values) . ')';
                    }
                }

                $where[] = $filter->field . ' ' . $operator . ' ' . $value;
            }

            if(stripos($pivot->sql, 'where') === false)
            {
                $pivot->sql .= ' WHERE ' . implode(' AND ', $where);
            }
            else
            {
                $pivot->sql .= ' ' . implode(' AND ', $where);
            }
        }

        /* Process fieldSettings. */

        $this->dao->insert(TABLE_PIVOT)->data($pivot)->autoCheck()->exec();
        $pivotID = $this->dao->lastInsertID();

        if(dao::isError()) return false;

        $this->dao->delete()->from(TABLE_CHART)->where('id')->eq($table->id)->exec();

        return $pivotID;
    }

    /**
     * Process report data.
     *
     * @access public
     * @return bool
     */
    public function processReport()
    {
        $reports = $this->dao->select('*')->from(TABLE_REPORT)->fetchAll();

        $groupCollectors = $this->dao->select('collector, id')->from(TABLE_MODULE)->where('type')->eq('pivot')->andWhere('root')->eq(1)->andWhere('collector')->ne('')->fetchPairs();

        $this->loadModel('pivot');
        $this->loadModel('dataview');

        foreach($reports as $report)
        {
            $data = new stdclass();
            $data->dimension   = 1;
            $data->name        = $report->name;
            $data->sql         = $this->pivot->replaceTableNames($report->sql);
            $data->vars        = $report->vars;
            $data->langs       = $report->langs;
            $data->stage       = 'published';
            $data->step        = 4;
            $data->desc        = $report->desc;
            $data->createdBy   = $report->addedBy;
            $data->createdDate = $report->addedDate;

            /* Process group. */
            $modules = explode(',', trim($report->module, ','));
            $groups  = array();
            foreach($modules as $module)
            {
                if(isset($groupCollectors[$module]))
                {
                    $groups[] = $groupCollectors[$module];
                }
                else
                {
                    if(!isset($defaultGroupID))
                    {
                        $defaultGroupID = $this->dao->select('id')->from(TABLE_MODULE)
                            ->where('type')->eq('pivot')
                            ->andWhere('name')->eq($this->lang->upgrade->defaultGroup)
                            ->andWhere('root')->eq(1)
                            ->andWhere('parent')->ne(0)
                            ->fetch('id');

                        if(!$defaultGroupID) $defaultGroupID = $this->createDefaultGroup('pivot');
                    }
                    $groups[] = $defaultGroupID;
                }
            }
            $data->group = implode(',', $groups);

            /* Process vars. */
            $vars = json_decode($report->vars);
            if($vars)
            {
                $filters = array();
                foreach($vars->varName as $index => $varName)
                {
                    $filter = new stdclass();
                    $filter->from       = 'query';
                    $filter->field      = $varName;
                    $filter->name       = $vars->showName[$index];
                    $filter->type       = $vars->requestType[$index];
                    $filter->typeOption = $filter->type == 'select' ? zget($vars->selectList, $index, '') : '';
                    $filter->default    = isset($vars->default) ? zget($vars->default, $index, '') : '';

                    $filters[] = $filter;
                }
                $data->filters = json_encode($filters);

            }

            /* Process settings. */
            $settings = new stdclass();
            $columns  = array();
            if($report->params)
            {
                $params = json_decode($report->params);

                $settings->group1      = $params->group1;
                $settings->group2      = $params->group2;
                $settings->columnTotal = 'sum';

                foreach($params->reportField as $index => $field)
                {
                    $column = new stdclass();
                    $column->field      = $field;
                    $column->slice      = $field;
                    $column->stat       = isset($params->reportType) ? zget($params->reportType, $index, 'noStat') : 'noStat';
                    $column->showTotal  = (isset($params->reportTotal) && zget($params->reportTotal, $index, '') == '1') ? 'sum' : 'noShow';
                    $column->showMode   = (isset($params->percent) && zget($params->percent, $index, '') == '1' && isset($params->contrast) && zget($params->contrast, $index, '') == 'crystalTotal') ? 'total' : 'default';
                    $column->monopolize = isset($params->showAlone) ? zget($params->showAlone, $index, '0') : '0';

                    if($column->stat == 'sum')
                    {
                        $sumAppend = isset($params->sumAppend) ? zget($params->sumAppend, $index, $field) : $field;

                        if($sumAppend == $field) $column->slice = 'noSlice';
                        if($sumAppend != $field) $column->field = $sumAppend;
                    }

                    $columns[] = $column;
                }
            }
            else
            {
                $column = new stdclass();
                $column->field     = '';
                $column->stat      = '';
                $column->slice     = 'noSlice';
                $column->showMode  = 'default';
                $column->showTotal = 'noShow';

                $columns[] = $column;

                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                $stmt = $this->dbh->query($data->sql);

                if(!$stmt)
                {
                    $data->stage = 'draft';
                    $data->step  = 1;
                }
                else
                {
                    $settings->summary = 'notuse';
                }
            }

            $settings->columns = $columns;

            /* Process fieldSettings. */
            $sql = $data->sql;
            if(!empty($filters))
            {
                foreach($filters as $filter)
                {
                    $filterDefault = isset($filter->default) ?  $filter->default : '';
                    if($filter->type == 'date') $filterDefault = $this->pivot->processDateVar($filterDefault);
                    $filterDefault = $filterDefault === NULL ? "NULL" : "'{$filterDefault}'";

                    $sql = str_replace('$' . $filter->field, $filterDefault, $sql);
                }
            }

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $stmt = $this->dbh->query($sql);
            if(!$stmt) /* Fix bug #35559. */
            {
                $data->stage = 'draft';
                $fieldSettings = array();
            }
            else
            {
                $fieldSettings = $this->getFieldSettings($sql);
            }

            $data->settings = json_encode($settings);
            $data->fields   = json_encode($fieldSettings);

            $this->dao->insert(TABLE_PIVOT)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Get field settings.
     *
     * @param  string    $sql
     * @access public
     * @return array
     */
    public function getFieldSettings($sql)
    {
        if(!$sql) return array();

        $this->loadModel('dataview');
        $this->loadModel('chart');

        $columns      = $this->dataview->getColumns($sql);
        $columnFields = array();
        foreach($columns as $column => $type) $columnFields[$column] = $column;

        $tableAndFields = $this->chart->getTables($sql);
        $tables         = $tableAndFields['tables'];
        $fields         = $tableAndFields['fields'];

        $moduleNames = array();
        if($tables) $moduleNames = $this->dataview->getModuleNames($tables);

        list($fieldPairs, $relatedObject) = $this->dataview->mergeFields($columnFields, $fields, $moduleNames);

        $fieldSettings = array();
        foreach($fieldPairs as $field => $name)
        {
            $relatedTable = zget($relatedObject, $field);

            $fieldSetting = new stdclass();
            $fieldSetting->object = $relatedTable;
            $fieldSetting->field  = $field;
            $fieldSetting->type   = 'string';
            $fieldSettings[$field] = $fieldSetting;
        }

        return $fieldSettings;
    }

    /**
     * Convert doc collect.
     *
     * @access public
     * @return bool
     */
    public function convertDocCollect()
    {
        $desc   = $this->dao->query('DESC ' . TABLE_DOC)->fetchAll();
        $fields = array();
        foreach($desc as $field)
        {
            $fieldName = $field->Field;
            $fields[$fieldName] = $fieldName;
        }

        if(!isset($fields['collector']) or isset($fields['collects'])) return true;

        $this->loadModel('doc');

        $users = $this->dao->select('account')->from(TABLE_USER)->fetchPairs('account', 'account');
        $docs  = $this->dao->select('id,collector')->from(TABLE_DOC)->where('collector')->ne('')->fetchAll();

        $this->dao->update(TABLE_DOC)->set('collector')->eq(0)->exec();
        $this->dao->exec("ALTER TABLE " . TABLE_DOC . " CHANGE `collector` `collects` smallint unsigned NOT NULL DEFAULT '0'");

        foreach($docs as $doc)
        {
            foreach(explode(',', $doc->collector) as $collector)
            {
                $collector = trim($collector);
                if(empty($collector)) continue;
                if(!isset($users[$collector])) continue;
                $this->doc->createAction($doc->id, 'collect', $collector);
            }
        }

        return true;
    }

    /**
     * Set UR switch status in feature switch.
     *
     * @access public
     * @return bool
     */
    public function setURSwitchStatus()
    {
        if(is_numeric($this->fromVersion[0]) and version_compare($this->fromVersion, '18.2', '>=')) return true;
        if(strpos($this->fromVersion, 'biz') !== false and version_compare($this->fromVersion, 'biz8.2', '>=')) return true;
        if(strpos($this->fromVersion, 'max') !== false and version_compare($this->fromVersion, 'max4.2', '>=')) return true;

        $URSwitchStatus = $this->loadModel('setting')->getItem("owner=system&module=custom&key=URAndSR");
        if(!$URSwitchStatus)
        {
            $closedFeatures = $this->setting->getItem('owner=system&module=common&key=closedFeatures');
            if(strpos($closedFeatures, 'productUR') === false) $closedFeatures .= ',productUR';
            $this->setting->setItem('system.common.closedFeatures', trim($closedFeatures, ','));
        }

        return true;
    }

    /**
     * Delete deploystep action where id not in zt_deploy.
     *
     * @access public
     * @return bool
     */
    public function processDeployStepAction()
    {
        $steps = $this->dao->select('*')->from(TABLE_DEPLOYSTEP)->fetchAll('id');
        if($steps) $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq('deploystep')->andWhere('objectID')->notIN(array_keys($steps))->exec();
    }

    /**
     * Update BI SQL for 18.4.stable new function: dataview model.php checkUniColumn().
     *
     * @access public
     * @return void
     */
    public function updateBISQL()
    {
        $alpha1File = $this->getUpgradeFile('18.4.alpha1');
        $beta1File  = $this->getUpgradeFile('18.4.beta1');

        $alpha1SQL = explode(";", file_get_contents($alpha1File));
        $beta1SQL  = explode(";", file_get_contents($beta1File));
        $execSQL   = array();

        foreach($alpha1SQL as $sql) if(strpos($sql, '`zt_pivot`') !== false) $execSQL[] = $sql;
        foreach($beta1SQL  as $sql) if(strpos($sql, '`zt_pivot`') !== false) $execSQL[] = $sql;

        /* Update stage to published and update sql. */
        foreach($execSQL as $sql)
        {
            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            $sql = trim($sql);

            $this->dbh->exec($sql);
        }
    }

    /**
     * Check pivot SQL.
     *
     * @access public
     * @return void
     */
    public function checkPivotSQL()
    {
        $this->loadModel('pivot');
        $this->loadModel('chart');
        $this->loadModel('dataview');
        $pivots    = $this->dao->select('*')->from(TABLE_PIVOT)->where('deleted')->eq(0)->fetchAll('id');
        $pivotList = array_keys($pivots);

        foreach($pivotList as $pivotID)
        {
            $pivot   = $this->pivot->getByID($pivotID);
            $sql     = $this->chart->parseSqlVars($pivot->sql, $pivot->filters);
            $stage   = $pivot->stage;
            if($stage == 'draft') continue;

            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $stmt = $this->dbh->query($sql);
            if(!$stmt) /* Fix bug #35559. */
            {
                $stage = 'draft';
            }
            elseif(!$this->dataview->checkUniColumn($sql))
            {
                $stage = 'draft';
            }

            $this->dao->update(TABLE_PIVOT)->set('stage')->eq($stage)->where('id')->eq($pivotID)->exec();
        }
    }

    /**
     * Fix missed rnd workflow field.
     *
     * @access public
     * @return void
     */
    public function fixMissedFlowField()
    {
        if($this->config->edition == 'open' || !in_array($this->fromVersion, $this->config->upgrade->missedFlowFieldVersions)) return;

        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowlayout');

        $modules         = $this->config->workflow->buildin->modules;
        $actions         = $this->config->workflowaction->buildin->actions;
        $actionTypes     = $this->config->workflowaction->buildin->types;
        $actionMethods   = $this->config->workflowaction->buildin->methods;
        $actionOpens     = $this->config->workflowaction->buildin->opens;
        $actionLayouts   = $this->config->workflowaction->buildin->layouts;
        $actionPositions = $this->config->workflowaction->buildin->positions;
        $actionShows     = $this->config->workflowaction->buildin->shows;
        $layouts         = $this->config->workflowlayout->buildin->layouts;

        $account = isset($this->app->user) ? $this->app->user->account : 'admin';
        $now     = helper::now();

        /* Insert buildin modules to TABLE_WORKFLOW. */
        $data = new stdclass();
        $data->vision      = 'rnd';
        $data->buildin     = 1;
        $data->createdBy   = $account;
        $data->createdDate = $now;
        $data->status      = 'normal';
        foreach($modules as $app => $appModules)
        {
            $data->app = $app;
            foreach($appModules as $module => $options)
            {
                $this->app->loadLang($module);

                $data->module    = $module;
                $data->name      = isset($this->lang->$module->common) ? $this->lang->$module->common : $module;
                $data->table     = str_replace('`', '', zget($options, 'table', ''));
                $data->navigator = zget($options, 'navigator', 'secondary');
                if($module == 'story')     $data->name = $this->lang->searchObjects['story'];
                if($module == 'execution') $data->name = $this->lang->workflow->execution;

                $modelField = $this->dao->select('id')->from(TABLE_WORKFLOW)->where('app')->eq($app)->andWhere('module')->eq($module)->andWhere('vision')->eq('rnd')->fetch('id');
                if(!$modelField) $this->dao->insert(TABLE_WORKFLOW)->data($data)->exec();
            }
        }

        /* Insert actions of buildin modules to TABLE_WORKFLOWACTION. */
        $data = new stdclass();
        $data->buildin       = 1;
        $data->role          = 'buildin';
        $data->extensionType = 'none';
        $data->createdBy     = $account;
        $data->createdDate   = $now;
        foreach($actions as $module => $moduleActions)
        {
            $data->module = $module;
            foreach($moduleActions as $action)
            {
                $data->action = $action;

                /* Use default action name if not set flow action name. */
                $name = '';
                if(isset($this->lang->$module->$action)) $name = $this->lang->$module->$action;
                if(empty($name) && in_array($action, array_keys($this->config->flowAction)))
                {
                    $methodName = $this->config->flowAction[$action];
                    if(isset($this->lang->$module->$methodName)) $name = $this->lang->$module->$methodName;
                }
                if(empty($name) && isset($this->lang->workflowaction->default->actions[$action])) $name = $this->lang->workflowaction->default->actions[$action];
                if(empty($name)) $name = $action;
                $data->name   = $name;

                $data->method   = $data->action;
                $data->open     = 'normal';
                $data->layout   = 'normal';
                $data->type     = 'single';
                $data->position = 'browseandview';
                $data->show     = 'direct';
                if(isset($actionMethods[$module][$action]))   $data->method   = $actionMethods[$module][$action];
                if(isset($actionOpens[$module][$action]))     $data->open     = $actionOpens[$module][$action];
                if(isset($actionLayouts[$module][$action]))   $data->layout   = $actionLayouts[$module][$action];
                if(isset($actionTypes[$module][$action]))     $data->type     = $actionTypes[$module][$action];
                if(isset($actionPositions[$module][$action])) $data->position = $actionPositions[$module][$action];
                if(isset($actionShows[$module][$action]))     $data->show     = $actionShows[$module][$action];

                $actionField = $this->dao->select('id')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($action)->andWhere('vision')->eq('rnd')->fetch('id');
                if(!$actionField) $this->dao->insert(TABLE_WORKFLOWACTION)->data($data)->exec();
            }
        }

        /* Insert layouts of buildin modules to TABLE_WORKFLOWLAYOUT. */
        $data = new stdclass();
        foreach($layouts as $module => $moduleLayouts)
        {
            $data->module = $module;
            foreach($moduleLayouts as $action => $layoutFields)
            {
                $order = 1;
                $data->action = $action;
                foreach($layoutFields as $field => $options)
                {
                    $data->field      = $field;
                    $data->width      = zget($options, 'width', 0);
                    $data->mobileShow = zget($options, 'mobileShow', 0);
                    $data->order      = $order++;

                    if($data->width == 'auto') $data->width = 0;

                    $layoutField = $this->dao->select('id')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($module)->andWhere('action')->eq($action)->andWhere('field')->eq($field)->andWhere('vision')->eq('rnd')->fetch('id');
                    if(!$layoutField) $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($data)->exec();
                }
            }
        }

    }

    /**
     * Update pivot fields type, pivotID maybe is 1008,1012,1013,1015,1020,1027.
     *
     * @access public
     * @return void
     */
    public function updatePivotFieldsType()
    {
        $fieldsPairs   = array();
        $fieldsPairs[] = array('maybeID' => 1008, 'beforefields' => '"execution":{"object":"project","field":"execution","type":"string"},"type":{"object":"project","field":"type","type":"string"}',
                               'fields' => '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"type":{"object":"task","field":"type","type":"option"},"taskID":{"object":"task","field":"taskID","type":"string"},"projectstatus":{"object":"task","field":"projectstatus","type":"string"}}',
                               'langs'  => '{"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"\\u4efb\\u52a1\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""},"taskID":{"zh-cn":"taskID","zh-tw":"","en":"","de":"","fr":""},"projectstatus":{"zh-cn":"projectstatus","zh-tw":"","en":"","de":"","fr":""}}');
        $fieldsPairs[] = array('maybeID' => 1012, 'beforefields' => '"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"status":{"object":"project","field":"status","type":"string"}',
                               'fields' => '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"status":{"object":"story","field":"status","type":"option"}}',
                               );
        $fieldsPairs[] = array('maybeID' => 1013, 'beforefields' => '"execution":{"object":"project","field":"execution","type":"string"},"stage":{"object":"project","field":"stage","type":"string"}}',
                               'fields' => '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"projectstory","field":"project","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"stage":{"object":"story","field":"stage","type":"option"}}',
                               'langs' => '{"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"stage":{"zh-cn":"\\u9636\\u6bb5","zh-tw":"","en":"","de":"","fr":""}}');
        $fieldsPairs[] = array('maybeID' => 1015, 'beforefields' => '"bugID":{"object":"bug","field":"bugID","type":"string"},"status":{"object":"project","field":"status","type":"string"}',
                               'fields' => '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"t3id":{"object":"project","field":"t3id","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"bugID":{"object":"bug","field":"bugID","type":"string"},"status":{"object":"bug","field":"status","type":"option"}}',
                               );
        $fieldsPairs[] = array('maybeID' => 1020, 'beforefields' => '"bugID":{"object":"project","field":"bugID","type":"string"},"type":{"object":"product","field":"type","type":"string"}',
                               'fields' => '{"id":{"object":"product","field":"id","type":"string"},"name":{"object":"product","field":"name","type":"string"},"bugID":{"object":"project","field":"bugID","type":"string"},"type":{"object":"bug","field":"type","type":"option"}}',
                               'langs' => '{"count":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"\\u9700\\u6c42\\u6570","en":"Stories"},"done":{"zh-cn":"\\u5b8c\\u6210\\u6570","zh-tw":"\\u5b8c\\u6210\\u6570","en":"Done"},"id":{"zh-cn":"\\u7f16\\u53f7","zh-tw":"","en":"","de":"","fr":""},"name":{"zh-cn":"\\u4ea7\\u54c1\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"bugID":{"zh-cn":"bugID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"Bug\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""}}');
        $fieldsPairs[] = array('maybeID' => 1027, 'beforefields' => '"bugID":{"object":"bug","field":"bugID","type":"string"},"type":{"object":"project","field":"type","type":"string"}',
                               'fields' => '{"id":{"object":"project","field":"id","type":"string"},"project":{"object":"project","field":"project","type":"string"},"t3id":{"object":"project","field":"t3id","type":"string"},"execution":{"object":"project","field":"execution","type":"string"},"bugID":{"object":"bug","field":"bugID","type":"string"},"type":{"object":"bug","field":"type","type":"option"}}',
                               'langs' => '{"stories":{"zh-cn":"\\u9700\\u6c42\\u6570","zh-tw":"\\u9700\\u6c42\\u6570","en":"Stories"},"tasks":{"zh-cn":"\\u4efb\\u52a1\\u6570","zh-tw":"\\u4efb\\u52a1\\u6570","en":"Tasks"},"undoneStory":{"zh-cn":"\\u5269\\u4f59\\u9700\\u6c42\\u6570","zh-tw":"\\u5269\\u4f59\\u9700\\u6c42\\u6570","en":"Undone Story"},"undoneTask":{"zh-cn":"\\u5269\\u4f59\\u4efb\\u52a1\\u6570","zh-tw":"\\u5269\\u4f59\\u4efb\\u52a1\\u6570","en":"Undone Task"},"consumed":{"zh-cn":"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6","zh-tw":"\\u5df2\\u6d88\\u8017\\u5de5\\u65f6","en":"Cost(h)"},"left":{"zh-cn":"\\u5269\\u4f59\\u5de5\\u65f6","zh-tw":"\\u5269\\u4f59\\u5de5\\u65f6","en":"Left(h)"},"consumedPercent":{"zh-cn":"\\u8fdb\\u5ea6","zh-tw":"\\u8fdb\\u5ea6","en":"Process"},"execution":{"zh-cn":"\\u6267\\u884c\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"id":{"zh-cn":"\\u9879\\u76eeID","zh-tw":"","en":"","de":"","fr":""},"project":{"zh-cn":"\\u9879\\u76ee\\u540d\\u79f0","zh-tw":"","en":"","de":"","fr":""},"bugID":{"zh-cn":"bugID","zh-tw":"","en":"","de":"","fr":""},"type":{"zh-cn":"Bug\\u7c7b\\u578b","zh-tw":"","en":"","de":"","fr":""}}');

        foreach($fieldsPairs as $field)
        {
            $pivot = $this->dao->select('*')->from(TABLE_PIVOT)->where('fields')->like("%{$field['beforefields']}%")->fetchAll();
            if(count($pivot) > 1 or count($pivot) == 0) continue;

            $pivot = reset($pivot);

            $data = new stdclass();
            if(isset($field['fields'])) $data->fields = $field['fields'];
            if(isset($field['langs']))  $data->langs = $field['langs'];

            $this->dao->update(TABLE_PIVOT)->data($data)->where('id')->eq($pivot->id)->exec();
        }
    }

    /**
     * Init recent action for new table.
     *
     * @access public
     * @return void
     */
    public function addCreateAction4Story()
    {
        if(!in_array($this->fromVersion, array('18_5', 'biz8_5', 'max4_5'))) return;

        $stories = $this->dao->select('id,product,openedBy,openedDate,vision')->from(TABLE_STORY)->where('openedDate')->ge('2023-07-12')->fetchAll('id');
        foreach($stories as $story)
        {
            $firstAction = $this->dao->select('*')->from(TABLE_ACTION)
                ->where('objectType')->eq('story')
                ->andWhere('objectID')->eq($story->id)
                ->orderBy('date,id')
                ->fetch();

            if(empty($firstAction) or $firstAction->action != 'opened')
            {
                $actionDate = $story->openedDate;
                if($firstAction->date <= $actionDate) $actionDate = date('Y-m-d H:i:s', strtotime($firstAction->date) - 1);

                $action = new stdclass();
                $action->objectType = 'story';
                $action->objectID   = $story->id;
                $action->product    = ',' . $story->product . ',';
                $action->project    = 0;
                $action->execution  = 0;
                $action->actor      = $story->openedBy;
                $action->action     = 'opened';
                $action->date       = $actionDate;
                $action->comment    = '';
                $action->extra      = '';
                $action->read       = 0;
                $action->vision     = $story->vision;
                $action->efforted   = 0;

                $this->dao->insert(TABLE_ACTION)->data($action)->exec();
            }
        }
    }

    /**
     * Remove product line required fields when 12.x upgrade to 18.x.
     *
     * @access public
     * @return bool
     */
    public function removeProductLineRequired()
    {
        $this->loadModel('setting');

        $createRequired = $this->setting->getItem('owner=system&module=product&section=create&key=requiredFields');
        $editRequired   = $this->setting->getItem('owner=system&module=product&section=edit&key=requiredFields');

        $createRequired = str_replace(',line,', ',', ",$createRequired,");
        $editRequired   = str_replace(',line,', ',', ",$editRequired,");

        $this->setting->setItem('system.product.create.requiredFields', trim($createRequired, ','));
        $this->setting->setItem('system.product.edit.requiredFields', trim($editRequired, ','));

        return true;
    }

    /**
     * Convert old metrics to new metrics.
     *
     * @access public
     * @return bool
     */
    public function processOldMetrics()
    {
        $this->loadModel('metric');
        $scopeMap   = $this->config->metric->oldScopeMap;
        $purposeMap = $this->config->metric->oldPurposeMap;
        $objectMap  = $this->config->metric->oldObjectMap;

        $oldMetrics = $this->dao->select('*')->from(TABLE_BASICMEAS)->where('deleted')->eq('0')->orderBy('order_asc')->fetchAll();

        foreach($oldMetrics as $oldMetric)
        {
            $metric = new stdclass();
            $metric->scope       = $scopeMap[$oldMetric->scope] ? $scopeMap[$oldMetric->scope] : 'other';
            $metric->purpose     = $purposeMap[$oldMetric->purpose] ? $purposeMap[$oldMetric->purpose] : 'other';
            $metric->object      = $objectMap[$oldMetric->object] ? $objectMap[$oldMetric->object] : 'other';
            $metric->stage       = 'wait';
            $metric->type        = 'sql';
            $metric->name        = $oldMetric->name;
            $metric->code        = $oldMetric->code;
            $metric->desc        = '';
            $metric->definition  = $oldMetric->definition;
            $metric->createdBy   = $oldMetric->createdBy;
            $metric->createdDate = helper::isZeroDate($oldMetric->createdDate) ? null : $oldMetric->createdDate;
            $metric->editedBy    = $oldMetric->editedBy;
            $metric->editedDate  = helper::isZeroDate($oldMetric->editedDate) ? null : $oldMetric->editedDate;
            $metric->builtin     = '1';
            $metric->fromID      = $oldMetric->id;
            $metric->order       = 0;
            $metric->deleted     = $oldMetric->deleted;

            $this->dao->insert(TABLE_METRIC)->data($metric)->exec();

            $metricID = $this->dao->lastInsertID();

            $this->loadModel('action')->create('metric', $metricID, 'created', '', '', 'system');
        }

        return !dao::isError();
    }

    /**
     * Alias for processHistoryOfStory.
     *
     * @access public
     * @return void
     */
    public function processHistoryDataForMetric()
    {
        $this->processHistoryOfStory();
    }

    /**
     * Process history of story.
     *
     * @access public
     * @return void
     */
    public function processHistoryOfStory()
    {
        $linked2releaseActions = $this->dao->select('objectID, extra, max(`date`) as date, action')
            ->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('action')->eq('linked2release')
            ->groupBy('objectID')
            ->get();

        $releasedStorys = $this->dao->select('t2.id, t1.date')
            ->from("($linked2releaseActions)")->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.objectID = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->leftJoin(TABLE_RELEASE)->alias('t4')->on('t1.extra = t4.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t2.stage', true)->eq('released')
            ->orWhere('t2.closedReason')->eq('done')
            ->markRight(1)
            ->fetchAll();

        foreach($releasedStorys as $releasedStory)
        {
            $story = $releasedStory->id;
            $date  = $releasedStory->date;

            $this->dao->update(TABLE_STORY)->set('releasedDate')->eq($date)->where('id')->eq($story)->exec();
        }
    }

    /**
     * Upgrade testtask members.
     *
     * @access public
     * @return void
     */
    public function upgradeTesttaskMembers()
    {
        $memberGroup = $this->dao->select('task,lastRunner')->from(TABLE_TESTRUN)
            ->where('lastRunner')->ne('')
            ->fetchGroup('task', 'lastRunner');

        foreach($memberGroup as $taskID => $members)
        {
            $members = implode(',', array_keys($members));
            $this->dao->update(TABLE_TESTTASK)->set('members')->eq($members)->where('id')->eq($taskID)->exec();
        }
    }

    /**
     * Delete waterfall general report block.
     *
     * @access public
     * @return true
     */
    public function deleteGeneralReportBlock()
    {
        $this->loadModel('setting');
        $closedBlocks = $this->setting->getItem('owner=system&module=block&key=closed');

        if(strpos($closedBlocks, 'waterfallgeneralreport') !== false)
        {
            $closedBlocks = str_replace(',project|waterfallgeneralreport', '', $closedBlocks);
            $this->setting->setItem('system.block.closed', $closedBlocks);
        }

        $this->dao->delete()->from(TABLE_BLOCK)->where('block')->eq('waterfallgeneralreport')->exec();

        return true;
    }

    /**
     * Sync xuanxuan https config.
     *
     * @access public
     * @return void
     */
    public function syncXXHttpsConfig()
    {
        $this->dao->update(TABLE_CONFIG)->set('value')->eq('off')->where('`key`')->eq('isHttps')->andWhere('`section`')->eq('xuanxuan')->andWhere('`value`')->eq('0')->exec();
        $this->dao->update(TABLE_CONFIG)->set('value')->eq('on')->where('`key`')->eq('isHttps')->andWhere('`section`')->eq('xuanxuan')->andWhere('`value`')->eq('1')->exec();
        return true;
    }

    /**
     * Create demo API.
     *
     * @access public
     * @return void
     */
    public function createDemoAPI()
    {
        $this->loadModel('api')->createDemoData($this->lang->api->zentaoAPI, commonModel::getSysURL() . $this->app->config->webRoot . 'api.php/v1', '16.0');
        return true;
    }

    /**
     * Complete the database for different edition.
     *
     * @access public
     * @return void
     */
    public function completionAllSQL()
    {
        switch($this->fromEdition)
        {
            case 'open':
                $this->execSQL($this->getUpgradeFile('proinstall'));
            case 'pro':
                $this->execSQL($this->getUpgradeFile('bizinstall'));
            case 'biz':
                $this->execSQL($this->getUpgradeFile('maxinstall'));
                $this->execSQL($this->getUpgradeFile('functionss'));
        }
    }

    /**
     * Add adminInvite field for xuanxuan.
     *
     * @access public
     * @return void
     */
    public function addAdminInviteField()
    {
        $table  = $this->config->db->prefix . 'im_chat';
        $exists = $this->checkFieldsExists($table, 'adminInvite');
        if(!$exists) $this->dbh->exec("ALTER TABLE $table ADD `adminInvite` enum('0','1') NOT NULL DEFAULT '0' AFTER `mergedChats`");
    }

    /**
     * Insetall IPD.
     *
     * @access public
     * @return void
     */
    public function installIPD()
    {
        $fromVersion = $this->fromVersion;
        $ipdinstall  = false;

        if(is_numeric($fromVersion[0]) and version_compare($fromVersion, '18.5', '<='))             $ipdinstall = true;
        if(strpos($fromVersion, 'pro') !== false)                                                   $ipdinstall = true;
        if(strpos($fromVersion, 'biz') !== false and version_compare($fromVersion, 'biz8.5', '<=')) $ipdinstall = true;
        if(strpos($fromVersion, 'max') !== false and version_compare($fromVersion, 'max4.5', '<=')) $ipdinstall = true;
        if($ipdinstall) $this->execSQL($this->getUpgradeFile('ipdinstall'));

        if($fromVersion == 'ipd1.0.beta1') $this->execSQL($this->getUpgradeFile('ipd1.0.beta1'));
    }

    /**
     * Stop old cron.
     *
     * @access public
     * @return void
     */
    public function stopOldCron()
    {
        touch($this->app->getCacheRoot() . 'restartcron');
    }

    /**
     * Add default traincourse priv to each group.
     *
     * @access public
     * @return bool
     */
    public function addDefaultTraincoursePriv()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);

        $groups      = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->ne('limited')->andWhere('vision')->eq('rnd')->fetchPairs();
        $sqlTemplate = 'REPLACE INTO ' . TABLE_GROUPPRIV . " (`group`, `module`, `method`) VALUES ('%s', 'traincourse', 'browse'), ('%s', 'traincourse', 'viewCourse'), ('%s', 'traincourse', 'viewChapter'), ('%s', 'traincourse', 'practice'), ('%s', 'traincourse', 'practiceBrowse'), ('%s', 'traincourse', 'practiceView'), ('%s', 'traincourse', 'updatePractice');";

        foreach($groups as $groupID)
        {
            $sql = str_replace('%s', $groupID, $sqlTemplate);
            $this->dbh->exec($sql);
        }

        return true;
    }

    /**
     * Check has consistency error or not.
     *
     * @access public
     * @return bool
     */
    public function hasConsistencyError()
    {
        $logFile = $this->getConsistencyLogFile();
        if(!file_exists($logFile)) return false;

        $lastTwoLines = array(0 =>'', 1 => '');

        $fh = fopen($logFile, 'r');
        while(!feof($fh))
        {
            $lastTwoLines[0] = $lastTwoLines[1];
            $lastTwoLines[1] = fgets($fh);
        }
        fclose($fh);

        if(trim($lastTwoLines[0]) == 'HasError') return true;
        return false;
    }

    /**
     * Rename BI default module name.
     *
     * @access public
     * @return void
     */
    public function renameBIModule()
    {
        $moduleNames  = array('zh' => '默认分组', 'en' => 'Default');
        $replaceNames = array('zh' => '未分组', 'en' => 'Ungrouped');
        $BIModules    = array('pivot', 'chart');

        foreach($BIModules as $BImodule)
        {
            foreach($moduleNames as $lang => $moduleName)
            {
                $modules = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('name')->eq($moduleName)
                    ->andWhere('type')->eq($BImodule)
                    ->fetchAll();

                if(count($modules) == 2)
                {
                    $data = new stdclass();
                    $data->name  = $replaceNames[$lang];
                    $data->order = 1000;

                    foreach($modules as $module)
                    {
                        $this->dao->update(TABLE_MODULE)->data($data)->where('id')->eq($module->id)->exec();
                    }

                    break;
                }
            }
        }
    }

    /**
     * Migrate xuanxuan client settings, make owners user accounts.
     *
     * @access public
     * @return void
     */
    public function migrateXuanClientSettings()
    {
        $existingSettings = $this->dao->select('`key`, `value`')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('chat')
            ->andWhere('section')->eq('settings')
            ->fetchAll();
        if(empty($existingSettings)) return;

        foreach($existingSettings as $setting)
        {
            $migratedSetting = array('owner' => $setting->key, 'module' => 'chat', 'section' => 'clientSettings', 'key' => 'settings', 'value' => $setting->value);
            $this->dao->insert(TABLE_CONFIG)->data($migratedSetting)->exec();
        }

        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('module')->eq('chat')
            ->andWhere('section')->eq('settings')
            ->exec();
    }
}
