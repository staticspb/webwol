*Installation*
Make sure to use same "value" for requests as configured in Mikrotik script

**Add API Key**
./add_api_key {API_KEY}

**Request WoL**
https://{ENDPOINT_URL}/?api_key={API_KEY}&action=set&name=webwol&value=send_magic_packet

**Get WoL request status**
https://{ENDPOINT_URL}/?api_key={API_KEY}&action=get&name=webwol

**Get WoL request status and clear it**
https://{ENDPOINT_URL}/?api_key={API_KEY}&action=cut&name=webwol