<?php
function searchSubArray(Array $array, $key1, $value1, $key2, $value2) {   
    foreach ($array as $subarray){  
        if (isset($subarray[$key1]) && $subarray[$key1] == $value1 && isset($subarray[$key2]) && $subarray[$key2] == $value2)
          return $subarray;       
    } 
}
?>