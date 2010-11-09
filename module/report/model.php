<?php
/**
 * The model file of report module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
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
function createChart$count()
{
chartWidth = "$width";
if(chartWidth == 'auto') chartWidth = $('#$divID').width();
if(chartWidth < 300) chartWidth = 300;
var $chartID = new FusionCharts("$chartRoot$swfFile", "{$chartID}id", chartWidth, "$height"); 
$chartID.setDataXML("$dataXML");
$chartID.render("$divID");
}
</script>
EOT;
    }

    /* 生成single系列的xml数据。。 */
    public function createSingleXML($sets, $chartOptions = array())
    {
        $data  = pack("CCC", 0xef, 0xbb, 0xbf);
        $data .="<?xml version='1.0' encoding='UTF-8'?>";

        $data .= '<graph';
        foreach($chartOptions as $key => $value) $data .= " $key='$value'";
        $data .= ">";

        $colorCount = count($this->lang->report->colors);
        $i = 0;
        foreach($sets as $set)
        {
            if($i == $colorCount) $i = 0;
            $color = $this->lang->report->colors[$i];
            $i ++;
            $data .= "<set name='$set->name' value='$set->value' color='$color' />";
        }
        $data .= "</graph>";
        return $data;
    }

    /* 输出渲染js图标的语句。*/
    public function rendJsCharts($chartCount)
    {
        $js = '<script language="Javascript">';
        for($i = 1; $i <= $chartCount; $i ++) $js .= "createChart$i()\n";
        $js .= '</script>';
        return $js;
    }

    /* 计算每条数据所占的百分比。*/
    public function computePercent($datas)
    {
        $sum = 0;
        foreach($datas as $data) $sum += $data->value;
        foreach($datas as $data) $data->percent = round($data->value / $sum, 2);
        return $datas;
    }
}
