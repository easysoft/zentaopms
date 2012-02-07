(*
This work is licensed under the Creative Commons Attribution-ShareAlike 3.0 Unported License.
To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/ or
send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.

Programmed by Steffen Strueber,

Updates:
3.0.2: May 10th 2011, Steffen Strueber
*)

unit uMain;

interface

uses
  GnuGettext, Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, ExtCtrls, ComCtrls, Buttons, uTools, uTomcat,
  uApache, uMySQL, uFileZilla, uMercury, uNetstat, uNetstatTable, Menus,
  IniFiles, uProcesses, AppEvnts, ImgList, JCLDebug ;


type
  TfMain = class(TForm)
    imgXAMPP: TImage;
    lHeader: TLabel;
    bConfig: TBitBtn;
    bSCM: TBitBtn;
    gbModules: TGroupBox;
    bApacheAction: TBitBtn;
    bApacheAdmin: TBitBtn;
    bMySQLAction: TBitBtn;
    bMySQLAdmin: TBitBtn;
    bFileZillaAction: TBitBtn;
    bFileZillaAdmin: TBitBtn;
    bMercuryAction: TBitBtn;
    bMercuryAdmin: TBitBtn;
    sbMain: TStatusBar;
    bQuit: TBitBtn;
    bHelp: TBitBtn;
    bExplorer: TBitBtn;
    pApacheStatus: TPanel;
    TimerUpdateStatus: TTimer;
    TrayIcon1: TTrayIcon;
    bNetstat: TBitBtn;
    puSystray: TPopupMenu;
    miShowHide: TMenuItem;
    miTerminate: TMenuItem;
    N1: TMenuItem;
    bApacheConfig: TBitBtn;
    lPIDs: TLabel;
    lPorts: TLabel;
    lApachePIDs: TLabel;
    bApacheLogs: TBitBtn;
    lApachePorts: TLabel;
    bMySQLConfig: TBitBtn;
    bMySQLLogs: TBitBtn;
    bFileZillaConfig: TBitBtn;
    bFileZillaLogs: TBitBtn;
    bMercuryConfig: TBitBtn;
    reLog: TRichEdit;
    lMySQLPIDs: TLabel;
    lMySQLPorts: TLabel;
    lFileZillaPorts: TLabel;
    lFileZillaPIDs: TLabel;
    lMercuryPorts: TLabel;
    lMercuryPIDs: TLabel;
    pMySQLStatus: TPanel;
    pFileZillaStatus: TPanel;
    pMercuryStatus: TPanel;
    ApplicationEvents1: TApplicationEvents;
    ImageList: TImageList;
    bMySQLService: TBitBtn;
    bFileZillaService: TBitBtn;
    Label1: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    bApacheService: TBitBtn;
    bMercurylogs: TBitBtn;
    puGeneral: TPopupMenu;
    lTomcatPorts: TLabel;
    lTomcatPIDs: TLabel;
    bTomcatAction: TBitBtn;
    bTomcatAdmin: TBitBtn;
    bTomcatConfig: TBitBtn;
    pTomcatStatus: TPanel;
    bTomcatLogs: TBitBtn;
    bTomcatService: TBitBtn;
    bMercuryService: TBitBtn;
    bXamppShell: TBitBtn;
    puTomcatAction: TPopupMenu;
    miActionTomcatAuto: TMenuItem;
    N2: TMenuItem;
    Statuserkennungnichtsicher1: TMenuItem;
    miActionTomcatStop: TMenuItem;
    miActionTomcatStart: TMenuItem;
    procedure FormCreate(Sender: TObject);
    procedure bApacheActionClick(Sender: TObject);
    procedure TimerUpdateStatusTimer(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure bNetstatClick(Sender: TObject);
    procedure miTerminateClick(Sender: TObject);
    procedure bQuitClick(Sender: TObject);
    procedure FormClose(Sender: TObject; var Action: TCloseAction);
    procedure FormCloseQuery(Sender: TObject; var CanClose: Boolean);
    procedure miShowHideClick(Sender: TObject);
    procedure TrayIcon1DblClick(Sender: TObject);
    procedure bExplorerClick(Sender: TObject);
    procedure bSCMClick(Sender: TObject);
    procedure bApacheAdminClick(Sender: TObject);
    procedure bApacheConfigClick(Sender: TObject);
    procedure miGeneralClick(Sender: TObject);
    procedure bConfigClick(Sender: TObject);
    procedure bApacheLogsClick(Sender: TObject);
    procedure miApacheLogsAccessClick(Sender: TObject);
    procedure miApacheLogsErrorClick(Sender: TObject);
    procedure bMySQLActionClick(Sender: TObject);
    procedure bMySQLAdminClick(Sender: TObject);
    procedure bMySQLConfigClick(Sender: TObject);
    procedure bMySQLLogsClick(Sender: TObject);
    procedure bFileZillaActionClick(Sender: TObject);
    procedure bFileZillaAdminClick(Sender: TObject);
    procedure bFileZillaConfigClick(Sender: TObject);
    procedure bFileZillaLogsClick(Sender: TObject);
    procedure bMercuryActionClick(Sender: TObject);
    procedure bMercuryAdminClick(Sender: TObject);
    procedure bMercuryConfigClick(Sender: TObject);
    procedure bHelpClick(Sender: TObject);
    procedure bApacheServiceClick(Sender: TObject);
    procedure bMySQLServiceClick(Sender: TObject);
    procedure bFileZillaServiceClick(Sender: TObject);
    procedure bMercuryServiceClick(Sender: TObject);
    procedure bMercurylogsClick(Sender: TObject);
    procedure bXamppShellClick(Sender: TObject);
    procedure bTomcatConfigClick(Sender: TObject);
    procedure bTomcatLogsClick(Sender: TObject);
    procedure bTomcatActionClick(Sender: TObject);
    procedure bTomcatAdminClick(Sender: TObject);
    procedure miActionTomcatAutoClick(Sender: TObject);
    procedure miActionTomcatStopClick(Sender: TObject);
    procedure miActionTomcatStartClick(Sender: TObject);
    procedure ApplicationEvents1Exception(Sender: TObject; E: Exception);
  private
    Apache: tApache;
    MySQL: tMySQL;
    FileZilla: tFileZilla;
    Mercury: tMercury;
    Tomcat: tTomcat;
    WindowsShutdownInProgress: boolean;
    procedure UpdateStatusAll;
    function TryGuessXamppVersion:string;
    procedure EditConfigLogs(ConfigFile: string);
    procedure GeneralPUClear;
    procedure GeneralPUAdd(text: string=''; hint: string=''; tag: integer=0);
    procedure GeneralPUAddUser(text: string; hint: string='');
    procedure GeneralPUAddUserFromSL(sl: tStringList);
    procedure WMQueryEndSession(var Msg: TWMQueryEndSession); message WM_QueryEndSession; // detect Windows shutdown message
    procedure SaveLogFile;
  public
    procedure AddLog(module,log: string; LogType: tLogType=ltDefault); overload;
    procedure AddLog(log: string; LogType: tLogType=ltDefault);        overload;
  end;

var
  fMain: TfMain;

implementation

uses uConfig, uHelp;

{$R *.dfm}

procedure TfMain.miShowHideClick(Sender: TObject);
begin
  if Visible then begin
    Hide;
    if fMain.WindowState=wsMinimized then fMain.WindowState:=wsNormal;
  end else begin
    Show;
    if fMain.WindowState=wsMinimized then fMain.WindowState:=wsNormal;
    Application.BringToFront;
  end;
end;


procedure TfMain.miGeneralClick(Sender: TObject);
var
  mi: TMenuItem;
  App: string;
begin
  if not (Sender is TMenuItem) then exit;
  mi:=Sender as tMenuItem;
  if mi.Tag=0 then
    EditConfigLogs(mi.Hint);
  if mi.Tag=1 then begin
    App:=BaseDir+mi.Hint;
    ExecuteFile(App,'','',SW_SHOW);
    Addlog(Format(_('Executing "%s"'),[App]));
  end;
end;

procedure TfMain.AddLog(module, log: string; LogType: tLogType=ltDefault);
begin
  if (not Config.ShowDebug) and (LogType=ltDebug) or (LogType=ltDebugDetails) then exit;
  if (LogType=ltDebugDetails) and (Config.DebugLevel=0) then exit;

  with reLog do begin
    SelStart := GetTextLen;

    SelAttributes.Color := clGray;
    SelText := TimeToStr(Now)+'  ';

    SelAttributes.Color := clBlack;
    SelText := '[';

    SelAttributes.Color := clBlue;
    SelText := module;

    SelAttributes.Color := clBlack;
    SelText := '] '+#9;


    case logtype of
      ltDefault: SelAttributes.Color := clBlack;
      ltInfo:    SelAttributes.Color := clBlue;
      ltError:   SelAttributes.Color := clRed;
      ltDebug:   SelAttributes.Color := clGray;
      ltDebugDetails:   SelAttributes.Color := clSilver;
    end;

    SelText := Log+#13;

//    SelStart := GetTextLen;
    SendMessage(Handle, EM_SCROLLCARET,0,0);
  end;

end;

procedure TfMain.AddLog(log: string; LogType: tLogType=ltDefault);
begin
  AddLog('main',log,LogType);
end;

procedure TfMain.ApplicationEvents1Exception(Sender: TObject; E: Exception);
var ts:tSTringList;
    i:integer;
begin
//  GlobalAddLog(Format('Exception in thread: %d / %s', [Thread.ThreadID, JclDebugThreadList.ThreadClassNames[Thread.ThreadID]]),0,'LogException');
  // Note: JclLastExceptStackList always returns list for *current* thread ID. To simplify getting the
  // stack of thread where an exception occured JclLastExceptStackList returns stack of the thread instead
  // of current thread when called *within* the JclDebugThreadList.OnSyncException handler. This is the
  // *only* exception to the behavior of JclLastExceptStackList described above.
  ts:=TStringList.Create;

  AddLog('EXCEPTION',E.Message,ltError);

  JclLastExceptStackList.AddToStrings(ts, True, True, True);
  for i:=0 to ts.count-1 do
    AddLog('EXCEPTION',ts[i],ltError);
  ts.Free;
end;

procedure TfMain.miActionTomcatAutoClick(Sender: TObject);
begin
  if Tomcat.isRunning then Tomcat.Stop
  else Tomcat.Start;
end;

procedure TfMain.miActionTomcatStartClick(Sender: TObject);
begin
  Tomcat.Start;
end;

procedure TfMain.miActionTomcatStopClick(Sender: TObject);
begin
  Tomcat.Stop
end;

procedure TfMain.bApacheActionClick(Sender: TObject);
begin
  if Apache.isRunning then Apache.Stop
  else Apache.Start;
end;

procedure TfMain.miTerminateClick(Sender: TObject);
begin
  Application.Terminate;
end;


procedure TfMain.SaveLogFile;
var
  en: string;
  LogFileName: string;
  f: TextFile;
  i: Integer;
begin
  en:=ExtractFileName(Application.ExeName);
  while (length(en)>0) and (en[length(en)]<>'.') do en:=copy(en,1,length(en)-1);
  LogFileName:=Basedir+en+'log';
  AssignFile(f,LogFileName);
  if FileExists(LogFileName) then Append(f)
  else Rewrite(f);
  for i:=0 to reLog.Lines.Count-1 do
    Writeln(f,reLog.Lines[i]);
  Writeln(f,'');
  CloseFile(f);
end;

procedure TfMain.miApacheLogsAccessClick(Sender: TObject);
begin
  Apache.ShowLogs(altAccess);
end;

procedure TfMain.miApacheLogsErrorClick(Sender: TObject);
begin
  Apache.ShowLogs(altError);
end;

procedure TfMain.bQuitClick(Sender: TObject);
begin
  miTerminateClick(Sender);
end;

procedure TfMain.bExplorerClick(Sender: TObject);
var
  App: string;
begin
  App:=BaseDir;
  ExecuteFile(App,'','',SW_SHOW);
  Addlog(Format(_('Executing "%s"'),[App]));
end;

procedure TfMain.bFileZillaActionClick(Sender: TObject);
begin
  if FileZilla.isService then begin
    if FileZilla.isRunning then FileZilla.Stop
    else FileZilla.Start;
  end else begin
    MessageDlg(_('FileZilla must be run as service!'),mtInformation,[mbOK],0);
  end;
end;

procedure TfMain.bFileZillaAdminClick(Sender: TObject);
begin
  FileZilla.Admin;
end;

procedure TfMain.bFileZillaConfigClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('FileZilla Server.xml','FileZillaFTP\FileZilla Server.xml');
  GeneralPUAddUserFromSL(Config.UserConfig.FileZilla);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'FileZillaFTP',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bFileZillaLogsClick(Sender: TObject);
begin
  GeneralPUClear;
//  GeneralPUAdd('error.log','mysql\data\mysql_error.log');
  GeneralPUAddUserFromSL(Config.UserLogs.FileZilla);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bFileZillaServiceClick(Sender: TObject);
var oldIsService: boolean;
begin
  oldIsService:=FileZilla.isService;

  if FileZilla.isRunning then begin
    MessageDlg(_('Services cant be installed or uninstalled while service is running!'),mtError,[mbOk],0);
    exit;
  end;
  if FileZilla.isService then begin
    if MessageDlg(Format(_('Click Yes to uninstall the %s service'),[FileZilla.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      FileZilla.ServiceUnInstall
    else
      exit;
  end else begin
    if MessageDlg(Format(_('Click Yes to install the "%s" service'),[FileZilla.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      FileZilla.ServiceInstall
    else
      exit;
  end;
  FileZilla.CheckIsService;
  if (oldIsService=FileZilla.isService) then begin
    FileZilla.AddLog(_('Service was NOT (un)installed!'),ltError);
    if (WinVersion.Major=5) then   // WinXP
      FileZilla.AddLog(_('One possible reason for failure: On windows security box you !!!MUST UNCHECK!!! that "Protect my computer and data from unauthorized program activity" checkbox!!!'),ltError);
  end;
end;

procedure TfMain.bHelpClick(Sender: TObject);
begin
  fHelp.Show;
end;

procedure TfMain.bApacheServiceClick(Sender: TObject);
var oldIsService: boolean;
begin
  oldIsService:=Apache.isService;

  if Apache.isRunning then begin
    MessageDlg(_('Services cant be installed or uninstalled while service is running!'),mtError,[mbOk],0);
    exit;
  end;

  if Apache.isService then begin
    if MessageDlg(Format(_('Click Yes to uninstall the %s service'),[Apache.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      Apache.ServiceUnInstall
    else
      exit;
  end else begin
    if MessageDlg(Format(_('Click Yes to install the "%s" service'),[Apache.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      Apache.ServiceInstall
    else
      exit;
  end;
  Apache.CheckIsService;
  if (oldIsService=Apache.isService) then begin
    Apache.AddLog(_('Service was NOT (un)installed!'),ltError);
    if (WinVersion.Major=5) then   // WinXP
      Apache.AddLog(_('One possible reason for failure: On windows security box you !!!MUST UNCHECK!!! that "Protect my computer and data from unauthorized program activity" checkbox!!!'),ltError);
  end else begin
    Apache.AddLog(_('Successful!'));
  end;
end;

procedure TfMain.bMercuryActionClick(Sender: TObject);
begin
  if Mercury.isRunning then Mercury.Stop
  else Mercury.Start;
end;

procedure TfMain.bMercuryAdminClick(Sender: TObject);
begin
  Mercury.Admin;
end;

procedure TfMain.bMercuryConfigClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('mercury.ini','MercuryMail\mercury.ini');
  GeneralPUAddUserFromSL(Config.UserConfig.Mercury);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'MercuryMail',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bMercurylogsClick(Sender: TObject);
begin
 GeneralPUClear;
//  GeneralPUAdd('error.log','mysql\data\mysql_error.log');
  GeneralPUAddUserFromSL(Config.UserLogs.Mercury);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bMercuryServiceClick(Sender: TObject);
begin
  MessageDlg(_('Mercury cant be run as service!'),mtError,[mbOk],0);
end;

procedure TfMain.bMySQLActionClick(Sender: TObject);
begin
  if MySQL.isRunning then MySQL.Stop
  else MySQL.Start;
end;

procedure TfMain.bMySQLAdminClick(Sender: TObject);
begin
  MySQL.Admin;
end;

procedure TfMain.bMySQLConfigClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('my.ini','mysql\bin\my.ini');
  GeneralPUAddUserFromSL(Config.UserConfig.MySQL);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'mysql',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bMySQLLogsClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('error.log','mysql\data\mysql_error.log');
  GeneralPUAddUserFromSL(Config.UserLogs.MySQL);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'mysql\data',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bMySQLServiceClick(Sender: TObject);
var oldIsService: boolean;
begin
  oldIsService:=Apache.isService;

  if MySQL.isRunning then begin
    MessageDlg(_('Services cant be installed or uninstalled while service is running!'),mtError,[mbOk],0);
    exit;
  end;
  if MySQL.isService then begin
    if MessageDlg(Format(_('Click Yes to uninstall the %s service'),[MySQL.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      MySQL.ServiceUnInstall
    else
      exit;
  end else begin
    if MessageDlg(Format(_('Click Yes to install the "%s" service'),[MySQL.ModuleName]),mtConfirmation,[mbYes, mbNo],0)=mrYes then
      MySQL.ServiceInstall
    else
      exit;
  end;
  MySQL.CheckIsService;
  if (oldIsService=MySQL.isService) then begin
    MySQL.AddLog(_('Service was NOT (un)installed!'),ltError);
    if (WinVersion.Major=5) then   // WinXP
      MySQL.AddLog(_('One possible reason for failure: On windows security box you !!!MUST UNCHECK!!! that "Protect my computer and data from unauthorized program activity" checkbox!!!'),ltError);
  end;
end;

procedure TfMain.bConfigClick(Sender: TObject);
begin
  fConfig.Show;
end;

procedure TfMain.bSCMClick(Sender: TObject);
var
  App: string;
begin
  App:='services.msc';
  ExecuteFile(App,'','',SW_SHOW);
  Addlog(Format(_('Executing "%s"'),[App]));
end;

procedure TfMain.bTomcatActionClick(Sender: TObject);
begin
  if Tomcat.isRunning then begin
    miActionTomcatAuto.Caption:='Stop';
    miActionTomcatStop.Visible:=false;
    miActionTomcatStart.Visible:=true;
  end else begin
    miActionTomcatAuto.Caption:='Start';
    miActionTomcatStop.Visible:=true;
    miActionTomcatStart.Visible:=false;
  end;

  puTomcatAction.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
//  if Tomcat.isRunning then Tomcat.Stop
//  else Tomcat.Start;
end;

procedure TfMain.bTomcatAdminClick(Sender: TObject);
begin
  Tomcat.Admin;
end;

procedure TfMain.bTomcatConfigClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('server.xml','Tomcat\conf\server.xml');
  GeneralPUAdd('tomcat-users.xml','Tomcat\conf\tomcat-users.xml');
  GeneralPUAdd('web.xml','Tomcat\conf\web.xml');
  GeneralPUAdd('context.xml','Tomcat\conf\context.xml');
  GeneralPUAddUserFromSL(Config.UserConfig.Tomcat);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'Tomcat\conf',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bTomcatLogsClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAddUserFromSL(Config.UserLogs.Tomcat);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'tomcat\logs',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bXamppShellClick(Sender: TObject);
const
  cBatchFileContents=
    '@ECHO OFF'+cr+
    ''+cr+
    'GOTO weiter'+cr+
    ':setenv'+cr+
    'SET "MIBDIRS=%~dp0php\extras\mibs"'+cr+
    'SET "MIBDIRS=%MIBDIRS:\=/%"'+cr+
    'SET "MYSQL_HOME=%~dp0mysql\bin"'+cr+
    'SET "OPENSSL_CONF=%~dp0apache\bin\openssl.cnf"'+cr+
    'SET "OPENSSL_CONF=%OPENSSL_CONF:\=/%"'+cr+
    'SET "PHP_PEAR_SYSCONF_DIR=%~dp0php"'+cr+
    'SET "PHPRC=%~dp0php"'+cr+
    'SET "TMP=%~dp0tmp"'+cr+
    'SET "PERL5LIB="'+cr+
    'SET "Path=%~dp0;%~dp0php;%~dp0perl\site\bin;%~dp0perl\bin;%~dp0apache\bin;%~dp0mysql\bin;%~dp0FileZillaFTP;%~dp0MercuryMail;%~dp0sendmail;%~dp0webalizer;%~dp0tomcat\bin;%Path%"'+cr+
    'GOTO :EOF'+cr+
    ':weiter'+cr+
    ''+cr+
    'IF "%1" EQU "setenv" ('+cr+
    '    ECHO.'+cr+
    '    ECHO Setting environment for using XAMPP for Windows.'+cr+
    '    CALL :setenv'+cr+
    ') ELSE ('+cr+
    '    SETLOCAL'+cr+
    '    TITLE XAMPP for Windows'+cr+
    '    PROMPT %username%@%computername%$S$P$_#$S'+cr+
    '    START "" /B %COMSPEC% /K "%~f0" setenv'+cr+
    ')';
  cFilename = 'xampp_shell.bat';
var
  ts: TStringList;
  batchfile: string;
begin
  batchfile:=BaseDir+cFilename;
  if not fileexists(BatchFile) then begin
    if MessageDlg(Format(_('File "%s" not found. Should it be created now?'),[BatchFile]),mtConfirmation,[mbYes, mbAbort],0)<>mrYes then exit;
    ts:=TStringList.Create;
    ts.Text:=cBatchFileContents;
    try
      ts.SaveToFile(batchfile);
    except
      on e:exception do begin
        MessageDlg(_('Error')+': '+E.Message,mtError,[mbOK],0);
      end;
    end;
    ts.Free;
  end;
  ExecuteFile(batchfile,'','',SW_SHOW);
end;

procedure TfMain.GeneralPUAdd(text: string=''; hint: string=''; tag: integer=0);
var
  mi: TMenuItem;
begin
  mi:=TMenuItem.Create(puGeneral);
  mi.Caption:=text;
  if text='' then begin
    mi.Caption:='-';
  end else begin
    mi.Hint:=hint;
    mi.Tag:=tag;
    mi.OnClick:=miGeneralClick;
  end;
  puGeneral.Items.Add(mi);
end;

procedure TfMain.GeneralPUAddUser(text: string; hint: string='');
var
  myCaption: TranslatedUnicodeString;
  mi, miMain: TMenuItem;
begin
  myCaption:=_('User defined');
  miMain:=puGeneral.Items.Find(myCaption);
  if miMain=nil then begin
    GeneralPUAdd();
    miMain:=TMenuItem.Create(puGeneral);
    miMain.Caption:=myCaption;
    puGeneral.Items.Add(miMain);
  end;

  mi:=TMenuItem.Create(miMain);
  mi.Caption:=text;
  if hint<>'' then mi.Hint:=hint
  else mi.Hint:=text;
  mi.OnClick:=miGeneralClick;
  miMain.Add(mi);
end;

procedure TfMain.GeneralPUAddUserFromSL(sl: tStringList);
var
  i: Integer;
begin
  for i:=0 to sl.Count-1 do
    GeneralPUAddUser(sl[i]);
end;

procedure TfMain.GeneralPUClear;
begin
  puGeneral.Items.Clear;
end;

procedure TfMain.EditConfigLogs(ConfigFile: string);
var
  App, Param: string;
begin
  App:=Config.EditorApp;
  Param:=BaseDir+ConfigFile;
  Addlog(Format(_('Executing %s %s'),[App,Param]),ltDebug);
  ExecuteFile(app,param,'',SW_SHOW);
end;

procedure TfMain.bNetstatClick(Sender: TObject);
begin
  fNetStat.Show;
  fNetstat.RefreshTable(true);
end;

procedure TfMain.bApacheAdminClick(Sender: TObject);
begin
  Apache.Admin;
end;

procedure TfMain.bApacheConfigClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('Apache (httpd.conf)','apache/conf/httpd.conf');
  GeneralPUAdd('PHP (php.ini)','php/php.ini');
  GeneralPUAddUserFromSL(Config.UserConfig.Apache);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>')+' [Apache]','apache',1);
  GeneralPUAdd(_('<Browse>')+' [PHP]','php',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.bApacheLogsClick(Sender: TObject);
begin
  GeneralPUClear;
  GeneralPUAdd('access.log','apache\logs\access.log');
  GeneralPUAdd('error.log','apache\logs\access.log');
  GeneralPUAddUserFromSL(Config.UserLogs.Apache);
  GeneralPUAdd();
  GeneralPUAdd(_('<Browse>'),'apache\logs',1);
  puGeneral.Popup(Mouse.CursorPos.X,Mouse.CursorPos.Y);
end;

procedure TfMain.FormClose(Sender: TObject; var Action: TCloseAction);
begin
  Action:=caHide;
end;

procedure TfMain.FormCloseQuery(Sender: TObject; var CanClose: Boolean);
begin
  if WindowsShutdownInProgress then begin
    CanClose:=true;
  end else begin
    CanClose:=false;
    Hide;
  end;
end;

procedure TfMain.FormCreate(Sender: TObject);
var
  isAdmin: Boolean;
  xamppVersion: string;
  CCVersion: string;
begin
  TranslateComponent(Self);

  BaseDir:=LowerCase(ExtractFilePath(Application.ExeName));
  if GetComputerName='STRUEBER' then BaseDir:='c:\xampp';

  AddLog(_('Initializing main'));

  left:=screen.WorkAreaWidth-Width;
  top:=screen.WorkAreaHeight-Height;

  WinVersion:=GetWinVersion;
  WindowsShutdownInProgress:=false;
  AddLog(Format(_('Windows version: %s'),[WinVersion.WinVersion]));
  xamppVersion:=TryGuessXamppVersion;
  AddLog('Xampp version: '+xamppVersion);

  if cCompileDate<>'' then CCVersion:=GlobalProgramversion+Format(' [ Compiled: %s ]',[cCompileDate])
  else CCVersion:=GlobalProgramversion;
  AddLog('Control center version: '+CCVersion);

  Caption:='XAMPP Control Panel v'+GlobalProgramversion+Format('  [ Compiled: %s ]',[cCompileDate]);
  lHeader.Caption:='XAMPP Control Panel v'+GlobalProgramversion;

  CurrentUser:=GetCurrentUserName;
  isAdmin:=IsWindowsAdmin;
  if isAdmin then begin
    AddLog(_('Running as admin - good!'));
  end else begin
    AddLog(_('Running not as admin! This will work for all application stuff, but whenever you do'),ltInfo);
    AddLog(_('something with services there will be a security dialogue! So think about running'),ltInfo);
    AddLog(_('this application with administrator rights!'),ltInfo);
  end;

  AddLog(Format(_('Working with basedir: "%s"'),[BaseDir]));

  if BaseDir[length(BaseDir)]<>'\' then BaseDir:=BaseDir+'\';

  NetStatTable.UpdateTable;
  Processes.Update;

  AddLog(_('Initializing moduls'));
  Apache:=tApache.Create(       bApacheService,    pApacheStatus,    lApachePIDs,    lApachePorts,     bApacheAction,    bApacheAdmin);
  MySQL:=tMySQL.Create(         bMySQLService,     pMySQLStatus,     lMySQLPIDs,     lMySQLPorts,      bMySQLAction,     bMySQLAdmin);
  FileZilla:=tFileZilla.Create( bFileZillaService, pFileZillaStatus, lFileZillaPIDs, lFileZillaPorts,  bFileZillaAction, bFileZillaAdmin);
  Mercury:=tMercury.Create(     bMercuryService,   pMercuryStatus,   lMercuryPIDs,   lMercuryPorts,    bMercuryAction,   bMercuryAdmin);
  Tomcat:=tTomcat.Create(       bTomcatService,    pTomcatStatus,    lTomcatPIDs,    lTomcatPorts,     bTomcatAction,    bTomcatAdmin);

  if Config.ASApache then begin
    Apache.AutoStart:=true;
    AddLog(Format(_('Enabling autostart for module "%s"'),[Apache.ModuleName]));
  end;
  if Config.ASMySQL then begin
    MySQL.AutoStart:=true;
    AddLog(Format(_('Enabling autostart for module "%s"'),[MySQL.ModuleName]));
  end;
  if Config.ASFileZilla then begin
    FileZilla.AutoStart:=true;
    AddLog(Format(_('Enabling autostart for module "%s"'),[FileZilla.ModuleName]));
  end;
  if Config.ASMercury then begin
    Mercury.AutoStart:=true;
    AddLog(Format(_('Enabling autostart for module "%s"'),[Mercury.ModuleName]));
  end;
  if Config.ASTomcat then begin
    Tomcat.AutoStart:=true;
    AddLog(Format(_('Enabling autostart for module "%s"'),[Tomcat.ModuleName]));
  end;


  AddLog(_('Starting')+' check-timer');
  TimerUpdateStatus.Enabled:=true;
//  UpdateStatusAll;
end;


procedure TfMain.FormDestroy(Sender: TObject);
begin
  AddLog(_('Deinitializing moduls'));
  Apache.Free;
  MySQL.Free;
  FileZilla.Free;
  Mercury.Free;
  AddLog(_('Deinitializing main'));
  SaveLogFile;
end;

procedure TfMain.TimerUpdateStatusTimer(Sender: TObject);
begin
  UpdateStatusAll;
end;

procedure TfMain.TrayIcon1DblClick(Sender: TObject);
begin
  miShowHideClick(nil);
end;


function TfMain.TryGuessXamppVersion: string;
var ts: TStringList;
  s: string;
  p: Integer;
begin
  result:='???';
  ts:=TStringList.Create;
  try
    ts.LoadFromFile(BaseDir+'\readme_de.txt');
    if ts.Count<1 then exit;
    s:=LowerCase(ts[0]);
    p:=pos('version',s);
    if p=0 then exit;
    delete(s,1,p+7);
    p:=pos(' ',s);
    if p=0 then exit;
    result:=copy(s,1,p-1);
  except
  end;
  ts.Free;
end;

procedure DumpProcesses;
var ProcInfo: TProcInfo;
  p: Integer;
  s: string;
begin
  for p:=0 to Processes.ProcessList.Count-1 do begin
    ProcInfo:=Processes.ProcessList[p];
    s:=Format('%d %s',[ProcInfo.PID,ProcInfo.ExePath]);
    fMain.reLog.Lines.Add(s)
  end;
end;

procedure TfMain.UpdateStatusAll;
begin
  Processes.Update;
  NetStatTable.UpdateTable;
//  DumpProcesses;

  // 1. Check Apache
  Apache.UpdateStatus;

  // 2. Check MySql
  MySQL.UpdateStatus;

  // 3. Check Filezilla
  FileZilla.UpdateStatus;

  // 4. Check Mercury
  Mercury.UpdateStatus;

  // 5. Check Mercury
  Tomcat.UpdateStatus;
end;

procedure TfMain.WMQueryEndSession(var Msg: TWMQueryEndSession);
begin
  WindowsShutdownInProgress:=true;
  inherited;
end;

end.
