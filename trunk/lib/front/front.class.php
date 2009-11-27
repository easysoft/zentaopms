<?php
/**
 * The front class file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class html
{
    /**
     * create tags like <a href="">text</a>
     *
     * @param string $href      the link url.
     * @param string $title     the link title.
     * @param string $target    the target window
     * @param string $misc      other params.
     */
    static public function a($href = '', $title = '', $target = "_self", $misc = '')
    {
        if(empty($title)) $title = $href;
        if($target == '_self') return "<a href='$href' $misc>$title</a>\n";
        return "<a href='$href' target='$target' $misc>$title</a>\n";
    }

    /**
     * create tags like <a href="mailto:">text</a>
     *
     * @param string $mail      the email address
     * @param string $title     the email title.
     */
    static public function mailto($mail = '', $title = '')
    {
        if(empty($title)) $title = $mail;
        return "<a href='mailto:$mail'>$title</a>";
    }

    /**
     * create tags like "<select><option></option></select>"
     *
     * @param string $name          the name of the select tag.
     * @param array  $options       the array to create select tag from.
     * @param string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param string $attrib        other params such as multiple, size and style.
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
            $key      = str_replace('item', '', $key);    // 因为对象的元素不能为数字，所以需要在配置里面会在数字前面添加item，这个地方将item去掉。
            $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
            $string  .= "<option value='$key'$selected>$value</option>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * Create tags like "<input type='radio' />"
     *
     * @param string $name       the name of the radio tag.
     * @param array  $options    the array to create radio tag from.
     * @param string $checked    the value to checked by default.
     * @param string $attrib     other attribs.
     */
    static public function radio($name = '', $options = array(), $checked = "", $attrib = "")
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        $string  = '';
        foreach($options as $key => $value)
        {

            $key     = str_replace('item', '', $key);    // 因为对象的元素不能为数字，所以需要在配置里面会在数字前面添加item，这个地方将item去掉。
            $string .= "<input type='radio' name='$name' value='$key' ";
            $string .= ($key == $checked) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " /> $value\n";
        }
        return $string;
    }

    /**
     * create tags like "<input type='checkbox' />"
     *
     * @param string $name      the name of the checkbox tag.
     * @param array  $options   the array to create checkbox tag from.
     * @param string $checked   the value to checked by default, can be item1,item2
     * @param string $attrib    other attribs.
     */
    static public function checkbox($name, $options, $checked = "", $attrib = "")
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;
        $string  = '';
        $checked = ",$checked,";

        foreach($options as $key => $value)
        {
            $key     = str_replace('item', '', $key);    // 因为对象的元素不能为数字，所以需要在配置里面会在数字前面添加item，这个地方将item去掉。
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= strpos($checked, ",$key,") !== false ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " /> $value\n";
        }
        return $string;
    }

    /**
     * create tags like "<input type='text' />"
     *
     * @param string $name     the name of the text input tag.
     * @param string $value    the default value.
     * @param string $attrib   other attribs.
     */
    static public function input($name, $value = "", $attrib = "")
    {
        return "<input type='text' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * create tags like "<textarea></textarea>"
     *
     * @param string $name      the name of the textarea tag.
     * @param string $value     the default value of the textarea tag.
     * @param string $attrib    other attribs.
     */
    static public function textarea($name, $value = "", $attrib = "")
    {
        return "<textarea name='$name' id='$name' $attrib>$value</textarea>\n";
    }

    /**
     * create tags like "<input type='file' />".
     *
     * @param string $name      the name of the file name.
     * @param string $attrib    other attribs.
     */
    static public function file($name, $attrib = "")
    {
        return "<input type='file' name='$name' id='$name' $attrib />\n";
    }

    /**
     * create submit button.
     *
     * @static
     * @access public
     * @return string   the submit button tag.
     */
    public static function submitButton($label = '')
    {
        if(empty($label))
        {
            global $lang;
            $label = $lang->save;
        }
        return " <input type='submit' id='submit' value='$label' class='button-s' /> ";
    }

    /**
     * create reset button.
     *
     * @static
     * @access public
     * @return string   the reset button tag.
     */
    public static function resetButton()
    {
        global $lang;
        return " <input type='reset' id='reset' value='{$lang->reset}' class='button-r' /> ";
    }

    /**
     * create common button.
     *
     * @static
     * @access public
     * @return string   the reset button tag.
     */
    public static function commonButton($label = '', $misc = '')
    {
        return " <input type='button' value='$label' class='button-c' $misc /> ";
    }
}

class js
{
    /* The start of javascript. */
    static private function start()
    {
        return <<<EOT
<html>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <script language='Javascript'>
EOT;
    }

    /* The end of javascript. */
    static private function end()
    {
        return "\n</script>\n";
    }

    /* Show a alert box. */
    static public function alert($message = '')
    {
        return self::start() . "alert('" . $message . "')" . self::end();
    }

    /* 弹出错误。其中message可以是一条字符串，也可以是一维或者二维数组。*/
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
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param string $message       the text to be showed.
     * @param string $okURL         the url to go to when press 'ok'.
     * @param string $cancleURL     the url to go to when press 'cancle'.
     * @param string $okTarget      the target to go to when press 'ok'.
     * @param string $cancleTarget  the target to go to when press 'cancle'.
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
     * @return  string         the javascript string.
     */
    static public function locate($url, $target = "self")
    {
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

    /* Close current window. */
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
     * @return  string         the javascript string.
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
     * @return  string         the javascript string.
     */
    static public function reload($window = 'self')
    {
        $js  = self::start();
        $js .=  "$window.location.href=$window.location.href";
        $js .= self::end();
        return $js;
    }

    /**
     * Export the createLink function of JS version.
     * 
     * @static
     * @access public
     * @return string   the js code of createLink()
     */
    static public function exportLinkFunc()
    {
        global $app, $config;
        $defaultViewType = $app->getViewType();
        $js = <<<EOT
<script language = 'javascript'>
function createLink(moduleName, methodName, vars, viewType)
{
    link        = '$config->webRoot';
    requestType = '$config->requestType';
    pathType    = '$config->pathType';
    requestFix  = '$config->requestFix';
    moduleVar   = '$config->moduleVar';
    methodVar   = '$config->methodVar';
    viewVar     = '$config->viewVar';
    viewType    = viewType ? viewType : '$defaultViewType';

    vars = vars.split('&');
    for(i = 0; i < vars.length; i ++) vars[i] = vars[i].split('=');

    if(requestType == 'PATH_INFO')
    {
        link += moduleName + requestFix + methodName;

        if(pathType == "full")
        {
            for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][0] + requestFix + vars[i][1];
        }
        else
        {
            for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][1];
        }
        link += '.' + viewType;
    }
    else
    {
        link += '?' + moduleVar + '=' + moduleName + '&' + methodVar + '=' + methodName + '&' + viewVar + '=' + viewType;
        for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
    }
    return link;
}
</script>
EOT;
        return $js;
    }
}
