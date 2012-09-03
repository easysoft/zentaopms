unit uTools;

interface

uses GnuGettext, Classes, Graphics, Windows, SysUtils, TlHelp32, ShellAPI, Forms, Dialogs, IniFiles, VersInfo;

const
//  cIdxApache = 1;
//  cIdxMySQL = 2;
//  cIdxFileZilla = 3;
//  cIdxMercury = 4;
  cRunningColor = 200 * $10000 + 255*$100 + 200;
  cStoppedColor = clBtnFace;
//  SW_HIDE = Windows.SW_HIDE;

  cCompileDate='May 14th 2011 - build #1';
  cr=#13#10;


type
  tLogType = (ltDefault, ltInfo, ltError, ltDebug, ltDebugDetails);

  TWinVersion = record
    WinPlatForm: Byte;        // VER_PLATFORM_WIN32_NT, VER_PLATFORM_WIN32_WINDOWS
    WinVersion: string;
    Major: Cardinal;
    Minor: Cardinal;
  end;

  tConfig = class
    EditorApp: string;
    BrowserApp: string;
    ShowDebug: boolean;
    DebugLevel: integer;
    CheckDefaultPorts: boolean;
    Language: string;
    TomcatVisible: boolean;

    ASApache: boolean;
    ASMySQL: boolean;
    ASFileZilla: boolean;
    ASMercury: boolean;
    ASTomcat: boolean;

    UserLogs: record
      Apache: tStringList;
      MySQL: tStringList;
      FileZilla: tStringList;
      Mercury: tStringList;
      Tomcat: tStringList;
    end;
    UserConfig: record
      Apache: tStringList;
      MySQL: tStringList;
      FileZilla: tStringList;
      Mercury: tStringList;
      Tomcat: tStringList;
    end;

    constructor Create;
    destructor Destroy; override;
  end;

var
   WinVersion: TWinVersion;
   CurrentUser: string;
   BaseDir: string;
   CachedComputerName: string;
   Config: tConfig;
   IniFileName: string;
   dfsVersionInfoResource1: TdfsVersionInfoResource;
   GlobalProgramversion: string;

procedure LoadSettings;
procedure SaveSettings;
function IsWindowsAdmin: Boolean;
function IntToServiceApp(b: boolean):string;
function GetCurrentUserName: string;
function GetWinVersion: TWinVersion;
function RunProcess(FileName: string; ShowCmd: DWORD; wait: Boolean; ProcID: PCardinal=nil): Longword;
function ExecuteFile(FileName, Params, DefaultDir: string; ShowCmd: Integer): THandle;
function RunAsAdmin(filename, parameters: string; ShowCmd: Integer): Cardinal;
function Cardinal2IP(C: Cardinal):string;
function GetComputerName:string;
function SystemErrorMessage(WinErrorCode: Cardinal): string;
function GetSystemLangShort:string;


implementation

procedure LoadSettings;
var mi: TMemIniFile;
begin
//  fMain.AddLog('Loading settings from configurationfile: "'+IniFileName+'"',ltDebug);

  mi:=nil;
  try
    mi:=TMemIniFile.Create(IniFileName);
    Config.EditorApp:=mi.ReadString('Common','Editor','notepad.exe');
    Config.BrowserApp:=mi.ReadString('Common','Browser','');
    Config.ShowDebug:=mi.ReadBool('Common','Debug',false);
    Config.DebugLevel:=mi.ReadInteger('Common','Debuglevel',0);
    Config.CheckDefaultPorts:=mi.ReadBool('Common','CheckDefaultPorts',true);
    Config.Language:=mi.ReadString('Common','Language','');
    Config.TomcatVisible:=mi.ReadBool('Common','TomcatVisible',true);

    Config.ASApache:=mi.ReadBool('Autostart','Apache',false);
    Config.ASMySQL:=mi.ReadBool('Autostart','MySQL',false);
    Config.ASFileZilla:=mi.ReadBool('Autostart','FileZilla',false);
    Config.ASMercury:=mi.ReadBool('Autostart','Mercury',false);
    Config.ASTomcat:=mi.ReadBool('Autostart','Tomcat',false);

    Config.UserConfig.Apache.DelimitedText:=mi.ReadString('UserConfigs','Apache','');
    Config.UserConfig.MySQL.DelimitedText:=mi.ReadString('UserConfigs','MySQL','');
    Config.UserConfig.FileZilla.DelimitedText:=mi.ReadString('UserConfigs','FileZilla','');
    Config.UserConfig.Mercury.DelimitedText:=mi.ReadString('UserConfigs','Mercury','');
    Config.UserConfig.Tomcat.DelimitedText:=mi.ReadString('UserConfigs','Tomcat','');

    Config.UserLogs.Apache.DelimitedText:=mi.ReadString('UserLogs','Apache','');
    Config.UserLogs.MySQL.DelimitedText:=mi.ReadString('UserLogs','MySQL','');
    Config.UserLogs.FileZilla.DelimitedText:=mi.ReadString('UserLogs','FileZilla','');
    Config.UserLogs.Mercury.DelimitedText:=mi.ReadString('UserLogs','Mercury','');
    Config.UserLogs.Tomcat.DelimitedText:=mi.ReadString('UserLogs','Tomcat','');
  except
    on e:exception do begin
      MessageDlg(_('Error')+': '+E.Message,mtError,[mbOK],0);
    end;
  end;
  mi.Free;
