function refreshStatus() {
  var NeedRefresh = false;
  $("#StatusTable")
    .find("tr")
    .each(function() {
      var tdArr = $(this).children();
      var RunID_Data = tdArr.eq(0).text();
      var RunID = parseInt(RunID_Data);
      var staticText = tdArr.eq(3).text();

      if (
        staticText == "等待分配 Wating" ||
        staticText == "等待评测 Pending" ||
        staticText == "正在编译 Compiling" ||
        staticText == "正在运行 Running"
      ) {
        $.ajax({
          type: "post",
          url: "refreshStatus.php",
          data: {
            RunID: RunID
          },
          dataType: "json",
          success: function(msg) {
            var data = "";

            if (msg != "") {
              data = eval("(" + msg + ")");
            }
            tdArr.eq(3).html(data.status);
            tdArr.eq(4).html(data.useTime);
            tdArr.eq(5).html(data.useMemory);
          },
          error: function(msg) {
            console.log(msg);
          }
        });

        NeedRefresh = true;
      }
    });

  if (NeedRefresh) {
    setTimeout(refreshStatus, 1000);
  }
}

$(document).ready(function() {
  setTimeout(refreshStatus, 1000);
});
