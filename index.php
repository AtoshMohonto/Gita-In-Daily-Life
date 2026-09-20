<?php
/**
 * This project's web root is /public (app code outside /public stays non-web-accessible).
 * On environments without a vhost pointed at /public, this redirects there.
 */
header('Location: public/');
exit;
