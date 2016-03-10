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
