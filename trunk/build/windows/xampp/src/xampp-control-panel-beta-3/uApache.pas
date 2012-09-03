unit uApache;

interface

uses GnuGettext, uBaseModule, SysUtils, Classes, Windows, ExtCtrls, StdCtrls, Buttons,
  uNetstatTable, uTools, uProcesses, Messages, uServices;

type
  tApacheLogType = (altAccess, altError);

  tApache = class(tBaseModule)
    OldPIDs, OldPorts: string;
    procedure ServiceInstall; override;
    procedure ServiceUnInstall; override;
    procedure Start; override;
    procedure Stop; override;
    procedure Admin; override;
    procedure UpdateStatus; override;
    procedure CheckIsService; reintroduce;
    procedure EditConfig(ConfigFile: string); reintroduce;
    procedure ShowLogs(LogType: tApacheLogType); reintroduce;
    procedure AddLog(Log: string; LogType: tLogType=ltDefault); reintroduce;
    constructor Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
    destructor Destroy; override;
  end;

implementation

const
  cServiceName = 'Apache2.2';
  cModuleName = 'apache';

{ tApache }

procedure tApache.AddLog(Log: string; LogType: tLogType=ltDefault);
begin
  inherited AddLog(cModuleName, Log, LogType);
end;

procedure tApache.Admin;
var
  App, Param: string;
begin
  inherited;
  Param:='http://localhost/xampp/';
  if Config.BrowserApp<>'' then begin
    App:=Config.BrowserApp;
    ExecuteFile(App,Param,'',SW_SHOW);
    Addlog(Format(_('Executing "%s" "%s"'),[App,Param]),ltDebug);
  end else begin
    ExecuteFile(Param,'','',SW_SHOW);
    Addlog(Format(_('Executing "%s"'),[Param]),ltDebug);
  end;
end;

procedure tApache.CheckIsService;
var
  s: string;
begin
  inherited CheckIsService(cServiceName);
  if isService then s:=_('Service installed')
  else s:=_('Service not installed');
  AddLog(Format(_('Checking for service (name="%s"): %s'),[cServiceName,s]),ltDebug);
end;

constructor tApache.Create;
const Ports:array[0..1] of integer=(80,443);
var
  PortBlocker: string;
  ServerApp: string;
  p: integer;
begin
  inherited;
  ModuleName:=cModuleName;
  AddLog(_('Initializing module...'),ltDebug);
  ServerApp:=basedir+'apache\bin\httpd.exe';
  if not FileExists(ServerApp) then
    AddLog(Format(_('Possible problem detected: file "%s" not found - run this program from your XAMPP root directory!'),[ServerApp]),ltError);

  CheckIsService;

  if Config.CheckDefaultPorts then begin
    AddLog(_('Checking default ports...'),ltDebug);

    for p:=Low(Ports) to High(Ports) do begin
      PortBlocker:=NetStatTable.isPortInUse(Ports[p]);
      if (PortBlocker<>'') then begin
        if (LowerCase(PortBlocker)=LowerCase(ServerApp)) then begin
          AddLog(Format(_('"%s" seems to be running on port %d?'),[ServerApp,Ports[p]]),ltError);
        end else begin
          AddLog(Format(_('Possible problem detected: Port %d in use by "%s"!'),[Ports[p],PortBlocker]),ltError);
        end;
      end;
    end;

  end;
end;

destructor tApache.Destroy;
begin
  inherited;
end;

procedure tApache.EditConfig(ConfigFile: string);
var
  App, Param: string;
begin
  App:=Config.EditorApp;
  Param:=BaseDir+ConfigFile;
  Addlog(Format(_('Executing %s %s'),[App,Param]),ltDebug);
  ExecuteFile(app,param,'',SW_SHOW);
end;


procedure tApache.ServiceInstall;
var
  App, Param: string;
  RC: Integer;
begin
  App:=BaseDir+'apache\bin\httpd.exe';
  Param:='-k install';
  AddLog(_('Installing service...'));
  AddLog(Format(_('Executing "%s %s"'),[App, Param]),ltDebug);
  RC:=RunAsAdmin(App,Param,SW_HIDE);
  if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
  else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
end;

procedure tApache.ServiceUnInstall;
var
  App, Param: string;
  RC: Cardinal;
begin
  App:=BaseDir+'apache\bin\httpd.exe';
  Param:='-k uninstall';
  AddLog(_('Uninstalling service...'));
  AddLog(Format(_('Executing "%s %s"'),[App, Param]),ltDebug);
  RC:=RunAsAdmin(App,Param,SW_HIDE);
  if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
  else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
