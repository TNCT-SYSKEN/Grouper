package org.sysken.grouper;

import android.app.Application;
import android.webkit.WebView;



public class Globals extends Application {
    //グローバルに使用する変数
    String url_string;
    WebView web;
    public void GlobalsAllinit() {
        url_string = "http://secure-bayou-4662.herokuapp.com/";
    }
}
