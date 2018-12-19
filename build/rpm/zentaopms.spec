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
Requires:httpd, php-cli, php, php-common, php-pdo, php-json, php-ldap, mysql
Requires:/usr/lib64/php/modules/pdo_mysql.so

%description

%prep
%setup -c

%install
mkdir -p $RPM_BUILD_ROOT
chmod 777 -R %{_builddir}/%{name}-%{version}/opt/zentao/tmp/
chmod 777 -R %{_builddir}/%{name}-%{version}/opt/zentao/www/data
chmod 777 -R %{_builddir}/%{name}-%{version}/opt/zentao/config
chmod 777 %{_builddir}/%{name}-%{version}/opt/zentao/module
chmod 777 %{_builddir}/%{name}-%{version}/opt/zentao/www
chmod a+rx %{_builddir}/%{name}-%{version}/opt/zentao/bin/*
find %{_builddir}/%{name}-%{version}/opt/zentao/ -name ext |xargs chmod -R 777
cp -a %{_builddir}/%{name}-%{version}/* $RPM_BUILD_ROOT 

%clean
rm -rf $RPM_BUILD_ROOT

%files
/

%post
chcon -R --reference=/var/www/html/ /opt/zentao/
lowVersion=`httpd -v|awk '$3~/Apache/{print $3}'|awk -F '/' '{print ($2<2.4) ? 1 : 0}'`
if [ $lowVersion -eq 1 ]; then
sed -i '/Require all granted/d' /etc/httpd/conf.d/zentaopms.conf
fi

echo "zentaopms has been successfully installed."
echo "Please restart httpd and visit http://localhost/zentao."
