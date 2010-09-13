<style>
.button-c {padding:1px}
</style>
<script language='Javascript'>
/* 删除文件。*/
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('file', 'delete', 'fileID=' + fileID);
}
/* 下载文件。*/
function downloadFile(fileID){
    if(!fileID) return;
    var URL = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    window.open(URL, '_blank');
    return false;
}
</script>

