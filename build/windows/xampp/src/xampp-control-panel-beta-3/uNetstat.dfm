object fNetstat: TfNetstat
  Left = 394
  Top = 196
  BorderStyle = bsSizeToolWin
  Caption = 'Netstat - TCP Listening sockets'
  ClientHeight = 580
  ClientWidth = 632
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'Tahoma'
  Font.Style = []
  OldCreateOrder = False
  Position = poScreenCenter
  OnClose = FormClose
  OnCreate = FormCreate
  OnDestroy = FormDestroy
  OnShow = FormShow
  DesignSize = (
    632
    580)
  PixelsPerInch = 96
  TextHeight = 13
  object ListView1: TListView
    Left = 4
    Top = 28
    Width = 624
    Height = 529
    Anchors = [akLeft, akTop, akRight, akBottom]
    Columns = <
      item
        Caption = 'Address'
        Width = 100
      end
      item
        Alignment = taRightJustify
        Caption = 'Port'
      end
      item
        Alignment = taRightJustify
        Caption = 'PID'
      end
      item
        Caption = 'Name'
        Width = 400
      end>
    DoubleBuffered = True
    OwnerData = True
    ReadOnly = True
    RowSelect = True
    ParentDoubleBuffered = False
    TabOrder = 0
    ViewStyle = vsReport
    OnColumnClick = ListView1ColumnClick
    OnCustomDrawItem = ListView1CustomDrawItem
    OnData = ListView1Data
  end
  object BitBtn1: TBitBtn
    Left = 549
    Top = 4
    Width = 75
    Height = 21
    Anchors = [akTop, akRight]
    Caption = 'Refresh'
    DoubleBuffered = True
    ModalResult = 4
    NumGlyphs = 2
    ParentDoubleBuffered = False
    TabOrder = 1
    OnClick = BitBtn1Click
  end
  object StatusBar1: TStatusBar
    Left = 0
    Top = 561
    Width = 632
    Height = 19
    Panels = <>
  end
  object Panel1: TPanel
    Left = 4
    Top = 4
    Width = 80
    Height = 18
    Caption = 'Active socket'
    Color = clWindow
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'Tahoma'
    Font.Style = []
    ParentBackground = False
    ParentFont = False
    TabOrder = 3
  end
  object Panel2: TPanel
    Left = 176
    Top = 4
    Width = 80
    Height = 18
    Caption = 'Old socket'
    Color = clMaroon
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWhite
    Font.Height = -11
    Font.Name = 'Tahoma'
    Font.Style = []
    ParentBackground = False
    ParentFont = False
    TabOrder = 4
  end
  object Panel3: TPanel
    Left = 90
    Top = 4
    Width = 80
    Height = 18
    Caption = 'New socket'
    Color = clLime
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = -11
    Font.Name = 'Tahoma'
    Font.Style = []
    ParentBackground = False
    ParentFont = False
    TabOrder = 5
  end
  object TimerUpdate: TTimer
    Interval = 500
    OnTimer = TimerUpdateTimer
    Left = 20
    Top = 84
  end
end
