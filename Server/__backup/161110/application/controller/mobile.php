<?php
include("application/service/mobileservice.php");

class Mobile
{
  // Test 페이지
  public function index()
  {
    require "webapp/view/template/header.php";
    require "webapp/view/mobile/index.php";
    require "webapp/view/template/footer.php";
  }

  // 버스정류소 List 가져오기
  public function doGetBusStationList()
  {
    $service = new MobileService();
    $service->doGetBusStationList();
  }

  // 버스 List 가져오기
  public function doGetBusList()
  {
    $service = new MobileService();
    $service->doGetBusList();
  }

  // 버스 Info 가져오기
  public function doGetBusInfo()
  {
    $service = new MobileService();
    $service->doGetBusInfo();
  }

  // 날씨 Info 가져오기
  public function doGetWeatherInfo()
  {
    $service = new MobileService();
    $service->doGetWeatherInfo();
  }

  // 센서 데이터 가져오기
  public function doGetSensorData()
  {
    $service = new MobileService();
    $service->doGetSensorData();
  }
}
