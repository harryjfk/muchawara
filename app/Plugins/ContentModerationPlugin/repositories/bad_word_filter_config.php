<?php
use App\Components\Plugin;

return [
	'source'      => 'file',
	'source_file' =>  Plugin::path('ContentModerationPlugin/repositories/bad_words.php'),
	'strictness'  => 'all',
	'also_check'  => [],
];