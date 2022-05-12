# Setup env configuration

Set up correct IP value for PROJECT_HOST env var, for instance `PROJECT_HOST=127.0.0.5`

Link this IP in /etc/hosts with your local domain:
```bash
$ cat /etc/hosts

##
# Host Database
#
# localhost is used to configure the loopback interface
# when the system is booting.  Do not change this entry.
##
127.0.0.1	localhost
255.255.255.255	broadcasthost
::1             localhost
# ...
127.0.0.5 stress-test.local
```

<details><summary>4.1. Mac users only</summary>
Inside the docker-compose file, we are using the internal network with a lo0 interface (127.x.x.x)
It's automatically supported on *nix machine, but for MacOS, you need some additional steps.

* Copy content bellow into /Library/LaunchDaemons/com.docker_127005_alias.plist

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.docker_127005_alias</string>
    <key>ProgramArguments</key>
    <array>
        <string>ifconfig</string>
        <string>lo0</string>
        <string>alias</string>
        <string>127.0.0.5</string>
    </array>
    <key>RunAtLoad</key>
    <true/>
</dict>
</plist>
```

* Reload LaunchDaemons by restarting the computer or run follow command

```bash
sudo launchctl load /Library/LaunchDaemons/com.docker_127005_alias.plist
```
</details>

# Run tests

## Setup project
```bash
# Run containers
$ make up

# Go inside php container
$ make php

# Install dependency
$ composer install

# Run migrations
$ php setup.php

# Insert ONE value into DB (you can run in many times)
$ php insert.php
```

## Run stress tests
```bash
# Run tests without cache
$ make stest-no-cache
```

```bash
# Run tests without cache
$ make stest-no-cache
```

## Tests results
### Without cache
| NO cache             | concurrent = 10 | concurrent = 25 | concurrent = 50 | concurrent = 100 |
|----------------------|-----------------|-----------------|-----------------|------------------|
| Availability         | 100%            | 100%            | 100%            | 100%             |
| Response time (secs) | 4.82            | 12.16           | 18.88           | 43.20            |
| Throughput (MB/sec)  | 19.60           | 17.79           | 21.52           | 11.05            | 
| Longest transaction  | 8.07            | 17.51           | 26.05           | 57.51            |
| Shortest transaction | 3.23            | 2.46            | 3.80            | 24.75            | 
### Probabilistic cache
| Probabilistic cache  | concurrent = 10 | concurrent = 25 | concurrent = 50 | concurrent = 100 |
|----------------------|-----------------|-----------------|-----------------|------------------|
| Availability         | 100%            | 100%            | 100%            | 100%             |
| Response time (secs) | 4.14            | 8.26            | 14.75           | 29.48            |
| Throughput (MB/sec)  | 22.79           | 18.68           | 29.17           | 18.84            | 
| Longest transaction  | 8.55            | 27.86           | 21.07           | 53.42            |
| Shortest transaction | 1.27            | 0.84            | 3.90            | 8.12             | 

### Monitoring
![Mysql monitoring](/docs/stest-mysql.png?raw=true "Mysql monitoring")
![PHP monitoring](/docs/stest-php.png?raw=true "PHP monitoring")

As you can see from the pictures above, when the cache is enabled, we reduce the load on MySQL and increase the load on PHP (on memory and processor), which is expected because of the logic of working with data (cache parsing and analyzing) is now transferred to the application level
