<?php
  require "functions.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>七並べ</title>
  <link rel="stylesheet" href="css/styles.css">
  <?php
    session_start();

    if (isset($_POST["reset"])) {
      unset($_SESSION['count']);
    }

    if (isset($_POST["view"])) {
      header("location: view.php");
    }

    if (!isset($_SESSION['count'])) {
      $_SESSION['count'] = 0;
      // $allCards = init_allCard();
      $_SESSION['allCards'] = init_allCard();
      // $cards = init_playCard();
      $_SESSION['cards'] = init_playCard();
      // $hands = array_slice($cards, 0, 24);
      $_SESSION['hands'] = array_slice($_SESSION['cards'], 0, 24);
      $_SESSION['handsCPU'] = array_slice($_SESSION['cards'], 24);
      $_SESSION['turn'] = 0; // 0:プレイヤー, 1:cpu
    } else {
      $_SESSION['count']++;
    }

    if (isset($_POST["hand"])) {
      // var_dump(isCheckRule($_POST["hand"], $_SESSION['allCards']));
      $_SESSION['turn'] = 1;
      if (isCheckRule($_POST["hand"], $_SESSION['allCards'])) {
        $tmp = arrayUpdate($_POST["hand"], $_SESSION['allCards'], $_SESSION['hands']);
        $_SESSION['allCards'] = $tmp[0];
        $_SESSION['hands'] = $tmp[1];
      }
    }
  ?>
</head>
<body>
  <form method="post" action="">
    <input type="submit" value="リセット" name="reset">
    <input type="submit" value="確認" name="view">
  </form>
  <div id=box></div>
  <?php
    if ($_SESSION['turn'] == 1) {
      $tmp1 = cpu($_SESSION['handsCPU'], $_SESSION['allCards']);
      $_SESSION['allCards'] = $tmp1[0];
      $_SESSION['handsCPU'] = $tmp1[1];
      $_SESSION['turn'] = 0;
    }
  ?>

  <div id=cpuHandNum>CPU's hands:
    <?php
      echo countCards($_SESSION['handsCPU']);
    ?>
  </div>

  <div id=hand>
    <form action="index.php" method="post">
      <?php
        sortArrayByKey($_SESSION['hands'], 'num');
        for ($i = 0; $i < count($_SESSION['hands']); $i++) {
          if (!$_SESSION['hands'][$i]['isField']) {
            outputHandCard($_SESSION['hands'][$i]);
          } else {
            continue;
          }
        }
      ?>
    </form>
  </div>
  <!-- <div id=view><?php require("view.php"); ?></div> -->
  <div id=field>
    <?php
      cardOutput($_SESSION['allCards']);
    ?>
  </div>
</body>
</html>
