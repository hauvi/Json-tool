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
        <label for="pa_title">Parent title:</label>

        <select name="pa_title" id="pa_title">
            <option value="">--- Select parent title ---</option>
            <option value="Dịch bệnh chăn nuôi">Dịch bệnh chăn nuôi</option>
            <option value="Cơ sở sản xuất chăn nuôi">Cơ sở sản xuất chăn nuôi</option>
            <option value="Thống kê chăn nuôi">Thống kê chăn nuôi</option>
        </select><br><br>

        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br><br>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title"><br><br>
        <label for="map_url">Map url:</label>
        <input type="text" id="map_url" name="map_url"><br><br>
        <input type="submit" value="Add" name="add">
        <input type="submit" value="Edit" name="edit"><br><br>
        <hr><br>
    </form>
    <?php
    if (isset($_GET['add'])) {
        if ((isset($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title']) && !empty($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title']) {
            $id = $_GET['id'];
            $title = $_GET['title'];
            $map_url = $_GET['map_url'];
            $pa_title = $_GET['pa_title'];
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

            $chudan = 'https://nongnghiepphuyen.girs.vn/file_system/a1_mapicon/icon_channuoi_mobile_dichbenh_min.png';
            $isEx = false;
            $isSelect = false;
            $pChildContent = [];

            // Create Array to json file for Add data  
            // $datasDecoded[0]['member'][] = ["id" => $id, "title" => $title, "map_url" => $map_url];
            $data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'][] = ["title" => $title, "id" => $id, "chudan" => $chudan, "isEx" => $isEx, "isSelect" => $isSelect, "pChildContent" => $pChildContent];
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
    } elseif (isset($_GET['edit'])) {
        if ((isset($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title']) && !empty($_GET['id']) && $_GET['title'] && $_GET['map_url'] && $_GET['pa_title']) {
            $id = $_GET['id'];
            $title = $_GET['title'];
            $map_url = $_GET['map_url'];
            $pa_title = $_GET['pa_title'];

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

            // decode json to array
            $data_sidebar_Decoded = json_decode($data_sidebar, true);
            $data_map_Decoded = json_decode($data_map, true);
            
            foreach ($data_sidebar_Decoded['channuoi']['pContent']['pChildContent'][$level]['pChildContent'] as $key => $value) {
                if ($value['id'] == $id) {
                    $data_sidebar_Decoded[$key]['channuoi']['pContent']['pChildContent'][$level]['pChildContent']['title'] = $title;
                    echo 'hauvi';
                //     // $data_sidebar_Decoded[$key]['id'] = $id;
                //     $data_sidebar_Decoded[$key][0]['channuoi']['pContent']['pChildContent'][$level]['pChildContent']['chudan'] = $chudan;
                //     $data_sidebar_Decoded[$key][0]['channuoi']['pContent']['pChildContent'][$level]['pChildContent']['isEx'] = $isEx;
                //     $data_sidebar_Decoded[$key][0]['channuoi']['pContent']['pChildContent'][$level]['pChildContent']['isSelect'] = $isSelect;
                //     $data_sidebar_Decoded[$key][0]['channuoi']['pContent']['pChildContent'][$level]['pChildContent']['pChildContent'] = $pChildContent;
                }

            }

            // foreach ($data_map_Decoded as $key => $value) {
            //     if ($value[$title] == $title) {
            //         $data_map_Decoded[$key][0]['iURL4Map'] = $map_url;
            //     }
            // }

            $json_sidebar = json_encode($data_sidebar_Decoded, JSON_UNESCAPED_UNICODE);
            echo $json_sidebar;
            echo '<hr>';
            $json_map = json_encode($data_map_Decoded, JSON_UNESCAPED_UNICODE);
            echo $json_map;
            echo '<hr>';
            // encode array to json and save to file
            file_put_contents('phuyen/sidebarConf/sidebar_channuoi.json', $json_sidebar);
            file_put_contents('phuyen/mapConf/mapwms.json', $json_map);
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
        // var_dump($data)
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