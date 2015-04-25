# simplesamlphp-module-authtiqr
simplesamlphp module for tiqr authentication

# Introduction
This  module for simpleSAMLphp  allows easy tiqr integration into an existing simpleSAMLphp setup.
This document explains how to install the simpleSAMLphp plugin and how to configure it.

We are going to assume you have a working simpleSAMLphp installation. If you don’t, we’d like to refer you to the excellent documentation of simpleSAMLphp itself.

# Install

To install in simplesamlphp, use

	composer require tiqr/simplesamlphp-module-tiqr "dev-master"


# Downloading the plugin and required libraries

Download the latest version of the simpleSAMLphp plugin from the download page. There’s a package with a demo setup, but for this howto we’re focussing on the plugin itself, which is what you’ll be using to integrate tiqr into your own setup.

Also download the tiqr library from the download page, as the plugin as basically a wrapper around this library, which does all the hard work.

Install the plugin in the modules/ directory of your simpleSAMLphp setup (or symlink it if you want to keep the directory structure clean). Also do ‘touch enable’ inside the plugin directly to enable the plugin in simpleSAMLphp. In the rest of this post, we’re going to assume the plugin is in /var/www/simplesamlphp/modules/authTiqr

Install the library anywhere you like, but preferably outside your document root so it can’t be browsed directly. In the rest of this post we’ll assume it’s in /var/www/library/libTiqr – adjust accordingly if you have it installed somewhere else.

Finally you’ll need to download phpqrcode and if you plan on using step-up authentication you’ll also need Zend Framework for the push notifications. We’re assuming /var/www/library/zend-framework in this post; again, adjust accordingly.

# Configuration

Copy the file `config-templates/module_tiqr.php` to simplesamlphp/config and edit the file. Here’s a description of the configuration values:

- identifier: this is an identifier of your identity provider. This is typically a domainname and it’s what the user sees if they enroll an account. If you’re installing tiqr to enroll accounts at my.piggybank.com then my.piggybank.com would be a suitable identifier.
- name: The name of your service, e.g. ‘Piggybank’
- auth.protocol: Every tiqr based mobile app is identified by a set of url specifiers. If you use the tiqr application from the appstore, the value for this would be ‘tiqrauth’. If you build your own iphone app and that uses the url specifier ‘piggyauth’, then that’s what you’d configure here. It ties the identity provider to the mobile apps.
- enroll.protocol. Similar to the previous entry but for enrollment. ‘tiqrenroll’ for the default tiqr app, ‘piggyenroll’ if that’s what you used while compiling your own apps.
- ocra.suite: The challenge response algorithm to use for authentication. Must be a valid OCRA suite value (see the OCRA spec). Note that we don’t support counter and time based input, so you can only use OCRA suites that do not contain counter or time inputs. If you’re confused by this setting, you can leave it to the default, which results in the system using 10-digit hexadecimal challenges, a 6 digit numeric response, and SHA1 as the hashing algorithm.
- logoUrl: An url that points to your logo. The logo is automatically scaled down but to avoid high download times, try to stay under 250×250 resolution. The logo will be displayed in the app during enrollment and authentication steps.
- infoUrl: An url that contains a page with more information about your enrollment process. If a user enrolls for your service, this page is where they’ll go to for questions. You can provide any url that you like but typically it’s a page on your main company website.
- tiqr.path: This is the path where you installed the tiqr library, e.g. /var/www/libary/libTiqr. You can provide an absolute path or a relative path (relative to the location of this config file!)
- phpqrode.path: The location of phpqrcode, e.g. /var/www/library/phpqrcode
- apns.path: The location of apns-php, e.g. /var/www/library/apns-php
- apns.certificate: Your Apple push notification certificate.Note: if you use the tiqr app store app, you can’t send push notifications, to use this feature you need your own apps and your own certificates.
- apns.environment: Set to ‘sandbox’ if you’re testing the push notifications, set to ‘production’ if you use the push notifications in a production environment.
- c2dm.username: The username you use for your google Cloud 2 Device Messaging account (android push notifications).
- c2dm.password: The password for the C2DM account.
- c2dm.application: The application identifier of your custom android app, typically com.yourorganization.yourapp
- statestorage: This is the name of the storage class that you will be using to store temporary session data. The default is ‘file’ which stores the state information in the /tmp folder. If you have memcache installed, you can use ‘memcache’ instead. See the documentation inside the statestorage folder for memcache or file based specific configuration options.
- devicestorage: Tiqr supports exchanging hardware based devicetokens for more generic notification tokens. This is only required if you use the push notifications. You can use a tokenexchange server to handle the token swapping. Set this to ‘dummy’ if you do want push notifications but do not want to use a token exchange (not recommended).
- userstorage: Tiqr must store user secrets and other details for a user. By default this setting is set to ‘file’ which stores the data in JSON files in the specifified directory. While this is great for testing purposes, we recommend you implement your own user storage (e.g. your existing user database or an LDAP server). To do this, have a look at the userstorage subdirectory in the authTiqr diretory.

