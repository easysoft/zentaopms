<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;

require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpExcel extends baseDelegate
{
    protected static $className = 'PhpOffice\PhpSpreadsheet\Spreadsheet';

    public function __construct()
    {
        $this->instance = new self::$className();
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
        } catch (ReaderException $e) {
            throw $e;
        }
    }

    public function createWriter($type = 'Xlsx')
    {
        try
        {
            return IOFactory::createWriter($this->instance, ucfirst($type));
        } catch (WriterException $e) {
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
        } catch (ReaderException $e) {
            return false;
        }
    }
}
