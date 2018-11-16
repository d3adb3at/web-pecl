#!/usr/bin/env php
<?php

/*
  +----------------------------------------------------------------------+
  | The PECL website                                                     |
  +----------------------------------------------------------------------+
  | Copyright (c) 1999-2018 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | https://php.net/license/3_01.txt                                     |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Authors: Stig S. Bakken <ssb@fast.no>                                |
  +----------------------------------------------------------------------+
*/

/**
 * Debugging and development script to export certain data from database.
 */

require_once __DIR__.'/../include/bootstrap.php';

if ($argc < 2) {
    die('Please provide argument what you want to output'."\n");
}

$what = $argv[1];

if ($what == "avail") {
    print "unavail\n";
    $sth = $dbh->query("SELECT username,path FROM cvs_acl");
    while ($sth->fetchInto($row, DB_FETCHMODE_ORDERED) === DB_OK) {
        $acl_paths[$row[1]][$row[0]] = true;
    }
    foreach ($acl_paths as $path => $acldata) {
        $users = implode(",", array_keys($acldata));
        print "avail|$users|$path\n";
    }
} elseif ($what == "cvsusers") {
    $sth = $dbh->query("SELECT handle,name,email FROM users");
    while ($sth->fetchInto($row, DB_FETCHMODE_ORDERED)) {
        print implode(":", $row) . "\n";
    }
} elseif ($what == "passwd") {
    $sth = $dbh->query("SELECT handle,password FROM users");
    while ($sth->fetchInto($row, DB_FETCHMODE_ORDERED)) {
        print implode(":", $row) . ":cvs\n";
    }
} elseif ($what == "writers") {
    $sth = $dbh->query("SELECT DISTINCT username FROM cvs_acl WHERE access = 1");
    while ($sth->fetchInto($row, DB_FETCHMODE_ORDERED)) {
        print "{$row[0]}\n";
    }
}
