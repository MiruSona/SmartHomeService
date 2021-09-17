<?php
include("application/service/noderedservice.php");

class NodeRED
{
  public function index()
  {
    require 'webapp/view/template/header.php';
    require 'webapp/view/nodered/index.php';
    require 'webapp/view/template/footer.php';
  }

  // 버스리스트 업데이트
  public function doUpdateBusDB()
  {
    $service = new NodeREDService();
    $service->doUpdateBusDB();
  }

  // 버스정류소 업데이트
  public function doUpdateBusStnDB()
  {
    $service = new NodeREDService();
    $service->doUpdateBusStnDB();
  }

}
