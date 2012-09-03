object fHelp: TfHelp
  Left = 356
  Top = 94
  Caption = 'Help'
  ClientHeight = 116
  ClientWidth = 256
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  Position = poScreenCenter
  OnCreate = FormCreate
  DesignSize = (
    256
    116)
  PixelsPerInch = 96
  TextHeight = 13
  object lAbout: TLabel
    Left = 8
    Top = 12
    Width = 233
    Height = 19
    Caption = 'programmed by Steffen Strueber'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -16
    Font.Name = 'Tahoma'
    Font.Style = []
    ParentFont = False
  end
  object Label1: TLabel
    Left = 8
    Top = 64
    Width = 105
    Height = 13
    Caption = 'Uhm, did this help? :-)'
  end
  object BitBtn1: TBitBtn
    Left = 173
    Top = 83
    Width = 75
    Height = 25
    Anchors = [akRight, akBottom]
    Caption = 'Close'
    DoubleBuffered = True
    ParentDoubleBuffered = False
    TabOrder = 0
    OnClick = BitBtn1Click
  end
end
