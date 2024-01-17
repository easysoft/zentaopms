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

class directive
{
    public string $type;

    public mixed $data;

    public ?array $options;

    public ?wg $parent = null;

    /**
     * Construct a directive object
     * @param  string $type
     * @param  mixed  $data
     * @param  array  $options
     * @access public
     */
    public function __construct(string $type, mixed $data, ?array $options = null)
    {
        $this->type    = $type;
        $this->data    = $data;
        $this->options = $options;

        zin::renderInGlobal($this);
    }

    public function __debugInfo(): array
    {
        return array(
            'type'    => $this->type,
            'data'    => $this->data,
            'options' => $this->options
        );
    }

    public function applyToWg(wg &$wg, string $blockName): void
    {
        $this->parent = $wg;

        $data = $this->data;
        $type = $this->type;

        if($type === 'prop')
        {
            $wg->setProp($data);
            return;
        }
        if($type === 'class' || $type === 'style')
        {
            $wg->setProp($type, $data);
            return;
        }
        if($type === 'cssVar')
        {
            $wg->setProp('--', $data);
            return;
        }
        if($type === 'html')
        {
            $wg->addToBlock($blockName, $this);
            return;
        }
        if($type === 'text')
        {
            $wg->addToBlock($blockName, htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false));
            return;
        }
        if($type === 'block')
        {
            foreach($data as $blockName => $blockChildren)
            {
                $wg->add($blockChildren, $blockName);
            }
        }
    }

    public static function is(mixed $item, ?string $type = null): bool
    {
        return $item instanceof directive && ($type === null || $item->type === $type);
    }
}

function directive($type, $data, $options = null): directive
{
    return new directive($type, $data, $options);
}

function isDirective(mixed $item, ?string $type = null): bool
{
    return directive::is($item, $type);
}