end;

procedure SaveSettings;
var mi: TMemIniFile;
begin
  mi:=nil;
  try
    mi:=TMemIniFile.Create(IniFileName);
    mi.WriteString('Common','Editor',Config.EditorApp);
    mi.WriteString('Common','Browser',Config.BrowserApp);
    mi.WriteBool('Common','Debug',Config.ShowDebug);
    mi.WriteInteger('Common','Debuglevel',Config.DebugLevel);
    mi.WriteBool('Common','CheckDefaultPorts',Config.CheckDefaultPorts);
    mi.WriteString('Common','Language',Config.Language);
    mi.WriteBool('Common','TomcatVisible',Config.TomcatVisible);

    mi.WriteBool('Autostart','Apache',Config.ASApache);
    mi.WriteBool('Autostart','MySQL',Config.ASMySQL);
    mi.WriteBool('Autostart','FileZilla',Config.ASFileZilla);
    mi.WriteBool('Autostart','Mercury',Config.ASMercury);
    mi.WriteBool('Autostart','Tomcat',Config.ASTomcat);

    mi.WriteString('UserConfigs','Apache',Config.UserConfig.Apache.DelimitedText);
    mi.WriteString('UserConfigs','MySQL',Config.UserConfig.MySQL.DelimitedText);
    mi.WriteString('UserConfigs','FileZilla',Config.UserConfig.FileZilla.DelimitedText);
    mi.WriteString('UserConfigs','Mercury',Config.UserConfig.Mercury.DelimitedText);
    mi.WriteString('UserConfigs','Tomcat',Config.UserConfig.Tomcat.DelimitedText);

    mi.WriteString('UserLogs','Apache',Config.UserLogs.Apache.DelimitedText);
    mi.WriteString('UserLogs','MySQL',Config.UserLogs.MySQL.DelimitedText);
    mi.WriteString('UserLogs','FileZilla',Config.UserLogs.FileZilla.DelimitedText);
    mi.WriteString('UserLogs','Mercury',Config.UserLogs.Mercury.DelimitedText);
    mi.WriteString('UserLogs','Tomcat',Config.UserLogs.Tomcat.DelimitedText);

    mi.UpdateFile;
  except
    on e:exception do begin
      MessageDlg(_('Error')+': '+E.Message,mtError,[mbOK],0);
    end;
  end;
  mi.Free;
end;


function IsWindowsAdmin:boolean;
type
  TIsUserAnAdminFunc = function (): BOOL; stdcall;
var
  Shell32DLL: THandle;
  IsUserAnAdminFunc: TIsUserAnAdminFunc;
begin
  result:=true;
  if WinVersion.Major<6 then exit; // older than vista? just return TRUE
  
  Shell32DLL := LoadLibrary('shell32.dll');
  try
    if Shell32DLL <> 0 then begin
      @IsUserAnAdminFunc := GetProcAddress(Shell32DLL, 'IsUserAnAdmin');
      if Assigned(@IsUserAnAdminFunc) then
        result := IsUserAnAdminFunc();
    end;
  except
  end;
  FreeLibrary(Shell32DLL);
