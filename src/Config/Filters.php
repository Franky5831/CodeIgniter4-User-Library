<?php

/**
 * Adds the redirect to login filter
 */
config('Filters')->aliases["redirect_to_login"] = \Franky5831\CodeIgniter4UserLibrary\Filters\RedirectToLogin::class;
config('Filters')->aliases["session_hijacking"] = \Franky5831\CodeIgniter4UserLibrary\Filters\CheckUserSession::class;
config('Filters')->globals["before"][] = "redirect_to_login";
config('Filters')->globals["before"][] = "session_hijacking";
