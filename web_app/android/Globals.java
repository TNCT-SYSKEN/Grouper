package com.example.buspo;

import android.app.Application;
import android.webkit.WebView;



public class Globals extends Application {
	//グローバルに使用する変数
	String url_string;
    WebView web;
	public void GlobalsAllinit() {
		url_string = "http://buspo.herokuapp.com/";
	}
}
