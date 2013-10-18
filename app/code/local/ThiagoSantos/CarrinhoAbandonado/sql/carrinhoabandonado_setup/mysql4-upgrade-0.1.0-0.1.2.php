<?php
/**
* Pereira, Thiago Santos
* http://thiagosantos.com/
*
* @category   ThiagoSantos
* @package    ThiagoSantos_CarrinhoAbandonado
*/
$installer = $this; 
$installer->startSetup();
 

 
$installer->run(" 
-- DROP TABLE IF EXISTS {$this->getTable('ts_ca_campanha')};
 CREATE TABLE IF NOT EXISTS `{$this->getTable('ts_ca_campanha')}`(
  `campanhaid` int(11) NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL,
  `total_emails_enviados` int(11) NOT NULL,
  `total_emails_clicados` int(11) NOT NULL,
  `total_emails_abertos` int(11) NOT NULL,
  `total_emails_revertidos` int(11) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `campanha_hash` varchar(100) default NULL,
  PRIMARY KEY  (`campanhaid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
    "); 
	
$installer->run(" 
-- DROP TABLE IF EXISTS {$this->getTable('ts_ca_email')};
 CREATE TABLE IF NOT EXISTS `{$this->getTable('ts_ca_email')}` (
  `emailid` int(11) NOT NULL auto_increment,
  `campanhaid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `aberto` int(11) NOT NULL,
  `clicado` int(11) NOT NULL,
  `revertido` enum('SIM','NAO') NOT NULL default 'NAO',
  `revertido_em` datetime NOT NULL,
  `total_pedido_comprado` varchar(11) default NULL,
  `order_id` int(11) NOT NULL,
  `total_pedido_original` varchar(11) default NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`emailid`),
  KEY `campanhaid` (`campanhaid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
");
$installer->endSetup();


