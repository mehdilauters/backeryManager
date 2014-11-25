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
        $this->out('Creating configuration');
        $ds = $this->Email->getDatasource();
        
$config = '# /etc/courier/authmysqlrc
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

debug($config);
        $this->out('Done');
    }
        
        
}
?> 
