<?php
namespace DerickR\GPX;

class Utils
{
	static function createMergedTrack( array $tracks, float $closeness = 25.0 ) : Track
	{
		/* Create track from first item in list, then unshift */
		$track = new Track( array_shift( $tracks )->getTrackData() );

		/* Loop until there are no more tracks in $tracks */
		while ( count( $tracks ) > 0 )
		{
			$tracks = array_values( $tracks );

			$currentTrackCount = count( $tracks );

			for ( $i = 0; $i < count( $tracks ); $i++ )
			{
				$currentTrack = $tracks[$i];

				$mergedFirst = $track->getFirstPointAsGeoJson();
				$mergedLast  = $track->getLastPointAsGeoJson();
				$currentFirst = $currentTrack->getFirstPointAsGeoJson();
				$currentLast = $currentTrack->getLastPointAsGeoJson();

				if ( haversine( $mergedLast, $currentFirst ) < $closeness )
				{
					/* Just append the track to the existing track */
					$track->appendTrack( $currentTrack );
					unset( $tracks[$i] );
					break;
				}
				else if ( haversine( $mergedLast, $currentLast ) < $closeness )
				{
					/* Reverse $currentTrack, and append */
					$currentTrack->reverse();
					$track->appendTrack( $currentTrack );
					unset( $tracks[$i] );
					break;
				}
				else if ( haversine( $mergedFirst, $currentFirst ) < $closeness )
				{
					/* Reverse $track, and append $currentTrack */
					$track->reverse();
					$track->appendTrack( $currentTrack );
					unset( $tracks[$i] );
					break;
				}
				else if ( haversine( $mergedFirst, $currentLast ) < $closeness )
				{
					/* Reverse $track and $currentTrack, and append */
					$track->reverse();
					$currentTrack->reverse();
					$track->appendTrack( $currentTrack );
					unset( $tracks[$i] );
					break;
				}
			}

			if ( $currentTrackCount == count( $tracks ) )
			{
				throw new \Exception( "No close tracks " );
			}
		}

		return $track;
	}
}
?>
