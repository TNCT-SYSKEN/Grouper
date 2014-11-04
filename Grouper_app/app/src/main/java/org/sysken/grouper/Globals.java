package org.sysken.grouper;

import android.app.Application;
import android.webkit.WebView;

public class Globals extends Application {
    //グローバルに使用する変数
    public String url_string, registrationId, url_talk;
    public WebView web;
    public String title;


    public void GlobalsAllinit() {

        url_string = "http://secure-bayou-4662.herokuapp.com/groups";

        url_talk = "http://secure-bayou-4662.herokuapp.com/groups/talk_index";

        title = null;
    }

}
