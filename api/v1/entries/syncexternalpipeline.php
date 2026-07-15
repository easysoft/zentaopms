<?php
/**
 * 同步外部流水线执行状态。
 * sync external pipeline.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class syncexternalpipelineEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? $header['Authorization'] : '';
        $entry  = $this->loadModel('entry')->getByCode('gitfox');

        if(empty($token) || empty($entry->key) || $token != $entry->key)
        {
            return $this->sendError(401, 'Unauthorized');
        }

        $result = $this->loadModel('pipeline')->syncExternalPipeline();

        return $this->send(200, $result ? 'success' : 'fail');
    }
}
