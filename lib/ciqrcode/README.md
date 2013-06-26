Codeigniter PHP QR Code
=======================

Version 1 - Dec 2011
by dwi.setiyadi@gmail.com



Introduction
------------

This a library for CodeIgniter Framework to make QR Code from some string given, a porting code from http://phpqrcode.sourceforge.net/.


Installation and Requirements
-----------------------------

This library requires CodeIgniter Framework and GD2 PHP extension.


### How to use ###

On the fly generate

	$this->load->library('ciqrcode');
	
	header("Content-Type: image/png");
	$params['data'] = 'This is a text to encode become QR Code';
	$this->ciqrcode->generate($params);



Saved QR Code image example

	$this->load->library('ciqrcode');
	
	$params['data'] = 'This is a text to encode become QR Code';
	$params['level'] = 'H';
	$params['size'] = 10;
	$params['savename'] = FCPATH.'tes.png';
	$this->ciqrcode->generate($params);
	
	echo '<img src="'.base_url().'tes.png" />';



Optional configuration

	$this->load->library('ciqrcode');
	
	$config['cacheable']	= true; //boolean, the default is true
	$config['cachedir']		= ''; //string, the default is application/cache/
	$config['errorlog']		= ''; //string, the default is application/logs/
	$config['quality']		= true; //boolean, the default is true
	$config['size']			= ''; //interger, the default is 1024
	$config['black']		= array(224,255,255); // array, default is array(255,255,255)
	$config['white']		= array(70,130,180); // array, default is array(0,0,0)
	$this->ciqrcode->initialize($config);


Contributors
------------
1. [Jared Hatfield](https://github.com/JaredHatfield "Jared Hatfield")
2. [Collin Price](https://github.com/collinprice "Collin Price")
