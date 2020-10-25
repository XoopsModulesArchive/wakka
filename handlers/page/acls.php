<?php

if ($this->UserIsOwner()) {
    if ($_POST) {
        // store lists

        $this->SaveAcl($this->GetPageTag(), 'read', $_POST['read_acl']);

        $this->SaveAcl($this->GetPageTag(), 'write', $_POST['write_acl']);

        $this->SaveAcl($this->GetPageTag(), 'comment', $_POST['comments_acl']);

        if ('' != $_POST['newowner']) {
            $this->SetPageOwner($this->GetPageTag(), $_POST['newowner']);
        }

        $message = _MI_MODMESSAGE;

        // redirect back to page

        $this->SetMessage($message . '!');

        $this->Redirect($this->Href());
    } else {
        // load acls

        $memberHandler = xoops_getHandler('member');

        $groupobjs = $memberHandler->getGroups();

        foreach ($groupobjs as $group) {
            $groups[$group->getVar('groupid')] = $group->getVar('name');
        }

        $ACL1 = $this->LoadAcl($this->GetPageTag(), 'read');

        $readacls = explode('|', $ACL1['list']);

        foreach ($readacls as $v) {
            if ('' != $v && '*' != $v) {
                $readACL .= "<option value='" . $v . "'>" . $groups[$v] . "</option>\n";
            }
        }

        $ACL2 = $this->LoadAcl($this->GetPageTag(), 'write');

        $writeacls = explode('|', $ACL2['list']);

        foreach ($writeacls as $v) {
            if ('' != $v && '*' != $v) {
                $writeACL .= "<option value='" . $v . "'>" . $groups[$v] . "</option>\n";
            }
        }

        $ACL3 = $this->LoadAcl($this->GetPageTag(), 'comment');

        $commentsacls = explode('|', $ACL3['list']);

        foreach ($commentsacls as $v) {
            if ('' != $v && '*' != $v) {
                $commentsACL .= "<option value='" . $v . "'>" . $groups[$v] . "</option>\n";
            }
        }

        // show form

        $this->template['text'] .= '<h3>'
                                   . _MI_ACLSLIST
                                   . ' '
                                   . $this->Link($this->GetPageTag())
                                   . '</h3><br>'
                                   . $this->FormOpen('acls')
                                   . "<div style='width:290'><table border=0 cellspacing=0 cellpadding=0>"
                                   . '<tr><td colspan=3><strong>'
                                   . _MI_READACL
                                   . ':</strong><br></td></tr>'
                                   . "<tr><td valign=top style='padding-right: 20px'><input type=hidden name='read_acl'>"
                                   . "<select name='readacl' size=7 multiple style='width:100px'>"
                                   . $readACL
                                   . '</select></td><td><br><br>'
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_REMOVE
                                   . "' onclick='move(this.form.group1,this.form.readacl)'><br><br>"
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_ADD
                                   . "' onclick='move(this.form.readacl,this.form.group1)'></td><td>"
                                   . "<select name='group1' size=7 multiple style='width:100px'>";

        foreach ($groups as $groupid => $name) {
            if ((!is_array($readacls) || !in_array($groupid, $readacls, true)) && XOOPS_GROUP_USERS != $groupid && XOOPS_GROUP_ANONYMOUS != $groupid) {
                $this->template['text'] .= "<option value='" . $groupid . "'>" . $name . "</option>\n";
            }
        }

        $this->template['text'] .= '</select></td></tr>'
                                   . '<tr><td colspan=3><strong>'
                                   . _MI_WRITEACL
                                   . ':</strong><br></td></tr>'
                                   . "<tr><td valign=top style='padding-right: 20px'><input type=hidden name='write_acl'>"
                                   . "<select name='writeacl' size=7 multiple style='width:100px'>"
                                   . $writeACL
                                   . '</select></td><td><br><br>'
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_REMOVE
                                   . "' onclick='move(this.form.group2,this.form.writeacl)'><br><br>"
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_ADD
                                   . "' onclick='move(this.form.writeacl,this.form.group2)'></td><td>"
                                   . "<select name='group2' size=7 multiple style='width:100px'>";

        foreach ($groups as $groupid => $name) {
            if ((!is_array($readacls) || !in_array($groupid, $writeacls, true)) && XOOPS_GROUP_USERS != $groupid && XOOPS_GROUP_ANONYMOUS != $groupid) {
                $this->template['text'] .= "<option value='" . $groupid . "'>" . $name . "</option>\n";
            }
        }

        $this->template['text'] .= '</select></td></tr>'
                                   . '<tr><td colspan=3><strong>'
                                   . _MI_COMMENTSACL
                                   . ':</strong><br></td></tr>'
                                   . "<tr><td valign=top style='padding-right: 20px'><input type=hidden name='comments_acl'>"
                                   . "<select name='commentsacl' size=7 multiple style='width:100px'>"
                                   . $comments_acl
                                   . '</select></td><td><br><br>'
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_REMOVE
                                   . "' onclick='move(this.form.group3,this.form.commentsacl)'><br><br>"
                                   . "<input type=button style='width:90px'  value='"
                                   . _MI_ADD
                                   . "' onclick='move(this.form.commentsacl,this.form.group3)'></td><td>"
                                   . "<select name='group3' size=7 multiple style='width:100px'>";

        foreach ($groups as $groupid => $name) {
            if ((!is_array($readacls) || !in_array($groupid, $commentsacls, true)) && XOOPS_GROUP_USERS != $groupid && XOOPS_GROUP_ANONYMOUS != $groupid) {
                $this->template['text'] .= "<option value='" . $groupid . "'>" . $name . "</option>\n";
            }
        }

        $this->template['text'] .= '</select></td></tr>' . "<tr><td colspan='3'><hr></td></tr><tr><td colspan=1><strong>" . _MI_SETOWNER . ':</strong></td>' . "<td colspan=2><select name=newowner><option value=''>" . _MI_NOCHANGE . '</option>';

        if ($users = $this->LoadUsers()) {
            foreach ($users as $user) {
                $this->template['text'] .= '<option value="' . $user['uname'] . '">' . $user['uname'] . "</option>\n";
            }
        }

        $this->template['text'] .= '</select></td></tr><tr><td colspan=3><br>'
                                   . '<input type=button value='
                                   . _MI_STOREACLS
                                   . " style='width: 120px' accesskey='s' onclick='commit(this.form.readacl,this.form.read_acl);commit(this.form.writeacl,this.form.write_acl);commit(this.form.commentsacl,this.form.comments_acl);this.form.submit();'>"
                                   . '<input type=button value='
                                   . _CANCEL
                                   . " onClick='history.back();' style='width: 120px'></td></tr></table></div>"
                                   . $this->FormClose();
    }
} else {
    $this->template['text'] = '<em>' . _NOPERM . '</em>';
}

?>
</p></div>
