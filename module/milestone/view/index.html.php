<?php include '../../common/view/header.html.php';?>
<style>
table .btn.addItem, table .btn.delItem{padding: 3px 8px; font-size: 12px; line-height: 18px; border-radius: 4px;}
.measures-input{width: 80%; float: left; margin-right: 5px;}
.main-table tbody>tr>td:first-child{padding-left: 8px;}
.angle-btn+.angle-btn{ border-left: 1px  solid #0c64eb;}
</style>
<?php if($projectID):?>
<div id="mainContent" class="main-row fade in">
  <div class="main-col">
    <div class="main-table">
    <?php include 'basicinfo.html.php';?>
    <?php include 'process.html.php';?>
    <?php include 'chart.html.php';?>
    <?php include 'productquality.html.php';?>
    <?php include 'workhour.html.php';?>
    <?php include 'progress.html.php';?>
    <?php include 'rectifying.html.php';?>
    <?php include 'condition.html.php';?>
    <?php include 'projectrisk.html.php';?>
    <?php include 'otherproblem.html.php';?>
    </div>
  </div>
</div>
<?php js::set('measuresUrl', $this->createLink('milestone', 'ajaxAddMeasures'));?>
<script>
function addItem(add)
{
    var rowspan = $("#measuresTd").attr("rowspan");
    $("#measuresTd").attr("rowspan", Number(rowspan) + 1);
    $(add).parent().parent().after('<tr>' + $("#measuresDiv").html() + '</tr>');
    submitMeasurse();
}

function delItem(del)
{
    if($(del).attr('id')) return false;
    var rowspan = $("#measuresTd").attr("rowspan");
    if(rowspan == '1') return false;
    $("#measuresTd").attr("rowspan", Number(rowspan) - 1);
    $(del).parent().parent().remove();
    submitMeasurse();
}

function submitMeasurse()
{
    $.ajax(
    {
        type: "post",
        url:  measuresUrl,
        data: $("#ajaxFormMeasures").serialize(),
        success: function(data)
        {

        }
    });
}
</script>
<?php else:?>
<div class='main-col'>
  <div class="table-empty-tip">
   <p><?php echo $lang->noData;?></p>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
