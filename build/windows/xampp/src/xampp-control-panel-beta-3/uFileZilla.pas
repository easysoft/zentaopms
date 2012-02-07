unit uFileZilla;

interface

uses GnuGettext, uBaseModule, SysUtils, Classes, Windows, ExtCtrls, StdCtrls, Buttons,
  uNetstatTable, uTools, uProcesses;

type
  tFileZilla = class(tBaseModule)
    OldPIDs, OldPorts: string;
    procedure ServiceInstall; override;
    procedure ServiceUnInstall; override;
    procedure Start; override;
    procedure Stop; override;
    procedure Admin; override;
    procedure UpdateStatus; override;
    procedure CheckIsService; reintroduce;
    procedure AddLog(Log: string; LogType: tLogType=ltDefault); reintroduce;
    constructor Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
    destructor Destroy; override;
  end;

implementation

const cServiceName = 'FileZilla Server';
      cModuleName = 'FileZilla';

{ tFileZilla }

procedure tFileZilla.AddLog(Log: string; LogType: tLogType);
begin
  inherited AddLog('filezilla', Log, LogType);
end;

procedure tFileZilla.Admin;
var
  App: string;
begin
  App:=BaseDir+'filezillaftp\filezilla server interface.exe';
  Addlog(Format(_('Executing "%s"'),[App]),ltDebug);
  ExecuteFile(App,'','',SW_SHOW);
end;

procedure tFileZilla.CheckIsService;
var
  s: string;
begin
  inherited CheckIsService(cServiceName);
  if isService then s:=_('Service installed')
  else s:=_('Service not installed');
  AddLog(Format(_('Checking for service (name="%s"): %s'),[cServiceName,s]),ltDebug);
end;

constructor tFileZilla.Create;
var
  PortBlocker: string;
  ServerApp: string;
  ServerPort: Integer;
begin
  inherited;
  ModuleName:=cModuleName;
  isService:=false;
  AddLog(_('Initializing module...'),ltDebug);
  ServerApp:=basedir+'FileZillaFTP\FileZillaServer.exe';
  ServerPort:=21;
  if not FileExists(ServerApp) then
    AddLog(Format(_('Possible problem detected: file "%s" not found - run this program from your XAMPP root directory!'),[ServerApp]),ltError);

  CheckIsService;

  if Config.CheckDefaultPorts then begin
    AddLog(_('Checking default ports...'),ltDebug);
    PortBlocker:=NetStatTable.isPortInUse(ServerPort);
    if (PortBlocker<>'') then begin
      if (LowerCase(PortBlocker)=LowerCase(ServerApp)) then begin
        AddLog(Format(_('"%s" seems to be running on port %d?'),[ServerApp,ServerPort]),ltError);
      end else begin
        AddLog(Format(_('Possible problem detected: Port %d in use by "%s"!'),[ServerPort,PortBlocker]),ltError);
      end;
    end;
  end;
end;

destructor tFileZilla.Destroy;
begin

  inherited;
end;


procedure tFileZilla.ServiceInstall;
var
  App, Param: string;
  RC: Integer;
begin
  App:=BaseDir+'filezillaftp\filezillaserver.exe';
  AddLog(_('Installing service...'));
  Addlog(Format(_('Executing "%s"'),[App]),ltDebug);
  RC:=RunAsAdmin(App,Param,SW_HIDE);
  if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
  else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
end;

procedure tFileZilla.ServiceUnInstall;
var
  App, Param: string;
  RC: Cardinal;
begin
  App:='sc';
  Param:='delete "'+cServiceName+'"';
  AddLog('Uninstalling service...');
  Addlog(Format(_('Executing "%s" "%s"'),[App,Param]),ltDebug);
  RC:=RunAsAdmin(App,Param,SW_HIDE);
  if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
  else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
end;


procedure tFileZilla.Start;
var
  App: string;
  RC: Cardinal;
begin
  if isService then begin
    AddLog(Format(_('Starting %s service...'),[cModuleName]));
    App:=Format('start "%s"',[cServiceName]);
    Addlog(Format(_('Executing "%s"'),['net '+App]),ltDebug);
    RC:=RunAsAdmin('net',App,SW_HIDE);
    if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
    else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
  end else begin
    AddLog(_('FileZilla must be run as service!'));
  end;
end;

procedure tFileZilla.Stop;
var
  App: string;
  RC: Cardinal;
begin
  if isService then begin
    AddLog(Format(_('Stopping %s service...'),[cModuleName]));
    App:=Format('stop "%s"',[cServiceName]);
    Addlog(Format(_('Executing "%s"'),['net '+App]),ltDebug);
    RC:=RunAsAdmin('net',App,SW_HIDE);
    if RC=0 then AddLog(Format(_('Return code: %d'),[RC]),ltDebug)
    else AddLog(Format(_('There may be an error, return code: %d - %s'),[RC,SystemErrorMessage(RC)]),ltError);
  end else begin
    AddLog(_('FileZilla must be run as service!'));
  end;
end;

procedure tFileZilla.UpdateStatus;
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
    if { (pos(BaseDir,ProcInfo.ExePath)=1) and } (pos('filezillaserver.exe',ProcInfo.Module)=1) then begin
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
