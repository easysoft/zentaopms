<?php
/**
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
 * The html class, to build html tags.
 * 
 * @package   framework
 */
class html
{
    /**
     * Create the title tag. 
     * 
     * @param  mixed $title 
     * @access public
     * @return string.
     */
    public static function title($title)
    {
        return "<title>$title</title>\n";
    }

    /**
     * Create a meta.
     * 
     * @param mixed $name   the meta name
     * @param mixed $value  the meta value
     * @access public
     * @return string          
     */
    public static function meta($name, $value)
    {
        return "<meta name='$name' content='$value'>\n";
    }

    /**
     * Create icon tag
     * 
     * @param mixed $url  the url of the icon.
     * @access public
     * @return string          
     */
    public static function icon($url)
    {
        return "<link rel='icon' href='$url' type='image/x-icon' />\n" . 
               "<link rel='shortcut icon' href='$url' type='image/x-icon' />\n";

    }

    /**
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
     * Create tags like <a href="">text</a>
     *
     * @param  string $href      the link url.
     * @param  string $title     the link title.
     * @param  string $target    the target window
     * @param  string $misc      other params.
     * @return string
     */
    static public function a($href = '', $title = '', $target = "_self", $misc = '')
    {
        if(empty($title)) $title = $href;
        if($target == '_self') return "<a href='$href' $misc>$title</a>\n";
        return "<a href='$href' target='$target' $misc>$title</a>\n";
    }

    /**
     * Create tags like <a href="mailto:">text</a>
     *
     * @param  string $mail      the email address
     * @param  string $title     the email title.
     * @return string
     */
    static public function mailto($mail = '', $title = '')
    {
        if(empty($title)) $title = $mail;
        return "<a href='mailto:$mail'>$title</a>";
    }

    /**
     * Create tags like "<select><option></option></select>"
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $options       the array to create select tag from.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @return string
     */
    static public function select($name = '', $options = array(), $selectedItems = "", $attrib = "")
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        /* The begin. */
        $id = $name;
        if($pos = strpos($name, '[')) $id = substr($name, 0, $pos);
        $string = "<select name='$name' id='$id' $attrib>\n";

        /* The options. */
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
     * Create select with optgroup.
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $groups        the option groups.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @return string
     */
    static public function selectGroup($name = '', $groups = array(), $selectedItems = "", $attrib = "")
    {
        if(!is_array($groups) or empty($groups)) return false;

        /* The begin. */
        $id = $name;
        if($pos = strpos($name, '[')) $id = substr($name, 0, $pos);
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
     * Create tags like "<input type='radio' />"
     *
     * @param  string $name       the name of the radio tag.
     * @param  array  $options    the array to create radio tag from.
     * @param  string $checked    the value to checked by default.
     * @param  string $attrib     other attribs.
     * @return string
     */
    static public function radio($name = '', $options = array(), $checked = '', $attrib = '')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        $string  = '';
        foreach($options as $key => $value)
        {
            $string .= "<input type='radio' name='$name' value='$key' ";
            $string .= ($key == $checked) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " /> $value\n";
        }
        return $string;
    }

    /**
     * Create tags like "<input type='checkbox' />"
     *
     * @param  string $name      the name of the checkbox tag.
     * @param  array  $options   the array to create checkbox tag from.
     * @param  string $checked   the value to checked by default, can be item1,item2
     * @param  string $attrib    other attribs.
     * @return string
     */
    static public function checkbox($name, $options, $checked = "", $attrib = "")
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;
        $string  = '';
        $checked = ",$checked,";

