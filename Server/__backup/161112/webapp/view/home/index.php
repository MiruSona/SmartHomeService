<!-- Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <div class="container" id="sidebar-wrapper">
    <ul class="sidebar-nav">
      <span class="glyphicon glyphicon-remove sidebar-close" id="btnCloseSidebar"></span>
      <li class="row sidebar-profile">
        <ul>
          <li>
            <div class="sidebar-profile-name">
              <h3>관리자</h3>
            </div>
          </li>
          <li>
            <div class="sidebar-profile-img">
              <img src="webapp/resource/img/home/profile/coffee.jpg" />
            </div>
          </li>
        </ul>
      </li>

      <li class="row" menu="homeManage" dropdown="hide"><span class="glyphicon glyphicon-home"></span><a>집 관리</a></li>
      <ul class="row sidebar-home-manage">
        <li><span class="glyphicon glyphicon-time"></span><a>실시간 데이터</a></li>
        <li><span class="glyphicon glyphicon-cog"></span><a>하드웨어 관리</a></li>
        <!--
        <li><span class="glyphicon glyphicon-temperature"><img src="/webapp/resource/img/home/icon/weather.png" alt="온도" /></span><a>온도</a></li>
        <li><span class="glyphicon glyphicon-tint"></span><a>습도</a></li>
        <li><span class="glyphicon glyphicon-globe"></span><a>미세먼지</a></li>
        <li><span class="glyphicon glyphicon-gas"><img src="/webapp/resource/img/home/icon/pipe.png" alt="가스" /></span><a>가스</a></li>
        -->
      </ul>
      <li class="row" menu="bus"><span class="glyphicon glyphicon-bus"><img src="/webapp/resource/img/home/icon/transport.png" alt="버스" /></span><a>버스 도착정보</a></li>
      <li class="row" menu="userManage"><span class="glyphicon glyphicon-user"></span><a>사용자 관리</a></li>
      <li class="row" menu="updateProfile"><span class="glyphicon glyphicon-pencil"></span><a>정보수정</a></li>
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
    <div class="row" id="titlebar-wrapper">
      <div class="titlebar-nav">
        <span class="glyphicon glyphicon-menu-hamburger" id="btnOpenSidebar"></span>
        <span class="current-content-name">집 관리</span>
        <span class="glyphicon glyphicon-option-horizontal" id="btnOption"></span>
      </div>
    </div>

    <!-- Main Content -->
    <div id="main-content-wrapper">
      <!-- Responsive slider -->
      <div class="responsive-slider" data-spy="responsive-slider" data-autoplay="false">
        <div class="slides" data-group="slides">
          <ul>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 집 관리 -->
                <div class="home-manage" style="border: 1px solid #212121;">
                  <h3>집 관리</h3>
                  <div class="container">
                    <div class="row">
                      <div class="col-sm-6 realtime-data-graph">
                        <div id="realtimeDataChart"></div>
                      </div>
                      <div class="col-sm-6" style="border: 1px solid #212121;">
                        하드웨어 관리
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6" style="border: 1px solid #212121;">
                        ?
                      </div>
                      <div class="col-sm-6" style="border: 1px solid #212121;">
                        ?
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
                <div class="bus-arrival-info" style="border: 1px solid #212121;">
                  <h3>버스 도착정보 <small>(적용반경 : <span>0.5</span> km)</small></h3>
                  <div class="container bus-info-wrapper">

                  </div>
              </div>
            </li>
            <li>
              <div class="slide-body" data-group="slide">
                <img src="webapp/resource/img/home/slider/background.jpg">
                <!-- 사용자 관리 -->
                <div class="user-manage" style="border: 1px solid #212121;">
                  <h3>사용자 관리</h3>
                  <div class="container user-info-wrapper">
                    <div class="row user-info-container">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>이름</th>
                            <th>전화번호</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="">
                            <th scope="row">test</th>
                            <th scope="row">테스트</th>
                            <th scope="row">010-1234-5678</th>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    이건 없앨거임
                  </div>
                  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
                          <input type="text" class="form-control" name="profileID" readonly>
                          <p check="profileID"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-offset-2 col-sm-2 control-label">비밀번호</label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" name="profilePWD" placeholder="4~8자 입력" readonly>
                          <p check="profilePWD"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profilePWDCheck" class="col-sm-offset-2 col-sm-2 control-label">비밀번호확인</label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" name="profilePWDCheck" placeholder="비밀번호 확인" readonly>
                          <p check="profilePWDCheck"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileName" class="col-sm-offset-2 col-sm-2 control-label">이름</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profileName" placeholder="한글, 영문 가능(2~16자)" readonly>
                          <p check="profileName"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileAddress" class="col-sm-offset-2 col-sm-2 control-label">주소</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profileAddress" placeholder="주소 기재" readonly>
                          <p check="profileAddress"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profileEmail" class="col-sm-offset-2 col-sm-2 control-label">이메일</label>
                        <div class="col-sm-5">
                          <input type="email" class="form-control" name="profileEmail" placeholder="E-mail" readonly>
                          <p check="profileEmail"></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="profilePhoneNo" class="col-sm-offset-2 col-sm-2 control-label">전화번호</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="profilePhoneNo" placeholder="폰번호 '-' 없이 입력" readonly>
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
          <button type="button" id="btnUserManage" data-jump-to="3"></button>
          <button type="button" id="btnUpdateProfile" data-jump-to="4"></button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="webapp/resource/js/home/script.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/homeManage.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/busArrivalInfo.js"></script>
<script type="text/javascript" src="webapp/resource/js/home/userProfile.js"></script>
