<?php
namespace DerickR\GPX;

class Reader
{
	/**
	 * @type array[Track]
	 */
	private array $tracks;

	function __construct( string $fileName )
	{
		$this->parseGpx( $fileName );
	}

	private function parseGpx( $fileName )
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
			$this->tracks = [ new Track( $points ) ];
			return;
		}

		/* No points in a rte/rtept, try trk/trkseg/trkpt */
		foreach ( $s->trk as $trk )
		{
			$points = [];

			foreach ( $trk->trkseg as $trkseg )
			{
				foreach ( $trkseg->trkpt as $trkpt )
				{
					$points[] = [ (float) $trkpt['lon'], (float) $trkpt['lat'] ];
				}
			}

			$this->tracks[] = new Track( $points );
		}
		return;
	}

	public function getTrack() : Track
	{
		if ( count( $this->tracks ) == 1 )
		{
			return $this->tracks[0];
		}

		return Utils::createMergedTrack( $this->tracks );
	}

	public function getTracks() : array
	{
		return $this->tracks;
	}
}
?>
