<?php

namespace AppBundle\Model;

class Combination
{
	private $fieldsCount;
	private $chipCount;
	private $count = null;
	private $combination = [];

	
	
    public function __construct($chipCount,$fieldsCount)
    {
		
		$this->fieldsCount = $fieldsCount;
		$this->chipCount = $chipCount;
		
		// первая комбинация
		$arr1 = array_fill(1,$chipCount,1);
		$arr2 = array_fill(1,$fieldsCount,0);
		$this->combination = array_replace( $arr2 , $arr1 );
		// перевернём, чтоб воспользоваться array_search
		$this->combination = array_reverse($this->combination);
    }	
	
	private function factorial($n)
	{
		if ($n == 0) {
			return 1;
		} else {
			return $n * $this->factorial($n - 1);
		}
	}	   
	
	/**
	 * функция дающая следующую комбинацию
	 * скорее всего это не самое оптимальное решение, но рабочее
	 */
	private function step(&$arr)
	{
		if(array_sum($arr) == 0 ) return;
		
		$key = array_search(1,$arr);
		if($key == 0)
		{
			array_shift($arr);
			$this->step($arr);
			array_unshift($arr,0);
			$key2 = array_search(1,$arr);
			$arr[$key2-1] = 1;
		}
		else
		{
			// меняем его с предыдущим 
			list($arr[$key - 1],$arr[$key]) = array($arr[$key],	$arr[$key - 1]);		
		}
		
	}
	
	public function getCount()
	{
		if($this->count === null)
		$this->count = $this->factorial($this->fieldsCount) / ($this->factorial($this->chipCount) * $this->factorial($this->fieldsCount - $this->chipCount));
		
        return $this->count;
	}
	
	public function getResult()
	{
		$result = "";
		for($i=1; $i<=$this->count; $i++ )
		{
			$result .= implode(',',$this->combination).PHP_EOL;
			$this->step($this->combination);
		}
		return $result;
	}
	
}