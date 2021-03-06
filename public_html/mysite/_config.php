<?php

global $project;
$project = 'mysite';

global $databaseConfig;

// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

//Standard stuff
MySQLDatabase::set_connection_charset('utf8');

GD::set_default_quality(95);

DataObjectAsPage::enable_versioning();

//enable the search
FulltextSearchable::enable();


Object::add_extension('ViewListingPage', 'TwitterFeedExtension');

TwitterFeedExtension::$twitter_cache_enabled = true; //optional

TwitterFeedExtension::$twitter_cache_key = 'LL4fB4ioRJ'; //optional - required if $twitter_cache_enabled is true
TwitterFeedExtension::$twitter_cache_id = 'DRgxVWiclR'; //optional - required if $twitter_cache_enabled is true

TwitterFeedExtension::$twitter_consumer_key = 'zDglt0Syk4Odlk59V6cCjqsUw'; //required
TwitterFeedExtension::$twitter_consumer_secret = 'tmuYIpyQHggu7Ke3wgfeDRb8DyFHJGagxJAuAzsSaiCNcC9mea'; //required
TwitterFeedExtension::$twitter_oauth_token = '189499763-2W0xaKPSX6UMWHXJ5UyMB6noJtc82MvEWctBdrrI'; //required
TwitterFeedExtension::$twitter_oauth_token_secret = 'VHWQaIdXhBMYGLBNZTOvHXDN1MxiJ7RDxEYg0h6ylJY3x'; //required

TwitterFeedExtension::$twitter_search_term = '#silverstripe';