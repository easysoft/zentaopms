<?php
declare(strict_types=1);
/**
 * The tao file of measurement module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guanxiying <guanxiying@easycorp.ltd>
 * @package     measurement
 * @link        https://www.zentao.net
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
        $code    = sprintf($this->config->measurement->phpTemplate, $code);
        $result  = file_put_contents($phpFile, $code);
        return $result ? $phpFile : false;
    }
}
