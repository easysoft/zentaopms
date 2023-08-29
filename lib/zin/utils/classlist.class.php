<?php
declare(strict_types=1);
/**
 * The classlist file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\utils;

/**
 * Manage classname list for html element and widgets
 */
class classlist
{
    /**
     * Store classname list, key => value
     *
     * @access private
     * @var    array
     */
    private array $list = array();

    /**
     * Create classname instance
     *
     * @access public
     * @param array ...$list - A string or a class name list
     */
    public function __construct(/* ...$list */)
    {
        $list = func_get_args();
        if(!empty($list)) $this->set($list);
    }

    /**
     * Convert classnames to string
     *
     * @access public
     * @return string
     */
    public function __toString(): string
    {
        return $this->toStr();
    }

    /**
     * Create classname instance
     *
     * Example:
     *
     *     // Set class names
     *     $classlist = new classlist();
     *     $classlist->set('btn primary rounded');
     *
     *     // Set multiple classnames by string list
     *     $classlist->set(array('btn', 'primary', 'rounded'));
     *
     *     // Set multiple classnames by a mapped array
     *     $classlist->set(array('btn' => true, 'primary' => true, 'rounded' => $isRounded));
     *
     * @access public
     * @param string|array|null $list - A string or a class name list
     * @param bool              $reset
     * @return classlist
     */
    public function set(string|array|null $list, bool $reset = false): classlist
    {
        if(is_string($list)) $list = explode(' ', $list);

        if(is_array($list))
        {
            if($reset) $this->list = array();

            $expectedKey = 0;
            foreach($list as $index => $value)
            {
                if(is_array($value))
                {
                    $this->set($value);
                    continue;
                }

                /* If $index is expected numberic key and the $value is string, then use the $value as the name */
                if($expectedKey === $index && is_string($value))
                {
                    $value = trim($value);
                    if(strlen($value) > 0) $this->list[$value] = true;
                }
                /* If index is string, then set $index as name */
                else if(is_string($index))
                {
                    $index = trim($index);
                    if(strlen($index) === 0) continue;

                    $this->list[$index] = boolval($value);
                }
                $expectedKey++;
            }
        }

        return $this;
    }

    /**
     * Add classnames
     *
     * Example:
     *
     *     $classlist = new classlist();
     *     $classlist->add('btn primary rounded');
     *
     *     // Add multiple classnames by string list
     *     $classlist->add('btn', 'primary', 'rounded');
     *
     * @access public
     * @param array ...$list - classname string joined by space or string array
     * @return classlist
     */
    public function add(/* ...$list */)
    {
        return $this->set(func_get_args());
    }

    /**
     * Remove classnames
     *
     * Example:
     *
     *     $classlist = new classlist('btn primary rounded');
     *     $classlist->remove('btn primary');
     *
     *     // Add multiple classnames by string list
     *     $classlist->remove('btn', 'primary');
     *
     * @access public
     * @param array|string $list - classname string joined by space or string array
     * @return classlist
     */
    public function remove(array|string $list): classlist
    {
        if(is_string($list)) $list = explode(' ', $list);

        foreach($list as $name)
        {
            if(!is_string($name)) continue;
            $name = trim($name);
            if(!strlen($name)) continue;

            $this->list[$name] = false;
        }
        return $this;
    }

    /**
     * Toggle classname
     *
     * Example:
     *
     *     $classlist = new classlist('btn');
     *     $classlist->toggle('btn'); // class list is ""
     *
     *     // Toggle class name by flag
     *     $classlist->toggle('primary', true); // class list is "primary"
     *
     * @access public
     * @param string $name - classname string
     * @return classlist
     */
    public function toggle(string $name, bool|null $toggle = null): classlist
    {
        $name = trim($name);
        if(strlen($name))
        {
            if($toggle === null) $toggle = !$this->has($name);
            $this->list[$name] = $toggle;
        }
        return $this;
    }

    /**
     * Check whether has specific class name
     *
     * Example:
     *
     *     $classlist = new classlist('btn primary rounded');
     *     echo $classlist->has('btn'); // Output true
     *
     *     // Check multiple names
     *     echo $classlist->has('btn primary'); // Output true
     */
    public function has(array|string $list): bool
    {
        if(is_string($list)) $list = explode(' ', $list);

        foreach($list as $name)
        {
            if(!is_string($name)) continue;
            $name = trim($name);
            if(!strlen($name)) continue;

            if(!isset($this->list[$name]) || !$this->list[$name]) return false;
        }
        return true;
    }

    public function clear()
    {
        $this->list = array();
    }

    /**
     * Convert classnames to string
     *
     * @access public
     * @return string
     */
    public function toStr(): string
    {
        $names = array();
        foreach($this->list as $name => $toggle)
        {
            if(!$toggle) continue;

            $name = trim($name);
            if(!strlen($name)) continue;

            $names[] = $name;
        }
        return implode(' ', $names);
    }

    /**
     * Get class names count
     *
     * @access public
     * @return int
     */
    public function count(): int
    {
        return count($this->list);
    }

    public function toJSON(): array
    {
        return $this->list;
    }
}
