<?php
declare(strict_types=1);
/**
 * The zen file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
class adminZen extends admin
{
    /**
     * The extension manager version. Don't change it.
     */
    const EXT_MANAGER_VERSION = '1.3';

    /**
     * 初始化sn。
     * Init sn.
     *
     * @access protected
     * @return void
     */
    protected function initSN(): void
    {
        if(!isset($this->config->global->sn))
        {
            $this->loadModel('setting');
            $this->setting->setItem('system.common.global.sn', $this->setting->computeSN());

            if(!isset($this->config->global)) $this->config->global = new stdclass();
            $this->config->global->sn = $this->setting->getItem('owner=system&module=common&section=global&key=sn');
        }
    }

    /**
     * Sync extensions from zentao official website by api.
     *
     * @param  string $type         plugin|patch
     * @param  int    $limit
     * @param  bool   $hasInternet
     * @access protected
     * @return bool
     */
    protected function syncExtensions(string $type = 'plugin', int $limit = 5): bool
    {
        $searchType = $type == 'plugin' ? 'byModule,offcial' : 'byModule';
        $param      = $type == 'plugin' ? '' : 'MTIxOA==';
        $extensions = $this->loadModel('extension')->getExtensionsByAPI($searchType, $param, 0, $limit);
        $extensions = isset($extensions->extensions) ? (array)$extensions->extensions : array();
        $plugins    = array();
        foreach($extensions as $extension)
        {
            if($type == 'patch' and !isset($extension->compatibleRelease)) continue;

            $extension->viewLink = str_replace(array('info', 'client'), '', $extension->viewLink);
            $plugins[] = $extension;
        }

        return $this->loadModel('setting')->setItem("system.common.zentaoWebsite.$type", json_encode($plugins));
    }

    /**
     * Sync public classes from zentao official website by api.
     *
     * @param  int    $limit
     * @access protected
     * @return bool
     */
    protected function syncPublicClasses(int $limit = 3): bool
    {
        $apiURL  = $this->config->admin->videoAPIURL;
        $data    = $this->fetchAPI($apiURL);
        $courses = $data->videos;

        $index       = 1;
        $publicClass = array();
        foreach($courses as $course)
        {
            if($index > $limit) break;

            $publicClass[$index] = new stdClass();
            $publicClass[$index]->name     = $course->title;
            $publicClass[$index]->image    = $this->config->admin->cdnRoot . $course->image->list[0]->middleURL;
            $publicClass[$index]->viewLink = $this->config->admin->apiRoot . '/publicclass/' . ($course->alias ? "{$course->alias}-" : '') . "{$course->id}.html";
            $index ++;
        }

        return $this->loadModel('setting')->setItem('system.common.zentaoWebsite.publicClass', json_encode($publicClass));
    }

    /**
     * Sync dynamics from zentao official website by API.
     *
     * @param  int    $limit
     * @access protected
     * @return bool
     */
    protected function syncDynamics(int $limit = 2): bool
    {
        $apiURL   = $this->config->admin->downloadAPIURL;
        $data     = $this->fetchAPI($apiURL);
        $articles = $data->articles;

        $index    = 1;
        $dynamics = array();
        foreach($articles as $article)
        {
            if($index > $limit) break;

            $tagKey = $this->config->edition . 'Tag';
            if(!isset($this->lang->admin->$tagKey)) break;
            if(!preg_match("/{$this->lang->admin->$tagKey}\d/", $article->title)) continue;

            $dynamics[$index] = new stdClass();
            $dynamics[$index]->id        = $article->id;
            $dynamics[$index]->title     = $article->title;
            $dynamics[$index]->addedDate = $article->addedDate;
            $dynamics[$index]->link      = $this->config->admin->apiRoot . "/download/{$article->alias}-{$article->id}.html";
            $index ++;
        }

        return $this->loadModel('setting')->setItem('system.common.zentaoWebsite.dynamic', json_encode($dynamics));
    }

    /**
     * Fetch data from an API.
     *
     * @param  string $url
     * @access protected
     * @return bool|array|object
     */
    protected function fetchAPI(string $url): bool|array|object
    {
        $version = $this->loadModel('upgrade')->getOpenVersion(str_replace('.', '_', $this->config->version));
        $version = str_replace('_', '.', $version);

        $url   .= (strpos($url, '?') === false ? '?' : '&') . 'lang=' . str_replace('-', '_', $this->app->getClientLang()) . '&managerVersion=' . self::EXT_MANAGER_VERSION . '&zentaoVersion=' . $version . '&edition=' . $this->config->edition;
        $result = json_decode(preg_replace('/[[:cntrl:]]/mu', '', common::http($url)));

        if(!isset($result->status)) return false;
        if($result->status != 'success') return false;
        if(isset($result->data)) return json_decode($result->data);
    }

    /**
     * 注册禅道账号。
     * Register zentao by API.
     *
     * @access protected
     * @return string
     */
    protected function registerByAPI(): string
    {
        $apiConfig = $this->admin->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiRegister.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
        return common::http($apiURL, $_POST);
    }

    /**
     * 绑定禅道账号。
     * Login zentao by API.
     *
     * @access protected
     * @return string
     */
    protected function bindByAPI(): string
    {
        $apiConfig = $this->admin->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-bindChanzhi.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
        return common::http($apiURL, $_POST);
    }

    /**
     * 发送验证码。
     * Send code by API.
     *
     * @param  string    $type mobile|email
     * @access protected
     * @return string
     */
    protected function sendCodeByAPI($type): string
    {
        $apiConfig = $this->admin->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiSendCode.json";

        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        if(isset($this->config->global->community) and $this->config->global->community != 'na') $this->post->set('account', $this->config->global->community);

        $param = http_build_query($params);
        return common::http($apiURL . '?' . $param, $_POST);
    }

    /**
     * 认证手机或邮箱。
     * Certify by API.
     *
     * @param  string    $type mobile|email
     * @access protected
     * @return string
     */
    protected function certifyByAPI($type): string
    {
        $apiConfig = $this->admin->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiCertify.json";

        $params['u'] = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->admin->getSignature($params);

        $param = http_build_query($params);
        return common::http($apiURL . '?' . $param, $_POST);
    }

    /**
     * 认证公司。
     * Set company by API.
     *
     * @access protected
     * @return string
     */
    protected function setCompanyByAPI(): string
    {
        $apiConfig = $this->admin->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiSetCompany.json";

        $params['u'] = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->admin->getSignature($params);

        $param = http_build_query($params);
        return common::http($apiURL . '?' . $param, $_POST);
    }

    /**
     * 获取禅道社区注册信息。
     * Get register information.
     *
     * @access protected
     * @return object
     */
    protected function getRegisterInfo(): object
    {
        $register = new stdclass();
        $register->company = $this->app->company->name;
        $register->email   = $this->app->user->email;
        return $register;
    }

    /**
     * 获取禅道官网数据。
     * Get zentao.net data.
     *
     * @access protected
     * @return object
     */
    protected function getZentaoData(): object
    {
        $data = new stdclass();
        $data->hasData  = true;
        $data->dynamics = array();
        $data->classes  = array();
        $data->plugins  = array();
        $data->patches  = array();

        $zentaoData = !empty($this->config->zentaoWebsite) ? $this->config->zentaoWebsite : null;
        if(empty($zentaoData))
        {
            $data->hasData = false;
            if($this->config->edition == 'open')
            {
                $data->plugins = array(
                    $this->config->admin->plugins[27],
                    $this->config->admin->plugins[26],
                    $this->config->admin->plugins[30]
                );
            }
            else
            {
                $data->plugins = array(
                    $this->config->admin->plugins[198],
                    $this->config->admin->plugins[194],
                    $this->config->admin->plugins[203]
                );
            }
        }
        else
        {
            if(!empty($zentaoData->dynamic))     $data->dynamics = json_decode($zentaoData->dynamic);
            if(!empty($zentaoData->publicClass)) $data->classes  = json_decode($zentaoData->publicClass);
            if(!empty($zentaoData->plugin))      $data->plugins  = json_decode($zentaoData->plugin);
            if(!empty($zentaoData->patch))       $data->patches  = json_decode($zentaoData->patch);
            if(common::checkNotCN()) array_pop($data->plugins);
        }

        return $data;
    }
}
