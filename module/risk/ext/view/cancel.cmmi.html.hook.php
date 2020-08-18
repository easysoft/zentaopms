<?php 
include $this->app->getModuleRoot() . 'risk/ext/lang/zh-cn/cmmi.php';
$html  = "<tr>";
$html .= "<th>{$lang->risk->comment}</th>";
$html .= "<td>";
$html .= html::textarea('comment', '', 'class="form-control" rows="5"');
$html .= "</td></tr>";
?>

<script>
$('#cancelReason').closest('tr').after(<?php echo json_encode($html);?>);
</script>

<?php include '../../common/view/kindeditor.html.php';?>
