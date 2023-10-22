<?php

class GetContentSizePage {

  var $url;
  var $fullpage;
  var $content;

  function GetContentSizePage($url) {
    $this->url = $url;
  }

  function retrievePage() {

    if(substr($this->url, 0, 7) != "http://") {
      return false;
    }

    $fp = @fopen($this->url, 'r');
    if (!$fp) {
      return false;
    }

    $temp = '';

    while ( !feof($fp) ) {
      $temp .= fgets($fp, 4096);
    }

    fclose ($fp);

    $this->fullpage = $temp;

    return true;
  }

  function determineContent() {
    $content = $this->fullpage;

    $content = preg_replace("/\n/", '', $content);

    $content = preg_replace("/<script.*>.*<\/script>/Ui", "", $content);

    $content = preg_replace("/<style.*>.*<\/style>/Ui", "", $content);

    $content = preg_replace("/(<.* title=\")(.*)(\".*>)/Ui", "$1$3$2", $content);

    $content = preg_replace("/(<.* alt=\")(.*)(\".*>)/Ui", "$1$3$2", $content);

    $content = preg_replace("/(<.* summary=\")(.*)(\".*>)/Ui", "$1$3$2", $content);

    $content = preg_replace('/<!--[^>]*-->/U', '', $content);

    $content = preg_replace('/<[^>]*>/', '', $content);

    $content = preg_replace("/\s+/", ' ', $content);

    $this->content = $content;
  }

  function getFullPageContent() {
    return $this->fullpage;
  }

  function getFullPageSize() {
    return strlen($this->fullpage);
  }

  function getTextContent() {
    return $this->content;
  }

  function getContentSize() {
    return strlen($this->content);
  }

  function getTextPercentage() {
    return ($this->getFullPageSize() == 0) ? 0 : 100 * $this->getContentSize() / $this->getFullPageSize();
  }

}

$page = new GetContentSizePage($adresa);
$page->retrievePage() or die ("Adresu nelze otevøít!");
$page->determineContent();
echo '<p>Celková velikost: ', $page->getFullPageSize() . '</p>';
echo '<p>', $page->getTextPercentage(), ' procent textu!</p>';

?>