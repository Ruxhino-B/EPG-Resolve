#!/bin/bash




xml_check_or_delete(){
	if xmllint --format "${file}"
	then
		echo "$file u rregullua eshte ok"
		sleep 2
	else
		mv $file "new_xml/problem/${file##*/}"
	fi
} 

rewrite_xml_file(){
	while read -r line
	do
		tekst=$(echo $line | grep -oP '(?<=name=").*?(?=">)' | grep '"')
		if [[ "${tekst}" == *'"'* ]]
		then
			echo "u gjet urrrrrraaaaaaaa"
			rrjeshti=$(echo $tekst | sed 's/"//g')
			#echo $tekst
			#echo $rrjeshti
			sed -i "s/$tekst/$rrjeshti/g" $file
		fi
	done < $file
	xml_check_or_delete
	#break

}


xml_check(){
	for file in new_xml/inode/*
	#for file in fshi.txt
	do
		tekst=""
		if xmllint --format "${file}"
		then
			echo "ok ${file##*/}"
			#sleep 2
			#reset
		else
			rewrite_xml_file
		fi
	done
}

create_xml_to_inode(){
	for element in new_xml/*.xml
	do
		[[ "$element" == "new_xml/allchannels.xml" || "$element" == "new_xml/channelID.xml" ]] && continue
		#echo $element
		new_name=$(echo $element | sed -e 's/ /_/g')
		#echo "new_xml/inode/${new_name##*/}"
		mv "$element" "new_xml/inode/${new_name##*/}"
		
	done
	xml_check
}

create_xml_to_inode

sleep 5                                         
#merge_all_xml
cat new_xml/inode/*xml >> new_xml/allchannels-with-widcast 
echo "file u be merge"  
sed -i '/<?xml version="1.0" encoding=/d' new_xml/allchannels-with-widcast 
sed -i 's/<WIDECAST_DVB>//g' new_xml/allchannels-with-widcast       
sed -i 's/<\/WIDECAST_DVB>//g' new_xml/allchannels-with-widcast    
echo '<?xml version="1.0" encoding="ISO-8859-1"?>' > new_xml/allchannels.xml   
echo '<WIDECAST_DVB>' >> new_xml/allchannels.xml 
cat new_xml/allchannels-with-widcast >> new_xml/allchannels.xml
echo '</WIDECAST_DVB>' >> new_xml/allchannels.xml 
rm new_xml/allchannels-with-widcast
