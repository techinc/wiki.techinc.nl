<?php
# This is a template for the default config for the Technologia Incognita wiki.
#
# This is for Mediawiki version 1.30.0
# Please add correct credentials where needed, and place this file in webroot/
#
# Maintained by Piele
# Version: 20180401

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
  exit;
}

## Fix that ssl is always on
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 443;

##################### CUSTOMIZE THESE SETTINGS TO YOUR NEED ###################

## Some site defaults
$wgSitename = " Technologia Incognita";
$wgMetaNamespace = "Technologia_Incognita";
$wgServer = "https://wiki.techinc.nl";
$wgLogo = "https://wiki.techinc.nl/images/c/cd/Techinc_logo.png";
$wgLocaltimezone = "Europe/Amsterdam";

## Database settings
$wgDBtype = "mysql";
$wgDBserver = "127.0.0.1";
$wgDBname = "DBNAME";
$wgDBuser = "DBUSER";
$wgDBpassword = "DBPASS";
$wgDBprefix = "";

## SMTP Settings
#$wgSMTP = array(
#  'host' => 'SMTPHOSTNAME',
#  'IDHost' => 'DOMAINNAME',
#  'port' => 587,
#  'username' => 'SMTPUSERNAME',
#  'password' => 'SMTPPASSWORD',
#  'auth' => true
#);

$wgEmergencyContact = "wiki-noreply@MYDOMAIN.COM";
$wgPasswordSender = "wiki-noreply@MYDOMAIN.COM";

# tr -c -d '0123456789abcdef' </dev/urandom | dd bs=64 count=1 2>/dev/null;echo
$wgSecretKey = "CHANGETHISVALUE";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
# tr -c -d '0123456789abcdef' </dev/urandom | dd bs=16 count=1 2>/dev/null;echo
$wgUpgradeKey = "CHANGETHISVALUE";

# Changing this will log out all existing sessions.
$wgAuthenticationTokenVersion = "1";

$wgUsePrivateIPs = true;
$wgUseSquid = true;
$wgSquidServers = array( '127.0.0.1', '10.209.200.1' );

## PART FOR DEBUGGING, ALSO SET $wgUseSquid = false; when enabling this ##
#$wgShowExceptionDetails = true;
#$wgShowSQLErrors        = true;
#$wgDebugComments        = true;
#$wgLogQueries           = true;
#$wgDebugDumpSql         = true;
#$wgDevelopmentWarnings  = true;
#$wgDebugProfiling       = true;
#$wgDebugTimestamps      = true;
#$wgResourceLoaderDebug  = true;
#$wgDebugToolbar         = true;

$wgFileExtensions = array('png','gif','jpg','jpeg','doc','xls','mpp','ppt','tiff','bmp','docx', 'xlsx','pptx','ps','odt','ods','odp','odg','svg','svgz','tar','gz','bzip','bz2','7z','ogg','mp3','wav','md','css','html','dxf','txt','pdf','dia');

$wgDefaultSkin = "vector";
wfLoadSkin( 'Vector' );

######################## END OF CUSTOMIZABLE AREA #############################
#
################ THESE VALUES SHOULD NOT HAVE TO BE CHANGED ###################
$wgScriptPath = "";
$wgArticlePath = "/$1";
$wgUsePathInfo      = true;
$wgScriptExtension  = ".php";
$wgResourceBasePath = $wgScriptPath;

## UPO means: this is also a user preference option
$wgEnableEmail = true;
$wgEnableUserEmail = true; # UPO
$wgEnotifUserTalk = false; # UPO
$wgEnotifWatchlist = false; # UPO
$wgEmailAuthentication = true;

# MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=utf8";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = false;

/////////////////////////////////// CACHING ///////////////////////////////////

$wgMainCacheType = 'redis';
$wgSessionCacheType = 'redis';  // same as WMF prod

// Not widely tested:
$wgMessageCacheType = 'redis';
$wgParserCacheType = 'redis';
$wgLanguageConverterCacheType = 'redis';


/** @see RedisBagOStuff for a full explanation of these options. **/
$wgObjectCaches['redis'] = array(
    'class'                => 'RedisBagOStuff',
    'servers'              => array( '127.0.0.1:6379' ),
    // 'connectTimeout'    => 1,
    // 'persistent'        => false,
    // 'password'          => 'secret',
    // 'automaticFailOver' => true,
);

