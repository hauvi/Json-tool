<?php
if (isset($_GET['path_side'])) {
    $path_side = $_GET['path_side'];
} else {
    $path_side = '';
}
if (isset($_GET['path_map'])) {
    $path_map = $_GET['path_map'];
} else {
    $path_map = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit JSON</title>
    <style>
        a {
            text-decoration: none;
            color: black;
        }

        a:hover {
            color: gray;
        }
    </style>
</head>

<body style="background-color: whitesmoke;">
    <form action="" method="get">
        <h1 style="text-transform: uppercase;"><a href="./">add json</a></h1>
        <p>Sample:</p>
        <p>Path sidebar: ___/sidebarConf/sidebar_****.json</p>
        <p>Path map: ___/mapConf/mapwms.json</p>
        <hr><br>
        <label for="path_side">Load file sidebar:</label>
        <input type="text" id="path_side" name="path_side" value="<?php echo $path_side; ?>">
        <label for="path_map">Load file map:</label>
        <input type="text" id="path_map" name="path_map" value="<?php echo $path_map; ?>">
        <input type="submit" value="Load" name="submit">
        <?php
        if (isset($_GET['submit'])) {
            $submit = $_GET['submit'];


            // load file
            if ($submit == 'Load') {
                $path_side = $_GET['path_side'];
                $path_map = $_GET['path_map'];
            }
        ?>
            <br><br>
            <label for="pa_title">Parent title:</label>
            <select name="pa_title" id="pa_title">
                <?php
                $data = file_get_contents($path_side);
                $datasDecoded = json_decode($data, true);
                // $schema = rtrim(ltrim(strpbrk($path_side, "_"), '_'), '.json');
                foreach ($datasDecoded as $key => $value) {
                    $schema = $key;
                }
                $pa_title = [];
                $count = count($datasDecoded[$schema]['pContent']['pChildContent']);
                echo '<option value="">--- Select parent title ---</option>';
                for ($i = 0; $i < $count; $i++) {
                    echo '<option value="' . $datasDecoded[$schema]['pContent']['pChildContent'][$i]['title'] . '">' . $datasDecoded[$schema]['pContent']['pChildContent'][$i]['title'] . '</option>';
                };
                ?>
            </select><br><br>
            <label for="id">ID:</label>
            <input type="text" id="id" name="id"><br><br>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title"><br><br>
            <label for="map_url">Map url:</label>
            <input type="text" id="map_url" name="map_url"><br><br>
            <label for="note_url">Note url:</label>
            <input type="text" id="note_url" name="note_url"><br><br>

            <input type="submit" value="Add" name="submit">
            <input type="submit" value="Edit" name="submit">
            <input type="submit" value="Delete" name="submit"><br><br>
            <hr><br>
    </form>
    <?php

            //add data

            if ($submit == 'Add') {

                if ((isset($_GET['id']) && $_GET['pa_title'] && $_GET['map_url'] && $_GET['title'] || $_GET['note_url']) && !empty($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {
                    $id = $_GET['id'];
                    $title = $_GET['title'];
                    $map_url = $_GET['map_url'];
                    $pa_title = $_GET['pa_title'];
                    $note_url = $_GET['note_url'];
                    // $datas = file_get_contents('data.json');
                    $data_sidebar = file_get_contents($path_side);
                    $data_map = file_get_contents($path_map);

                    //Decode the JSON data into a PHP array.
                    // $datasDecoded = json_decode($datas, true);
                    $data_sidebar_Decoded = json_decode($data_sidebar, true);
                    $data_map_Decoded = json_decode($data_map, true);

                   

                    $isEx = false;
                    $isSelect = false;
                    $pChildContent = [];
                    // Create Array to json file for Add data  
                    for ($i = 0; $i < $count; $i++) {
                        // echo json_encode($datasDecoded[$schema]['pContent']['pChildContent'][$i]['title'], JSON_UNESCAPED_UNICODE);
                        if ($pa_title == $datasDecoded[$schema]['pContent']['pChildContent'][$i]['title']) {
                            // echo 11111;
                            $data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][] = ["title" => $title, "id" => $id, "chudan" => $note_url, "isEx" => $isEx, "isSelect" => $isSelect, "pChildContent" => $pChildContent];
                            $data_map_Decoded[$id] = ["iURL4Map" => $map_url];
                        }
                        echo 'Sidebar config:'.'<br>';
                        echo  json_encode($data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'], JSON_UNESCAPED_UNICODE);
                        echo '<hr><br>';
                        echo 'Map config:'.'<br>';
                        echo json_encode($data_map_Decoded[$id], JSON_UNESCAPED_UNICODE);
                        echo '<hr>';
                    }

                   

                    //Encode the array back into a JSON string.
                    $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
                    $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
                    
                    //Save the file.
                    file_put_contents($path_side, $json_sidebar);
                    file_put_contents($path_map, $json_map);
                }
            }
            // edit data
            elseif ($submit == 'Edit') {
                if ((isset($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) && !empty($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {

                    $id = $_GET['id'];
                    $title = $_GET['title'];
                    $map_url = $_GET['map_url'];
                    $pa_title = $_GET['pa_title'];
                    $note_url = $_GET['note_url'];
                    // read file
                    $data_sidebar = file_get_contents($path_side);
                    $data_map = file_get_contents($path_map);

                    $isEx = false;
                    $isSelect = false;
                    $pChildContent = [];
                    $j = -1;
                    // decode json to array
                    $data_sidebar_Decoded = json_decode($data_sidebar, true);
                    $data_map_Decoded = json_decode($data_map, true);

                  // edit data
                    for ($i = 0; $i < $count; $i++) {
                        if ($pa_title == $datasDecoded[$schema]['pContent']['pChildContent'][$i]['title']) {
                            foreach ($data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'] as $key => $value) {
                                $j++;
                                if (strcmp($value['id'], $id) == 0) {
                                    $data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][$j]['title'] = $title;
                                    $data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][$j]['id'] = $id;
                                    $data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][$j]['chudan'] = $note_url;
                                }
                            }
                        }
                        echo 'Sidebar config:'.'<br>';
                        echo json_encode($data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][$j], JSON_UNESCAPED_UNICODE);
                        echo '<hr><br>';
                    }


                    $k = -1;

                    foreach ($data_map_Decoded as $key => $value) {
                        $k++;

                        if ($key == $id) {
                            $data_map_Decoded[$key]['iURL4Map'] = $map_url;
                            // $data_map_Decoded[$key] = $id;
                            echo 'Map config:'.'<br>';
                            echo json_encode( $data_map_Decoded[$key], JSON_UNESCAPED_UNICODE);
                            echo '<hr>';
                        }
                        
                    }
                    // var_dump($tmp_map_decode);

                    $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
                    // echo $json_sidebar;
                    // echo '<hr>';
                    $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
                    // echo $json_map;
                    // echo '<hr>';
                    // encode array to json and save to file
                    file_put_contents($path_side, $json_sidebar);
                    file_put_contents($path_map, $json_map);
                }
            }
            // delete data

            elseif ($submit == 'Delete') {
                if ((isset($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) && !empty($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {
                    $id = $_GET['id'];
                    $title = $_GET['title'];
                    $map_url = $_GET['map_url'];
                    $pa_title = $_GET['pa_title'];
                    $note_url = $_GET['note_url'];
                    $isEx = false;
                    $isSelect = false;
                    $pChildContent = [];


                    // read file
                    $data_sidebar = file_get_contents($path_side);
                    $data_map = file_get_contents($path_map);
                    // decode json to array
                    $data_sidebar_Decoded = json_decode($data_sidebar, true);
                    $data_map_Decoded = json_decode($data_map, true);
                    // get array index to delete
                    $arr_index_sidebar = [];
                    $arr_index_map = array();
                    for ($i = 0; $i < $count; $i++) {
                        if ($pa_title == $datasDecoded[$schema]['pContent']['pChildContent'][$i]['title']) {
                            foreach ($data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'] as $key => $value) {
                                if ($value['id'] == $id) {
                                    $arr_index_sidebar[] = $key;
                                    
                                }
                            }
                            foreach ($data_map_Decoded as $key => $value) {
                                if ($key == $id) {
                                    $arr_index_map[] = $key;
                                }
                            // delete data

                            }

                            foreach ($arr_index_sidebar as $j) {
                                unset($data_sidebar_Decoded[$schema]['pContent']['pChildContent'][$i]['pChildContent'][$j]);
                             
                            }
                            foreach ($arr_index_map as $j) {
                                unset($data_map_Decoded[$j]);
                            }
                        }
                    }




                    $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);

                    $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
                    // // encode array to json and save to file
                    file_put_contents($path_side, $json_sidebar);
                    file_put_contents($path_map, $json_map);
                    echo 'Sidebar config:'.'<br>';
                    echo $json_sidebar;
                    echo '<hr><br>';
                    echo 'Map config:'.'<br>';
                    echo $json_map;
                    echo '<hr>';
                }
            }


    ?>
    <form accept-charset="utf-8">
    <?php

            $path = 'phuyen/sidebarConf/sidebar_channuoi.json';
            $data = file_get_contents($path);
            $datasDecoded = json_decode($data, true);

           

            //ket thuc kiem tra co nhan nut submit khong
        }
    ?>

    </form>

</body>

</html>