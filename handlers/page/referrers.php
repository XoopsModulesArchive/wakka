<?php

if ($global = $_REQUEST['global']) {
    $title     = 'Sites linking to this Wakka (<a href="' . $this->href('referrers_sites', '', 'global=1') . '">see list of domains</a>):';
    $referrers = $this->LoadReferrers();
} else {
    $title     = _MI_PAGELINK
                 . $this->Link($this->GetPageTag())
                 . ($this->GetConfigValue('referrers_purge_time') ? ' (' . _MI_LAST . ' ' . ($this->GetConfigValue('referrers_purge_time') == 1 ? '24 ' . _MI_HOURS : $this->GetConfigValue('referrers_purge_time') . ' days') . ')' : '')
                 . ' (<a href="'
                 . $this->href('referrers_sites')
                 . '">'
                 . _MI_LISTDOMAIN
                 . '</a>):';
    $referrers = $this->LoadReferrers($this->GetPageTag());
}

$this->template['text'] .= "<strong>$title</strong><br><br>\n";
if ($referrers) {
    {
        $this->template['text'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
        foreach ($referrers as $referrer) {
            $this->template['text'] .= '<tr>';
            $this->template['text'] .= '<td width="30" align="right" valign="top" style="padding-right: 10px">' . $referrer['num'] . '</td>';
            $this->template['text'] .= '<td valign="top"><a href="' . $referrer['referrer'] . '">' . $referrer['referrer'] . '</a></td>';
            $this->template['text'] .= "</tr>\n");
		}
        $this->template['text'] .= "</table>\n";
    }
} else {
    $this->template['text'] = '<em>' . _NOPERM . '</em>';
}

if ($global) {
    $this->template['text'] .= '<br>[<a href="' . $this->href('referrers_sites') . '">View referring sites for ' . $this->GetPageTag() . ' only</a> | <a href="' . $this->href('referrers') . '">View referrers for ' . $this->GetPageTag() . ' only</a>]';
} else {
    $this->template['text'] .= '<br>[<a href="' . $this->href('referrers_sites', '', 'global=1') . '">View global referring sites</a> | <a href="' . $this->href('referrers', '', 'global=1') . '">View global referrers</a>]';
}


