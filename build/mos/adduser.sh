echo "This tool is used to add user to access phpmyadmin.";
read -p "Account: " account
read -s -p "Password: " password
htpasswd -b users $account $password
