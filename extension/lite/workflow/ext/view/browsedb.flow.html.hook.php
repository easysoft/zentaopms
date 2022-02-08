<style>
.col-md-5 .main-table td.actions .btn{
  display: inline-block;
  width: 26px;
  padding: 2px;
  overflow: hidden;
  line-height: 20px;
  color: #16a8f8;
  background: 0 0;
  border-color: transparent;
}
</style>
<script>
$(function()
{
    $('tr.data-url').removeAttr('data-url');
    var html = $('.col-md-5 .main-table td.actions a.fields:first').html();
    $('.col-md-5 .main-table td.actions a.fields').attr('title', html).addClass('btn').html("<i class='icon icon-fields'></i>");

    var html = $('.col-md-5 .main-table td.actions a.edit:first').html();
    $('.col-md-5 .main-table td.actions a.edit').attr('title', html).addClass('btn').html("<i class='icon icon-edit'></i>");

    var html = $('.col-md-5 .main-table td.actions a.deleter:first').html();
    $('.col-md-5 .main-table td.actions a.deleter').attr('title', html).addClass('btn').html("<i class='icon icon-trash'></i>");
    $('.col-md-5 .main-table tr').removeAttr('data-url');
})
</script>
