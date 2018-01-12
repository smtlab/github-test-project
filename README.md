# Github Test Project

# Description
This is a demo project which demonstrates the importing of data from github to mysql database thrugh github php api.
In this case the repositories for user `symfony` is being pulled and dumped to the mysql database.

# Installation Steps (Comamnds)
* git clone https://github.com/smtlab/github-test-project.git
* cd to project folder and run `composer install`
* `php src/app github:import <database> <user> <password>` Ex: `php src/app github:import github_repos root dbpass123`
* Thats it! Navigate to github-api-test/index.php to browse imported data
