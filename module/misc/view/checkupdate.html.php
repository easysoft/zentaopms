<?php error_reporting(0);?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head>
<body <?php if($browser != 'opera') echo "bgcolor='transparent'";?> style='color:white; font-size:13px; text-align:center'>
<?php if($note):?>
<?php echo $note;?>
<script language='javascript'>window.parent.document.getElementById('updater').className=''</script>
<?php endif;?>
</body>
</html>
