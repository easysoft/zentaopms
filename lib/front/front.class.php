<?php
/**
 * ZenTaoPHP的前端类。
 * The front class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

helper::import(dirname(dirname(__FILE__)) . '/base/front/front.class.php');

/**
 * html类，生成html标签。
 * The html class, to build html tags.
 *
 * @package framework
 */
class html extends baseHTML
{
    /**
     * 生成超链接。
     * Create tags like <a href="">text</a>
     *
     * @param  string $href      the link url.
     * @param  string $title     the link title.
     * @param  string $misc      other params.
     * @param  string $newline
     * @static
     * @access public
     * @return string
     */
    static public function a($href = '', $title = '', $target = "_self", $misc = '', $newline = true)
    {
        if(empty($target)) $target = '_self';
        if($target != '_self')  $misc .= " target='$target'";
        if($target == '_blank') $misc .= " rel='noopener noreferrer'";
        if(strpos($misc, 'disabled')) $href = '#';
        return parent::a($href, $title, $misc, $newline);
    }

    /**
     * 生成input输入标签。
     * Create tags like "<input type='text' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function input($name, $value = "", $attrib = "", $autocomplete = false)
    {
        $id = "id='$name'";
        $id = str_replace(array('[', ']'), "", $id);
        if(strpos($attrib, 'id=') !== false) $id = '';
        if(is_null($value)) $value = '';
        $value = str_replace("'", '&#039;', $value);
        $autocomplete = $autocomplete ? 'autocomplete="on"' : 'autocomplete="off"';
        return "<input type='text' name='$name' {$id} value='$value' $attrib $autocomplete />\n";
    }

    /**
     * 生成多选按钮。
     * Create tags like "<input type='checkbox' />"
     *
     * @param  string $name      the name of the checkbox tag.
     * @param  array  $options   the array to create checkbox tag from.
     * @param  string $checked   the value to checked by default, can be item1,item2
     * @param  string $attrib    other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function checkbox($name, $options, $checked = "", $attrib = "", $type = 'block')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        if(is_array($checked)) $checked = implode(',', $checked);
        $string  = '';
        $checked = ",$checked,";
        $isBlock = $type == 'block';

        foreach($options as $key => $value)
        {
            if($isBlock) $string .= "<div class='checkbox-primary'>";
            else $string .= "<div class='checkbox-primary checkbox-inline'>";
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= (strpos($checked, ",$key,") !== false) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' title='{$value}'/> ";
            $string .= "<label for='$name$key'>" . $value . '</label></div>';
        }
        return $string;
    }

    /**
     * 创建提交按钮。
     * Create submit button.
     *
     * @param  string $label    the label of the button
     * @param  string $class    the class of the button
     * @param  string $misc     other params
     * @static
     * @access public
     * @return string the submit button tag.
     */
    public static function submitButton($label = '', $misc = '', $class = 'btn btn-wide btn-primary')
    {
        return parent::submitButton($label, $class, $misc);
    }

    public static function commonButton($label = '', $misc = '', $class = 'btn', $icon = '')
    {
        return parent::commonButton($label, $class, $misc, $icon);
    }

    public static function linkButton($label = '', $link = '', $target = 'self', $misc = '', $class = 'btn')
    {
        return parent::linkButton($label, $link, $class, $misc, $target);
    }

    /**
     * 创建全选checkbox。
     * Create select buttons include 'selectAll' and 'selectReverse'.
     *
     * @param  string $scope  the scope of select reverse.
     * @param  bool   $asGroup
     * @param  string $appendClass
     * @static
     * @access public
     * @return string
     */
    static public function selectButton($scope = "", $asGroup = true, $appendClass = 'btn')
    {
        global $lang;
        return "<div class='checkbox $appendClass'><label><input type='checkbox' data-scope='$scope' class='rows-selector'> $lang->select</label></div>";
    }

