<?php
/* // read file
$data = file_get_contents('data.json');

// decode json to array
$json_arr = json_decode($data, true);

foreach ($json_arr[0]['member'] as $key => $value) {
    if ($value['id'] == '2') {
        $json_arr[$key]['member'][1]['name'] = "Hardik Savani";
    }
}

// encode array to json and save to file
file_put_contents('data.json', json_encode($json_arr));
$json = json_encode($json_arr);
echo $json; */
?>

<?php
// read json file
$data = file_get_contents('data.json');

// decode json to associative array
$json_arr = json_decode($data, true);

// get array index to delete
$arr_index = array();
foreach ($json_arr[0]['member'] as $key => $value)
{
    if ($value['id'] == '1')
    {
        $arr_index[] = $key;
    }
}

// delete data
foreach ($arr_index as $i)
{
    unset($json_arr[0]['member'][$i]);
}

// rebase array
$json_arr = array_values($json_arr);

// encode array to json and save to file
file_put_contents('data.json', json_encode($json_arr));

echo json_encode($json_arr);
?>