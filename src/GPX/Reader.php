<?php
namespace DerickR\GPX;

class Reader
{
	private Track $track;

	function __construct( string $fileName )
	{
		$this->track = $this->parseGpx( $fileName );
	}

	private function parseGpx( $fileName ) : Track
	{
		$points = [];
		$s = simplexml_load_file( $fileName );

		/* Try rte/rtept */
		foreach ( $s->rte as $rte )
		{
			foreach ( $rte->rtept as $rtept )
			{
				$points[] = [ (float) $rtept['lon'], (float) $rtept['lat'] ];
			}
		}
		if ( count( $points ) > 0 )
		{
			return new Track( $points );
		}

		/* No points in a rte/rtept, try trk/trkseg/trkpt */
		foreach ( $s->trk as $trk )
		{
			foreach ( $trk->trkseg as $trkseg )
			{
				foreach ( $trkseg->trkpt as $trkpt )
				{
					$points[] = [ (float) $trkpt['lon'], (float) $trkpt['lat'] ];
				}
			}
		}

		return new Track( $points );
	}

	public function getTrack() : Track
	{
		return $this->track;
	}
}
?>
