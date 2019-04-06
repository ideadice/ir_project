#!/bin/bash

homeDir="/var/www/html/master/public/Investor_Relations/data/updatePosts/"


xmlOutput=(
'delekdrilling_rss.xml'
)

apiCalls=(
'https://www.delekdrilling.co.il/en/rss/delek/announcements.xml'
)

for ((i=0;i<${#xmlOutput[@]};++i)); do
        :
	wget --header="Accept: text/html" --user-agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0" --output-document=$homeDir/${xmlOutput[$i]} "${apiCalls[$i]}"
	#chmod 755 $homeDir/${xmlOutput[$i]}
done
exit 0
