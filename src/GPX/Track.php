<?php
namespace DerickR\GPX;

class Track
{
	private array $track;

	function __construct( array $track )
	{
		$this->track = $track;
	}

	function getTrackData() : array
	{
		return $this->track;
	}

	function getFirstPointAsGeoJson() : array
	{
		return [
			'type' => 'Point',
			'coordinates' => $this->track[0]
		];
	}

	function getLastPointAsGeoJson() : array
	{
		return [
			'type' => 'Point',
			'coordinates' => $this->track[count($this->track) - 1]
		];
	}

	function appendTrack( Track $newTrack ) : void
	{
		foreach ( $newTrack->getTrackData() as $pointPair )
		{
			$this->track[] = $pointPair;
		}
	}

	function reverse() : void
	{
		$this->track = array_reverse( $this->track );
	}

	function truncatePrecision( int $decimals )
	{
		$tmpTrack = array_map(
			function($pair) use ( $decimals ) {
				$factor = pow( 10, $decimals );

				$pair[0] = floor($pair[0] * $factor) / $factor;
				$pair[1] = floor($pair[1] * $factor) / $factor;

				return $pair;
			},
			$this->track
		);

		$this->track = [];

		$currentPair = [0, 0];

		foreach ( $tmpTrack as $pair )
		{
			if ( $currentPair[0] != $pair[0] || $currentPair[1] != $pair[1] )
			{
				$this->track[] = $pair;
				$currentPair = $pair;
			}
		}
	}
}
?>
