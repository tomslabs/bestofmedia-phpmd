#!/usr/bin/php
<?php

$changes = realpath(dirname(__FILE__)."/../../../../site/docx/changes.xml");
echo "Altering $changes\n";

$xml = simplexml_load_file($changes);
unset($xml->body);
$body = $xml->addChild('body');

$tags = array();
exec('git tag -l | sort -u -r', $tags, $ret);

$next = null;
foreach($tags as $tag) {
  if($next) {
    $current = $next;
  } else {
    $current = 'current';
  }

  $release = gitTagAsReleaseXml($body, $tag);
  $gitpager = array();
  exec("git log --no-merges --format=\"%H\" $tag..$next", $gitpager);

  foreach($gitpager as $hash) {
    gitLogAsActionXml($release, $hash);
  }
  $next = $tag;
}

$first=exec('git tag -l | head -1');

$release = gitTagAsReleaseXml($body, $first);

exec("git log --no-merges --format=\"%H\" $first", $gitpager);
foreach($gitpager as $hash) {
  gitLogAsActionXml($release, $hash);
}

function gitTagAsReleaseXml(SimpleXMLElement $body, $tag) {
  $text = array();
  exec("git log -1 --tags --format=\"%s\" $tag", $text);
  $text = wordwrap(implode("\n", $text), 60);

  $date = exec("git log -1 --tags --format=\"%ai\" $tag");

  $release = $body->addChild('release');
  $release->addAttribute('version', $tag);
  $release->addAttribute('date', $date);
  $release->addAttribute('description', $text) ;

  echo "[$tag]\n$text\n\n";

  return $release;
}

function gitLogAsActionXml(SimpleXMLElement $release, $hash) {
  $text = array();
  exec("git log -1 --format=\"%s\" $hash", $text);
  $text = wordwrap(implode("\n", $text), 68, "\n", true);

  $shorthash = exec("git log -1 --format=\"%h\" $hash");
  $author = exec("git log -1 --format=\"%an\" $hash");

  $action = $release->addChild('action', $text);
  $action->addAttribute('date', $shorthash);
  $action->addAttribute('dev', $author);
  $action->addAttribute('type', 'fix');

  echo "  *  $text\n";
}

$xml->asXML($changes);