//事件处理程序兼容写法
function addEvent(target, type, handler) {
  if (target.addEventListener) {
    target.addEventListener(type, handler, false);
  } else {
    target.attachEvent("on" + type, function(event) {
      return handler.call(target, event);
    });
  }
}

(function() {
  //生成元素
  var progress = document.createElement("progress");
  progress.id = "progress";
  progress.style.cssText =
    'position:fixed;left:0;right:0;bottom:0;width:100%;height:12px;text-align:center;font:12px/12px "宋体"; z-index:3';
  document.body.appendChild(progress);

  //计算H
  var H;
  addEvent(window, "load", function() {
    progress.max = H = document.body.scrollHeight - $(window).height();
    progress.innerHTML = progress.title = Math.floor(0) + "%";
    progress.value = 0;
  });

  //计算h及radio
  addEvent(window, "scroll", function() {
    progress.max = H = document.body.scrollHeight - $(window).height();
    var h = document.documentElement.scrollTop || document.body.scrollTop;
    progress.value = h;
    var radio = h / H >= 1 ? 1 : h / H;
    progress.innerHTML = progress.title = Math.floor(100 * radio) + "%";
  });
})();