    /**
     * 生成select标签。
     * Create tags like "<select><option></option></select>"
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $options       the array to create select tag from.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @param  string $append        adjust if add options[$selectedItems].
     * @static
     * @access public
     * @return string
     */
    static public function select($name = '', $options = array(), $selectedItems = "", $attrib = "", $append = false)
    {
        $options = (array)($options);
        if($append and !isset($options[$selectedItems])) $options[$selectedItems] = $selectedItems;

        /* The begin. */
        $id = $name;
        if(strpos($name, '[') !== false) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $id = "id='{$id}'";
        if(strpos($attrib, 'id=') !== false) $id = '';

        global $config;
        $convertedPinYin = (empty($config->isINT) and class_exists('common')) ? common::convert2Pinyin($options) : array();
        if(count($options) >= $config->maxCount or isset($config->moreLinks[$name]))
        {
            if(strpos($attrib, 'chosen') !== false)
            {
                $attrib = str_replace('chosen', 'picker-select', $attrib);
                $attrib = preg_replace('/data-drop[-_]?direction=([\'"]?)down([\'"]?)/i', 'data-drop-direction=$1bottom$2', $attrib);
                $attrib = preg_replace('/data-drop[-_]?direction=([\'"]?)up([\'"]?)/i', 'data-drop-direction=$1top$2', $attrib);
            }
            if(isset($config->moreLinks[$name]))
            {
                $link = $config->moreLinks[$name];
                $attrib .= " data-pickertype='remote' data-pickerremote='" . $link . "'";
            }
        }

        $string = "<select name='$name' {$id} $attrib>\n";

        /* The options. */
        if(is_array($selectedItems)) $selectedItems = implode(',', $selectedItems);
        $selectedItems   = ",$selectedItems,";
        foreach($options as $key => $value)
        {
            $optionPinyin = zget($convertedPinYin, $value, '');
            $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
            $string  .= "<option value='$key'$selected title='{$value}' data-keys='{$optionPinyin}'>$value</option>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * Create input tag that type is number.
     *
     * @param  string $name
     * @param  string $value
     * @param  string $attrib
     * @static
     * @access public
     * @return string
     */
    static public function number($name, $value = '', $attrib = '')
    {
        $id = "id='$name'";
        $id = str_replace(array('[', ']'), "", $id);
        if(strpos($attrib, 'id=') !== false) $id = '';
        $value = str_replace("'", '&#039;', $value);
        return "<input type='number' name='$name' {$id} value='$value' $attrib />\n";
    }

    /**
     * Convert a string to a uni code
     *
     * @param string $string
     * @return int
     */
    static public function stringToCode($string)
    {
        $stringLength = strlen($string);
        if($stringLength == 0) return 0;

        $code = 0;
        for($i = 0; $i < $stringLength; ++ $i) $code += ($i + 1) * ord($string[$i]);
        return $code;
    }

    /**
     * Create user avatar.
     *
     * @param  string|object|array  $user        User object or user account
     * @param  string|int           $size        Avatar size, can be a number or preset sizes: "xs", "sm", "", "lg", "xl", default is ""
     * @param  string               $className   Avatar element class name, default is "avatar-circle"
     * @param  string               $attrib      Extra attributes on avatar element
     * @param  string               $tag         Avatar element tag name, default is "div"
     * @param  string               $hueDistance Hue distance used as background color for default avatar
     * @param  string               $saturation  Saturation used as background color for default avatar
     * @param  string               $lightness   Lightness used as background color for default avatar
     * @static
     * @access public
     * @return string
     */
    static public function avatar($user, $size = '', $className = 'avatar-circle', $attrib = '', $tag = 'div', $hueDistance = 43, $saturation = '40%', $lightness = '60%')
    {
        $userObj = new stdClass();

        if(is_string($user))
        {
            $userObj->account = $user;
            $user = $userObj;
        }
        elseif(is_array($user))
        {
            $userObj->avatar  = $user['avatar'];
            $userObj->account = $user['account'];
            $userObj->name    = isset($user['name']) ? $user['name'] : (isset($user['realname']) ? $user['realname'] : $user['account']);
            $user = $userObj;
        }
        elseif(is_object($user))
        {
            $userObj->avatar  = isset($user->avatar) ? $user->avatar : '';
            $userObj->account = $user->account;
            $userObj->name    = isset($user->name) ? $user->name : (isset($user->realname) ? $user->realname : $user->account);
            $user = $userObj;
        }

        $hasImage = !empty($user->avatar);

        $extraClassName = $hasImage ? ' has-img' : ' has-text';
        $style = '';

        if($size)
        {
            if(is_numeric($size)) $style .= "width: $size" . "px; height: $size" . "px; line-height: $size" . 'px;';
            $extraClassName .= " avatar-$size";
        }

        if(!$hasImage)
        {
            $colorHue = (html::stringToCode($user->account) * $hueDistance) % 360;
            $style   .= "background: hsl($colorHue, $saturation, $lightness);";
            if(is_numeric($size)) $style .= 'font-size: ' . round($size / 2) . 'px;';
        }

        if(!empty($style)) $style = "style='$style'";

        $html  = "<$tag class='avatar$extraClassName $className' $attrib $style>";
        if($hasImage)
        {
            $html .= html::image($user->avatar);
        }
        else
        {
            $mbLength = mb_strlen($user->name, 'utf-8');
            $strLength = strlen($user->name);

            $text = '';
            if($strLength === $mbLength)
            {
                /* Pure alphabet or numbers 纯英文情况 */
                $text .= strtoupper($user->name[0]);
            }
            else if($strLength % $mbLength == 0 && $strLength % 3 == 0)
            {
                /* Pure chinese characters 纯中文的情况 */
                $text .= $mbLength <= 2 ? $user->name : mb_substr($user->name, $mbLength - 2, $mbLength, 'utf-8');
            }
            else
            {
                /* Mix of Chinese and English 中英文混合的情况 */
                $text .= $mbLength <= 2 ? $user->name : mb_substr($user->name, 0, 2, 'utf-8');
            }
            $textLength = mb_strlen($text, 'utf-8');
            $html .= "<span class='text text-len-$textLength'>$text</span>";
        }
        $html .= "</$tag>";

        return $html;
    }

    /**
     * Create a small user avatar.
     *
     * @param  string|object $user      User object or user avatar url or user account
     * @param  string        $className Avatar element class name, default is "avatar-circle"
     * @param  string        $attrib    Extra attributes on avatar element
     * @param  string        $tag       Avatar element tag name, default is "div"
     * @static
     * @access public
     * @return string
     */
    static public function smallAvatar($user, $className = 'avatar-circle', $attrib = '', $tag = 'div')
    {
        return html::avatar($user, 'sm', $className, $attrib, $tag);
    }

    /**
     * Create a middle size user avatar.
     *
     * @param  string|object $user      User object or user avatar url or user account
     * @param  string        $className Avatar element class name, default is "avatar-circle"
     * @param  string        $attrib    Extra attributes on avatar element
     * @param  string        $tag       Avatar element tag name, default is "div"
     * @static
     * @access public
     * @return string
     */
    static public function middleAvatar($user, $className = 'avatar-circle', $attrib = '', $tag = 'div')
    {
        return html::avatar($user, 'md', $className, $attrib, $tag);
    }

    /**
     * Create a large user avatar.
     *
     * @param  string|object $user      User object or user avatar url or user account
     * @param  string        $className Avatar element class name, default is "avatar-circle"
     * @param  string        $attrib    Extra attributes on avatar element
     * @param  string        $tag       Avatar element tag name, default is "div"
     * @static
     * @access public
     * @return string
     */
    static public function largeAvatar($user, $className = 'avatar-circle', $attrib = '', $tag = 'div')
    {
        return html::avatar($user, 'lg', $className, $attrib, $tag);
    }

    /**
     * Create a progress ring.
     *
     * @param  int    progress  Progress value, 0 ~ 100
     * @static
     * @access public
     * @return string
     */
    static public function ring($progress)
    {
        $progressVal = max(0, min(100, round($progress)));
        $ringPosition = ceil($progressVal / 2) * 24;
        return "<div class='ring' style='background-position-x: -{$ringPosition}px'><span>{$progressVal}</span></div>";
    }
}

/**
 * JS类。
 * JS class.
 *
 * @package front
 */
class js extends baseJS
{
    /**
     * Open a new app window.
     *
     * @param  string    $app
     * @param  string    $url
     * @static
     * @access public
     * @return string
     */
    static public function openEntry($app, $url)
    {
        return static::start() . "$.apps.open('$url', '$app')" . static::end();
    }

    /**
     * Generate the start of a js block, injects code for zentao client.
     *
     * @param  bool   $full
     * @static
     * @access public
     * @return string
     */
    static public function start($full = true)
    {
        if($full)
        {
            $document = "<html><meta charset='utf-8'/><style>body{background:white}</style><script>";
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
            {
                /* Inject handler for confirm, prompt and alert. */
                $document .= <<<EOT
window.confirm = function() {
    console.warn('\"window.confirm\" is disabled in webview.');
    return true;
};
window.prompt = function() {
    console.warn('\"window.prompt\" is disabled in webview.');
};
window.alert = function(msg) {
    const win = window.parent ? window.parent : window;
    win.open(`xxc://webview/alert/\${encodeURIComponent(msg)}`, '_blank');
};
EOT;
            }
            return $document;
        }
        return '<script>';
    }
}

/**
 * css类。
 * css class.
 *
 * @package front
 */
class css extends baseCSS
{
}
