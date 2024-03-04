<?php
    function men($elem)
    {
        if ($elem > 0) {
            return true;
        } else {
            return false;
        }
    }
  
    function women($elem)
    {
        if ($elem < 0) {
            return true;
        } else {
            return false;
        }
    }

    function random_float($min, $max) { 
        return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX ); 
    }    
?>