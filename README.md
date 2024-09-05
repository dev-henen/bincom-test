# Configuration Guide

This guide provides a breakdown of the settings available in the `config.ini` file, which is used to configure the application and database settings for this project.

## [config] Section

The `[config]` section contains settings related to the general configuration of the application.

- **CURRENT_HOSTED_DOMAIN**: The domain where the application is hosted.  
  Example: `localhost`

- **SHOW_ERRORS**: Controls whether errors should be displayed in the application.  
  Set to `1` to show errors, or `0` to hide them.  
  Example: `1`

- **TIME_ZONE**: The time zone setting for the application. This should be set to a valid time zone string.  
  Example: `'Africa/Lagos'`

## [database] Section

The `[database]` section contains settings related to connecting to the database.

- **DB_SERVER_NAME**: The name of the database server. Typically, this is `localhost` for local development environments.  
  Example: `localhost`

- **DB_USERNAME**: The username used for connecting to the database.  
  Example: `root`

- **DB_PASSWORD**: The password for the database user. Leave this empty if no password is set.  
  Example: (leave empty if no password)

- **DB_NAME**: The name of the database to connect to.  
  Example: `bincom_test`

- **DB_PORT**: The port number for the database server. For MySQL, the default is usually `3306`.  
  Example: (leave empty if using default)

- **DB_SOCKET**: The socket path for the database server. This is usually not needed unless explicitly specified by the database server.  
  Example: (leave empty if not required)

---

Ensure you provide the correct values for each setting based on your environment and needs. Save the `config.ini` file after making changes, and restart the application for the changes to take effect.

