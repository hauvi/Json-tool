<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta charset="UTF-8"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit JSON</title>
    <!-- <link rel="stylesheet" href="../sinhthai_tayninh/export/tabselect.css"> -->

</head>

<body style="background-color: whitesmoke;">
    <form action="" method="get">
        <h1 style="text-transform: uppercase;">add json</h1>
        <hr><br>
        <label for="path_folder">Load file:</label>
        <input type="text" id="id" name="id">
        <input type="submit" value="Load" name="submit">
        <br><br>
        <label for="pa_title">Parent title:</label>
        <select name="pa_title" id="pa_title">
            <?php
            $data = file_get_contents('phuyen/sidebarConf/sidebar_channuoi.json');
            $datasDecoded = json_decode($data, true);
            $pa_title = [];
            $count = count($datasDecoded['channuoi']['pContent']['pChildContent']);
            echo '<option value="">--- Select parent title ---</option>';
            for ($i = 0; $i < $count; $i++) {
                echo '<option value="' . $datasDecoded['channuoi']['pContent']['pChildContent'][$i]['title'] . '">' . $datasDecoded['channuoi']['pContent']['pChildContent'][$i]['title'] . '</option>';
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
    if (isset($_GET['submit']) == 'Add') {
        if ((isset($_GET['id']) && $_GET['pa_title'] && $_GET['map_url'] && $_GET['title'] || $_GET['note_url']) && !empty($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {
            $id = $_GET['id'];
            $title = $_GET['title'];
            $map_url = $_GET['map_url'];
            $pa_title = $_GET['pa_title'];
            $note_url = $_GET['note_url'];
            // $datas = file_get_contents('data.json');
            $data_sidebar = file_get_contents('phuyen/sidebarConf/sidebar_channuoi.json');
            $data_map = file_get_contents('phuyen/mapConf/mapwms.json');

            //Decode the JSON data into a PHP array.
            // $datasDecoded = json_decode($datas, true);
            $data_sidebar_Decoded = json_decode($data_sidebar, true);
            $data_map_Decoded = json_decode($data_map, true);

            if ($pa_title == 'Dịch bệnh chăn nuôi') {
                $level = 0;
            } elseif ($pa_title == 'Cơ sở sản xuất chăn nuôi') {
                $level = 1;
            } elseif ($pa_title == 'Thống kê chăn nuôi') {
                $level = 2;
            }

            $isEx = false;
            $isSelect = false;
            $pChildContent = [];

            // Create Array to json file for Add data  
            // $datasDecoded[0]['member'][] = ["id" => $id, "title" => $title, "map_url" => $map_url];
            $data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][] = ["title" => $title, "id" => $id, "chudan" => $note_url, "isEx" => $isEx, "isSelect" => $isSelect, "pChildContent" => $pChildContent];
            $data_map_Decoded[$id] = ["iURL4Map" => $map_url];

            //Encode the array back into a JSON string.
            $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
            echo $json_sidebar;
            echo '<hr>';
            $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
            echo $json_map;
            echo '<hr>';
            //Save the file.
            file_put_contents('phuyen/sidebarConf/sidebar_channuoi.json', $json_sidebar);
            file_put_contents('phuyen/mapConf/mapwms.json', $json_map);
        }
    }
    // edit data
    elseif (isset($_GET['submit']) == 'Edit') {
        if ((isset($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) && !empty($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {
            $id = $_GET['id'];
            $title = $_GET['title'];
            $map_url = $_GET['map_url'];
            $pa_title = $_GET['pa_title'];
            $note_url = $_GET['note_url'];
            // read file
            // $data = file_get_contents('data.json');
            $data_sidebar = file_get_contents('phuyen/sidebarConf/sidebar_channuoi.json');
            $data_map = file_get_contents('phuyen/mapConf/mapwms.json');

            if ($pa_title == 'Dịch bệnh chăn nuôi') {
                $level = 0;
            } elseif ($pa_title == 'Cơ sở sản xuất chăn nuôi') {
                $level = 1;
            } elseif ($pa_title == 'Thống kê chăn nuôi') {
                $level = 2;
            }

            $chudan = 'https://nongnghiepphuyen.girs.vn/file_system/a1_mapicon/icon_channuoi_mobile_dichbenh_min.png';
            $isEx = false;
            $isSelect = false;
            $pChildContent = [];
            $i = -1;
            // decode json to array
            $data_sidebar_Decoded = json_decode($data_sidebar, true);
            $data_map_Decoded = json_decode($data_map, true);



            foreach ($data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'] as $key => $value) {
                $i++;
                if (strcmp($value['id'], $id) == 0) {
                    $data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][$i]['title'] = $title;
                    $data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][$i]['id'] = $id;
                    $data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][$i]['chudan'] = $note_url;
                }
            }


            // foreach ($data_map_Decoded as $key => $value) {
            //     $i++;

            //     if ($value == $id) {
            //         $data_map_Decoded[$i]['iURL4Map'] = $map_url;
            //         // $data_map_Decoded[$key] = $id;
            //     }

            // }
            // var_dump($tmp_map_decode);

            $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
            echo $json_sidebar;
            echo '<hr>';
            // $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
            // echo $json_map;
            // echo '<hr>';
            // encode array to json and save to file
            file_put_contents('phuyen/sidebarConf/sidebar_channuoi.json', $json_sidebar);
            // file_put_contents('phuyen/mapConf/mapwms.json', $json_map);
        }
    }
    // delete data
    elseif (isset($_GET['submit']) == 'Delete') {
        if ((isset($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) && !empty($_GET['id']) || $_GET['title'] || $_GET['map_url'] && $_GET['pa_title'] || $_GET['note_url']) {
            $id = $_GET['id'];
            $title = $_GET['title'];
            $map_url = $_GET['map_url'];
            $pa_title = $_GET['pa_title'];
            $chudan = 'https://nongnghiepphuyen.girs.vn/file_system/a1_mapicon/icon_channuoi_mobile_dichbenh_min.png';
            $isEx = false;
            $isSelect = false;
            $pChildContent = [];

            if ($pa_title == 'Dịch bệnh chăn nuôi') {
                $level = 0;
            } elseif ($pa_title == 'Cơ sở sản xuất chăn nuôi') {
                $level = 1;
            } elseif ($pa_title == 'Thống kê chăn nuôi') {
                $level = 2;
            }

            // read file
            $data_sidebar = file_get_contents('phuyen/sidebarConf/sidebar_channuoi.json');
            $data_map = file_get_contents('phuyen/mapConf/mapwms.json');
            // decode json to array
            $data_sidebar_Decoded = json_decode($data_sidebar, true);
            $data_map_Decoded = json_decode($data_map, true);
            // get array index to delete
            $arr_index_sidebar = [];
            $arr_index_map = array();
            foreach ($data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'] as $key => $value) {
                if ($value['id'] == $id) {
                    $arr_index_sidebar[] = $key;
                }
            }
            // foreach ($data_map_Decoded as $key => $value) {
            //     if ($value['id'] == $id) {
            //         $arr_index_map[] = $key;
            //     }
            // }


            // delete data
            foreach ($arr_index_sidebar as $i) {
                unset($data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][$i]);
            }
            // foreach ($arr_index_map as $i) {
            //     unset($data_map_Decoded[$i]);
            // }



            $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
            // $data_map_Decoded = array_values($data_map_Decoded);
            // $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
            // encode array to json and save to file
            file_put_contents('phuyen/sidebarConf/sidebar_channuoi.json', $json_sidebar);
            // file_put_contents('phuyen/mapConf/mapwms.json', $json_map);
            echo $json_sidebar;
            echo '<hr>';
            // echo $json_map;
            // echo '<hr>';
        }
    }


    ?>
    <form accept-charset="utf-8">
        <?php
        // header('Content-Type: text/html; charset=UTF-8');
        $data = file_get_contents('phuyen/sidebarConf/sidebar_channuoi.json');
        $datasDecoded = json_decode($data, true);
        // echo $datasDecoded[1]["member"][3]["name"];
        // echo json_encode($datasDecoded);
        // var_dump($datasDecoded)
        // foreach ($datasDecoded['channuoi']['pContent']['pChildContent'][1]['pChildContent'] as $key => $value) {
        //     // if ($value['id'] == 'channuoi_coso_nhayen') {
        //         $data_sidebar_Decoded[$key][0]['channuoi']['pContent']['pChildContent']['pChildContent']['title'] = "hauvi";
        //         echo json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);;
        //     // }

        // }
        ?>

    </form>

</body>

</html>