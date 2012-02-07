unit uBaseModule;

interface

uses GnuGettext, Classes, ExtCtrls, StdCtrls, Buttons, SysUtils, uTools, uServices;

type
  tBaseModule = class
  public
    bbService: TBitBtn;
    pStatus: tPanel;
    lPID: tLabel;
    lPort: tLabel;
    bStartStop: tBitBtn;
    bAdmin: TBitBtn;
    AutoStart: boolean;

    oldIsRunningByte: byte;
    isRunning: boolean;
    isService: boolean;
    PIDList: tList;

    ModuleName: string;
    procedure Start;            virtual; abstract;
    procedure Stop;             virtual; abstract;
    procedure Admin;            virtual; abstract;
    procedure UpdateStatus;     virtual; abstract;

    procedure ServiceInstall;   virtual; abstract;
    procedure ServiceUnInstall; virtual; abstract;

    procedure SetServiceButton(isActive: boolean);
    procedure CheckIsService(ServiceName: string);

    procedure AddLog(module,log: string; LogType: tLogType);
    constructor Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
    destructor Destroy; override;
  end;

implementation

uses uMain;

{ tBaseModule }

procedure tBaseModule.Addlog(module, log: string; LogType: tLogType);
begin
  fMain.AddLog(module,log,LogType);
end;

procedure tBaseModule.CheckIsService(ServiceName: string);
var
  ServiceStatus: TServiceStatus;
begin
  ServiceStatus:=GetServiceStatus(ServiceName);
  isService:=ServiceStatus in [ssRunning, ssStopped];
  SetServiceButton(isService);
end;

constructor tBaseModule.Create(pbbService: TBitBtn; pStatusPanel: tPanel; pPIDLabel, pPortLabel: tLabel; pStartStopButton, pAdminButton: tBitBtn);
begin
  PIDList:=TList.Create;
  isRunning:=false;
  isService:=false;

  bbService:=pbbService;
  pStatus:=pStatusPanel;
  lPID:=pPIDLabel;
  lPort:=pPortLabel;
  bStartStop:=pStartStopButton;
  bAdmin:=pAdminButton;

  oldIsRunningByte:=2;

  AutoStart:=false;
end;

destructor tBaseModule.Destroy;
begin
  PIDList.Free;
  inherited;
end;

procedure tBaseModule.SetServiceButton(isActive: boolean);
begin
  bbService.Glyph:=nil;
  if isActive then
    fMain.ImageList.GetBitmap(0, bbService.Glyph) ;
  if not isActive then
    fMain.ImageList.GetBitmap(1, bbService.Glyph) ;
end;

end.
