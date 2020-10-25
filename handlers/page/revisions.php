<?php

if ($this->HasAccess('read')) {
    // load revisions for this page

    if ($pages = $this->LoadRevisions($this->tag)) {
        $output .= $this->FormOpen('diff', '', 'GET');

        $output .= '<input type="submit" value="' . _MI_SHOWDIFFERENCES . "\">\n";

        $output .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";

        if ($user = $this->GetUser()) {
            $max = $user['revisioncount'];
        } else {
            $max = 20;
        }

        $c = 0;

        foreach ($pages as $page) {
            $c++;

            if (($c <= $max) || !$max) {
                $output .= '<tr>';

                $output .= '<td><input type="radio" name="a" value="' . $page['id'] . '" ' . (1 == $c ? 'checked' : '') . '></td>';

                $output .= '<td><input type="radio" name="b" value="' . $page['id'] . '" ' . (2 == $c ? 'checked' : '') . '></td>';

                $output .= '<td>&nbsp;<a href="' . $this->href('show') . '?time=' . urlencode($page['time']) . '">' . $page['time'] . '</a></td>';

                $output .= '<td>&nbsp;by ' . $this->Format($page['user']) . '</td>';

                $output .= "</tr>\n";
            }
        }

        $output .= "</table><br>\n";

        $output .= '<input type="button" value="' . _CANCEL . "\" onClick=\"document.location='" . $this->href('') . "';\">\n";

        $output .= $this->FormClose() . "\n";
    }

    $this->template['text'] = $output;
} else {
    $this->template['text'] = '<em>' . _NOPERM . '</em>';
}
?>
</p></div>
