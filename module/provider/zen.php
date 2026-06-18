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
        if(empty($type) || empty($url)) return true;

        if($type == 'Subversion') return $this->checkSubversionUrl($url);

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
     * 检查 Subversion 服务地址。
     * Check Subversion provider url.
     *
     * @param  string $url
     * @access protected
     * @return bool
     */
    protected function checkSubversionUrl(string $url): bool
    {
        if(!$this->isValidSubversionUrl($url))
        {
            dao::$errors['url'][] = sprintf($this->lang->error->URL, $this->lang->provider->url);
            return false;
        }

        commonModel::$requestErrors = array();
        if($this->isAccessibleSubversionUrl($url)) return true;

        $message = zget(commonModel::$requestErrors, 0, '');
        dao::$errors['url'][] = empty($message) ? $this->lang->provider->error->api : sprintf($this->lang->provider->error->apiWithMessage, $message);
        return false;
    }

    /**
     * 检查 Subversion 服务地址格式。
     * Check Subversion provider url format.
     *
     * @param  string $url
     * @access protected
     * @return bool
     */
    protected function isValidSubversionUrl(string $url): bool
    {
        $parts = parse_url($url);
        if($parts === false) return false;

        $scheme = strtolower((string)zget($parts, 'scheme', ''));
        if(!in_array($scheme, array('svn', 'http', 'https', 'file'))) return false;

        if($scheme == 'file') return strpos($url, 'file://') === 0 && !empty(zget($parts, 'path', ''));
        return !empty(zget($parts, 'host', ''));
    }

    /**
     * 检查 Subversion 服务地址可访问性。
     * Check Subversion provider accessibility.
     *
     * @param  string $url
     * @access protected
     * @return bool
     */
    protected function isAccessibleSubversionUrl(string $url): bool
    {
        $parts  = parse_url($url);
        $scheme = strtolower((string)zget($parts, 'scheme', ''));

        if($scheme == 'file') return $this->checkSubversionFilePath((string)zget($parts, 'path', ''));
        if($scheme == 'svn')  return $this->checkSubversionSocket((string)zget($parts, 'host', ''), (int)zget($parts, 'port', 3690));

        return $this->checkSubversionHttpUrl($url);
    }

    /**
     * 检查 Subversion HTTP 地址是否可访问。
     * Check whether the Subversion HTTP url is accessible.
     *
     * @param  string $url
     * @access protected
     * @return bool
     */
    protected function checkSubversionHttpUrl(string $url): bool
    {
        $response = common::http($url, null, array(CURLOPT_NOBODY => true), array(), 'data', 'GET', 5, true, false);
        return is_array($response) && empty($response['errno']) && !empty($response[1]);
    }

    /**
     * 检查 Subversion svn 协议地址是否可访问。
     * Check whether the Subversion svn protocol url is accessible.
     *
     * @param  string $host
     * @param  int    $port
     * @access protected
     * @return bool
     */
    protected function checkSubversionSocket(string $host, int $port = 3690): bool
    {
        $errno   = 0;
        $error   = '';
        $handler = static function(int $level, string $message) use (&$error): bool
        {
            $error = $message;
            return true;
        };

        set_error_handler($handler, E_WARNING | E_NOTICE);
        $connection = fsockopen($host, $port, $errno, $error, 5);
        restore_error_handler();
        if($connection === false)
        {
            if($error) commonModel::$requestErrors[] = $error;
            return false;
        }

        fclose($connection);
        return true;
    }

    /**
     * 检查 Subversion file 地址是否可访问。
     * Check whether the Subversion file url is accessible.
     *
     * @param  string $path
     * @access protected
     * @return bool
     */
    protected function checkSubversionFilePath(string $path): bool
    {
        $path = rawurldecode($path);
        if(file_exists($path) && is_readable($path)) return true;

        commonModel::$requestErrors[] = $path;
        return false;
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

    /**
     * 获取探活请求头。
     * Get auth headers for checking provider connectivity.
     *
     * @param  string $type
     * @param  string $token
     * @access protected
     * @return array
     */
    protected function getCheckHeaders(string $type, string $token): array
    {
        if(empty($token)) return array();

        if($type == 'GitLab') return array('PRIVATE-TOKEN: ' . $token);
        if($type == 'GitHub') return array('Authorization: Bearer ' . $token);
        if($type == 'Jenkins') return array('Authorization: basic ' . $token);

        return array('Authorization: token ' . $token);
    }
}
