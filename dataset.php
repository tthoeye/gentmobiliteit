<?php 
header('Content-type: application/json; charset=utf-8');
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once 'Config.php';
include_once CLASSES.'Response.class.php';
include_once CLASSES.'PoisDataset.class.php';
include_once CLASSES.'Util.class.php';

define("SERVICEURI", "http://apiHermes:api__M0b1l3@www.deparking.be/api/v1.1/parkings/Gent");

function getData() {
  // @TODO checks
  $json = file_get_contents(SERVICEURI);
  $parkings = json_decode($json, true);
  return $parkings["parkings"];
}

// Start building output Array

$now = new DateTime();

$output = Array();
$output["dataset"] = Array();
$output["dataset"]["updated"] = $now->format('c');
$output["dataset"]["created"] = "2014-05-19T11:10:30+02:00";
$output["dataset"]["lang"] = "nl-NL";
$output["dataset"]["author"] = array(
  "id" => "http://www.parkeerbedrijf.gent.be",
  "value" => "IVA Mobiliteitsbedrijf Stad Gent"
);
$output["dataset"]["license"] = array(
  "href" => "http://www.creativecommons.org/CC-A/3.0/license/xml",
  "term" => "CC BY 3.0"
);
$output["dataset"]["link"] = array(
  "href" => "http://www.parkeerbedrijf.gent.be",
  "term" => "source"
);
$output["dataset"]["updatefrequency"] = "1 minute";
$output["dataset"]["id"] = "http://data.gent.be/datasets/parkeergarages";
$output["dataset"]["poi"] = Array();

$parkings = getData();
foreach($parkings as $parking) {
  //print_r($parking);
  $output["dataset"]["poi"][] = array(
    "id" => $parking['name'],
    "title" => $parking['description'],
    "description" => $parking['description'],
    "category" => array(
       "parking", "gent", "mobiliteit"
    ),
    "location" => array(
      "point" => array(
        "term" => "centroid",
        "pos" => array(
           "srsName" => "http://www.opengis.net/def/crs/EPSG/0/4326",
           "posList" => $parking['latitude'] . " " . $parking["longitude"],
        )
      ),
      "address" => array(
        "value" => strip_tags($parking["address"]),
        "postal" => "9000",
        "city" => "Gent"
      )
    ),
    "attribute" => array(
      array(
        "term" => "Capacity",
        "type" => "string",
        "text" => $parking["totalCapacity"],
	      "tplIdentifier" => "#Citadel_parkCapacity"
	    ),
	    array(
        "term" => "Capacity",
        "type" => "string",
        "text" => $parking["availableCapacity"],
	      "tplIdentifier" => "#Citadel_parkSpaces"
	    ),
      array(
        "term" => "Capacity",
        "type" => "string",
        "text" => "http://www.mobiliteitgent.be/",
	      "tplIdentifier" => "#Citadel_website"
	    ),
    ),
  );
}

$poisDataset = Response::createFromArray(DatasetTypes::Poi, $output);
Util::printJsonObj(new Response($poisDataset));

?>