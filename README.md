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
