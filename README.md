# Github Test Project

# Installation Steps (Comamnds)
* git clone https://github.com/smtlab/github-test-project.git
* cd to project folder and run `composer install`
* `php src/app github:import <database> <user> <password>`

# Description
This is a demo project which demonstrates the pulling of data through github php api.
In this case the repositories for user `symfony` is being pulled and displayed in html layout with pagination functionality.


* `index.php` (Layout)
* `api.php` (Github api usage)
* `src/GithubUser.php` Custom api class
