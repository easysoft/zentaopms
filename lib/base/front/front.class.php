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

/**
 * html类，生成html标签。
 * The html class, to build html tags.
 * 
 * @package framework
 */
class baseHTML
{
    /**
     * 生成title标签。
     * Create the title tag. 
     * 
     * @param  mixed $title 
     * @static
     * @access public
     * @return string.
     */
    public static function title($title)
    {
        return "<title>$title</title>\n";
    }

    /**
     * 生成meta标签。
     * Create a meta.
     * 
     * @param mixed $name   the meta name
     * @param mixed $value  the meta value
     * @static
     * @access public
     * @return string          
     */
    public static function meta($name, $value)
    {
        if($name == 'charset') return "<meta charset='$value'>\n";
        return "<meta name='$name' content='$value'>\n";
    }

    /**
     * 生成favicon标签。
     * Create favicon tag
     *
     * @param mixed $url  the url of the icon.
     * @static
     * @access public
     * @return string
     */
    public static function favicon($url)
    {
        return "<link rel='icon' href='$url' type='image/x-icon' />\n<link rel='shortcut icon' href='$url' type='image/x-icon' />\n";
    }

    /**
     * 创建图标。
     * Create icon.
     * 
     * @param name $name  the name of the icon.
     * @param cssClass $class  the extra css class of the icon.
     * @static
     * @access public
     * @return string          
     */
    public static function icon($name, $class = '')
    {
        $class = empty($class) ? ('icon-' . $name) : ('icon-' . $name . ' ' . $class);
        return "<i class='$class'></i>";
    }

    /**
     * 生成rss标签。
     * Create the rss tag.
     * 
     * @param  string $url 
     * @param  string $title 
     * @static
     * @access public
     * @return string
     */
    public static function rss($url, $title = '')
    {
        return "<link href='$url' title='$title' type='application/rss+xml' rel='alternate' />";
    }

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
    static public function a($href = '', $title = '', $misc = '', $newline = true)
    {
        global $config;

        if(empty($title)) $title = $href;
        $newline = $newline ? "\n" : '';

        return "<a href='$href' $misc>$title</a>$newline";
    }