        foreach($options as $key => $value)
        {
            $key     = str_replace('item', '', $key);
            $string .= "<span><input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= strpos($checked, ",$key,") !== false ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " /> <label>$value</label></span>\n";
        }
        return $string;
    }

    /**
     * Create tags like "<input type='text' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @return string
     */
    static public function input($name, $value = "", $attrib = "")
    {
        return "<input type='text' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * Create tags like "<input type='hidden' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @return string
     */
    static public function hidden($name, $value = "", $attrib = "")
    {
        return "<input type='hidden' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * Create tags like "<input type='password' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @return string
     */
    static public function password($name, $value = "", $attrib = "")
    {
        return "<input type='password' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * Create tags like "<textarea></textarea>"
     *
     * @param  string $name      the name of the textarea tag.
     * @param  string $value     the default value of the textarea tag.
     * @param  string $attrib    other attribs.
     * @return string
     */
    static public function textarea($name, $value = "", $attrib = "")
    {
        return "<textarea name='$name' id='$name' $attrib>$value</textarea>\n";
    }

    /**
     * Create tags like "<input type='file' />".
     *
     * @param  string $name      the name of the file name.
     * @param  string $attrib    other attribs.
     * @return string
     */
    static public function file($name, $attrib = "")
    {
        return "<input type='file' name='$name' id='$name' $attrib />\n";
    }

    /**
     * Create submit button.
     * 
     * @param  string $label    the label of the button
     * @param  string $misc     other params
     * @static
     * @access public
     * @return string the submit button tag.
     */
    public static function submitButton($label = '', $misc = '')
    {
        if(empty($label))
        {
            global $lang;
            $label = $lang->save;
        }
        return " <input type='submit' id='submit' value='$label' class='button-s' $misc /> ";
    }

    /**
     * Create reset button.
     * 
     * @static
     * @access public
     * @return string the reset button tag.
     */
    public static function resetButton()
    {
        global $lang;
        return " <input type='reset' id='reset' value='{$lang->reset}' class='button-r' /> ";
    }

    /**
     * Create common button.
     * 
     * @param  string $label the label of the button
     * @param  string $misc  other params
     * @static
     * @access public
     * @return string the common button tag.
     */
    public static function commonButton($label = '', $misc = '')
    {
        return " <input type='button' value='$label' class='button-c' $misc /> ";
    }

    /**
     * create a button, when click, go to a link.
     * 
     * @param  string $label    the link title
     * @param  string $link     the link url
     * @param  string $misc     other params
     * @static
     * @access public
     * @return string
     */
    public static function linkButton($label = '', $link = '', $misc = '')
    {
        return " <input type='button' value='$label' class='button-c' $misc onclick='location.href=\"$link\"' /> ";
    }
}

/**
 * JS class.
 * 
 * @package front
 */
class js
{
   /**
     * Import a js file.
     * 
     * @param  string $url 
     * @param  string $version 
     * @access public
     * @return string
     */
    public static function import($url, $version = '')
    {
        if(!$version) $version = filemtime(__FILE__);
        echo "<script src='$url?v=$version' type='text/javascript'></script>\n";
    }

    /**
     * The start of javascript. 
     * 
     * @static
     * @access private
     * @return string
     */
    static private function start($full = true)
    {
        if($full) return "<html><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><style>body{background:white}</style><script language='Javascript'>";
        return "<script language='Javascript'>";
    }

    /**
     * The end of javascript. 
     * 
     * @static
     * @access private
     * @return void
     */
    static private function end()
    {
        return "\n</script>\n";
    }

    /**
     * Show a alert box. 
     * 
     * @param  string $message 
     * @static
     * @access public
     * @return string
     */
    static public function alert($message = '')
    {
        return self::start() . "alert('" . $message . "')" . self::end() . self::resetForm();
    }

    /**
     * Show error info.
     * 
     * @param  string|array $message 
     * @static
     * @access public
     * @return string
     */
    static public function error($message)
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
        return self::alert($alertMessage);
    }

    /**
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
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param  string $message       the text to be showed.
     * @param  string $okURL         the url to go to when press 'ok'.
     * @param  string $cancleURL     the url to go to when press 'cancle'.
     * @param  string $okTarget      the target to go to when press 'ok'.
     * @param  string $cancleTarget  the target to go to when press 'cancle'.
     * @return string
     */
    static public function confirm($message = '', $okURL = '', $cancleURL = '', $okTarget = "self", $cancleTarget = "self", $Echo = true)
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
     * change the location of the $target window to the $URL.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
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
     * Goto a page after a timer.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @param   int    $time   the timer, msec.
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
     * Reload a window.
     *
     * @param   string $window the window to reload.
     * @return  string the javascript string.
     */
    static public function reload($window = 'self')
    {
        $js  = self::start();
        $js .=  "$window.location.href=$window.location.href";
        $js .= self::end();
        return $js;
    }

    /**
     * Export the config vars for createLink() js version.
     * 
     * @static
     * @access public
     * @return void
     */
    static public function exportConfigVars()
    {
        global $app, $config, $lang;
        $defaultViewType = $app->getViewType();
        $themeRoot       = $app->getWebRoot() . 'theme/';
        $moduleName      = $app->getModuleName();
        $methodName      = $app->getMethodName();
        $clientLang      = $app->getClientLang();
        $requiredFields  = '';
        if(isset($config->$moduleName->$methodName->requiredFields)) $requiredFields = str_replace(' ', '', $config->$moduleName->$methodName->requiredFields);

        $jsConfig->webRoot        = $config->webRoot;
        $jsConfig->cookieLife     = ceil(($config->cookieLife - time()) / 86400);
        $jsConfig->requestType    = $config->requestType;
        $jsConfig->pathType       = $config->pathType;
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
        $jsConfig->lblShowAll     = $lang->showAll;
        $jsConfig->lblHideClosed  = $lang->hideClosed;
        $jsConfig->submitting     = $lang->submitting;
        $jsConfig->save           = $lang->save;
        $jsConfig->router         = $app->server->PHP_SELF;

        $js  = self::start(false);
        $js .= 'var config=' . json_encode($jsConfig);
        $js .= self::end();
        echo $js;
    }

    /**
     * Execute some js code.
     * 
     * @param string $code 
     * @static
     * @access public
     * @return string
     */
    static public function execute($code)
    {
        $js = self::start();
        $js .= $code;
        $js .= self::end();
        echo $js;
    }
}

/**
 * css class.
 *
 * @package front
 */
class css
{
    /**
     * Import a css file.
     * 
     * @param  string $url 
     * @param  string $version 
     * @access public
     * @return vod
     */
    public static function import($url, $version = '')
    {
        if(!$version) $version = filemtime(__FILE__);
        echo "<link rel='stylesheet' href='$url?v=$version' type='text/css' media='screen' />\n";
    }

    /**
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
