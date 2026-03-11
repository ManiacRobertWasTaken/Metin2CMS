<?php

header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";
echo "<p>The requested address <strong>".htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, "UTF-8")."</strong> was not found.</p>";
exit();