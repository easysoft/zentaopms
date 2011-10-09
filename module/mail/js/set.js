function setMtaType(type)
{
  if(type == 'gmail')
  {
    $('#gmailUsername').show();
    $('#gmailPassword').show();
    $('#gmailDebug').show();
    $('#smtpDebug').hide();
    $('#smtpUsername').hide();
    $('#smtpPassword').hide();
    $('#smtpHost').hide();
    $('#smtpAuth').hide();
    $('#smtpSecure').hide();
    $('#smtpPort').hide();
  }
  else if(type == 'smtp')
  {
    $('#gmailUsername').hide();
    $('#gmailPassword').hide();
    $('#gmailDebug').hide();
    $('#smtpDebug').show();
    $('#smtpUsername').show();
    $('#smtpPassword').show();
    $('#smtpHost').show();
    $('#smtpAuth').show();
    $('#smtpSecure').show();
    $('#smtpPort').show();
  }
  else if(type == 'phpmail' || type == 'sendmail')
  {
    $('#gmailUsername').hide();
    $('#gmailPassword').hide();
    $('#gmailDebug').hide();
    $('#smtpDebug').hide();
    $('#smtpUsername').hide();
    $('#smtpPassword').hide();
    $('#smtpHost').hide();
    $('#smtpAuth').hide();
    $('#smtpSecure').hide();
    $('#smtpPort').hide();
  }
}
function setVerificationType(type)
{
  if(type == 'true')
  {
    $('#smtpUsername').show(); 
    $('#smtpPassword').show(); 
  }
  else if(type == 'false')
  {
    $('#smtpUsername').hide(); 
    $('#smtpPassword').hide(); 
  }
}
type = document.getElementById("mta")
setMtaType(type.value);
