<?php
/*
    Plugin Name: Shabbos and Yom Tov
    Version: 1.6
    Author: Shabbos Commerce
    Author URI: mailto:shabboscommerce@gmail.com
    Description: Automatically hide shopping cart or other content on Shabbos and Yom Tov and disply a message to the user.
*/

    define ('SHABBOS_PATH' , dirname( __FILE__ ));

    function Shabbos_and_YomTov( $atts, $content = null ) {
      
      $shabbos_options = get_option('shabbos_options');
       
      $a =  array(
        'timezone'      => 'America/New_York',
        'testdate'      =>  NULL,
        'passover'      => 'Passover',
        'shavuos'       => 'Shavuos',
        'sukkos'        => 'Sukkos',
        'rosh_hashanah' => 'Rosh Hashanah',
        'yom_kippur'    => 'Yom Kippur',
        'yom_tov_msg'   => 'We\'re sorry but we are currently closed due to %yomtov%.',
        'shabbos_msg'   => 'We\'re sorry but we are currently closed due to Shabbos.',
        'chol_hamoed'   => 'closed',
        'latitude'      => '40.63',
        'longitude'     => '-73.98',
        'candle'        => '15',
        'dusk'          => '72',
        'class'         => NULL,
        'one-day'       => 'no'

       );
      
      foreach ($shabbos_options as $option=>$setting){
          $a[$option]=stripslashes($setting);
      }
      
      $a = shortcode_atts( $a, $atts );


      //$result = $content;
      $timezone = $a['timezone'];
      $latitude = $a['latitude'];
      $longitude = $a['longitude'];
      $candle =$a['candle'];
      $dusk =$a['dusk'];
      $a['one-day']= strtolower($a['one-day']);
      $a['chol_hamoed'] = strtolower($a['chol_hamoed']);

      if( isset($a['testdate']) ) {
        $now = $a['testdate'];
      } else {
        $now = date(); 
      }


      $date=date_create($now,timezone_open($timezone));
//echo date_format($date,"Y/m/d H;m w l D");
      $dayofweek = date_format($date,"D");
      $time= date_format($date,"H:i:s");

      $date2 = date_format($date,"m/d/Y");

      $gmonth =date_format($date,"n");
      $gday =date_format($date,"j");
      $gyear =date_format($date,"Y");

      $jdNumber = gregoriantojd($gmonth, $gday, $gyear);
//echo $jdNumber . '<br>';
      $jewishDate = jdtojewish($jdNumber);
//echo $jewishDate. '<br>';
      list($jewishMonth, $jewishDay, $jewishYear) = explode('/', $jewishDate);
      $jewishMonthName = getJewishMonthName($jewishMonth, $jewishYear);
//echo "<p>Today is $jewishDay $jewishMonthName $jewishYear</p>\n";


      if ($candle < '15') {$candle = '15';}
      if ($dusk < '72') {$dusk = '72';}

      $Eatts = array (
        'latitude'  => $latitude,
        'longitude' => $longitude,
        'timezone'  => $timezone,
        'date2'     => $date2,
        'candle'    => $candle,
        'date'      => $date,
        'dusk'      => $dusk
      );


// Check for Shabbos

      if ($dayofweek == 'Fri') {
        if (iserev($Eatts)){
          $result = $a['shabbos_msg'];
        }
      } elseif ($dayofweek == 'Sat') {
        if (!ismotzai($Eatts)){
          $result = $a['shabbos_msg'];
        }
      }



// Check for Yom Tov

      switch ($jewishMonthName) {
        case "Nisan":
        switch ($jewishDay) {
          case "14":
          if (iserev($Eatts)){
            $yomtov = $a['passover'];
          }
          break;
          
          case "15":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['passover'];
          } elseif ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          } elseif (!ismotzai($Eatts)){
            $yomtov = $a['passover'];
          }
          break;
          
          case "16":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          } elseif ( $a['one-day'] == 'no') {
            if (!ismotzai($Eatts)){
              $yomtov = $a['passover'];
            }
          }
          break;
          
          case "17":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          }
          break;
          
          case "18":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          }
          break;
          
          case "19":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          }
          break;

          case "20":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['passover'];
          } elseif (!iserev($Eatts)){
            $yomtov = $a['passover'];
          }
          break;

          case "21":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['passover'];
          } elseif (!ismotzai($Eatts)){
            $yomtov = $a['passover'];
          }
          break;

          case "22":
          if ( $a['one-day'] == 'no') {
            if (!ismotzai($Eatts)){
              $yomtov = $a['passover'];
            }
          }
          break;

        }
        break;
        case "Sivan":
        switch ($jewishDay) {
          case "5":
          if (iserev($Eatts)){
            $yomtov = $a['shavuos'];
          }
          break;
          
          case "6":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['shavuos'];
          } elseif (!ismotzai($Eatts)){
            $yomtov = $a['shavuos'];
          }
          break;
          
          case "7":
          if ( $a['one-day'] == 'no') {
            if (!ismotzai($Eatts)){
              $yomtov = $a['shavuos'];
            }
          }
          break;
        }
        break;
        
        case "Elul":
        switch ($jewishDay) {
          case "29":
          if (iserev($Eatts)){
            $yomtov = $a['rosh_hashanah'];
          }
          break;
        }
        break;
        
        case "Tishri":
        switch ($jewishDay) {
          case "1":
          $yomtov = $a['rosh_hashanah'];
          break;
          
          case "2":
          if (!ismotzai($Eatts)){
            $yomtov = $a['rosh_hashanah'];
          }
          break;
          
          case "9":
          if (iserev($Eatts)){
            $yomtov = $a['yom_kippur'];
          }
          break;
          
          case "10":
          if (!ismotzai($Eatts)){
            $yomtov = $a['yom_kippur'];
          }
          break;
          
          case "14":
          if (iserev($Eatts)){
            $yomtov = $a['sukkos'];
          }
          break;
          
          case "15":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['sukkos'];
          } elseif ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          } elseif (!ismotzai($Eatts)){
            $yomtov = $a['sukkos'];
          }
          
          break;
          
          case "16":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['sukkos'];
          } elseif ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          } elseif (!ismotzai($Eatts)){
            $yomtov = $a['sukkos'];
          }
          break;
          
          case "17":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          }
          break;
          
          case "18":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          }
          break;
          
          case "19":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          }
          break;

          case "20":
          if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          }
          break;

          case "21":
          if (iserev($Eatts)){
            $yomtov = $a['sukkos'];
          }if ($a['chol_hamoed'] == 'closed') {
            $yomtov = $a['sukkos'];
          }
          break;

          case "22":
          if ( $a['one-day'] == 'no') {
            $yomtov = $a['sukkos'];
          } else {
            if (!ismotzai($Eatts)){
              $yomtov = $a['sukkos'];
            }
          }
          break;
          
          case "23":
          if ( $a['one-day'] == 'no') {
            if (!ismotzai($Eatts)){
              $yomtov = $a['sukkos'];
            }
          }
          break;
        }
        break;
      }
