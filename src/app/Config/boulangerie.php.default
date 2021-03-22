<?php
/**
* setup:
*    apt-get install apache2 mysql-server phpmyadmin git fail2ban logwatch htop php5-curl courier-pop courier-imap postfix postfix-mysql roundcube courier-authlib-mysql apache2-utils iptables-persistent AutoMySQLBackup courier-imap-ssl
*    optional
*	apt-get install ruby
*       gem install selenium
* git clone https://github.com/mehdilauters/bakeryManager.git
*  chown -R www-data:www-data bakeryManager
* a2enmod rewrite
* nano /etc/apache2/sites-enabled/000-default 
* AllowOverride All
* service apache2 restart
*
* https://workaround.org/ispmail/lenny/postfix-database-mappings
* http://dannorth.net/2007/09/09/virtual-mailboxes-with-courier-imap-and-postfix/
*
* iptables
* iptables -A INPUT -p tcp --dport ssh -j ACCEPT
* iptables -A INPUT -p tcp --dport 80 -j ACCEPT
* iptables -A INPUT -p tcp --dport 443 -j ACCEPT
* iptables -A INPUT -p tcp --dport 993 -j ACCEPT
* iptables -I INPUT 1 -i lo -j ACCEPT
* iptables -P INPUT DROP
* // allow apt-get downloads http://serverfault.com/questions/433295/what-is-the-right-iptables-rule-to-allow-apt-get-to-download-programs
* iptables -F OUTPUT  # remove your existing OUTPUT rule which becomes redundant
* iptables -A OUTPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
* iptables -A OUTPUT -p tcp --dport 80 -m state --state NEW -j ACCEPT
* iptables -A OUTPUT -p tcp --dport 53 -m state --state NEW -j ACCEPT
* iptables -A OUTPUT -p udp --dport 53 -m state --state NEW -j ACCEPT
* iptables -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
*
* iptables -A INPUT -p tcp --dport 993 -j ACCEPT
*/  


  Configure::write('Settings.Medias.Photos.path', APP.'tmp/');  

  Configure::write('Settings.Medias.Photos.xPreview', 200);  
  Configure::write('Settings.Medias.Photos.yPreview', 150);  

  Configure::write('Settings.Medias.Photos.xNormal', 800);  
  Configure::write('Settings.Medias.Photos.yNormal', 600);    

    Configure::write('Settings.dbBackupPath', '/tmp/');
    Configure::write('Settings.excelExportPath', '/tmp/');
	
	Configure::write('Settings.dbBackupUrl', 'https://boulangerie-faury.fr/boulangerie/');
	
	Configure::write('Settings.email', array('from' => 
					array(
						'email'=>'boulangeriefaury@orange.fr',
						'name'=>'Boulangerie Faury'
						),
			'debug' => array(
								'status' => false,
								'email' => array('boulangeriefaury@orange.fr','mehdilauters@gmail.com')
							),
			
			)
			
  );
	

Configure::write('Settings.Order.pageBreakItemsMax', 20);

Configure::write('Settings.Approximation.order', 4);
Configure::write('Settings.Approximation.bcscale', 10);
Configure::write('Settings.Approximation.nbProjectionsPoint', 0);


Configure::write('Settings.Security.ssl', false);


Configure::write('Settings.Cookie.Name', 'bakeryManager');




Configure::write('Settings.demo', array(
  'active' => false,
  'dbPrefix' => 'demo_',
  'limit' => 2000,
  'User' => array('email'=>'demo@lauters.fr', 'password'=> 'demo'),
  'root' => 'root'

));


Configure::write('Settings.Excel', array(
  'maxNbRow' => 500,

));

    Configure::write('Settings.midday', 14);

	// https://github.com/mehdilauters/bakeryManager/archive/b9a98d18559c5ae6483ebaba9855b9732986182b.zip
	// https://github.com/mehdilauters/bakeryManager/archive/master.zip
	
	Configure::write('Settings.github.downloadUrl', 'https://codeload.github.com/mehdilauters/bakeryManager/');
	Configure::write('Settings.github.apiUrl', 'https://api.github.com/repos/mehdilauters/bakeryManager/');
	//commits
	
	
Configure::write('Settings.Emails', array(
  'path' => '/var/mail/vhost/',
  'uid' =>  5000,
  'gid' => 5000,
  'maildirmake' => 'maildirmake',

));

Configure::write('Settings.public', true);

?>