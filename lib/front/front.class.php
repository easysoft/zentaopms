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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentaoms.com
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
     * create select with optgroup.
     *
     * @param string $name          the name of the select tag.
     * @param array  $groups        the option groups.
     * @param string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param string $attrib        other params such as multiple, size and style.
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
                $key      = str_replace('item', '', $key);    // 因为对象的元素不能为数字，所以需要在配置里面会在数字前面添加item，这个地方将item去掉。
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
     * @param string $name       the name of the radio tag.
     * @param array  $options    the array to create radio tag from.
     * @param string $checked    the value to checked by default.
     * @param string $attrib     other attribs.
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
            $string .= "<span><input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= strpos($checked, ",$key,") !== false ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " /><label>$value</label></span>\n";
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
     * create tags like "<input type='hidden' />"
     *
     * @param string $name     the name of the text input tag.
     * @param string $value    the default value.
     * @param string $attrib   other attribs.
     */
    static public function hidden($name, $value = "", $attrib = "")
    {
        return "<input type='hidden' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * create tags like "<input type='password' />"
     *
     * @param string $name     the name of the text input tag.
     * @param string $value    the default value.
     * @param string $attrib   other attribs.
     */
    static public function password($name, $value = "", $attrib = "")
    {
        return "<input type='password' name='$name' id='$name' value='$value' $attrib />\n";
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

    /**
     * create a button with a link.
     *
     * @static
     * @access public
     * @return string   the reset button tag.
     */
    public static function linkButton($label = '', $link = '', $misc = '')
    {
        return " <input type='button' value='$label' class='button-c' $misc onclick='location.href=\"$link\"' /> ";
    }

    /**
     * create a export link.
     *
     * @static
     * @access public
     * @return string   the reset button tag.
     */
    public static function export2csv($label = '', $pluginTitle, $misc = '')
    {
        return "<a href='#' onclick=\"$('.datatable').table2CSV({title:'$pluginTitle'})\" $misc />$label</a>\n<div id='exporter' class='hidden'></div>\n";
    }
    
    /*!
     * ubb2html support for php
     * @requires xhEditor
     * 
     * @author Yanis.Wang<yanis.wang@gmail.com>
     * @site http://xheditor.com/
     * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
     * 
     * @Version: 0.9.8 (build 100505)
     */

    public static function parseUBB($sUBB)
    {	
        $sHtml=$sUBB;

        global $emotPath,$cnum,$arrcode,$bUbb2htmlFunctionInit;$cnum=0;$arrcode=array();
        $emotPath='../xheditor_emot/';//表情根路径

        if(!$bUbb2htmlFunctionInit)
        {
            function saveCodeArea($match)
            {
                global $cnum,$arrcode;
                $cnum++;$arrcode[$cnum]=$match[0];
                return "[\tubbcodeplace_".$cnum."\t]";
            }
        }

        $sHtml=preg_replace_callback('/\[code\s*(?:=\s*((?:(?!")[\s\S])+?)(?:"[\s\S]*?)?)?\]([\s\S]*?)\[\/code\]/i','saveCodeArea',$sHtml);

        //$sHtml=preg_replace("/&/",'&amp;',$sHtml);
        //$sHtml=preg_replace("/</",'&lt;',$sHtml);
        //$sHtml=preg_replace("/>/",'&gt;',$sHtml);
        $sHtml=preg_replace("/\r?\n/",'<br />',$sHtml);

        $sHtml=preg_replace("/\[(\/?)(b|u|i|s|sup|sub)\]/i",'<$1$2>',$sHtml);
        $sHtml=preg_replace('/\[color\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="color:$1;">',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getSizeName($match)
            {
                $arrSize=array('8pt','10pt','12pt','14pt','18pt','24pt','36pt');
                return '<span style="font-size:'.$arrSize[$match[1]-1].';">';
            }
        }
        $sHtml=preg_replace_callback("/\[size\s*=\s*(\d+?)\s*\]/i",'getSizeName',$sHtml);
        $sHtml=preg_replace('/\[font\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="font-family:$1;">',$sHtml);
        $sHtml=preg_replace('/\[back\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="background-color:$1;">',$sHtml);
        $sHtml=preg_replace("/\[\/(color|size|font|back)\]/i",'</span>',$sHtml);

        for($i=0;$i<3;$i++)$sHtml=preg_replace('/\[align\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\](((?!\[align(?:\s+[^\]]+)?\])[\s\S])*?)\[\/align\]/','<p align="$1">$2</p>',$sHtml);
        $sHtml=preg_replace('/\[img\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/img\]/i','<img src="$1" alt="" />',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getImg($match)
            {
                $alt=$match[1];$p1=$match[2];$p2=$match[3];$p3=$match[4];$src=$match[5];
                $a=$p3?$p3:(!is_numeric($p1)?$p1:'');
                return '<img src="'.$src.'" alt="'.$alt.'"'.(is_numeric($p1)?' width="'.$p1.'"':'').(is_numeric($p2)?' height="'.$p2.'"':'').($a?' align="'.$a.'"':'').' />';
            }
        }

        $sHtml=preg_replace_callback('/\[img\s*=([^,\]]*)(?:\s*,\s*(\d*%?)\s*,\s*(\d*%?)\s*)?(?:,?\s*(\w+))?\s*\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*)?\s*\[\/img\]/i','getImg',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getEmot($match)
            {
                global $emotPath;
                $arr=split(',',$match[1]);
                if(!isset($arr[1])){$arr[1]=$arr[0];$arr[0]='default';}
                $path=$emotPath.$arr[0].'/'.$arr[1].'.gif';
                return '<img src="'.$path.'" alt="'.$arr[1].'" />';
            }
        }

        $sHtml=preg_replace_callback('/\[emot\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\/\]/i','getEmot',$sHtml);
        $sHtml=preg_replace('/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/i','<a href="$1">$1</a>',$sHtml);
        $sHtml=preg_replace('/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/i','<a href="$1">$2</a>',$sHtml);
        $sHtml=preg_replace('/\[email\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/email\]/i','<a href="mailto:$1">$1</a>',$sHtml);
        $sHtml=preg_replace('/\[email\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]+?)\s*\[\/email\]/i','<a href="mailto:$1">$2</a>',$sHtml);
        $sHtml=preg_replace("/\[quote\]([\s\S]*?)\[\/quote\]/i",'<blockquote>$1</blockquote>',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getFlash($match)
            {
                $w=$match[1];$h=$match[2];$url=$match[3];
                if(!$w)$w=480;if(!$h)$h=400;
                return '<embed type="application/x-shockwave-flash" src="'.$url.'" wmode="opaque" quality="high" bgcolor="#ffffff" menu="false" play="true" loop="true" width="'.$w.'" height="'.$h.'" />';
            }
        }
        $sHtml=preg_replace_callback('/\[flash\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/flash\]/i','getFlash',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getMedia($match)
            {
                $w=$match[1];$h=$match[2];$play=$match[3];$url=$match[4];
                if(!$w)$w=480;if(!$h)$h=400;
                return '<embed type="application/x-mplayer2" src="'.$url.'" enablecontextmenu="false" autostart="'.($play=='1'?'true':'false').'" width="'.$w.'" height="'.$h.'" />';
            }
        }
        $sHtml=preg_replace_callback('/\[media\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*(\d+)\s*)?)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/media\]/i','getMedia',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getTable($match)
            {
                return '<table'.(isset($match[1])?' width="'.$match[1].'"':'').(isset($match[2])?' bgcolor="'.$match[2].'"':'').'>';
            }
        }
        $sHtml=preg_replace_callback('/\[table\s*(?:=(\d{1,4}%?)\s*(?:,\s*([^\]"]+)(?:"[^\]]*?)?)?)?\s*\]/i','getTable',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getTR($match){return '<tr'.(isset($match[1])?' bgcolor="'.$match[1].'"':'').'>';}
        }
        $sHtml=preg_replace_callback('/\[tr\s*(?:=(\s*[^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getTR',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getTD($match)
            {
                $col=isset($match[1])?$match[1]:0;$row=isset($match[2])?$match[2]:0;$w=isset($match[3])?$match[3]:null;
                return '<td'.($col>1?' colspan="'.$col.'"':'').($row>1?' rowspan="'.$row.'"':'').($w?' width="'.$w.'"':'').'>';
            }
        }
        $sHtml=preg_replace_callback("/\[td\s*(?:=\s*(\d{1,2})\s*,\s*(\d{1,2})\s*(?:,\s*(\d{1,4}%?))?)?\s*\]/i",'getTD',$sHtml);
        $sHtml=preg_replace("/\[\/(table|tr|td)\]/i",'</$1>',$sHtml);
        $sHtml=preg_replace("/\[\*\]((?:(?!\[\*\]|\[\/list\]|\[list\s*(?:=[^\]]+)?\])[\s\S])+)/i",'<li>$1</li>',$sHtml);
        if(!$bUbb2htmlFunctionInit)
        {
            function getUL($match)
            {
                $str='<ul';
                if(isset($match[1]))$str.=' type="'.$match[1].'"';
                return $str.'>';
            }
        }
        $sHtml=preg_replace_callback('/\[list\s*(?:=\s*([^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getUL',$sHtml);
        $sHtml=preg_replace("/\[\/list\]/i",'</ul>',$sHtml);

        for($i=1;$i<=$cnum;$i++)$sHtml=str_replace("[\tubbcodeplace_".$i."\t]", $arrcode[$i],$sHtml);

        if(!$bUbb2htmlFunctionInit)
        {
            function fixText($match)
            {
                $text=$match[2];
                $text=preg_replace("/\t/",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$text);
                $text=preg_replace("/ /",'&nbsp;',$text);
                return $match[1].$text;
            }
        }
        $sHtml=preg_replace_callback('/(^|<\/?\w+(?:\s+[^>]*?)?>)([^<$]+)/i','fixText',$sHtml);

        $bUbb2htmlFunctionInit=true;

        return $sHtml;
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
     * Export the config vars for createLink() js version.
     * 
     * @static
     * @access public
     * @return string
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
        $js = <<<EOT
<script language = 'javascript'>
webRoot        = '$config->webRoot';
requestType    = '$config->requestType';
pathType       = '$config->pathType';
requestFix     = '$config->requestFix';
moduleVar      = '$config->moduleVar';
methodVar      = '$config->methodVar';
viewVar        = '$config->viewVar';
defaultView    = '$defaultViewType';
themeRoot      = '$themeRoot';
currentModule  = '$moduleName';
currentMethod  = '$methodName';
clientLang     = '$clientLang';
requiredFields = '$requiredFields';
lblShowAll     = '$lang->showAll';
lblHideClosed  = '$lang->hideClosed';
</script>

EOT;
        return $js;
    }
}
