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
|----------------------|----------------| --- | --- | --- |
| Availability         | 100%           | 100% | 100% | 100% |
| Response time (secs) | 3.92 | 9.88 | 18.44 | 37.11 |
| Throughput (MB/sec)  | 23.31 | 22.73 | 21.83 | 15.41 | 
| Longest transaction  | 9.79 | 13.24 | 24.40 | 58.30 |
| Shortest transaction | 1.33 | 1.76 | 3.30 | 13.86 | 
### Probabilistic cache
| Probabilistic cache  | concurrent = 10 | concurrent = 25 | concurrent = 50 | concurrent = 100 |
|----------------------|----------------| --- | --- | --- |
| Availability         | 100%           | 100% | 100% | 100% |
| Response time (secs) | 3.47 | 8.23 | 15.86 | 30.95 |
| Throughput (MB/sec)  | 27.45 | 27.59 | 25.49 | 14.35 | 
| Longest transaction  | 6.45 | 12.21 | 21.58 | 60.38 |
| Shortest transaction | 1.02 | 0.67 | 2.81 | 10.47 | 
