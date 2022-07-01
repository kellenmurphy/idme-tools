#!/bin/bash
#TODO: make compatible with idp

USAGE="usage: ./fetch_metadata.sh https://sp.example.org/Shibboleth.sso/Metadata"
if [ "$#" -eq  "0" ]
then
  echo $'\nERROR: missing metadata URL! \n'$USAGE $'\n'
  exit 1
else
  URL=$1
  REGEX='(https?)://[-A-Za-z0-9\+&@#/%?=~_|!:,.;]*[-A-Za-z0-9\+&@#/%=~_|]'
  if [[ $URL =~ $REGEX ]]
    then
      XML=`curl -k -s $1 2>&1  | sed 's/<!--/\x0<!--/g;s/-->/-->\x0/g' | grep -zv '^<!--' | tr -d '\0' | xmllint --format -`
      name=`echo "$XML" | xmlstarlet sel -T -t -v "/md:EntityDescriptor/md:SPSSODescriptor/md:AssertionConsumerService[@Binding='urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST']/@Location" | cut -d'/' -f3`
      echo "$XML" > $name-metadata.xml
      echo File output to: $name-metadata.xml $'\n'
  else
    echo $'\nERROR: invalid metadata URL! \n'$USAGE $'\n'
    exit 1
  fi
fi
