<?php

$ldap_server = "ldap://ldap.example.com";
$ldap_port = "389";
$ldap_dn = "cn=admin,dc=example,dc=com"; // The admin DN
$ldap_password = "password"; // The admin password

// Connect to the LDAP server
$ldap_conn = ldap_connect($ldap_server, $ldap_port);
ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3); // Set LDAP version to 3

if ($ldap_conn) {
	// Bind as admin user
	$ldap_bind = ldap_bind($ldap_conn, $ldap_dn, $ldap_password);

	if ($ldap_bind) {
		// Authenticate user
		$username = $_POST['username'];
		$password = $_POST['password'];
		$ldap_base_dn = "dc=example,dc=com"; // The base DN for user search
		$ldap_filter = "(uid=$username)"; // The LDAP filter to find user by username
		$ldap_search = ldap_search($ldap_conn, $ldap_base_dn, $ldap_filter);
		$ldap_entries = ldap_get_entries($ldap_conn, $ldap_search);

		if ($ldap_entries['count'] == 1) {
			$user_dn = $ldap_entries[0]['dn'];
			$user_bind = ldap_bind($ldap_conn, $user_dn, $password);

			if ($user_bind) {
				// Authentication success
				echo "Login successful";
			} else {
				// Authentication failed
				echo "Invalid username or password";
			}
		} else {
			// User not found
			echo "User not found";
		}
	} else {
		// Bind failed
		echo "LDAP bind failed";
	}
} else {
	// Connection failed
	echo "LDAP connection failed";
}
