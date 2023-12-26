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
        if(strlen(trim($title)) == 0) $title = $href;
        $newline = $newline ? "\n" : '';

        /* Make sure href is opened in the same tab. */
        if(!str_contains($misc, 'data-app='))
        {
            global $app, $lang;
            $module  = $app->rawModule;
            $dataApp = (isset($lang->navGroup->$module) and $lang->navGroup->$module != $app->tab) ? "data-app='{$app->tab}'" : '';
            $misc   .= ' ' . $dataApp;
        }

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
        if(str_contains($name, '[')) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $id = "id='{$id}'";
        if(str_contains($attrib, 'id=')) $id = '';

        $string = "<select name='$name' {$id} $attrib>\n";

        /* The options. */
        if(is_array($selectedItems)) $selectedItems = implode(',', $selectedItems);
        $selectedItems = ",$selectedItems,";
        foreach($options as $key => $value)
        {
            $selected = str_contains($selectedItems, ",$key,") ? " selected='selected'" : '';
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
        if(str_contains($name, '[')) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $string = "<select name='$name' id='$id' $attrib>\n";

        /* The options. */
        $selectedItems = ",$selectedItems,";
        foreach($groups as $groupName => $options)
        {
            $string .= "<optgroup label='$groupName'>\n";
            foreach($options as $key => $value)
            {
                $selected = str_contains($selectedItems, ",$key,") ? " selected='selected'" : '';
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
            if($isBlock) $string .= "<div class='checkbox'><label>";
            else $string .= "<label class='checkbox-inline'>";
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= (str_contains($checked, ",$key,")) ? " checked ='checked'" : "";
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
        if(str_contains($attrib, 'id=')) $id = '';
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
        $id = str_replace(array('[', ']'), "", $name);
        return "<input type='hidden' name='$name' id='$id' value='$value' $attrib />\n";
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
        if(stripos($attrib, 'autocomplete') === false) $attrib .= " autocomplete='off'";
        $id = str_replace(array('[', ']'), "", $name);
        return "<input type='password' name='$name' id='$id' value='$value' $attrib />\n";
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
        $id = "id='$name'";
        $id = str_replace(array('[', ']'), "", $id);
        if(str_contains($attrib, 'id=')) $id = '';
        return "<textarea name='$name' $id $attrib>$value</textarea>\n";
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
        $id    = str_replace(array('[', ']'), "", $name);
        $html  = "<div class='input-append date date-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$id' value='$value' {$attrib} />\n";
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
        $id    = str_replace(array('[', ']'), "", $name);
        $html  = "<div class='input-append date time-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$id' value='$value' {$attrib} />\n";
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
        $misc .= !str_contains($misc, 'data-loading') ? " data-loading='$lang->loading'" : '';

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
    public static function resetButton($label = '', $class = 'btn-wide')
    {
        if(empty($label))
        {
            global $lang;
            $label = $lang->reset;
        }
        return " <button type='reset' id='reset' class='btn btn-reset $class'>$label</button>";
    }

    /**
     * Get goback link.
     * 获取返回按钮链接
     */
    public static function getGobackLink()
    {
        global $app, $config;

        $gobackLink   = '';
        $referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $refererParts = parse_url($referer);

        if($config->requestType == 'PATH_INFO' and empty($refererParts)) return $gobackLink;
        if($config->requestType == 'GET' and !isset($refererParts['query'])) return $gobackLink;

        $tab        = $app->tab;
        $gobackList = isset($_COOKIE['goback']) ? json_decode($_COOKIE['goback'], true) : array();
        $gobackLink = isset($gobackList[$tab]) ? $gobackList[$tab] : '';

        /* Make sure href is opened in the same tab. */
        if(!empty($gobackLink)) $gobackLink .= "#app=$tab";

        /* If the link of the referer is not the link of the current page or the link of the index,  the cookie and gobackLink will be updated. */
        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();
        $refererLink   = $config->requestType == 'PATH_INFO' ? $refererParts['path'] : $refererParts['query'];
        if(!preg_match("/(m=|\/)(index|search|$currentModule)(&f=|-)(index|buildquery|$currentMethod)(&|-|\.)?/", strtolower($refererLink)))
        {
            $gobackList[$tab] = $referer;
            $gobackLink       = $referer;
            setcookie('goback', json_encode($gobackList), $config->cookieLife, $config->webRoot, '', $config->cookieSecure, false);
        }

        return empty($gobackLink) ? 'javascript:history.go(-1)' : $gobackLink;
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
    public static function backButton($label = '', $misc = '', $class = 'btn-wide')
    {
        if(helper::inOnlyBodyMode()) return false;

        global $lang, $app, $config;
        if(empty($label)) $label = $lang->goback;

        $gobackLink   = "<a href='javascript:history.go(-1)' class='btn btn-back $class' $misc>{$label}</a>";
        $tab          = $_COOKIE['tab'] ?? '';
        $referer      = $_SERVER['HTTP_REFERER'] ?? '';
        $refererParts = parse_url((string) $referer);

        if($config->requestType == 'PATH_INFO' and empty($refererParts)) return $gobackLink;
        if($config->requestType == 'GET' and !isset($refererParts['query'])) return $gobackLink;

        $refererLink   = $config->requestType == 'PATH_INFO' ? $refererParts['path'] : $refererParts['query'];
        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();
        $gobackList    = isset($_COOKIE['goback']) ? json_decode((string) $_COOKIE['goback'], true) : array();
        $gobackLink    = $gobackList[$tab] ?? '';

        /* Make sure href is opened in the same tab. */
        if(!str_contains($misc, 'data-app='))
        {
            $module  = $app->rawModule;
            $dataApp = (isset($lang->navGroup->$module) and $lang->navGroup->$module != $app->tab) ? "data-app='{$app->tab}'" : '';
            $misc   .= ' ' . $dataApp;
        }

        /* If the link of the referer is not the link of the current page or the link of the index,  the cookie and gobackLink will be updated. */
        if(preg_match("/(?:m=|\/)([a-zA-Z0-9]+)(?:(&f=)|(-?))([a-zA-Z0-9]+)?(?:&|-|\.)?/", strtolower($refererLink), $matches))
        {
            if(!isset($matches[4])) $matches[4] = $config->default->method;
            if(!in_array($matches[1], array($config->default->module, 'search')) or !in_array($matches[4], array($config->default->method, 'buildquery')))
            {
                if($matches[1] != 'index' and ($matches[1] != $currentModule or $matches[4] != $currentMethod))
                {
                    $gobackList[$tab] = $referer;
                    $gobackLink       = $referer;
                    setcookie('goback', json_encode($gobackList), $config->cookieLife, $config->webRoot, '', $config->cookieSecure, false);
                }
            }
        }

        $button = "<a href='{$gobackLink}' class='btn btn-back $class' $misc>{$label}</a>";

        $app->loadClass('purifier', true);
        $purifierConfig   = HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Cache.DefinitionImpl', null);

        /* 设置a标签允许的特殊属性，应用于高亮左侧导航。 */
        $def = $purifierConfig->getHTMLDefinition(true);
        $def->addAttribute('a', 'data-app', 'CDATA');

        $purifier = new HTMLPurifier($purifierConfig);

        return $purifier->purify($button);
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
     */
    public static function closeButton(): string
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
            var check = $('#' + scope + ' input:checkbox').length == $('#' + scope + ' input:checkbox:checked').length ? false : true;
            $('#' + scope + ' input').each(function()
            {
                $(this).prop("checked", check)
            });
            $(checker).data('check', check == true ? false :true);
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
            var check = $('input:checkbox').length == $('input:checkbox:checked').length ? false : true;
            $('input:checkbox').each(function()
            {
                $(this).prop("checked", check)
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
     */
    public static function printStars($stars, $print = true)
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

        $starsHtml = "<span class='stars-list'>";
        for($i = 1; $i <= $redStars;   $i ++) $starsHtml .= "<i class='icon-star'></i>";
        for($i = 1; $i <= $halfStars;  $i ++) $starsHtml .= "<i class='icon-star-half-full'></i>";
        for($i = 1; $i <= $whiteStars; $i ++) $starsHtml .= "<i class='icon-star-empty'></i>";
        $starsHtml .= '</span>';

        if(!$print) return $starsHtml;

        echo $starsHtml;
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
    public static function import($url, $ieParam = ''): void
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
     */
    static public function start($full = true): string
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
    static public function end($newline = true): string
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
        global $app;

        if($app->viewType == 'json')
        {
            $output = array();
            $output['status'] = 'success';
            $output['data']   = json_encode(array('message' => $message));
            $output['md5']    = md5($output['data']);

            return json_encode($output);
        }

        /* Convert ' to \'. */
        $message = str_replace("\\'", "'", $message);
        $message = str_replace("'", "\\'", $message);

        return static::start($full) . "window.alert('" . $message . "')" . static::end() . static::resetForm();
    }

    /**
     * 关闭浏览器窗口。
     * Close window
     *
     * @static
     * @access public
     * @return void
     */
    static public function close(): string
    {
        return static::start() . "window.close()" . static::end();
    }

    /**
     * 显示错误信息。
     * Show error info.
     *
     * @param  bool         $full
     * @static
     * @access public
     * @return string
     */
    static public function error(string|array $message, $full = true)
    {
        global $app;

        if($app->viewType == 'json')
        {
            $output = array();
            $output['status'] = 'success';
            $output['data']   = json_encode(array('result' => 'fail', 'message' => $message));
            $output['md5']    = md5($output['data']);

            return json_encode($output);
        }

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
        return static::alert($alertMessage, $full);
    }

    /**
     * 重置禁用的提交按钮。
     * Reset the submit form.
     *
     * @static
     * @access public
     */
    static public function resetForm(): string
    {
        return static::start() . 'if(window.parent) window.parent.$.enableForm();' . static::end();
    }

    /**
     * 显示一个确认框，点击确定跳转到$okURL，点击取消跳转到$cancelURL。
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param  string $message       显示的内容。              the text to be showed.
     * @param  string $okURL         点击确定后跳转的地址。    the url to go to when press 'ok'.
     * @param  string $cancleURL     点击取消后跳转的地址。    the url to go to when press 'cancle'.
     * @param  string $okTarget      点击确定后跳转的target。  the target to go to when press 'ok'.
     * @param  string $cancleTarget  点击取消后跳转的target。  the target to go to when press 'cancle'.
     * @param  string $okOpenApp     点击确定后跳转的应用。    the app to go to when press 'ok'.
     * @param  string $cancleOpenApp 点击取消后跳转的应用。    the app to go to when press 'cancle'.
     * @static
     * @access public
     * @return string
     */
    static public function confirm($message = '', $okURL = '', $cancleURL = '', $okTarget = "self", $cancleTarget = "self", $okOpenApp = '', $cancleOpenApp = '')
    {
        global $app;
        if($app->viewType == 'json')
        {
            $data = array();
            $data['message']      = $message;
            $data['okURL']        = common::getSysURL() . $okURL;
            $data['cancleURL']    = common::getSysURL() . $cancleURL;
            $data['okTarget']     = $okTarget;
            $data['cancleTarget'] = $cancleTarget;

            $output = array();
            $output['status'] = 'success';
            $output['data']   = json_encode($data);
            $output['md5']    = md5($output['data']);

            return json_encode($output);
        }

        $js = static::start();

        $confirmAction = '';
        if(strtolower($okURL) == "back")
        {
            $confirmAction = "history.back(-1);";
        }
        elseif(str_contains($okTarget, '$.apps.open'))
        {
            $confirmAction = "$okTarget('$okURL', '$okOpenApp');";
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
        elseif(str_contains($cancleTarget, '$.apps.open'))
        {
            $cancleAction = "$cancleTarget('$cancleURL', '$cancleOpenApp');";
        }
        elseif(!empty($cancleURL))
        {
            $cancleAction = "$cancleTarget.location = '$cancleURL';";
        }
        if(!str_contains((string) $_SERVER['HTTP_USER_AGENT'], 'xuanxuan'))
        {
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
        }
        else
        {
            $js .= $confirmAction;
        }

        $js .= static::end();
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
        global $app;

        /* If the url if empty, goto the home page. */
        if(!$url)
        {
            global $config;
            $url = $config->webRoot;
        }

        if($app->viewType == 'json')
        {
            $data = strtolower((string) $url) == 'back' ? array('locate' => 'back') : array('locate' => common::getSysURL() . $url);

            $output = array();
            $output['status'] = 'success';
            $output['data']   = json_encode($data);
            $output['md5']    = md5($output['data']);

            return json_encode($output);
        }

        $js  = static::start();
        if(strtolower((string) $url) == "back")
        {
            $js .= "history.back(-1);\n";
        }
        elseif($target === 'app' or str_contains($target, '$.apps.open'))
        {
            $js .= "parent.$target('$url')";
        }
        else
        {
            /* Can not locate the url that has '#app', so remove it. */
            if(str_contains((string) $url, '#app=')) $url = substr((string) $url, 0, strpos((string) $url, '#app='));
            $js .= "$target.location='$url';\n";
        }
        return $js . static::end();
    }

    /**
     * 关闭当前窗口。
     * Close current window.
     *
     * @static
     * @access public
     */
    static public function closeWindow(): string
    {
        return static::start(). "window.close();" . static::end();
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
        $js  = static::start();
        $js .= "setTimeout(\"$target.location='$url'\", $time);";
        $js .= static::end();
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
        $js  = static::start();
        // See bug #2379 http://pms.zentao.net/bug-view-2379.html
        if($window !== 'self' && $window !== 'window')
        {
            $js .= "if($window !== window) $window.location.reload(true);\n";
        }
        else
        {
            $js .= "$window.location.reload(true);\n";
        }
        $js .= static::end();
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
        return static::closeModal($window);
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
        $js  = static::start();
        $js .= "if($window.location.href == self.location.href){ $window.window.close();}";
        $js .= "else{ $window.$.cookie('selfClose', 1);$window.$.closeModal($callback, '$location');}";
        $js .= static::end();
        return $js;
    }

    static function getJSConfigVars()
    {
        global $app, $config, $lang;
        $defaultViewType = $app->getViewType();
        $themeRoot       = $app->getWebRoot() . 'theme/';
        $moduleName      = $app->getModuleName();
        $methodName      = $app->getMethodName();
        $clientLang      = $app->getClientLang();
        $runMode         = defined('RUN_MODE') ? RUN_MODE : '';
        $requiredFields  = '';
        if(isset($config->$moduleName->$methodName->requiredFields)) $requiredFields = str_replace(' ', '', (string) $config->$moduleName->$methodName->requiredFields);

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
        $jsConfig->save           = $lang->save ?? '';
        $jsConfig->expand         = $lang->expand ?? '';
        $jsConfig->runMode        = $runMode;
        $jsConfig->timeout        = $config->timeout ?? '';
        $jsConfig->pingInterval   = $config->pingInterval ?? '';
        $jsConfig->onlybody       = zget($_GET, 'onlybody', 'no');
        $jsConfig->tabSession     = $config->tabSession;
        if($config->tabSession and helper::isWithTID()) $jsConfig->tid = zget($_GET, 'tid', '');

        return $jsConfig;
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

        global $lang;

        $jsConfig = static::getJSConfigVars();

        $jsLang = new stdclass();
        $jsLang->submitting   = $lang->loading ?? '';
        $jsLang->save         = $jsConfig->save;
        $jsLang->expand       = $lang->expand ?? '';
        $jsLang->timeout      = $lang->timeout ?? '';
        $jsLang->confirmDraft = $lang->confirmDraft ?? '';
        $jsLang->resume       = $lang->resume ?? '';
        $jsLang->program      = zget($lang->program, 'common', '');
        $jsLang->project      = zget($lang->project, 'common', '');
        $jsLang->product      = zget($lang->product, 'common', '');
        $jsLang->task         = zget($lang->task, 'common', '');
        $jsLang->story        = zget($lang->story, 'common', '');
        $jsLang->bug          = zget($lang->bug, 'common', '');
        $jsLang->testcase     = zget($lang->testcase, 'common', '');
        $jsLang->zahost       = zget($lang->zahost, 'common', '');
        $jsLang->zanode       = zget($lang->zanode, 'common', '');
        $jsLang->gitlab       = zget($lang->gitlab, 'common', '');
        $jsLang->gogs         = zget($lang->gogs, 'common', '');
        $jsLang->gitea        = zget($lang->gitea, 'common', '');
        $jsLang->jenkins      = zget($lang->jenkins, 'common', '');
        $jsLang->sonarqube    = zget($lang->sonarqube, 'common', '');
        $jsLang->repo         = zget($lang->repo, 'common', '');

        $js  = static::start(false);
        $js .= 'window.config=' . json_encode($jsConfig) . ";\n";
        $js .= 'window.lang=' . json_encode($jsLang) . ";\n";
        $js .= static::end();
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
    static public function execute($code): void
    {
        $js = static::start($full = false);
        $js .= $code;
        $js .= static::end();
        echo $js;
    }

    /**
     * 设置Javascript变量值。
     * Set js value.
     *
     * @param  string   $key
     * @param  mixed    $value
     * @static
     * @access public
     * @return string
     */
    static public function set($key, $value): void
    {
        global $config;
        $prefix = (isset($config->framework->jsWithPrefix) and $config->framework->jsWithPrefix == false) ? '' : 'v.';

        static $viewOBJOut;
        $js  = static::start(false);
        if(!$viewOBJOut and $prefix)
        {
            $js .= 'if(typeof(v) != "object") v = {};';
            $viewOBJOut = true;
        }

        /* Fix value is '0123' error. */
        if(is_numeric($value) and !preg_match('/^0[0-9]+/', $value))
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
            $js .= "{$prefix}{$key} = '{$value}';";
        }
        $js .= static::end($newline = false);
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
     */
    public static function import($url, $attrib = ''): void
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
     */
    public static function internal($css): void
    {
        echo "<style>$css</style>";
    }
}
