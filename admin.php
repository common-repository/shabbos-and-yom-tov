<?php

register_activation_hook( SHABBOS_PATH .'/Shabbos-and-YomTov.php', 'Shabbos_and_YomTov_active' );

register_uninstall_hook( SHABBOS_PATH .'/Shabbos-and-YomTov.php', 'Shabbos_and_YomTov_delete' );

function Shabbos_and_YomTov_active() {
    
    $shabbos_options =  array(
        'timezone'       => 'America/New_York',
        'passover'       => 'Passover',
        'shavuos'        => 'Shavuos',
        'sukkos'         => 'Sukkos',
        'rosh_hashanah'  => 'Rosh Hashanah',
        'yom_kippur'     => 'Yom Kippur',
        'yom_tov_msg'    => 'We\'re sorry but we are currently closed due to %yomtov%.',
        'shabbos_msg'    => 'We\'re sorry but we are currently closed due to Shabbos.',
        'chol_hamoed'    => 'closed',
        'latitude'       => '40.63',
        'longitude'      => '-73.98',
        'candle'         => '15',
        'dusk'           => '72',
        'class'          => NULL,
        'one-day'        => 'no'
    );

    add_option ('shabbos_options', $shabbos_options);
    
}

function Shabbos_and_YomTov_delete() {

    delete_option ('shabbos_options');

}


    //Shabbat by Studio GLD from the Noun Project
    //candles by Eliricon from the Noun Project
    //'dashicons-media-default'
add_action( 'admin_menu', 'shabbos_admin_menu' );

function shabbos_err_msg ($msg) {
    echo '<div id = "message" class = "error notice is-dismissible"><p>'.$msg.'</p><button type = "button" class = "notice-dismiss"><span class = "screen-reader-text">Dismiss this notice.</span></button></div>';
    return 1;
    
}


function shabbos_admin_menu () {
    
    //add_menu_page('Shabbos And Yom Tov Options', 'Shabbos And Yom Tov','manage_options','shabbos_and_yom_tov','shabbos_admin_menu_page', SHABBOS_PATH .'/assets/noun_candles_93831.png',35 );
    add_submenu_page( 'options-general.php', 'Shabbos And Yom Tov Options', 'Shabbos And Yom Tov', 'manage_options', 'shabbos_and_yom_tov', 'shabbos_admin_menu_page' );
}

