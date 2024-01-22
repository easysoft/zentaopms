<?php
declare(strict_types=1);
/**
 * The js callback class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'wg.func.php';
require_once __DIR__ . DS . 'jshelper.class.php';

/**
 * JS callback class.
 */
class jsCallback extends jsHelper
{
    /**
     * The function name.
     * 函数名称。
     *
     * @access public
     * @var string|null
     */
    public ?string $funcName = null;

    /**
     * The function arguments.
     * 函数参数列表。
     *
     * @access public
     * @var array
     */
    public array $funcArgs = array();

    /**
     * Is arrow function.
     * 是否为箭头函数。
     *
     * @access public
     * @var string
     */
    public bool $isArrowFunc = false;

    /**
     * 构造函数。
     *
     * @access public
     * @param string ...$args The function arguments. 函数参数列表。
     */
    public function __construct(string ...$args)
    {
        parent::__construct();

        $this->funcArgs = $args;
    }

    /**
     * Set the function name.
     * 设置函数名称。
     *
     * @access public
     * @param string|null $name The function name. 函数名称。
     * @return self
     */
    public function name(?string $name): self
    {
        $this->funcName = $name;
        return $this;
    }

    /**
     * Set the function arguments.
     * 设置函数参数名称列表。
     *
     * @access public
     * @param string ...$args The function arguments. 函数参数名称列表。
     * @return self
     */
    public function args(string ...$args): self
    {
        $this->funcArgs = $args;
        return $this;
    }

    /**
     * Set whether to use arrow functions.
     * 设置是否使用箭头函数。
     *
     * @access public
     * @param bool $arrow Is arrow function. 是否使用箭头函数。
     * @return self
     */
    public function arrow(bool $arrow = true): self
    {
        $this->isArrowFunc = $arrow;
        return $this;
    }

    /**
     * Convert to JS code.
     * 转换为 JS 代码。
     *
     * @access public
     * @param string $joiner The joiner. 连接符。
     * @return string
     */
    public function toJS(string $joiner = "\n"): string
    {
        $name     = $this->funcName;
        $args     = $this->funcArgs;
        $codes    = array();
        $argsPart = implode(',', $args);

        if($this->isArrowFunc)
        {
            $codes[] = "($argsPart)=>{";
        }
        else
        {
            $codes[] = "function" . (empty($name) ? '' : " $name") . "($argsPart){";
        }

        $codes[] = $this->buildBody($joiner);
        $codes[] = '}';
        return implode($joiner, $codes);
    }

    /**
     * Build the body.
     * 构建函数体。
     *
     * @access function
     * @param string $joiner The joiner. 连接符。
     * @return string
     */
    public function buildBody(string $joiner = "\n"): string
    {
        return parent::toJS($joiner);
    }

    /**
     * Add return statement.
     * 添加 return 语句。
     *
     * @access public
     * @param mixed $data The return data. 返回数据。
     * @return self
     */
    public function returnData(mixed $data = null): self
    {
        return $this->appendLine('return', static::json($data));
    }

    /**
     * Convert to variable.
     * 转换为变量。
     *
     * @access public
     * @param string|null $name The variable name. 变量名称。
     * @param bool $const Is const variable. 是否为常量。
     * @return string
     * @throws \ErrorException
     */
    public function toVar(?string $name = null, bool $const = false): string
    {
        if(is_null($name)) $name = $this->funcName;
        if(is_null($name))
        {
            trigger_error('The function name is required when converting to variable.', E_USER_ERROR);
        }

        $codes = array();
        $codes[] = $const ? 'const' : 'var';
        $codes[] = $name;
        $codes[] = '=';
        $codes[] = $this->toJS();
        $codes[] = ';';

        return implode(' ', $codes);
    }

    /**
     * Convert to const variable.
     * 转换为常量变量。
     *
     * @access public
     * @param string|null $name The variable name. 变量名称。
     * @return string
     */
    public function toConst(?string $name = null): string
    {
        return $this->toVar($name, true);
    }

    /**
     * Define callback with magic method.
     * 通过魔术方法定义回调函数。
     *
     * @access public
     * @param string $name The function name. 函数名称。
     * @param array $args The function arguments. 函数参数列表。
     * @return self
     * @static
     */
    public static function __callStatic(string $name, array $args)
    {
        $callback = new jsCallback(...$args);
        return $callback->name($name);
    }
}

/**
 * Create a js callback.
 *
 * @param string ...$args The function arguments. 函数参数列表。
 * @return jsCallback
 */
function jsCallback(string ...$args): jsCallback
{
    return new jsCallback(...$args);
}
