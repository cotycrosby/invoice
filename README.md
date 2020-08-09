## PHP Invoice

I needed a way to track hours worked on a project. This is a follow up to my timetracker app that I made in python. 

### How to use it 
I generally have all my programs installed under `~/programs/`, but you can navigate to wherever.  
* `git clone https://github.com/cotycrosby/invoice.git` 

#### Install PHP
* I'm using php v7.3 at the time of writing this. 
* If you're on windows you will need to have PHP set to your PATH variables.

#### Creating an alias
* I highly recommend creating an alias for this `php /path/to/where/the/invoice/tool/is/located/index.php/`
* In ~/.bashrc/ add a line `alias invoice='php /path/to/where/the/invoice/tool/is/located/index.php/'`
* Linux and MAC this should be fine. Windows users should use git bash.


#### Initializing a database
Change to the projects directory you wish to track.
* `cd /path/to/project`
* Run `invoice init` 
* A file name `.invoice.db` will be created. 
* Highly recommend adding it to the gitignore file.  

Example
`$ invoice init`  
`Database created successfully!`

#### Adding an entry to the database
* After completing a task run `invoice add hours(float) "message"`
Example  
$ invoice add 2.5 "Updated template files to reflect theme changes"  
`Added the entry: 2.5 => Updated template files to reflect theme changes`

#### Obtaining the invoice records
* When you're ready to "cash out", you can run `invoice get month(MM) year(YYYY) rate(0.00-infinity)`
* ie `invoice get 08 2020 65`
* All arguments are optional: month defaults to the current month, same with year, and rate just wont show a total with your hours worked.

Example   
`Invoice for 2020-08-01 to 2020-08-31`  
`Hours    Description`  
`2.5      Updated template files to reflect theme changes`  
`5.3      Backed up databases and updated Wordpress`  
`Total Hours: 7.8`  
`Total: $959.40`