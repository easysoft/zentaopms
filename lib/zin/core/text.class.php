<?php
declare(strict_types=1);
/**
 * The text element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'directive.class.php';

class text extends node
{
    public static array $defineProps = array
    (
        'html' => '?bool',
    );

    public function type(): string
    {
        return 'node::' . $this->shortType();
    }

    public function shortType(): string
    {
        return $this->prop('html') ? 'html' : 'text';
    }

    public function render(): string
    {
        if($this->prop('html')) return implode('', $this->children());
        return parent::render();
    }
}

function text(string ...$texts): text
{
    return new text(...$texts);
}

function html(string ...$codes): text
{
    $text = new text(...$codes);
    $text->setProp('html', true);
    return $text;
}