//echo $a['sukkos'].'<br>'.$a['chol_hamoed'].'<br>'.$a['passover'].'<br>'.$a['shavuos'].'<br>'.$a['rosh_hashanah'].'<br>'.$a['yom_tov_msg'];
      if (isset($yomtov)) {
        $yom_tov_msg = str_replace('%yomtov%', $yomtov, $a['yom_tov_msg'] );
        $result = $yom_tov_msg;
      }
      
      if (isset ($result)) {
        if (isset ($a['class'])) {
          return '<div class='.$a['class'].'>'. $result .'</div>';
        } else {
          return $result;
        }
      } else {
        return do_shortcode( $content );
      }
    }
    if (function_exists('wp')) {
      add_shortcode('Shabbos_and_YomTov', 'Shabbos_and_YomTov');
          if (is_admin()) {
              //$plugin_folder = plugin_dir_path(__FILE__);
              require_once (SHABBOS_PATH . '/admin.php');
          }
    }



//http://www.david-greve.de/luach-code/jewish-php.html
    function isJewishLeapYear($year) {
      if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
        $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
        $year % 19 == 17)
        return true;
        else
          return false;
      }

      function getJewishMonthName($jewishMonth, $jewishYear) {
        $jewishMonthNamesLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
          "Shevat", "Adar I", "Adar II", "Nisan",
          "Iyar", "Sivan", "Tammuz", "Av", "Elul");
        $jewishMonthNamesNonLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
          "Shevat", "", "Adar", "Nisan",
          "Iyar", "Sivan", "Tammuz", "Av", "Elul");
        if (isJewishLeapYear($jewishYear))
          return $jewishMonthNamesLeap[$jewishMonth-1];
        else
          return $jewishMonthNamesNonLeap[$jewishMonth-1];
      }

      function get_sunset ($latitude , $longitude , $timezone, $date) {
                  
        $url = sprintf('https://wyrezmanim.herokuapp.com/api/zmanim?timezone='.$timezone.'&latitude='.$latitude.'&longitude='.$longitude.'&mode=basic'.'&date='.$date);
        //$url = 'https://wyrezmanim.herokuapp.com/api/zmanim?timezone='.$timezone.'&latitude='.$latitude.'&longitude='.$longitude.'&mode=basic'.'&date='.$date;
        /*
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $response = curl_exec($curl);
        curl_close($curl);
        */
        $response = wp_remote_get($url);

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        //$headers = $response['headers']; // array of http header lines
        $body    = $response['body']; // use the content
        }

        $response = json_decode($body);
        $sunset = $response->{'Shkia'};//['data'][0]

        $sunset2 = explode(':', $sunset);
        $sunset = $date . ' ' .$sunset2[0].':'.$sunset2[1].':'.$sunset2[2].' '.$sunset2[3];
        //echo $sunset;
        return $sunset;
}

function iserev($b) {

  $sunset = get_sunset($b['latitude'], $b['longitude'], $b['timezone'], $b['date2']);


  $candletime = date_create($sunset,timezone_open($b['timezone']));
  date_sub($candletime,date_interval_create_from_date_string($b['candle']." minutes"));

  $candletime = strtotime(date_format($candletime,"Y-m-d H:i:s"));


  $date3 = strtotime(date_format($b['date'],"Y-m-d H:i:s"));

  if ($date3 > $candletime) {
    $erev = TRUE;

  } else {
    $erev = FALSE;
  }


  return $erev;
}

function ismotzai($b) {
  $sunset = get_sunset($b['latitude'], $b['longitude'], $b['timezone'], $b['date2']);


  $dusktime = date_create($sunset,timezone_open($b['timezone']));
  date_add($dusktime,date_interval_create_from_date_string($b['dusk']." minutes"));

  $dusktime = strtotime(date_format($dusktime,"Y-m-d H:i:s"));    
  $date3 = strtotime(date_format($b['date'],"Y-m-d H:i:s"));
  if ($date3 > $dusktime) {
    $motzai = TRUE;

  } else {
    $motzai = FALSE;
  }


  return $motzai;
}


?>