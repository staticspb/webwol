# Installation
1. Put contents of "backend" directory to some webserver with PHP
2. Create an API key using "add_api_key.sh"
3. Install and configure script at your Mikrotik router
4. Add installed script to scheduler
5. Initiate Wake-on-LAN when needed using "Request WoL" URL

Make sure to use same "value" for requests as configured in Mikrotik script.

## Add API Key
```./add_api_key {API_KEY}```

## Request WoL
```https://{ENDPOINT_URL}/?api_key={API_KEY}&action=set&name=webwol&value=send_magic_packet```