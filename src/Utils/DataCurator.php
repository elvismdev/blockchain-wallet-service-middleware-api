<?php

namespace App\Utils;

class DataCurator
{
    /**
     * Convert recipients string to array format.
     */
    public static function recipientsToArray(string $string): array
    {
    	$finalArray = [];
    	$asArr = explode( ',', $string );
    	foreach( $asArr as $val ){
    		$tmp = explode( '|', $val );
    		$finalArray[ $tmp[0] ] = $tmp[1];
    	}

    	return $finalArray;
    }
}