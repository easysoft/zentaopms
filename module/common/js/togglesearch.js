<script language='Javascript'>
$(function()
{
/* show the search or reduction the style. */
    $("#bysearchTab").toggle(
      function(){
          if(browseType == 'bymodule')
          {
              $('#treebox').addClass('hidden');
              $('.divider').addClass('hidden');
              $('#bymoduleTab').removeClass('active');
          }
          else
          {
              $('#' + browseType + 'Tab').removeClass('active');
          }
          $('#bysearchTab').addClass('active');
          $('#querybox').removeClass('hidden');
      },
      function(){
          if(browseType == 'bymodule')
          {
              $('#treebox').removeClass('hidden');
              $('.divider').removeClass('hidden');
              $('#bymoduleTab').addClass('active');
          }
          else
          {
              $('#' + browseType +'Tab').addClass('active');
          }
          $('#bysearchTab').removeClass('active');
          $('#querybox').addClass('hidden');
      } 
    );
})
</script>
