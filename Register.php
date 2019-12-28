<!DOCTYPE html>

<html lang="zh-cn">

<?php require_once('Php/HTML_Head.php') ?>

<body>
  <?php require_once('Php/Page_Header.php') ?>

  <div class="container animated fadeInLeft">

    <div class="well bs-component">
      <form class="form-horizontal" autocomplete="off" onsubmit="return(pstreginf());" id="registerform">
        <fieldset>
          <legend><?php echo $WebName ?>用户注册</legend>

          <div class="form-group" id="regemail" rel="popover" data-content="请填写正确的email地址" data-original-title="Email">
            <label for="inputEmail" class="col-lg-2 control-label">Email</label>
            <div class="col-lg-10">
              <input id="inputEmail" name="email" type="email" placeholder="不可更改" class="form-control" rel="popover" data-content="请填写正确的email地址！" data-html="true">
            </div>
          </div>

          <div class="form-group" id="regusername" rel="popover" data-content="用户名可以由中文，字母，数字，下划线组成。" data-original-title="用户名">
            <label for="inputUsername" class="col-lg-2 control-label">用户名</label>
            <div class="col-lg-10">
              <input id="inputUsername" name="username" type="text" placeholder="中文 字母 数字 下划线" class="form-control">
            </div>
          </div>

          <div class="form-group" id="regpassword" rel="popover" data-content="密码长度最少位，最多20位。" data-original-title="密码">
            <label for="inputPassword" class="col-lg-2 control-label">密码</label>
            <div class="col-lg-10">
              <input id="inputPassword" name="password" type="password" placeholder="6-20位" class="form-control">
            </div>
          </div>

          <div class="form-group" id="regcheckpwd" rel="popover" data-content="请再输入一次密码。" data-original-title="确认密码">
            <label for="inputrepwd" class="col-lg-2 control-label">确认密码</label>
            <div class="col-lg-10">
              <input id="inputrepwd" name="repassword" type="password" placeholder="确认密码" class="form-control">
            </div>
          </div>

          <div class="form-group" id="regcaptcha" rel="popover" data-original-title="验证码" data-content="验证码3分钟内有效">
            <label for="inputCaptcha" class="col-lg-2 control-label">验证码</label>
            <div class="col-lg-10">
              <input id="inputCaptcha" name="captcha" class="form-control" placeholder="验证码">
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
              <img class="captcha" src="/Php/Captcha.php" />
            </div>
          </div>


          <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
              <button id="SubmitRegisterButton" type="submit" class="btn btn-primary">提交注册</button>
            </div>
          </div>

        </fieldset>
      </form>

    </div>
  </div>

  <?php
  $PageActive = '#register_user';
  require_once('Php/Page_Footer.php');
  ?>
  <script src="/js/reg.js?v=<?php echo $OJ_Version ?>" type="text/javascript"></script>

</body>

</html>