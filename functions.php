<?php
  function outputHandCard($card) {
    $img = "images/";
    switch ($card['mark']) {
      case "spade":
        $img1 = $img . "s";
        break;
      case "heart":
        $img1 = $img . "h";
        break;
      case "diamond":
        $img1 = $img . "d";
        break;
      case "club":
        $img1 = $img . "c";
        break;
    }
    $img1 = $img1 . sprintf("%02d", $card['num']);
    $img1 = $img1 . ".png";
    $value = $card['mark'] . "," . $card['num'];
    echo "<button type=\"submit\" name=\"hand\" value=$value class=\"gazo\"><img src=" . $img1 . " width=100% height=100%><br></button>";
  }

  function allCardOutput() {
    $trumpMarks = array('club', 'heart', 'spade',  'diamond');
    $img = "images/";
    echo "<div>";
    foreach ($trumpMarks as $mark) {
      $img1 = $img;
      for ($i = 1; $i <= 13; $i++) {
        switch ($mark) {
          case "spade":
            $img1 = $img . "s";
            break;
          case "heart":
            $img1 = $img . "h";
            break;
          case "diamond":
            $img1 = $img . "d";
            break;
          case "club":
            $img1 = $img . "c";
            break;
        }
        $img1 = $img1 . sprintf("%02d", $i);
        $img1 = $img1 . ".png";
        echo "<img src=" . $img1 . " width=5% height=5%>";
      }
      echo "<br />";
    }
    echo "</div>";
  }

  function cardOutput($fieldCards) {
    $img = "images/";
    echo "<div>";
    foreach ($fieldCards as $card) {
      // var_dump($card);
      if (!$card["isField"]) {
        echo "<img src=images/z01.png width=5% height=5%>";
        if ($card["num"] == 13) {
          echo "<br />";
        }
        continue;
      }
      $img1 = $img;
      switch ($card["mark"]) {
        case "spade":
          $img1 = $img . "s";
          break;
        case "heart":
          $img1 = $img . "h";
          break;
        case "diamond":
          $img1 = $img . "d";
          break;
        case "club":
          $img1 = $img . "c";
          break;
      }
      $img1 = $img1 . sprintf("%02d", $card["num"]);
      $img1 = $img1 . ".png";
      echo "<img src=" . $img1 . " width=5% height=5%>";
      if ($card["num"] == 13) {
        echo "<br />";
      }
    }
    echo "</div>";
  }

  function init_allCard() {
    $cards = array();
    $trumpMarks = array('club', 'heart', 'spade',  'diamond');
    foreach ($trumpMarks as $mark) {
      for ($i = 1; $i <= 13; $i++) {
        if ($i != 7) {
          $cards[] = array(
            'mark' => $mark,
            'num' => $i,
            'isField' => false
          );
        } else {
          $cards[] = array(
            'mark' => $mark,
            'num' => $i,
            'isField' => true
          );
        }
      }
    }
    return $cards;
  }

  function init_playCard() {
    $cards = array();
    $trumpMarks = array('club', 'heart', 'spade',  'diamond');
    foreach ($trumpMarks as $mark) {
      for ($i = 1; $i <= 13; $i++) {
        if ($i == 7) {
          continue;
        }
        $cards[] = array(
          'mark' => $mark,
          'num' => $i,
          'isField' => false
        );
      }
    }
    shuffle($cards);
    return $cards;
  }

  function sortArrayByKey( &$array, $sortKey, $sortType = SORT_ASC ) {

    $tmpArray = array();
    foreach ( $array as $key => $row ) {
        $tmpArray[$key] = $row[$sortKey];
    }
    array_multisort( $tmpArray, $sortType, $array );
    unset( $tmpArray );
  }

  function arrayUpdate($cardInfo, $field, $hand) {
    $cardInfoArray = explode(",", $cardInfo);

    for ($i = 0; $i < count($field); $i++) {
      if ($field[$i]['mark'] == $cardInfoArray[0] && $field[$i]['num'] == $cardInfoArray[1]) {
        $field[$i]['isField'] = true;
      }
    }

    for ($i = 0; $i < count($hand); $i++) {
      if ($hand[$i]['mark'] == $cardInfoArray[0] && $hand[$i]['num'] == $cardInfoArray[1]) {
        $hand[$i]['isField'] = true;
      }
    }

    return array($field, $hand);
  }

  // ルール処理関数
  function isCheckRule($cardInfo, $field) {
    $cardInfoArray = explode(",", $cardInfo);

    for ($i = 0; $i < count($field); $i++) {
      if ($field[$i]['mark'] == $cardInfoArray[0] && $field[$i]['num'] == $cardInfoArray[1]) {
        if ($cardInfoArray[1] < 7) {
          if ($field[$i+1]['isField'] == true) {
            return true;
          } else {
            return false;
          }
        } else {
          if ($field[$i-1]['isField'] == true) {
            return true;
          } else {
            return false;
          }
        }
      }
    }

    return true;
  }

  // 相手のCPU
  function cpu($hand, $field) {
    $possibleCards = array();

    // var_dump($field);

    for ($i = 0; $i < count($hand); $i++) {
      if ($hand[$i]['isField']) {
        continue;
      }
      // var_dump(isCheckRule($hand[$i]['mark'] . "," . $hand[$i]['num'], $field));
      if (isCheckRule($hand[$i]['mark'] . "," . $hand[$i]['num'], $field)) {
         array_push($possibleCards, $hand[$i]);
      }
    }

    // var_dump($possibleCards);

    $tmp = arrayUpdate($possibleCards[0]['mark'] . "," . $possibleCards[0]['num'], $field, $hand);

    return $tmp;
  }

  function countCards($hands) {
    $count = 0;
    foreach ($hands as $card) {
      if (!$card["isField"]) {
        $count += 1;
      }
    }

    return $count;
  }
