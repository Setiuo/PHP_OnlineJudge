var lstsend;
$(document).ready(function() {
  var teml, tcde, teck, tuser;
  var felm = false,
    fcde = false,
    feck = false,
    fusr = false,
    fpwd = false,
    frep = false;
  $("#inputEmail").blur(function() {
    var reemil = $("#inputEmail").val();
    if (reemil != teml) {
      teml = reemil;
      $("#regemail").attr("class", "form-group has-warning");
      var regm = new RegExp(
        "^[0-9a-zA-Z_]+(\\.[0-9a-zA-Z_]+)*@[0-9a-zA-Z_]+(\\.[0-9a-zA-Z_]+)+$",
        "i"
      );

      if (!regm.test(reemil)) {
        felm = 0;
        $("#regemail").attr("class", "form-group has-error");
        $("#regemail").attr("data-content", "邮箱格式填写错误，请重新填写！");
      } else {
        felm = 1;
        $("#regemail").attr("class", "form-group has-success");
        $("#regemail").attr("data-content", "邮箱可以使用！");
      }
    }
  });
  $("#inputUsername").blur(function() {
    var rusr = $("#inputUsername")
      .val()
      .trim();
    if (rusr != tuser) {
      tuser = rusr;
      $("#regusername").attr("class", "form-group has-warning");
      var regu = new RegExp("^[-0-9a-zA-Z_\u4e00-\u9fa5]+$", "i");
      if (!regu.test(rusr)) {
        fusr = 0;
        $("#regusername").attr("class", "form-group has-error");
        $("#regusername").attr("data-content", "用户名填写错误，请重新填写！");
      } else {
        fusr = 1;
        $("#regusername").attr("class", "form-group has-success");
      }
    }
  });

  $("#inputPassword").blur(function() {
    var pwd1 = $("#inputPassword").val();
    var pwd2 = $("#inputrepwd").val();

    if (pwd1.length >= 6 && pwd1.length <= 20) {
      if (pwd2.length) {
        if (pwd1 == pwd2) {
          fpwd = true;
          frep = true;
          $("#regpassword").attr("class", "form-group has-success");
          $("#regpassword").attr("data-content", "您可以使用该密码！");

          $("#regcheckpwd").attr("class", "form-group has-success");
          $("#regcheckpwd").attr("data-content", "两次输入的密码一致。");
        } else {
          fpwd = false;
          $("#regpassword").attr("class", "form-group has-error");
          $("#regpassword").attr("data-content", "两次输入的密码不一致。");

          $("#regcheckpwd").attr("class", "form-group has-error");
          $("#regcheckpwd").attr("data-content", "两次输入的密码不一致。");
        }
      } else {
        fpwd = true;
        $("#regpassword").attr("class", "form-group has-success");
        $("#regpassword").attr("data-content", "您可以使用该密码！");
      }
    } else {
      fpwd = false;
      $("#regpassword").attr("class", "form-group has-error");
      $("#regpassword").attr("data-content", "密码长度太长或太短。");
    }
  });

  $("#inputrepwd").blur(function() {
    var pwd1 = $("#inputPassword").val();
    var pwd2 = $("#inputrepwd").val();

    if (pwd1) {
      if (pwd1 == pwd2) {
        frep = true;
        $("#regcheckpwd").attr("class", "form-group has-success");
        $("#regcheckpwd").attr("data-content", "两次输入的密码一致。");

        if (!fpwd) {
          if (pwd1.length >= 6 && pwd1.length <= 20) {
            fpwd = true;
            $("#regpassword").attr("class", "form-group has-success");
            $("#regpassword").attr("data-content", "您可以使用该密码！");
          }
        }
      } else {
        frep = false;
        fpwd = false;
        $("#regcheckpwd").attr("class", "form-group has-error");
        $("#regcheckpwd").attr("data-content", "两次输入的密码不一致。");
      }
    } else {
      frep = false;
      $("#regcheckpwd").attr("class", "form-group has-error");
      $("#regcheckpwd").attr("data-content", "还没有输入密码。");
    }
  });

  $("#inputCaptcha").blur(function() {
    var cap_code = $("#inputCaptcha").val();
    if (cap_code.length == 4) {
      $("#regcaptcha").attr("class", "form-group has-success");
      $("#regcaptcha").attr("data-content", "验证码格式正确！");
    } else {
      $("#regcaptcha").attr("class", "form-group has-error");
      $("#regcaptcha").attr("data-content", "验证码格式错误！");
    }
  });

  pstreginf = function() {
    var ers = "";
    if (felm == false) ers = ers + "邮箱填写错误！\n";
    if (fusr == false) ers = ers + "用户名填写错误！\n";
    if (fpwd == false) ers = ers + "密码填写错误！\n";
    if (frep == false) ers = ers + "重复密码填写错误！\n";

    if (ers.length == 0) {
      $("#SubmitRegisterButton").attr("disabled", "disabled");
      $("#SubmitRegisterButton").text("注册中...");

      $.post("/Php/RegisterUser.php", $("#registerform").serialize(), function(
        msg
      ) {
        var obj = eval("(" + msg + ")");

        if (obj.status === 0) {
          location.href = "/";
        } else if (obj.status === 1) {
          alert("注册失败！");
        } else if (obj.status === 3) {
          alert("注册失败，输入信息读取失败！");
        } else if (obj.status === 2) {
          alert("注册失败：用户已经存在！");
        } else if (obj.status === 4) {
          alert("注册失败：密码长度不符合要求！");
        } else if (obj.status === 5) {
          alert("验证码错误！");
        }

        if (obj.status != 0) {
          $(".captcha").attr("src", "/Php/Captcha.php?" + Math.random());
        }

        $("#SubmitRegisterButton").removeAttr("disabled");
        $("#SubmitRegisterButton").text("提交注册");
      });

      return false;
    } else {
      alert(ers);
      $(".captcha").attr("src", "/Php/Captcha.php?" + Math.random());
      return false;
    }
  };
});

$(function() {
  $("#regemail").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#regusername").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#regpassword").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#regcheckpwd").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#regcaptcha").popover({
    placement: "bottom",
    trigger: "hover"
  });
});
