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
}
?>
