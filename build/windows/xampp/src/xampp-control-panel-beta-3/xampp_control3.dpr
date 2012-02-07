program xampp_control3;

uses
  Forms,
  ExtCtrls,
  Controls,
  Graphics,
  uMain in 'uMain.pas' {fMain},
  uApache in 'uApache.pas',
  uBaseModule in 'uBaseModule.pas',
  uTools in 'uTools.pas',
  uNetstatTable in 'uNetstatTable.pas',
  uNetstat in 'uNetstat.pas' {fNetstat},
  uConfig in 'uConfig.pas' {fConfig},
  uMySQL in 'uMySQL.pas',
  uFileZilla in 'uFileZilla.pas',
  uMercury in 'uMercury.pas',
  uProcesses in 'uProcesses.pas',
  uHelp in 'uHelp.pas' {fHelp},
  uServices in 'uServices.pas',
  gnugettext in 'gnugettext.pas',
  uLanguage in 'uLanguage.pas' {fLanguage},
  VersInfo in 'VersInfo.pas',
  uConfigUserDefined in 'uConfigUserDefined.pas' {fConfigUserDefined},
  uTomcat in 'uTomcat.pas';

{$R *.res}

begin
  AddDomainForResourceString ('delphi2006_de_en');
  AddDomainForResourceString('xampp_control');
  gnugettext.textdomain('xampp_control');

  TP_GlobalIgnoreClassProperty(TControl,'HelpKeyword');
  TP_GlobalIgnoreClassProperty(TNotebook,'Pages');
  TP_GlobalIgnoreClassProperty(TControl,'ImeName');
  TP_GlobalIgnoreClass(TFont);

  IniFileName:=Application.ExeName;
  IniFileName:=copy(IniFileName,1,length(IniFileName)-3)+'ini';

  LoadSettings;

  if Config.Language='' then begin
    Config.Language:=GetSystemLangShort;
    Application.CreateForm(TfLanguage, fLanguage);
  fLanguage.ShowModal;
    fLanguage.Free;
    SaveSettings;
  end;
  UseLanguage(Config.Language);


  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TfMain, fMain);
  Application.CreateForm(TfNetstat, fNetstat);
  Application.CreateForm(TfConfig, fConfig);
  Application.CreateForm(TfHelp, fHelp);
  Application.CreateForm(TfLanguage, fLanguage);
  Application.CreateForm(TfConfigUserDefined, fConfigUserDefined);
  Application.Run;
end.
