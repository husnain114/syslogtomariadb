<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "shipped!!", "syslog");
$columns = array('seq', 'host', 'level', 'datetime', 'msg');

$query = "SELECT * FROM logs WHERE ";

//if($_POST["is_date_search"] == "no")
//{
// $query .= 'datetime BETWEEN "2017-11-21 00:00:00" AND "2017-11-21 23:59:59" AND ';
//}


if($_POST["is_date_search"] == "yes")
{
 $query .= 'datetime BETWEEN "'.$_POST["start_date"].' 00:00:00" AND "'.$_POST["end_date"].' 23:59:59" AND ';
// $query .= 'datetime BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}


if(isset($_POST["search"]["value"]))
{
 $query .= '
  (seq LIKE "%'.$_POST["search"]["value"].'%"
  OR host LIKE "%'.$_POST["search"]["value"].'%"
  OR level LIKE "%'.$_POST["search"]["value"].'%"
  OR msg LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["query"]["value"]))
{
 $query .= '
  (seq LIKE "%'.$_POST["search"]["value"].'%"
  OR host LIKE "%'.$_POST["search"]["value"].'%"
  OR level LIKE "%'.$_POST["search"]["value"].'%"
  OR msg LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"])) {
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].'
 ';
}
else
{
 $query .= 'ORDER BY seq DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["seq"];
 $sub_array[] = $row["host"];
 $sub_array[] = $row["level"];
 $sub_array[] = $row["datetime"];
 $sub_array[] = $row["msg"];
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM logs";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>

