    <div class="container">
      <header class="row header">

      </header>

      <section class="row">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="loginID" class="col-sm-offset-2 col-sm-2 control-label">아이디</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="loginID" placeholder="ID">
            </div>
          </div>
          <div class="form-group">
            <label for="loginPWD" class="col-sm-offset-2 col-sm-2 control-label">비밀번호</label>
            <div class="col-sm-5">
              <input type="password" class="form-control" id="loginPWD" placeholder="PassWord">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              <div class="checkbox">
                <label>
                  <input type="checkbox"> 아이디 기억
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              <button type="button" class="col-sm-2 btn btn-primary" id="btnLogin">로그인</button>
              <button type="button" class="col-sm-2 btn btn-primary" data-toggle="modal" data-target="#modalRegister">회원가입</button>
            </div>
            <hr>
            <div class="col-sm-offset-4 col-sm-8">
              <button type="button" class="col-sm-4 btn btn-primary" id="btnTestDB">DB테스트</button>
            </div>
            <hr>
            <div class="col-sm-offset-4 col-sm-8">
              <button type="button" class="col-sm-4 btn btn-primary" id="btnTestWeather">날씨테스트</button>
            </div>
            <hr>
            <div class="col-sm-offset-4 col-sm-8">
              <button type="button" class="col-sm-4 btn btn-primary" id="btnTestBus">버스테스트</button>
            </div>
          </div>
        </form>
      </section>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">회원가입</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label for="registerID" class="col-sm-offset-2 col-sm-2 control-label">아이디</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="registerID" placeholder="ID">
                </div>
              </div>
              <div class="form-group">
                <label for="registerPWD" class="col-sm-offset-2 col-sm-2 control-label">비밀번호</label>
                <div class="col-sm-5">
                  <input type="password" class="form-control" id="registerPWD" placeholder="PWD">
                </div>
              </div>
              <div class="form-group">
                <label for="registerPWDCheck" class="col-sm-offset-2 col-sm-2 control-label">비밀번호확인</label>
                <div class="col-sm-5">
                  <input type="password" class="form-control" id="registerPWDCheck" placeholder="PWD Check">
                </div>
              </div>
              <div class="form-group">
                <label for="registerName" class="col-sm-offset-2 col-sm-2 control-label">이름</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="registerName" placeholder="NAME">
                </div>
              </div>
              <div class="form-group">
                <label for="registerBirth" class="col-sm-offset-2 col-sm-2 control-label">생년월일</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="registerBirth" placeholder="BIRTH">
                </div>
              </div>
              <div class="form-group">
                <label for="registerPhoneNo" class="col-sm-offset-2 col-sm-2 control-label">전화번호</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="registerPhoneNo" placeholder="PHONENO">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            <button type="button" class="btn btn-primary" id="btnRegister">신청</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="<?php echo PATH_JS; ?>main.js"></script>
