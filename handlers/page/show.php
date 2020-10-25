<?php

$this->template['text'] = '';
if ($this->HasAccess('read')) {
    if (!$this->page) {
        $this->template['text'] .= _MI_PAGEMISS;

        if ($this->HasAccess('create')) {
            $this->template['text'] .= sprintf(_MI_PAGECREATE, "<a href='" . $this->href('edit') . "'>", '</a>');
        }
    } else {
        if ('N' == $this->page['latest']) {
            $link = sprintf(_MI_OLDREVISION, "<a href='" . $this->href() . "'>" . $this->GetPageTag() . '</a>');

            $this->template['text'] .= "<div class='revisioninfo'>" . $link . $this->page['time'] . '.</div>';
        }

        // display page

        $this->template['text'] .= $this->Format($this->page['body'], 'wakka');

        // if this is an old revision, display some buttons

        if ($this->HasAccess('write') && ('N' == $this->page['latest'])) {
            $latest = $this->LoadPage($this->tag);

            $this->template['text'] .= '<br>' . $this->FormOpen('edit') . "<input type=hidden name='previous' value='" . $latest['id'] . "'>" . "<input type=hidden name=body value='" . htmlspecialchars($this->page['body'], ENT_QUOTES | ENT_HTML5)
                                       . '>' . '<input type=submit value=' . _EDIT . '>' . $this->FormClose(
                );
        }
    }
} else {
    $this->template['text'] = '<em>' . _NOPERM . '</em>';
}

if ($this->HasAccess('read') && 1 != $this->GetConfigValue('hide_comments')) {
    // load comments for this page

    $comments = $this->LoadComments($this->tag);

    // store comments display in session

    $tag = $this->GetPageTag();

    if (!isset($_SESSION['show_comments'][$tag])) {
        $_SESSION['show_comments'][$tag] = ($this->UserWantsComments() ? '1' : '0');
    }

    switch ($_REQUEST['show_comments']) {
        case '0':
            $_SESSION['show_comments'][$tag] = 0;
            break;
        case '1':
            $_SESSION['show_comments'][$tag] = 1;
            break;
    }

    // display comments!

    $this->template['comments'] = '';

    if ($this->page && $_SESSION['show_comments'][$tag]) {
        // display comments header

        $this->template['comments'] .= "<a name='comments'></a><div class='commentsheader'>" . _MI_COMMENT . "[<a href='" . $this->href('', '', 'show_comments=0') . "'>" . _MI_HIDECOMMENT . '</a>]</div>';

        // display comments themselves

        if ($comments) {
            foreach ($comments as $comment) {
                $this->template['comments'] .= '<a name="' . $comment['tag'] . "\"></a>\n";

                $this->template['comments'] .= "<div class=\"comment\">\n";

                $this->template['comments'] .= $this->Format($comment['body']) . "\n";

                $this->template['comments'] .= "<div class=\"commentinfo\">\n-- " . $this->Format($comment['user']) . ' (' . $comment['time'] . ")\n</div>\n";

                $this->template['comments'] .= "</div><hr>\n";
            }
        }

        // display comment form

        $this->template['comments'] .= "<div class=\"commentform\">\n";

        if ($this->HasAccess('comment')) {
            $this->template['comments'] .= _MI_ADDCOMMENT . ':<br>' . $this->FormOpen('addcomment') . "<textarea name=body rows=6 style='width: 96%'></textarea><br>" . "<input type='submit'  value='" . _MI_SUBMITCOMMENT . "' accesskey=s>" . $this->FormClose();
        }

        $this->template['comments'] .= "</div>\n";
    } else {
        $this->template['comments'] .= "<div class='commentsheader'>";

        switch (count($comments)) {
            case 0:
                $this->template['comments'] .= _MI_NOCOMMENT;
                break;
            case 1:
                $this->template['comments'] .= _MI_ONECOMMENT;
                break;
            default:
                $this->template['comments'] .= sprintf(_MI_MORECOMMENT, count($comments));
        }

        $this->template['comments'] .= '[<a href=' . $this->href('', '', 'show_comments=1#comments') . '>' . _MI_DISPLAYCOMMENT . '</a>]</div>';
    }
}
