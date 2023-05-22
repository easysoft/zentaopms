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
}
