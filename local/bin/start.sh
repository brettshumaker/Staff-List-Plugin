#!/usr/bin/env bash

set -e

docker-compose up --build --force-recreate -d

./$(dirname "$0")/docker-setup.sh "$@"