<?php
class EmailShell extends AppShell {

public $uses = array('Email');

public function getOptionParser() {
    return ConsoleOptionParser::buildFromArray(array(
        'description' => array(
            __("create postfix configuration for current bdd settings"),
        ),
    ));
}

    public function main() {
    
    App::uses('File', 'Utility');
    App::uses('Folder', 'Utility');
        $ds = $this->Email->getDatasource();

$dir = new Folder(Configure::read('Settings.Emails.path'), true, 0755);

$authmysqlrc = '# /etc/courier/authmysqlrc
MYSQL_SERVER            '.$ds->config['host'].'
MYSQL_USERNAME          '.$ds->config['login'].'
MYSQL_PASSWORD          '.$ds->config['password'].'
#MYSQL_SOCKET           /var/lib/mysql/mysql.sock
MYSQL_PORT              '.$ds->config['port'].'
#MYSQL_OPT               0
MYSQL_DATABASE          '.$ds->config['database'].'
MYSQL_USER_TABLE        '.$this->Email->table.' e, '.$this->Email->Company->table.' c
#MYSQL_CRYPT_PWFIELD     concat("$1$",e.password)

MYSQL_CRYPT_PWFIELD     e.password

#MYSQL_CLEAR_PWFIELD    name
#DEFAULT_DOMAIN         domain.tld
MYSQL_UID_FIELD         5000
MYSQL_GID_FIELD         5000
MYSQL_LOGIN_FIELD       concat(e.email,"@",c.domain_name,".fr")
MYSQL_HOME_FIELD        "'.Configure::read('Settings.Emails.path').'"
MYSQL_NAME_FIELD        e.email
MYSQL_MAILDIR_FIELD     concat(c.id,"/",e.email,"/")
#MYSQL_QUOTA_FIELD      quota
MYSQL_WHERE_CLAUSE     e.company_id = c.id';
      $this->out($authmysqlrc);
      
      $postconf = array(
        'postconf -e "mailbox_command = "',
        'postconf -e "home_mailbox = Maildir/"',
        'postconf -e "postfix_mydestination = localhost"',
        'postconf -e "smtpd_use_tls = yes"',
        'postconf -e "virtual_gid_maps = static:'.Configure::read('Settings.Emails.gid').'"',
        'postconf -e "virtual_mailbox_base = '.Configure::read('Settings.Emails.path').'."',
        'postconf -e "virtual_mailbox_domains = mysql:/etc/postfix/mysql-virtual-mailbox-domains.cf"',
        'postconf -e "virtual_mailbox_maps = mysql:/etc/postfix/mysql-virtual-mailbox-maps.cf"',
        'postconf -e "virtual_uid_maps = static:'.Configure::read('Settings.Emails.uid').'"',
      );
      $this->out('# postconf');
      foreach($postconf as $conf)
      {
        $this->out($conf);
      }


$mysql_virtual_mailbox_maps='
#/etc/postfix/mysql-virtual-mailbox-maps.cf
user = '.$ds->config['login'].'
password = '.$ds->config['password'].'
hosts = '.$ds->config['host'].'
dbname = '.$ds->config['database'].'
query = SELECT concat(c.id,"/",e.email,"/") FROM '.$this->Email->table.' e, '.$this->Email->Company->table.' c WHERE e.company_id = c.id
';
      
      $this->out($mysql_virtual_mailbox_maps);
      
      $mysql_virtual_mailbox_domains = '
#/etc/postfix/mysql-virtual-mailbox-domains.cf
user = '.$ds->config['login'].'
password = '.$ds->config['password'].'
hosts = '.$ds->config['host'].'
dbname = '.$ds->config['database'].'
query = SELECT 1 FROM '.$this->Email->Company->table.' WHERE \'%s\' like concat(domain_name,\'.%%\')
      ';
      $this->out($mysql_virtual_mailbox_domains);
      
      
      $file = new File('/etc/postfix/mysql-virtual-mailbox-maps.cf');
      if(!$file->write($mysql_virtual_mailbox_maps))
      {
        $this->out('error for mailboxMaps');
      }

      $file = new File('/etc/postfix/mysql-virtual-mailbox-domains.cf');
      if(!$file->write($mysql_virtual_mailbox_domains))
      {
        $this->out('error for mailboxDomains');
      }
      
      foreach($postconf as $conf)
      {
        system($conf);
      }
      
    }
        
        
}
?> 
