<script>
$("#createCaseActionMenu").parent('.btn-group').remove();
$(".histories-list li a").not("[href*='feedback']").attr('href','javascript:void(0)').attr('disabled',"true").css('color', '#3c4353');
</script>
