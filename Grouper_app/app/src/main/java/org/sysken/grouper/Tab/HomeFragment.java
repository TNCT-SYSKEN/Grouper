package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.app.ProgressDialog;
import android.graphics.Picture;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;

import org.sysken.grouper.R;

public class HomeFragment extends Fragment {

    Globals globals;

    /*
    @Override
    public void onCreate(Bundle saveInstanceState){
        super.onCreate(saveInstanceState);
        setRetainInstance(true);
    }
*/

    public WebView webView;

    @Override
    public View onCreateView(LayoutInflater inflater,
                             ViewGroup container,
                             Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.web, container, false);
        webView = (WebView) v.findViewById(R.id.webview);;
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webView.setWebViewClient(new WebViewClient());
        webView.loadUrl("http://secure-bayou-4662.herokuapp.com/groups");
        return v;
    }

    public boolean canGoBack() {
        return  ( webView != null ) && webView.canGoBack();
    }

    public boolean GoBack(){
        webView.goBack();
        return true;
    }
}









