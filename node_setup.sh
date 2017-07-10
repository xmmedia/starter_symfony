#!/bin/sh

echo "-- Setting up node"

nvm install
nvm use --delete-prefix --silent

# install local node packages with yarn
yarn

# not sure this is needed, but appears yarn doesn't properly install node-sass
echo "-- Rebuild node-sass"
npm rebuild node-sass --silent

echo "-- node Setup Done"