<?php
namespace Derickr\GPX;

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

	function reverse() : void
	{
		$this->track = array_reverse( $this->track );
	}
}
?>
