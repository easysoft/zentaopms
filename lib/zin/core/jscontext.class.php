<?php
declare(strict_types=1);
/**
 * The js context class file of zin lib.
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
 * Class for generating js code with context.
 */
class jsContext extends js
{
    protected ?string $contextName = null;

    public function __construct(mixed $value, null|string|bool $name = null, string|array|js|null ...$codes)
    {
        parent::__construct(...$codes);

        if($name === false)
        {
            $this->contextName = $value;
        }
        else
        {
            if(!is_string($name)) $name = '__C' . (static::$tempIndex++);
            $this->contextName = $name;
            $this->set($name, jsRaw($value));
        }
    }

    public function __call($name, $arguments)
    {
        if(str_ends_with($name, 'If'))
        {
            return parent::__call($name, $arguments);
        }

        return $this->call($name, ...$arguments);
    }

    public function call(string $func, mixed ...$args): self
    {
        return parent::call($this->contextName . '.' . $func, ...$args);
    }

    public function callSelf(mixed ...$args): self
    {
        return parent::call($this->contextName, ...$args);
    }

    public function do(string|array|js|null ...$codes): self
    {
        return $this->with($this->contextName, ...$codes);
    }

    public static int $tempIndex = 0;
}

function jsContext(mixed $value, ?string $name = null, string|array|null $codes = null): jsContext
{
    return new jsContext($value, $name, $codes);
}
