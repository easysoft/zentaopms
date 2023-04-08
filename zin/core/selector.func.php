<?php
/**
 * The selector helpers file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

/**
 * Parse wg selector
 * @param string|object $selector
 * @return object|null
 */
function parseWgSelector($selector)
{
    if(is_object($selector)) return $selector;

    $selector = trim($selector);
    $len      = strlen($selector);

    if($len < 1) return NULL;

    $result = ['class' => [], 'id' => NULL, 'tag' => NULL, 'inner' => false, 'name' => $selector, 'first' => false, 'selector' => $selector];
    if(str_contains($selector, '/'))
    {
        $parts          = explode('/', $selector, 2);
        $result['name'] = $parts[0];
        $selector       = $parts[1];
        $len            = strlen($selector);
    }
    $selector = str_replace('> *', '>*', $selector);
    if(str_ends_with($selector, '>*'))
    {
        $result['inner'] = true;
        $selector = substr($selector, 0, strlen($selector) - 2);
        $len      = strlen($selector);
    }

    $type    = 'tag';
    $current = '';
    for($i = 0; $i < $len; $i++)
    {
        $c = $selector[$i];
        $t = '';

        if($c === '#')     $t = 'id';
        elseif($c === '.') $t = 'class';
        elseif($c === ':') $t = 'flag';

        if(empty($t))
        {
            $current .= $c;
        }
        else
        {
            if(!empty($current))
            {
                if($type === 'class')    $result[$type][]  = $current;
                elseif($type === 'flag') $result[$current] = true;
                else                     $result[$type]    = $current;
            }
            $current = '';
            $type    = $t;
        }
    }
    if(!empty($current))
    {
        if($type === 'class')    $result[$type][]  = $current;
        elseif($type === 'flag') $result[$current] = true;
        else                     $result[$type]    = $current;
    }

    if(empty($result['class'])) $result['class'] = NULL;

    return (object)$result;
}

/**
 * Parse wg selectors
 * @param array|string|object $selectors
 * @return array
 */
function parseWgSelectors($selectors)
{
    if(is_object($selectors)) return [$selectors];
    if(is_string($selectors)) $selectors = explode(',', trim($selectors));
    $results = [];
    foreach($selectors as $selector)
    {
        $selector = parseWgSelector($selector);
        if(is_object($selector)) $results[] = $selector;
    }
    return $results;
}

function stringifyWgSelectors($selector)
{
    if(empty($selector)) return '';
    if(is_array($selector))
    {
        $result = [];
        foreach($selector as $s) $result[] = stringifyWgSelectors($s);
        return implode(',', $result);
    }

    $result = '';
    if(!empty($selector->name) && $selector->name !== $selector->selector) $result .= $selector->name . '/';
    if(!empty($selector->tag))   $result .= $selector->tag;
    if(!empty($selector->id))    $result .= '#' . $selector->id;
    if(!empty($selector->class)) $result .= '.' . implode('.', $selector->class);
    if(!empty($selector->first)) $result .= ':first';
    if($selector->inner)         $result .= '>*';
    return $result;
}
