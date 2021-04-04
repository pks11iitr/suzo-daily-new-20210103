<?php
//day will be represented as 0,1,2,3,4,5,6
function calculateDaysCountBetweenDate($start_date, $end_date, $dayslist){
    $date=$start_date;
    $days=0;
    while($date<=$end_date){
        if(in_array(date('l', strtotime($date)), $dayslist))
                $days++;
        $date=date('Y-m-d', strtotime('+1 day', strtotime($date)));
    }
    return $days;
}