# Using tiqr for general authentication
To use tiqr for general authentication in a simpleSAMLphp setup, you should configure tiqr as an authsource. To do this, you have to add it to simpleSAMLphp/config/authsources.php like this:

    'authTiqr' =>
         array(          
             'authTiqr:Tiqr',
         ),
This allows users to login using the tiqr mechanism. The default implementation has a ‘create new account’ link in the login screen which allows uses to do a simple enrollment (typically you’ll want to integrate enrollment into your business processes, but this should get you started).
Using tiqr for step-up authentication
Tiqr works great as a step-up authentication method. This means that the user logs in using a regular username/password method first, and then confirms his identity using his phone and a tiqr app. To accomplish this, tiqr supports use as a processing filter. This way you can append tiqr authentication to an existing authsource. To do this, edit config/authsources.php and hook up tiqr like this:

    'default-sp' =>
        array(
            'saml:SP',
            'idp' => 'https://login.yourdomain.com/simplesaml/saml2/idp/metadata.php',
            'authproc' => array(
               10 => array(
                   'class' => 'authTiqr:Tiqr',
                   'uidAttribute' => 'urn:oid:0.9.2342.19200300.100.1.1',                  
                   'cnAttribute' => 'urn:oid:2.5.4.3',
               ),
           ),
       ),
                      
This configures a federated authsource that uses a hypothetical Identity Provider for the first login. It then attaches a processing filter to it by adding an entry to the authproc array. All it takes is defining that the class for this filter is authTiqr:Tiqr, and a definition of which attributres Tiqr should use as the display name and user id in its authentication process. (in this case urn:oid:… identifiers since our hypothetical IDP uses oids).

# Using tiqr for basic authentication, but another source for enrollment
There is a third way to use Tiqr. Suppose you want to use it as the primary authentication method for a site, but you don’t want anybody to be able to create accounts. Suppose you have an alternative login through another authsource that you want users to complete before they can enroll for Tiqr. This usecase is supported.

Suppose that we want the user to login to the authsource ‘example-userpass’ first (one of the simpleSAMLphp demo authsources) before they can enroll, and effectively link the phone identity to a userpass identity. In that case, we would configure tiqr in config/authsources.php like this:

    'authTiqr' => array(
   	    'authTiqr:Tiqr',
        'enroll.authsource'=>'example-userpass',
        'enroll.uidAttribute'=>'uid',
        'enroll.cnAttribute'=>'cn',
    ),

This configures authTiqr as the main authsource, but it tells tiqr that for enrollment, it should use example-userpass (or any other authsource you have defined in authsources.php). Similar to the previous example we need to map the userid and display name of the other authsource to tiqr’s fields, so that tiqr knows which fields to use for the user.
