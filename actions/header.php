<?php

$message = $this->GetMessage();
$user = $this->GetUser();
if ($message) {
    $this->template['msg'] = "alert('" . $message . "');";
}
$this->template['style'] = "<style type=text/css>\n"
                           . ".indent {margin-left: 40px;}\n"
                           . ".additions {color: #008800;}\n"
                           . ".deletions {color: #880000;}\n"
                           . ".error {color: #CC3333; font-weight: bold;}\n"
                           . "BODY {font-family: Verdana;	font-size: 13px;line-height: 1.3;color: #000000;background-color: #F8F8F8;}\n"
                           . "P, TD, LI, INPUT, SELECT, TEXTAREA {font-family: Verdana;font-size: 13px;line-height: 1.3}\n"
                           . "UL, OL {margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;}\n"
                           . "FORM, H1, H2, H3, H4, H5 {margin: 0px;padding: 0px;}\n"
                           . "</style>\n";

$this->template['script'] = "<script language=JavaScript type=text/javascript>\n"
                            . "		function fKeyDown()\n"
                            . "		{\n"
                            . "			if (event.keyCode == 9)\n"
                            . "			{\n"
                            . "				event.returnValue = false;\n"
                            . "				document.selection.createRange().text = String.fromCharCode(9);\n"
                            . "			}\n"
                            . "		}\n"
                            . "		function move(add,del){\n"
                            . "			var key=new Array;\n"
                            . "			var val=new Array;\n"
                            . "			if (del.options.selectedIndex==-1)\n"
                            . "				del.options.selectedIndex=del.options.length-1;\n"
                            . "			if(del.options.length && del.options[del.options.selectedIndex].value!=''){\n"
                            . "				add.options.length=add.options.length+1;\n"
                            . "				add.options[add.options.length-1].value=del.options[del.options.selectedIndex].value;\n"
                            . "				add.options[add.options.length-1].text=del.options[del.options.selectedIndex].text;\n"
                            . "				counter=0;\n"
                            . "				for (var i=0;i<del.options.length;i++){\n"
                            . "					if (!del.options[i].selected){\n"
                            . "						key[counter] = del.options[i].text;\n"
                            . "						val[counter] = del.options[i].value;\n"
                            . "						counter++;\n"
                            . "					}\n"
                            . "				}\n"
                            . "				del.options.length =  del.options.length -1;\n"
                            . "				for (i in key){\n"
                            . "					del.options[i].text  = key[i];\n"
                            . "					del.options[i].value = val[i];\n"
                            . "				}\n"
                            . "			}\n"
                            . "		}\n"
                            . "		function commit(admins,admin){\n"
                            . "			var value='';\n"
                            . "			var flag=true;\n"
                            . "			admin.value='';\n"
                            . "			for (var i=0;i<admins.options.length;i++){\n"
                            . "				if (admins.options[i].value=='*')\n"
                            . "					flag=false;\n"
                            . "			}\n"
                            . "			if (flag){\n"
                            . "				for (var i=0;i<admins.options.length;i++){\n"
                            . "					if (i==admins.options.length-1)\n"
                            . "						admin.value=admin.value+admins.options[i].value;\n"
                            . "					else\n"
                            . "						admin.value=admin.value+admins.options[i].value+'|';\n"
                            . "				}\n"
                            . "			}\n"
                            . "		}\n"
                            . $msg
                            . "	</script>\n";

$this->template['title'] = "<a href='" . XOOPS_URL . "/modules/wakka/HomePage'>" . $this->config['wakka_name'] . "</a> : <a href='" . $this->config['base_url'] . 'TextSearch?phrase=' . $this->GetPageTag() . "'>" . $this->conver_pagename($this->GetPageTag()) . '</a>';
$this->template['navheader'] = '';
if (!$this->xoopsConfig['navheader']) {
    $this->template['navheader'] .= "<a href='" . XOOPS_URL . "'>" . _YOURHOME . '</a> :: ';
}
$this->template['navheader'] .= $this->config['navigation_links'] . ' ' . _MI_USER . $this->Format($this->UserName());
