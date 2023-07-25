<?php
declare(strict_types=1);
/**
 * The tao file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

class fileTao extends fileModel
{
    /**
     * 保存一条文件数据。
     * Save one file data.
     *
     * @param  array     $file
     * @param  string    $strSkipFields
     * @access protected
     * @return int
     */
    protected function saveFile(array $file, string $strSkipFields = ''): int|false
    {
        if(empty($file)) return false;

        $this->dao->insert(TABLE_FILE)->data($file, $strSkipFields)->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * 转换文件大小单位。
     * Convert file size.
     *
     * @param  int       $fileSize
     * @access protected
     * @return string
     */
    protected function convertFileSize(int $fileSize): string
    {
        if($fileSize < 1024) return $fileSize . 'B';
        if($fileSize < 1024 * 1024) return round($fileSize / 1024, 2) . 'K';
        if($fileSize < 1024 * 1024 * 1024) return (string)round($fileSize / (1024 * 1024 * 1024), 2);
    }
}
