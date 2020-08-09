document.getElementById("area").innerHTML = getNow();

function getNow() {
  var now = new Date();
  var year = now.getFullYear();
  var mon = now.getMonth()+1;
  var day = now.getDate();
  var hour = now.getHours();
  var min = now.getMinutes();
  var sec = now.getSeconds();

  var s = year + "年" + mon + "月" + day + "日" + hour + "時" + min + "分" + sec + "秒";
  return s;
  }
