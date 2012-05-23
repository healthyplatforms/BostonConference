<?php

if ( count( $tracks ) > 0 )
{
?>

<h2>Tracks</h2>
<ul>
<?php

	foreach( $tracks as $track )
	{
		echo '<li>'.$track['name'].'</li>';
	}
?>
</ul>
<?php
}
?>
<h2>Schedule</h2>

<?php

function getTalkClass( $duration, $talks, $i )
{
	$duration = floor($duration/15);
	$class = '';

	if ( $duration == 0 || $duration == 1 )
		$class = 'minutes-15';
	else if ( $duration == 2 )
		$class = 'minutes-30';
	else if ( $duration == 3 )
		$class = 'minutes-45';
	else
		$class = 'minutes-60';

	if ( $talks > 1 )
		$class .= ' ';

	if ( $talks == 2 )
		$class .= 'double-block';
	else if ( $talks == 3 )
		$class .= 'tripple-block';
	else if ( $talks == 4 )
		$class .= 'quad-block';

	if ( $i > 0 )
		$class .= ' talk-'.($i+1);

	return $class;
}

if ( ($c = count( $talks )) > 0 )
{
	echo '<div class="schedule">';

	// Stores the current day for day headers
	$day = null;
	$dayIndex = 0;

	// Stores the block state for time display
	$previousBlock = 0;
	$blockEnd = 0;

	// Stores the status of multi-block talks
	$blockMap = array( 0, 0, 0, 0 );

	// Loop through all the blocks (note: $c is set in the previous `if`)
	for( $i = 0; $i < $c; $i++ )
	{
		$talk = $talks[$i]['Talk'];

		$startTime = strtotime($talk['start_time']);

		// Echo the day header if appropriate
		if ( date('z',$startTime) != $day ) {
			$day = date('z',$startTime);
			$previousBlock = 0;

			echo '<div class="day">Day '.(++$dayIndex).' - '.date('l, F jS, Y',$startTime).'</div>';
		}

		// Calulate the current block
		$block = $startTime - (date('i',$startTime)%30*60);
		$blockEnd = $block + $talk['duration']*60;

		// Fill in "empty" time blocks if appropriate
		if ( $previousBlock != 0 ) {
			$emptyBlocks = 1;	

			for ( $b = $previousBlock + 30*60; $b < $block; $b += 30*60 ) {
				echo '<div class="block">';
				echo '<div class="time"><p>'.date('h:i a',$b).'</p></div>';
				echo '</div>';
				$emptyBlocks++;
			}

			foreach ( $blockMap as $bi => $blockHeight ) {
				if ( $blockHeight > $emptyBlocks )
					$blockMap[$bi] -= $emptyBlocks;
				else
					$blockMap[$bi] = 0;
			}
		}
		$previousBlock = $block;

		// Start the current time block
		echo '<div class="block">';


		// Create an array of talks in this time block
		$talkBlock = array($talk);

		for ( $ii = $i+1; $ii < $c; $ii++ ) {
			$nextTalk = $talks[$ii]['Talk'];

			$tmp = strtotime($nextTalk['start_time']);
			if ( $tmp-(date('i',$tmp)%30*60) == $block ) {
				if ( $nextTalk['duration'] > $talk['duration'] )
					$bockEnd = $block + $nextTalk['duration']*60;

				$talkBlock[] = $nextTalk;
				$i++;
			}
			else {
				break;
			}
		}

		$ct = count($talkBlock);

		// Calculate overlap and take that into consideration for number of columns
		foreach ( $blockMap as $blockHeight ) {
			if ( $blockHeight > 0 )
				$ct++;
		}

		// Echo the time for this block
		echo '<div class="time"><p>'.date('h:i a',$block).'</p></div>';


		$col = 0;
		foreach ( $talkBlock as $ii => $talk ) {
			if ( $blockMap[$col] > 0 )
				$col++;

			echo '<div class="talk '.getTalkClass($talk['duration'],$ct, $col).'"><p>'.$talk['topic'].'</p></div>';
			$blockMap[$col] += ceil($talk['duration']/30);

			$col++;
		}

		echo '</div>';
	}

	if ( $previousBlock != 0 ) {
		for ( $b = $previousBlock + 30*60; $b < $blockEnd; $b += 30*60 ) {
			echo '<div class="block">';
			echo '<div class="time"><p>'.date('h:i a',$b).'</p></div>';
			echo '</div>';
		}
	}


	echo '</div>';
}
?>