<!-- Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <div class="container" id="sidebar-wrapper">
    <ul class="sidebar-nav" id="menuSidebar">
      <span class="glyphicon glyphicon-remove sidebar-close" id="btnCloseSidebar"></span>
      <li class="row sidebar-profile">
        <ul>
          <li>
            <div class="sidebar-profile-name">
              <h3 id="profileUserName"></h3>
            </div>
          </li>
          <li>
            <div class="sidebar-profile-img">
              <img src="webapp/resource/img/home/profile/coffee.jpg" />
            </div>
          </li>
        </ul>
      </li>

      <li class="row active" menu="homeManage" dropdown="hide"><span class="glyphicon glyphicon-home"></span><a>집 관리</a></li>
      <ul class="row sidebar-home-manage">
        <li><span class="glyphicon glyphicon-time"></span><a>실시간 데이터</a></li>
        <li><span class="glyphicon glyphicon-cog"></span><a>하드웨어 관리</a></li>
      </ul>
      <li class="row" menu="busArrivalInfo"><span class="glyphicon glyphicon-bus"><img src="/webapp/resource/img/home/icon/transport.png" alt="버스" /></span><a>버스 도착정보</a></li>
      <li class="row" menu="weatherInfo"><span class="glyphicon glyphicon-globe"></span><a>날씨정보</a></li>
      <li class="row" menu="updateProfile"><span class="glyphicon glyphicon-user"></span><a>정보수정</a></li>
      <li class="row" menu="logout"><span class="glyphicon glyphicon-off"></span><a>로그아웃</a></li>
    </ul>
  </div>

  <!-- Logout Modal -->
  <div class="modal fade" id="modalLogout" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">로그아웃</h4>
        </div>
        <div class="modal-body">
          <p>정말 로그아웃을 하시겠습니까?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
          <button type="button" class="btn btn-primary" id="btnLogout">로그아웃</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="container" id="content-wrapper">
    <!-- Titlebar -->
    <div class="row titlebar-wrapper" id="titlebarWrapper">
      <div class="titlebar-nav">
        <span class="glyphicon glyphicon-menu-hamburger" id="btnOpenSidebar"></span>
        <span class="current-content-name">집 관리</span>
        <!-- <span class="glyphicon glyphicon-option-vertical" id="btnOption"></span> -->
      </div>
    </div>

    <!-- Main Content -->
    <div id="main-content-wrapper">
      <!-- Responsive slider -->
      <div class="responsive-slider" id="responsiveSlider" data-spy="responsive-slider" data-autoplay="false">
        <div class="slides" data-group="slides">
          <ul>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 집 관리 -->
                <div class="home-manage" style="border: 1px solid #212121;">
                  <h3>집 관리</h3>
                  <div class="container home-manage-wrapper">
                    <div class="row">
                      <div class="col-sm-6 realtime-data-graph">
                        <h4 class="text-center">실시간 실내데이터</h4>
                        <div id="realtimeDataChart"></div>
                      </div>
                      <div class="col-sm-6 hardware-manage">
                        <h4 class="text-center">하드웨어 관리</h4>
                        <div class="hardware-wrapper">
                          <div class="pi-wrapper">
                            <h5>라즈베리파이</h5>
                            <div id="piInfo"></div>
                          </div>
                          <div class="arduino-wrapper">
                            <h5>아두이노</h5>
                            <div id="arduinoInfo"></div>
                          </div>
                          <hr>
                          <button type="button" class="btn btn-primary btn-hardware" id="btnModifyHW" data-toggle="modal" data-target="#modalUpdateHW">수정하기</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 버스 도착정보 -->
                <div class="bus-arrival-info" id="busArrivalInfo" style="border: 1px solid #212121;">
                  <h3>버스 도착정보 <small>(적용반경 : <span></span> km)</small></h3>
                  <div class="container bus-info-wrapper" id="busInfoWrapper"></div>
              </div>
            </li>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 사용자 관리 -->
                <div class="weather-info" style="border: 1px solid #212121;">
                  <h3>날씨정보</h3>
                  <div class="container weather-info-wrapper">
                    <div class="weather-info-graph">
                      <div id="weatherChart"></div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 정보 수정 -->
                <div class="update-profile" style="border: 1px solid #212121;">
                  <h3>정보 수정</h3>
                  <div class="container user-profile-wrapper">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="profileID" class="col-sm-offset-2 col-sm-2 control-label">아이디</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profileID" disabled>
                          <p check="profileID"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-offset-2 col-sm-2 control-label">비밀번호</label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" name="profilePWD" placeholder="4~8자 입력" disabled>
                          <p check="profilePWD"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profilePWDCheck" class="col-sm-offset-2 col-sm-2 control-label">비밀번호확인</label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" name="profilePWDCheck" placeholder="비밀번호 확인" disabled>
                          <p check="profilePWDCheck"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileName" class="col-sm-offset-2 col-sm-2 control-label">이름</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profileName" placeholder="한글, 영문 가능(2~16자)" disabled>
                          <p check="profileName"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileAddress" class="col-sm-offset-2 col-sm-2 control-label">주소</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profileAddress" placeholder="주소 입력" disabled>
                          <p check="profileAddress"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileEmail" class="col-sm-offset-2 col-sm-2 control-label">이메일</label>
                        <div class="col-sm-5">
                          <input type="email" class="form-control" name="profileEmail" placeholder="E-mail 입력" disabled>
                          <p check="profileEmail"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profilePhoneNo" class="col-sm-offset-2 col-sm-2 control-label">전화번호</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profilePhoneNo" placeholder="폰번호 '-' 없이 입력" disabled>
                          <p check="profilePhoneNo"></p>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" class="btn btn-primary" id="btnModifyProfile">수정하기</button>
                          <button type="button" class="btn btn-default" id="btnCancelProfile" style="display: none;">취소</button>
                          <button type="button" class="btn btn-primary" id="btnChangeProfile" style="display: none;">수정완료</button>
                        </div>
                      </div>
                      <!-- Touch On/Off Test
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="button" class="btn btn-primary" id="btnTouchOn">TouchOn</button>
                          <button type="button" class="btn btn-default" id="btnTouchOff">TouchOff</button>
                      </div>
                    -->
                    </form>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="move-button">
          <button type="button" id="btnHomeManage" data-jump-to="1"></button>
          <button type="button" id="btnBusArrivalInfo" data-jump-to="2"></button>
          <button type="button" id="btnWeatherInfo" data-jump-to="3"></button>
          <button type="button" id="btnUpdateProfile" data-jump-to="4"></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- HW Modal -->
<div class="modal fade modal-hardware" id="modalUpdateHW" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">하드웨어</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <h4>[라즈베리파이]</h4>
          <div class="form-group">
            <label for="piAddress" class="col-sm-offset-2 col-sm-2 control-label">주소</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="piAddress" name="piAddress" placeholder="주소 입력">
              <p id="txtChkPhoneNo"></p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-offset-2 col-sm-2 control-label lbl-radius">반경(km)</label>
            <div class="col-sm-5 dropdown">
              <button type="button" id="btnRadius" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span id="radius"></span>&nbsp;&nbsp;
                <span class="caret"></span>
              </button>
              <ul class="col-sm-5 dropdown-menu" id="ddRadius">
                <li><a>0.5 km</a></li>
                <li><a>1.0 km</a></li>
                <li><a>1.5 km</a></li>
              </ul>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseHW">닫기</button>
        <button type="button" class="btn btn-primary" id="btnUpdateHW">수정하기</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="webapp/resource/js/home/script.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/homeManage.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/busArrivalInfo.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/weatherInfo.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/userProfile.js"></script>
