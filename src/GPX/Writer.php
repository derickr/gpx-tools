<?php
namespace Derickr\GPX;

class Writer
{
	private Track $track;

	function __construct( Track $track )
	{
		$this->track = $track;
	}

	function writeGpx( string $fileName )
	{
		$points = $this->track->getTrackData();

		$result = <<<HEAD
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<gpx xmlns="http://www.topografix.com/GPX/1/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" creator="waymarkedtrails.org" version="1.1" xsi:schemaLocation="http://www.topografix.co
m/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">

  <trk>
    <trkseg>

HEAD;

		foreach( $points as $point )
		{
			$result .= "    <trkpt lat=\"{$point[1]}\" lon=\"{$point[0]}\"/>\n";
		}

		$result .= <<<FOOT
    </trkseg>
  </trk>
</gpx>

FOOT;

		file_put_contents( $fileName, $result );
	}
}
?>
