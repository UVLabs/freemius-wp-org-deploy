<?php

$commit_message = getenv( 'COMMITTEXT' );

// Merge commit message has two new lines after title, this is set automatically by Github
$commit_message_parts = explode( "\n\n", $commit_message );

// Get everything after the commit message title
$commit_message_body = $commit_message_parts[1];

// Push commit message body (fix, changes etc ) into an array for formatting
$commit_message_body_lines = explode( "\n", $commit_message_body );

$release_notes = '';

foreach ( $commit_message_body_lines as $commit_message_body_line ) {
	// Format Markdown
	$release_notes .= '* ' . $commit_message_body_line . PHP_EOL;
}

// Write to file
file_put_contents( 'release_notes.txt', $release_notes );
exit;
