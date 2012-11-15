#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

pear package-validate package.xml
VERSION=$( xmllint --xpath "string(/*[name()='package']/*[name()='version']/*[name()='release'])" package.xml )

mkdir -p PHP_PMD_BestOfMedia-$VERSION
cp -R resources PHP_PMD_BestOfMedia-$VERSION
tar -zcvf PHP_PMD_BestOfMedia-$VERSION.tgz package.xml PHP_PMD_BestOfMedia-$VERSION
rm -Rf PHP_PMD_BestOfMedia-$VERSION