<?php
$data = [10,2,8,3,7,4,6,5,1];

/**
*冒泡排序 比较相邻两个元素
*
*/
function m_sort($data){
	$len = count($data);
	for ($i=0; $i <$len ; $i++) { 
		for ($k=0; $k <$len-1-$i ; $k++) { 
			if($data[$k] > $data[$k-1]){
				$tmp = $data[$k];
				$data[$k] = $data[$k-1];
				$data[$k-1] = $tmp;
			}
		}
	}
	return $data;
}
/*
 *选择排序
 */

function select_sort($data){
	for ($i=0; $i < $len; $i++) { 
		$p = $i;
		for ($j=$i+1; $j <$len ; $j++) { 
			if($data[$p] > $data[$j]){
				$p = $j; //最小值
			}
		}
		if($p != $i){
			$tmp = $data[$p];//得到的最小值
			$data[$p] = $data[$i];
			$data[$i] = $tmp; 
		}
	}
	return $data;
}

/*
 * 插入排序 1 9 2 8 3 7 4 6 5
            1 2 9 8 3 7 4 6 5

*/
function insert_sort($data){
	for ($i=1; $i<$len ; $i+1) { 
		for ($j=$i; $j >0 ; $j--) { 
			if($data[$j] > $data[$j-1]){
					$tmp = $data[$j]；
					$data[$j] = $data[$j-1]；
					$data[$j-1] = $tmp;
			}	
		}
	}
	require $data;
}




?>