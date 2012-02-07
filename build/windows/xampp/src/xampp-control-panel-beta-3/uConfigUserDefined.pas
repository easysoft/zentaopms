unit uConfigUserDefined;

interface

uses
  GnuGettext, Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons, uTools;

type
  TfConfigUserDefined = class(TForm)
    lHeader1: TLabel;
    bSave: TBitBtn;
    bAbort: TBitBtn;
    gbApache: TGroupBox;
    mConfigApache: TMemo;
    mLogsApache: TMemo;
    lHeader2: TLabel;
    lConfig: TLabel;
    lLogs: TLabel;
    gbMySQL: TGroupBox;
    mConfigMySQL: TMemo;
    mLogsMySQL: TMemo;
    gbFileZilla: TGroupBox;
    mConfigFilezilla: TMemo;
    mLogsFileZilla: TMemo;
    gbMercury: TGroupBox;
    mConfigMercury: TMemo;
    mLogsMercury: TMemo;
    gbTomcat: TGroupBox;
    mConfigTomcat: TMemo;
    mLogsTomcat: TMemo;
    procedure FormCreate(Sender: TObject);
    procedure FormShow(Sender: TObject);
    procedure bAbortClick(Sender: TObject);
    procedure bSaveClick(Sender: TObject);
    procedure FormKeyPress(Sender: TObject; var Key: Char);
  private
    procedure Memo2Config;
    procedure Config2Memo;
  public
    { Public-Deklarationen }
  end;

var
  fConfigUserDefined: TfConfigUserDefined;

implementation

{$R *.dfm}

{ TfConfigUserDefined }

procedure TfConfigUserDefined.bAbortClick(Sender: TObject);
begin
  Close;
end;

procedure TfConfigUserDefined.bSaveClick(Sender: TObject);
begin
  Memo2Config;
  SaveSettings;
end;

procedure TfConfigUserDefined.Memo2Config;
begin
  Config.UserConfig.Apache.Text:=mConfigApache.Text;
  Config.UserConfig.MySQL.Text:=mConfigMySQL.Text;
  Config.UserConfig.FileZilla.Text:=mConfigFilezilla.Text;
  Config.UserConfig.Mercury.Text:=mConfigMercury.Text;
  Config.UserConfig.Tomcat.Text:=mConfigTomcat.Text;

  Config.UserLogs.Apache.Text:=mLogsApache.Text;
  Config.UserLogs.MySQL.Text:=mLogsMySQL.Text;
  Config.UserLogs.FileZilla.Text:=mLogsFileZilla.Text;
  Config.UserLogs.Mercury.Text:=mLogsMercury.Text;
  Config.UserLogs.Tomcat.Text:=mLogsTomcat.Text;
end;


procedure TfConfigUserDefined.Config2Memo;
begin
  mConfigApache.Text:=Config.UserConfig.Apache.Text;
  mConfigMySQL.Text:=Config.UserConfig.MySQL.Text;
  mConfigFilezilla.Text:=Config.UserConfig.FileZilla.Text;
  mConfigMercury.Text:=Config.UserConfig.Mercury.Text;
  mConfigTomcat.Text:=Config.UserConfig.Tomcat.Text;

  mLogsApache.Text:=Config.UserLogs.Apache.Text;
  mLogsMySQL.Text:=Config.UserLogs.MySQL.Text;
  mLogsFileZilla.Text:=Config.UserLogs.FileZilla.Text;
  mLogsMercury.Text:=Config.UserLogs.Mercury.Text;
  mLogsTomcat.Text:=Config.UserLogs.Tomcat.Text;
end;

procedure TfConfigUserDefined.FormCreate(Sender: TObject);
begin
  TranslateComponent(self);
end;

procedure TfConfigUserDefined.FormKeyPress(Sender: TObject; var Key: Char);
begin
  if key=#27 then begin
    key:=#0;
    close;
    exit;
  end;
end;

procedure TfConfigUserDefined.FormShow(Sender: TObject);
begin
  Config2Memo;
end;


end.
