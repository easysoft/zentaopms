unit uServices;

interface

uses
  GnuGettext, WinSvc, Windows, uTools;

type
  TServiceStatus=(ssError, ssNotFound, ssUnknown, ssRunning, ssStopped);
  TStartStopService=(ssStart, ssStop);


function GetServiceStatus(name: string):TServiceStatus;
//function StartStopService(name: string; StartStopService: TStartStopService):boolean;


implementation

uses uMain;

function GetServiceStatus(name: string):TServiceStatus;
var
  hSCM: THandle;
  hService: THandle;
  ServiceStatus: _SERVICE_STATUS;
begin
  hSCM:=OpenSCManager(nil,nil,SC_MANAGER_CONNECT);
  if (hSCM = 0) then begin
    result:=ssError;
    exit;
  end;

  hService:=OpenService(hSCM, @name[1], SERVICE_QUERY_STATUS);;
  if (hService = 0) then begin
    CloseServiceHandle(hSCM);
    result:=ssNotFound;
    exit;
  end;

  // The SERVICE exists and we have access

  if (QueryServiceStatus(hService, ServiceStatus)) then begin
    result := ssUnknown;
    if (ServiceStatus.dwCurrentState = SERVICE_RUNNING) then result := ssRunning;
    if (ServiceStatus.dwCurrentState = SERVICE_STOPPED) then result := ssStopped;
  end else begin
    result := ssError;
  end;

  CloseServiceHandle(hService);
  CloseServiceHandle(hSCM);
end;

function ServiceDelete(name: string): boolean;
var
  hSCM: THandle;
  hService: THandle;
begin
  result:=false;
  hSCM:=OpenSCManager(nil,nil,SC_MANAGER_CONNECT);
  if (hSCM = 0) then exit;

  hService:=OpenService(hSCM, @name[1], SERVICE_QUERY_STATUS);;
  if (hService = 0) then begin
    CloseServiceHandle(hSCM);
    exit;
  end;
  // The SERVICE exists and we have access

  result:=(DeleteService(hService));

  CloseServiceHandle(hService);
  CloseServiceHandle(hSCM);
end;



end.