end;

procedure tApache.ShowLogs(LogType: tApacheLogType);
var
  App, Param: string;
begin
  App:=Config.EditorApp;
  if LogType=altAccess then
    Param:=BaseDir+'apache\logs\access.log';
  if LogType=altError then
    Param:=BaseDir+'apache\logs\error.log';
  AddLog(Format(_('Executing "%s %s"'),[App,Param]),ltDebug);
  ExecuteFile(app,param,'',SW_SHOW);
end;

procedure tApache.Start;
var
  App: string;
  RC: Cardinal;
begin
  if isService then begin
    AddLog(Format(_('Starting %s service...'),[cModuleName]));
    App:=Format('start "%s"',[cServiceName]);
    AddLog(Format(_('Executing "%s"'),['net '+App]),ltDebug);
    RC:=RunAsAdmin('net',App,SW_HIDE);
    if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
    else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
  end else begin
    AddLog(Format(_('Starting %s app...'),[cModuleName]));
    App:=BaseDir+'apache\bin\httpd.exe';
    AddLog(Format(_('Executing "%s"'),[App]),ltDebug);
    RunProcess(App,SW_HIDE,false);
  end;
end;

procedure tApache.Stop;
var
  i, pPID: Integer;
  App: string;
  RC: Cardinal;
begin
  if isService then begin
    AddLog(Format(_('Stopping %s service...'),[cModuleName]));
    App:=Format('stop "%s"',[cServiceName]);
    AddLog(Format(_('Executing "%s"'),['net '+App]),ltDebug);
    RC:=RunAsAdmin('net',App,SW_HIDE);
    if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
    else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
  end else begin
    if PIDList.Count>0 then begin
      for i:=0 to PIDList.Count-1 do begin
        pPID:=Integer(PIDList[i]);
        AddLog(_('Stopping')+' '+cModuleName+' '+Format('(PID: %d)',[pPID]));
        App:=Format(BaseDir+'apache\bin\pv.exe -f -k -q -i %d',[pPID]);
        AddLog(Format(_('Executing "%s"'),[App]),ltDebug);
        RunProcess(App,SW_HIDE,false);
      end;
    end else begin
      AddLog(_('No PIDs found?!'));
    end;
  end;
end;

procedure tApache.UpdateStatus;
var
  p: Integer;
  ProcInfo: TProcInfo;
  s: string;
  ports: string;
begin
  isRunning:=false;
  PIDList.Clear;
  for p:=0 to Processes.ProcessList.Count-1 do begin
    ProcInfo:=Processes.ProcessList[p];
    if { (pos(BaseDir,ProcInfo.ExePath)=1) and } (pos('httpd.exe',ProcInfo.Module)=1) then begin
      isRunning:=true;
      PIDList.Add(Pointer(ProcInfo.PID));
    end;
  end;

  // Checking processes
  s:='';
  for p:=0 to PIDList.Count-1 do begin
    if p=0 then s:=IntToStr(Integer(PIDList[p]))
    else s:=s+#13+IntToStr(Integer(PIDList[p]));
  end;
  if s<>OldPIDs then begin
    lPID.Caption:=s;
    OldPIDs:=s;
  end;

  // Checking netstats
  s:='';
  for p:=0 to PIDList.Count-1 do begin
    ports:=NetStatTable.GetPorts4PID(Integer(PIDList[p]));
    if ports<>'' then begin
      if s='' then s:=ports
      else s:=s+', '+ports;
    end;
  end;
  if s<>OldPorts then begin
    lPort.Caption:=s;
    OldPorts:=s;
  end;

  if byte(isRunning)<>oldIsRunningByte then begin

    if oldIsRunningByte<>2 then begin
      if isRunning then s:=_('running')
      else s:=_('stopped');
      AddLog(_('Status change detected:')+' '+s);
    end;

    oldIsRunningByte:=byte(isRunning);
    if isRunning then begin
      pStatus.Color:=cRunningColor;
      bStartStop.Caption:=_('Stop');
      bAdmin.Enabled:=true;
    end else begin
      pStatus.Color:=cStoppedColor;
      bStartStop.Caption:=_('Start');
      bAdmin.Enabled:=false;
    end;
  end;

  if AutoStart then begin
    AutoStart:=false;
    if isRunning then begin
      AddLog(_('Autostart active: modul is already running - aborted'),ltError);
    end else begin
      AddLog(_('Autostart active: starting...'));
      Start;
    end;
  end;

end;

end.
