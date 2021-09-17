<h1>history_tbl 접근 테스트</h1>
<form class="form-horizontal">
  <div class="form-group">
    <label for="btnSensor" class="col-sm-2 control-label">Sensor</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="iptSensor" placeholder="idx 값(test:1~4)">
    </div>
    <div class="col-sm-offset-4 col-sm-8">
      <button type="button" class="col-sm-2 btn btn-primary" id="btnSensor">전송</button>
    </div>
  </div>
  <div class="form-group">
    <label for="btnMac" class="col-sm-2 control-label">Mac</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="iptMac" placeholder="mac 주소(test:1001~1004)">
    </div>
    <div class="col-sm-offset-4 col-sm-8">
      <button type="button" class="col-sm-2 btn btn-primary" id="btnMac">전송</button>
    </div>
  </div>
  <div class="form-group">
    <label for="btnMac" class="col-sm-2 control-label">기간</label>
    <div class="col-sm-10">
      <div class="col-sm-4">
        <input type="text" class="col-sm-3 form-control" id="iptFrom" placeholder="from(yyyymmdd)" value="20161001">
      </div>
      <div class="col-sm-2">
        <span>~</span>
      </div>
      <div class="col-sm-4">
        <input type="text" class="col-sm-3 form-control" id="iptTo" placeholder="to(yyyymmdd)" value="20161013">
      </div>
    </div>
    <div class="col-sm-offset-4 col-sm-8">
      <button type="button" class="col-sm-2 btn btn-primary" id="btnDate">전송</button>
    </div>
  </div>
  <div class="form-group">
    <label for="btnMac" class="col-sm-2 control-label">종류</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="iptType" placeholder="type(test:D,T,H,G)">
    </div>
    <div class="col-sm-offset-4 col-sm-8">
      <button type="button" class="col-sm-2 btn btn-primary" id="btnType">전송</button>
    </div>
  </div>
</form>

<script type="text/javascript" src="<?php echo PATH_JS; ?>testdb.js"></script>
