<?php

namespace App\config;

use mysqli;

class Database
{
    /** @var mysqli */
    private $conn;

    public function __construct()
    {
        $dbUrl = env_var('AIVEN_MYSQL_URI');   // e.g. mysql://user:pass@host:14909/defaultdb?ssl-mode=REQUIRED
        $sslCa = env_var('DB_SSL_CA');         // absolute path to Aiven ca.pem

        if (!$dbUrl) {
            throw new \RuntimeException('AIVEN_MYSQL_URI is required but not set.');
        }
        if (!$sslCa || !is_file($sslCa)) {
            throw new \RuntimeException('DB_SSL_CA is required and must point to a valid ca.pem file.');
        }

        $parts = parse_url($dbUrl);
        if ($parts === false || empty($parts['host'])) {
            throw new \RuntimeException('Invalid AIVEN_MYSQL_URI.');
        }

        $host   = $parts['host'];
        $port   = isset($parts['port']) ? (int)$parts['port'] : 3306;
        $user   = $parts['user'] ?? '';
        $pass   = $parts['pass'] ?? '';
        $dbname = isset($parts['path']) ? ltrim($parts['path'], '/') : '';

        // Parse query string (for ssl-mode etc.) – SSL is always enforced here.
        $query = parse_url($dbUrl, PHP_URL_QUERY) ?: '';
        parse_str($query, $q);

        $charset = 'utf8mb4';

        $link = mysqli_init();
        if (!$link) {
            throw new \RuntimeException('mysqli_init() failed');
        }

        // Sensible options
        @mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        @mysqli_options($link, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
        if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
            @mysqli_options($link, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
        }

        // Enforce SSL with Aiven CA
        if (!@mysqli_ssl_set($link, null, null, $sslCa, null, null)) {
            throw new \RuntimeException('Failed to set SSL parameters (CA).');
        }

        $flags = defined('MYSQLI_CLIENT_SSL') ? MYSQLI_CLIENT_SSL : 0;

        if (!@mysqli_real_connect($link, $host, $user, $pass, $dbname, $port, null, $flags)) {
            $err = mysqli_connect_error();
            throw new \RuntimeException('Database connection failed: ' . ($err ?: 'unknown error'));
        }

        if (!$link->set_charset($charset)) {
            $err = $link->error;
            $link->close();
            throw new \RuntimeException('Failed to set charset: ' . $err);
        }

        $this->conn = $link;
    }

    /** @return mysqli */
    public function getConnection()
    {
        return $this->conn;
    }
}
