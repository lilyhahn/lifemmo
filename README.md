Octogenarian -- The Game of Life MMO
=======
## The server
### Dependencies
* MongoDB
* DrowsyDromedary (https://github.com/zuk/DrowsyDromedary)
* PHP  

### First time setup  
`./server.php`  
`init()`   
`cd` to where Drowsy is installed  
`rackup`  

### Running the server
`./server.php`  
`start()`  

### Available commands
`help()`  
`draw(x, y, state)` draws a cell, arguments are ints. State should be 0 or 1  
`generate()` goes forward one generation  
`start()` starts continuously generating. The server will poll events from clients during this time.  
`quit()`  
`setRule(Rule r)` sets the rule. You can make a rule object like this:  
`new Rule(array(2,3), array(3))`  Rule constructor is `__construct(array $s, array $b)`

## The client

### Configuration

* Set scale in client.js to desired value - ideal is size of canvas / size of db
* Set drowsyUrl to appropriate value
