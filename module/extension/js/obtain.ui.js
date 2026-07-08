function searchExtension()
{
    const url  = $.createLink('extension', 'obtain', "type=bysearch");
    const form = new FormData();
    form.append('key', $('#key').val());

   postAndLoadPage(url, form);
}