end;

function IntToServiceApp(b: boolean):string;
begin
  if b then result:=_('Service')
  else result:=_('Application')
end;

function SystemErrorMessage(WinErrorCode: Cardinal): string;
var
  P: PChar;
begin
  if FormatMessage(Format_Message_Allocate_Buffer + Format_Message_From_System,
                   nil,
                   WinErrorCode,
                   0,
                   @P,
                   0,
                   nil) <> 0 then
  begin
    Result := trim(P);
    LocalFree(Integer(P));
  end else begin
    Result := '';
  end;
end;

function GetCurrentUserName: string;
const
  cnMaxUserNameLen = 254;
var
  sUserName: string;
  dwUserNameLen: DWORD;
begin
  dwUserNameLen := cnMaxUserNameLen - 1;
  SetLength(sUserName, cnMaxUserNameLen);
  GetUserName(PChar(sUserName), dwUserNameLen);
  SetLength(sUserName, dwUserNameLen);
  Result := sUserName;
end;

 function GetWinVersion: TWinVersion;
 var
    osVerInfo: TOSVersionInfo;
    s: string;
 begin
    osVerInfo.dwOSVersionInfoSize := SizeOf(TOSVersionInfo) ;
    if GetVersionEx(osVerInfo) then
    begin
      result.WinPlatForm:=osVerInfo.dwPlatformId;
      result.Major:=osVerInfo.dwMajorVersion;
      result.Minor:=osVerInfo.dwMinorVersion;
      s:=osVerInfo.szCSDVersion;
      if s<>'' then s:=' - '+s;
      
      result.WinVersion:=Format('%d.%d (build %d)%s',[osVerInfo.dwMajorVersion,osVerInfo.dwMinorVersion,osVerInfo.dwBuildNumber,s]);
    end;
 end;


function RunProcess(FileName: string; ShowCmd: DWORD; wait: Boolean; ProcID: PCardinal=nil): Longword;
var
  StartupInfo: TStartupInfo;
  ProcessInfo: TProcessInformation;
