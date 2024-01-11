<?php
declare(strict_types=1);
/**
 * The zen file of misc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
class miscZen extends misc
{
    /**
     * 打印 hello world。
     * print hello world.
     *
     * @access public
     * @return string
     */
    public function hello(): string
    {
        return 'hello world from hello()<br />';
    }

    /**
     * 获取缓存文件。
     * Get cache files.
     *
     * @param  string    $directory
     * @access protected
     * @return void
     */
    protected function cleanCachaFiles(string $directory): void
    {
        $files = glob($directory . DS . '*.cache');

        foreach($files as $file) $this->deleteExpiredFile($file);

        $subdirectories = glob($directory . DS . '*', GLOB_ONLYDIR);

        foreach($subdirectories as $subdirectory) $this->cleanCachaFiles($subdirectory);
    }

    /**
     * 删除过期文件。
     * Delete expired file.
     *
     * @param  string    $file
     * @access protected
     * @return bool
     */
    protected function deleteExpiredFile(string $file): bool
    {
        $content = file_get_contents($file);
        $content = unserialize($content);

        if(is_null($content['time'])) return false;
        if(time() > $content['time']) return unlink($file);

        return false;
    }
}
