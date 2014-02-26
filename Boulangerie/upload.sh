#!/bin/bash    
HOST="ftp.boulangerie-faury.fr"
USER="boulangey"
# "8iWTx6Km"
LCD="./"
RCD="/www/dev"
if [ "$2" = "backup" ]
then
	RCD="/www/backup"
fi
if [ "$2" = "production" ]
then
	echo "Voulez vous vraiment updater la version officielle? (Y/n)"
	read rep
	if [ "$rep" = "Y" ]
	then
		RCD="/www/boulangerie"
	else
		exit
	fi
fi
echo "Mise a jour de ${RCD}"


#--exclude-glob dev/ \
       #--exclude-glob blog/ \
       #--exclude-glob ./.htaccess \
       #--exclude-glob cake/ \
       #       --exclude-glob app/config/database.php \
       #--exclude-glob app/config/feriatheque.php \
       #       --exclude-glob cake/tests/ \
       #       --exclude-glob app/tmp/* \
       #       --exclude-glob app/models/datasources/ \


       #--exclude-glob cake/ \
       #--exclude-glob app/Config/core.php \
       
       #--exclude-glob app/Config/feriatheque_server.php \
       
       
lftp -c "set ftp:list-options -a;
open ftp://$USER:$1@$HOST; 
lcd $LCD;
cd $RCD;
mirror --reverse \
	--delete \
	--verbose \
	--exclude-glob tests/ \
	--exclude-glob .svn/ \
	--exclude-glob *.log \
	--exclude-glob *.tmp \
	--exclude-glob database.php \
	--exclude-glob core.php \
	--exclude-glob *~ \
       "