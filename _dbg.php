<?php
session_name('ci_session');
session_save_path(sys_get_temp_dir());
@session_start();
echo "SID=" . session_id() . "<br>";
echo "SAVEPATH=" . session_save_path() . "<br>";
echo "TEMP=" . sys_get_temp_dir() . "<br>";
echo "SESSION=" . print_r($_SESSION, true) . "<br>";
