package org.sysken.grouper;

import android.app.Application;
import android.app.ProgressDialog;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.widget.Toast;


public class Globals extends Application {
    //グローバルに使用する変数
    public String url_string, registrationId;
    public WebView web;

    public void GlobalsAllinit() {

        url_string = "http://secure-bayou-4662.herokuapp.com/groups";
    }


   public void GlobalsWebview(){
        ProgressDialog loading = new ProgressDialog(this){
            public void onBackPressed() {
                web.stopLoading();
                web.goBack();
                cancel();
                };
        };
    }

}


