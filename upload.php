<?php 

require_once 'vendor/autoload.php';
  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['submit'])) {
  
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      
    if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
      
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
      
        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
  
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
  
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        
        $result = [];
        foreach ($sheetData as $num => $row){
            if($num == 0){
                $key = $row;                
            }
            else{
                $rowNew = array_combine($key, $row);
                array_push($result, $rowNew);
            }
            if($num == 3) break;
        }

        echo "<pre>";
        print_r($result);
        print(convertArrayTo($result, 'short_syntax'));
        echo "</pre>";

    } else {
        echo "Upload only CSV or Excel file.";
    }
}

function convertArrayTo($array, $option)
{
    $result = null;
    switch ($option) {
        case 'short_syntax':
            $result .= '[<br>';
            $lengthArray = count($array);
            foreach($array as $key => $values) {
                $result .= '&emsp;&emsp;&emsp;&emsp;[<br>';
                $lenghtValues = count($values);
                $index = 0;
                foreach($values as $i => $value) {
                   $index < ($lenghtValues - 1) ? $result .= '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;"'.$i.'" => "' . $value . '",<br>' : $result .= '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;"'.$i.'" => "' . $value . '"<br>' ;  
                   $index ++;
                }
                $key < ($lengthArray - 1) ? $result .= '&emsp;&emsp;&emsp;&emsp;],<br>' : $result .= '&emsp;&emsp;&emsp;&emsp;]<br>'; 
            }
            $result .= ']';
            break;
        case 'json':
            $result = json_encode($array);
            break;
    }

    return $result;
}
?>
