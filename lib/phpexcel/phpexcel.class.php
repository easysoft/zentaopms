<?php
/**
 * ZenTaoPMS - Open-source project management system.
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *
 * A third-party license is embedded for some of the code in this file:
 * The use of the source code of this file is also subject to the terms
 * and consitions of the license of "PhpSpreadsheet" (LGPL, see
 * </lib/vendor/phpoffice/phpspreadsheet/LICENSE>).
 */

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;

require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpExcel extends baseDelegate
{
    protected static $className = 'PhpOffice\PhpSpreadsheet\Spreadsheet';

    public function __construct()
    {
        $this->instance = new static::$className();
    }

    public static function load($file)
    {
        return IOFactory::load($file);
    }

    public function createReader($type = 'Xlsx')
    {
        try
        {
            return IOFactory::createReader($type);
        }
        catch (ReaderException $e)
        {
            throw $e;
        }
    }

    public function createWriter($type = 'Xlsx')
    {
        try
        {
            return IOFactory::createWriter($this->instance, ucfirst($type));
        }
        catch (WriterException $e)
        {
            throw $e;
        }
    }

    public static function canRead($file)
    {
        try
        {
            $fileType = IOFactory::identify($file);
            $reader   = IOFactory::createReader($fileType);
            return $fileType == 'Xls' || $fileType == 'Xlsx';
        }
        catch (ReaderException $e)
        {
            return false;
        }
    }
}
