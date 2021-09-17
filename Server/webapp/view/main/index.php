<div class="container main-wrapper">
  <header class="row header">

  </header>
  <section class="row">
    <form class="form-horizontal">
      <div class="form-group">
        <label for="loginID" class="col-sm-offset-2 col-sm-2 control-label">아이디</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="loginID" placeholder="ID(관리자:admin)">
        </div>
      </div>
      <div class="form-group">
        <label for="loginPWD" class="col-sm-offset-2 col-sm-2 control-label">비밀번호</label>
        <div class="col-sm-5">
          <input type="password" class="form-control" id="loginPWD" placeholder="PassWord">
          <p>(초기 비밀번호는 'roptop' 입니다.)</p>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
          <div class="checkbox">
            <label>
              <input type="checkbox" id="rememberID"> 아이디 기억
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
          <button type="button" class="col-sm-2 btn btn-primary" id="btnLogin">로그인</button>
          <button type="button" class="col-sm-2 btn btn-primary" data-toggle="modal" data-target="#modalReset">초기화</button>
        </div>
    </form>
  </section>
</div>

<!-- Account Reset Modal -->
<div class="modal fade" id="modalReset" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">계정 초기화</h4>
      </div>
      <div class="modal-body">
        <p>계정을 초기화 하시겠습니까?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-primary" id="btnReset">초기화</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="webapp/resource/js/main/script.js"></script>
