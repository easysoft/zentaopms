<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<script>
var chooseUsersToMail    = '<?php echo $lang->chooseUsersToMail;?>';
$(document).ready(function()
{
    $("#mailto").attr('data-placeholder', chooseUsersToMail);
    $("#mailto, #productID").chosen();
});
</script>
