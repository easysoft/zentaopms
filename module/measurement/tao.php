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
     * Save new php meas file.
     *
     * @param  string $code
     * @param  string $measCode
     * @access protected
     * @return bool
     */
    protected function savePhpMeasFile(string $code, string $measCode): bool
    {
        $measCode = strtolower($measCode);
        $measFile = $this->app->getExtensionRoot() . 'custom' . DS . 'meas' .DS . 'ext' . DS . 'model' . DS . $measCode . '.php';
        $result   = file_put_contents($measFile, $code);
        return $result !== false;
    }

    /**
     * Test if one php meas can be used.
     *
     * @param  string $measCode
     * @param  array  $params
     * @access protected
     * @return mixed
     */
    protected function testPhpMeas($measCode, $params = array()): mixed
    {
        $meas = $this->loadModel('meas');
        return call_user_func_array(array($meas, $measCode), $params);
    }

    /**
     * Save php meas code to one tmp file.
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