    /**
     * 生成邮件链接。
     * Create tags like <a href="mailto:">text</a>
     *
     * @param  string $mail      the email address
     * @param  string $title     the email title.
     * @static
     * @access public
     * @return string
     */
    static public function mailto($mail = '', $title = '')
    {
        $html   = '';
        $mails  = explode(',', $mail);
        $titles = explode(',', $title);
        foreach($mails as $key => $m)
        {
            if(empty($m)) continue;
            $t     = empty($titles[$key]) ? $mail : $titles[$key];
            $html .= " <a href='mailto:$m'>$t</a>";
        }
        return $html;
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
        if(!is_array($options) or empty($options)) return false;

        /* The begin. */
        $id = $name;
        if(strpos($name, '[') !== false) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $id = "id='{$id}'";
        if(strpos($attrib, 'id=') !== false) $id = '';

        $string = "<select name='$name' {$id} $attrib>\n";

        /* The options. */
        if(is_array($selectedItems)) $selectedItems = implode(',', $selectedItems);
        $selectedItems = ",$selectedItems,";
        foreach($options as $key => $value)
        {
            $key      = str_replace('item', '', $key);
            $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
            $string  .= "<option value='$key'$selected>$value</option>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * 生成带optgroup标签的select标签。
     * Create select with optgroup.
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $groups        the option groups.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @static
     * @access public
     * @return string
     */
    static public function selectGroup($name = '', $groups = array(), $selectedItems = "", $attrib = "")
    {
        if(!is_array($groups) or empty($groups)) return false;

        /* The begin. */
        $id = $name;
        if(strpos($name, '[') !== false) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $string = "<select name='$name' id='$id' $attrib>\n";

        /* The options. */
        $selectedItems = ",$selectedItems,";
        foreach($groups as $groupName => $options)
        {
            $string .= "<optgroup label='$groupName'>\n";
            foreach($options as $key => $value)
            {
                $key      = str_replace('item', '', $key);
                $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
                $string  .= "<option value='$key'$selected>$value</option>\n";
            }
            $string .= "</optgroup>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * 生成单选按钮。
     * Create tags like "<input type='radio' />"
     *
     * @param  string $name       the name of the radio tag.
     * @param  array  $options    the array to create radio tag from.
     * @param  string $checked    the value to checked by default.
     * @param  string $attrib     other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function radio($name = '', $options = array(), $checked = '', $attrib = '', $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;
        $isBlock = $type == 'block';

        $string  = '';
        foreach($options as $key => $value)
        {
            if($isBlock) $string .= "<div class='radio'><label>";
            else $string .= "<label class='radio-inline'>";
            $string .= "<input type='radio' name='$name' value='$key' ";
            $string .= ($key == $checked) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
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
    static public function checkbox($name, $options, $checked = "", $attrib = "", $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        if(is_array($checked)) $checked = implode(',', $checked);
        $string  = '';
        $checked = ",$checked,";
        $isBlock = $type == 'block';

        foreach($options as $key => $value)
        {
            $key     = str_replace('item', '', $key);
            if($isBlock) $string .= "<div class='checkbox'><label>";
            else $string .= "<label class='checkbox-inline'>";
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= (strpos($checked, ",$key,") !== false) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
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
    static public function input($name, $value = "", $attrib = "")
    {
        $id = "id='$name'";
        if(strpos($attrib, 'id=') !== false) $id = '';
        $value = str_replace("'", '&#039;', $value);
        return "<input type='text' name='$name' {$id} value='$value' $attrib />\n";
    }

    /**
     * 生成隐藏的提交标签。
     * Create tags like "<input type='hidden' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function hidden($name, $value = "", $attrib = "")
    {
        return "<input type='hidden' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * 创建密码输入框。
     * Create tags like "<input type='password' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function password($name, $value = "", $attrib = "")
    {
        return "<input type='password' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * 创建编辑器标签。
     * Create tags like "<textarea></textarea>"
     *
     * @param  string $name      the name of the textarea tag.
     * @param  string $value     the default value of the textarea tag.
     * @param  string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function textarea($name, $value = "", $attrib = "")
    {
        return "<textarea name='$name' id='$name' $attrib>$value</textarea>\n";
    }

    /**
     * 创建文件上传标签。
     * Create tags like "<input type='file' />".
     *
     * @param  string $name      the name of the file name.
     * @param  string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function file($name, $attrib = "")
    {
        return "<input type='file' name='$name' id='$name' $attrib />\n";
    }

    /**
     * 创建日期输入框。
     * Create date picker.
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $options 
     * @param  string $attrib 
     * @static
     * @access public
     * @return void
     */
    static public function date($name, $value = "", $options = '', $attrib = '')
    {
        $html = "<div class='input-append date date-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$name' value='$value' {$attrib} />\n";
        $html .= "<span class='add-on'><button class='btn' type='button'><i class='icon-calendar'></i></button></span></div>";
        return $html;
    }

    /**
     * 创建日期时间输入框。
     * Create dateTime picker.
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $options 
     * @param  string $attrib 
     * @static
     * @access public
     * @return void
     */
    static public function dateTime($name, $value = "", $options = '', $attrib = '')
    {
        $html = "<div class='input-append date time-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$name' value='$value' {$attrib} />\n";
        $html .= "<span class='add-on'><button class='btn' type='button'><i class='icon-calendar'></i></button></span></div>";
        return $html;
    }

    /**
     * 创建img标签。
     * create tags like "<img src='' />".
     *
     * @param string $name      the name of the image name.
     * @param string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function image($image, $attrib = '')
    {
        return "<img src='$image' $attrib />\n";
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
    public static function submitButton($label = '', $class = 'btn btn-primary', $misc = '')
    {
        global $lang;

        $label = empty($label) ? $lang->save : $label;
        $misc .= strpos($misc, 'data-loading') === false ? " data-loading='$lang->loading'" : '';

        return " <button type='submit' id='submit' class='$class' $misc>$label</button>";
    }

    /**
     * 创建重置按钮。
     * Create reset button.
     * 
     * @param  string $label 
     * @param  string $class 
     * @static
     * @access public
     * @return string the reset button tag.
     */
    public static function resetButton($label = '', $class = '')
    {
        if(empty($label))
        {
            global $lang;
            $label = $lang->reset;
        }
        return " <button type='reset' id='reset' class='btn btn-reset $class'>$label</button>";
    }

    /**
     * 创建返回按钮。
     * Back button. 
     * 
     * @param  string $label 
     * @param  string $misc 
     * @static
     * @access public
     * @return string the back button tag.
     */
    public static function backButton($label = '', $misc = '', $class = '')
    {
        if(helper::inOnlyBodyMode()) return false;

        global $lang;
        if(empty($label))
        {
            global $lang;
            $label = $lang->goback;
        }
        return  "<a href='javascript:history.go(-1);' class='btn btn-back $class' $misc>{$label}</a>";
    }

    /**
     * 创建通用按钮。
     * Create common button.
     * 
     * @param  string $label the label of the button
     * @param  string $class the class of the button
     * @param  string $misc  other params
     * @param  string $icon  icon
     * @static
     * @access public
     * @return string the common button tag.
     */
    public static function commonButton($label = '', $class = 'btn', $misc = '', $icon = '')
    {
        if($icon) $label = "<i class='icon-" . $icon . "'></i> " . $label;
        return " <button type='button' class='$class' $misc>$label</button>";
    }

    /**
     * 创建一个带有链接的按钮。
     * create a button, when click, go to a link.
     * 
     * @param  string $label    the link title
     * @param  string $link     the link url
     * @param  string $class    the link style
     * @param  string $misc     other params
     * @param  string $target   the target window
     * @static
     * @access public
     * @return string
     */
    public static function linkButton($label = '', $link = '', $class='btn', $misc = '', $target = 'self')
    {
        global $config, $lang;

        if(helper::inOnlyBodyMode() and $lang->goback == $label) return false;
        $link = helper::processOnlyBodyParam($link);

        return " <button type='button' class='$class' $misc onclick='$target.location.href=\"$link\"'>$label</button>";
    }

    /**
     * 创建关闭模态框按钮。
     * Create a button to close.
     *
     * @static
     * @access public
     * @return string
     */
    public static function closeButton()
    {
        return "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
    }

    /**
     * 创建全选标签。
     * Create tags like "<input type='$type' onclick='selectAll()'/>"
     * 
     * @param  string  $scope  the scope of select all.
     * @param  string  $type   the type of input tag.
     * @param  boolean $checked if the type is checkbox, set the checked attribute.
     * @param  string  $class
     * @static
     * @access public
     * @return string
     */
    static public function selectAll($scope = "", $type = "button", $checked = false, $class = '')
    {
        $string = <<<EOT
<script>
function selectAll(checker, scope, type)
{ 
    if(scope)
    {
        if(type == 'button')
        {
            $('#' + scope + ' input').each(function() 
            {
                $(this).prop("checked", true)
            });
        }
        else if(type == 'checkbox')
        {
            $('#' + scope + ' input').each(function() 
            {
                $(this).prop("checked", checker.checked)
            });
         }
    }
    else
    {
        if(type == 'button')
        {
            $('input:checkbox').each(function() 
            {
                $(this).prop("checked", true)
            });
        }
        else if(type == 'checkbox')
        { 
            $('input:checkbox').each(function() 
            {
                $(this).prop("checked", checker.checked)
            });
        }
    }
}
</script>
EOT;
        global $lang;
        if($type == 'checkbox')
        {
            $string .= " <input type='checkbox' name='allchecker[]'" . ($checked ? " checked=$checked" : '') . " onclick='selectAll(this, \"$scope\", \"$type\")' />";
        }
        elseif($type == 'button')
        {
            $string .= "<input type='button' name='allchecker' id='allchecker' class='btn btn-select-all $class' value='{$lang->selectAll}' onclick='selectAll(this, \"$scope\", \"$type\")' />";
        }

        return  $string;
    }

    /**
     * 创建反选标签。
     * Create tags like "<input type='button' onclick='selectReverse()'/>"
     * 
     * @param  string $scope  the scope of select reverse.
     * @static
     * @access public
     * @return string
     */
    static public function selectReverse($scope = "")
    {
        $string = <<<EOT
<script type="text/javascript">
function selectReverse(scope)
{ 
    if(scope)
    {
        $('#' + scope + ' input').each(function() 
        {
            $(this).prop("checked", !$(this).prop("checked"))
        });
    }
    else
    {
        $('input:checkbox').each(function() 
        {
            $(this).prop("checked", !$(this).prop("checked"))
        });
    }
}
</script>
EOT;
        global $lang;
        $string .= "<input type='button' name='reversechecker' id='reversechecker' value='{$lang->selectReverse}' class='btn' onclick='selectReverse(\"$scope\")'/>";

        return  $string;
    }

    /**
     * 创建全选、反选按钮组。
     * Create select buttons include 'selectAll' and 'selectReverse'.
     * 
     * @param  string $scope  the scope of select reverse.
     * @param  bool   $asGroup 
     * @param  string $appendClass 
     * @static
     * @access public
     * @return string
     */
    static public function selectButton($scope = "", $asGroup = true, $appendClass = '')
    {
        $string = <<<EOT
<script>
$(function()
{
    if($('body').data('bindSelectBtn')) return;
    $('body').data('bindSelectBtn', true);
    $(document).on('click', '.check-all, .check-inverse, #allchecker, #reversechecker', function()
    {
        var e = $(this);
        if(e.closest('.datatable').length) return;
        scope = e.data('scope');
        scope = scope ? $('#' + scope) : e.closest('.table');
        if(!scope.length) scope = e.closest('form');
        scope.find('input:checkbox').each(e.hasClass('check-inverse') ? function() { $(this).prop("checked", !$(this).prop("checked"));} : function() { $(this).prop("checked", true);});
    });
});
</script>
EOT;
        global $lang;
        if($asGroup) $string .= "<div class='btn-group'>";
        $string .= "<a id='allchecker' class='btn btn-select-all check-all $appendClass' data-scope='$scope' href='javascript:;' >{$lang->selectAll}</a>";
        $string .= "<a id='reversechecker' class='btn btn-select-reverse check-inverse $appendClass' data-scope='$scope' href='javascript:;'>{$lang->selectReverse}</a>";
        if($asGroup) $string .= "</div>";
        return $string;
    }

    /**
     * 打印星星。
     * Print the star images.
     * 
     * @param  float    $stars 0 1 1.5 2 2.5 3 3.5 4 4.5 5
     * @access public
     * @static
     * @access public
     * @return void
     */
    public static function printStars($stars)
    {
        $redStars   = 0;
        $halfStars  = 0;
        $whiteStars = 5;
        if($stars)
        {
            /* If stars more than max, then fix it. */
            if($stars > $whiteStars) $stars = $whiteStars;

            $redStars  = floor($stars);
            $halfStars = $stars - $redStars ? 1 : 0;
            $whiteStars = 5 - ceil($stars);
        }
        echo "<span class='stars-list'>";
        for($i = 1; $i <= $redStars;   $i ++) echo "<i class='icon-star'></i>";
        for($i = 1; $i <= $halfStars;  $i ++) echo "<i class='icon-star-half-full'></i>";
        for($i = 1; $i <= $whiteStars; $i ++) echo "<i class='icon-star-empty'></i>";
        echo '</span>';
    }
}

/**
 * JS类。
 * JS class.
 * 
 * @package front
 */
class baseJS
{
    /**
     * 引入一个js文件。
     * Import a js file.
     * 
     * @param  string $url 
     * @param  string $ieParam    like 'lt IE 9'
     * @static
     * @access public
     * @return string
     */
    public static function import($url, $ieParam = '')
    {
        global $config;
        $pathInfo = parse_url($url);
        $mark  = !empty($pathInfo['query']) ? '&' : '?';

        $hasLimit = ($ieParam and stripos($ieParam, 'ie') !== false);
        if($hasLimit) echo "<!--[if $ieParam]>\n";
        echo "<script src='$url{$mark}v={$config->version}'></script>\n";
        if($hasLimit) echo "<![endif]-->\n";
    }

    /**
     * 开始输出js。
     * The start of javascript. 
     * 
     * @param  bool   $full 
     * @static
     * @access public
     * @return string
     */
    static public function start($full = true)
    {
        if($full) return "<html><meta charset='utf-8'/><style>body{background:white}</style><script>";
        return "<script>";
    }

    /**
     * 结束输出js。
     * The end of javascript. 
     * 
     * @param  bool    $newline 
     * @static
     * @access public
     * @return void
     */
    static public function end($newline = true)
    {
        if($newline) return "\n</script>\n";
        return "</script>\n";
    }

    /**
     * 显示一个警告框。
     * Show a alert box. 
     * 
     * @param  string $message 
     * @param  bool   $full 
     * @static
     * @access public
     * @return string
     */
    static public function alert($message = '', $full = true)
    {
        return self::start($full) . "alert('" . $message . "')" . self::end() . self::resetForm();
    }

    /**
     * 关闭浏览器窗口。
     * Close window 
     * 
     * @static
     * @access public
     * @return void
     */
    static public function close()
    {
        return self::start() . "window.close()" . self::end();
    }

    /**
     * 显示错误信息。
     * Show error info.
     * 
     * @param  string|array $message 
     * @param  bool         $full 
     * @static
     * @access public
     * @return string
     */
    static public function error($message, $full = true)
    {
        $alertMessage = '';
        if(is_array($message))
        {
            foreach($message as $item)
            {
                is_array($item) ? $alertMessage .= join('\n', $item) . '\n' : $alertMessage .= $item . '\n';
            }
        }
        else
        {
            $alertMessage = $message;
        }
        return self::alert($alertMessage, $full);
    }

    /**
     * 重置禁用的提交按钮。
     * Reset the submit form. 
     * 
     * @static
     * @access public
     * @return string
     */
    static public function resetForm()
    {
        return self::start() . 'if(window.parent) window.parent.document.body.click();' . self::end();
    }

    /**
     * 显示一个确认框，点击确定跳转到$okURL，点击取消跳转到$cancelURL。
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param  string $message      显示的内容。              the text to be showed.
     * @param  string $okURL        点击确定后跳转的地址。    the url to go to when press 'ok'.
     * @param  string $cancleURL    点击取消后跳转的地址。    the url to go to when press 'cancle'.
     * @param  string $okTarget     点击确定后跳转的target。  the target to go to when press 'ok'.
     * @param  string $cancleTarget 点击取消后跳转的target。  the target to go to when press 'cancle'.
     * @static
     * @access public
     * @return string
     */
    static public function confirm($message = '', $okURL = '', $cancleURL = '', $okTarget = "self", $cancleTarget = "self")
    {
        $js = self::start();

        $confirmAction = '';
        if(strtolower($okURL) == "back")
        {
            $confirmAction = "history.back(-1);";
        }
        elseif(!empty($okURL))
        {
            $confirmAction = "$okTarget.location = '$okURL';";
        }

        $cancleAction = '';
        if(strtolower($cancleURL) == "back")
        {
            $cancleAction = "history.back(-1);";
        }
        elseif(!empty($cancleURL))
        {
            $cancleAction = "$cancleTarget.location = '$cancleURL';";
        }

        $js .= <<<EOT
if(confirm("$message"))
{
    $confirmAction
}
else
{
    $cancleAction
}
EOT;
        $js .= self::end();
        return $js;
    }

    /**
     * $target会跳转到$url指定的地址。
     * change the location of the $target window to the $URL.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function locate($url, $target = "self")
    {
        /* If the url if empty, goto the home page. */
        if(!$url)
        {
            global $config;
            $url = $config->webRoot;
        }

        $js  = self::start();
        if(strtolower($url) == "back")
        {
            $js .= "history.back(-1);\n";
        }
        else
        {
            $js .= "$target.location='$url';\n";
        }
        return $js . self::end();
    }

    /**
     * 关闭当前窗口。
     * Close current window.
     * 
     * @static
     * @access public
     * @return string
     */
    static public function closeWindow()
    {
        return self::start(). "window.close();" . self::end();
    }

    /**
     * 经过一段时间后跳转到指定的页面。
     * Goto a page after a timer.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @param   int    $time   the timer, msec.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function refresh($url, $target = "self", $time = 3000)
    {
        $js  = self::start();
        $js .= "setTimeout(\"$target.location='$url'\", $time);";
        $js .= self::end();
        return $js;
    }

    /**
     * 重新加载窗口。
     * Reload a window.
     *
     * @param   string $window the window to reload.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function reload($window = 'self')
    {
        $js  = self::start();
        $js .= "$window.location.reload(true);\n";
        $js .= self::end();
        return $js;
    }

    /**
     * 用Javascript关闭colorbox弹出框。
     * Close colorbox in javascript.
     * This is a obsolete method, you can use 'closeModal' instead.
     * 
     * @param  string $window 
     * @static
     * @access public
     * @return string
     */
    static public function closeColorbox($window = 'self')
    {
        return self::closeModal($window);
    }

    /**
     * 用Javascript关闭模态框。
     * Close modal with javascript.
     * 
     * @param  string $window 
     * @param  string $location 
     * @param  string $callback 
     * @static
     * @access public
     * @return string
     */
    static public function closeModal($window = 'self', $location = 'this', $callback = 'null')
    {
        $js  = self::start();
        $js .= "if($window.location.href == self.location.href){ $window.window.close();}";
        $js .= "else{ $window.$.cookie('selfClose', 1);$window.$.closeModal($callback, '$location');}";
        $js .= self::end();
        return $js;
    }

    /**
     * 导出$config到js，因为js的createLink()方法需要获取config信息。
     * Export the config vars for createLink() js version.
     * 
     * @static
     * @access public
     * @return void
     */
    static public function exportConfigVars()
    {
        if(!function_exists('json_encode')) return false;

        global $app, $config, $lang;
        $defaultViewType = $app->getViewType();
        $themeRoot       = $app->getWebRoot() . 'theme/';
        $moduleName      = $app->getModuleName();
        $methodName      = $app->getMethodName();
        $clientLang      = $app->getClientLang();
        $runMode         = defined('RUN_MODE') ? RUN_MODE : '';
        $requiredFields  = '';
        if(isset($config->$moduleName->$methodName->requiredFields)) $requiredFields = str_replace(' ', '', $config->$moduleName->$methodName->requiredFields);

        $jsConfig = new stdclass();
        $jsConfig->webRoot        = $config->webRoot;
        $jsConfig->debug          = $config->debug;
        $jsConfig->appName        = $app->getAppName();
        $jsConfig->cookieLife     = ceil(($config->cookieLife - time()) / 86400);
        $jsConfig->requestType    = $config->requestType;
        $jsConfig->requestFix     = $config->requestFix;
        $jsConfig->moduleVar      = $config->moduleVar;
        $jsConfig->methodVar      = $config->methodVar;
        $jsConfig->viewVar        = $config->viewVar;
        $jsConfig->defaultView    = $defaultViewType;
        $jsConfig->themeRoot      = $themeRoot;
        $jsConfig->currentModule  = $moduleName;
        $jsConfig->currentMethod  = $methodName;
        $jsConfig->clientLang     = $clientLang;
        $jsConfig->requiredFields = $requiredFields;
        $jsConfig->router         = $app->server->SCRIPT_NAME;
        $jsConfig->save           = isset($lang->save) ? $lang->save : '';
        $jsConfig->runMode        = $runMode;
        $jsConfig->timeout        = isset($config->timeout) ? $config->timeout : '';
        $jsConfig->pingInterval   = isset($config->pingInterval) ? $config->pingInterval : '';

        $jsLang = new stdclass();
        $jsLang->submitting = isset($lang->loading) ? $lang->loading : '';
        $jsLang->save       = $jsConfig->save;
        $jsLang->timeout    = isset($lang->timeout) ? $lang->timeout : '';

        $js  = self::start(false);
        $js .= 'window.config=' . json_encode($jsConfig) . ";\n";
        $js .= 'window.lang=' . json_encode($jsLang) . ";\n";
        $js .= self::end();
        echo $js;
    }

    /**
     * 执行js代码。
     * Execute some js code.
     * 
     * @param string $code 
     * @static
     * @access public
     * @return string
     */
    static public function execute($code)
    {
        $js = self::start($full = false);
        $js .= $code;
        $js .= self::end();
        echo $js;
    }

    /**
     * 设置Javascript变量值。
     * Set js value.
     * 
     * @param  string   $key 
     * @param  mix      $value 
     * @static
     * @access public
     * @return string
     */
    static public function set($key, $value)
    {
        global $config;
        $prefix = (isset($config->framework->jsWithPrefix) and $config->framework->jsWithPrefix == false) ? '' : 'v.';

        static $viewOBJOut;
        $js  = self::start(false);
        if(!$viewOBJOut and $prefix)
        {
            $js .= 'if(typeof(v) != "object") v = {};'; 
            $viewOBJOut = true;
        }

        if(is_numeric($value))
        {
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_array($value) or is_object($value) or is_string($value))
        {
            /* Fix for auto-complete when user is number.*/
            if(is_array($value) or is_object($value))
            {
                $value = (array)$value;
                foreach($value as $k => $v)
                {
                    if(is_numeric($v)) $value[$k] = (string)$v;
                }
            }

            $value = json_encode($value);
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_bool($value))
        {
            $value = $value ? 'true' : 'false';
            $js .= "{$prefix}{$key} = $value;";
        }
        else
        {
            $value = addslashes($value);
            $js .= "{$prefix}{$key} = '{$value};'";
        }
        $js .= self::end($newline = false);
        echo $js;
    }
}

/**
 * css类。
 * css class.
 *
 * @package front
 */
class baseCSS
{
    /**
     * 引入css文件。
     * Import a css file.
     * 
     * @param  string $url 
     * @access public
     * @return void
     */
    public static function import($url, $attrib = '')
    {
        global $config;
        if(!empty($attrib)) $attrib = ' ' . $attrib;
        echo "<link rel='stylesheet' href='$url?v={$config->version}' type='text/css' media='screen'{$attrib} />\n";
    }

    /**
     * 打印css代码。
     * Print a css code.
     * 
     * @param  string    $css 
     * @static
     * @access public
     * @return void
     */
    public static function internal($css)
    {
        echo "<style>$css</style>";
    }
}
