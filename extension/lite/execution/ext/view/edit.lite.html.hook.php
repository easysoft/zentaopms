<?php include dirname(dirname(__FILE__)) . '/lang/' . $this->app->getClientLang() . '/lite.php';?>
<script>
$('#aclprivate').closest('td').html(<?php echo json_encode(html::radio('acl', $lang->execution->aclList, $execution->acl, "onclick='setWhite(this.value);'", 'block'));?>)
$('#begin').closest('tr').hide();
$('#days').closest('tr').hide();
$('#productsBox').closest('tr').hide();
$('#plansBox').closest('tr').hide();
$('#PO').closest('.input-group').parent().hide();
$('#QD').closest('.input-group').parent().hide();
$('#RD').closest('.input-group').parent().hide();
$('#PM').closest('.input-group').find('.input-group-addon').hide();
</script>
