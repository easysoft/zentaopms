<?php
declare(strict_types=1);
/**
 * The directive class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'zin.class.php';
require_once __DIR__ . DS . 'context.func.php';

use zin\node;

interface iDirective
{
    public function apply(node $node, string $blockName): void;
}

class directive implements iDirective
{
    public string $type;

    public mixed $data;

    public ?array $options;

    public mixed $parent = null;

    /**
     * Construct a directive object
     * @param  string $type
     * @param  mixed  $data
     * @param  array  $options
     * @access public
     */
    public function __construct(string $type, mixed $data = null, ?array $options = null)
    {
        $this->type    = $type;
        $this->data    = $data;
        $this->options = $options;

        if(!$options || !isset($options['notRenderInGlobal']) || !$options['notRenderInGlobal'])
        {
            renderInGlobal($this);
        }
    }

    public function __debugInfo(): array
    {
        return array(
            'type'    => $this->type,
            'data'    => $this->data,
            'options' => $this->options
        );
    }

    public function apply(node $node, string $blockName): void
    {
        $this->parent = $node;

        $data = $this->data;
        $type = $this->type;

        if($type === 'prop')
        {
            $node->setProp($data);
            return;
        }
        if($type === 'class' || $type === 'style')
        {
            $node->setProp($type, $data);
            return;
        }
        if($type === 'cssVar')
        {
            $node->setProp('--', $data);
            return;
        }
        if($type === 'html')
        {
            $html = new stdClass();
            $html->html = implode("\n", $data);
            $node->addToBlock($blockName, $html);
            return;
        }
        if($type === 'text')
        {
            $node->addToBlock($blockName, $data);
            return;
        }
        if($type === 'block')
        {
            foreach($data as $blockName => $blockChildren)
            {
                $node->addToBlock($blockName, $blockChildren);
            }
        }
    }

    public static function is(mixed $item): bool
    {
        return ($item instanceof directive) || $item instanceof iDirective || (is_object($item) && method_exists($item, 'apply'));
    }
}

function directive($type, $data, $options = null): directive
{
    return new directive($type, $data, $options);
}

function isDirective(mixed $item, ?string $type = null): bool
{
    return directive::is($item);
}