$wgJobTypeConf['default'] = [
    'class'          => 'JobQueueRedis',
    'redisServer'    => '127.0.0.1:6379',
    'redisConfig'    => [],
    'claimTTL'       => 3600,
    'daemonized'     => true
];

//////////////////////////////// END OF CACHING ///////////////////////////////

$wgEnableUploads = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";
$wgShellLocale = "en_US.utf8";

$wgUseInstantCommons = false;
$wgPingback = false;
$wgCacheDirectory = "$IP/cache";
$wgLanguageCode = "en";

$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";

$wgDiff3 = "/usr/bin/diff3";

########################## SETTINGS FOR EXTENSIONS ############################

require_once("$IP/extensions/AdminLinks/AdminLinks.php");
require_once( "$IP/extensions/Arrays/Arrays.php" );

## ConfirmEdit / QuestyCaptcha settings
wfLoadExtension( 'ConfirmEdit' );
wfLoadExtension( 'ConfirmEdit/QuestyCaptcha' );

## Start QuestyCaptcha block
$wgCaptchaClass = 'QuestyCaptcha';

/// CHANGE THIS TO SOMETHING REAL
$wgCaptchaQuestions[] = array( 'question' => 'Which animal is displayed here? <img src="http://wiki.mysite.com/dog.jpg" alt="" title="" />', 'answer' => 'dog' );
## End QuestyCaptcha block

$wgGroupPermissions['*'            ]['skipcaptcha'] = false;
$wgGroupPermissions['user'         ]['skipcaptcha'] = false;
$wgGroupPermissions['autoconfirmed']['skipcaptcha'] = false;
$wgGroupPermissions['bot'          ]['skipcaptcha'] = true; // registered bots
$wgGroupPermissions['sysop'        ]['skipcaptcha'] = true;

$wgCaptchaTriggers['edit']          = false;
$wgCaptchaTriggers['create']        = false;
$wgCaptchaTriggers['addurl']        = false;
$wgCaptchaTriggers['createaccount'] = true;
$wgCaptchaTriggers['badlogin']      = true;


wfLoadExtension( 'MsUpload' );
wfLoadExtension( 'Nuke' );
wfLoadExtension( 'PageForms' );
require_once "$IP/extensions/PageImages/PageImages.php";
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'Popups' );
include_once("$IP/extensions/SemanticDrilldown/SemanticDrilldown.php");
include_once("$IP/extensions/SemanticInternalObjects/SemanticInternalObjects.php");
wfLoadExtension( 'TextExtracts' );
wfLoadExtension( 'UserMerge' );
wfLoadExtension( 'WikiEditor' );



$wgPFEnableStringFunctions = true;
$wgGroupPermissions['*']['edit'] = false;

$wgEnableDnsBlacklist = true;
$wgDnsBlacklistUrls = array( 'xbl.spamhaus.org', 'dnsbl.tornevall.org' );

#require_once "$IP/extensions/UserMerge/MergeUser.php";
$wgGroupPermissions['bureaucrat']['usermerge'] = true;
#$wgGroupPermissions['sysop']['usermerge'] = true;

// optional: default is array( 'sysop' )
//$wgUserMergeProtectedGroups = array( 'groupname' );

enableSemantics( 'MYDOMAIN.COM' );
$smwgPageSpecialProperties = array( '_MDAT', '_CDAT', '_NEWP', '_LEDT');
$wgPageForms24HourTime = true;
$wgGroupPermissions['*']['viewedittab'] = false;

#$wgPopupsBetaFeature = true;
$wgPopupsOptInDefaultState = '1';

$wgDefaultUserOptions['usebetatoolbar'] = 1;
$wgDefaultUserOptions['usebetatoolbar-cgd'] = 1;
$wgDefaultUserOptions['wikieditor-preview'] = 1;
$wgDefaultUserOptions['wikieditor-publish'] = 1;


$wgMSU_showAutoCat = true; // Files uploaded while editing a category page will be added to that category
$wgMSU_checkAutoCat = true; // Whether the checkbox for adding a category to a page is checked by default


