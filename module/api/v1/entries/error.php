<?php
/**
 * The error entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class errorEntry extends entry
{
    /**
     * 404 Not Found.
     *
     * @access public
     * @return string
     */
    public function notFound()
    {
        return $this->send(404, array('error' => 'not found'));
    }
}
