<?php global $app;?>
<?php include dirname(dirname(__FILE__)) . '/lang/' . $app->getClientLang() . '/lite.php';?>
<script>
window.waitDom('#begin', function(){ $('#begin').closest('div.form-row').hide();})
window.waitDom('#days', function(){ $('#days').closest('div.form-row').hide();})
window.waitDom('input[name=PM]', function(){ $('input[name=PM]').closest('div.form-group').removeClass('w-1/4').addClass('w-1/2');})
window.waitDom('input[name=PO]', function(){ $('input[name=PO]').closest('div.form-group').hide();})
window.waitDom('input[name=QD]', function(){ $('input[name=QD]').closest('div.form-group').hide();})
window.waitDom('input[name=RD]', function(){ $('input[name=RD]').closest('div.form-group').hide();})
window.waitDom('.form-group.linkProduct', function(){ $('.form-group.linkProduct').closest('div.form-row').hide();})
</script>
