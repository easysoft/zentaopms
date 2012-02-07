unit uHelp;

interface

uses
  GnuGettext, Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons;

type
  TfHelp = class(TForm)
    lAbout: TLabel;
    Label1: TLabel;
    BitBtn1: TBitBtn;
    procedure BitBtn1Click(Sender: TObject);
    procedure FormCreate(Sender: TObject);
  private
    { Private-Deklarationen }
  public
    { Public-Deklarationen }
  end;

var
  fHelp: TfHelp;

implementation

{$R *.dfm}

procedure TfHelp.BitBtn1Click(Sender: TObject);
begin
  Close;
end;

procedure TfHelp.FormCreate(Sender: TObject);
begin
  TranslateComponent(Self);
end;

end.
