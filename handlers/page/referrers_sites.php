<?php

if ($global = $_REQUEST['global']) {
    $title = 'Domains/sites linking to this Wakka (<a href="' . $this->href('referrers', '', 'global=1') . '">see list of different URLs</a>):';

    $referrers = $this->LoadReferrers();
} else {
    $title = 'Domains/sites pages linking to '
                 . $this->Link($this->GetPageTag())
                 . ($this->GetConfigValue('referrers_purge_time') ? ' (last ' . (1 == $this->GetConfigValue('referrers_purge_time') ? '24 hours' : $this->GetConfigValue('referrers_purge_time') . ' days') . ')' : '')
                 . ' (<a href="'
                 . $this->href('referrers')
                 . '">see list of different URLs</a>):';

    $referrers = $this->LoadReferrers($this->GetPageTag());
}

$this->template['text'] .= "<strong>$title</strong><br><br>\n";
if ($referrers) {
    for ($a = 0, $aMax = count($referrers); $a < $aMax; $a++) {
        $temp_parse_url = parse_url($referrers[$a]['referrer']);

        $temp_parse_url = ('' != $temp_parse_url['host']) ? mb_strtolower(preg_replace("/^www\./Ui", '', $temp_parse_url['host'])) : 'unknown';

        if (isset($referrer_sites[(string)$temp_parse_url])) {
            $referrer_sites[(string)$temp_parse_url] += $referrers[$a]['num'];
        } else {
            $referrer_sites[(string)$temp_parse_url] = $referrers[$a]['num'];
        }
    }

    array_multisort($referrer_sites, SORT_DESC, SORT_NUMERIC);

    reset($referrer_sites);

    $this->template['text'] .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";

    foreach ($referrer_sites as $site => $site_count) {
        $this->template['text'] .= '<tr>';

        $this->template['text'] .= "<td width=\"30\" align=\"right\" valign=\"top\" style=\"padding-right: 10px\">$site_count</td>";

        $this->template['text'] .= '<td valign="top">' . (('unknown' != $site) ? "<a href=\"http://$site\">$site</a>" : $site) . '</td>';

        $this->template['text'] .= "</tr>\n";
    }

    $this->template['text'] .= "</table>\n";
} else {
    $this->template['text'] .= "<em>None</em><br>\n";
}

if ($global) {
    $this->template['text'] .= '<br>[<a href="' . $this->href('referrers_sites') . '">View referring sites for ' . $this->GetPageTag() . ' only</a> | <a href="' . $this->href('referrers') . '">View referrers for ' . $this->GetPageTag() . ' only</a>]';
} else {
    $this->template['text'] .= '<br>[<a href="' . $this->href('referrers_sites', '', 'global=1') . '">View global referring sites</a> | <a href="' . $this->href('referrers', '', 'global=1') . '">View global referrers</a>]';
}
