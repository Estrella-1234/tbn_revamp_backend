<?php
// Execute artisan command
$output = shell_exec('php artisan storage:link');

// Output the result (optional)
echo "<pre>$output</pre>";
?>
