<?php
/**
 * The model file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class reportModel extends model
{
    /**
     * Create the html code of chart.
     * 
     * @param  string $swf      the swf type
     * @param  string $dataURL  the date url
     * @param  int    $width 
     * @param  int    $height 
     * @access public
     * @return string
     */
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

    /**
     * Create the js code of chart.
     * 
     * @param  string $swf      the swf type
     * @param  string $dataURL  the date url
     * @param  int    $width 
     * @param  int    $height 
     * @access public
     * @return string
     */
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
<div id="$divID" class='chartDiv'></div>
<script language="JavaScript"> 
function createChart$count()
{
chartWidth = "$width";
if(chartWidth == 'auto') chartWidth = $('#$divID').width() - 10;
if(chartWidth < 300) chartWidth = 300;
var $chartID = new FusionCharts("$chartRoot$swfFile", "{$chartID}id", chartWidth, "$height"); 
$chartID.setDataXML("$dataXML");
$chartID.render("$divID");
}
</script>
EOT;
    }

    public function createJSChartFlot($projectName, $dataJSON, $count, $width = 'auto', $height = 500)
    {
        $this->app->loadLang('project');
        $jsRoot = $this->app->getWebRoot() . 'js/';
        $width  = $width . 'px';
        $height = $height . 'px';
return <<<EOT
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="{$jsRoot}jquery/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="{$jsRoot}jquery/flot/jquery.flot.min.js"></script>
<h1>$projectName  {$this->lang->project->burn}</h1>
<div id="placeholder" style="width:$width;height:$height;margin:0 auto"></div>
<script type="text/javascript">
$(function () 
{
    var data = [{data: $dataJSON}];
    function showTooltip(x, y, contents) 
    {
        $('<div id="tooltip">' + contents + '</div>').css
        ({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    } 
    if($count < 20)
    {
        var options = {
            series: {lines:{show: true,  lineWidth: 2}, points: {show: true},hoverable: true},
            legend: {noColumns: 1},
            grid: { hoverable: true, clickable: true },
            xaxis: {mode: "time", timeformat: "%m-%d", tickSize:[1, "day"]},
            yaxis: {mode: null, min: 0, minTickSize: [1, "day"]}};
    }
    else
    {
        var options = {
            series: {lines:{show: true,  lineWidth: 2}, points: {show: true},hoverable: true},
            legend: {noColumns: 1},
            grid: { hoverable: true, clickable: true },
            xaxis: {mode: "time", timeformat: "%m-%d", ticks:20, minTickSize:[1, "day"]},
            yaxis: {mode: null, min: 0, minTickSize: [1, "day"]}};
    }
    var placeholder = $("#placeholder");

    placeholder.bind("plotselected", function (event, ranges) 
    {
        plot = $.plot(placeholder, data, $.extend(true, {}, options, {xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to } }));
    });
    var plot = $.plot(placeholder, data, options);
    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) 
    {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) 
        {
            if (previousPoint != item.dataIndex)    
            {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2), y = item.datapoint[1].toFixed(2);

                showTooltip(item.pageX, item.pageY, y);
            }
        }
    });
});
</script>
EOT;
    }

    /**
     * Create xml data of single charts.
     * 
     * @param  array  $sets 
     * @param  array  $chartOptions 
     * @param  array  $colors 
     * @access public
     * @return string the xml data.
     */
    public function createSingleXML($sets, $chartOptions = array(), $colors = array())
    {
        $data  = pack("CCC", 0xef, 0xbb, 0xbf); // utf-8 bom.
        $data .="<?xml version='1.0' encoding='UTF-8'?>";

        $data .= '<graph';
        foreach($chartOptions as $key => $value) $data .= " $key='$value'";
        $data .= ">";

        if(empty($colors)) $colors = $this->lang->report->colors;
        $colorCount = count($colors);
        $i = 0;
        foreach($sets as $set)
        {
            if($i == $colorCount) $i = 0;
            $color = $colors[$i];
            $i ++;
            $data .= "<set name='$set->name' value='$set->value' color='$color' />";
        }
        $data .= "</graph>";
        return $data;
    }

    public function createSingleJSON($sets)
    {
        $data = '[';
        foreach($sets as $set)
        {
            $data .= " [$set->name, $set->value],";
        }
        $data = rtrim($data, ',');
        $data .= ']';
        return $data;
    }

    /**
     * Create the js code to render chart.
     * 
     * @param  int    $chartCount 
     * @access public
     * @return string
     */
    public function renderJsCharts($chartCount)
    {
        $js = '<script language="Javascript">';
        for($i = 1; $i <= $chartCount; $i ++) $js .= "createChart$i()\n";
        $js .= '</script>';
        return $js;
    }

    /**
     * Compute percent of every item.
     * 
     * @param  array    $datas 
     * @access public
     * @return array
     */
    public function computePercent($datas)
    {
        $sum = 0;
        foreach($datas as $data) $sum += $data->value;
        foreach($datas as $data) $data->percent = round($data->value / $sum, 2);
        return $datas;
    }

    /**
     * Get projects. 
     * 
     * @access public
     * @return void
     */
    public function getProjects()
    {
        $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('status')->eq('done')->fetchAll();
        foreach($projects as $project)
        {
            $total = $this->dao->select('project, SUM(estimate) AS estimate, SUM(consumed) AS consumed')
                ->from(TABLE_TASK)
                ->where('project')->eq($project->id)
                ->andWhere('status')->ne('cancel')
                ->andWhere('deleted')->eq(0)
                ->fetch();
            $stories = $this->dao->select("count(*) as count")->from(TABLE_PROJECTSTORY)->where('project')->eq($project->id)->fetch();
            $bugs    = $this->dao->select("count(*) as count")->from(TABLE_BUG)->where('project')->eq($project->id)->fetch();
            $dev     = $this->dao->select('SUM(consumed) as consumed')
                ->from(TABLE_TASK)
                ->where('project')->eq($project->id)
                ->andWhere('type')->eq('devel')
                ->andWhere('status')->ne('cancel')
                ->andWhere('deleted')->eq(0)
                ->fetch();
            $test   = $this->dao->select('SUM(consumed) as consumed')
                ->from(TABLE_TASK)
                ->where('project')->eq($project->id)
                ->andWhere('type')->eq('test')
                ->andWhere('status')->ne('cancel')
                ->andWhere('deleted')->eq(0)
                ->fetch();
            $project->estimate     = round($total->estimate, 2);
            $project->consumed     = round($total->consumed, 2);
            $project->stories      = $stories->count;
            $project->bugs         = $bugs->count;
            $project->devConsumed  = empty($dev->consumed) ? 0 : round($dev->consumed, 2);
            $project->testConsumed = empty($test->consumed) ? 0 : round($test->consumed, 2);
        }
        return $projects;
    }

    public function getProducts()
    {
        $products    = $this->dao->select('id, code, name, PO')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchAll('id');
        $plans       = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->in(array_keys($products))->fetchAll('id');
        $planStories = $this->dao->select('plan, id, status')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('plan')->in(array_keys($plans))->fetchGroup('plan', 'id');
        foreach($planStories as $planID => $stories)
        {
            foreach($stories as $story) $statusList[$story->status][] = $story;
            foreach(array_keys($statusList) as $status) $plans[$planID]->status[$status] = count($statusList[$status]);
        }
        foreach($plans as $plan) $products[$plan->product]->plans[] = $plan;
        return $products;
    }

    public function getBugs($begin, $end)
    {
        $bugGroups  = $this->dao->select('id, resolution, openedBy')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->andWhere('openedDate')->gt($begin)
            ->andWhere('openedDate')->lt($end)
            ->fetchGroup('openedBy', 'id');
        $bugSummary = array();
        foreach($bugGroups as $user => $bugs)
        {
            foreach($bugs as $bug)
            {
                $bugSummary[$user][$bug->resolution] = empty($bugSummary[$user][$bug->resolution]) ? 1 : $bugSummary[$user][$bug->resolution] + 1;
            }
            $bugSummary[$user]['all'] = count($bugs);
        }
        return $bugSummary; 
    }

    public function getWorkload()
    {
        $tasks = $this->dao->select('t1.*, t2.name as projectName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->notin('cancel, closed')
            ->fetchGroup('assignedTo');
        $bugs = $this->dao->select('t1.*, t2.name as productName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->eq('active')
            ->fetchGroup('assignedTo');
        $workload = array();
        foreach($tasks as $user => $userTasks)
        {
            if($user)
            {
                foreach($userTasks as $task)
                {
                    $workload[$user]['task'][$task->projectName]['count']   = isset($workload[$user]['task'][$task->projectName]['count']) ? $workload[$user]['task'][$task->projectName]['count'] + 1 : 1;
                    $workload[$user]['task'][$task->projectName]['manhour'] = isset($workload[$user]['task'][$task->projectName]['manhour']) ? $workload[$user]['task'][$task->projectName]['manhour'] + $task->consumed : $task->consumed;
                }
            }
        }
        foreach($bugs as $user => $userBugs)
        {
            if($user)
            {
                foreach($userBugs as $bug)
                {
                    $workload[$user]['bug'][$bug->productName]['count'] = isset($workload[$user]['bug'][$bug->productName]['count']) ? $workload[$user]['bug'][$bug->productName]['count'] + 1 : 1;
                }
            }
        }
        return $workload;
    }
}
