<?php
declare(strict_types=1);
/**
 * The js helper class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'js.class.php';

/**
 * Class for generating js helper code.
 */
class jsHelper extends js
{
    public function toggleHide(string $selector): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").hide();");
    }

    public function toggleShow(string $selector): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").show();");
    }

    public function toggle(string $selector, string $toggle): self
    {
        $selector = str_replace('"', '\\"', $selector);
        if(!is_string($toggle)) $toggle = json_encode($toggle);
        return $this->appendLine("$(\"{$selector}\").toggle($toggle);");
    }

    public function addClass(string $selector, string $class): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").addClass(\"{$class}\");");
    }

    public function removeClass(string $selector, string $class): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").removeClass(\"{$class}\");");
    }

    public function toggleClass(string $selector, string $class, string $toggle): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").removeClass(\"{$class}\", $toggle);");
    }

    public function setHtml(string $selector, string $html): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").html(", json_encode($html), ");");
    }

    public function setText(string $selector, string $text): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").text(", json_encode($text), ");");
    }

    public function setVal(string $selector, string $value): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").val(", json_encode($value), ");");
    }

    public function setAttr(string $selector, string $name, string $value): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").attr(\"{$name}\", ", json_encode($value), ");");
    }

    public function removeAttr(string $selector, string $name): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").removeAttr(\"{$name}\");");
    }

    public function setProp(string $selector, string $name, string $value): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").prop(\"{$name}\", ", json_encode($value), ");");
    }

    public function removeProp(string $selector, string $name): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").removeProp(\"{$name}\");");
    }

    public function triggerEvent(string $selector, string $event, string $data = ''): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").trigger(\"{$event}\", ", json_encode($data), ");");
    }

    public function onEvent(string $selector, string $event, string $handler): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").on(\"{$event}\", {$handler});");
    }

    public function offEvent(string $selector, string $event, string $handler): self
    {
        $selector = str_replace('"', '\\"', $selector);
        return $this->appendLine("$(\"{$selector}\").off(\"{$event}\", {$handler});");
    }
}

function jsHelper(string|array|js|null ...$codes): jsHelper
{
    return new jsHelper(...$codes);
}
