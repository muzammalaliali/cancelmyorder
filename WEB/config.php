
<?php

/**
 * config.php
 * @author     Fabian Tomischka <fabian@fabito.net>
 * @copyright  2022 Fabito Media
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Secret Key + Steam API key
    |--------------------------------------------------------------------------
    |
    | The secret key is used to confirm that the server sending the statistics
    | is actually the confirmed server and not a 3rd party adding new entries.
    | Please change the secret key to something safe, if possible, about 16 characters.
    |
    | The Steam API key is used to request the users avatars. Please visit
    | https://steamcommunity.com/dev/apikey. Request an API key if you haven't yet
    | and enter it below.
    |
    */

    'secret' => 'simplestat-web-leaderboards',

    'steamApiKey' => 'E20D082ABCD1831FCCE5326299EC213FA',


    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | The stats do require a working database. It supports MySql/MariaDB/PostGreSQL databases.
    | You can usually get a database setup with your webhost.
    | Host: The host of your server. localhost can usually be kept as default
    | User: The username of the database user
    | Password: The password for that specific user
    | Name: The database name you created (this is where we add the data)
    | Port: Default is 3306. Leave this as-is in most cases
    | Table: The table where the data is written to. Leave as-is if you use the default SQL import.
    |
    */

    'database' => [

        'host' => 'localhost',

        'user' => 'root',

        'password' => '',

        'name' => 'simplestats-web-leaderboard',

        'port' => '3306',

        'table' => 'ruststats',

    ],


    /*
    |--------------------------------------------------------------------------
    | Servers
    |--------------------------------------------------------------------------
    |
    | Rust Stats supports multiple servers. Here you define all your servers which
    | you have the plugin running on. This will enable players to select servers
    | in the statistics and view it for a specific server. If you only have 1 server it
    | will not show the selection. You can also disable server selection if you want.
    | The ID (first number) has to be the same id you assign to the server in the plugin and has to be a number.
    | Server selection is enabled by default.
    |
    | Example: Server ID => Server Name
    |
    */

    'servers' => [
    
        1 => "Server 1",
        2 => "Server 2",
        3 => "Server 3",
        4 => "Server 4",
        5 => "Server 5",
        6 => "Server 6",
        7 => "Server 7",
        8 => "Server 8",
        9 => "Server 9",
        10 => "Server 10",

    ],

    'serverSelection' => 'enabled',


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | For performance purposes, entries are paginated by default. Users will have
    | the option to view later parts of the table by clicking on the buttons
    | below the table. The number represents the amount of players shown per page.
    | Default: 10
    |
    */

    'pagination' => 10,


    /*
    |--------------------------------------------------------------------------
    | Default Order
    |--------------------------------------------------------------------------
    |
    | Users have the option to manually sort the statistics table by whatever
    | statistic they want. However, you are able to set a default.
    | Please use the corresponding number. A list of sortable columns:
    |
    | 2 - Playtime
    | 3 - Kills (Default)
    | 4 - Deaths
    | 5 - K/D Ratio
    |
    */

    'orderBy' => 3,


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    |
    | Enable / disable the search box for players to search for specific usernames
    | or steam id's. Set to enabled/disabled. Default: enabled
    |
    */

    'search' => 'enabled',


    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | The stats page does have multi-theme support. By default, it ships with 3
    | different themes, a dark, a light and a rust-themed one. However, you can always
    | add your custom theme. Simply add a new .css file in the css folder, e.g.
    | custom.css, copy the contents of either light/dark/rust.css and add your
    | own colors to it. Now put the name of the file into the theme variable,
    | in our example this would be custom.
    |
    | Options: rust, dark, light
    |
    */

    'theme' => 'dark',

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    |
    | If you are not using english on your server website you'll be able to change
    | the language settings for each word displayed in the stats here. There are
    | variables inside those texts (marked by the underscore _). Do not change those.
    |
    */

    'language' => [

        'search' => 'Search',
        'player' => 'Player',
        'playtime' => 'Playtime',
        'kills' => 'Kills',
        'deaths' => 'Deaths',
        'kdr' => 'K/D Ratio',
        'no_players' => 'No players found',
        'players_stats' => 'Showing _START_ to _END_ of _TOTAL_ players',
        'players_stats_empty' => 'Showing 0 to 0 of 0 players',
        'players_stats_filtered' => '(filtered from _MAX_ players)',
        'paginate_first' => 'First',
        'paginate_last' => 'Last',
        'paginate_next' => 'Next',
        'paginate_previous' => 'Previous'

    ],

]; 