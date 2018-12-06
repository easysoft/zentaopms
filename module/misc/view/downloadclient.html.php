<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->downloadClient;?></h2>
  </div>
  <?php if($confirm == 'no'):?>
  <form method='post' target='hiddenwin'>
    <table class='w-p100'>
      <tr>
        <td>
          <?php echo html::radio('os', $lang->misc->client->osList, 'windows64', '', 'block');?>
        </td>
      </tr>
      <tr class='text-center'>
        <td>
          <?php echo html::submitButton($lang->misc->client->download, '', 'btn btn-primary');?>
        </td>
      </tr>
    </table>
  </form>
  <?php endif;?>
  <?php if($confirm == 'yes'):?>
  <?php js::set('os', $os);?>
  <div>
  </div>
  <script>
  $(document).ready(function()
  {
      getClient();
      setInterval("showDownloadProgress()", 2000);
  })
  
  function getClient()
  {
      var link = createLink('misc', 'ajaxGetClient', 'os=' + os);
      $.getJSON(link, function(response)
      {
          if(response.result == 'success')
          {
              downloadClient();
          }
          else
          {
              alert('fail');
          }
      });
  }

  function downloadClient()
  {
      var link = createLink('misc', 'downloadClient', "os=" + os + "&confirm=yes" + "&send=yes");
      location.href = link;
  }

  function showDownloadProgress()
  {
      var link = createLink('misc', 'ajaxGetDownProgress', 'file=' + os);
      $.getJSON(link, function(response)
      {
          if(response.result == 'finished')
          {
          }
          else
          {
              console.log('i');
          }
      });
  }
  </script>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
