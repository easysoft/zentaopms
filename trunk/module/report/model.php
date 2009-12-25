<?php
/**
 * The model file of report module of ZenTaoMS.
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
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class reportModel extends model
{
    /* 输出chart swf代码。*/
    public function createChart($swf, $dataURL, $width = 800, $height = 500)
    {
        $chartRoot = $this->app->getWebRoot() . 'fusioncharts/';
        $swfFile   = "fcf_$swf.swf";
        return <<<EOT
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="$width" height="$height" id="fcf$swf" >
<param name="movie"     value="$chartRoot$swfFile" />
<param name="FlashVars" value="&dataURL=$dataURL&chartWidth=$width&chartHeight=$height">
<param name="quality"   value="high" />
<param name="wmode"     value="Opaque">
<embed src="$chartRoot$swfFile" flashVars="&dataURL=$dataURL&chartWidth=$width&chartHeight=$height" quality="high" wmode="Opaque" width="$width" height="$height" name="fcf$swf" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
EOT;
    }

    /* 创建js输出的chart。*/
    public function createJSChart($swf, $dataXML, $width = 'auto', $height = 500)
    {
        $jsRoot = $this->app->getWebRoot() . 'js/';
        static $count = 0;
        $count ++;
        $chartRoot = $this->app->getWebRoot() . 'fusioncharts/';
        $swfFile   = "fcf_$swf.swf";
        $divID     = "chart{$count}div";
        $chartID   = "chart{$count}";

        $js = '';
        if($count == 1) $js = "<script language='Javascript' src='{$jsRoot}misc/fusioncharts.js'></script>";
        return <<<EOT
$js
<div id="$divID"></div>
<script language="JavaScript"> 
chartWidth = "$width";
if(chartWidth == 'auto') chartWidth = $('#$divID').css('width').replace('px', '');
var $chartID = new FusionCharts("$chartRoot$swfFile", "{$chartID}id", chartWidth, "$height"); 
$chartID.setDataXML("$dataXML");
$chartID.render("$divID");
</script>
EOT;
    }

    /* 生成single系列的xml数据。。 */
    function createSingleXML($sets, $chartOptions = array())
    {
        $data  = pack("CCC", 0xef, 0xbb, 0xbf);
        $data .="<?xml version='1.0' encoding='UTF-8'?>";
        $data .= '<graph';
        foreach($chartOptions as $key => $value) $data .= " $key='$value'";
        $data .= ">";
        foreach($sets as $set) $data .= "<set name='$set->name' value='$set->value' />";
        $data .= "</graph>";
        return $data;
    }
}
