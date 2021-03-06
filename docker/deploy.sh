#!/bin/bash

# ensure running bash
if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

# Setup for relative paths.
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT") 
cd $SCRIPTPATH

# load the variables from the relative path
source ../app/.env


# Deploy the databse
docker stop db
docker rm db

docker run -d \
  -p 3306:3306 \
  --env-file ../app/.env \
  --restart=always \
  --name="db" \
  -v $HOME/gitlab-tools/database:/var/lib/mysql \
  mariadb


if [[ $REGISTRY ]]; then
    CONTAINER_IMAGE="`echo $REGISTRY`/`echo $PROJECT_NAME`"
else
    CONTAINER_IMAGE="`echo $PROJECT_NAME`"
fi

# Kill the site if it is already running.
# e.g. we are replacing the container
docker kill $PROJECT_NAME
docker rm $PROJECT_NAME


# Now start our site container.
docker run -d \
  -p 80:80 \
  --env-file ../app/.env \
  --restart=always \
  --name="$PROJECT_NAME" \
  -v $SCRIPTPATH/../app/.env:/var/www/my-site/.env \
  $CONTAINER_IMAGE

