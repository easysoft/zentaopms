<?php
/**
 * ZenTaoPHP的验证和过滤类。
 * The validater and fixer class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/filter/filter.class.php');
/**
 * validater类，检查数据是否符合规则。
 * The validater class, checking data by rules.
 *
 * @package framework
 */
class validater extends baseValidater
{
    /**
     * 检查文件名。
     * Check file name.
     *
     * @param string $var
     * @static
     * @access public
     * @return bool
     */
    public static function checkFileName($var)
    {
        return !preg_match('/>+|<+/', $var);
    }
}

/**
 * fixer类，处理数据。
 * fixer class, to fix data types.
 *
 * @package framework
 */
class fixer extends baseFixer
{
    /**
     * 过滤Emoji表情。
     * Filter Emoji.
     *
     * @param  string $value
     * @access public
     * @return object
     */
    public function filterEmoji($value)
    {
        if(is_object($value) or is_array($value))
        {
            foreach($value as $subValue)
            {
                $subValue = $this->filterEmoji($subValue);
            }
        }
        else
        {
            $value = preg_replace_callback('/./u', function (array $match)
            {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            }, $value);
        }

        return $value;
    }

    /**
     * 对工作流配置的日期格式字段赋予NULL默认值。
     * Assign a default value of NULL to the date format field of the workflow configuration.
     *
     * @param  string $fieldName
     * @access public
     * @return mixed
     */
    public function get(string $fields = ''): mixed
    {
        global $config, $app;

        if($config->edition != 'open' && empty($app->installing) && (!defined('RUN_MODE') || !in_array(RUN_MODE, array('api', 'test'))))
        {
            $moduleName = $app->getModuleName();
            $methodName = $app->getMethodName();

            $flow = $app->control->loadModel('workflow')->getByModule($moduleName);
            if(!$flow) return parent::get($fields);

            $action = $app->control->loadModel('workflowaction')->getByModuleAndAction($flow->module, $methodName);
            if(!$action || $action->extensionType != 'extend') return parent::get($fields);

            $fieldList = $app->control->workflowaction->getPageFields($flow->module, $action->action);
            $layouts   = $app->control->loadModel('workflowlayout')->getFields($moduleName, $methodName);
            if($layouts)
            {
                foreach($fieldList as $key => $field)
                {
                    if($field->buildin || !$field->show || !isset($layouts[$field->field])) continue;

                    if($field->control == 'date' || $field->control == 'datetime')
                    {
                        if(empty($this->data->{$field->field})) $this->data->{$field->field} = NULL;
                    }
                }
            }
        }
        return parent::get($fields);
    }
}
