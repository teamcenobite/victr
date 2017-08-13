# victr assesment demo

## About

This is a quick little demo for code evaluation purposes.  Two main tasks:
1. Using the Git API, grab the most starred PHP public repositories, and save them to MySQL database.
2. Display the list of repositories, providing the requested details and a link to the repository on Github.


## Compatibility

    PHP: version 5.6
    MySQL: version 5.6.36
    

## Notes

Due to the maximum returned search result limits of 1000, I decided to just captured the top 1000 starred repositories.  After a handful of attempts, I could not find a way to bypass this, and so therefore no viable way to get an accurate list of the entire Git directory.  

Notes on Code:
1. Used Twitter Bootstrap framework for front end styling
2. Used Codeigniter framework for PHP application.  I like the simplicity and extendability of Codeigniter...clean MVC flow and strong active record library.
3. Used simple templating structure with the Stencil library.
4. Used custom core model and controller for shared custom code between all models and controllers.
5. Used database mode for Codeigniter sessions for compatibility


## SQL

-- Server version: 5.6.36
-- PHP Version: 5.6.30
-- Database: `victr`
--
--
-- Table structure for table `ci_sessions`

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `git_repo`

CREATE TABLE IF NOT EXISTS `git_repo` (
  `repository_id` bigint(20) unsigned NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text,
  `html_url` varchar(256) NOT NULL DEFAULT '',
  `created_at` varchar(32) NOT NULL,
  `updated_at` varchar(32) NOT NULL,
  `stargazers_count` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `git_repo`
--
ALTER TABLE `git_repo`
  ADD PRIMARY KEY (`repository_id`);
