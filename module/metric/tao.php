<?php
declare(strict_types=1);
/**
 * The tao file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */

class metricTao extends metricModel
{
    /**
     * 获取度量项计算文件的根目录。
     * Get root of metric calculator.
     *
     * @access protected
     * @return string
     */
    protected function getCalcRoot()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc' . DS;
    }

    /**
     * 获取用户自定义的度量项计算文件的根目录。
     * Get root of custom metric calculator.
     *
     * @access protected
     * @return string
     */
    protected function getCustomCalcRoot()
    {
        return $this->app->getTmpRoot() . 'metric' .DS;
    }

    /**
     * 获取数据集文件的路径
     * Get path of calculator data set.
     *
     * @access protected
     * @return string
     */
    protected function getDatasetPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'dataset.php';
    }

    /**
     * 获取度量项基类文件的路径。
     * Get path of base calculator class.
     *
     * @access protected
     * @return string
     */
    protected function getBaseCalcPath()
    {
        return $this->app->getModuleRoot() . 'metric' . DS . 'calc.class.php';
    }

    /**
     * 获取自定义的度量项计算文件的路径。
     * Get path of custom calculator file.
     *
     * @access protected
     * @return string
     */
    protected function getCustomCalcFile($code)
    {
        return $this->getCustomCalcRoot() . $code . '.php';
    }

    /**
     * 请求度量项数据列表。
     * Fetch metric list.
     *
     * @param  string    $scope
     * @param  string    $stage
     * @param  string    $object
     * @param  string    $purpose
     * @param  string    $query
     * @param  stirng    $sort
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function fetchMetrics($scope, $stage = 'all', $object = '', $purpose = '', $query = '', $sort = 'id_desc', $pager = null)
    {
        $metrics = $this->dao->select('*')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($stage != 'all')->andWhere('stage')->eq($stage)->fi()
            ->beginIF(!empty($object))->andWhere('object')->eq($object)->fi()
            ->beginIF(!empty($purpose))->andWhere('purpose')->eq($purpose)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')
            ->beginIF($sort)->orderBy($sort)->fi()
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll();

        return $metrics;
    }

    /**
     * 请求模块数据。
     * Fetch module data.
     *
     * @param string  $scope
     * @access protected
     * @return void
     */
    protected function fetchModules($scope)
    {
        return $this->dao->select('object, purpose')->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')
            ->groupBy('object, purpose')
            ->fetchAll();
    }

    /**
     * 更新度量项。
     * Updata metric.
     *
     * @param  int       $metricID
     * @param  object    $data
     * @access protected
     * @return void
     */
    protected function updateMetric(int $metricID, object $data)
    {
        $this->dao->update(TABLE_METRIC)->data($data)
            ->where('id')->eq($metricID)
            ->exec();
    }

    /**
     * 通过反射获取类的函数列表。
     * Get method name list of class by reflection.
     *
     * @param  string $file
     * @param  string $className
     * @access public
     * @return array
     */
    protected function getMethodNameList($className)
    {
        $classReflection = new ReflectionClass($className);
        $methods = $classReflection->getMethods();

        $methodNameList = array();
        foreach($methods as $index => $reflectionMethod) $methodNameList[$index] = $reflectionMethod->name;

        return $methodNameList;
    }

    /**
     * 获取旧度量项的SQL函数名。
     * Get sql function name of a old metric.
     *
     * @param  object $measurement
     * @access protected
     * @return string
     */
    protected function getSqlFunctionName($measurement)
    {
        if(!$measurement) return '';
        return strtolower("qc_{$measurement->code}");
    }

    /**
     * 解析SQL函数。
     * Parsing SQL function.
     *
     * @param  string $sql
     * @access protected
     * @return string
     */
    protected function parseSqlFunction($sql)
    {
        $pattern = "/create\s+function\s+`{0,1}([\$,a-z,A-z,_,0-9,\(,|)]+`{0,1})\(+/Ui";
        preg_match_all($pattern, $sql, $matches);

        if(empty($matches[1][0])) return null;
        return trim($matches[1][0], '`');
    }
}
