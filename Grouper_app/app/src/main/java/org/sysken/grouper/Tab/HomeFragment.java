package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import org.sysken.grouper.Globals;
import org.sysken.grouper.R;
import android.util.Log;

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
        webView.setWebViewClient(new ViewClient());
        webView.loadUrl("http://secure-bayou-4662.herokuapp.com/groups");

        globals = (Globals) getActivity().getApplication();

        return v;
    }

    public boolean canGoBack() {
        return  ( webView != null ) && webView.canGoBack();
    }

    public boolean GoBack(){
        webView.goBack();
        return true;
    }


    public final class ViewClient extends WebViewClient {
        @Override
        public void onPageFinished(WebView view, String url) {
            if(url.equals("http://secure-bayou-4662.herokuapp.com/users/sign_in") ||
               url.equals("http://secure-bayou-4662.herokuapp.com/users/sign_up") )
            webView.loadUrl("javascript:document.getElementById('regId').value = '" + globals.registrationId + "';");
            Log.d("gid", globals.registrationId);
        }
    }
}









