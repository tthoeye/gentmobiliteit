<?php
/*
 * Configuration settings
 */

// directories

/***********************************************************
 * Don't forget to insert the web root directory 
 * for example: "C:/wamp/www/" is the default root web dir in a standard wampServer setup)
 ***********************************************************
 */
define("HTDOCS_ROOT", "");  
		
/*********************************************************** 
 * Replace 'localhost' with your IP address, 
 * if you want to access the template with a mobile device
 * connected to the same network 
 ***********************************************************
 */
define("SERVERNAME", "http://localhost/"); 

/*********************************************************** 
 * Leave all the settings below unchanged if you only want
 * to run the template with the default dataset and parameters.
 ***********************************************************
 */
		
define("BASE_DIR", "Citadel-Parkings-Template/" );
define("CLASSES_DIR", "php/");
define("CLASSES", HTDOCS_ROOT . BASE_DIR . CLASSES_DIR);
 
define("DEBUG", true);

// dataset
define("DATASET_FILE", HTDOCS_ROOT . BASE_DIR ."data/CitadeL-POI-common-Manchester.json");
define("DATASET_ID", 47);
define("DATASET_URL", SERVERNAME . BASE_DIR . "dataset.php");
define("USE_DATABASE", false);

// Map Options (coords point to center of Manchester)
define("MAP_CENTER_LATITUDE", 53.477057);
define("MAP_CENTER_LONGITUDE", -2.238957);

//  Athens city center Coords
//define("MAP_CENTER_LATITUDE", 37.992208);
//define("MAP_CENTER_LONGITUDE", 23.731598);


define("MAP_ZOOM", 16);

// database
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_HOSTNAME", "127.0.0.1");
define("DB_PORT", "3306");
define("DB_NAME", "citadel");
?>
