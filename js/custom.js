function pstsubmit() {
  $("#SubmitCodeButton").attr("disabled", "disabled");
  $("#SubmitCodeButton").text("上传中...");

  var code = myCodeMirror.getValue();
  var data = {};
  var t = $("#codeform").serializeArray();
  $.each(t, function() {
    data[this.name] = this.value;
  });
  data.code = code;

  $.post("/Php/SubmitCode.php", data, function(msg) {
    var obj = eval("(" + msg + ")");

    if (obj.status === 0) {
      location.href = "/Status.php";
    } else if (obj.status === 2) {
      alert("代码提交时发生错误！");
    } else if (obj.status === 3) {
      alert("您未登陆，无法提交代码！");
    } else if (obj.status === 4) {
      alert("未输入代码，提交失败！");
    } else if (obj.status === 5) {
      alert("提交时间间隔过短！");
    } else {
      alert("提交失败！");
    }

    $("#SubmitCodeButton").removeAttr("disabled");
    $("#SubmitCodeButton").text("提交代码");
  });

  return false;
}

function pstsubmit_contest() {
  $("#SubmitCodeButton").attr("disabled", "disabled");
  $("#SubmitCodeButton").text("上传中...");

  var code = myCodeMirror.getValue();
  var data = {};
  var t = $("#codeform").serializeArray();
  $.each(t, function() {
    data[this.name] = this.value;
  });
  data.code = code;

  $.post("/Contest/SubmitCode.php", data, function(msg) {
    var obj = eval("(" + msg + ")");

    if (obj.status === 0) {
      location.href = "/Contest/Status.php?ConID=" + obj.contestID;
    } else if (obj.status === 2) {
      alert("您未登陆，无法提交代码！");
    } else if (obj.status === 3) {
      alert("比赛已经结束！");
    } else if (obj.status === 4) {
      alert("比赛未开始！");
    } else if (obj.status === 5) {
      alert("您未报名比赛，无法提交代码！");
    } else if (obj.status === 6) {
      alert("未输入代码，提交失败！");
    } else if (obj.status === 7) {
      alert("提交时间间隔过短！");
    } else {
      alert("提交失败！");
    }

    $("#SubmitCodeButton").removeAttr("disabled");
    $("#SubmitCodeButton").text("提交代码");
  });

  return false;
}

var myCodeMirror = null;
var codemode = {
  C: "text/x-csrc",
  Gcc: "text/x-csrc",
  "G++": "text/x-c++src",
  "C++": "text/x-c++src",
  Java: "text/x-java",
  "Python2.7": {
    name: "python",
    version: 2,
    singleLineStringErrors: false
  },
  "Python3.6": {
    name: "python",
    version: 3,
    singleLineStringErrors: false
  },
  Python: "text/x-java"
};
$("#submitcode").on("shown.bs.modal", function() {
  if (!myCodeMirror)
    myCodeMirror = CodeMirror.fromTextArea($("#codeeditor")[0], {
      indentUnit: 4,
      lineNumbers: true,
      matchBrackets: true,
      mode: codemode[$("#language").val()]
    });
});
$("#language").click(function() {
  myCodeMirror.setOption(
    "mode",
    codemode[(localStorage.deflan = $(this).val())]
  );
});
$("[data-href]").click(function() {
  var self = $(this);
  location.href = self.attr("data-hrefhead") + $(self.attr("data-href")).val();
});

$("[data-status]").each(function() {
  var that = $(this);
  var cls = {
    Correct: "label-success",
    "Presentation Error": "label-danger",
    "Time Limit Exceeded": "label-danger",
    "Memory Limit Exceeded": "label-danger",
    "Wrong Answer": "label-danger",
    "Runtime Error": "label-danger",
    "Output Limit Exceeded": "label-danger",
    "Compile Error": "label-warning",
    "System Error": "label-danger"
  }[that.attr("data-status")];
  if (!cls) cls = "label-primary";
  that.addClass(cls);
});

$("[data-enter]").keydown(function(event) {
  if (event.keyCode == 13) $($(this).attr("data-enter")).click();
});

if (!localStorage.deflan) localStorage.deflan = "C++";
$("#language").val(localStorage.deflan);
$(function() {
  $("#popfq").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#popoh").popover({
    placement: "bottom",
    trigger: "hover"
  });
  $("#StatusTitle").popover({
    placement: "bottom",
    trigger: "hover"
  });
});

$(".captcha").click(function() {
  $(this).attr("src", "/Php/Captcha.php?" + Math.random());
});
