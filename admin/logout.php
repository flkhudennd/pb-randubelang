<?php
session_unset();
session_destroy();
header('Location: /portal-berita/pb-admin');
exit;
?>
