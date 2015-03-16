Name:zentaopms
Version:7.1.stable
Release:1
Summary:This is ZenTao PMS software.	

Group:utils
License:ZPL
URL:http://www.zentao.net
Source0:%{name}-%{version}.tar.gz
BuildRoot:%{_tmppath}/%{name}-%{version}-root
BuildArch:noarch
Requires:httpd,php-cli, php-common,php-pdo,php-mysql,php-json,php-ldap,mysql 

%description

%prep
%setup -c

%install
mkdir -p $RPM_BUILD_ROOT
chmod 777 -R %{_builddir}/%{name}-%{version}/var/www/zentao/tmp/
chmod 777 -R %{_builddir}/%{name}-%{version}/var/www/zentao/www/data
chmod 777 -R %{_builddir}/%{name}-%{version}/var/www/zentao/config
chmod 777 %{_builddir}/%{name}-%{version}/var/www/zentao/module
chmod a+rx %{_builddir}/%{name}-%{version}/var/www/zentao/bin/*
find %{_builddir}/%{name}-%{version}/var/www/zentao/ -name ext |xargs chmod -R 777
cp -a %{_builddir}/%{name}-%{version}/* $RPM_BUILD_ROOT 

%clean
rm -rf $RPM_BUILD_ROOT

%files
/

%post
echo "zentaopms has been successfully installed."
echo "Please restart httpd and visit http://localhost/zentao."
