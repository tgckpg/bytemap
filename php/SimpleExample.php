<?php

require "ByteMap.php";

class MyCompiler implements ByteMapCompiler
{
	protected function get_indent( $l )
	{
		return str_repeat("	", $l);
	}

	function branchOpen( $level, \Branch $b )
    {
		$indent = $this->get_indent( $level );

        $sequence = implode( " ", array_map( "chr", $b->byteSequence ) );
        if( $b->isHead )
        {
            return "{$indent}Head Branch $sequence\n";
        }
        else
        {
            return "{$indent}Branch $sequence\n";
        }
    }

	function branchValue( $level, $value, $seq_len, $wholeByte, $index_byte )
	{
		$indent = $this->get_indent( $level + 1 );

		if($wholeByte)
		{
			$index_byte ++;
			return "{$indent}wholeByte value: $value\n";
		}
		else
		{
			return "{$indent} value: $value\n";
		}
	}

	function branchClose( $level, $isHead )
	{

		return $this->get_indent($level) . "Closed\n";
	}
}

$level = 1;
$bm = new ByteMap( function(){} );

$seqArr = [
    "a" => 1
    , "aa" => 2
    , "aaa" => 3
    , "aab" => 4
    , "aca" => 10
];

echo $bm->cascade( $seqArr )->compile( $level, new MyCompiler() );
