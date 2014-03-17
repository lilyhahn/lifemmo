Octogenarian -- The Game of Life MMO
=======
## Setting up the server
### Dependencies
* MongoDB
* DrowsyDromedary (https://github.com/zuk/DrowsyDromedary)
* PHP  

### First time setup  
`php -f server.php`  
`init`  
`^C` once done  
`cd` to where Drowsy is installed  
`rackup`  

### Running the server
`php -f server.php`  
`start`  

## Setting up the client

### Configuration

* Set scale in client.js to desired value - ideal is size of canvas / size of db
* Set drowsyUrl to appropriate value
