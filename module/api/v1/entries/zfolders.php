<?php
/**
 * The root folder entry point for yueku.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class zfoldersEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $this->app->loadApiConfig('zdisk');

        $nodes = array();
        $now   = gmdate("Y-m-d\TH:i:s\Z");
        foreach($this->config->zdisk->root as $code => $node)
        {
            $nodes[] = array(
                'id'           => $code,
                'parentID'     => null,
                'storeID'      => 0,
                'name'         => $node['name'] . ($node['type'] == 'file' ? '.txt' : ''),
                'type'         => $node['type'],
                'size'         => 0,
                'createdTime'  => $now,
                'accessedTime' => $now,
                'editedTime'   => $now,
                'modifiedTime' => $now,
            );
        }

        return $this->send(200, array('nodes' => $nodes));
    }
}