function shabbos_admin_menu_page () {

    
    $shabbos_options = get_option('shabbos_options');
   // var_dump ($shabbos_options);
    
   //$fields = array('latitude','longitude','timezone','candle','dusk','chol_hamoed','one-day','shabbos_msg','yom_tov_msg','passover','shavuos','sukkos','rosh_hashanah','yom_kippur','class');
    
    
    if (isset($_POST['submit'])){

        if(preg_match ( '/^\-?\d+(\.?\d*)?$/'  , $_POST['latitude'] )){
            $shabbos_options['latitude'] = sanitize_text_field($_POST['latitude']);
        } else { 
            $error = shabbos_err_msg('Latitude must be a valid numeric value.');
        }
        
        if(preg_match ( '/^\-?\d+(\.?\d*)?$/'  , $_POST['longitude'] )){
            $shabbos_options['longitude'] = sanitize_text_field($_POST['longitude']); 
        } else { 
            $error = shabbos_err_msg('Longitude must be a valid numeric value.');
        }
     
        if(!empty ($_POST['timezone'] )){
            $shabbos_options['timezone'] = sanitize_text_field($_POST['timezone']);
        } else { 
            $error = shabbos_err_msg('Please enter a valid Timezone.');
        }
 
        if(preg_match ( '/^\d+(\.?\d*)?$/'  , $_POST['candle'] )){
            $shabbos_options['candle'] = sanitize_text_field($_POST['candle']);
        } else { 
            $error = shabbos_err_msg('Candle lighting time must be a valid numeric value in minutes before sunset.');
        }

        if(preg_match ( '/^\d+(\.?\d*)?$/'  , $_POST['dusk'] )){
            $shabbos_options['dusk'] = sanitize_text_field($_POST['dusk']);
        } else { 
            $error = shabbos_err_msg('Dusk time must be a valid numeric value in minutes after sunset.');
        }

        if(preg_match ( '/open|closed/'  , $_POST['chol_hamoed'] )){
            $shabbos_options['chol_hamoed'] = sanitize_text_field($_POST['chol_hamoed']);
        } else { 
            $error = shabbos_err_msg('Chol Hamoed must be se to "Open" or "Closed".');
        }
        
        if(preg_match ( '/yes|no/'  , $_POST['one-day'] )){
            $shabbos_options['one-day'] = sanitize_text_field($_POST['one-day']);
        } else { 
            $error = shabbos_err_msg('One-Day must be se to "Yes" or "No".');
        }
        $may_be_empty = array ('shabbos_msg','yom_tov_msg','passover','shavuos','sukkos','rosh_hashanah','yom_kippur');
        foreach ($may_be_empty as $field) {
            $shabbos_options[$field] = sanitize_text_field($_POST[$field]);
        }
        if (empty( $_POST['class'])) {
            $shabbos_options['class'] = NULL;
        }else{
            $shabbos_options['class'] = sanitize_html_class($_POST['class'], NULL);
        }

        if ($error == 1) {
            echo '<div id = "message" class = "error notice is-dismissible"><p>Settings cannot be saved saved.</p><button type = "button" class = "notice-dismiss"><span class = "screen-reader-text">Dismiss this notice.</span></button></div>';
        } else {
    
        update_option ('shabbos_options', $shabbos_options);
    
        echo '<div id = "message" class = "updated notice is-dismissible"><p>Settings saved.</p><button type = "button" class = "notice-dismiss"><span class = "screen-reader-text">Dismiss this notice.</span></button></div>';
        }

    }


    
    echo "<h3>Shabbos and Yom Tov defult options</h3>";
    echo "<p><strong>You can override these setting by setting the attributes in the shortcode.</strong></p>";
    echo '<p>For more information see <a href = "' . plugins_url() . '/shabbos-and-yomtov/readme.txt" target = "blank">README FILE</a>.</p>';



    echo '
    <style>
    label {
        font-size:14px;
        line-height: 24px;
        font-weight: 600; 
        margin-bottom: 4px;
        margin-top: 6px;
        
    }
    .description{
        font-style:italic;
    }
    </style>';

    echo '
    <form style = "margin-top:20px;" method = "post"';
    
    echo'
    <p>
    <label>Latitude</label></br>
    <span class="description">The latitude of your location.</span><br>
    <input type="number" max="90" min="-90" step=".00000001" size = "75" name = "latitude" value = "'.stripslashes($shabbos_options['latitude']).'">
    </p>
    
    <p>
    <label>Longitude</label></br>
    <span class="description">The longitude of your location.</span><br>
    <input type="number" max="180" min="-180" step=".00000001" size = "75" name = "longitude" value = "'.stripslashes($shabbos_options['longitude']).'">
    </p>
    
    <p>
    <label>Timezone</label></br>
    <span class="description">The timezone of your location.<br>Must be a timezone from <a target="blank" href="http://php.net/manual/en/timezones.php">http://php.net/manual/en/timezones.php</a>.</span><br>
    <input size = "75" name = "timezone" value = "'.stripslashes($shabbos_options['timezone']).'">
    </p>
    
    <p>
    <label>Candle lighting time</label></br>
    <span class="description">Candle lighting time, in minutes before sunset.</span><br>
    <input type="number" min="15" step=".5" size = "75" name = "candle" value = "'.stripslashes($shabbos_options['candle']).'">
    </p>
    
    <p>
    <label>Dusk time</label></br>
    <span class="description">Time when Shabbos ends, in minutes after sunset.</span><br>
    <input type="number" min="72" step=".5" size = "75" name = "dusk" value = "'.stripslashes($shabbos_options['dusk']).'">
    </p>
    
    <p>
    <label>Are you open on Chol Hamoed?</label></br>
    <span class="description"></span><br>
    
    <select name="chol_hamoed">
    <option value="open"'; 
    if($shabbos_options['chol_hamoed']== 'open') {echo 'selected';} 
    echo'>Open</option>
    <option value="closed"'; 
    if($shabbos_options['chol_hamoed']== 'closed') {echo 'selected';} 
    echo'>Closed</option>
    </select>
    </p>
    
    <p>
    <label>Do you observe one day Yom Tov?</label></br>
    <span class="description">If you are in Israel set to "yes".</span><br>
    
    <select name="one-day">
    <option value="no"'; 
    if($shabbos_options['one-day']== 'no') {echo 'selected';} 
    echo'>No</option>
    <option value="yes"'; 
    if($shabbos_options['one-day']== 'yes') {echo 'selected';} 
    echo'>Yes</option>
    </select>
    </p>
    
    <p>
    <label>Shabbos message</label></br>
    <span class="description">What message should be displayed on Shabbos.</span><br>
    <textarea name = "shabbos_msg"  rows="2" cols="77" >'.stripslashes($shabbos_options['shabbos_msg']).'</textarea>
    </p>
    
    <p>
    <label>Yom-Tov message</label></br>
    <span class="description">What message should be displayed on Yom Tov.<br>%yomtov% will be replaced with the current Yom Tov.</span><br>
    <textarea name = "yom_tov_msg" rows="2" cols="77" >'.stripslashes($shabbos_options['yom_tov_msg']).'</textarea>
    </p>
    
    <p>
    <label>How should your site display Passover.</label></br>
    <span class="description"></span><br>
    <input size = "75" name = "passover" value = "'.stripslashes($shabbos_options['passover']).'">
    </p>
    
    <p>
    <label>How should your site display Shavuos.</label></br>
    <span class="description"></span><br>
    <input size = "75" name = "shavuos" value = "'.stripslashes($shabbos_options['shavuos']).'">
    </p>
    
    <p>
    <label>How should your site display Rosh Hashanah.</label></br>
    <span class="description"></span><br>
    <input size = "75" name = "rosh_hashanah" value = "'.stripslashes($shabbos_options['rosh_hashanah']).'">
    </p> 
    
    <p>
    <label>How should your site display Sukkos.</label></br>
    <span class="description"></span><br>
    <input size = "75" name = "sukkos" value = "'.stripslashes($shabbos_options['sukkos']).'">
    </p>
    
    <p>
    <label>How should your site display Yom Kippur.</label></br>
    <span class="description"></span><br>
    <input size = "75" name = "yom_kippur" value = "'.stripslashes($shabbos_options['yom_kippur']).'">
    </p>
    
    <p>
    <label>Class.</label></br>
    <span class="description">You can set a custom class for the message.</span><br>
    <input size = "75" name = "class" value = "'.stripslashes($shabbos_options['class']).'">
    </p>
    
    ';
    echo '
    <input name = "submit" type = "submit" value = "Save">
    </form>
    ';
}



?>