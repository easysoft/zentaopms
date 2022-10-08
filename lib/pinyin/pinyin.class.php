<?php

/*
 * This file is part of the overtrue/pinyin.
 *
 * (c) 2016 overtrue <i@overtrue.me>
 */
class pinyin
{
    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->segments = array();
        $segment = dirname(__FILE__) . '/data/words';
        if(file_exists($segment)) $this->segments = include $segment;
    }

    /**
     * Convert string to pinyin.
     *
     * @param string $string
     * @param string $option
     *
     * @return array
     */
    public function convert($string)
    {
        $pinyin = $this->romanize($string);
        $split  = array_filter(preg_split('/[^a-z]+/iu', $pinyin));
        return array_values($split);
    }


    /**
     * Preprocess.
     *
     * @param string $string
     *
     * @return string
     */
    public function prepare($string)
    {
        return preg_replace_callback('/[a-z0-9_-]+/i', array($this, 'prepareCallback'), $string);
    }

    /**
     * Convert Chinese to pinyin.
     *
     * @param string $string
     * @param bool   $isName
     *
     * @return string
     */
    public function romanize($string)
    {
        $string = $this->prepare($string);
        $string = strtr($string, $this->segments);
        return $string;
    }

    /**
     * The callback for prepare method.
     *
     * @param  array    $matches
     * @access public
     * @return string
     */
    public function prepareCallback($matches)
    {
        return "\t" . $matches[0];
    }
}
