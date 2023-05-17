<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/header.css">
  <style>
    /* CSS 스타일링 추가 */
    .selected {
      border-bottom: 2px solid white;
    }
  </style>
</head>
<body>
<header>
  <div class="header">
    <a class="a-logo" href="main.php">
      <span class="logo">BSSM SECOM</span>
    </a>
    <nav class="nav">
      <a class="a" href="status.php">기숙사 상태</a>
      <a class="a" href="manage.php">출입자 관리</a>
      <a class="a" href="control.php">원격제어</a>
    </nav>
  </div>
</header>

<script>
  // 현재 페이지 주소 가져오기
  var currentPage = window.location.href;

  // 네비게이션 링크들을 가져옴
  var navLinks = document.getElementsByClassName('a');

  // 각 링크를 순회하면서 현재 페이지와 주소가 일치하는지 확인
  for (var i = 0; i < navLinks.length; i++) {
    var link = navLinks[i].href;

    // 현재 페이지와 주소가 일치하면 해당 링크에 'selected' 클래스를 추가
    if (currentPage === link) {
      navLinks[i].classList.add('selected');
    }
  }
</script>

</body>
</html>
