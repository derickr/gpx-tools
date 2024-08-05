GPX Utils
=========

A set of utilities to operate on GPX files and sets of GPX files.

Reading GPX Files
-----------------

The GPX reader class ``DerickR/GPX/Reader`` can be used to read a GPX file.

It can read GPX routes (``rte``/``rtept``) and if none are
present, it will read a GPX track (``trk``/``trkseg``/``trkpt``) instead.

You can read a GPX file with::
	
	<?php
	require __DIR__ . '/vendor/autoload.php';
	
	use DerickR\GPX\Reader;

	$gpxInformation = new Reader( $file );
	?>

To access the tracks, you can use the ``getTrack()`` method which returns a
``DerickR/GPX/Track`` object, which encapsulates a ordered set of coordinate
pairs. If the GPX file had multiple tracks, these are merged into one::

	$track = $gpxInformation->getTrack();

To get individual tracks out of a GPX file, you can use the ``getTracks()``
method to obtain an array of ``Track`` objects::

	$tracks = $gpxInformation->getTracks();
	foreach ( $tracks as $track )
	{
		/* Do something with $track */
	}

For GPX files that contain routes (``rte``/``rtept``) this will still return a
merged track.

For GPX files that contain tracks (``trk``/``trkseg``) this return an array of
tracks, with each ``Track`` object representing a track as stored in the GPX
file.

To merge tracks stored in multiple GPX files, see `Merging GPX Files`_.


Writing GPX Files
-----------------

The GPX writer class ``DerickR/GPX/Writer`` can be used to write a ``Track``
object as a GPX track using ``trk``/``trkseg``/``trkpt`` elements. It only
supports the ``lat`` and ``lon`` XML elements.

You can write a track to GPX file with::

	<?php
	require __DIR__ . '/vendor/autoload.php';

	use DerickR\GPX\Writer;

	$writer = new Writer( $track );
	$writer->writeGpx( 'route-test.gpx' );
	?>

Instead of writing GPX XML directly to a file, it is also possible to retrieve
a string with this information, by using the `getGpxString()` method::

	<?php
	require __DIR__ . '/vendor/autoload.php';

	use DerickR\GPX\Writer;

	$writer = new Writer( $track );
	$xmlString = $writer->getGpxString();
	?>


Operating on Tracks
-------------------

The ``Track`` class has several methods to operate on GPX tracks. The
following methods are supported:

``getTrackData``
	Returns an array with lat/lon coordinate pairs.

``getFirstPointAsGeoJson``
	Returns the first element in the track as a GeoJson formatted array.

``getLastPointAsGeoJson``
	Returns the last element in the track as a GeoJson formatted array.

``appendTrack( Track $newTrack )``
	Adds all the coordinate pairs from ``$newTrack`` in sequence to the track.

``reverse()``
	Reverses the array of coordinate pairs that make up the track.

``truncatePrecision( int $decimals )``
	Loops over all the coordinate pairs in the track, and truncates the number
	of decimals in the coordinate pair to ``$decimals``. In a second loop, it
	merges two consecutive coordinate pairs if they are the same after
	truncation.


Merging GPX Files
-----------------

The ``Utils::createMergedTrack`` static method can be used to combine a set of
GPX files. The method automatically reverses and selects the order in which
the tracks are merged, based on the first and last points in the track.

The second argument to ``createMergedTrack`` determines how far (in metres)
the different points of different tracks might lay away from each other to be
considered as consecutive. By default, this is ``25.0`` metres.

If the GPX files are not consecutive then an Exception in thrown.

For example to merge all GPX files in a directory, you would use::

	<?php
	require __DIR__ . '/vendor/autoload.php';

	use DerickR\GPX\Reader;
	use DerickR\GPX\Utils;
	use DerickR\GPX\Writer;

	// Find all .gpx files
	$files = glob( '*.gpx' );

	// Read all tracks into an array
	$tracks = [];
	foreach ( $files as $file )
	{
		$tracks[] = (new Reader( $file ))->getTrack();
	}

	// Merge all tracks into a single one.
	$track = Utils::createMergedTrack( $tracks );

	// Write created track to a file
	$writer = new Writer( $track );
	$writer->writeGpx( 'route-test.gpx' );
	?>

Changelog
=========

======== ===================================================
Release  Changes
======== ===================================================
0.1.0    Initial Release
0.2.0    Added the DerickR\GPX\Writer::getGpxString() method
0.3.0    Added the DerickR\GPX\Reader::getTracks() method
======== ===================================================
