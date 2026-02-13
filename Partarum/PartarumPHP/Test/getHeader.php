<?php
$headers = headers_list();
foreach ($headers as $header) {
    if (!str_starts_with($header, 'Authorization')) {
        header("Location: https://${$domain}/landingPage");
    }
}