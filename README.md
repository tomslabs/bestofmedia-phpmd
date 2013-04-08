Ant Build Commons are nice but not documented.

## How to build and install a package ?

<pre>
ant clean package
sudo pear upgrade --alldeps build/dist/PHP_CodeSniffer_Standards_BestOfMedia-X.Y.Zsnapshot1234567890.tgz
</pre>

## How to configure remote host for Pirum ?

You need to store credential outside of the project
in your home directory since we don't want to publish
our credentials on GitHub.

Simply edit:
~/.ant-build-commons/global.properties

<pre>
remote.shell.credentials = yourusername@yourremothostname
pirum.root = /path/to/yourpirumdir
</pre>

Ensure that you can connect passwordlessly on pirum using:
ssh yourusername@yourremothostname

You can use ssh-copy-id for that.

## How to create a release ?

Prepare changelogs.
<pre>
./src/main/resources/extensions/changes/changelog.php
ant changes:log changes:manifest
ant scm:commit -Dscm.message="<Release message>"
</pre>

Then hit the red button.
<pre>
ant clean package:release pirum scm:tag
</pre>

You can now start a new release version
<pre>
ant clean version:version scm:commit -Dscm.message="New version"
</pre>

## How to create a snapshot and release it ?

<pre>
ant clean package pirum
</pre>