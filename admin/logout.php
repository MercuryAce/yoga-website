<?php

require dirname(__DIR__) . '/includes/auth.php';
cs_session_clear();
cs_redirect('/admin/login.php');
