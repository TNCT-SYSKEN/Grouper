package org.sysken.grouper.Tab;

import android.app.Application;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.widget.Toast;


public class Globals extends Application {
    //グローバルに使用する変数
    String url_string;
    WebView web;
    public void GlobalsAllinit() {

        url_string = "http://secure-bayou-4662.herokuapp.com/groups";
    }

}


