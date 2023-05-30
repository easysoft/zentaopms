<?php
declare(strict_types=1);
/**
 * The tao file of measurement module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guanxiying <guanxiying@easycorp.ltd>
 * @package     measurement
 * @link        http://www.zentao.net
 */
class measurementTao extends measurementModel
{
    /**
     * Parsing php function.
     *
     * @param  string $php
     * @access public
     * @return object|false
     */
    public function parsePostFunction(string $measCode, string $code): object|false
    {
        $phpFile = $this->saveTmpPhpFile($measCode, $code);
        if(!$phpFile) return false;

        include_once($phpFile);
        $class   = new ReflectionClass('meas');
        $methods = $class->getMethods();

        $methodInfo = new stdclass();

        if(empty($methods)) return false;
        $method     = current($methods);
        $methodInfo->methodName = $method->name;

        $methodInfo->methodCode = $this->pickPhpFunction($phpFile, $methodInfo->methodName);
        return $methodInfo;
    }

    /**
     * Append new php function to meas class.
     *
     * @param  int    $code
     * @param  int    $measFile
     * @access public
     * @return viod
     */
    public function appendPhpFunction(string $code, string $measFile): string|false
    {
        $activeCode = $this->getActivePhpCode();
        $activeCode .= $code;
        $measCode = sprintf($this->config->meas);
    }

    /**
     * Get active php code.
     *
     * @access public
     * @return string
     */
    public function getActivePhpCode(): string
    {
        $meas = $this->dao->select('configure')->from(TABLE_BASICMEAS)->where('engine')->eq('php')->fetchAll();
        return implode(PHP_EOL, $meas);
    }

    /**
     * Save tmp php file.
     *
     * @param  string $measCode
     * @param  string $code
     * @access public
     * @return string|false
     */
    public function saveTmpPhpFile(string $measCode, string $code): string|false
    {
        $phpFile = $this->app->getTmpRoot() . DS . 'meas' . DS . $measCode . '.php';
        $result  = file_put_contents($phpFile, $code);
        return $result ? $phpFile : false;
    }

    /**
     * Pick function code.
     *
     * @param  array  $option
     * @param  string $option[0] file
     * @param  string $option[1] function
     * @access public
     * @return string
     */
    public function pickPhpFunction($file, $method): string
    {
        $position = $this->getFuncPosition($file, 'meas', $method);
        $code     = $this->getFileSlice($file, $position->startLine, $position->endLine);
        return $code;
    }

    /**
     * Get function's first line and last line.
     *
     * @param  string  $file
     * @param  string  $class
     * @param  string  $function
     * @return object
     */
    private function getFuncPosition($file, $class, $method)
    {
        if(!class_exists($class)) include $file;

        $reflection = new ReflectionMethod($class, $method);

        $position = new stdclass;
        $position->startLine = $reflection->getStartLine();
        $position->endLine   = $reflection->getEndLine();
        return $position;
    }

    /**
     * Extract a slice of the file.
     *
     * @param  string $file
     * @param  int $start
     * @param  int $end
     * @access public
     * @return string
     */
    public function getFileSlice(string $file, int $start, int $end): string
    {
        if(!is_file($file)) return 'file error';
        if($end < $start) return '';
        $lines = file($file);
        $start = $start - 1;
        $lines = array_slice($lines, $start, $end - $start);
        return implode('', $lines);
    }
}
