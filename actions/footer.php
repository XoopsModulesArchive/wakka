<?php

$this->template['navfooter'] = '<table border=0 cellpadding=0 cellspacing=0>' . $this->FormOpen('', 'TextSearch', 'get') . '<tr><td>';
if ($this->HasAccess('write')) {
    $this->template['navfooter'] .= "<a href='" . $this->href('edit') . "' >" . _MI_EDIT . "</a> ::\n";
}
if ($this->GetPageTime()) {
    $this->template['navfooter'] .= "<a href='" . $this->href('revisions') . "' >" . $this->GetPageTime() . '</a>';
}

$this->template['navfooter'] .= "<a href='" . $this->href('revisions.xml') . "'><img src='" . $this->href('', 'xml/xml.gif') . "' width=36 height=14 style='border: 0px;'></a> ::";

// if this page exists
if ($this->page) {
    // if owner is current user

    if ($this->UserIsOwner()) {
        $this->template['navfooter'] .= _MI_ISOWNER . ' :: <a href="' . $this->href('acls') . '">' . _MI_EDITACLS . '</a> ::';
    } else {
        $owner = $this->GetPageOwner();

        if ($this->xoopsConfig['isadmin'] && $owner) {
            $this->template['navfooter'] .= _MI_OWNER . ': ' . $this->Format($owner) . ': (<a href="' . $this->href('claim') . '">' . _MI_OWNERSHIP . '</a>)';
        } elseif ($owner) {
            $this->template['navfooter'] .= _MI_OWNER . ': ' . $this->Format($owner);
        } elseif ($this->GetUser()) {
            $this->template['navfooter'] .= _MI_NOBODY . ' (<a href="' . $this->href('claim') . '">' . _MI_OWNERSHIP . '</a>)';
        } else {
            $this->template['navfooter'] .= _MI_NOBODY;
        }

        $this->template['navfooter'] .= ' :: ';
    }
}
$this->template['navfooter'] .= "<a href='" . $this->href('referrers') . "'>" . _MI_REFERRERS . '</a> :: ' . _MI_SEARCH . ' : <input name=phrase size=15></td></tr>' . $this->FormClose() . '</table>';

if ($this->GetConfigValue('debug')) {
    $this->template['debug'] = "<span style=\"font-size: 11px; color: #888888\"><strong>Query log:</strong><br>\n";

    foreach ($this->queryLog as $query) {
        $this->template['debug'] .= $query['query'] . ' (' . $query['time'] . ")<br>\n";
    }

    $this->template['debug'] .= '</span>';
}
