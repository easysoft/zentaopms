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
}
