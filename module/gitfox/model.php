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
     * 获取gitfox根据id。
     * Get a gitfox by id.
     *
     * @param  int $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * 获取gitfox列表。
     * Get gitfox list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', object $pager = null): array
    {
        $gitfoxList = $this->loadModel('pipeline')->getList('gitfox', $orderBy, $pager);

        return $gitfoxList;
    }

    /**
     * 获取gitfox id name 键值对。
     * Get gitfox pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        return $this->loadModel('pipeline')->getPairs('gitfox');
    }

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
        $url      = rtrim($url, '/') . '/api/v1/admin/users';
        $header   = array('Authorization: Bearer ' . $token);
        $response = commonModel::http($url, null, array(), $header);

        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        return $users;
    }
}

