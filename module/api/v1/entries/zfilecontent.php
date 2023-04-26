<?php
/**
 * The file entry point for yueku.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class zfileContentEntry extends entry
{
    /**
     * GET method.
     *
     * @param  string $fileID
     * @access public
     * @return string
     */
    public function get($fileID)
    {
        ob_end_clean();    

        header("Content-type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Accept-Ranges: bytes");
        // header("Content-Length: " . filesize($filePath));
        header("Content-Disposition: attachment; filename=\"hello.txt\"");

        echo 'hello';
    }
}
