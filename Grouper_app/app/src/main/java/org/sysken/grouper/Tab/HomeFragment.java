package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import org.sysken.grouper.R;

public class HomeFragment extends Fragment {

    Globals globals;
    private static final String TAG = "GCMSampleActivity";


    @Override
    public View onCreateView(LayoutInflater inflater,
                             ViewGroup container,
                             Bundle savedinstanceState) {
        globals = (Globals) this.getActivity().getApplication();
        globals.GlobalsAllinit();
        View v = inflater.inflate(R.layout.web, container, false);
        globals.web = (WebView) v.findViewById(R.id.webview);
        WebSettings webSettings = globals.web.getSettings();
        webSettings.setJavaScriptEnabled(true);
        globals.web.setWebViewClient(new WebViewClient());

        globals.web.loadUrl(globals.url_string);


        return v;
    }


}









