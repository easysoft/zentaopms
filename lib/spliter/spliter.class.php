<?php
/**
 * The spliter class file of chanzhiEPS.
 *
 * @copyright   Copyright 2013-2014 青岛息壤网络信息有限公司 (QingDao XiRang Network Infomation Co,LTD www.xirangit.com)
 * @license     LGPL
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     spliter
 * @version     $Id$
 * @link        http://www.chanzhi.org
 * @see         http://www.cnblogs.com/chenwenbiao/archive/2011/08/11/2134503.html 
 * @see         http://stackoverflow.com/questions/9361303/can-i-get-the-unicode-value-of-a-character-or-vise-versa-with-php
 */
class spliter
{
    /**
     * Split a utf-8 string into words, computing unicode for every word.
     * 
     * @param  string    $string 
     * @access public
     * @return array
     */
    public function utf8Split($string)
    {
        $string = strtolower($string);
        $i = 0; 
        $length = strlen($string);
        $dict   = array();
        $words  = '';
        $offset = 0;

        while($i <= $length)
        {
            $letter = substr($string, $i, 1);
            $ord    = ord($letter);

            /* The first letter is ascii, try to get a word. */
            if($ord >= 0 && $ord <= 191)
            {
                $i ++;

                if($this->isLetter($letter))
                {
                    $word = $letter;
                    while($i <= $length)
                    {
                        $letter = substr($string, $i, 1);
                        if(!$this->isLetter($letter)) break;

                        $word .= $letter;
                        $i++;
                    }
                    
                    /* Process intigers. */
                    if(is_numeric($word) and (strpos($word, '.') === false)) $word = "|" . $word . "|";
                    $word   = str_pad(strtolower($word), 5, '_');
                    $words .= ' ' . $word;
                }
                continue;
            }

            if($ord >= 192 && $ord <= 223) $offset = 2;
            if($ord >= 224 && $ord <= 239) $offset = 3;
            if($ord >= 240 && $ord <= 247) $offset = 4;
            if($ord >= 248 && $ord <= 251) $offset = 5;
            if($ord >= 252 && $ord <= 253) $offset = 6;

            if($offset >= 2)
            {
                $letter  = substr($string, $i, $offset);
                $unicode = $this->unicode($letter);
                if(strlen($unicode) == 5)
                {
                    $dict[$unicode] = $letter;
                    $words .= ' ' . $unicode;
                }
                else
                {
                    $words .= ' ' . $letter;
                }
                $i += $offset;
            }
        }

        return array('dict' => $dict, 'words' => $words);
    }

    /**
     * Return unicode value for a char. 
     * 
     * @param  string    $c 
     * @access public
     * @return int
     */
    public function unicode($c) 
    {
        if(ord($c{0}) >= 0   && ord($c{0}) <= 127) return  ord($c{0});
        if(ord($c{0}) >= 192 && ord($c{0}) <= 223) return (ord($c{0}) - 192) * 64         + (ord($c{1}) - 128);
        if(ord($c{0}) >= 224 && ord($c{0}) <= 239) return (ord($c{0}) - 224) * 4096       + (ord($c{1}) - 128) * 64       + (ord($c{2}) - 128);
        if(ord($c{0}) >= 240 && ord($c{0}) <= 247) return (ord($c{0}) - 240) * 262144     + (ord($c{1}) - 128) * 4096     + (ord($c{2}) - 128) * 64     + (ord($c{3}) - 128);
        if(ord($c{0}) >= 248 && ord($c{0}) <= 251) return (ord($c{0}) - 248) * 16777216   + (ord($c{1}) - 128) * 262144   + (ord($c{2}) - 128) * 4096   + (ord($c{3}) - 128) * 64   + (ord($c{4}) - 128);
        if(ord($c{0}) >= 252 && ord($c{0}) <= 253) return (ord($c{0}) - 252) * 1073741824 + (ord($c{1}) - 128) * 16777216 + (ord($c{2}) - 128) * 262144 + (ord($c{3}) - 128) * 4096 + (ord($c{4}) - 128) * 64 + (ord($c{5}) - 128);

        if(ord($c{0}) >= 254 && ord($c{0}) <= 255) return false;
        return false;
    }

    /**
     * Judge a char is Letter or not.
     * 
     * @param  string    $letter 
     * @access public
     * @return bool
     */
    public function isLetter($letter)
    {
        $ord = ord($letter);
        if($ord >= ord('a') and $ord <= ord('z')) return true;
        if($ord >= ord('A') and $ord <= ord('Z')) return true;
        if($ord >= ord(0)   and $ord <= ord(9))   return true;
        if($letter and strpos('._/->:<?&', $letter) !== false) return true;
        return false;
    }
}
