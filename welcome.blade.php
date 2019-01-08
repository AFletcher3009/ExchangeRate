<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Font -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Page Styling -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <!-- @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif -->

            <div class="content">
                <div class="title m-b-md">
                    Exchange Rate On Your Birthday
                </div>
                <form method="GET" enctype="multipart/form-data">
                <div class="links">
<!-- Prints 1 to 31 within a dropdown -->
                    <select name="dd" size='1'>
                        <?php
                        for ($i = 1; $i < 32; $i++) {
                            if ($i < 10){
                                echo "<option name='day' value='0$i'>0$i</option>";
                            } else {
                                echo "<option name='day' value='$i'>$i</option>";
                            }
                        }
                    ?>    
                    </select>
 <!-- Prints January to December within a dropdown -->
                    <select name="mm" size='1'>
                        <?php
                        for ($i = 0; $i < 12; $i++) {
                            $time = strtotime(sprintf('%d months', $i));   
                            $label = date('F', $time);   
                            $value = date('n', $time);
                            if ($value < 10){
                            echo "<option name='month' value='0$value'>$label</option>";
                            } else {
                                echo "<option name='month' value='$value'>$label</option>";
                            }
                        }
                    ?>    
                    </select>
 <!-- Options for Currency -->
                    <select name="crnc">
                        <option name="crnc" value="CAD">CAD</option>
                        <option name="crnc" value="EUR">EUR</option>
                        <option name="crnc" value="GBP">GBP</option>
                        <option name="crnc" value="USD">USD</option>
                    </select>
                    <input name="submit" type="submit" value="submit">
  <!-- PHP that gets information entered and put it into a URL for Fixer.io to display relevent information -->                  
                    <?php
                            if ( isset( $_GET['submit'] ) ) {
                            $month = $_GET['mm'];
                            $day = $_GET['dd'];
                            $key = "?access_key=651234578b00ac0a922bfd255bd2fa29&symbols=";
                            $crnc = $_GET['crnc'];
                            $json_string = "http://data.fixer.io/api/2018-$month-$day$key$crnc";
                            $array = file_get_contents($json_string);
                            $obj = json_decode($array,true);
                            echo "<pre>";
                            //print $array;
                            //$data = json_encode($obj);
                            //print $jsondata;
                            function build_table($array){
                                function my_remove_array_item( $array, $item ) {
                                    $index = array_search($item, $array);
                                    if ( $index !== false ) {
                                        unset( $array[$index] );
                                    }
                                
                                    return $array;
                                }
                               
                                // start table
                                $html = '<table>';
                                // header row
                                $html .= '<tr>';
                                foreach($array[0] as $key=>$value){
                                        //$items = array( $value);
                                        //$replace = array('[', ']', '=', '>');
                                        //$remove = my_remove_array_item( $items, 'success','timestamp','historical','base','date','rates' );
                                        $string = str_replace(str_split('\/:*=?"<>|'), ' ', $value); // Replaces all spaces with hyphens.
                                        $html .= '<th>' . print_r($string) . '</th>';
                                    }
                                $html .= '</tr>';
                                // data rows
                                foreach( $array as $key=>$value){
                                    $html .= '<tr>';
                                    foreach($value as $key2=>$value2){
                                        $exlcuded_words = array( 'success','timestamp','historical','base','date','rates', '[', ']', '=');
                                        $replace = array_fill_keys($exlcuded_words,'');
                                        $html .= '<td>' . print_r(str_replace(array_keys($replace),$replace, $value2)) . '</td>';
                                    }
                                    $html .= '</tr>';
                                }
                                // finish table and return it
                                $html .= '</table>';
                                return $html;
                            }
                            $array = array($obj);
                            echo build_table($array);
                        }
                            ?>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
