<?php
/**
 * ZenTaoPHP的前端类。
 * The front class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/front/front.class.php');
/**
 * html类，生成html标签。
 * The html class, to build html tags.
 * 
 * @package framework
 */
class html extends baseHTML
{
    /**
     * 生成超链接。
     * Create tags like <a href="">text</a>
     *
     * @param  string $href      the link url.
     * @param  string $title     the link title.
     * @param  string $misc      other params.
     * @param  string $newline
     * @static
     * @access public
     * @return string
     */
    static public function a($href = '', $title = '', $target = "_self", $misc = '', $newline = true)
    {
        if(empty($target)) $target = '_self';
        if($target != '_self') $misc .= " target='$target'";
        return parent::a($href, $title, $misc, $newline);
    }

    /**
     * 创建提交按钮。
     * Create submit button.
     * 
     * @param  string $label    the label of the button
     * @param  string $class    the class of the button
     * @param  string $misc     other params
     * @static
     * @access public
     * @return string the submit button tag.
     */
    public static function submitButton($label = '', $misc = '', $class = 'btn btn-primary')
    {
        return parent::submitButton($label, $class, $misc);
    }

    public static function commonButton($label = '', $misc = '', $class = 'btn btn-default', $icon = '')
    {
        return parent::commonButton($label, $class, $misc, $icon);
    }

    public static function linkButton($label = '', $link = '', $target = 'self', $misc = '', $class = 'btn btn-default')
    {
        return parent::linkButton($label, $link, $class, $misc, $target);
    }

    /**
     * 创建全选checkbox。
     * Create select buttons include 'selectAll' and 'selectReverse'.
     * 
     * @param  string $scope  the scope of select reverse.
     * @param  bool   $asGroup 
     * @param  string $appendClass 
     * @static
     * @access public
     * @return string
     */
    static public function selectButton($scope = "", $asGroup = true, $appendClass = 'btn')
    {
        global $lang;
        return "<div class='checkbox $appendClass'><label><input type='checkbox' data-scope='$scope' class='rows-selector'> $lang->select</label></div>";
    }
}

/**
 * JS类。
 * JS class.
 * 
 * @package front
 */
class js extends baseJS
{
}

/**
 * css类。
 * css class.
 *
 * @package front
 */
class css extends baseCSS
{
}
