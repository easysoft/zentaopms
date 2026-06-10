<?php
declare(strict_types=1);
/**
 * The zen file of provider module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
class providerZen extends provider
{
    /**
     * 检查服务地址。
     * Check provider url.
     *
     * @param  object $provider
     * @access public
     * @return bool
     */
    public function checkServiceUrl(object $provider): bool
    {
        $type = zget($provider, 'type', '');
        $url  = trim((string)zget($provider, 'url', ''));
        if(empty($type) || $type == 'Subversion' || empty($url)) return true;

        if(!filter_var($url, FILTER_VALIDATE_URL))
        {
            dao::$errors['url'][] = sprintf($this->lang->error->URL, $this->lang->provider->url);
            return false;
        }

        $apiUrl = $this->getCheckApiUrl($type, $url);
        if(empty($apiUrl)) return true;

        $headers  = $this->getCheckHeaders($type, trim((string)zget($provider, 'token', '')));
        $response = json_decode(common::http($apiUrl, null, array(), $headers, 'JSON'));

        if(empty($response))
        {
            $requestError = zget(commonModel::$requestErrors, 0, '');
            dao::$errors['url'][] = empty($requestError) ? $this->lang->provider->error->api : sprintf($this->lang->provider->error->apiWithMessage, $requestError);
        }
        if(!empty($response->message))
        {
            $message = $response->message;
            dao::$errors['url'][] = sprintf($this->lang->provider->error->apiWithMessage, $message);
        }

        return !dao::isError();
    }

    /**
     * 获取探活接口地址。
     * Get api url for checking provider connectivity.
     *
     * @param  string $type
     * @param  string $url
     * @access protected
     * @return string
     */
    protected function getCheckApiUrl(string $type, string $url): string
    {
        $url   = rtrim($url, '/');
        $parts = parse_url($url);
        $host  = strtolower((string)zget($parts, 'host', ''));
        $path  = trim((string)zget($parts, 'path', ''), '/');

        if($type == 'GitHub')
        {
            if($host == 'github.com' || $host == 'www.github.com') return 'https://api.github.com/user';
            if($host == 'api.github.com') return $url . '/user';
            if($path == 'api/v3' || strpos($path, 'api/v3/') === 0) return $url . '/user';
            return $url . '/api/v3/user';
        }

        if($type == 'Jenkins') return $url . '/api/json';

        $apiRoots = array('GitLab' => 'api/v4', 'Gitea' => 'api/v1', 'Gogs' => 'api/v1');
        $apiRoot  = zget($apiRoots, $type, '');
        if(empty($apiRoot)) return '';

        if($path == $apiRoot || strpos($path, $apiRoot . '/') === 0) return $url . '/user';
        return $url . '/' . $apiRoot . '/user';
    }
}
