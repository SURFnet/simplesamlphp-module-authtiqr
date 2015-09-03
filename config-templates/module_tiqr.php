<?php

/**
 * This file is part of simpleSAMLphp.
 * 
 * The authTiqr module is a module adding authentication via the tiqr 
 * project to simpleSAMLphp. It was initiated by SURFnet and 
 * developed by Egeniq.
 *
 * See the README file for instructions and requirements.
 *
 * @author Ivo Jansch <ivo@egeniq.com>
 * 
 * @package simpleSAMLphp
 * @subpackage authTiqr
 *
 * @license New BSD License - See LICENSE file in the tiqr library for details
 * @copyright (C) 2010-2011 SURFnet BV
 *
 */

$config = array(

    #"identifier"          => "yourserver.uri.com",
    "name"                => "Name of your service",
    "auth.protocol"       => "tiqrauth",	# protocol compiled into app
    "enroll.protocol"     => "tiqrenroll",	# enroll protocol compiled in to app

    "ocra.suite"          => "OCRA-1:HOTP-SHA1-6:QH10-S",

    "logoUrl"             => "https://demo.tiqr.org/img/tiqrRGB.png",	# change to your idp logo URL
    "infoUrl"             => "https://tiqr.org",			# change to your info page URL

    "tiqr.path"           => "../vendor/tiqr/tiqr-server-libphp/library/tiqr",

    "phpqrcode.path"      => "../vendor/kairos/phpqrcode",

    "zend.path"           => "../vendor/zendframework/zendframework1/library",
    
    "apns.certificate"    => "../certificates/your_apple_push_notification_certificate.pem",
    "apns.environment"    => "sandbox",
    
    "gcm.application"     => "com.example.authenticator",
    "gcm.apikey"          => "your GCM api key",
    
    // blocks configuration
    "maxAttempts"            => 3, // maximum number of login attempts before a block is set, set to 0 for not using blocks at all
    "temporaryBlockDuration" => 0, // duration of temporary block in minutes, set to 0 for no blocks or permanent blocks only
    "maxTemporaryBlocks"     => 0, // defines number of temporary blocks before setting a permanent block, set to anything other then 0 for using temporary and permanent blocks

    "statestorage"        => array("type" => "file"),
    // "statestorage"        => array( "type" => "pdo",
    //                                 "table" => "tiqrstate",
    //                                 "dsn" => "mysql:dbname=tiqr;host=mysql.surfnet.nl",
    //                                 "username" => "tiqr",
    //                                 "password" => "*****"),
    
    "devicestorage"       => array("type"  => "tokenexchange",
                                   "url"   => "http://path/to/your/tokenexchange/server/",
                                   "appid" => "idOfYourApp"),

    "userstorage"         => array("type" => "file", "path" => "/tmp", "encryption" => array('type' => 'dummy')),

    // "userstorage"         =>  array("type" => "ldap",
    //                                 "encryption" => array('type' => 'dummy')
    //                                 "host" => "ldap.surfnet.nl",
    //                                 "username" => "cn=Admin,ou=Persons,ou=Office,dc=SURFnet,dc=NL",
    //                                 "password" => "*****",
    //                                 "bindRequiresDn" => true,
    //                                 "accountDomainName" => "surfnet.nl",
    //                                 "baseDn" => "dc=surfnet,dc=nl",
    //                                 "userClass" => 'organizationalPerson',
    //                                 "dnPattern" =>  "cn=%s,ou=Persons,ou=Office,dc=surfnet,dc=nl",
    //                                 "idAttr" => 'cn',
    //                                 "displayNameAttr" => 'cn',
    //                                 "notificationTypeAttr" => 'tiqrNotificationType',
    //                                 "notificationAddressAttr" => 'tiqrNotificationAddress',
    //                                 "secretAttr" => 'tiqrSecret',
    //                                 "isBlockedAttr" => 'tiqrIsBlocked',
    //                                 "loginAttemptsAttr" => 'tiqrLoginAttempts',
    //                          ),

    // "userstorage"       => array("type" => "pdo",
    //                              "table" => "tiqruser",
    //                              "dsn" => "mysql:dbname=tiqr;host=mysql.surfnet.nl",
    //                              "username" => "tiqr",
    //                              "password" => "*****"),

    // secretstore can de defined to store user shared secrets separately
    // same as userstorage, but also possible to use an external oath service for storage
    // "usersecretstorage"   => array("type" => "file", "path" => "../../users/secrets"),

    // "usersecretstorage"   =>  array("type" => "ldap",
    //                                 "host" => "ldap.surfnet.nl",
    //                                 "username" => "cn=Admin,ou=Persons,ou=Office,dc=SURFnet,dc=NL",
    //                                 "password" => "*****",
    //                                 "bindRequiresDn" => true,
    //                                 "accountDomainName" => "surfnet.nl",
    //                                 "baseDn" => "dc=surfnet,dc=nl",
    //                                 "userClass" => 'organizationalPerson',
    //                                 "dnPattern" =>  "cn=%s,ou=Persons,ou=Office,dc=surfnet,dc=nl",
    //                                 "idAttr" => 'cn',
    //                                 "secretAttr" => 'tiqrSecret',
    //                          ),

    // "usersecretstorage" => array("type" => "pdo",
    //                              "table" => "tiqrusersecret",
    //                              "dsn" => "mysql:dbname=tiqr;host=mysql.surfnet.nl",
    //                              "username" => "tiqr",
    //                              "password" => "*****"),

    // if you use this, the oathservice will also be used to generate ocra challenges and validate them
    //"usersecretstorage"   => array("type" => "oathserviceclient",
    //                               "apiURL" => "http://example.com/",
    //                               "consumerKey" => "43290843902890"),

);
