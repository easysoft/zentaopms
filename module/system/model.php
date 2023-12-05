 <?php
/**
 * The model file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class systemModel extends model
{
    /**
     * Construct function: load setting model.
     *
     * @access public
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
    }

    /**
     * Get LDAP menu switcher.
     *
     * @access public
     * @return string
     */
    public function getLDAPSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->ldapManagement}</span>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Get Oss menu switcher.
     *
     * @access public
     * @return string
     */
    public function getOssSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->oss->common}</span>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Get SMTP menu switcher.
     *
     * @access public
     * @return string
     */
    public function getSMTPSwitcher()
    {
        $output  = "<div class='btn-group header-btn'>";
        $output .= "<span class='btn'>{$this->lang->system->SMTP->common}</span>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Print action buttons.
     *
     * @param  object $db
     * @access public
     * @return void
     */
    public function printDBAction($db)
    {
        $disabled = strtolower($db->status) == 'running' ? '' : 'disabled';
        $btnHtml  = html::commonButton($this->lang->system->management, "{$disabled} data-db-name='{$db->name}' data-db-type='{$db->db_type}' data-namespace='{$db->namespace}'", 'db-login btn btn-primary');

        echo $btnHtml;
    }

    /**
     * Print edit LDAP button.
     *
     * @access public
     * @return void
     */
    public function printEditLDAPBtn()
    {
        $this->loadModel('instance');

        $title       = $this->lang->system->editLDAP;
        $toolTips    = '';
        $count       = $this->instance->countLDAP();
        if($count)
        {
            $title       = sprintf($this->lang->system->notices->ldapUsed, $count);
            $toolTips    = "data-toggle='tooltip' data-placement='bottom'";
        }

        $buttonHtml = '';
        $buttonHtml .= "<span class='edit-tools-tips' {$toolTips} title='{$title}'>";
        $buttonHtml .= html::a(inLink('editLDAP'), $this->lang->system->editLDAP, '', "title='{$title}' class='btn-edit btn label label-outline label-primary label-lg'");
        $buttonHtml .= "</span>";

        echo $buttonHtml;
    }

    /**
     * Print LDAP buttons.
     *
     * @param  objevt $ldapInstance
     * @access public
     * @return mixed
     */
    public function printLDAPButtons($ldapInstance)
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $buttonHtml = '';

        if($ldapInstance->domain)
        {
            $disableVisit = !$this->instance->canDo('visit', $ldapInstance);
            $buttonHtml  .= html::commonButton($this->lang->instance->visit, "instance-id='{$ldapInstance->id}' title='{$this->lang->instance->visit}'" . ($disableVisit ? ' disabled ' : ''), 'btn-visit btn label label-outline label-primary label-lg');
        }

        $disableStart = !$this->instance->canDo('start', $ldapInstance);
        $buttonHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$ldapInstance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $title    = $this->lang->instance->stop;
        $toolTips = '';
        $count    = $this->instance->countLDAP();
        if($count)
        {
            $title    = $this->lang->system->notices->ldapUsed;
            $toolTips = "data-toggle='tooltip' data-placement='bottom' runat='server'";
        }

        $disableStop = $count > 0 || !$this->instance->canDo('stop', $ldapInstance);
        $buttonHtml .= "<span {$toolTips} title='{$title}'>";
        $buttonHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$ldapInstance->id}' title='{$title}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-danger label-lg');
        $buttonHtml .= "</span>";

        echo $buttonHtml;
    }

    /**
     * Install QuCheng LDAP.
     *
     * @param  string   $channel
     * @access public
     * @return bool|object
     */
    public function installQuchengLDAP($ldapApp, $channel)
    {
        return $this->loadModel('instance')->installLDAP($ldapApp, '', 'OpenLDAP', $k8name = '', $channel);
    }

    /**
     * Update Qucheng LDAP.
     *
     * @param  object $ldapApp
     * @param  string $channel
     * @access public
     * @return string
     */
    public function updateQuchengLDAP($ldapApp, $channel)
    {
        $instanceID = $this->setting->getItem('owner=system&module=common&section=ldap&key=instanceID');
        if($instanceID)
        {
            /* If QuCheng internal LDAP has been installed. Set QuCheng LDAP to be active. */
            return $this->setting->setItem('system.common.ldap.active', 'qucheng');
        }
        else
        {
            /* If QuCheng internal LDAP has not been installed. */
            return $this->installQuchengLDAP($ldapApp, $channel);
        }
    }

    /**
     * Install or update extra LDAP: it is creating a snippet in k8s system in fact.
     *
     * @param  object    $settings
     * @access protected
     * @return bool
     */
    public function configExtraLDAP($settings)
    {
        if(!$this->testLDAPConnection($settings))
        {
            dao::$errors[] = $this->lang->system->notSupportedLDAP;
            return false;
        }

        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $snippetSettings = new stdclass;
        $snippetSettings->name        = 'snippet-extra-ldap';
        $snippetSettings->namespace   = $space->k8space;
        $snippetSettings->auto_import = false;

        $snippetSettings->values = new stdclass;
        $snippetSettings->values->auth = new stdclass;
        $snippetSettings->values->auth->ldap = new stdclass;
        $snippetSettings->values->auth->ldap->enabled   = true;
        $snippetSettings->values->auth->ldap->type      = 'ldap';
        $snippetSettings->values->auth->ldap->host      = $settings->host;
        $snippetSettings->values->auth->ldap->port      = strval($settings->port);
        $snippetSettings->values->auth->ldap->bindDN    = "cn={$settings->bindDN},{$settings->baseDN}";
        $snippetSettings->values->auth->ldap->bindPass  = $settings->bindPass;
        $snippetSettings->values->auth->ldap->baseDN    = $settings->baseDN;
        $snippetSettings->values->auth->ldap->filter    = html_entity_decode($settings->filter);
        $snippetSettings->values->auth->ldap->attrUser  = $settings->attrUser;
        $snippetSettings->values->auth->ldap->attrEmail = $settings->attrEmail;

        $exists = $this->getExtraLDAPSettings();
        if(empty($exists))
        {
            $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
            if($snippetResult->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToInstallExtraLDAP;
                return false;
            }
        }
        else
        {
            $snippetResult = $this->loadModel('cne')->updateSnippet($snippetSettings);
            if($snippetResult->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUpdateExtraLDAP;
                return false;
            }
        }

        /* Save extra LDAP setting to database. */
        $secretKey          = helper::readKey();
        $encryptedPassword  = openssl_encrypt($snippetSettings->values->auth->ldap->bindPass, 'DES-ECB', $secretKey);
        $settings->bindPass = $encryptedPassword;

        $this->setting->setItem('system.common.ldap.active', 'extra');
        $this->setting->setItem('system.common.ldap.extraSnippetName', $snippetSettings->name); // Parameter for App installation API.
        $this->setting->setItem('system.common.ldap.extraSettings', json_encode($settings));

        return true;
    }

    /**
     * Update LDAP config and update instance.
     *
     * @param  object $ldapApp
     * @param  string $channel
     * @access public
     * @return void
     */
    public function updateLDAP($ldapApp, $channel)
    {
        $postData = fixer::input('post')->setDefault('source', 'qucheng')->get();
        if($postData->source == 'qucheng')
        {
            $success = $this->updateQuchengLDAP($ldapApp, $channel);
        }
        else if($postData->source == 'extra')
        {
            $success = $this->configExtraLDAP((object)$postData->extra);
            if($success) $this->uninstallQuChengLDAP(true);
        }

        if(!$success) return false;

        /* Update instances that has been enabled LDAP. */
        $instanceList = $this->loadModel('instance')->getListEnabledLDAP();
        $counter = count($instanceList);
        foreach($instanceList  as $instance)
        {
            $this->loadModel('setting')->setItem('system.common.ldap.updatingProgress', $counter);
            $this->instance->switchLDAP($instance, true);
            $counter-- ;
        }

        $this->loadModel('setting')->deleteItems('owner=system&module=common&section=ldap&key=updatingProgress');
        return true;
    }

    /**
     * Uninstall QuCheng LDAP.
     *
     * @param bool    $force
     * @access public
     * @return bool
     */
    public function uninstallQuChengLDAP($force = false)
    {
        if(!$force)
        {
            $ldapLinked = $this->loadModel('instance')->countLDAP();
            if($ldapLinked)
            {
                dao::$errors[] = $this->lang->system->errors->LDAPLinked;
                return false;
            }
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=ldap&key=instanceID');
        $instance   = $this->loadModel('instance')->getByID($instanceID);
        if($instance)
        {
            /* 1. Uninstall QuCheng LDAP service. */
            if(!$this->loadModel('instance')->uninstall($instance))
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
                return false;
            }

            /* 2. Remove snippet config map from CNE. */
            $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

            $apiParams = new stdclass;
            $apiParams->name      = 'snippet-qucheng-ldap';
            $apiParams->namespace = $space->k8space;

            $result = $this->loadModel('cne')->removeSnippet($apiParams);
            if($result->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
                return false;
            }
        }

        /* 3. Delete LDAP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=instanceID');
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=snippetName');
        if($this->getActiveLDAP() == 'qucheng') $this->setting->deleteItems('owner=system&module=common&section=ldap&key=active');

        return true;
    }

    /**
     * Uninstall extra LDAP.
     *
     * @access public
     * @return bool
     */
    public function uninstallExtraLDAP()
    {
        $ldapLinked = $this->loadModel('instance')->countLDAP();
        if($ldapLinked)
        {
            dao::$errors[] = $this->lang->system->errors->LDAPLinked;
            return false;
        }

        /* 1. Remove snippet config map from CNE. */
        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $apiParams = new stdclass;
        $apiParams->name      = 'snippet-extra-ldap';
        $apiParams->namespace = $space->k8space;

        $result = $this->loadModel('cne')->removeSnippet($apiParams);
        if($result->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToDeleteLDAPSnippet;
            return false;
        }

        /* 2. Delete extra LDAP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=extraSettings');
        $this->setting->deleteItems('owner=system&module=common&section=ldap&key=extraSnippetName');
        if($this->getActiveLDAP() == 'extra') $this->setting->deleteItems('owner=system&module=common&section=ldap&key=active');

        return true;
    }

    /**
     * Get extra LDAP settings.
     *
     * @access public
     * @return object|array
     */
    public function getExtraLDAPSettings()
    {
        $settings = $this->setting->getItem('owner=system&module=common&section=ldap&key=extraSettings');
        $settings = @json_decode($settings);
        if(empty($settings)) return array();

        $secretKey          = helper::readKey();
        $settings->bindPass = openssl_decrypt($settings->bindPass, 'DES-ECB', $secretKey);
        return $settings;
    }

    /**
     * Test LDAP Connection by post settings.
     *
     * @param  object $settings
     * @access public
     * @return bool
     */
    public function testLDAPConnection($settings)
    {
        $connectID = ldap_connect("ldap://{$settings->host}:{$settings->port}");

        if(!ldap_set_option($connectID, LDAP_OPT_PROTOCOL_VERSION, 3)) return false;

        return ldap_bind($connectID, "cn={$settings->bindDN},{$settings->baseDN}", $settings->bindPass);
    }

    /**
     * Has installed global LDAP or not.
     *
     * @access public
     * @return bool
     */
    public function hasSystemLDAP()
    {
        $activeLDAP = $this->setting->getItem('owner=system&module=common&section=ldap&key=active');
        return $activeLDAP == 'extra' or $activeLDAP == 'qucheng'; // LDAP has been installed.
    }

    /**
     * Get global LDAP snippet name.
     *
     * @access public
     * @return string
     */
    public function ldapSnippetName()
    {
        $activeLDAP = $this->setting->getItem('owner=system&module=common&section=ldap&key=active');

        if($activeLDAP == 'extra') return $this->setting->getItem('owner=system&module=common&section=ldap&key=extraSnippetName');

        return $this->setting->getItem('owner=system&module=common&section=ldap&key=snippetName');
    }

    /**
     * Get active LDAP type.
     *
     * @access public
     * @return string|null
     */
    public function getActiveLDAP()
    {
        return $this->setting->getItem('owner=system&module=common&section=ldap&key=active');
    }

    /**
     * Check global SMTP is enabled or not.
     *
     * @access public
     * @return bool
     */
    public function isSMTPEnabled()
    {
        $smtpSnippetName = $this->smtpSnippetName();
        $enabled         = $this->setting->getItem('owner=system&module=common&section=smtp&key=enabled');

        return $smtpSnippetName && $enabled;
    }

    /**
     * Get SMTP snippet name.
     *
     * @access public
     * @return string
     */
    public function smtpSnippetName()
    {
        return $this->setting->getItem('owner=system&module=common&section=smtp&key=snippetName');
    }

    /**
     * Get SMTP settings.
     *
     * @access public
     * @return object
     */
    public function getSMTPSettings()
    {
        $settingMap = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=settingsMap'));
        if(empty($settingMap)) return new stdclass;

        $settings = $settingMap->env;
        $settings->SMTP_PASS = openssl_decrypt($settings->SMTP_PASS, 'DES-ECB', helper::readKey());

        //$snippetSettings = json_decode($this->setting->getItem('owner=system&module=common&section=smtp&key=snippetSettings'));

        return $settings;
    }

    /**
     * Update SMTP settings.
     *
     * @access public
     * @return void
     */
    public function updateSMTPSettings()
    {
        $this->loadModel('cne');
        $this->loadModel('instance');

        $channel  = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;

        $smtpSettings = fixer::input('post')->get();

        $instanceID = $this->setting->getItem('owner=system&module=common&section=smtp&key=instanceID');
        $instance   = $this->instance->getByID($instanceID);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->notFoundSMTPService;
            return false;
        }

        $instance->version = 'latest'; // Update and upgrade SMTP proxy instance.

        /* 1. Update SMTP service settings. */
        $settingsMap = new stdclass;
        $settingsMap->env = new stdclass;
        $settingsMap->env->SMTP_HOST = $smtpSettings->host;
        $settingsMap->env->SMTP_PORT = strval($smtpSettings->port);
        $settingsMap->env->SMTP_USER = $smtpSettings->user;
        $settingsMap->env->SMTP_PASS = $smtpSettings->pass;

        $settings = new stdclass;
        $settings->settings_map = $settingsMap;

        $success = $this->loadModel('cne')->updateConfig($instance, $settings);
        if(!$success)
        {
            dao::$errors[] = $this->lang->system->errors->failToUpdateSMTP;
            return false;
        }

        /* 2. Save SMTP account. */
        $secretKey = helper::readKey();
        $settingsMap->env->SMTP_PASS = openssl_encrypt($settingsMap->env->SMTP_PASS, 'DES-ECB', $secretKey);

        $this->loadModel('setting');
        $this->setting->setItem('system.common.smtp.settingsMap', json_encode($settingsMap));

        return true;
    }

    /**
     * Install global SMTP service.
     *
     * @param  string $channel
     * @access public
     * @return bool
     */
    public function installSysSMTP($channel = 'stable')
    {
        $settings = fixer::input('post')->get();

        $smtpApp = $this->loadModel('store')->getAppInfoByChart('cne-courier', $channel, false);
        if(empty($smtpApp))
        {
            dao::$errors[] = $this->lang->system->notFoundSMTPApp;
            return false;
        }

        $result = $this->loadModel('instance')->installSysSMTP($smtpApp, $settings, 'cne-courier', '', $channel);
        return $result;
    }

    /**
     * Uninstall system SMTP.
     *
     * @access public
     * @return bool
     */
    public function uninstallSysSMTP()
    {
        $smtpLinked = $this->loadModel('instance')->countSMTP();
        if($smtpLinked)
        {
            dao::$errors[] = $this->lang->system->errors->SMTPLinked;
            return false;
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=smtp&key=instanceID');
        $instance   = $this->instance->getByID($instanceID);
        if($instance)
        {
            /* 1. Uninstall QuCheng LDAP service. */
            if(!$this->loadModel('instance')->uninstall($instance))
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallSMTP;
                return false;
            }

            /* 2. Remove snippet config map from CNE. */
            $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

            $apiParams = new stdclass;
            $apiParams->name      = 'snippet-smtp-proxy';
            $apiParams->namespace = $space->k8space;

            $result = $this->loadModel('cne')->removeSnippet($apiParams);
            if($result->code != 200)
            {
                dao::$errors[] = $this->lang->system->errors->failToUninstallQuChengLDAP;
                return false;
            }
        }

        /* 3. Delete SMTP settings in database. */
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=enabled');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=instanceID');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=snippetName');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=settingsMap');
        $this->setting->deleteItems('owner=system&module=common&section=smtp&key=snippetSettings');

        return true;
    }

    /**
     * Get customized domain settings. *
     * @access public
     * @return object
     */
    public function getDomainSettings()
    {
        $settings = new stdclass;
        $settings->customDomain = $this->setting->getItem('owner=system&module=common&section=domain&key=customDomain');
        $settings->https        = $this->setting->getItem('owner=system&module=common&section=domain&key=https');
        $settings->certPem      = ''; //
        $settings->certKey      = ''; //

        return $settings;
    }

    /**
     * Save customized somain settings.
     *
     * @access public
     * @return void
     */
    public function saveDomainSettings()
    {
        $settings = fixer::input('post')
            ->setDefault('customDomain', '')
            ->setDefault('https', 'false')
            ->setIf(is_array($this->post->https) && in_array('true', $this->post->https), 'https', 'true')
            ->setDefault('certPem', '')
            ->setDefault('certKey', '')
            ->get();

        $this->dao->from('system')->data($settings)
            ->check('customDomain', 'notempty')
            ->checkIf($settings->https == 'true', 'certPem', 'notempty')
            ->checkIf($settings->https == 'true', 'certKey', 'notempty');
        if(dao::isError()) return;

        if(!validater::checkREG($settings->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            dao::$errors[] = $this->lang->system->errors->invalidDomain;
            return;
        }

        /* Upload Certificate to CNE. */
        if($settings->https == 'true')
        {
            $cert = new stdclass;
            $cert->name            = 'tls-' . str_replace('.', '-', $settings->customDomain);
            $cert->certificate_pem = $settings->certPem;
            $cert->private_key_pem = $settings->certKey;
            $certResult = $this->loadModel('cne')->uploadCert($cert);
            if($certResult->code != 200)
            {
                dao::$errors[] = $certResult->message;
                return;
            }
        }

        $oldSettings = $this->getDomainSettings();
        if($settings->customDomain == $oldSettings->customDomain)
        {
            dao::$errors[] = $this->lang->system->errors->newDomainIsSameWithOld;
            return;
        }

        if(stripos($settings->customDomain, 'haogs.cn') !== false)
        {
            dao::$errors[] = $this->lang->system->errors->forbiddenOriginalDomain;
            return;
        }

        $expiredDomain   = $this->setting->getItem('owner=system&module=common&section=domain&key=expiredDomain');
        $expiredDomain   = empty($expiredDomain ) ? array(getenv('APP_DOMAIN')) : json_decode($expiredDomain, true);
        $expiredDomain[] = zget($settings, 'customDomain', '');
        $this->setting->setItem('system.common.domain.expiredDomain', json_encode($expiredDomain));

        $this->setting->setItem('system.common.domain.customDomain', zget($settings, 'customDomain', ''));
        $this->setting->setItem('system.common.domain.https', zget($settings, 'https', 'false'));

        $this->loadModel('instance')->updateInstancesDomain();

        $this->updateMinioDomain();
    }

    /**
     * Update minio domain.
     *
     * @access public
     * @return void
     */
    public function updateMinioDomain()
    {
        $this->loadModel('cne');
        $sysDomain = $this->cne->sysDomain();

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->chart     = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->minio = new stdclass;
        $settings->settings_map->minio->ingress = new stdclass;
        $settings->settings_map->minio->ingress->enabled = true;
        $settings->settings_map->minio->ingress->host    = 's3.' . $sysDomain;

        $this->cne->updateConfig($minioInstance, $settings);
    }

    /**
     * Get SLB settings.
     *
     * @access public
     * @return object
     */
    public function getSLBSettings()
    {
        $settings = new stdclass;
        $settings->instanceID = $this->setting->getItem('owner=system&module=common&section=slb&key=instanceID');
        $settings->name       = $this->setting->getItem('owner=system&module=common&section=slb&key=name');
        $settings->ippool     = '';

        if($settings->instanceID)
        {
            $slbInstance = $this->loadModel('instance')->getByID($settings->instanceID);

            $qlbInfo = $this->loadmodel('cne')->getQLBinfo($settings->name, $slbInstance->spaceData->k8space); // QLB: Qucheng load balancer.
            if($qlbInfo) $settings->ippool = $qlbInfo->ippool;
        }

        return $settings;
    }

    /**
     * Save SLB settings.
     *
     * @access public
     * @return void
     */
    public function saveSLBSettings()
    {
        $settings = fixer::input('post')
            ->setDefault('ippool', '')
            ->get();
        if(empty($settings->ippool))
        {
            dao::$errors[] = $this->lang->system->errors->ippoolRequired;
            return;
        }

        $reg1Result = validater::checkREG($settings->ippool, '/^((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}\/\d{1,2}$/');
        $reg2Result = validater::checkREG($settings->ippool, '/^(((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3})-(((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3})$/');
        if($reg1Result === false && $reg2Result === false)
        {
            dao::$errors[] = $this->lang->system->errors->wrongIPRange;
            return;
        }

        $instanceID = $this->setting->getItem('owner=system&module=common&section=slb&key=instanceID');
        if(!$instanceID)
        {
            /* 1. Install SLB component metallb at first time. */
            $slbInstance = $this->installSLBInstance();
            if(!$slbInstance) return;

            $instanceID = $slbInstance->id;
            $this->setting->setItem('system.common.slb.instanceID', $slbInstance->id);
        }

        $instance = $this->loadModel('instance')->getByID($instanceID);
        $status = '';
        /* Wait 30 seconds at most for SLB instance ready. */
        for($times = 0; $times < 10; $times++)
        {
            sleep(3);
            $statusResponse = $this->loadModel('cne')->queryStatus($instance);
            if($statusResponse->code != 200) continue;

            $status = $statusResponse->data->status;
            if($status == 'running') break;
        }

        if($status != 'running')
        {
            dao::$errors[] = $this->lang->system->errors->tryReinstallSLB;
            return;
        }

        /* 2. Config SLB. */
        $settings->name      = 'qlb-quickon';
        $settings->namespace = $this->config->k8space;
        $success = $this->loadModel('cne')->configQLB($settings);
        if(!$success)
        {
            dao::$errors[] = $this->lang->system->errors->failedToConfigSLB;
            return;
        }

        $this->setting->setItem('system.common.slb.name', zget($settings, 'name', ''));
    }

    /**
     * Install SLB instance.
     *
     * @access private
     * @return object|null
     */
    private function installSLBInstance()
    {
        $channel = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;

        $slbApp = new stdclass;
        $slbApp->name        = 'metallb';
        $slbApp->alias       = 'metallb';
        $slbApp->desc        = 'metallb';
        $slbApp->chart       = 'metallb';
        $slbApp->app_version = '';
        $slbApp->version     = '';
        $slbApp->id          = '';
        $slbApp->logo        = '';

        $slbInstance = $this->loadModel('instance')->installSysSLB($slbApp, 'cne-lb', $channel);
        if(!$slbInstance)
        {
            dao::$errors[] = $this->lang->system->errors->failedToInstallSLBComponent;
            return;
        }

        return $slbInstance;
    }

    /**
     * Print SMTP buttons.
     *
     * @param  objevt $smtpInstance
     * @access public
     * @return string
     */
    public function printSMTPButtons($smtpInstance)
    {
        $this->loadModel('instance');
        $this->app->loadLang('instance');

        $buttonHtml = '';

        $disableStart = !$this->instance->canDo('start', $smtpInstance);
        $buttonHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$smtpInstance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $title    = $this->lang->instance->stop;
        $toolTips = '';
        $count    = $this->instance->countSMTP();
        if($count)
        {
            $title    = sprintf($this->lang->system->notices->smtpUsed, $count);
            $toolTips = "data-toggle='tooltip' data-placement='bottom' runat='server'";
        }

        $buttonHtml .= "<span class='edit-tools-tips' {$toolTips} title='{$title}'>";
        $buttonHtml .= html::a(inLink('editSMTP'), $this->lang->system->SMTP->edit, '', "title='{$title}' class='btn-edit btn label label-outline label-primary label-lg'");
        $buttonHtml .= "</span>";

        $disableStop = $count > 0 || !$this->instance->canDo('stop', $smtpInstance);
        $buttonHtml .= "<span {$toolTips} title='{$title}'>";
        $buttonHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$smtpInstance->id}' title='{$title}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-danger label-lg');
        $buttonHtml .= "</span>";

        echo $buttonHtml;
    }

    public function getK8sTag()
    {
        $serviceAccount = '/var/run/secrets/kubernetes.io/serviceaccount';
        if(!is_dir($serviceAccount)) return "Fail: Please run script in k8s.";

        $nameSpace = file_get_contents($serviceAccount . '/namespace');

        $apiServer = empty($_POST['apiServer']) ? 'https://kubernetes.default.svc' : $_POST['apiServer'];
        $token     = file_get_contents($serviceAccount . '/token');
        $cacert    = $serviceAccount . '/ca.crt';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$apiServer/api/v1/namespaces/{$nameSpace}/serviceaccounts/default");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CAINFO, $cacert);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $response = curl_exec($curl);
        $errors   = curl_error($curl);
        curl_close($curl);

        if($errors) return "Fail: " . $errors;

        $serviceAccounts = json_decode($response);
        if(empty($serviceAccounts->metadata->uid))
        {
            if($serviceAccounts->code == '403') return $this->lang->misc->k8s->repairNotice;
            return "Fail: " . $response;
        }

        $infos = array();
        $infos['apiServer']  = $apiServer;
        $infos['nameSpace']  = $nameSpace;
        $infos['uid']        = '';
        if(isset($serviceAccounts->metadata->uid)) $infos['uid'] = $serviceAccounts->metadata->uid;

        if(empty($infos['uid'])) return "Fail: Can not find k8s uid information";

        $md5 = md5($this->config->global->sn);
        $systemDebug = $this->config->debug;
        $this->config->debug = false;
        $encrypted = @openssl_encrypt(json_encode($infos), 'DES-CBC', substr($md5, 0, 8));
        $this->config->debug = $systemDebug;
        return $encrypted;
    }

    /**
     * Backup SQL.
     *
     * @param  string    $backupFile
     * @access public
     * @return object
     */
    public function backSQL($backupFile, $backupType = 'manual')
    {
        $zdb = $this->app->loadClass('zdb');
        $dumpStatus = $zdb->dump($backupFile);
        if($dumpStatus->result === true) $this->processSQLSummary($backupFile, $backupType);
        return $dumpStatus;
    }

    /**
     * Restore SQL.
     *
     * @param  string    $backupFile
     * @access public
     * @return object
     */
    public function restoreSQL($backupFile)
    {
        $zdb    = $this->app->loadClass('zdb');
        $nosafe = strpos($this->config->backup->setting, 'nosafe') !== false;

        $backupDir    = dirname($backupFile);
        $fileName     = date('YmdHis') . mt_rand(0, 9);
        $backFileName = "{$backupDir}/{$fileName}.sql";
        if(!$nosafe) $backFileName .= '.php';

        $result = $this->backSQL($backFileName, 'restore');
        if($result->result and !$nosafe) $this->addFileHeader($backFileName);

        $allTables = $zdb->getAllTables();
        foreach($allTables as $tableName => $tableType)
        {
            try
            {
                $this->dbh->query("DROP $tableType IF EXISTS `$tableName`");
            }
            catch(PDOException $e){}
        }

        $importResult = $zdb->import($backupFile);

        if($importResult && $importResult->result)
        {
            $this->loadModel('instance')->restoreInstanceList();
            $this->processRestoreSummary('sql', 'done');
        }

        return $importResult;
    }

    /**
     * Restore File.
     *
     * @param  string    $backupFile
     * @access public
     * @return object
     */
    public function restoreFile($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        if(is_file($backupFile))
        {
            $oldDir = getcwd();
            chdir($this->app->getTmpRoot());
            $this->app->loadClass('pclzip', true);
            $zip = new pclzip($backupFile);
            if($zip->extract(PCLZIP_OPT_PATH, $this->app->getAppRoot() . 'www/data/', PCLZIP_OPT_TEMP_FILE_ON) == 0)
            {
                $return->result = false;
                $return->error  = $zip->errorInfo();
            }
            chdir($oldDir);
        }
        elseif(is_dir($backupFile))
        {
            $zfile = $this->app->loadClass('zfile');
            $zfile->copyDir($backupFile, $this->app->getAppRoot() . 'www/data/', $showDetails = false);
        }

        $this->processRestoreSummary('file', 'done');

        return $return;
    }

    /**
     * Get backup account and backup type.
     *
     * @param  string  $file
     * @access public
     * @return array
     */
    public function getSQLSummary($file)
    {
        $summaryFile = $this->loadModel('backup')->getBackupPath() . DS . 'summary';
        $sqlSummary = json_decode(file_get_contents($summaryFile), true);
        return isset($sqlSummary[basename($file)]) ? $sqlSummary[basename($file)] : array();
    }

    /**
     * Process restore summay.
     *
     * @param  string $restoreType
     * @param  string $status
     * @param  string $action
     * @access public
     * @return bool
     */
    public function processRestoreSummary($restoreType = 'sql', $status = 'done', $action = 'add')
    {
        $summaryFile = $this->loadModel('backup')->getBackupPath() . DS . 'restoreSummary';
        if(!file_exists($summaryFile) and !touch($summaryFile)) return false;

        $summary = json_decode(file_get_contents($summaryFile), true);
        if(empty($summary)) $summary = array();

        if($action == 'add')
        {
            $summary[$restoreType] = $status;
        }
        else
        {
            $summary = array();
        }
        if(file_put_contents($summaryFile, json_encode($summary))) return true;
        return false;

    }

    /**
     * Save backup account and backup type.
     *
     * @param  string $file
     * @param  string $type
     * @param  string $action
     * @access public
     * @return bool
     */
    public function processSQLSummary($file, $type = 'manual', $action = 'add')
    {
        $backupPath = dirname($file);
        $fileName   = basename($file);

        $summaryFile = $backupPath . DS . 'summary';
        if(!file_exists($summaryFile) and !touch($summaryFile)) return false;

        $summary = json_decode(file_get_contents($summaryFile), true);
        if(empty($summary)) $summary = array();

        if($action == 'add')
        {
            $summary[$fileName]['account']    = $this->app->user->account == 'guest' ? '' : $this->app->user->account;
            $summary[$fileName]['backupType'] = $type;
        }
        else
        {
            unset($summary[$fileName]);
        }

        if(file_put_contents($summaryFile, json_encode($summary))) return true;
        return false;
    }

    /**
     * Check upgrade process is overtime (5 miniutes) or not.
     *
     * @access public
     * @return mixed
     */
    public function isGradeOvertime()
    {
        $upgradedAt = $this->loadModel('setting')->getItem('owner=system&module=backup&section=global&key=upgradedAt');

        return (time() - intval($upgradedAt)) > 300;
    }

}
