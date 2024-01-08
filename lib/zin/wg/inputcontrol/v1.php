<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

class inputControl extends wg
{
    protected static array $defineProps = array(
        'prefix?: mixed',
        'suffix?: mixed',
        'prefixWidth?: string|int',
        'suffixWidth?: string|int',
        'class?: string'
    );

    protected static array $defineBlocks = array(
        'prefix' => array(),
        'suffix' => array()
    );

    protected function build(): wg
    {
        list($prefix, $suffix, $prefixWidth, $suffixWidth, $class) = $this->prop(array('prefix', 'suffix', 'prefixWidth', 'suffixWidth', 'class'));

        if(empty($prefix)) $prefix = $this->block('prefix');
        if(empty($suffix)) $suffix = $this->block('suffix');

        $class = "input-control {$class}";
        $vars  = array();
        if(!empty($prefix))
        {
            if(is_numeric($prefixWidth))
            {
                $vars['input-control-prefix'] = $prefixWidth . 'px';
                $class .= ' has-prefix';
            }
            elseif(!empty($prefixWidth))
            {
                $class .= " has-prefix-$prefixWidth";
            }
            else
            {
                $class .= ' has-prefix';
            }
        }
        if(!empty($suffix))
        {
            if(is_numeric($suffixWidth))
            {
                $vars['input-control-suffix'] = $suffixWidth . 'px';
                $class .= ' has-suffix';
            }
            elseif(!empty($suffixWidth))
            {
                $class .= " has-suffix-$suffixWidth";
            }
            else
            {
                $class .= ' has-suffix';
            }
        }

        return div
        (
            setClass($class),
            empty($vars) ? null : setCssVar($vars),
            $this->children(),
            empty($prefix) ? null : div(setClass('input-control-prefix'), $prefix),
            empty($suffix) ? null : div(setClass('input-control-suffix'), $suffix)
        );
    }
}
