<?php
class TestWeather
{
  public function index()
  {
    require 'webapp/views/_templates/header.php';
    require 'webapp/views/testweather/index.php';
    require 'webapp/views/_templates/footer.php';
  }

  public function getLocation()
  {
    require 'application/api/LocationAPI.php';

    $locationAPI = new LocationAPI();
    $location_ll = $locationAPI->getLocation(SERVER_IP);
    $location_xy = $locationAPI->getWeatherXY($location_ll['latitude'], $location_ll['longitude']);

    echo json_encode($location_xy);
  }

  public function getWeather()
  {
    require 'application/api/WeatherAPI.php';

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
      $x = $_POST["x"];
      $y = $_POST["y"];

      $weatherAPI = new WeatherAPI();
      $weatherInfo = $weatherAPI->parserAreaWeatherXMLxml($x, $y);
      echo json_encode($weatherInfo);
    }
  }
}
