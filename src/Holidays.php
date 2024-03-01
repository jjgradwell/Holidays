<?php

class Holidays
{

  function __construct( $year = null ) {
  	$this->year = $year ?? date( "Y" );
    $this->file = "./src/default.json";
    $this->dates = json_decode( file_get_contents( $this->file ), true );

    foreach( $this->dates as $name => &$values ) {
      if ( method_exists( $this, $values['date'] ) ) {
    		$code = call_user_func( array( $this, $values['date'] ) );
    	} else {
    		$code = strtotime( sprintf( "%s %s", $values['date'], $this->year ) );
    		if ( $values['move'] != "" ) $code = strtotime( $values['move'], $code );
    	}
    	$this->dates[ $name ] = date( 'Y-m-d', $code );
    }
    $this->dates = array_values( $this->dates );
  }

/*
* Named date functions go below
*/

  function victoriaDay() {
    $str = strtotime( 'May 25 ' . $this->year );
    return date( "N", $str ) == 1 ? $str : strtotime( 'previous monday', $str );
  }

  function goodFriday() {
    return strtotime( 'previous friday', easter_date( $this->year ) );
  }

  function easterMonday() {
    return strtotime( 'next monday', easter_date( $this->year ) );
  }

}
