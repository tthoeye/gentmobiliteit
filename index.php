<?php include_once 'Config.php'; ?>
<!DOCTYPE html>
<html>
    <head>         
        <title>Parking Template</title> 

        <!--------------- Metatags ------------------->   
        <meta charset="utf-8" />
        <!-- Not allowing the user to zoom -->    
        <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0"/>
        <!-- iphone-related meta tags to allow the page to be bookmarked -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

        <!--------------- CSS files ------------------->    
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
        <link rel="stylesheet" href="css/my.css" />
            
        <!--------------- Javascript dependencies -------------------> 
            
        <!-- Google Maps JavaScript API v3 -->    
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?sensor=false">
        </script>
        <!-- Google Maps Utility Library - Infobubble -->     
        <script type="text/javascript"
                src = "http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js">
        </script>
        <!-- Overlapping markers Library: Deals with overlapping markers in Google Maps -->
        <script src="http://jawj.github.com/OverlappingMarkerSpiderfier/bin/oms.min.js"></script>  
        <!-- jQuery Library --> 
        <script src="js/jquery-1.8.2.min.js"></script>
        <!-- jQuery Mobile Library -->
        <script src="js/jquery.mobile-1.2.0.min.js"></script>  
        <!-- Page params Library: Used to pass query params to embedded/internal pages of jQuery Mobile -->    
        <script src="js/jqm.page.params.js"></script>
        <!-- Template specific functions and handlers -->    
        <script src="js/parking-lib.js"></script>   
         
    </head> 

    <body>
        <!-- Home Page: Contains the Map -->
        <div data-role="page" id="page1" class="page">
            <header data-role="header" data-posistion="fixed" data-id="constantNav" data-fullscreen="true">
                <a href="#info" data-rel="dialog" data-icon="info" data-iconpos="notext" data-theme="b" title="Info">&nbsp;</a>
                <span class="ui-title">Find Parking Lots</span>
                <div data-role="navbar" class="navbar">
                    <ul>
                        <li><a href="#" class="pois-nearme" data-theme="b">Near me</a></li>
                        <li><a href="#" class="pois-showall ui-btn-active" data-theme="b">Show all</a></li>
                        <li><a href="#page2" class="pois-list" data-theme="b">List</a></li>
                    </ul>
                </div><!-- /navbar -->
            </header>

            <div data-role="content" id="map-container">
                <div id="map_canvas" class="map_canvas"></div>
            </div>
        </div>       

        <!-- List Page: Contains a list with the results -->
        <div data-role="page" id="page2" class="page">

            <header data-role="header" data-posistion="fixed" data-id="constantNav">
                <a href="#info" data-rel="dialog" data-icon="info" data-iconpos="notext" data-theme="b" title="Info">&nbsp;</a>
                <span class="ui-title"> Find Parking Lots List </span>
                <a href="" data-icon="back" data-iconpos="notext" data-theme="b" title="Back" data-rel="back">&nbsp;</a>
                <div data-role="navbar" class="navbar">
                    <ul>
                        <li><a href="#" class="pois-nearme" data-theme="b">Near me</a></li>
                        <li><a href="#" class="pois-showall" data-theme="b">Show all</a></li>
                        <li><a href="#page2" class="pois-list ui-btn-active" data-theme="b">List</a></li>
                    </ul>
                </div><!-- /navbar -->
            </header>
                
            <div class="list-container">
                <div class="list-scroll-container">
                    <div data-role="content" id="list" class="parking">
                        <ul data-role='listview' data-filter='true' data-theme='b'>
                        <!-- dynamically filled with data -->
                        </ul>
                    </div><!--list-->
                </div><!--list-scroll-container-->
            </div><!--list-container-->
        </div><!-- /page -->
        
        <!-- Details Page: Contains the details of a selected element -->
        <div data-role="page" id="page3" data-title="Parking Details" class="page">
            <header data-role="header" data-posistion="fixed" data-fullscreen="true">
                <a href="#info" data-rel="dialog" data-icon="info" data-iconpos="notext" data-theme="b" title="Info">&nbsp;</a>
                <span class="ui-title">Parking details</span>
                <a href="" data-icon="back" data-iconpos="notext" data-theme="b" title="Back" data-rel="back">&nbsp;</a>
                <div data-role="navbar" class="navbar">
                    <ul>
                        <li><a href="#" class="pois-nearme" data-theme="b">Near me</a></li>
                        <li><a href="#" class="pois-showall" data-theme="b">Show all</a></li>
                        <li><a href="#page2" class="pois-list" data-theme="b">List</a></li>
                    </ul>
                </div><!-- /navbar --> 
            </header>
            
            <div class="list-container">
                <div class="list-scroll-container">
                    <div data-role="content" id="item">
                        <!-- dynamically filled with data -->
                    </div><!--item-->
                </div><!--list-scroll-container-->
            </div><!--list-container-->
        </div><!-- /page -->
            
        <!-- Info Page: Contains info of the currently used dataset -->  
        <div data-role="page" id="info">
            <header data-role="header">
                <span class="ui-title">Metadata Information</span>	
            </header>
            <article data-role="content">
                <ul data-role="listview">
                    <!-- dynamically filled with data -->
                </ul> 
            </article> 
        </div>    
            

        <script type="text/javascript">
                    /****************** Global js vars ************************/
                    
                    /* GLobal map object */
                    var map;
                    /* List of pois read from json object */
                    var pois = {};
                    /* List of dataset metadata read from json object */
                    var meta = {};
                    /* Remember if a page has been opened at least once */
                    var lastLoaded = '';
                    /* Remember if 'near me' marker is loaded */
                    var isNearMeMarkerLoaded = false;
                    /* 
                     * Remember if map was initialy loaded. If not
                     * it means we loaded list page
                     */
                    var isMapLoaded = false;
                    /*
                     * Keeps page id to emulate full url using querystring
                     */
                    var pageId = 0;
                    
                    /* The coordinates of the center of the map */
                    
                    var mapLat = <?php echo MAP_CENTER_LATITUDE; ?>;
                    var mapLon = <?php echo MAP_CENTER_LONGITUDE; ?>;
					var mapZoom = <?php echo MAP_ZOOM; ?>;
                    
                    /* The url of the dataset */    
                    var datasetUrl = "<?php echo DATASET_URL; ?>";

                    /* Just call the initialization function when the page loads */
                    $(window).load(function() {
                        globalInit();
                    });                    
                                        
        </script>
    </body>
</html>