begin
  FillChar(StartupInfo, SizeOf(StartupInfo), #0);
  StartupInfo.cb          := SizeOf(StartupInfo);
  StartupInfo.dwFlags     := STARTF_USESHOWWINDOW or STARTF_FORCEONFEEDBACK;
  StartupInfo.wShowWindow := ShowCmd;
  if not CreateProcess(nil,
    @Filename[1],
    nil,
    nil,
    False,
    CREATE_NEW_CONSOLE or
    NORMAL_PRIORITY_CLASS,
    nil,
    nil,
    StartupInfo,
    ProcessInfo)
    then
      Result := WAIT_FAILED
  else
  begin
    if wait = FALSE then
    begin
      if ProcID <> nil then ProcID^ := ProcessInfo.dwProcessId;
      exit;
    end;
    WaitForSingleObject(ProcessInfo.hProcess, INFINITE);
    GetExitCodeProcess(ProcessInfo.hProcess, Result);
  end;
  if ProcessInfo.hProcess <> 0 then
    CloseHandle(ProcessInfo.hProcess);
  if ProcessInfo.hThread <> 0 then
    CloseHandle(ProcessInfo.hThread);
end;

function ExecuteFile(FileName, Params, DefaultDir: string; ShowCmd: Integer): THandle;
var zFileName, zParams, zDir: array[0..255] of Char;
begin
  if DefaultDir='' then DefaultDir:=BaseDir;  
  Result := ShellExecute(Application.MainForm.Handle, 'open', StrPCopy(zFileName, FileName), StrPCopy(zParams, Params), StrPCopy(zDir, DefaultDir), ShowCmd);
end;

function RunAsAdmin(filename, parameters: string; ShowCmd: Integer): Cardinal;
var
  Info: TShellExecuteInfo;
  pInfo: PShellExecuteInfo;
begin
  pInfo := @Info;
  with Info do
  begin
    cbSize := SizeOf(Info);
    fMask := SEE_MASK_NOCLOSEPROCESS;
    wnd   := application.Handle;
    lpVerb := PChar('runas');
    lpFile := PChar(filename);
    lpParameters := PChar(parameters + #0);
    lpDirectory := NIL;
    nShow       := ShowCmd;
    hInstApp    := 0;
  end;
  if not ShellExecuteEx(pInfo) then begin
    result:=GetLastError;
    exit;
  end;
  {Wait to finish}
  repeat
    result := WaitForSingleObject(Info.hProcess, 500);
    Application.ProcessMessages;
  until (result <> WAIT_TIMEOUT);
end;


function Cardinal2IP(C: Cardinal):string;
begin
  result:=
    inttostr((c and $000000FF)       )+'.'+
    inttostr((c and $0000FF00) shr 08)+'.'+
    inttostr((c and $00FF0000) shr 16)+'.'+
    inttostr((c and $FF000000) shr 24);
end;

function GetComputerName:string;
const MAX_COMPUTERNAME_LENGTH=100;
var ComputerName: array[0..MAX_COMPUTERNAME_LENGTH + 1] of Char;
//var ComputerName: array[0..50 + 1] of Char;
    size:cardinal;
  LE: Cardinal;
begin
  try
    if CachedComputerName='' then begin
      size:=MAX_COMPUTERNAME_LENGTH;
      if Windows.GetComputerName(ComputerName, size) then begin
        result:=ComputerName;
        CachedComputerName:=ComputerName;
      end else begin
        LE:=GetLastError;
        MessageDlg(Format('GetComputerName failed, Code: %d - %s',[LE,SystemErrorMessage(LE)]),mtError,[mbOK],0);
        result:='';
      end;
    end else begin
      result:=CachedComputerName;
    end;
  except
    result:='';
  end;
end;


function GetSystemLangShort:string;
var bla:array[0..1023] of Char;
  s: string;

begin
  GetLocaleInfo(GetSystemDefaultLCID, LOCALE_SENGLANGUAGE, @bla, sizeof(bla));
  s:=uppercase(bla);
  if s='GERMAN' then result:='de'
  else if s='ENGLISH' then result:='en'
  else if s='FRENCH' then result:='fr'
  else if s='ITALIAN' then result:='it'
  else result:='en';
end;


{ tConfig }

constructor tConfig.Create;
begin
  UserLogs.Apache:=TStringList.Create;
  UserLogs.MySQL:=TStringList.Create;
  UserLogs.FileZilla:=TStringList.Create;
  UserLogs.Mercury:=TStringList.Create;
  UserLogs.Tomcat:=TStringList.Create;

  UserConfig.Apache:=TStringList.Create;
  UserConfig.MySQL:=TStringList.Create;
  UserConfig.FileZilla:=TStringList.Create;
  UserConfig.Mercury:=TStringList.Create;
  UserConfig.Tomcat:=TStringList.Create;

  UserLogs.Apache.Delimiter:='|';
  UserLogs.MySQL.Delimiter:='|';
  UserLogs.FileZilla.Delimiter:='|';
  UserLogs.Mercury.Delimiter:='|';
  UserLogs.Tomcat.Delimiter:='|';

  UserConfig.Apache.Delimiter:='|';
  UserConfig.MySQL.Delimiter:='|';
  UserConfig.FileZilla.Delimiter:='|';
  UserConfig.Mercury.Delimiter:='|';
  UserConfig.Tomcat.Delimiter:='|';
end;

destructor tConfig.Destroy;
begin
  FreeAndNil(UserLogs.Apache);
  FreeAndNil(UserLogs.MySQL);
  FreeAndNil(UserLogs.FileZilla);
  FreeAndNil(UserLogs.Mercury);
  FreeAndNil(UserLogs.Tomcat);

  FreeAndNil(UserConfig.Apache);
  FreeAndNil(UserConfig.MySQL);
  FreeAndNil(UserConfig.FileZilla);
  FreeAndNil(UserConfig.Mercury);
  FreeAndNil(UserConfig.Tomcat);
  inherited;
end;


initialization
  Config:=tConfig.Create;
  CachedComputerName:='';
  dfsVersionInfoResource1:=TdfsVersionInfoResource.Create();
  dfsVersionInfoResource1.Filename:=Application.ExeName;
  with dfsVersionInfoResource1.FileVersion do
    GlobalProgramversion:=IntToStr(Major)+'.'+IntToStr(Minor)+'.'+IntToStr(Release);
  dfsVersionInfoResource1.Free;
finalization
  Config.Free;
end.

