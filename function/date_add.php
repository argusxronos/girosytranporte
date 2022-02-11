<?php
	function date_fix_date(&$month,&$day,&$year,$unix=true){
		if($month>12){
			while ($month>12){
				$month-=12;//subtract a $year
				$year++;//add a $year
			}
		} else if ($month<1){
			while ($month<1){
				$month +=12;//add a $year
				$year--;//subtract a $year
			}
		}
		if ($day>31){
			while ($day>31){
				if ($month==2){
					if (is_leap_year($year)){//subtract a $month
						$day-=29;
					} else{
						$day-=28;
					}
					$month++;//add a $month
				} else if (date_hasThirtyOneDays($month)){
					$day-=31;
					$month++;
				} else{
					$day-=30;
					$month++;
				}
			}//end while
			while ($month>12){ //recheck $months
				$month-=12;//subtract a $year
				$year++;//add a $year
			}
		} else if ($day<1){
			while ($day<1){
				$month--;//subtract a $month
				if ($month==2){
					if (is_leap_year($year)){//add a $month
						$day+=29;
					}else{
						$day+=28;
					}
				} else if (date_hasThirtyOneDays($month)){
					$day+=31;
				} else{
					$day+=30;
				}
			}//end while
			while ($month<1){//recheck $months
				$month+=12;//add a $year
				$year--;//subtract a $year
			}
		} else if ($month==2){
			if (is_leap_year($year)&&$day>29){
				$day-=29;
				$month++;
			} else if($day>28){
				$day-=28;
				$month++;
			}
		} else if (!date_hasThirtyOneDays($month)&&$day>30){
			$day-=30;
			$month++;
		}
		if ($year<1900) $year=1900;
		if ($unix){
			return "$year-$month-$day";
		} else{
			return "$month-$day-$year";
		}
	}
	/**
	 * Checks to see if the month has 31 days.
	 * @param integer $month
	 * @return boolean True if the month has 31 days
	 */
	function date_hasThirtyOneDays($month){
		//1234567 89012:1357 802
		//JfMaMjJ AsOnD:JMMJ AOD
		if ($month<8)
			return $month%2==1;
		else
			return $month%2==0;
	}
	/**
	 * Checks to see if the year is a leap year.
	 * @param integer $year
	 * @return boolean True if the year is a leap year
	 */
	function is_leap_year($year){
		return (0 ==$year%4&&0!=$year%100 || 0 ==$year%400);
	}
?>