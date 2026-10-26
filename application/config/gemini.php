<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Key diambil dari file lokal yang TIDAK di-commit
$local = APPPATH . 'config/gemini.local.php';
$config['gemini_api_key'] = file_exists($local) ? (require $local) : getenv('GEMINI_API_KEY');