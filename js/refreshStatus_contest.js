function refreshStatus() {
  var NeedRefresh = false;
  $("#StatusTable")
    .find("tr")
    .each(function() {
      var tdArr = $(this).children();
      var ConID_Data = tdArr.eq(0).text();
      var ConID = parseInt(ConID_Data);
      var RunID_Data = tdArr.eq(1).text();
      var RunID = parseInt(RunID_Data);
      var staticText = tdArr.eq(4).text();

      if (
        staticText == "等待分配 WAITING" ||
        staticText == "等待评测 PENDING" ||
        staticText == "正在编译 COMPILING" ||
        staticText == "正在运行 RUNNING"
      ) {
        $.ajax({
          type: "post",
          url: "refreshStatus.php",
          data: {
            ConID,
            RunID
          },
          dataType: "json",
          success: function(msg) {
            var data = "";

            if (msg != "") {
              data = eval("(" + msg + ")");
            }
            tdArr.eq(4).html(data.status);
            tdArr.eq(5).html(data.useTime);
            tdArr.eq(6).html(data.useMemory);
          },
          error: function(msg) {}
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
