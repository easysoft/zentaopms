<?php
declare(strict_types=1);
/**
 * The model file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfoxModel extends model
{
    /**
     * 检查token。
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return object|array|null|false
     */
    public function checkTokenAccess(string $url = '', string $token = ''): object|array|null|false
    {
        $apiRoot  = rtrim($url, '/') . '/api/v4%s' . "?private_token={$token}";
        $url      = sprintf($apiRoot, "/users") . "&per_page=5&active=true";
        $response = commonModel::http($url);
        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        $apiRoot .= '&sudo=' . $users[0]->id;
        return $this->apiGet($apiRoot, '/user');
    }
}

